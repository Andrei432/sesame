<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Domain; 


use App\Entity\User as DoctrineUser;

use App\Contexts\UserManagement\Domain\Exception\InvalidEmailException;
use Symfony\Component\Uid\Uuid;

# Domain UserEntity. 
# Basic logic like field validation is allowed in Domain Entities. 
class User {

    const ROLE_ADMIN = 1;
    const ROLE_USER = 0;

    private function __construct(
        private string $email, 
        private string $password, 
        private string $name, 
        private string $api_token, 
        private int $role, 
    )
    {}

    public static function create(string $email, string $password, string $name, string $api_token, int $role = self::ROLE_USER): self {
        # Hash password and generate api_token
        # Use named parameters for expresiveness. 
        # Validate email: 

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("The email: $email is not valid.");
        }

        return new self(
            email: $email, 
            password: hash('sha256', $password),
            name: $name, 
            api_token: $api_token,
            role: $role
        );
    }

    public static function fromDoctrineUser(DoctrineUser $doctrine_user): self {
        return self::create(
            email: $doctrine_user->getEmail(),
            password: $doctrine_user->getPassword(),
            name: $doctrine_user->getName(),
            api_token: $doctrine_user->getApiToken(),
            role: $doctrine_user->getRole()
        ); 
    }
    

    public function getEmail(): string {
        return $this->email;
    }
    public function getPassword(): string {
        return $this->password;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getApiToken(): string {
        return $this->api_token;
    }

    public function getRole(): int {
        return $this->role;
    }

    public function isAdminRole(): bool {
        return  $this->role === self::ROLE_ADMIN;
    }

    public function isUserRole(): bool {
        return  $this->role === self::ROLE_USER;
    }

}

