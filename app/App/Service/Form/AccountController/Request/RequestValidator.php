<?php

namespace App\Service\Form\AccountController\Request;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class RequestValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for password reminder
     *
     * @var array
     */
    public $rules = array();

}
