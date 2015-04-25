<?php

namespace App\Service\Form\BranchUser;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class BranchUserValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new Phone
     *
     * @var array
     */
    public $rules = array(
        'branch_id' => 'required|numeric|exists:branch,id',
        'user_id' => "required|numeric|exists:user,id"
    );

}
