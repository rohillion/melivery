<?php

namespace App\Service\Validation\Laravel\Validators\AccountValidator;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class Login extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for login a User
     *
     * @var array
     */
    protected $rules = array(
        'email' => 'required|email',
        'password' => 'required',
    );

}
