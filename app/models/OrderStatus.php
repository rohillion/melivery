<?php

class OrderStatus extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_status';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('status_id', 'order_id');

    public function setUpdatedAtAttribute($value) {
        // to Disable updated_at
    }

    /**
     * Order relationship
     */
    public function orders() {
        return $this->belongsTo('Order', 'order_id');
    }

    /**
     * Status relationship
     */
    public function status() {
        return $this->belongsTo('Status', 'status_id');
    }
    
    /**
     * Motive relationship
     */
    public function motive() {
        return $this->belongsToMany('Motive', 'order_status_motive', 'order_status_id', 'motive_id')->withPivot('observations');
    }

}
