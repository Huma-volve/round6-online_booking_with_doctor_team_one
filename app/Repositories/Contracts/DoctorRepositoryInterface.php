<?php

namespace App\Repositories\Contracts;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DoctorRepositoryInterface
{
    public function getAllDoctors(array $filters = []): 
    LengthAwarePaginator; // Or Collection if not paginating
    public function findDoctor(int $id): ?Doctor;
    public function search(string $majorSlug, 
    array $filters = []): LengthAwarePaginator;
}
