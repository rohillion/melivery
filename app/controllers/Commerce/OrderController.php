<?php

use Illuminate\Database\Eloquent\Collection;
use App\Repository\Order\OrderInterface;
use App\Service\Form\Order\OrderForm;
use App\Service\Form\OrderStatus\OrderStatusForm;
use App\Repository\User\UserInterface;
use App\Repository\BranchDealer\BranchDealerInterface;
use App\Repository\Motive\MotiveInterface;
use App\Service\Form\BranchDealer\BranchDealerForm;

class OrderController extends BaseController {

    protected $order;
    protected $orderForm;
    protected $orderstatus;
    protected $user;
    protected $branchDealer;
    protected $branchDealerForm;
    protected $motive;

    public function __construct(OrderInterface $order, OrderForm $orderForm, OrderStatusForm $orderstatus, UserInterface $user, BranchDealerInterface $branchDealer, BranchDealerForm $branchDealerForm, MotiveInterface $motive) {
        $this->order = $order;
        $this->orderForm = $orderForm;
        $this->orderstatus = $orderstatus;
        $this->user = $user;
        $this->branchDealer = $branchDealer;
        $this->branchDealerForm = $branchDealerForm;
        $this->motive = $motive;
    }

    public function index() {

        $data['user'] = $this->user->find(Session::get('user.id'), ['*'], ['branches']);

        $data['orders'] = $this->orderForm->allGroupByStatus(Session::get('user.branch_id'));

        $dealers = $this->branchDealer->all(['*'], [], ['branch_id' => Session::get('user.branch_id')]);

        $dealerCollection = new Collection();

        foreach ($dealers as $dealer) {
            $dealerCollection->push($this->branchDealer->findWithOrders($dealer->id, Session::get('user.branch_id')));
        }

        $data['dealers'] = $dealerCollection;

        $data['motives'] = $this->motive->all(['*'], [], ['active' => 1]);

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

        $order = $this->orderForm->update($id, $input);

        if ($order) {

            $inputStatus = array(
                'status_id' => Config::get('cons.order_status.progress'),
                'motive_id' => false,
                'observations' => false
            );

            if ($this->orderstatus->save($order, $inputStatus)) {
                // Success!
                return Response::json(array(
                            'status' => TRUE,
                            'type' => 'success',
                            'message' => 'Se ha notificado al comensal. Tiempo estimado: ' . $order->estimated . 'm',
                            'order' => $order)
                );
            }

            return Response::json(array(
                        'status' => FALSE,
                        'type' => 'error',
                        'message' => $this->orderstatus->errors()->all())
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
    public function changeStatus($order_id, $status_id) {

        $input = array(
            'order_id' => $order_id,
            'status_id' => $status_id,
            'motive_id' => Input::get('motive'),
            'observations' => Input::get('observations')
        );

        if ($this->orderstatus->changeStatus($input)) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success')
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->orderstatus->errors()->all())
        );
    }

    /**
     * Update order status
     * POST /order/{order_id}/status
     */
    public function changeType($order_id) {

        $input = array(
            'order_id' => $order_id,
            'paycash' => Input::get('paycash')
        );

        $order = $this->orderForm->changeType($input);

        if ($order) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'order' => $order)
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

        $dealer = $this->branchDealer->findWithOrders($dealer_id, Session::get('user.branch_id'));

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
    public function undispatch($dealer_id) {

        $dealer = $this->branchDealer->findWithOrders($dealer_id, Session::get('user.branch_id'));

        if (!is_null($dealer)) {

            if ($this->branchDealerForm->undispatch($dealer)) {
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

        $dealer = $this->branchDealer->findWithOrders($dealer_id, Session::get('user.branch_id'));

        if (!is_null($dealer)) {

            if ($dealer = $this->branchDealerForm->report($dealer)) {
                // Success!
                return Response::json(array(
                            'status' => TRUE,
                            'type' => 'success',
                            'message' => Lang::get('dealer.report.success'),
                            'orders' => $dealer->orders->toArray()
                                )
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
     * Get order card html
     * GET /order/{order_id}/card
     */
    public function card($order_id) {

        $order = $this->order->find($order_id, ['*'], ['user', 'order_products.branch_product.product.tags', 'order_products.branch_product_price.size', 'order_products.attributes_order_product.attributes', 'cash', 'branch_dealer'], ['branch_id' => Session::get('user.branch_id')]);
        $order->new = true;

        if (!is_null($order)) {
            // Success!
            $panel = Input::get('panel');

            if (!$panel)
                $panel = 'progress';
            
            $data['order'] = $order;
            $data['motives'] = $this->motive->all(['*'], [], ['active' => 1]);

            $view = View::make('commerce.order.' . $panel, $data);

            $orderCard = $view->render();

            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'orderCard' => $orderCard)
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchDealer->errors()->all())
        );
    }

}
