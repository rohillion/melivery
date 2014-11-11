<?php

namespace App\Service\Form\AttributeOrderProduct;

use App\Service\Validation\ValidableInterface;
use App\Repository\OrderProduct\OrderProductInterface;
use App\Service\Form\AbstractForm;

class AttributeOrderProductForm extends AbstractForm {

    /**
     * AttributeOrderProduct repository
     *
     * @var \App\Repository\AttributeOrderProduct\AttributeOrderProductInterface
     */
    protected $orderproduct;

    public function __construct(ValidableInterface $validator, OrderProductInterface $orderproduct) {
        parent::__construct($validator);
        $this->orderproduct = $orderproduct;
    }

    /**
     * Sync attributes with orderproduct relation
     *
     * @return boolean
     */
    public function syncAttributes($attributes, $orderProductId) {

        $orderProduct = $this->orderproduct->find($orderProductId);

        if (is_null($orderProduct))
            return false;

        if (is_null($attributes))
            return false;

        foreach ($attributes as $attribute) {

            $input = array(
                'order_product_id' => $orderProduct->id,
                'attribute_id' => $attribute
            );

            if (!$this->valid($input)) {
                return false;
            }
        }

        return $orderProduct->attributes()->sync($attributes);
    }

}
