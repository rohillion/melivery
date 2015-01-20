<?php

namespace App\Repository\Category;

use App\Repository\RepositoryAbstract;

class EloquentCategory extends RepositoryAbstract implements CategoryInterface {

    public function allWithPublishedTagsByCommerce($commerceId) {

        return $this->entity->select('category.*', 'subcategory.*', 'attribute.*', 'attribute_type.*', 'tag.*', \DB::raw('CASE WHEN product.tag_id IS NOT NULL THEN 1 ELSE 0 END AS published'))
                        ->join('subcategory', 'category.id', '=', 'subcategory.id_category')
                        ->join('attribute_subcategory', 'subcategory.id', '=', 'attribute_subcategory.id_subcategory')
                        ->join('attribute', 'attribute_subcategory.id_attribute', '=', 'attribute.id')
                        ->join('attribute_type', 'attribute.id_attribute_type', '=', 'attribute_type.id')
                        ->join('tag', 'subcategory.id', '=', 'tag.subcategory_id')
                        ->leftJoin('product', function($join) use($commerceId) {
                            $join->on('tag.id', '=', 'product.tag_id')->where('product.id_commerce', '=', $commerceId);
                        })
                        ->get();
    }

    public function allActive() {

        return $this->entity
                        ->where('active', '=', '1')
                        ->orderBy('category_name')
                        ->get();
    }

    public function findWithCustomTag($category_id, $commerceId) {

        return $this->entity
                        ->with('subcategories.attributes.attribute_types.rules.rule_type', 'products.tags', 'products.attributes', 'products.rules')
                        ->with(
                                array('subcategories.tags' => function($query) use ($commerceId) {
                                $query->where('commerce_id', '=', NULL);
                                $query->orWhere('commerce_id', '=', $commerceId);
                                $query->where('active', '=', 1);
                            }))
                        ->where('id', '=', $category_id)
                        ->first();
    }

    public function findByName($category_name) {

        return $this->entity
                        ->where('category_name', '=', $category_name)
                        ->with('subcategories')
                        ->first();
    }
    
    public function likeCategoryName($countryCode, $q) {
        $categories = $this->entity
                ->where('active', 1)
                ->where(function($query) use($q) {
                    $query->where('category_name', 'LIKE', '%' . $q . '%');
                })
                ->get();

        return $categories;
    }

}
