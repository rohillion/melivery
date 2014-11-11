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
    public function save($branch, array $dealers) {

        if (count($dealers) < 1) {
            $this->validator->errors = new MessageBag(['dealer' => 'Debe haber al menos un repartidor si la sucursal admite delivery']);
            return false;
        }

        foreach ($dealers as $dealer_id => $dealer_name) {

            $input = array(
                'branch_id' => $branch->id,
                'dealer_name' => $dealer_name
            );

            $branchDealer = $this->branchDealer->find($dealer_id);

            if (!is_null($branchDealer)) {

                if (!$this->update($branchDealer->id, $input))
                    return false;
            } else {

                $this->validator->rules['dealer_name'] = "required|alpha_spaces|unique:branch_dealer,dealer_name,NULL,id,branch_id," . $input['branch_id'];

                if (!$this->valid($input)) {
                    return false;
                }

                $this->branchDealer->create($input);
            }
        }

        return true;
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

}
