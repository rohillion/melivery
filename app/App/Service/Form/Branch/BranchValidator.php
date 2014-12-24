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
        'delivery' => 'required_without:pickup',
        /*'radio' => 'required_with:delivery|numeric',
        'delivery_area' => 'required_with:delivery|max:255',*/
        'pickup' => 'required_without:delivery|numeric',
        'commerce_id' => 'required|numeric',
    );

}
