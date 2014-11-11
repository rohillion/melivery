<?php

namespace App\Service\Form\AccountController;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class AccountValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for login a User
     *
     * @var array
     */
    protected $rules = array(
        'email' => 'required|email',
        'password' => 'required'
    );

}
