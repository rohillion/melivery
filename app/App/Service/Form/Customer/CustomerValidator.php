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
        'street' => 'required|max:150',
        'city_id' => 'required|numeric|exists:city,geonameid',
        'position' => 'required|max:255',
        'user_id' => 'required|exists:user,id',
    );

}