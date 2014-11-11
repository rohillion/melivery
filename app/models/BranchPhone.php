<?php

class BranchPhone extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branch_phone';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('branch_id','number');

    /**
     * Branch relationship
     */
    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }

}
