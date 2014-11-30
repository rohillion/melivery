<?php

namespace App\Repository\Product;

use App\Repository\RepositoryAbstract;

class EloquentProduct extends RepositoryAbstract implements ProductInterface {

    public function allByCommerceId($commerceId) {

        return $this->entity
                        /* ->select('product.*', \DB::raw('CASE WHEN tag.id IS NOT NULL THEN 1 ELSE 0 END AS published'))
                          ->leftJoin('tag', 'product.tag_id', '=', 'tag.id') */
                        ->where('product.id_commerce', '=', $commerceId)
                        ->with('attributes.attribute_types')
                        ->with('rules.rule_type')
                        ->with('tags')
                        ->get();
    }

    public function allByPosition($position, array $filters, $items) {

        $query = $this->entity
                ->select('product.*', 'branch.id as branch_id'/*, 'branch_product.active_branch'*/)
                ->join('branch_product', 'product.id', '=', 'branch_product.product_id')
                /*->join(\DB::raw(
                                '(
                                    SELECT tbl2.product_id , tbl2.active_branch , tbl1.branch_id
                                        FROM branch_product as tbl1 
                                    JOIN(
                                        SELECT product_id, COUNT(product_id) as active_branch 
                                            FROM branch_product 
                                        GROUP BY product_id 
                                        ) as tbl2 
                                    ON (tbl1.product_id = tbl2.product_id)
                                 ) as branch_product'
                        ), function($join) {

                    $join->on('product.id', '=', 'branch_product.product_id');
                })*/
                ->join('branch', 'branch_product.branch_id', '=', 'branch.id')
                ->where('product.active', '=', 1)
                ->where(function($sql) use($position) {
            $sql->where(\DB::raw("SELECT 
                                    inDeliveryArea (
                                      POINTFROMTEXT(
                                        'POINT(" . $position . ")'
                                      ),
                                      POLYFROMTEXT(
                                        CONCAT('POLYGON((', branch.area ,'))')
                                      )
                                    )"), "=", 1);
        });

        $this->setFilters($filters, $query);

        return $query->with('commerce')
                        ->with('categories')
                        ->with('subcategories')
                        ->with('tags')
                        ->with('attributes.attribute_types')
                        ->groupBy('product.id')
                        ->paginate($items)
        ;
    }

    public function findWhereBranchId($productId, $branchId) {

//        return $this->entity
//                        ->where('id', '=', $productId)
//                        ->where('branch', '=', $branchId)
//                        ->first();
        return $this->entity
                        ->whereHas('branches', function($q) use($branchId) {
                            $q->where('branch.id', '=', $branchId);
                        })->find($productId);
    }

    public function findProductByTagByCommerceId($tagId, $commerceId) {

        return $this->entity
                        ->where('tag_id', '=', $tagId)
                        ->where('id_commerce', '=', $commerceId)
                        ->with('attributes')
                        ->first();
    }

    public function allActive($filters, $items) {

        $query = $this->entity
                ->select('product.*')
                ->where('product.active', '=', 1);

        $this->setFilters($filters, $query);

        return $query->with('commerce')
                        ->with('categories')
                        ->with('subcategories')
                        ->with('tags')
                        ->with('attributes')
                        ->paginate($items);
    }

    private function setFilters($filters, $query) {

        foreach ($filters as $field => $id) {
            if ($id)
                $query->where($field, '=', $id);
        }

        return $query;
    }

}
