<?php

use Illuminate\Support\MessageBag;
use App\Repository\Order\OrderInterface;
use App\Repository\BranchProduct\BranchProductInterface;

class CustomerController extends BaseController {

    protected $order;
    protected $branchProduct;
    
    public function __construct(OrderInterface $order, BranchProductInterface $branchProduct) {
        $this->order = $order;
        $this->branchProduct = $branchProduct;
    }

    public function index() {
        
        $data['orders'] = NULL;
        
        $orderStatus = array(
            "pending" => NULL,
            "progress" => NULL,
            "ready" => NULL
        );

        $orders = $this->order->allByUserId(Session::get('user.id'));

        if (!$orders->isEmpty()) {

            foreach ($orders as $order) {

                $orderStatus[$order->status_name][$order->id] = $order->toArray();
            }
            
            $data['orders'] = $orderStatus;
        }

        return View::make("customer.index", $data);
    }

}
