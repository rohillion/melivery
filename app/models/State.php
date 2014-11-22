<?php

class State extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'state';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('state_name','state_asciiname','admin1_code','country_id');
    
    /**
     * Country relationship
     */
    public function country() {
        return $this->belongsTo('Country','country_id');
    }
    
    /**
     * City relationship
     */
    public function cities() {
        return $this->hasMany('City', 'admin1_code');
    }

}
