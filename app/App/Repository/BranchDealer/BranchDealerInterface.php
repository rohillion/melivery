<?php

namespace App\Repository\BranchDealer;

use App\Repository\RepositoryInterface;

interface BranchDealerInterface extends RepositoryInterface {

    public function findWithReadyOrders($dealer_id);
    
    public function findByBranchId($dealerId, $branchId);
}
