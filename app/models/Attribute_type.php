<?php

class AttributeType extends Eloquent {

    protected $table = 'attribute_type';
    
    protected $fillable = array('d_attribute_type','multiple','valuable');
    
    /**
     * Attribute_type relationship
     */
    public function attributes() {
        return $this->hasMany('Attribute','id_attribute_type');
    }
    
    /**
     * Rule relationship
     */
    public function rules() {
        return $this->belongsToMany('Rule','attribute_type_rule','attribute_type_id','rule_id')->withPivot('required');
    }
    
    /**
     * Rules relationship
     */
    public function product_rules() {
        return $this->belongsToMany('Product','attribute_type_product','attribute_type_id','product_id')->withPivot('required');
    }

}
