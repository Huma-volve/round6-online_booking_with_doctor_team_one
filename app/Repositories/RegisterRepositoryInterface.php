<?php
namespace App\Repositories;
use App\Models\User;
interface RegisterRepositoryInterface
{
    public function create(array $data): User;
}