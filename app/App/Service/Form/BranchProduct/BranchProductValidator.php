<?php

namespace App\Service\Form\BranchProduct;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class BranchProductValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new Phone
     *
     * @var array
     */
    protected $rules = array(
        'branch_id' => 'required|numeric|exists:branch,id',
        'product_id' => 'required|numeric|exists:product,id'
    );

}
