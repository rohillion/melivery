<?php

namespace App\Service\Form\OrderCash;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class OrderCashValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        "order_id" => 'required|numeric|exists:order,id',
        "paycash" => 'required|price',
        "change" => 'required|price',
    );

}
