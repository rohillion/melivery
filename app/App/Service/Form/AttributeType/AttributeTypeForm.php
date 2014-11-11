<?php

namespace App\Service\Form\AttributeType;

use App\Service\Validation\ValidableInterface;
use App\Repository\AttributeType\AttributeTypeInterface;
use App\Repository\Rule\RuleInterface;
use App\Service\Form\AbstractForm;

class AttributeTypeForm extends AbstractForm {

    /**
     * AttributeType repository
     *
     * @var \App\Repository\AttributeType\AttributeTypeInterface
     */
    protected $attributetype;
    protected $rule;

    public function __construct(ValidableInterface $validator, AttributeTypeInterface $attributetype, RuleInterface $rule) {
        parent::__construct($validator);
        $this->attributetype = $attributetype;
        $this->rule = $rule;
    }

    /**
     * Create an new attributetype
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->attributetype->all($columns, $with);
    }

    /**
     * Create an new attributetype
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        $attributeType = $this->attributetype->create($input);

        if (isset($input['rules']) && !is_null($input['rules']) && $input['rules'] != '0') {

            foreach ($input['rules'] as $rule_id) {

                $rule = $this->rule->find($rule_id);

                if (!is_null($rule))
                    $attributeType->rules()->attach($rule->id);
            }
        }

        return true;
    }

    /**
     * Update an existing attributetype
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->attributetype->edit($id, $input);
    }

    /**
     * Create an new attributetype
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->attributetype->edit($id, $input);
    }

}
