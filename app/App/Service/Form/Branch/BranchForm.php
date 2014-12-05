<?php

namespace App\Service\Form\Branch;

use App\Service\Validation\ValidableInterface;
use App\Repository\Branch\BranchInterface;
use App\Service\Form\BranchOpening\BranchOpeningForm;
use App\Service\Form\BranchPhone\BranchPhoneForm;
use App\Service\Form\BranchDealer\BranchDealerForm;
use App\Repository\Product\ProductInterface;
use App\Service\Form\AbstractForm;
use Illuminate\Support\MessageBag;
use Illuminate\Filesystem\Filesystem;

class BranchForm extends AbstractForm {

    /**
     * Branch repository
     *
     * @var \App\Repository\Branch\BranchInterface
     */
    protected $branch;
    protected $branchOpening;
    protected $branchPhone;
    protected $branchDealer;
    protected $product;

    public function __construct(ValidableInterface $validator, BranchInterface $branch, BranchOpeningForm $branchOpening, BranchPhoneForm $branchPhone, BranchDealerForm $branchDealer, ProductInterface $product) {
        parent::__construct($validator);
        $this->branch = $branch;
        $this->branchOpening = $branchOpening;
        $this->branchPhone = $branchPhone;
        $this->branchDealer = $branchDealer;
        $this->product = $product;
    }

    /**
     * Create an new branch
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->branch->all($columns, $with);
    }

    /**
     * Create an new branch
     *
     * @return boolean
     */
    public function save(array $input) {

        $user = \Auth::user();

        $input['commerce_id'] = $user->id_commerce;
        $input['city_id'] = $input['city'];

        if (!$this->valid($input)) {
            return false;
        }


        if (isset($input['delivery'])) {
            $input['delivery'] = $input['radio'];
            $input['area'] = $input['delivery_area'];
        }

        //Start transaction
        \DB::beginTransaction();

        // Branch creation
        $branch = $this->branch->create($input);

        if (!$branch) {
            return false;
        }

        // Branch static map position Google API
        //$this->staticMap($input, $branch);

        // Branch Opening Hours--------
        $branchOpening = $this->branchOpening->save($branch, $input['days']);


        if (!$branchOpening) {
            \DB::rollback();
            $this->validator->errors = $this->branchOpening->errors();
            return false;
        }


        // Branch Phone Numbers--------
        $branchPhone = $this->branchPhone->save($branch, $input['phone']);


        if (!$branchPhone) {
            \DB::rollback();
            $this->validator->errors = $this->branchPhone->errors();
            return false;
        }

        // Branch Dealers --------
        if (isset($input['delivery'])) {

            $branchDealer = $this->syncBranchDealers($branch, $input['dealer']);

            if (!$branchDealer) {
                \DB::rollback();
                $this->validator->errors = $this->branchDealer->errors();
                return false;
            }
        }

        $this->syncBranchUsers($branch, $user);
        $this->syncBranchProducts($branch, $user);

        \DB::commit();
        // End transaction

        return $branch;
    }

    /**
     * Update an existing branch
     *
     * @return boolean
     */
    public function update($id, array $input) {

        $commerceId = \Auth::user()->id_commerce;

        //validate Branch by Commerce ID.
        if (is_null($this->branch->findByCommerceId($id, $commerceId))) {
            $this->validator->errors = 'No hemos podido encontrar esa sucursal.';
            return false;
        }

        $input['commerce_id'] = $commerceId;
        $input['city_id'] = $input['city'];

        if (!$this->valid($input, $id)) {
            return false;
        }

        if (isset($input['delivery'])) {
            $input['delivery'] = $input['radio'];
            $input['area'] = $input['delivery_area'];
        }

        $phone = $input['phone'];
        $dealer = $input['dealer'];
        $days = $input['days'];

        unset($input['radio']);
        unset($input['delivery_area']);
        unset($input['phone']);
        unset($input['dealer']);
        unset($input['days']);
        unset($input['city']);

        //Start transaction
        \DB::beginTransaction();

        // Branch edit
        $branch = $this->branch->edit($id, $input);

        if (!$branch) {
            return false;
        }

        // Branch static map position Google API
        $this->staticMap($input, $branch);

        // Branch Opening Hours--------

        $branchOpening = $this->branchOpening->save($branch, $days);


        if (!$branchOpening) {
            \DB::rollback();
            $this->validator->errors = $this->branchOpening->errors();
            return false;
        }


        // Branch Phone Numbers--------
        $branchPhone = $this->branchPhone->save($branch, $phone);


        if (!$branchPhone) {
            \DB::rollback();
            $this->validator->errors = $this->branchPhone->errors();
            return false;
        }

        // Branch Dealers --------
        if (isset($input['delivery'])) {

            $branchDealer = $this->syncBranchDealers($branch, $dealer);

            if (!$branchDealer) {
                \DB::rollback();
                $this->validator->errors = $this->branchDealer->errors();
                return false;
            }
        }


        \DB::commit();
        // End transaction

        return $branch;
    }

