<?php

namespace App\Repository\Branch;

use App\Repository\RepositoryInterface;

interface BranchInterface extends RepositoryInterface {

    public function allByCommerceId($commerceId);
}
