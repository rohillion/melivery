<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';
	protected $primaryKey = 'id';
        
	protected $fillable = array('name', /*'email'*/'mobile', 'password', 'id_user_type', 'id_commerce', 'country_id');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
        
        /**
	 * Branch relation
	 */
        public function branches(){
            
            return $this->belongsToMany('Branch', 'branch_user', 'user_id', 'branch_id');
        }
        
        /**
	 * Customer relation
	 */
        public function customer(){
            
            return $this->hasOne('Customer', 'user_id');
        }
        
        /**
	 * Step relation
	 */
        public function steps(){
            
            return $this->belongsToMany('Step', 'step_user','user_id','step_id')->withPivot('done','active');
        }

}
