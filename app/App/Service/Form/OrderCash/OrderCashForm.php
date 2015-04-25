<?php

namespace App\Service\Form\OrderCash;

use App\Service\Validation\ValidableInterface;
use App\Repository\OrderCash\OrderCashInterface;
use App\Service\Form\AbstractForm;

class OrderCashForm extends AbstractForm {

    /**
     * OrderCash repository
     *
     * @var \App\Repository\OrderCash\OrderCashInterface
     */
    protected $ordercash;

    public function __construct(ValidableInterface $validator, OrderCashInterface $ordercash) {
        parent::__construct($validator);
        $this->ordercash = $ordercash;
    }

    /**
     * Create an new ordercash
     *
     * @return boolean
     */
    public function save($order) {

        $input = array(
            "order_id" => $order->id,
            "paycash" => $order->paycash,
            "change" => $order->change,
        );

        if (!$this->valid($input)) {
            return false;
        }

        try {

            return $this->ordercash->create($input);
        } catch (Exception $e) {
            
            \Log::error($e->getMessage());
            $this->messageBag->add('error', 'Ha ocurrido un error. Por favor refresque el navegador e intentelo nuevamente.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
        }

        return false;
    }

    /**
     * Update an existing ordercash
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->ordercash->edit($id, $input);
    }

    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->ordercash->destroy($id);
    }

}
