<?php

namespace App\Repositories\Contracts;

use App\Models\Specialty;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface MajorRepositoryInterface
{
    public function getAll(): LengthAwarePaginator;
    public function find(int $id): ?Specialty;
    public function findBySlug(string $slug): ?Specialty;
}
