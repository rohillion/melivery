<?php

namespace App\Service\Form\AccountController\Request;

use App\Service\Validation\ValidableInterface;
use App\Service\Form\AbstractForm;

class RequestForm extends AbstractForm {

    public function __construct(ValidableInterface $validator) {
        parent::__construct($validator);
    }

    public function remind($input) {

        if (!$this->valid($input))
            return false;

        $res = array();

        $response = \Password::remind($input, function($message) {
                    $message->subject('Cambio de clave Melivery');
                });

        switch ($response) {

            case \Password::INVALID_USER:
                //$res['error'] = Lang::get($response);
                $res['error'] = 'Usuario inválido.';

            case \Password::REMINDER_SENT:
                $res['success'] = 'Hemos enviado un email a ' . $input['email'] . ' con un código para recuperar su clave. Por favor revise su buzon de SPAM en caso de no visualizar el email en su casilla de entrada.';
        }

        return $res;
    }

}
