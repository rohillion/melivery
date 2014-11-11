<?php

class BranchOpening extends Eloquent {

    protected $table = 'branch_opening';

    protected $fillable = array('day_id','open','open_time','close_time','branch_id');

    /**
     * Branch relationship
     */
    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }

}
