<?php

namespace App\Service\Form\Preorder;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class PreorderValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $preorders = array(
        'preorder_type_id' => 'required|numeric|exists:preorder_type,id',
        'preorder_value' => 'required|numeric',
    );

}