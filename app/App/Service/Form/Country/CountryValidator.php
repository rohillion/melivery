<?php

namespace App\Service\Form\Country;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class CountryValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'id_country_type' => 'required|numeric|exists:country_type,id',
        'country_name' => 'required|max:100|unique:country',
    );

}