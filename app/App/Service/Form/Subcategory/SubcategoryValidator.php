<?php

namespace App\Service\Form\Subcategory;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class SubcategoryValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new Subcategory
     *
     * @var array
     */
    protected $rules = array(
        'subcategory_name' => 'required|max:50',
        'id_category' => 'required|numeric|exists:category,id'
    );

}