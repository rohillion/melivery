<?php

namespace App\Repository\BranchUser;

use App\Repository\RepositoryAbstract;

class EloquentBranchUser extends RepositoryAbstract implements BranchUserInterface {
    
    /*public function findByBranchId($dealerId, $branchId){
        
        return $this->entity
                ->where('branch_id', $branchId)
                ->find($dealerId);
    }*/

}
