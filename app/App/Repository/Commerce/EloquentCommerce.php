<?php

namespace App\Repository\Commerce;

use App\Repository\RepositoryAbstract;

class EloquentCommerce extends RepositoryAbstract implements CommerceInterface {

    public function findByName($commerceName, $entities = array()){
        
        return $this->withEntities($entities)
                ->where('commerce_url', $commerceName)
                ->first();
        
    }
}
