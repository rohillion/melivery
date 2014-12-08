<?php

namespace App\Service\Form\AccountController;

use App\Service\Validation\ValidableInterface;
use App\Service\Form\AbstractForm;

class AccountForm extends AbstractForm {

    public function __construct(ValidableInterface $validator) {
        parent::__construct($validator);
    }

    public function login($input) {

        $res = array();

        if (!$this->valid($input))
            return ['error' => $this->validator->errors()];


        if (\Auth::attempt($input)) {

            $user = \Auth::user();
            $this->setSession($user);

            switch ($user->id_user_type) {
                case '1':
                    \Session::put('user.dashboard', 'admin');
                    break;

                case '2':
                    \Session::put('user.dashboard', 'commerce');
                    break;

                case '3':
                    \Session::put('user.dashboard', 'customer');

                    break;
            }

            $queue = \CommonEvents::getLastAction();

            if ($queue)
                return \URL::action($queue['action']);

            return \URL::route(\Session::get('user.dashboard'));
        }

        return ['error' => 'Usuario o clave inválidos'];
    }

    public function setSession($user) {

        \Session::put('user.id', $user->id);
        \Session::put('user.name', $user->name);
        \Session::put('user.email', $user->email);
        \Session::put('user.id_user_type', $user->id_user_type);
        \Session::put('user.active', $user->active);
        \Session::put('user.verified', $user->verified);
        \Session::put('user.id_commerce', $user->id_commerce);

        return true;
    }

}
