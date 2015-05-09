<?php

namespace App\Service\Form\User;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class UserValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        "name" => "required|max:50|alpha_spaces",
        //"email" => "required|max:50|email|unique:user,email",
        "mobile" => "required|max:30|unique:user,mobile",
        "password" => "required|min:6",
        "id_user_type" => "required|numeric|exists:user_type,id",
        "id_commerce" => "numeric|exists:commerce,id",
    );

}