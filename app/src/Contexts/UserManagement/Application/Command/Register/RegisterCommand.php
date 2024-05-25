<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Application\Command\Register;

use App\Contexts\UserManagement\Domain\Token;

# This acts as a register for carrying data for state mutations
# we don't worry about encapsulation.  
class RegisterCommand 
{
    private function __construct(
        public string $email,
        public string $password, 
        public string $name,
        public string $api_token
    ){}
    
    # This is a good place to generate the api token. 
    # We dont polute DTOs or Domain objects, which are used for data in and and out, 
    # and since, this is random string, potentially new tokens are generated 
    # and extra logic would be needed to keep the behaviour as expected. 
    public static function create(string $email, string $password, string $name): self {
        return new self($email, $password, $name, api_token: Token::generate()->getTokenString());
    }
}