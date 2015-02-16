<?php

namespace App\Service\Form\ProductPrice;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Product\ProductInterface;
use App\Repository\ProductPrice\ProductPriceInterface;
use App\Service\Form\AbstractForm;

class ProductPriceForm extends AbstractForm {

    /**
     * Product repository
     *
     * @var \App\Repository\Product\ProductInterface
     */
    protected $messageBag;
    protected $product;
    protected $productPrice;

    public function __construct(ValidableInterface $validator, ProductPriceInterface $productPrice, ProductInterface $product) {
        parent::__construct($validator);
        $this->messageBag = new MessageBag();
        $this->product = $product;
        $this->productPrice = $productPrice;
    }

    /**
     * Create an new product
     *
     * @return boolean
     */
    public function store($input) {

        if (!$this->valid($input))
            return false;

        $productPrice = $this->productPrice->create($input);

        if (isset($input['size']))
            $productPrice->productPriceSize()->save(new \ProductPriceSize(array('product_price_id' => $productPrice->id, 'size_name' => $input['size'])));

        return $productPrice;
    }

}
