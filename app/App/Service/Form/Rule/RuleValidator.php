<?php

namespace App\Service\Form\Rule;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class RuleValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'rule_type_id' => 'required|numeric|exists:rule_type,id',
        'rule_value' => 'required|numeric',
    );

}