<?php

namespace App\Service\Form\ProductController;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Product\ProductInterface;
use App\Service\Form\ProductPrice\ProductPriceForm;
use App\Repository\Branch\BranchInterface;
use App\Service\Form\AbstractForm;
use Illuminate\Filesystem\Filesystem;

class ProductForm extends AbstractForm {

    /**
     * Product repository
     *
     * @var \App\Repository\Product\ProductInterface
     */
    protected $messageBag;
    protected $product;
    protected $productPrice;
    protected $branch;

    public function __construct(ValidableInterface $validator, ProductInterface $product, ProductPriceForm $productPrice, BranchInterface $branch) {
        parent::__construct($validator);
        $this->messageBag = new MessageBag();
        $this->product = $product;
        $this->productPrice = $productPrice;
        $this->branch = $branch;
    }

    /**
     * Create an new product
     *
     * @return boolean
     */
    public function all($columns = array('*'), $with = array()) {

        return $this->product->all($columns, $with);
    }

    /**
     * Create an new product
     *
     * @return boolean
     */
    public function allByCommerceId($commerceId) {

        return $this->product->allByCommerceId($commerceId);
    }

    /**
     * Create an new product
     *
     * @return boolean
     */
    public function allToCustomer($commerceId) {

        return $this->product->allToCustomer($commerceId);
    }

    /**
     * Create an new product
     *
     * @return boolean
     */
    public function save(array $input) {

        $commerceId = \Session::get('user.id_commerce');

        $data = [
            'id_commerce' => $commerceId,
            'id_category' => $input['category'],
            'subcategory_id' => $input['subcategory'],
            'tag_id' => $input['tag']
        ];

        if (!$this->valid($data))
            return false;

        //Start transaction
        \DB::beginTransaction();

        $product = $this->product->create($data);

        if (!$this->syncPrices($product, $input))
            return false;

        if (!$this->syncBranches($product, $commerceId))
            return false;

        if (isset($input['attribute_type'])) {
            if (!$this->syncAttributes($product, $input))
                return false;

            if (!$this->syncRules($product, $input))
                return false;
        }


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

        /* if (!$this->valid($input, $id)) {
          return false;
          } */

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->product->edit($id, $input);
    }

    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function delete($id) {

        //$input['tags'] = $this->processTags($input['tags']);
        return $this->product->destroy($id);
    }

    /**
     * Create an new product
     *
     * @return boolean
     */
    public function changeStatus($id, array $input) {

        return $this->product->edit($id, $input);
    }

    private function savePhoto($directoryPhotoPath, $product, $productForm) {

        $Filesystem = new Filesystem();

        $productPhotoPath = $directoryPhotoPath . $product->id;

        if (!$Filesystem->exists($productPhotoPath)) {

            $Filesystem->makeDirectory($productPhotoPath);
        }

        $productPhotoName = str_random(10) . '.' . $productForm['file']->getClientOriginalExtension();

        $productForm['file']->move($productPhotoPath, $productPhotoName);

        $fitImg = \Image::make($productPhotoPath . '/' . $productPhotoName)->fit(360, 240);
        $fitImg->save($productPhotoPath . '/' . $productPhotoName);

        $this->update($product->id, ['image' => $productPhotoName]);

        return true;
    }

    private function syncPrices($product, $input) {

        if (isset($input['multisize'])) {

            foreach ($input['multiprice']['price'] as $key => $price) {

                $priceSize['product_id'] = $product->id;
                $priceSize['price'] = $price;
                $priceSize['size'] = $input['multiprice']['size'][$key];

                $productPrice = $this->productPrice->store($priceSize);

                if (!$productPrice) {
                    \DB::rollback();
                    $this->validator->errors = $this->productPrice->errors();
                    return false;
                }
            }
        } else {

            $price['product_id'] = $product->id;
            $price['price'] = $input['price'];

            $productPrice = $this->productPrice->store($price);

            if (!$productPrice) {
                \DB::rollback();
                $this->validator->errors = $this->productPrice->errors();
                return false;
            }
        }

        return true;
    }

    private function syncBranches($product, $commerceId) {

        $branches = $this->branch->allByCommerceId($commerceId);

        if (!$branches->isEmpty()) {

            $productBranch = array();

            foreach ($branches as $branch) {

                $productBranch[] = $branch->id;
            }

            $product->branches()->sync($productBranch);
        }

        return true;
    }

    private function syncAttributes($product, $input) {

        foreach ($input['attribute_type']['attr'] as $attrTypeId => $attrItems) {

            if (isset($attrItems)) {

                foreach ($attrItems as $attrId => $price) {

                    $attrToSync[$attrId] = ['aditional_price' => $price];
                }

                $product->attributes()->sync($attrToSync);
            } else {

                \DB::rollback();
                $this->validator->errors = 'Faltan atributos';
                return false;
            }
        }


        return true;
    }

    private function syncRules($product, $input) {

        foreach ($input['attribute_type']['rule'] as $attrTypeId => $rules) {

            foreach ($rules as $rule) {
                if($rule)
                    $rulesToSync[$rule] = ['attribute_type_id' => $attrTypeId];
            }
            if(isset($rulesToSync))
                $product->rules()->sync($rulesToSync);
        }

        return true;
    }

}
