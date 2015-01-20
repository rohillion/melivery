<?php

namespace App\Repository\Category;

use App\Repository\RepositoryInterface;

interface CategoryInterface extends RepositoryInterface {

    public function allWithPublishedTagsByCommerce($commerceId);

    public function allActive();
    
    public function findWithCustomTag($commerceId, $commerceId);
    
    public function findByName($category_name);
    
    public function likeCategoryName($countryCode, $q);
}
