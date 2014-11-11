<?php

class Country extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'country';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array();
    
    /**
     * City relationship
     */
    public function cities() {
        return $this->hasMany('City', 'country_id');
    }
    
    /**
     * User relationship
     */
    public function users() {
        return $this->hasMany('User', 'country_id');
    }

}
