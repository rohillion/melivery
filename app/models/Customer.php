<?php

class Customer extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customer';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('floor_apt','street','city_id','position','user_id');

    /**
     * User relationship
     */
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}