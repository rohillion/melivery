<?php

namespace App\Service\Form\BranchProductPrice;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class BranchProductPriceValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new BranchProductPrice
     *
     * @var array
     */
    protected $rules = array(
        'price' => 'required|numeric|price',
        'size' => 'sometimes|required|alpha_spaces',
        'branch_product_id' => 'required|numeric|exists:branch_product,id'
    );

}
