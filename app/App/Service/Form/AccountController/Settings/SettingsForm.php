<?php

namespace App\Service\Form\AccountController\Settings;

use App\Service\Validation\ValidableInterface;
use App\Repository\User\UserInterface;
use App\Service\Form\AccountController\AccountForm;
use App\Service\Form\AbstractForm;
use Illuminate\Support\MessageBag;

class SettingsForm extends AbstractForm {

    protected $user;
    protected $messageBag;

    public function __construct(ValidableInterface $validator, UserInterface $user, AccountForm $accountForm) {
        parent::__construct($validator);
        $this->user = $user;
        $this->accountForm = $accountForm;
        $this->messageBag = new MessageBag();
    }

    public function updateProfile($input) {

        $user_id = \Session::get('user.id');

        $this->validator->rules = array(
            "name" => "required|max:50|alpha_spaces",
            "mobile" => "required|max:30|unique:user",
        );

        if (!$this->valid($input, $user_id))
            return false;

        $user = $this->user->edit($user_id, $input);

        if ($user)
            $this->accountForm->setSession($user);

        return $user;
    }

    public function updatePassword($input) {

        if (!$this->valid($input))
            return false;

        if (!\Auth::attempt(array('mobile' => \Session::get('user.mobile'), 'password' => $input['password']))) {
            $this->messageBag->add('error', 'La clave actual es incorrecta.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        $input['password'] = \Hash::make($input['newpassword']);

        unset($input['newpassword']);
        unset($input['confirm']);

        return $this->user->edit(\Session::get('user.id'), $input);
    }

}
