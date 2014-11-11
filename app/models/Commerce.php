<?php

class Commerce extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'commerce';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('commerce_name','commerce_url');

    /**
     * User relationship
     */
    public function users() {
        return $this->hasMany('User', 'id_commerce');
    }
    
    /**
     * User relationship
     */
    public function branches() {
        return $this->hasMany('Branch', 'commerce_id');
    }

}
