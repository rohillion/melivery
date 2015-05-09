<?php

namespace App\Service\Form\AccountController\Reset;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class ResetValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for password reminder
     *
     * @var array
     */
    protected $rules = array(
        'mobile' => 'required|mobileIE',
        'code' => 'required|digits:6'
    );

}
