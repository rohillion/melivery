<?php

namespace App\Service\Form\BranchProduct;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Branch\BranchInterface;
use App\Repository\BranchProduct\BranchProductInterface;
use App\Repository\BranchProductPrice\BranchProductPriceInterface;
use App\Service\Form\BranchProductPrice\BranchProductPriceForm;
use App\Service\Form\Product\ProductForm;
use App\Service\Form\AbstractForm;

class BranchProductForm extends AbstractForm {

    /**
     * Branch repository
     *
     * @var \App\Repository\Branch\BranchInterface
     */
    protected $messageBag;
    protected $branch;
    protected $branchProduct;
    protected $branchProductPrice;
    protected $branchProductPriceForm;
    protected $productForm;

    public function __construct(ValidableInterface $validator, BranchInterface $branch, BranchProductInterface $branchProduct, BranchProductPriceInterface $branchProductPrice, ProductForm $productForm, BranchProductPriceForm $branchProductPriceForm) {
        parent::__construct($validator);
        $this->messageBag = new MessageBag();
        $this->branch = $branch;
        $this->branchProduct = $branchProduct;
        $this->branchProductPrice = $branchProductPrice;
        $this->branchProductPriceForm = $branchProductPriceForm;
        $this->productForm = $productForm;
    }

    /**
     * Attach opening hours to Branch
     *
     * @return boolean
     */
    public function save(array $input) {

        //Start transaction
        \DB::beginTransaction();

        $product = $this->productForm->save($input);

        if (!$product) {
            $this->validator->errors = $this->productForm->errors();
            return false;
        }

        if (!$this->syncBranches($product))
            return false;

        if (!$this->syncPrices($product, $input))
            return false;

        \DB::commit();
        // End transaction

        return $product;
    }

    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function update($id, array $input) {

        $branchId = \Session::get('user.branch_id');

        //validate Branch by Commerce ID.
        $branchProduct = $this->branchProduct->find($id, ['*'], [], ['branch_id' => $branchId]);

        if (is_null($branchProduct)) {
            $this->messageBag->add('error', 'No hemos podido encontrar ese producto.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        //Start transaction
        \DB::beginTransaction();

        $product = $this->productForm->update($branchProduct->product_id, $input);

        if (!$product) {
            $this->validator->errors = $this->productForm->errors();
            return false;
        }

        if (!$this->syncBranches($product))
            return false;//TODO. Nunca entra.

        if (!$this->syncPrices($product, $input))
            return false;

        \DB::commit();
        // End transaction

        return $product;
    }

    /**
     * Change status active/inactive of a branchProduct
     *
     * @return Product
     */
    public function changeStatus($id) {

        $branchId = \Session::get('user.branch_id');

        //validate Branch by Commerce ID.
        $branchProduct = $this->branchProduct->find($id, ['*'], [], ['branch_id' => $branchId]);
        if (is_null($branchProduct)) {
            $this->messageBag->add('error', 'No hemos podido encontrar ese producto.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        return $this->branchProduct->edit($branchProduct->id, array('active' => !$branchProduct->active));
    }

    /**
     * Attach opening hours to Branch
     *
     * @return boolean
     */
    public function delete($branchProductId) {

        $branch_id = \Session::get('user.branch_id');

        $branchProduct = $this->branchProduct->find($branchProductId, ['*'], [], ['branch_id' => $branch_id]);

        if (is_null($branchProduct)) {
            $this->messageBag->add('error', 'No hemos encontrado ese producto.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        return $this->branchProduct->destroy($branchProduct->id);
    }

    private function syncBranches($product) {

        $branches = $this->branch->allByCommerceId($product->id_commerce);

        if (!$branches->isEmpty()) {

            $productBranch = array();

            foreach ($branches as $branch) {

                $productBranch[] = $branch->id;
            }

            $sync = $product->branches()->sync($productBranch);
        }

        //$sync['attached'];
        //$sync['detached'];

        return true;
    }

    private function syncPrices($product, $input) {

        $branchProducts = $this->branchProduct->all(['*'], [], ['product_id' => $product->id]);
        $oldPrices = array();

        foreach ($branchProducts as $branchProduct) {
            $branchProductPrice = $this->branchProductPrice->all(['*'], [], ['branch_product_id' => $branchProduct->id]);
            if (!$branchProductPrice->isEmpty())
                $oldPrices[] = $branchProductPrice;
        }

        if (isset($input['multisize'])) {

            foreach ($input['multiprice']['price'] as $key => $price) {

                foreach ($branchProducts as $branchProduct) {
                    $priceSize['branch_product_id'] = $branchProduct->id;
                    $priceSize['price'] = $price;
                    $priceSize['size'] = $input['multiprice']['size'][$key];

                    $productPrice = $this->branchProductPriceForm->store($priceSize);

                    if (!$productPrice) {
                        \DB::rollback();
                        $this->validator->errors = $this->branchProductPriceForm->errors();
                        return false;
                    }
                }
            }
        } else {

            foreach ($branchProducts as $branchProduct) {
                $price['branch_product_id'] = $branchProduct->id;
                $price['price'] = $input['price'];

                $productPrice = $this->branchProductPriceForm->store($price);

                if (!$productPrice) {
                    \DB::rollback();
                    $this->validator->errors = $this->branchProductPriceForm->errors();
                    return false;
                }
            }
        }

        foreach ($oldPrices as $prices) {
            foreach ($prices as $price) {
                $this->branchProductPriceForm->delete($price->id);
            }
        }

        return true;
    }

}
