<?php

namespace App\Repository\ProductPrice;

use App\Repository\RepositoryAbstract;

class EloquentProductPrice extends RepositoryAbstract implements ProductPriceInterface {

    /*public function allByCommerceId($commerceId) {

        return $this->entity
                        ->where('productprice.id_commerce', '=', $commerceId)
                        ->with('attributes.attribute_types')
                        ->with('rules.rule_type')
                        ->with('tags')
                        ->get();
    }*/
}
