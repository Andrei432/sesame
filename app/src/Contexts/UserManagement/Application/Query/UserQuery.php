<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Application\Query;

use App\Contexts\UserManagement\Domain\UserRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class UserQuery
{
    public function __construct(private UserRepositoryInterface $userRepository) {} 

    public function userExistsByToken(string $token): bool {
        return $this->userRepository->userExistsByToken($token);
    }

    public function getUser(string $email, string $password): ?UserDTO {
        $user = $this->userRepository->getUser(email: $email, password: $password);
       

        if ($user === null) 
            return null;

        return UserDTO::create(
            email: $user->getEmail(),
            password: $user->getPassword(),
            api_token: $user->getApiToken(), 
            role: $user->getRole()
        ); 
    }

    public function getUserId(string $email): Uuid
     {
        return $this->userRepository->getUserId(email: $email); 
    }


}