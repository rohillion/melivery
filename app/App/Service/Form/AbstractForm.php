<?php

namespace App\Service\Form;

use App\Service\Validation\ValidableInterface;

abstract class AbstractForm {
    
    /**
     * Form Data
     *
     * @var array
     */
    protected $data;

    /**
     * Validator
     *
     * @var \App\Form\Service\ValidableInterface
     */
    protected $validator;
    
    public function __construct(ValidableInterface $validator) {
        $this->validator = $validator;
    }
    
    /**
     * Return any validation errors
     *
     * @return array
     */
    public function errors() {
        return $this->validator->errors();
    }
    
    /**
     * Return any validation errors
     *
     * @return array
     */
    public function messages() {
        return $this->validator->messages();
    }

    /**
     * Test if form validator passes
     *
     * @return boolean
     */
    protected function valid(array $input, $id = NULL) {
        return $this->validator->with($input)->passes($id);
    }
    
    /**
     * Convert string of tags to
     * array of tags
     *
     * @param  string
     * @return array
     */
    protected function processTags($tags) {
        $tags = explode(',', $tags);

        foreach ($tags as $key => $tag) {
            $tags[$key] = trim($tag);
        }

        return $tags;
    }
}