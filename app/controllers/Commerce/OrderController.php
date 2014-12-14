<?php

use App\Repository\Order\OrderInterface;
use App\Service\Form\Order\OrderForm;
use App\Service\Form\OrderStatus\OrderStatusForm;
use App\Repository\User\UserInterface;
use App\Repository\BranchDealer\BranchDealerInterface;
use App\Service\Form\BranchDealer\BranchDealerForm;

class OrderController extends BaseController {

    protected $order;
    protected $orderForm;
    protected $orderstatus;
    protected $user;
    protected $branchDealer;
    protected $branchDealerForm;

    public function __construct(OrderInterface $order, OrderForm $orderForm, OrderStatusForm $orderstatus, UserInterface $user, BranchDealerInterface $branchDealer, BranchDealerForm $branchDealerForm) {
        $this->order = $order;
        $this->orderForm = $orderForm;
        $this->orderstatus = $orderstatus;
        $this->user = $user;
        $this->branchDealer = $branchDealer;
        $this->branchDealerForm = $branchDealerForm;
    }

    public function index() {

        $data['user'] = $this->user->find(Session::get('user.id'), ['*'], ['branches']);

        $branch = Input::get('branch_id');

        if ($branch) {

            Session::put('user.branch_id', $branch);
        } else {

            Session::put('user.branch_id', $data['user']->branches[0]->id); //TODO si no hay sucursales cargadas no se puede ingresar al panel de ordenes.
        }

        $data['orders'] = $this->orderForm->allGroupByStatus(Session::get('user.branch_id'));

        $data['dealers'] = $this->branchDealer->all(['*'], ['orders.user'], ['branch_id' => Session::get('user.branch_id')]);

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

        $order = $this->orderForm->update($id, $input, Session::get('user.branch_id'));

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
                        ->withErrors($this->orderForm->errors())
                        ->with('status', 'error');
    }

    /**
     * Update order status
     * POST /order/{order_id}/status
     */
    public function changeStatus($order_id, $status_id) {

        $order = $this->order->find($order_id, ['*'], [], ['branch_id' => Session::get('user.branch_id')]);

        if (!is_null($order)) {

            $order->status_id = $status_id;
            $order->motive_id = Input::get('motive');
            //$order->branch_dealer_id = Input::get('dealer');
            $order->observations = Input::get('observations');

            if ($this->orderstatus->save($order)) {
                // Success!
                return Response::json(array(
                            'status' => TRUE,
                            'type' => 'success')
                );
            }
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->orderstatus->errors()->all())
        );
    }

    /**
     * Attach order dealer
     * GET /order/{order_id}/dealer/{dealer_id}
     */
    public function attachDealer($order_id, $dealer_id) {

        if ($this->orderForm->attachDealer($order_id, $dealer_id)) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success')
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->orderForm->errors()->all())
        );
    }

    /**
     * Attach order dealer
     * GET /order/{order_id}/dealer/remove
     */
    public function dettachDealer($order_id) {

        if ($this->orderForm->dettachDealer($order_id)) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success')
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->orderForm->errors()->all())
        );
    }

    /**
     * Update order status
     * POST /order/{order_id}/status
     */
    public function dispatch($dealer_id) {

        $dealer = $this->branchDealer->findWithReadyOrders($dealer_id, Session::get('user.branch_id'));

        if (!is_null($dealer)) {

            if ($this->branchDealerForm->dispatch($dealer)) {
                // Success!
                return Response::json(array(
                            'status' => TRUE,
                            'type' => 'success',
                            'message' => Lang::get('dealer.dispatched.success'))
                );
            }
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchDealer->errors()->all())
        );
    }

    /**
     * Update order status
     * POST /order/{order_id}/status
     */
    public function report($dealer_id) {

        $dealer = $this->branchDealer->findWithReadyOrders($dealer_id, Session::get('user.branch_id'));

        if (!is_null($dealer)) {

            if ($this->branchDealerForm->report($dealer)) {
                // Success!
                return Response::json(array(
                            'status' => TRUE,
                            'type' => 'success',
                            'message' => Lang::get('dealer.report.success'))
                );
            }
        }
        
        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchDealer->errors()->all())
        );
    }

}
