<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\Order\OrderForm;
use App\Service\Form\OrderStatus\OrderStatusForm;
use App\Service\Form\OrderProduct\OrderProductForm;
use App\Service\Form\AttributeOrderProduct\AttributeOrderProductForm;
use App\Service\Form\BranchDealer\BranchDealerForm;
use App\Repository\Branch\BranchInterface;
use App\Repository\User\UserInterface;

class OrderController extends BaseController {

    protected $order;
    protected $orderstatus;
    protected $orderproduct;
    protected $attributeorderproduct;
    protected $branchDealer;
    protected $branch;
    protected $user;

    public function __construct(OrderForm $order, OrderStatusForm $orderstatus, OrderProductForm $orderproduct, AttributeOrderProductForm $attributeorderproduct, BranchDealerForm $branchDealer, BranchInterface $branch, UserInterface $user) {
        $this->order = $order;
        $this->orderstatus = $orderstatus;
        $this->orderproduct = $orderproduct;
        $this->attributeorderproduct = $attributeorderproduct;
        $this->branchDealer = $branchDealer;
        $this->branch = $branch;
        $this->user = $user;
    }

    public function index() {

        $data['dealers'] = NULL;

        $data['user'] = $this->user->find(Session::get('user.id'), ['*'], ['branches']);

        $branch = Input::get('branch_id');

        if ($branch) {

            Session::put('user.branch_id', $branch);
        } else {

            Session::put('user.branch_id', $data['user']->branches[1]->id); //TODO. cambiar a 0
        }

        $data['orders'] = $this->order->allByBranchId(Session::get('user.branch_id'));

        $dealers = $this->branch->find(Session::get('user.branch_id'), ['*'], ['dealers'])->dealers;

        if (!$dealers->isEmpty()) {
            foreach ($dealers as $dealer) {
                $data['dealers'][$dealer->dealer_name] = $dealer;
            }
        }

        return View::make('commerce.order.index', $data);
    }

    /**
     * Update order
     * PUT /order/{order_id}
     */
    public function update($id) {

        $input = array(
            "estimated" => Input::get('estimated')
        );

        $order = $this->order->update($id, $input);

        if ($order) {

            $order->status_id = 2; //TODO. hacer de esto una constante. 2 = progress

            if ($this->orderstatus->save($order)) {
                // Success!
                return Redirect::to('/order')
                                ->withSuccess(Lang::get('segment.order.message.success.edit'))
                                ->with('status', 'success');
            }

            return Redirect::to('/order')
                            ->withInput()
                            ->withErrors($this->orderstatus->errors())
                            ->with('status', 'error');
        }

        return Redirect::to('/order')
                        ->withInput()
                        ->withErrors($this->order->errors())
                        ->with('status', 'error');
    }

    /**
     * Update order status
     * POST /order/{order_id}/status
     */
    public function changeStatus($id) {

        $order = $this->order->find($id);

        if (!is_null($order)) {

            $order->status_id = Input::get('status');
            $order->motive_id = Input::get('motive');
            $order->branch_dealer_id = Input::get('dealer');
            $order->observations = Input::get('observations');

            if ($this->orderstatus->save($order)) {
                // Success!
                return Redirect::to('/order')
                                ->withSuccess(Lang::get('segment.order.message.success.edit'))
                                ->with('status', 'success');
            }
        }

        return Redirect::to('/order')
                        ->withInput()
                        ->withErrors($this->orderstatus->errors())
                        ->with('status', 'error');
    }

    /**
     * Update order status
     * POST /order/{order_id}/status
     */
    public function dispatch($dealer_id) {

        $dealer = $this->branchDealer->findWithReadyOrders($dealer_id);

        if (!is_null($dealer)) {

            if ($this->branchDealer->dispatch($dealer)) {
                // Success!
                return Redirect::to('/order')
                                ->withSuccess(Lang::get('dealer.dispatched.success'))
                                ->with('status', 'success');
            }
        }

        return Redirect::to('/order')
                        ->withInput()
                        ->withErrors($this->branchDealer->errors())
                        ->with('status', 'error');
    }
    
    /**
     * Update order status
     * POST /order/{order_id}/status
     */
    public function report($dealer_id) {
        
        $dealer = $this->branchDealer->findWithReadyOrders($dealer_id);

        if (!is_null($dealer)) {
            
            if ($this->branchDealer->report($dealer, Input::get('orders'))) {
                // Success!
                return Redirect::to('/order')
                                ->withSuccess(Lang::get('dealer.report.success'))
                                ->with('status', 'success');
            }
        }

        return Redirect::to('/order')
                        ->withInput()
                        ->withErrors($this->branchDealer->errors())
                        ->with('status', 'error');
    }
    

}
