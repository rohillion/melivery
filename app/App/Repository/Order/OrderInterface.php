<?php

namespace App\Repository\Order;

use App\Repository\RepositoryInterface;

interface OrderInterface extends RepositoryInterface {
     public function allByBranchId($branch_id);
     public function allByUserId($user_id);
     
}
