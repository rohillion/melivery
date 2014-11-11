<?php

namespace App\Service\Form\OrderStatus;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class OrderStatusValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'order_id' => 'required|numeric|exists:order,id',
        'status_id' => 'required|numeric|exists:status,id'
    );

}