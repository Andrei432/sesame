<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Domain; 

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function getToken(string $email): ?string;

    public function userExistsByToken(string $token): bool; 

    public function getUser(string $email, string $password): ?User;

}

