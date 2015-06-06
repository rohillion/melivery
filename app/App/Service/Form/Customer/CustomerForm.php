<?php

namespace App\Service\Form\Customer;

use App\Service\Validation\ValidableInterface;
use App\Repository\Customer\CustomerInterface;
use App\Service\Form\AbstractForm;

class CustomerForm extends AbstractForm {

    /**
     * Customer repository
     *
     * @var \App\Repository\Customer\CustomerInterface
     */
    protected $customer;

    public function __construct(ValidableInterface $validator, CustomerInterface $customer) {
        parent::__construct($validator);
        $this->customer = $customer;
    }

    /**
     * Create an new customer
     *
     * @return boolean
     */
    public function save(array $input) {
        
        if (!$this->valid($input)) {
            return false;
        }

        return $this->customer->create($input);
    }

    /**
     * Update an existing customer
     *
     * @return boolean
     */
    public function delete($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->customer->edit($id, $input);
    }

}
