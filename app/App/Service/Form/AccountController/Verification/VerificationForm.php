<?php

namespace App\Service\Form\AccountController\Verification;

use App\Service\Validation\ValidableInterface;
use App\Repository\User\UserInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Service\Form\AccountController\AccountForm;
use App\Service\Form\AbstractForm;

class VerificationForm extends AbstractForm {

    protected $user;
    protected $commerce;
    protected $accountForm;

    public function __construct(ValidableInterface $validator, UserInterface $user, CommerceInterface $commerce, AccountForm $accountForm) {
        parent::__construct($validator);
        $this->user = $user;
        $this->commerce = $commerce;
        $this->accountForm = $accountForm;
    }

    public function sendVerification($user_id) {

        $input['verified'] = \CommonEvents::getRandomCode();

        $user = $this->user->edit($user_id, $input);

        /* $data['vcode'] = $user->verified;

          \Mail::send('emails.auth.verification', $data, function($message) use ($user) {

          $message->to($user->email, $user->email)->subject('Código de verificación Melivery');

          \Session::put('user.verification', true);
          }); */

        \Session::put('user.verification', true);

        $res = \SMS::send($user->mobile, \Config::get('twilio.TWILIO_NUMBER'), 'Tu código de verificación Melivery es ' . $user->verified);

        return $res;
    }

    public function verify($input) {

        if (!$this->valid($input))
            return ['error' => $this->validator->errors()];

        $user = $this->user->findByVCode($input['vcode']);

        if (!is_null($user)) {

            if ($user->id_user_type == 2) {//si usuario del tipo comercio
                $toEdit['id_commerce'] = $this->commerce->create()->id;
            }

            $toEdit['verified'] = 1; //estado verificado

            $user = $this->user->edit($user->id, $toEdit);

            \Session::forget('user.verification');

            \Billing::createCustomer($user);

            $this->accountForm->setSession($user);

            //check if is data in queue
            $queue = \CommonEvents::getLastAction();
            if ($queue) {
                \CommonEvents::setLastAction(FALSE); //Delete last attempt action;
                return \URL::action($queue['action']);
            }

            return \URL::route(\Session::get('user.dashboard'));
        }

        return ['error' => 'El código de verificacion es invalido.'];
    }

}
