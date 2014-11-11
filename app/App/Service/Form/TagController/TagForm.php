<?php

namespace App\Service\Form\TagController;

use App\Service\Validation\ValidableInterface;
use App\Repository\Tag\TagInterface;
use App\Service\Form\AbstractForm;

class TagForm extends AbstractForm {

    /**
     * Tag repository
     *
     * @var \App\Repository\Tag\TagInterface
     */
    protected $tag;
    
    public function __construct(ValidableInterface $validator, TagInterface $tag) {
        parent::__construct($validator);
        $this->tag = $tag;
    }

    /**
     * Create an new tag
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->tag->all($columns, $with);
    }

    /**
     * Create an new tag
     *
     * @return boolean
     */
    public function save(array $input) {

        if (!$this->valid($input)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->tag->create($input);
    }

    /**
     * Update an existing tag
     *
     * @return boolean
     */
    public function update($id, array $input) {

        if (!$this->valid($input, $id)) {
            return false;
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->tag->edit($id, $input);
    }
    
    /**
     * Delete a tag
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->tag->destroy($id);
    }

    /**
     * Create an new tag
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->tag->edit($id, $input);
    }

}
