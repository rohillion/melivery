<?php

namespace App\Service\Validation\Laravel\Validators\Category;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class Create extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'description' => 'required',
        'weight' => 'required',
        'price' => 'required',
        'tracking' => 'required',
        'pin' => 'required|exists:user,pin'
    );

}

class Update extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for updating a new User
     *
     * @var array
     */
    protected $rules = array(
        'description' => 'required',
        'weight' => 'required',
        'price' => 'required',
        'tracking' => 'required',
        'pin' => 'required|exists:user,pin'
    );

}
