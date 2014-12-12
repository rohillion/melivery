<?php

namespace App\Service\Form\OrderStatus;

use App\Service\Validation\ValidableInterface;
use App\Repository\OrderStatus\OrderStatusInterface;
use App\Service\Form\AbstractForm;

class OrderStatusForm extends AbstractForm {

    /**
     * OrderStatus repository
     *
     * @var \App\Repository\OrderStatus\OrderStatusInterface
     */
    protected $orderstatus;

    public function __construct(ValidableInterface $validator, OrderStatusInterface $orderstatus) {
        parent::__construct($validator);
        $this->orderstatus = $orderstatus;
    }

    /**
     * Create an new orderstatus
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->orderstatus->all($columns, $with);
    }

    /**
     * Create an new orderstatus
     *
     * @return boolean
     */
    public function save($order) {

        $input = array(
            "order_id" => $order->id,
            "status_id" => $order->status_id
        );

        if (!$this->valid($input)) {
            return false;
        }

        $orderstatus = $this->orderstatus->create($input);

        switch ($input['status_id']) {

            case 1://Pending

                $sended = \Notification::send('branch_' . $order->branch_id, 'order', ['order' => 'new']);

                if (!$sended) {

                    \Mail::send('emails.notification.order_receive', [], function($message) {

                        $message->to('rohillion@hotmail.com', 'Jose Lopez')->subject('Pedido de Melivery');
                    });
                }

                break;

            case 2://Progress

                break;

            case 3://Ready
                
                // TODO. SI es comida para la barra se debe notificar inmediatamente al comensal, de lo contrario, se notifica cuando el repartidor es despachado.

                //$order->branch_dealer()->attach($order->branch_dealer_id);

                break;

            case 4://Done

                break;

            case 5://Canceled

                $orderstatus->motive()->attach($order->motive_id, array('observations' => $order->observations));

                break;
        }

        return $orderstatus;
    }

    /**
     * Update an existing orderstatus
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->orderstatus->edit($id, $input);
    }

    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->orderstatus->destroy($id);
    }

}
