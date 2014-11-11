<?php

namespace App\Service\Form\OrderProduct;

use App\Service\Validation\ValidableInterface;
use App\Repository\OrderProduct\OrderProductInterface;
use App\Service\Form\AbstractForm;

class OrderProductForm extends AbstractForm {

    /**
     * OrderProduct repository
     *
     * @var \App\Repository\OrderProduct\OrderProductInterface
     */
    protected $orderproduct;
    
    public function __construct(ValidableInterface $validator, OrderProductInterface $orderproduct) {
        parent::__construct($validator);
        $this->orderproduct = $orderproduct;
    }

    /**
     * Create an new orderproduct
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->orderproduct->all($columns, $with);
    }

    /**
     * Create an new orderproduct
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->orderproduct->create($input);
    }

    /**
     * Update an existing orderproduct
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->orderproduct->edit($id, $input);
    }
    
    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->orderproduct->destroy($id);
    }

}
