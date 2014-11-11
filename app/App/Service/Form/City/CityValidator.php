<?php

namespace App\Service\Form\City;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class CityValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'id_city_type' => 'required|numeric|exists:city_type,id',
        'city_name' => 'required|max:100|unique:city',
    );

}