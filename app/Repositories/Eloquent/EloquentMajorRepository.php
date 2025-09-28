<?php


// app/Repositories/Eloquent/EloquentMajorRepository.php
namespace App\Repositories\Eloquent;

use App\Models\Specialty;
use App\Repositories\Contracts\MajorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentMajorRepository implements MajorRepositoryInterface
{
    protected Specialty $model;

    public function __construct(Specialty $model)
    {
        $this->model = $model;
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id): ?Specialty
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?Specialty
    {
        return $this->model->where('slug', $slug)->first();
    }
}