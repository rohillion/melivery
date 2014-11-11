<?php

namespace App\Service\Form\Customer;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class CustomerValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'customer_name' => 'required_if:customer_name,""|max:50',
        'customer_url' => 'required_with:customer_name|max:50|unique:customer'
    );

}
