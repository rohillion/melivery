<?php

namespace App\Service\Form\Country;

use App\Service\Validation\ValidableInterface;
use App\Repository\Country\CountryInterface;
use App\Service\Form\AbstractForm;

class CountryForm extends AbstractForm {

    /**
     * Country repository
     *
     * @var \App\Repository\Country\CountryInterface
     */
    protected $country;
    
    public function __construct(ValidableInterface $validator, CountryInterface $country) {
        parent::__construct($validator);
        $this->country = $country;
    }

    /**
     * Create an new country
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->country->all($columns, $with);
    }

    /**
     * Create an new country
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->country->create($input);
    }

    /**
     * Update an existing country
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->country->edit($id, $input);
    }
    
    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->country->destroy($id);
    }

    /**
     * Create an new country
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->country->edit($id, $input);
    }

}
