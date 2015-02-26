<?php

namespace App\Service\Form\Attribute;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class AttributeValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'id_attribute_type' => 'required|numeric|exists:attribute_type,id',
        'attribute_name' => 'required|max:100',
    );

}