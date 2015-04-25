<?php

namespace App\Service\Form\BranchProductPrice;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Product\ProductInterface;
use App\Repository\BranchProductPrice\BranchProductPriceInterface;
use App\Service\Form\AbstractForm;

class BranchProductPriceForm extends AbstractForm {

    /**
     * Product repository
     *
     * @var \App\Repository\Product\ProductInterface
     */
    protected $messageBag;
    protected $product;
    protected $branchProductPrice;

    public function __construct(ValidableInterface $validator, BranchProductPriceInterface $branchProductPrice, ProductInterface $product) {
        parent::__construct($validator);
        $this->messageBag = new MessageBag();
        $this->product = $product;
        $this->branchProductPrice = $branchProductPrice;
    }

    /**
     * Create an new product
     *
     * @return boolean
     */
    public function store($input) {

        if (!$this->valid($input))
            return false;

        $branchProductPrice = $this->branchProductPrice->create($input);

        if (isset($input['size']))
            $branchProductPrice->size()->save(new \BranchProductPriceSize(array('product_price_id' => $branchProductPrice->id, 'size_name' => $input['size'])));

        return $branchProductPrice;
    }
    
    /**
     * Delete an existing priceProduct
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->branchProductPrice->destroy($id);
    }

}
