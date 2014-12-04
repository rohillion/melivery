<?php

namespace App\Repository\Branch;

use App\Repository\RepositoryInterface;

interface BranchInterface extends RepositoryInterface {

    public function allByCommerceId($commerceId);

    public function findByCommerceId($branchId, $commerceId, $entities = array());
}
