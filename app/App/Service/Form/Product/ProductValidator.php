<?php

namespace App\Service\Form\Product;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class ProductValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        /*'price' => 'required_without:multiprice|numeric|price',
        'multiprice' => 'required_without:price',*/
        'id_commerce' => 'required|numeric|exists:commerce,id',
        'id_category' => 'required|numeric|exists:category,id',
        'subcategory_id' => 'required|numeric|exists:subcategory,id',
        'tag_id' => 'required|numeric|exists:tag,id'
    );

}
