<?php

namespace App\Service\Form\User;

use App\Service\Validation\ValidableInterface;
use App\Repository\User\UserInterface;
use App\Service\Form\AbstractForm;

class UserForm extends AbstractForm {

    /**
     * User repository
     *
     * @var \App\Repository\User\UserInterface
     */
    protected $user;

    public function __construct(ValidableInterface $validator, UserInterface $user) {
        parent::__construct($validator);
        $this->user = $user;
    }

    /**
     * Create an new user
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->user->all($columns, $with);
    }

    /**
     * Find a user
     *
     * @return boolean
     */
    public function find($id, $columns = array('*')) {

        return $this->user->find($id, $columns);
    }

    /**
     * Save an new user
     *
     * @return boolean
     */
    public function save(array $input, $sendVerification = false) {

        switch ($input['account_type']) {
            case 'admin':

                $input['id_user_type'] = 1;
                $dashboard = 'admin';
                break;

            case 'commercial':

                $input['id_user_type'] = 2;
                $dashboard = 'commerce';
                break;

            case 'individual':

                $input['id_user_type'] = 3;
                $dashboard = 'customer';
                break;
        }

        if (!$this->valid($input)) {
            return false;
        }

        $input['password'] = \Hash::make($input['password']);

        //$input['tags'] = $this->processTags($input['tags']);
        $user = $this->user->create($input);

        if ($user) {

            if ($sendVerification) {
                \Auth::loginUsingId($user->id);
                \Session::put('user.dashboard', $dashboard);
            }

            return $user;
        }

        return false;
    }

    /**
     * Update an existing user
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->user->edit($id, $input);
    }

    /**
     * Attach Step to an existing user
     *
     * @return boolean
     */
    public function step($user_id, $step_id) {

        $step = \Session::get('user.steps.' . $step_id);

        if (!$step) {

            $user = $this->user->find($user_id);

            if (!is_null($user)) {

                $user->steps()->attach($step_id, array('done' => 1));
                \Session::put('user.steps.' . $step_id, array(
                    'done' => 1
                ));
            }
        }

        return $step;
    }

    /**
     * Undone Step of an existing user
     *
     * @return boolean
     */
    public function undoneStep($user_id, $step_id) {

        $step = \Session::get('user.steps.' . $step_id);

        if ($step) {

            $user = $this->user->find($user_id);

            if (!is_null($user)) {

                $user->steps()->detach($step_id);
                \Session::forget('user.steps.' . $step_id);
            }
        }

        return $step;
    }

    public function setSession($user) {

        \Session::put('user.id', $user->id);
        \Session::put('user.email', $user->email);
        \Session::put('user.id_user_type', $user->id_user_type);
        \Session::put('user.active', $user->active);
        \Session::put('user.verified', $user->verified);
        \Session::put('user.id_commerce', $user->id_commerce);

        return true;
    }

}
