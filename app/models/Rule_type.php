<?php

class RuleType extends Eloquent {

    protected $table = 'rule_type';
    
    protected $fillable = array('rule_type_name');
    
    /**
     * Rule relationship
     */
    public function rules() {
        return $this->hasMany('Rule','rule_type_id');
    }

}
