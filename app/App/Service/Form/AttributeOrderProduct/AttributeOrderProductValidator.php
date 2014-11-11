<?php

namespace App\Service\Form\AttributeOrderProduct;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class AttributeOrderProductValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'order_product_id' => 'required|numeric|exists:order_product,id',
        'attribute_id' => 'required|numeric|exists:attribute,id',
    );

}