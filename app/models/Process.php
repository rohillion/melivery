<?php

class Process extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'process';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('process_name');

    /**
     * User_type relationship
     */
    public function user_types() {
        return $this->belongsToMany('User_type','process_user_type','process_id','user_type_id');
    }
    
    /**
     * Step relationship
     */
    public function steps() {
        return $this->hasMany('Step', 'process_id');
    }

}
