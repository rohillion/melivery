<?php namespace App\Service\Validation;
 
interface ValidableInterface {
 
  /**
   * With
   *
   * @param array
   * @return self
   */
  public function with(array $input);
 
  /**
   * Passes
   *
   * @param integer
   * @return boolean
   */
  public function passes($id = NULL);
 
  /**
   * Errors
   *
   * @return array
   */
  public function errors();
  
  /**
   * Messages
   *
   * @return array
   */
  public function messages(\Illuminate\Support\MessageBag $messages = NULL);
 
}