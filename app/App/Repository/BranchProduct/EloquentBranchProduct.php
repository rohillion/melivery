<?php

namespace App\Repository\BranchProduct;

use App\Repository\RepositoryAbstract;

class EloquentBranchProduct extends RepositoryAbstract implements BranchProductInterface {

    public function allByPosition($position, array $filters, $items, array $sort) {

        $query = $this->entity
                ->select('branch_product.*', 'branch_area.id as branch_area_id')
                ->join('product', 'product_id', '=', 'product.id')
                ->join('branch_area', 'branch_product.branch_id', '=', 'branch_area.branch_id')
                ->join('branch_product_price as prices', function($query){
                    $query->on('branch_product.id', '=', 'prices.branch_product_id');
                    $query->whereNull('prices.deleted_at');
                })
                ->where('branch_product.active', '=', 1)
                ->where(function($sql) use($position) {
            $sql->where(\DB::raw("SELECT 
                                    inDeliveryArea (
                                      POINTFROMTEXT(
                                        'POINT(" . $position . ")'
                                      ),
                                      POLYFROMTEXT(
                                        CONCAT('POLYGON((', branch_area.area ,'))')
                                      )
                                    )"), "=", 1);
        });

        $this->setFilters($filters, $query);

        return $query->with('product.commerce')
                        ->with('product.categories')
                        ->with('product.subcategories')
                        ->with('product.tags')
                        ->with('prices.size')
                        ->with('product.attributes.attribute_types')
                        ->groupBy('product.id')
                        ->orderBy($sort['by'], $sort['order'])
                        ->paginate($items)
        ;
    }
    
    public function allActive(array $filters, $items, array $sort) {

        $query = $this->entity
                ->select('branch_product.*')
                ->join('product', 'product_id', '=', 'product.id')
                ->join('branch_product_price as prices', function($query){
                    $query->on('branch_product.id', '=', 'prices.branch_product_id');
                    $query->whereNull('prices.deleted_at');
                })
                ->where('branch_product.active', '=', 1);

        $this->setFilters($filters, $query);

        return $query->with('product.commerce')
                        ->with('product.categories')
                        ->with('product.subcategories')
                        ->with('product.tags')
                        ->with('prices.size')
                        ->with('product.attributes.attribute_types')
                        ->groupBy('product.id')
                        ->orderBy($sort['by'], $sort['order'])
                        ->paginate($items)
        ;
    }

    private function setFilters($filters, $query) {

        foreach ($filters as $field => $id) {
            if ($id)
                $query->where($field, '=', $id);
        }

        return $query;
    }

}
