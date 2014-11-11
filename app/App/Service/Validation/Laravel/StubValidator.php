<?php namespace App\Service\Validation\Laravel;
 
use App\Service\Validation\ValidableInterface;
 
class StubValidator extends LaravelValidator implements ValidableInterface {
 
  /**
   * Validation stub for testing
   *
   * @var array
   */
  protected $rules = array(
    'username' => 'required|alpha_dash|min:2',
    'email' => 'required|email',
    'password' => 'required|confirmed'
  );
 
}