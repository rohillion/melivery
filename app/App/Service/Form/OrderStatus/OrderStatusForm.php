<?php

namespace App\Service\Form\OrderStatus;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\OrderStatus\OrderStatusInterface;
use App\Repository\Order\OrderInterface;
use App\Service\Form\AbstractForm;

class OrderStatusForm extends AbstractForm {

    /**
     * OrderStatus repository
     *
     * @var \App\Repository\OrderStatus\OrderStatusInterface
     */
    protected $messageBag;
    protected $orderstatus;

    public function __construct(ValidableInterface $validator, OrderStatusInterface $orderstatus, OrderInterface $order) {
        parent::__construct($validator);
        $this->messageBag = new MessageBag();
        $this->order = $order;
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
    public function save($order, $status) {

        $input = array(
            "order_id" => $order->id,
            "status_id" => $status['status_id']
        );

        if (!$this->valid($input))
            return false;


        //Start transaction
        \DB::beginTransaction();

        $orderstatus = $this->orderstatus->create($input);

        switch ($input['status_id']) {

            case \Config::get('cons.order_status.pending')://Pending

                $sended = \Notification::send('branch_' . $order->branch_id, 'order', ['order' => $order->id]);

                if (!$sended) {

                    \Mail::send('emails.notification.order_receive', [], function($message) {

                        $message->to('rohillion@hotmail.com', 'Jose Lopez')->subject('Pedido de Melivery');
                    });
                }

                break;

            case \Config::get('cons.order_status.progress')://Progress

                break;

            case \Config::get('cons.order_status.ready')://Ready
                // TODO. SI es comida para la barra se debe notificar inmediatamente al comensal, de lo contrario, se notifica cuando el repartidor es despachado.
                //$order->branch_dealer()->attach($order->branch_dealer_id);

                break;

            case \Config::get('cons.order_status.done')://Done

                break;

            case \Config::get('cons.order_status.canceled')://Canceled

                $orderstatus->motive()->attach($status['motive_id'], array('observations' => $status['observations']));

                break;
        }

        \DB::commit();
        // End transaction

        return $orderstatus;
    }

    /**
     * Update an existing orderstatus
     *
     * @return boolean
     */
    public function changeStatus(array $input) {

        $order = $this->order->find($input['order_id'], ['*'], [], ['branch_id' => \Session::get('user.branch_id')]);

        if (is_null($order)) {
            $this->messageBag->add('error', 'El pedido en cuestion no existe. Por favor intentelo nuevamente.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        return $this->save($order, $input);
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
