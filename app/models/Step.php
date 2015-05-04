<?php

class Step extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'step';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = array('process_id','next_step_id','step_name','mandatory','active');

    /**
     * User relationship
     */
    public function users() {
        return $this->belongsToMany('User','step_user','step_id','user_id');
    }
    
    /**
     * Process relationship
     */
    public function process() {
        return $this->belongsTo('Process', 'process_id');
    }

}
