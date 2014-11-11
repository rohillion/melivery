<?php

namespace App\Service\Form\AccountController\Verification;

use App\Service\Validation\ValidableInterface;
use App\Repository\User\UserInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Service\Form\AbstractForm;

class VerificationForm extends AbstractForm {

    protected $user;
    protected $commerce;

    public function __construct(ValidableInterface $validator, UserInterface $user, CommerceInterface $commerce) {
        parent::__construct($validator);
        $this->user = $user;
        $this->commerce = $commerce;
    }

    public function sendVerification($user_id) {

        $input['verified'] = str_random(4);

        $user = $this->user->edit($user_id, $input);

        $data['vcode'] = $user->verified;

        \Mail::send('emails.auth.verification', $data, function($message) use ($user) {

            $message->to($user->email, $user->email)->subject('Código de verificación Melivery');

            \Session::put('user.verification', true);
        });

        return true;
    }

    public function verify($input) {

        if (!$this->valid($input))
            return ['error' => $this->validator->errors()];

        $user = $this->user->findByVCode($input['vcode']);

        if (!is_null($user)) {
            
            if($user->id_user_type == 2){//si usuario del tipo comercio
                
                $toEdit['id_commerce'] = $this->commerce->create()->id;
            }
            
            $toEdit['verified'] = 1;//estado verificado

            $this->user->edit($user->id, $toEdit);
            
            $data['email'] = $user->email;

            \Mail::send('emails.auth.welcome', $data, function($message) use ($user) {

                $message->to($user->email, $user->email)->subject('Bienvenido a Melivery!');

                \Session::forget('user.verification');
            });

            return \URL::route(\Session::get('user.dashboard'));
        }

        return ['error' => 'El código de verificacion es invalido.'];
    }

}
