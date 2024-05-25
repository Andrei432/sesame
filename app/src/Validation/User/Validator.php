<?php declare(strict_types=1);

namespace App\Validation\User; 

# I use these method to ensure the keys are contained in the input form 
# in a way that order is not important.
# A-B = empty && B-A = empty 
class Validator {

    const registering_required_keys = ["email", "password", "name"];
    const login_required_keys = ["email", "password"];

    public function __construct() {}
    
    public function validateUserRegisterRequest(array $formdata): bool {
        return empty(array_diff(self::registering_required_keys, array_keys($formdata))) and 
            empty(array_diff(array_keys($formdata), self::registering_required_keys));
    }

    public function validateUserLoginRequest(array $formdata): bool {
        return empty(array_diff(self::login_required_keys, array_keys($formdata))) and
            empty(array_diff(array_keys($formdata), self::login_required_keys));
    }
}

