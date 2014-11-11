<?php

namespace App\Service\Form\RuleType;

use App\Service\Validation\ValidableInterface;
use App\Repository\RuleType\RuleTypeInterface;
use App\Service\Form\AbstractForm;

class RuleTypeForm extends AbstractForm {

    /**
     * RuleType repository
     *
     * @var \App\Repository\RuleType\RuleTypeInterface
     */
    protected $ruletype;
    
    public function __construct(ValidableInterface $validator, RuleTypeInterface $ruletype) {
        parent::__construct($validator);
        $this->ruletype = $ruletype;
    }

    /**
     * Create an new ruletype
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->ruletype->all($columns, $with);
    }

    /**
     * Create an new ruletype
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->ruletype->create($input);
    }

    /**
     * Update an existing ruletype
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }
        
        //$input['tags'] = $this->processTags($input['tags']);
        return $this->ruletype->edit($id, $input);
    }
    
    /**
     * Delete a ruletype
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->ruletype->destroy($id);
    }

    /**
     * Create an new ruletype
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->ruletype->edit($id, $input);
    }

}