    /**
     * Update an existing branch
     *
     * @return boolean
     */
    public function delete($id) {

        $commerceId = \Auth::user()->id_commerce;

        //validate Branch by Commerce ID.
        if (is_null($this->branch->findByCommerceId($id, $commerceId))) {
            $this->validator->errors = 'No hemos podido encontrar esa sucursal.';
            return false;
        }

        if ($this->branch->destroy($id)) {

            $directoryPhotoPath = public_path() . '/upload/branch_image/';

            $Filesystem = new Filesystem();
            $Filesystem->deleteDirectory($directoryPhotoPath . $id);
        }
    }

    /**
     * Create an new branch
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->branch->edit($id, $input);
    }

    /**
     * Create an new branch
     *
     * @return boolean
     */
    public function find($idBranch, $columns = array('*'), $entities = array()) {

        return $this->branch->find($idBranch, $columns, $entities);
    }

    /**
     * Create an new branch
     *
     * @return boolean
     */
    public function allByCommerceId($branchId) {

        return $this->branch->allByCommerceId($branchId);
    }

    public function syncBranchUsers($branch, $user) {

        return $branch->users()->save($user);
    }

    public function syncBranchProducts($branch, $user) {

        $products = $this->product->allByCommerceId($user->id_commerce);

        if (!$products->isEmpty()) {

            $productBranch = array();

            foreach ($products as $product) {

                $productBranch[] = $product->id;
            }

            $branch->products()->sync($productBranch);
        }

        return $branch;
    }

    public function syncBranchDealers($branch, $dealers) {

        $branch = $this->branch->find($branch->id, ['*'], ['dealers']);

        /*
         * check if there are dealers to remove and proceed if needed, if not, we update them.
         */
        foreach ($branch->dealers as $branchDealer) {

            $delete = true;

            if (isset($dealers['edit'])) {

                foreach ($dealers['edit'] as $dealerId => $dealerName) {

                    if ($branchDealer->id == $dealerId) {
                        $delete = false;
                        break;
                    }
                }
            }

            if ($delete) { //delete
                if (!$this->branchDealer->delete($branchDealer->id))
                    return false;
            } else { //update
                $input = array(
                    'branch_id' => $branch->id,
                    'dealer_name' => $dealerName
                );

                if (!$this->branchDealer->update($branchDealer->id, $input))
                    return false;
            }
        }

        /*
         * add new dealers
         */
        if (isset($dealers['new'])) {

            foreach ($dealers['new'] as $dealerId => $dealerName) {

                $input = array(
                    'branch_id' => $branch->id,
                    'dealer_name' => $dealerName
                );

                if (!$this->branchDealer->save($input))
                    return false;
            }
        }


        return true;
    }

    private function staticMap($input, $branch) {

        $Filesystem = new Filesystem();

        $small = 'http://maps.googleapis.com/maps/api/staticmap?center=' . $branch->position . '&markers=color:red|' . $branch->position . '&zoom=15&size=245x245&maptype=ROADMAP&sensor=false';
        $directoryPhotoPath = public_path() . '/upload/branch_image/';

        $branchPhotoPath = $directoryPhotoPath . $branch->id;

        if (!$Filesystem->exists($branchPhotoPath)) {

            $Filesystem->makeDirectory($branchPhotoPath, 0777);
        }

        $smallName = $branch->id . '.png';

        $Filesystem->copy($small, $branchPhotoPath . '/' . $smallName);

        if (isset($input['delivery'])) {

            $big = 'http://maps.googleapis.com/maps/api/staticmap?center=' . $branch->position . '&markers=color:red|' . $branch->position . '&zoom=15&size=454x376&maptype=ROADMAP&sensor=false&path=color:red|weight:1|fillcolor:white|' . str_replace(' ', ',', str_replace(',', '|', $branch->area));
            $bigName = $branch->id . '_area.png';
            $Filesystem->copy($big, $branchPhotoPath . '/' . $bigName);
        } else {

            $bigName = $branch->id . '_area.png';
            if ($Filesystem->exists($branchPhotoPath . '/' . $bigName))
                $Filesystem->delete($branchPhotoPath . '/' . $bigName);
        }

        return true;
    }

    public function findByCommerceId($branchId, $commerceId, $entities = array()) {

        return $this->branch->findByCommerceId($branchId, $commerceId, $entities);
    }

}
