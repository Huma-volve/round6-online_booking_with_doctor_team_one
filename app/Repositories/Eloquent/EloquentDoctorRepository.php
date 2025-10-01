<?php
// app/Repositories/Eloquent/EloquentDoctorRepository.php
namespace App\Repositories\Eloquent;

use App\Models\Doctor;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentDoctorRepository implements DoctorRepositoryInterface
{
    protected int $perPage = 10; // Default items per page
    protected float $earthRadius = 6371;  // radius in km  or 3959 in mile

    public function getAllDoctors(array $filters = []): LengthAwarePaginator
    {
        return $this->applyFilters(Doctor::query(), $filters)->paginate($this->perPage);
    }

    public function findDoctor(int $id): ?Doctor
    {
        return Doctor::with(['specialties', 'hospitals'])->find($id);
    }

    public function search(string $majorSlug, array $filters = []): LengthAwarePaginator
    {
        $query = Doctor::whereHas('specialties', function (Builder $query) use ($majorSlug) {
            $query->where('slug', $majorSlug);
        });

        return $this->applyFilters($query, $filters)->paginate($this->perPage);
    }

    /**
     * Apply various filters to the doctor query.
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        $defaultRadius = 30;
        // Search by Doctor name
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Search by Specialist type (via major name/slug)
        if (isset($filters['specialist'])) {
            $query->whereHas('specialties', function (Builder $subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['specialist'] . '%')
                         ->orWhere('slug', 'like', '%' . $filters['specialist'] . '%');
            });
        }

        // Search by Location (Hospital city/address)
        if (isset($filters['location'])) {
            $query->whereHas('hospitals', function (Builder $subQuery) use ($filters) {
                $subQuery->where('city', 'like', '%' . $filters['location'] . '%')
                         ->orWhere('address', 'like', '%' . $filters['location'] . '%');
            });
        }

        // Filter by Rating
        if (isset($filters['min_rating'])) {
            $query->where('rating', '>=', (float) $filters['min_rating']);
        }
        if (isset($filters['max_rating'])) {
            $query->where('rating', '<=', (float) $filters['max_rating']);
        }
        // Location Search by Latitude, Longitude, and Radius
        if (isset($filters['latitude']) && isset($filters['longitude'])){
            $searchLat = (float) $filters['latitude'];
            $searchLong = (float) $filters['longitude'];
            $radius = (!empty($filters['radius'])) 
                ? (float) $filters['radius'] 
                : $defaultRadius;

            $query->whereHas('hospitals', function (Builder $subQuery) use ($searchLat, $searchLong, $radius) {
                $haversine = "(
                    {$this->earthRadius} * acos(
                        cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )
                )";

                $subQuery->select()->selectRaw("{$haversine} AS distance", 
                [$searchLat, $searchLong, $searchLat])->having('distance', '<=', $radius);
            });

            // Optional: Order by distance (requires joining hospitals to the main doctor query)
            // This is more complex if you have multiple hospitals per doctor and want the *closest* hospital's distance.
            // For simplicity, for now, we'll just filter. If you need ordering by distance,
            // you might need to eager load and then sort in PHP, or do a more complex subquery/join.
            // Example of how to add distance to the main query if you only cared about *one* hospital per doctor
            // Or if you want to select doctors and show the distance to their closest hospital.
            // This requires a more advanced join/subquery or moving calculation to a service layer.
            /*
            $query->select('doctors.*')
                ->selectSub(function ($sub) use ($searchLat, $searchLong) {
                    $haversine = "(
                        {$this->earthRadius} * acos(
                            cos(radians(?)) * cos(radians(hospitals.latitude)) * cos(radians(hospitals.longitude) - radians(?)) +
                            sin(radians(?)) * sin(radians(hospitals.latitude))
                        )
                    )";
                    $sub->selectRaw("MIN({$haversine})", [$searchLat, $searchLong, $searchLat])
                        ->from('doctor_hospital')
                        ->join('hospitals', 'doctor_hospital.hospital_id', '=', 'hospitals.id')
                        ->whereColumn('doctor_hospital.doctor_id', 'doctors.id');
                }, 'min_distance')
                ->with(['majors', 'hospitals'])
                ->orderBy('min_distance'); // Order by the closest hospital's distance
            */
        }
        // ---- END NEW LOCATION SEARCH ----

        // Filter by Availability (This is more complex and depends on your schedule setup)
        // For simplicity, let's assume doctors have a general availability (e.g., 'morning', 'afternoon', 'evening')
        // Or, more realistically, you'd join with a `schedules` or `appointments` table.
        // For now, I'll add a placeholder. You'd need a `DoctorSchedule` model/table.
        /*
        if (isset($filters['availability'])) {
            $query->whereHas('schedules', function (Builder $subQuery) use ($filters) {
                $subQuery->where('day_of_week', $filters['day'])
                         ->where('start_time', '<=', $filters['time'])
                         ->where('end_time', '>=', $filters['time']);
            });
        }
        */

        // Order results
        if (isset($filters['sort_by'])) {
            $order = isset($filters['sort_order']) && in_array(strtolower($filters['sort_order']), ['asc', 'desc'])
                     ? $filters['sort_order'] : 'asc';
            $query->orderBy($filters['sort_by'], $order);
        } else {
            // Default sort
            $query->orderBy('rating', 'desc');
        }


        // Eager load relationships for efficiency
        $query->with(['specialties', 'hospitals']);

        return $query;
    }
}