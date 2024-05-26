<?php declare(strict_types=1);


namespace App\Validation\WorkEntry; 


class Validator {

    const required_keys = ['start_date', 'end_date'];

    public function validatePost(array $formdata) {
        return empty(array_diff(self::required_keys, array_keys($formdata))) and 
            empty(array_diff(array_keys($formdata), self::required_keys));
    }

    
}   