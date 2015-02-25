<?php

class Category extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('category_name');

    /**
     * Products relationship
     */
    public function products() {
        return $this->hasMany('Product', 'id_category');
    }

    /**
     * Attribute relationship
     */
    public function attributes() {
        return $this->belongsToMany('Attribute', 'attribute_category', 'id_category', 'id_attribute')->withPivot('id');
    }
    
    /**
     * Subcategory relationship
     */
    public function subcategories() {
        return $this->hasMany('Subcategory', 'id_category');
    }
    
    /**
     * Subcategory relationship
     */
    public function activeSubcategories() {
        return $this->hasMany('Subcategory', 'id_category')->where('active', '=', 1);
    }

    // User model
    public function availableAttributes() {
        $ids = \DB::table('attribute_category')->where('id_category', '=', $this->id)->lists('id_attribute');
        return \Attribute::whereNotIn('id', $ids)->get();
    }
    
    /**
     * Country relationship
     */
    public function countries() {
        return $this->belongsTo('Country', 'country_id');
    }

}
