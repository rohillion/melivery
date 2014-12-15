<?php

namespace App\Repository\BranchDealer;

use App\Repository\RepositoryInterface;

interface BranchDealerInterface extends RepositoryInterface {

    public function findWithOrders($dealer_id, $branch_id);
    
    public function findByBranchId($dealerId, $branchId);
}
