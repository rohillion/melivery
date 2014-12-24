<?php

namespace App\Service\Form\BranchArea;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Branch\BranchInterface;
use App\Service\Form\AbstractForm;

class BranchAreaForm extends AbstractForm {

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
    public function save($branch, array $area) {
        
        $sync = array();

        if (!is_null($area)) {

            $branch->areas()->delete();

            for ($i = 0; $i < count($area['radio']); $i++) {

                $branchArea = array(
                    'branch_id' => $branch->id,
                    'cost' => isset($area['cost']) && isset($area['cost'][$i]) ? $area['cost'][$i] : NULL,
                    'min' => isset($area['min']) && isset($area['min'][$i]) ? $area['min'][$i] : NULL,
                    'area' => isset($area['area']) && isset($area['area'][$i]) ? $area['area'][$i] : NULL,
                    'radio' => isset($area['radio']) && isset($area['radio'][$i]) ? $area['radio'][$i] : NULL,
                );

                

                if (!$this->valid($branchArea)) {
                    return false;
                }

                array_push($sync, new \BranchArea($branchArea));
            }

            return $branch->areas()->saveMany($sync);
        }

        $this->validator->errors = new MessageBag(['area' => 'Debe haber al menos un area de entrega']);

        return false;
    }

}
