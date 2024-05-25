<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Domain; 

# Domain service is allowed to use Infrastructure-level dependencies like Repository Interface.

use App\Contexts\UserManagement\Domain\Exception\UserAlreadyExistsException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException; 

# TODO: Push events to the event bus. 
class UserService {

    public function __construct( private UserRepositoryInterface $userRepository) {}

    public function register(User $user): void
    {   
        try {
            $this->userRepository->save($user);
        } catch (UniqueConstraintViolationException $e) {
            throw new UserAlreadyExistsException("User already exists: " . $user->getEmail());
        }
    }
    public function logout(string $token): void {

        $this->userRepository->refreshToken($token);
    }

    public function login(?string $email=null, ?string $password=null, ?string $api_token=null): ?User
    {
        if ($api_token !== null) {
            return $this->userRepository->getUserByApiToken($api_token);
        } else if ($email !== null && $password !== null) {
            return $this->userRepository->getUser($email, $password);
        }
        return $this->userRepository->getUser($email, $password);
    }

}



