<?php

namespace App\Service\Form\BranchPhone;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Branch\BranchInterface;
use App\Service\Form\AbstractForm;

class BranchPhoneForm extends AbstractForm {

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
    public function save($branch, array $phones) {

        $sync = array();

        if (isset($phones['primary']) && !empty($phones['primary'])) {

            $branch->phones()->delete();

            foreach ($phones as $phone) {
                
                if (!empty($phone)) {

                    $input = array(
                        'branch_id' => $branch->id,
                        'number' => $phone,
                    );

                    
                    if (!$this->valid($input)) {
                        return false;
                    }

                    array_push($sync, new \BranchPhone($input));
                }
            }

            return $branch->phones()->saveMany($sync);
        }
        
        $this->validator->errors = new MessageBag(['phone'=>'Debe haber al menos un numero de tel&eacute;fono']);

        return false;
    }

}
