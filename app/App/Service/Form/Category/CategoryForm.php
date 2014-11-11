<?php

namespace App\Service\Form\Category;

use App\Service\Validation\ValidableInterface;
use App\Repository\Category\CategoryInterface;
use App\Service\Form\AbstractForm;

class CategoryForm extends AbstractForm{

    /**
     * Category repository
     *
     * @var \App\Repository\Category\CategoryInterface
     */
    protected $category;

    public function __construct(ValidableInterface $validator, CategoryInterface $category) {
        parent::__construct($validator);
        $this->category = $category;
    }

    /**
     * Create an new category
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->category->all($columns, $with);
    }

    /**
     * Create an new category
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->category->create($input);
    }

    /**
     * Update an existing category
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->category->edit($id, $input);
    }
    
    /**
     * Delete a category
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->category->destroy($id);
    }

    /**
     * Create an new category
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->category->edit($id, $input);
    }

}
