<?php

namespace App\Service\Form\CommerceController;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class CommerceValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'd_commerce' => 'max:50',
        'brand_url' => 'max:50|unique:commerce,brand_url'
    );

}
