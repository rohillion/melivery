<?php

namespace App\Service\Form\Attribute;

use App\Service\Validation\ValidableInterface;
use App\Repository\Attribute\AttributeInterface;
use App\Service\Form\AbstractForm;

class AttributeForm extends AbstractForm {

    /**
     * Attribute repository
     *
     * @var \App\Repository\Attribute\AttributeInterface
     */
    protected $attribute;

    public function __construct(ValidableInterface $validator, AttributeInterface $attribute) {
        parent::__construct($validator);
        $this->attribute = $attribute;
    }

    /**
     * Create an new attribute
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->attribute->all($columns, $with);
    }

    /**
     * Create an new attribute
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input))
            return false;

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->attribute->create($input);
    }

    /**
     * Update an existing attribute
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id))
            return false;

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->attribute->edit($id, $input);
    }

    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->attribute->destroy($id);
    }

    /**
     * Create an new attribute
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->attribute->edit($id, $input);
    }

}
