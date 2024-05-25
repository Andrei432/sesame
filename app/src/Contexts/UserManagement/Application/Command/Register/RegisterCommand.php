<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Application\Command\Register; 

# This acts as a register for carrying data for state mutations
# we don't worry about encapsulation.  
class RegisterCommand 
{
    private function __construct(
        public string $email,
        public string $password, 
        public string $name, 
    ){}

    public static function create(string $email, string $password, string $name): self {
        return new self($email, $password, $name);
    }
}