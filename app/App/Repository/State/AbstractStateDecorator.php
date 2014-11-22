<?php namespace App\Repository\State;

abstract class AbstractStateDecorator implements StateInterface {

    protected $nextState;

    public function __construct(StateInterface $nextState)
    {
        $this->nextState = $nextState;
    }

    /**
     * {@inheritdoc}
     */
    /*public function byCountryCode($id)
    {
        return $this->nextState->byCountryCode($id);
    }*/

}