<?php

namespace App\Service\Form\BranchArea;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Branch\BranchInterface;
use App\Repository\BranchArea\BranchAreaInterface;
use App\Service\Form\AbstractForm;

class BranchAreaForm extends AbstractForm {

    /**
     * Branch repository
     *
     * @var \App\Repository\Branch\BranchInterface
     */
    protected $messageBag;
    protected $branch;
    protected $branchArea;

    public function __construct(ValidableInterface $validator, BranchInterface $branch, BranchAreaInterface $branchArea) {
        parent::__construct($validator);
        $this->messageBag = new MessageBag();
        $this->branch = $branch;
        $this->branchArea = $branchArea;
    }

    /**
     * Attach opening hours to Branch
     *
     * @return boolean
     */
    public function save(array $input) {

        $commerce_id = \Session::get('user.id_commerce');

        $branch = $this->branch->findByCommerceId($input['branch_id'], $commerce_id, ['areas']);

        if (is_null($branch)) {
            $this->messageBag->add('error', 'No se ha encontrado esa sucursal.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        $num = !$branch->areas->isEmpty() ? $branch->areas->count() + 1 : 1;

        $branchArea = array(
            'branch_id' => $branch->id,
            'cost' => $input['cost'] ? $input['cost'] : 0,
            'min' => $input['min'] ? $input['min'] : 0,
            'area' => $input['area'],
            'area_name' => $input['area_name'] ? $input['area_name'] : 'Area de entrega ' . $num, //TODO. Lang
        );

        if (!$this->valid($branchArea))
            return false;
        
        $area = $this->branchArea->create($branchArea);
        
        //ACTIVATE DELIVERY IF IT IS OFF
        if(!$branch->delivery)
            $this->branch->edit($branch->id, ['delivery' => 1]);

        return $area;
    }

    /**
     * Attach opening hours to Branch
     *
     * @return boolean
     */
    public function update($area_id, array $input) {

        $commerce_id = \Session::get('user.id_commerce');

        $branch = $this->branch->findByCommerceId($input['branch_id'], $commerce_id);

        if (is_null($branch)) {
            $this->messageBag->add('error', 'No se ha encontrado esa sucursal.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        $area = $this->branchArea->find($area_id, array('*'), [], ['branch_id' => $branch->id]);

        if (is_null($area)) {
            $this->messageBag->add('error', 'No se ha encontrado ese area de entrega.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        $branchArea = array(
            'branch_id' => $branch->id,
            'cost' => $input['cost'] ? $input['cost'] : 0,
            'min' => $input['min'] ? $input['min'] : 0,
            'area' => $input['area'],
            'area_name' => $input['area_name'] ? $input['area_name'] : $area->area_name,
        );

        if (!$this->valid($branchArea))
            return false;

        return $this->branchArea->edit($area_id, $branchArea);
    }

    /**
     * Attach opening hours to Branch
     *
     * @return boolean
     */
    public function delete($area_id, $branch_id) {

        $commerce_id = \Session::get('user.id_commerce');

        $branch = $this->branch->findByCommerceId($branch_id, $commerce_id, ['areas']);

        if (is_null($branch)) {
            $this->messageBag->add('error', 'No se ha encontrado esa sucursal.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        $area = $this->branchArea->find($area_id, array('*'), [], ['branch_id' => $branch->id]);

        if (is_null($area)) {
            $this->messageBag->add('error', 'No se ha encontrado ese area de entrega.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        //DELETE DELIVERY IF THERE IS NO MORE AREAS ATTACHED
        if ($branch->areas->count() < 2)
            $this->branch->edit($branch_id, ['delivery' => 0]);

        return $this->branchArea->destroy($area_id);
    }

}
