<?php

namespace App\Repository\Commerce;

use App\Repository\RepositoryAbstract;

class EloquentCommerce extends RepositoryAbstract implements CommerceInterface {

    public function findByName($commerceName){
        
        return $this->entity
                ->with('branches.products')
                ->where('commerce_url', $commerceName)
                ->first();
        
    }
}
