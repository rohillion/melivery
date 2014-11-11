<?php

namespace App\Service\Validation\Laravel;

use Illuminate\Validation\Factory;
use App\Service\Validation\AbstractValidator;

abstract class LaravelValidator extends AbstractValidator {

    /**
     * Validator
     *
     * @var Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * Construct
     *
     * @param Illuminate\Validation\Factory $validator
     */
    public function __construct(Factory $validator) {
        $this->validator = $validator;
    }

    /**
     * Pass the data and the rules to the validator
     *
     * @param integer
     * @return boolean
     */
    public function passes($id = NULL) {

        if (!empty($id)) {
            $this->fixRules($id);
        }

        $validator = $this->validator->make($this->data, $this->rules, $this->messages);

        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return false;
        }

        return true;
    }

    private function fixRules($id) {
        foreach ($this->rules as $index => $string) {

            $statement = '';
            $conditions = explode('|', $string);

            foreach ($conditions as $i => $condition) {
                if (strpos($condition, 'unique') !== FALSE) {
                    $condition .= ',' . $index . ',' . $id;
                }
                $statement .= $condition . '|';
            }

            $statements[$index] = substr($statement, 0, -1);
        }

        $this->rules = $statements;
    }

}
