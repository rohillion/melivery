<?php

namespace App\Service\Form\ProductPrice;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class ProductPriceValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new ProductPrice
     *
     * @var array
     */
    protected $rules = array(
        'price' => 'required|numeric|price',
        'size' => 'sometimes|required|alpha_spaces',
        'product_id' => 'required|numeric|exists:product,id'
    );

}
