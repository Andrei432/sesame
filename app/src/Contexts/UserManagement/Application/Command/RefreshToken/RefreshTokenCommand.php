<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Application\Command\RefreshToken;

class RefreshTokenCommand {

    private function __construct(private string $email){}

    public static function create(string $email): self {
        return new self($email);
    }

    public function getEmail(): string {
        return $this->email;
    }
    
}