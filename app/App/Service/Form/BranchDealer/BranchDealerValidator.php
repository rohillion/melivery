<?php

namespace App\Service\Form\BranchDealer;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class BranchDealerValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new Phone
     *
     * @var array
     */
    public $rules = array(
        'branch_id' => 'required|numeric|exists:branch,id',
        'dealer_name' => "required|alpha_spaces"
    );

}
