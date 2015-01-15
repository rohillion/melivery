<?php

namespace App\Service\Form\Branch;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class BranchValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'street' => 'required|max:255',
        'city_id' => 'required|numeric',
        'email' => 'required|email',
        'position' => 'required|max:255',
        'delivery' => 'numeric',
        'pickup' => 'numeric',
        'commerce_id' => 'required|numeric',
    );

}
