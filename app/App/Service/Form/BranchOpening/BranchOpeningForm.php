<?php

namespace App\Service\Form\BranchOpening;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Branch\BranchInterface;
use App\Service\Form\AbstractForm;

class BranchOpeningForm extends AbstractForm {

    /**
     * Branch repository
     *
     * @var \App\Repository\Branch\BranchInterface
     */
    protected $branch;
    protected $messageBag;

    public function __construct(ValidableInterface $validator, BranchInterface $branch) {
        parent::__construct($validator);
        $this->branch = $branch;
        $this->messageBag = new MessageBag();
    }

    /**
     * Attach opening hours to Branch
     *
     * @return boolean
     */
    public function save($branch_id, array $days) {
        
        $commerceId = \Auth::user()->id_commerce;
        
        //validate Branch by Commerce ID.
        $branch = $this->branch->findByCommerceId($branch_id, $commerceId);

        if (is_null($branch)) {
            $this->messageBag->add('error', 'No hemos podido encontrar esa sucursal.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }
        
        $branch->openingHours()->delete();

        $sync = array();

        foreach ($days as $dayIndex => $day) {

            if (isset($day['open'])) {
                
                $input = array(
                    'day_id' => $dayIndex,
                    'open' => 1,
                    'open_time' => isset($day['from'])? $day['from'] : NULL,
                    'close_time' => isset($day['to'])? $day['to'] : NULL,
                    'branch_id' => $branch->id,
                );
            } else {
                
                $input = array(
                    'day_id' => $dayIndex,
                    'open' => 0,
                    'branch_id' => $branch->id,
                );
            }

            if ( ! $this->valid($input) ) return false;
            
            array_push($sync, new \BranchOpening($input));
            
        }

        return $branch->openingHours()->saveMany($sync);
    }

}
