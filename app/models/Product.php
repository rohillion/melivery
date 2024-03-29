<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Product extends Eloquent {

    use SoftDeletingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('image', 'id_commerce', 'id_category', 'subcategory_id', 'tag_id');

    /**
     * Category relationship
     */
    public function categories() {
        return $this->belongsTo('Category', 'id_category');
    }

    /**
     * Tag relationship
     */
    public function tags() {
        return $this->belongsTo('Tag', 'tag_id');
    }

    /**
     * Category relationship
     */
    public function subcategories() {
        return $this->belongsTo('Subcategory', 'subcategory_id');
    }

    /**
     * Category relationship
     */
    public function attributes() {
        return $this->belongsToMany('Attribute', 'attribute_product', 'id_product', 'id_attribute')->withPivot('aditional_price');
    }

    /**
     * Category relationship
     */
    public function rules() {
        return $this->belongsToMany('Rule', 'attribute_type_product_rule', 'product_id', 'rule_id')->withPivot('attribute_type_id');
    }

    /**
     * Commerce relationship
     */
    public function commerce() {
        return $this->belongsTo('Commerce', 'id_commerce');
    }

    /**
     * Branch relationship
     */
    public function branches() {
        return $this->belongsToMany('Branch', 'branch_product', 'product_id', 'branch_id');
    }

    /**
     * Order relationship
     */
    public function orders() {
        return $this->belongsToMany('Order', 'order_product', 'product_id', 'order_id');
    }

    /**
     * Order relationship
     */
    public function attributes_order_product() {
        return $this->hasManyThrough('AttributeOrderProduct', 'OrderProduct', 'product_id', 'order_product_id');
    }

    /**
     * BranchProduct relationship
     */
    public function branchProducts() {
        return $this->hasMany('BranchProduct', 'product_id');
    }

}
