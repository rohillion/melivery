<?php

namespace App\Service\Form\AccountController\Reset;

use App\Service\Validation\ValidableInterface;
use App\Repository\User\UserInterface;
use App\Service\Form\AbstractForm;
use Illuminate\Support\MessageBag;

class ResetForm extends AbstractForm {

    private $user;

    public function __construct(ValidableInterface $validator, UserInterface $user) {
        parent::__construct($validator);
        $this->user = $user;
        $this->messageBag = new MessageBag();
    }

    public function save($input) {

        if (!$this->valid($input))
            return false;

        $input['token'] = \Password::getToken(array('mobile' => $input['mobile']), $input['code']);

        unset($input['code']);

        $response = \Password::reset($input, function($user, $password) {
                    $user->password = \Hash::make($password);

                    $user->save();
                });

        switch ($response) {
            case \Password::INVALID_PASSWORD:
            case \Password::INVALID_TOKEN:
            case \Password::INVALID_USER:
                $this->messageBag->add('error', $response); //TODO. Soporte Lang.
                $this->validator->errors = $this->messageBag;
                $res = false;
                break;

            case \Password::PASSWORD_RESET:
                $res = 'Su clave ha sido cambiada. Por favor inicie sesión con su nueva clave.';
                break;
        }

        return $res;
    }

}
