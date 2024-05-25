<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Domain; 

class Token {

    private string $token_string; 

    private function __construct() {
        $this->token_string = bin2hex(random_bytes(16));
    } 

    public static function generate(): self {
        return new self();
    }

    public function getTokenString(): string {
        return $this->token_string;
    }
}