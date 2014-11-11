<?php

namespace App\Repository\Branch;

use App\Repository\RepositoryAbstract;

class EloquentBranch extends RepositoryAbstract implements BranchInterface {

    public function allByCommerceId($commerceId) {

        return $this->entity
                        ->where('commerce_id', '=', $commerceId)
                        ->get();
    }

}
