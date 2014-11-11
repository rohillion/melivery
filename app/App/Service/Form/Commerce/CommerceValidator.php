<?php

namespace App\Service\Form\Commerce;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class CommerceValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'commerce_name' => 'required_if:commerce_name,""|max:50',
        'commerce_url' => 'required_with:commerce_name|max:50|unique:commerce'
    );

}
