<?php

namespace App\Service\Form\AccountController\Reset;

use App\Service\Validation\ValidableInterface;
use App\Service\Form\AbstractForm;

class ResetForm extends AbstractForm {

    public function __construct(ValidableInterface $validator) {
        parent::__construct($validator);
    }

    public function save($input) {

        if (!$this->valid($input))
            return false;

        $res = array();

        $response = \Password::reset($input, function($user, $password) {
                    $user->password = \Hash::make($password);

                    $user->save();
                });

        switch ($response) {
            case \Password::INVALID_PASSWORD:
            case \Password::INVALID_TOKEN:
            case \Password::INVALID_USER:
                //return Redirect::back()->with('error', Lang::get($response));
                $res['error'] = $response;

            case \Password::PASSWORD_RESET:
                $res['success'] = 'Su clave ha sido cambiada. Por favor inicie sesión con su nueva clave.';
        }

        return $res;
    }

}
