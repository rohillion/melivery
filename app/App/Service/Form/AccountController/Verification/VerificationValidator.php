<?php

namespace App\Service\Form\AccountController\Verification;

use App\Service\Validation\ValidableInterface;
use App\Service\Validation\Laravel\LaravelValidator;

class VerificationValidator extends LaravelValidator implements ValidableInterface {

    /**
     * Validation for password reminder
     *
     * @var array
     */
    protected $rules = array(
        //'email' => 'required|email',
        'vcode' => 'required|alpha_num|size:4'
    );

}
