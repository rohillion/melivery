<?php

namespace App\Repository\Order;

use App\Repository\RepositoryAbstract;

class EloquentOrder extends RepositoryAbstract implements OrderInterface {

    public function allByBranchId($branch_id) {

        return $this->entity
                        ->select('O.*', 'B.status_id', 'B.status_name', 'B.status_date')
                        ->from('order as O')
                        ->with('user.customer')
                        ->with('branch')
                        ->with('cash')
                        ->with('order_products.product.tags.subcategories')
                        ->with('order_products.attributes_order_product.attributes')
                        ->join(\DB::raw(
                                        '(
                                            SELECT OS.order_id, OS.status_id, OS.created_at as status_date, Z.last_status AS last_status, S.status_name, M.motive_name, OSM.observations
                                            FROM order_status OS 

                                            JOIN
                                            (
                                                SELECT OS2.order_id, max(OS2.id) last_status
                                                FROM order_status OS2
                                                group by OS2.order_id
                                            ) Z

                                            ON OS.order_id = Z.order_id
                                            AND OS.id = Z.last_status

                                            JOIN status S
                                            ON OS.status_id = S.id 
                                            
                                            LEFT JOIN order_status_motive OSM
                                            ON S.id = OSM.order_status_id 
                                            
                                            LEFT JOIN motive M
                                            ON OSM.motive_id = M.id 
                                        ) as B'
                                ), function($join) {

                            $join->on('O.id', '=', 'B.order_id');
                        })
                        ->where('O.branch_id', $branch_id)
                        ->orderBy('O.id', 'desc')
                        ->get();
    }

}
