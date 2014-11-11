<?php

class Motive extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'motive';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('motive_name','motive_description','active');
    
    /**
     * OrderStatus relationship
     */
    public function orderStatus() {
        return $this->belongsToMany('OrderStatus', 'order_status_motive','motive_id','order_status_id')->withPivot('observations');
    }

}
