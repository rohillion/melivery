<?php

namespace App\Repository\BranchProduct;

use App\Repository\RepositoryInterface;

interface BranchProductInterface extends RepositoryInterface {

    public function allByPosition($position, array $filters, $items, array $sort);
    public function allActive(array $filters, $items, array $sort);
}
