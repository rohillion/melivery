<?php

namespace App\Service\Form\Menu;

use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Commerce\CommerceInterface;

class MenuForm {

    /**
     * Menu Form Service
     *
     */
    protected $product;
    protected $category;
    protected $commerce;

    public function __construct(ProductInterface $product, CategoryInterface $category, CommerceInterface $commerce) {
        $this->product = $product;
        $this->category = $category;
        $this->commerce = $commerce;
    }

    /**
     * Create an new rule
     *
     * @return boolean
     */
    public function products($position, $method, $filters, $items = 10) {

        if ($position) {

            if ($method == 'pickup') {

                $products['pickupProducts'] = $this->product->allActive($filters, $items);
            } else {

                $products['deliveryProducts'] = $this->product->allByPosition($position, $filters, $items);

                if ($products['deliveryProducts']->isEmpty())
                    $products['pickupProducts'] = $this->product->allActive($filters, $items = 3);
            }
        } else {

            $products['pickupProducts'] = $this->product->allActive($filters, $items);
        }

        return $products;
    }

}
