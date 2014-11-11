<?php

namespace App\Repository\User;

use App\Repository\RepositoryAbstract;

class EloquentUser extends RepositoryAbstract implements UserInterface {

    public function findByVCode($vcode) {
        return $this->entity->where('verified', $vcode)
                        ->first();
    }

}
