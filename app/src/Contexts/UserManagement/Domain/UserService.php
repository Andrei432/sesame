<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Domain; 


# Domain service is allowed to use Infrastructure-level dependencies like Repository Interface.
class UserService {

    public function __construct( private UserRepositoryInterface $userRepository) {}

    public function register(User $user): void
    {
        $this->userRepository->save($user);
    }

}



