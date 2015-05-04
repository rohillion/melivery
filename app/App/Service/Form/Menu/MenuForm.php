<?php

namespace App\Service\Form\Menu;

use App\Repository\BranchProduct\BranchProductInterface;
use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Commerce\CommerceInterface;

class MenuForm {

    /**
     * Menu Form Service
     *
     */
    protected $product;
    protected $branchProduct;
    protected $category;
    protected $commerce;

    public function __construct(ProductInterface $product, CategoryInterface $category, CommerceInterface $commerce, BranchProductInterface $branchProduct) {
        $this->branchProduct = $branchProduct;
        $this->product = $product;
        $this->category = $category;
        $this->commerce = $commerce;
    }

    /**
     * Create an new rule
     *
     * @return boolean
     */
    public function products($position, $method, $filters, $items = 10, $sort) {

        if ($position && $method == 'delivery')
            return $this->branchProduct->allByPosition($position, $filters, $items, $sort);

        return $this->branchProduct->allActive($filters, $items, $sort);
    }

}
