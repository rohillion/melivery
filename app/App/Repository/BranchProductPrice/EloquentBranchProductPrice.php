<?php

namespace App\Repository\BranchProductPrice;

use App\Repository\RepositoryAbstract;

class EloquentBranchProductPrice extends RepositoryAbstract implements BranchProductPriceInterface {

    /*public function allByCommerceId($commerceId) {

        return $this->entity
                        ->where('productprice.id_commerce', '=', $commerceId)
                        ->with('attributes.attribute_types')
                        ->with('rules.rule_type')
                        ->with('tags')
                        ->get();
    }*/
}
