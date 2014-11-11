<?php

namespace App\Service\Form\Menu;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class MenuValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $menus = array(
        'menu_type_id' => 'required|numeric|exists:menu_type,id',
        'menu_value' => 'required|numeric',
    );

}