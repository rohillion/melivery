<?php

class User_type extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_type';
    
    /**
     * The table primary key used by the model.
     *
     * @var string
     */
    protected $primaryKey  = 'id_user_type';

    /**
     * Post relationship
     */
    public function user() {
        return $this->hasMany('User','id_user');
    }

}
