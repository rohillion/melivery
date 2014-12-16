<?php

class Order extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('branch_id','user_id','estimated','delivery');
    
    /**
     * Branch relationship
     */
    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }
    
    /**
     * Commerce relationship
     */
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
    /**
     * Status relationship
     */
    public function status() {
        return $this->belongsToMany('Status', 'order_status', 'order_id', 'status_id');
    }
    
    /**
     * OrderProduct relationship
     */
    public function order_products() {
        return $this->hasMany('OrderProduct', 'order_id');
    }
    
    /**
     * Order relationship
     */
    public function attributes_order_product() {
        return $this->hasManyThrough('AttributeOrderProduct', 'OrderProduct', 'order_id', 'order_product_id');
    }
    
    /**
     * BranchDealer relationship
     */
    public function branch_dealer() {
        return $this->belongsToMany('BranchDealer', 'branch_dealer_order', 'order_id', 'branch_dealer_id');
    }
    
    /**
     * Order relationship
     */
    public function cash() {
        return $this->hasOne('OrderCash', 'order_id');
    }

}
