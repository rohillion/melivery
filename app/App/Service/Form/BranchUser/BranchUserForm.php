<?php

namespace App\Service\Form\BranchUser;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Branch\BranchInterface;
use App\Repository\BranchUser\BranchUserInterface;
use App\Service\Form\AbstractForm;

class BranchUserForm extends AbstractForm {

    /**
     * Branch repository
     *
     * @var \App\Repository\Branch\BranchInterface
     */
    protected $branch;
    protected $branchUser;

    public function __construct(ValidableInterface $validator, BranchInterface $branch, BranchUserInterface $branchUser) {
        parent::__construct($validator);
        $this->branch = $branch;
        $this->branchUser = $branchUser;
    }

    /**
     * Create BranchUser
     *
     * @return BranchUser
     */
    public function save($branch, $user_id) {

        $input = array(
            'branch_id' => $branch->id,
            'user_id' => $user_id
        );

        if (!$this->valid($input)) {
            return false;
        }

        return $this->branchUser->create($input);
    }

    public function setCurrent($branchUserId) {


        $last = $this->branchUser->first(['*'], [], [
            'user_id' => \Session::get('user.id'),
            'current' => 1
        ]);

        $new = $this->branchUser->find($branchUserId, ['*'], [], [
            'user_id' => \Session::get('user.id'),
            'current' => 0
        ]);

        if (!is_null($last) && !is_null($new)) {
            $last->current = 0;
            $last->save();

            $new->current = 1;
            $new->save();
            
            \Session::put('user.branch_id', $new->branch_id);
        }

        return true;
    }

}
