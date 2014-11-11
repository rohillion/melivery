<?php

namespace App\Service\Form\BranchOpening;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class BranchOpeningValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'day_id' => 'required|numeric|max:8',
        'open' => 'required|numeric|max:1',
        'open_time' => 'required_if:open,1|time',
        'close_time' => 'required_if:open,1|time',
        'branch_id' => 'required|numeric|exists:branch,id',
    );

}
