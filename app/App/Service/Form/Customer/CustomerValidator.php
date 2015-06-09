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
        'floor_apt' => 'max:30',
        'street' => 'max:150',
        'city_id' => 'numeric|exists:city,geonameid',
        'position' => 'max:255',
        'user_id' => 'required|exists:user,id',
    );

}