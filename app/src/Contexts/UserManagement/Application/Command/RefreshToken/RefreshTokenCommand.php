<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Application\Command\RefreshToken;


# Only takes email, because email is marked as unque in the db
# that is, it can be used as primary key, giving identity to the object. 
class RefreshTokenCommand {

    private function __construct(public string $api_token){}

    public static function create(string $api_token): self {
        return new self($api_token);
    }

}