<?php

namespace App;

use App\UserManagementContext\Application\Query\UserQuery;
use App\UserManagementContext\Domain\Hasher;

class Authenticator 
{

    public function __construct(private Hasher $hasher, private UserQuery $query){}

    public static function is_valid_user(string $email, string $password): bool {
        return true;
    }

}
