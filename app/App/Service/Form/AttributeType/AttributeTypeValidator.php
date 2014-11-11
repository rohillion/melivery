<?php

namespace App\Service\Form\AttributeType;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class AttributeTypeValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new AttributeType
     *
     * @var array
     */
    protected $rules = array(
        'd_attribute_type' => 'required|max:45|unique:attribute_type'
    );

}