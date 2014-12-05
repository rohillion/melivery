<?php

namespace App\Repository\Branch;

use App\Repository\RepositoryAbstract;

class EloquentBranch extends RepositoryAbstract implements BranchInterface {

    public function allByCommerceId($commerceId) {

        return $this->entity
                        ->where('commerce_id', '=', $commerceId)
                        ->get();
    }

    public function findByCommerceId($branchId, $commerceId, $entities = array()) {

        return $this->withEntities($entities)
                        ->where('commerce_id', $commerceId)
                        ->find($branchId);
    }

}
