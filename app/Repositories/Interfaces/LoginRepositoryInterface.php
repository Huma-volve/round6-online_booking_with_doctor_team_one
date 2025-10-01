<?php
namespace App\Repositories\Interfaces;
interface LoginRepositoryInterface
{
    public function findByEmail(string $email);
    public function checkPassword($user,string $password):bool;
    public function createToken($user): string;
    public function findByPhone(string $phone);
}