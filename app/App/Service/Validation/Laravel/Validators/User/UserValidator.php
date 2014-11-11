<?php

namespace App\Service\Validation\Laravel\Validators\User;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class Login extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for login a User
     *
     * @var array
     */
    protected $rules = array(
        "username" => "required",
        "password" => "required"
    );

}

class Request extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for login a User
     *
     * @var array
     */
    protected $rules = array(
        "email" => "required|email|exists:user,email"
    );

}

class Reset extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for login a User
     *
     * @var array
     */
    protected $rules = array(
        "email" => "required|email|exists:user,email",
        "password" => "required|min:6",
        "password_confirmation" => "required|same:password",
        "token" => "required|exists:token,token"
    );

}

class Create extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'name' => 'required',
        'username' => 'required|unique:user,username',
        'email' => 'required|email|unique:user,email',
        "password" => "required|min:6",
        "password_confirmation" => "required|same:password",
    );

}

class Verification extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for verifying a new User
     *
     * @var array
     */
    protected $rules = array(
        "token" => "required|exists:token,token"/*,
        "email" => "required|unique:user,email,token,NULL,token,token"*/
    );

}

class Update extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for updating a new User
     *
     * @var array
     */
    protected $rules = array(
        'email' => 'required|email|unique:users',
        'password' => 'required|min:3|confirmed',
        'password_confirmation' => 'required|min:3'
    );

}
