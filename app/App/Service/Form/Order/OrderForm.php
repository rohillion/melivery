<?php

namespace App\Service\Form\Order;

use App\Service\Validation\ValidableInterface;
use App\Repository\Order\OrderInterface;
use App\Service\Form\AbstractForm;

class OrderForm extends AbstractForm {

    /**
     * Order repository
     *
     * @var \App\Repository\Order\OrderInterface
     */
    protected $order;

    public function __construct(ValidableInterface $validator, OrderInterface $order) {
        parent::__construct($validator);
        $this->order = $order;
    }

    /**
     * Create an new order
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->order->all($columns, $with);
    }
    
    /**
     * Create an new order
     *
     * @return boolean
     */
    public function find($id, $columns = array('*'), $with = array()) {

        return $this->order->find($id, $columns, $with);
    }

    /**
     * All Orders by Commerce Id
     *
     * @return Eloquent collection
     */
    public function allByBranchId($branch_id) {

        $orderStatus = array(
            "pending" => NULL,
            "progress" => NULL,
            "ready" => NULL,
            "done" => NULL,
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
    public function update($id, array $input) {

        $order = $this->order->find($id);

        $input["branch_id"] = $order->branch_id;
        $input["user_id"] = $order->user_id;
        $input["delivery"] = $order->delivery;

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

}
