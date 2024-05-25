<?php declare(strict_types=1);


namespace App\Contexts\UserManagement\Application\Query;

# DTO only contains properties that are needed when we 
# are getting information out of the system. 
class UserDTO {

    private function __construct(private string $email, private string $password, private string $api_token)
    {}
    
    public static function create(string $email, string $password, string $api_token): self
    {
        return new self($email, $password, $api_token);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getApiToken(): string
    {
        return $this->api_token;
    }

}