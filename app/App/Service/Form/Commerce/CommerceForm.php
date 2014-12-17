<?php

namespace App\Service\Form\Commerce;

use App\Service\Validation\ValidableInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Service\Form\AbstractForm;

class CommerceForm extends AbstractForm {

    /**
     * Commerce repository
     *
     * @var \App\Repository\Commerce\CommerceInterface
     */
    protected $commerce;

    public function __construct(ValidableInterface $validator, CommerceInterface $commerce) {
        parent::__construct($validator);
        $this->commerce = $commerce;
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->commerce->all($columns, $with);
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->commerce->create($input);
    }

    /**
     * Update an existing commerce
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->commerce->edit($id, $input);
    }

    /**
     * Update an existing commerce
     *
     * @return boolean
     */
    public function delete($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->commerce->edit($id, $input);
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->commerce->edit($id, $input);
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function find($idCommerce, $columns = array('*'), $entities = array()) {

        return $this->commerce->find($idCommerce, $columns, $entities);
    }

    /**
     * Create an new commerce
     *
     * @return boolean
     */
    public function checkBrandUrl($url) {

        $valid = \Validator::make(
                        array('brandUrl' => $url), array('brandUrl' => 'required|unique:commerce,commerce_url,' . \Session::get('user.id_commerce'))
        );

        if ($valid->fails()) {
            $this->validator->errors = $valid->messages();
            return false;
        }

        return true;
    }

}
