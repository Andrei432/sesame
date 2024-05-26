<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Domain; 

# Domain service is allowed to use Infrastructure-level dependencies like Repository Interface.

use App\Contexts\UserManagement\Application\Query\UserQuery;
use App\Contexts\UserManagement\Domain\Exception\UserAlreadyExistsException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Messenger\MessageBusInterface;

use App\Events\ConcreteEvents\User\UserCreatedEvent;
use App\Events\ConcreteEvents\User\UserLoginEvent;
use App\Events\ConcreteEvents\User\UserLogoutEvent;

# TODO: Push events to the event bus. 
class UserService {

    public function __construct(
        private UserRepositoryInterface $userRepository, 
        private MessageBusInterface $eventBus, 
        private UserQuery $userQuery
    ){}

    public function register(User $user): void
    {   
        try {
            $this->userRepository->save($user);
        } catch (UniqueConstraintViolationException $e) {
            throw new UserAlreadyExistsException("User already exists: " . $user->getEmail());
        } finally {
            $this->eventBus->dispatch(UserCreatedEvent::create());            
        }
        
    }
    public function logout(string $token): void {

        $this->userRepository->refreshToken($token);

        $this->eventBus->dispatch(UserLogoutEvent::create());

    }

    public function login(?string $email=null, ?string $password=null, ?string $api_token=null): ?User
    {
        if ($api_token !== null) 
            $user =  $this->userRepository->getUserByApiToken($api_token);
        else if ($email !== null && $password !== null) 
            $user = $this->userRepository->getUser($email, $password);

        if ($user)
            $this->eventBus->dispatch(UserLoginEvent::create());

        return $user;
        
    }
}



