<?php

namespace App\Service\Form\City;

use App\Service\Validation\ValidableInterface;
use App\Repository\City\CityInterface;
use App\Service\Form\AbstractForm;

class CityForm extends AbstractForm {

    /**
     * City repository
     *
     * @var \App\Repository\City\CityInterface
     */
    protected $city;
    
    public function __construct(ValidableInterface $validator, CityInterface $city) {
        parent::__construct($validator);
        $this->city = $city;
    }

    /**
     * Create an new city
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->city->all($columns, $with);
    }

    /**
     * Create an new city
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->city->create($input);
    }

    /**
     * Update an existing city
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->city->edit($id, $input);
    }
    
    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->city->destroy($id);
    }

    /**
     * Create an new city
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->city->edit($id, $input);
    }

}
