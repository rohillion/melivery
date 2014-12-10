<?php

use App\Repository\Order\OrderInterface;
use App\Service\Form\Order\OrderForm;
use App\Service\Form\OrderStatus\OrderStatusForm;
use App\Repository\User\UserInterface;
use App\Repository\BranchDealer\BranchDealerInterface;

class OrderController extends BaseController {

    protected $order;
    protected $orderForm;
    protected $orderstatus;
    protected $user;
    protected $branchDealer;

    public function __construct(OrderInterface $order, OrderForm $orderForm, OrderStatusForm $orderstatus, UserInterface $user, BranchDealerInterface $branchDealer) {
        $this->order = $order;
        $this->orderForm = $orderForm;
        $this->orderstatus = $orderstatus;
        $this->user = $user;
        $this->branchDealer = $branchDealer;
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

        $data['dealers'] = $this->branchDealer->all(['*'], ['orders'], ['branch_id' => Session::get('user.branch_id')]);

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
    public function changeStatus($id) {

        $order = $this->order->find($id); //TODO. buscar por sucursal por seguridad.

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

}
