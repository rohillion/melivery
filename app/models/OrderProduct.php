<?php

class OrderProduct extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_product';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('branch_product_id','branch_product_price_id','product_qty','order_id');
    
    /**
     * Order relationship
     */
    public function orders() {
        return $this->belongsTo('Order', 'order_id');
    }
    
    /**
     * Product relationship
     */
    public function product() {
        return $this->belongsTo('Product', 'product_id');
    }
    
    /**
     * BranchProduct relationship
     */
    public function branch_product() {
        return $this->belongsTo('BranchProduct', 'branch_product_id');
    }
    
    /**
     * BranchProductPrice relationship
     */
    public function branch_product_price() {
        return $this->belongsTo('BranchProductPrice', 'branch_product_price_id');
    }
    
    /**
     * Attribute relationship
     */
    public function attributes() {
        return $this->belongsToMany('Attribute', 'attribute_order_product','order_product_id','attribute_id');
    }
    
    /**
     * Order relationship
     */
    public function attributes_order_product() {
        return $this->hasMany('AttributeOrderProduct', 'order_product_id');
    }

}
