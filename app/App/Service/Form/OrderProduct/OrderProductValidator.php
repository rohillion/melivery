<?php

namespace App\Service\Form\OrderProduct;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class OrderProductValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'order_id' => 'required|numeric|exists:order,id',
        'product_id' => 'required|numeric|exists:product,id',
        'product_qty' => 'required|numeric|min:0'
    );

}