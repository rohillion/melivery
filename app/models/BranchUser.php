<?php

class BranchUser extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branch_user';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('branch_id','user_id','current');

    /**
     * Branch relationship
     */
    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }
    
    /**
     * Branch relationship
     */
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}
