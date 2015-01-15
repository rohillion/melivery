<?php

namespace App\Repository\BranchArea;

use App\Repository\RepositoryAbstract;

class EloquentBranchArea extends RepositoryAbstract implements BranchAreaInterface {
    
    /*public function findByBranchId($dealerId, $branchId){
        
        return $this->entity
                ->where('branch_id', $branchId)
                ->find($dealerId);
    }*/

}
