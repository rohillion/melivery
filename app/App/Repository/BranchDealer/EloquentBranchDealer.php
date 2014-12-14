<?php

namespace App\Repository\BranchDealer;

use App\Repository\RepositoryAbstract;

class EloquentBranchDealer extends RepositoryAbstract implements BranchDealerInterface {
    
    public function findByBranchId($dealerId, $branchId){
        
        return $this->entity
                ->where('branch_id', $branchId)
                ->find($dealerId);
    }

    public function findWithReadyOrders($dealer_id, $branch_id) {

        return $this->entity
                        ->with(array('orders' => function($query) {

                        $query->join(\DB::raw(
                                        '(
                                            SELECT OS.order_id, OS.status_id, Z.last_status AS last_status
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
                                            
                                        ) as B'
                                ), function($join) {

                            $join->on('order.id', '=', 'B.order_id');
                        });

                        $query->where('B.status_id', '=', \DB::raw(3));
                    }))
                        ->where('id', $dealer_id)
                        ->where('branch_id', $branch_id)
                        ->first();
    }

}
