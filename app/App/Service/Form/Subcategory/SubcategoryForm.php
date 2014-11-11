<?php

namespace App\Service\Form\Subcategory;

use App\Service\Validation\ValidableInterface;
use App\Repository\Subcategory\SubcategoryInterface;
use App\Service\Form\AbstractForm;

class SubcategoryForm extends AbstractForm{

    /**
     * Subcategory repository
     *
     * @var \App\Repository\Subcategory\SubcategoryInterface
     */
    protected $subcategory;

    public function __construct(ValidableInterface $validator, SubcategoryInterface $subcategory) {
        parent::__construct($validator);
        $this->subcategory = $subcategory;
    }

    /**
     * Create an new subcategory
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->subcategory->all($columns, $with);
    }

    /**
     * Create an new subcategory
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->subcategory->create($input);
    }

    /**
     * Update an existing subcategory
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->subcategory->edit($id, $input);
    }
    
    /**
     * Delete a subcategory
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->subcategory->destroy($id);
    }

    /**
     * Create an new subcategory
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->subcategory->edit($id, $input);
    }

}
