<?php

namespace App\Repository\BranchProduct;

use App\Repository\RepositoryAbstract;

class EloquentBranchProduct extends RepositoryAbstract implements BranchProductInterface {
    
    /*public function findByBranchId($dealerId, $branchId){
        
        return $this->entity
                ->where('branch_id', $branchId)
                ->find($dealerId);
    }*/

}
