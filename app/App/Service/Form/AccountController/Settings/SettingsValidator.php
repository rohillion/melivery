<?php

namespace App\Service\Form\AccountController\Settings;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class SettingsValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for password update
     *
     * @var array
     */
    public $rules = array(
        'password' => 'required|min:6',
        'newpassword' => 'required_with:password',
        'confirm' => 'same:newpassword',
    );

}
