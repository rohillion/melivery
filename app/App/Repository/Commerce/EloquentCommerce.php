<?php

namespace App\Repository\Commerce;

use App\Repository\RepositoryAbstract;

class EloquentCommerce extends RepositoryAbstract implements CommerceInterface {

    public function findByName($commerceName){
        
        return $this->entity
                ->with('branches.products.categories.subcategories')
                ->with('branches.products.tags')
                ->where('commerce_url', $commerceName)
                ->first();
        
    }
}
