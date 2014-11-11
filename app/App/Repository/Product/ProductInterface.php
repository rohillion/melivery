<?php

namespace App\Repository\Product;

use App\Repository\RepositoryInterface;

interface ProductInterface extends RepositoryInterface {

    public function allByCommerceId($commerceId);
    
    public function allByPosition($position, array $filters, $items);

    public function findProductByTagByCommerceId($tagId, $commerceId);
    
    public function findWhereBranchId($productId, $branchId);
    
    public function allActive($filters, $items);
}
