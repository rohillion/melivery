<?php

namespace App\Service\Form\RuleType;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class RuleTypeValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new RuleType
     *
     * @var array
     */
    protected $rules = array(
        'rule_type_name' => 'required|max:50|unique:rule_type'
    );

}