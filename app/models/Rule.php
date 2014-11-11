<?php

class Rule extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rule';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('rule_type_id','rule_value');

    /**
     * Rule relationship
     */
    public function attribute_types() {
        return $this->belongsToMany('Attribute_type','attribute_type_rule','rule_id','attribute_type_id');
    }
    
    /**
     * Product relationship
     */
    public function products() {
        return $this->belongsToMany('Product', 'attribute_type_product_rule','rule_id','product_id')->withPivot('attribute_type_id');
    }
    
    /**
     * Rule_type relationship
     */
    public function rule_type() {
        return $this->belongsTo('RuleType','rule_type_id');
    }

}
