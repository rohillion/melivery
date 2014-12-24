<?php

namespace App\Service\Form\BranchArea;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class BranchAreaValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new Phone
     *
     * @var array
     */
    protected $rules = array(
        'branch_id' => 'required|numeric|exists:branch,id',
        'cost' => 'required|price',
        'min' => 'required|price',
        'area' => 'required|max:255',
        'radio' => 'required|numeric',
    );

}
