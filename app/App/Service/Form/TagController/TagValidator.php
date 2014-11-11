<?php

namespace App\Service\Form\TagController;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class TagValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'tag_name' => 'required|max:50',
        'subcategory_id' => 'required|numeric|exists:subcategory,id',
        'commerce_id' => 'numeric|exists:commerce,id',
        'active' => 'numeric'
    );

}