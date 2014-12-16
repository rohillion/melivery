<?php

namespace App\Service\Form\Order;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class OrderValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'branch_id' => 'required|numeric|exists:branch,id',
        'user_id' => 'required|numeric|exists:user,id',
        'estimated' => 'numeric',
        'delivery' => 'required|numeric|max:1',
        //'paycash' => 'required_if:delivery,1|price'
    );

}