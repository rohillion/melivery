<?php

namespace App\Service\Form\Rule;

use App\Service\Validation\ValidableInterface;
use App\Repository\Rule\RuleInterface;
use App\Service\Form\AbstractForm;

class RuleForm extends AbstractForm {

    /**
     * Rule repository
     *
     * @var \App\Repository\Rule\RuleInterface
     */
    protected $rule;
    
    public function __construct(ValidableInterface $validator, RuleInterface $rule) {
        parent::__construct($validator);
        $this->rule = $rule;
    }

    /**
     * Create an new rule
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->rule->all($columns, $with);
    }

    /**
     * Create an new rule
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->rule->create($input);
    }

    /**
     * Update an existing rule
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->rule->edit($id, $input);
    }
    
    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->rule->destroy($id);
    }

    /**
     * Create an new rule
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->rule->edit($id, $input);
    }

}
