<?php

class City extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'city';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array();
    
    /**
     * Country relationship
     */
    public function country() {
        return $this->belongsTo('Country','country_id');
    }
    
    /**
     * Country relationship
     */
    public function state() {
        return $this->belongsTo('State','admin1_code','admin1_code');
    }
    
    /**
     * Branch relationship
     */
    public function branches() {
        return $this->hasMany('Branch', 'city_id');
    }

}
