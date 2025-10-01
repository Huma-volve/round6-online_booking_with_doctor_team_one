<?php
namespace App\Repositories\Interfaces;
use App\Models\User;
interface RegisterRepositoryInterface
{
    public function create(array $data): User;
}