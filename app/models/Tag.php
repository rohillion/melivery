<?php

class Tag extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tag';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('tag_name','subcategory_id','commerce_id','active');

    /**
     * Product relationship
     */
    public function products() {
        return $this->hasMany('Product','tag_id');
    }
    
    /**
     * Subcategory relationship
     */
    public function subcategories() {
        return $this->belongsTo('Subcategory','subcategory_id');
    }
    
    /**
     * Tag_type relationship
     */
    public function tag_types() {
        return $this->belongsTo('TagType','id_tag_type');
    }

}
