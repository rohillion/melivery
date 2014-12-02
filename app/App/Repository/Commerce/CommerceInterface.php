<?php

namespace App\Repository\Commerce;

use App\Repository\RepositoryInterface;

interface CommerceInterface extends RepositoryInterface {
    
    public function findByName($commerceName);
}
