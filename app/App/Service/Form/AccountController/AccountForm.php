<?php

namespace App\Service\Form\AccountController;

use App\Service\Validation\ValidableInterface;
use App\Repository\BranchUser\BranchUserInterface;
use App\Service\Form\AbstractForm;

class AccountForm extends AbstractForm {

    private $branchUser;

    public function __construct(ValidableInterface $validator, BranchUserInterface $branchUser) {
        parent::__construct($validator);
        $this->branchUser = $branchUser;
    }

    public function login($input) {
        
        $this->validator->rules['mobile'] = "required|mobile_".\Session::get('location.country');

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
            
            //check if is data in queue
            $queue = \CommonEvents::getLastAction();
            if ($queue) {
                \CommonEvents::setLastAction(FALSE); //Delete last attempt action;
                return \URL::action($queue['action']);
            }

            return \URL::route(\Session::get('user.dashboard'));
        }

        return ['error' => 'Usuario o clave inválidos']; //TODO. lang support
    }

    public function setSession($user) {

        \Session::put('user.id', $user->id);
        \Session::put('user.name', $user->name);
        \Session::put('user.mobile', $user->mobile);
        \Session::put('user.id_user_type', $user->id_user_type);
        \Session::put('user.active', $user->active);
        \Session::put('user.verified', $user->verified);
        \Session::put('user.id_commerce', $user->id_commerce);
        \Session::put('user.country_id', $user->country_id);

        foreach ($user->steps as $step) {
            \Session::put('user.steps.' . $step->id, array(
                'done' => $step->pivot->done
            ));
        }

        //if Commerce related User then load current branch
        if ($user->id_user_type != \Config::get('cons.user_type.admin') && $user->id_user_type != \Config::get('cons.user_type.customer')) {
            $branchUser = $this->branchUser->first(['*'], [], [
                'user_id' => $user->id,
                'current' => 1
            ]);
            
            if(!is_null($branchUser))
                \Session::put('user.branch_id', $branchUser->branch_id);
        }

        return true;
    }
}
