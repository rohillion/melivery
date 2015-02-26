<?php

namespace App\Service\Form\AttributeSubcategory;

use App\Service\Validation\ValidableInterface;
use App\Repository\AttributeSubcategory\AttributeSubcategoryInterface;
use App\Service\Form\AbstractForm;

class AttributeSubcategoryForm extends AbstractForm {

    /**
     * Attribute repository
     *
     * @var \App\Repository\Attribute\AttributeInterface
     */
    protected $attributeSubcategory;

    public function __construct(ValidableInterface $validator, AttributeSubcategoryInterface $attributeSubcategory) {
        parent::__construct($validator);
        $this->attributeSubcategory = $attributeSubcategory;
    }

    /**
     * Create an new attribute_category
     *
     * @return boolean
     */
    public function save(array $input) {

        foreach ($input as $subcategory => $attributes) {

            foreach ($attributes as $attribute) {

                $insert['id_subcategory'] = $subcategory;
                $insert['id_attribute'] = $attribute;

                if (!$this->valid($insert))
                    return false;

                $this->attributeSubcategory->create($insert);
            }
        }

        return true;
    }

    /**
     * Create an new attribute
     *
     * @return boolean
     */
    public function delete(array $input) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->attributeSubcategory->destroy($input);
    }

    /**
     * Create an new attribute
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->attributeSubcategory->edit($id, $input);
    }

}
