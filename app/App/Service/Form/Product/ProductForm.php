<?php

namespace App\Service\Form\Product;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Product\ProductInterface;
use App\Repository\BranchProduct\BranchProductInterface;
use App\Repository\BranchProductPrice\BranchProductPriceInterface;
use App\Service\Form\BranchProductPrice\BranchProductPriceForm;
use App\Repository\Branch\BranchInterface;
use App\Service\Form\AbstractForm;
use Illuminate\Filesystem\Filesystem;

class ProductForm extends AbstractForm {

    const IMAGE = '/upload/product_image/';
    const IMAGETMP = '/upload/product_image_tmp/';

    /**
     * Product repository
     *
     * @var \App\Repository\Product\ProductInterface
     */
    protected $messageBag;
    protected $product;
    protected $branchProduct;
    protected $branch;

    public function __construct(ValidableInterface $validator, ProductInterface $product, BranchInterface $branch, BranchProductInterface $branchProduct) {
        parent::__construct($validator);
        $this->messageBag = new MessageBag();
        $this->product = $product;
        $this->branchProduct = $branchProduct;
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

        if ($input['imagechanged'])
            $data['image'] = $this->syncPhoto();

        $product = $this->product->create($data);

        if (isset($input['attribute_type'])) {
            if (!$this->syncAttributes($product, $input))
                return false;

            if (!$this->syncRules($product, $input))
                return false;
        }

        return $product;
    }

    /**
     * Update an existing product
     *
     * @return boolean
     */
    public function update($id, array $input) {

        $commerceId = \Session::get('user.id_commerce');

        //validate Branch by Commerce ID.
        if (is_null($this->product->find($id, ['*'], [], ['id_commerce' => $commerceId]))) {
            $this->messageBag->add('error', 'No hemos podido encontrar ese producto.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        $data = [
            'id_commerce' => $commerceId,
            'id_category' => $input['category'],
            'subcategory_id' => $input['subcategory'],
            'tag_id' => $input['tag']
        ];

        if (!$this->valid($data, $id))
            return false;

        if ($input['imagechanged'])
            $data['image'] = $this->syncPhoto();

        $product = $this->product->edit($id, $data);

        if (isset($input['attribute_type'])) {
            if (!$this->syncAttributes($product, $input))
                return false;

            if (!$this->syncRules($product, $input))
                return false;
        }

        return $product;
    }

    /**
     * Update an existing branch
     *
     * @return boolean
     */
    public function delete($id) {

        $commerceId = \Session::get('user.id_commerce');

        //validate Branch by Commerce ID.
        if (is_null($this->product->find($id, ['*'], [], ['id_commerce' => $commerceId]))) {
            $this->messageBag->add('error', 'No hemos podido encontrar ese producto.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        return $this->product->destroy($id);
    }

    public function saveTmpPhoto($image) {

        $imagePath = public_path() . self::IMAGETMP;
        $imageName = md5(\Session::getId()) . '.' . 'jpg';

        $image->move($imagePath, $imageName);

        //$fitImg = \Image::make($imagePath . $imageName)->fit(360, 240);
        $fitImg = \Image::make($imagePath . $imageName)->fit(555, 370);
        $fitImg->save($imagePath . '/' . $imageName);

        return self::IMAGETMP . $imageName;
    }

    public function syncPhoto() {

        $imageTmpPath = public_path() . self::IMAGETMP;
        $imageTmpName = md5(\Session::getId()) . '.' . 'jpg';

        $imagePath = public_path() . self::IMAGE;
        $imageName = md5(mt_rand()) . '.' . 'jpg'; //TODO mejorar rand

        $Filesystem = new Filesystem();

        if (!$Filesystem->exists($imagePath))
            $Filesystem->makeDirectory($imagePath);

        //$productPhotoName = str_random(10) . '.' . $productForm['file']->getClientOriginalExtension();

        if ($Filesystem->exists($imageTmpPath . $imageTmpName))
            $Filesystem->copy($imageTmpPath . $imageTmpName, $imagePath . $imageName);


        return $imageName;
    }

    private function syncAttributes($product, $input) {

        if (isset($input['attribute_type']['attr'])) {

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
        }

        return true;
    }

    private function syncRules($product, $input) {

        foreach ($input['attribute_type']['rule'] as $attrTypeId => $rules) {

            foreach ($rules as $rule) {
                if ($rule)
                    $rulesToSync[$rule] = ['attribute_type_id' => $attrTypeId];
            }
            if (isset($rulesToSync))
                $product->rules()->sync($rulesToSync);
        }

        return true;
    }

}
