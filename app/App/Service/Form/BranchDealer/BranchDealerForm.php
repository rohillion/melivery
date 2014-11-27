<?php

namespace App\Service\Form\BranchDealer;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Branch\BranchInterface;
use App\Repository\BranchDealer\BranchDealerInterface;
use App\Service\Form\OrderStatus\OrderStatusForm;
use App\Service\Form\AbstractForm;

class BranchDealerForm extends AbstractForm {

    /**
     * Branch repository
     *
     * @var \App\Repository\Branch\BranchInterface
     */
    protected $branch;
    protected $branchDealer;
    protected $orderStatus;

    public function __construct(ValidableInterface $validator, BranchInterface $branch, BranchDealerInterface $branchDealer, OrderStatusForm $orderStatus) {
        parent::__construct($validator);
        $this->branch = $branch;
        $this->branchDealer = $branchDealer;
        $this->orderstatus = $orderStatus;
    }

    /**
     * Attach opening hours to Branch
     *
     * @return boolean
     */
    public function save($input) {

        $this->validator->rules['dealer_name'] = "required|alpha_spaces|unique:branch_dealer,dealer_name,NULL,id,branch_id," . $input['branch_id'];

        if (!$this->valid($input)) {
            return false;
        }

        return $this->branchDealer->create($input);
    }

    public function findWithReadyOrders($id) {

        return $this->branchDealer->findWithReadyOrders($id);
    }

    public function update($id, $input) {

        $this->validator->rules['dealer_name'] = "required|alpha_spaces|unique:branch_dealer,dealer_name," . $id . ",id,branch_id," . $input['branch_id'];

        if (!$this->valid($input)) {
            return false;
        }

        return $this->branchDealer->edit($id, $input);
    }

    public function dispatch($dealer) {

        $input = array(
            'branch_id' => $dealer->branch_id,
            'dealer_name' => $dealer->dealer_name,
            'dispatched' => 1
        );

        $dealer = $this->update($dealer->id, $input);

        if (!$dealer)
            return false;

        foreach ($dealer->orders as $order) {

            $sended = \Notification::send('customer_' . $order->id, 'order', ['order' => 'delivered']);

            if (!$sended) {

                \Mail::send('emails.notification.order_receive', [], function($message) {

                    $message->to('rohillion@hotmail.com', 'Jose Lopez')->subject('Pedido de Melivery');
                });
            }
        }

        return $dealer;
    }

    public function report($dealer, $ordersStatus) {

        if (count($ordersStatus) < $dealer->orders->count()) {
            $this->validator->errors = 'Por favor, indique la resoluci&oacute;n de todas las comandas correspondientes al repartidor ' . $dealer->dealer_name;
            return false;
        }

        $input = array(
            'branch_id' => $dealer->branch_id,
            'dealer_name' => $dealer->dealer_name,
            'dispatched' => 0
        );

        $this->update($dealer->id, $input);

        foreach ($dealer->orders as $order) {

            $order->status_id = $ordersStatus[$order->id] ? 4 : 6;
            //$order->motive_id = Input::get('motive');
            //$order->branch_dealer_id = Input::get('dealer');
            //$order->observations = Input::get('observations');

            if (!$this->orderstatus->save($order)) {
                $this->validator->errors = $this->orderstatus->errors();
                return false;
            }
        }

        return $dealer;
    }

    public function findByBranchId($dealerId, $branchId) {

        return $this->branchDealer->findByBranchId($dealerId, $branchId);
    }

    public function delete($dealerId) {

        $dealer = $this->branchDealer->findWithReadyOrders($dealerId);

        if (!$dealer->orders->isEmpty()) {
            $this->validator->errors = new MessageBag(['dealer' => 'El repartidor ' . $dealer->dealer_name . ' no puede ser eliminado debido a que tiene asignados uno o mas pedidos pendientes de entrega. Por favor finalice su cuenta abierta para luego poder eliminarlo.']);
            return false;
        }

        return $this->branchDealer->destroy($dealerId);
    }

}
