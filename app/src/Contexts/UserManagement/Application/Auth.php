<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Application; 

use App\Contexts\UserManagement\Domain\UserService; 


use App\Contexts\UserManagement\Domain\User; 

class Auth {

    private ?User $current_user;

    public function __construct(private UserService $userService) {}


    # Support for various login methods.
    public function login(?string $email=null, ?string $password=null, ?string $api_token = null) : bool
    {   
        # Cache the user first: 
        if ($api_token !== null) {
            $this->current_user = $this->userService->login(api_token: $api_token);
        } else if ($email !== null && $password !== null) {
            $this->current_user = $this->userService->login(email: $email, password: $password);
        }

        return $this->current_user != null;
    }
    
    public function getAuthenticatedUser() : ?User
    {
        return $this->current_user;
    }

}