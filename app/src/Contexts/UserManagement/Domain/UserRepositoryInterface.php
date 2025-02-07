<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Domain; 

use Symfony\Component\Uid\Uuid;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function getToken(string $email): ?string;

    public function userExistsByToken(string $token): bool; 

    public function getUser(string $email, string $password): ?User;

    public function getUserByApiToken(string $token): ?User;

    public function refreshToken(string $email): void;

    public function getUserId(string $email): ?Uuid;

    public function getUserIdByApiToken(string $token): ?Uuid;
    
}

