<?php

class Branch extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branch';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('street', 'city_id', 'email', 'delivery', 'pickup', 'position', 'area', 'active', 'commerce_id');

    /**
     * User relationship
     */
    public function commerce() {
        return $this->belongsTo('Commerce', 'commerce_id');
    }

    /**
     * Product relationship
     */
    public function products() {
        return $this->belongsToMany('Product', 'branch_product', 'branch_id', 'product_id');
    }

    /**
     * User relationship
     */
    public function users() {
        return $this->belongsToMany('User', 'branch_user', 'branch_id', 'user_id');
    }

    /**
     * Product relationship
     */
    public function openingHours() {
        return $this->hasMany('BranchOpening', 'branch_id');
    }

    /**
     * Product relationship
     */
    public function phones() {
        return $this->hasMany('BranchPhone', 'branch_id');
    }

    /**
     * Product relationship
     */
    public function dealers() {
        return $this->hasMany('BranchDealer', 'branch_id');
    }

    /**
     * City relationship
     */
    public function city() {
        return $this->belongsTo('City', 'city_id', 'geonameid');
    }

}
