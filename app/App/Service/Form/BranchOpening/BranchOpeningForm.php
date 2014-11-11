<?php

namespace App\Service\Form\BranchOpening;

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

    public function __construct(ValidableInterface $validator, BranchInterface $branch) {
        parent::__construct($validator);
        $this->branch = $branch;
    }

    /**
     * Attach opening hours to Branch
     *
     * @return boolean
     */
    public function save($branch, array $days) {
        
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
