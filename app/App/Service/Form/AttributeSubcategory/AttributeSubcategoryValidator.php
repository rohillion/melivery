<?php

namespace App\Service\Form\AttributeSubcategory;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class AttributeSubcategoryValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new AttributeSubcategory Pivot Relation
     *
     * @var array
     */
    protected $rules = array(
        'id_attribute' => 'required|numeric|exists:attribute,id',
        'id_subcategory' => 'required|numeric|exists:subcategory,id',
    );

}