<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Application\Command\Register; 

use RuntimeException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


# DDD Concept: Application services are allwed to know about Domain. 
use App\Contexts\UserManagement\Domain\UserService;
use App\Contexts\UserManagement\Domain\User; 
use App\Contexts\UserManagement\Domain\Exception\InvalidEmailException;


#[AsMessageHandler]
class RegisterHandler  {

    public function __construct(private UserService $user_service) {}

    # User type covariance
    public function __invoke(RegisterCommand $command): void
    {   
        try {
            $user = User::create(email: $command->email, password: $command->password, name: $command->name);
            $this->user_service->register($user);

        } catch (InvalidEmailException $e) {
            throw new RuntimeException($e->getMessage());
        } catch (\Exception $e) { 
            throw new RuntimeException($e->getMessage());
        }

    }
        
}

