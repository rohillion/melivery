<?php

namespace App\Service\Validation;

abstract class AbstractValidator {

    /**
     * Validator
     *
     * @var object
     */
    protected $validator;

    /**
     * Data to be validated
     *
     * @var array
     */
    protected $data = array();

    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = array();
    
    /**
     * Custom Messages
     *
     * @var NULL
     */
    protected $messages = array();

    /**
     * Validation errors
     *
     * @var array
     */
    public $errors = array();

    /**
     * Set data to validate
     *
     * @param array $data
     * @return self
     */
    public function with(array $data) {
        $this->data = $data;

        return $this;
    }

    /**
     * Return errors
     *
     * @return array
     */
    public function errors() {
        return $this->errors;
    }
    
    /**
     * Return messages
     *
     * @return array
     */
    public function messages(\Illuminate\Support\MessageBag $messages = NULL) {
        if(!is_null($messages))
            $this->messages = $messages;
        return $this->messages;
    }

    /**
     * Pass the data and the rules to the validator
     *
     * @param integer
     * @return boolean
     */
    abstract function passes($id = NULL);
}