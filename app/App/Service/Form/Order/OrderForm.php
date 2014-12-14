<?php

namespace App\Service\Form\Order;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Order\OrderInterface;
use App\Repository\BranchDealer\BranchDealerInterface;
use App\Service\Form\AbstractForm;

class OrderForm extends AbstractForm {

    /**
     * Order repository
     *
     * @var \App\Repository\Order\OrderInterface
     */
    protected $order;
    protected $branchDealer;
    protected $messageBag;

    public function __construct(ValidableInterface $validator, OrderInterface $order, BranchDealerInterface $branchDealer) {
        parent::__construct($validator);
        $this->order = $order;
        $this->branchDealer = $branchDealer;
        $this->messageBag = new MessageBag();
    }

    /**
     * All Orders by Commerce Id
     *
     * @return Eloquent collection
     */
    public function allGroupByStatus($branch_id) {

        $orderStatus = array(
            "pending" => NULL,
            "progress" => NULL,
            "ready" => NULL
        );

        $orders = $this->order->allByBranchId($branch_id);

        if (!$orders->isEmpty()) {

            foreach ($orders as $order) {

                $orderStatus[$order->status_name][$order->id] = $order->toArray();
            }
        }

        return $orderStatus;
    }

    /**
     * All Orders by Commerce Id
     *
     * @return Eloquent collection
     */
    public function allByCommerceId($commerce_id) {

        return $this->order->allByCommerceId($commerce_id);
    }

    /**
     * Create an new order
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        return $this->order->create($input);
    }

    /**
     * Update an existing order
     *
     * @return boolean
     */
    public function update($id, array $input, $branchId) {

        $order = $this->order->find($id, ['*'], [], ['branch_id' => $branchId]);

        $input["branch_id"] = $order->branch_id;
        $input["user_id"] = $order->user_id;
        $input["delivery"] = $order->delivery;
        $input["paycash"] = $order->paycash;

        if (!$this->valid($input, $id)) {
            return false;
        }

        $this->order->edit($id, $input);

        return $order;
    }

    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->order->destroy($id);
    }

    /**
     * Create an new order
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->order->edit($id, $input);
    }

    /**
     * Attach dealer to order 
     *
     * @return boolean
     */
    public function attachDealer($order_id, $dealer_id) {

        $order = $this->order->find($order_id, ['*'], [], ['branch_id' => \Session::get('user.branch_id')]);

        if (is_null($order)) {

            $this->messageBag->add('error', 'El pedido en cuestion no existe. Por favor intentelo nuevamente.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;

            return false;
        }


        $branchDealer = $this->branchDealer->find($dealer_id, ['*'], [], ['branch_id' => \Session::get('user.branch_id')]);

        if (is_null($branchDealer)) {

            $this->messageBag->add('error', 'El repartidor en cuestion no existe. Por favor intentelo nuevamente.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;

            return false;
        }

        try {
            //$order->branch_dealer()->attach($branchDealer->id);
            $order->branch_dealer()->sync(array($branchDealer->id));
            return true;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            $this->messageBag->add('error', 'Ha ocurrido un error. Por favor refresque el navegador e intentelo nuevamente.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
        }

        return false;
    }

    /**
     * Attach dealer to order 
     *
     * @return boolean
     */
    public function dettachDealer($order_id) {

        $order = $this->order->find($order_id, ['*'], ['branch_dealer'], ['branch_id' => \Session::get('user.branch_id')]);

        if (is_null($order)) {

            $this->messageBag->add('error', 'El pedido en cuestion no existe. Por favor intentelo nuevamente.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;

            return false;
        }

        if (!$order->branch_dealer->isEmpty()) {
            try {
                $order->branch_dealer()->detach($order->branch_dealer[0]->id);
                return true;
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                $this->messageBag->add('error', 'Ha ocurrido un error. Por favor refresque el navegador e intentelo nuevamente.'); //TODO. Soporte Lang.
                $this->validator->errors = $this->messageBag;
            }
        }

        return false;
    }

}
