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
        
	protected $fillable = array('name', 'email', 'password', 'id_user_type', 'id_commerce');

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

}
