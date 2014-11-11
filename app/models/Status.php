<?php

class Status extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'status';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('status_name','active');
    
    /**
     * Order relationship
     */
    public function orders() {
        return $this->belongsToMany('Order', 'order_status','status_id','order_id')->withPivot('active');
    }

}
