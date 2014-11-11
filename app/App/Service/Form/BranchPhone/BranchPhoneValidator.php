<?php

namespace App\Service\Form\BranchPhone;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class BranchPhoneValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new Phone
     *
     * @var array
     */
    protected $rules = array(
        'branch_id' => 'required|numeric|exists:branch,id',
        'number' => 'required|numeric'
    );

}
