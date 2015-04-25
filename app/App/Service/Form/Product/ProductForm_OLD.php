<?php

namespace App\Service\Form\Product;

use App\Service\Validation\ValidableInterface;
use App\Repository\Product\ProductInterface;
/* use App\Repository\Subcategory\SubcategoryInterface;
  use App\Repository\Tag\TagInterface; */
use App\Repository\Branch\BranchInterface;
use App\Service\Form\AbstractForm;
use Illuminate\Filesystem\Filesystem;

class ProductForm extends AbstractForm {

    /**
     * Product repository
     *
     * @var \App\Repository\Product\ProductInterface
     */
    protected $product;
    /* protected $subcategory;
      protected $tag; */
    protected $branch;

    public function __construct(ValidableInterface $validator, ProductInterface $product/* , SubcategoryInterface $subcategory, TagInterface $tag */, BranchInterface $branch) {
        parent::__construct($validator);
        $this->product = $product;
        /* $this->subcategory = $subcategory;
          $this->tag = $tag; */
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

        $commerceId = \Auth::user()->id_commerce;

        $res = array();

        if (!isset($input['subcategory']) || empty($input['subcategory'])) {
            $res['error'] = 'No hay una subcategor&iacute;a asignada';
            return $res;
        }

        if (!isset($input['product']) || empty($input['product'])) {
            $res['error'] = 'No hay productos asignados';
            return $res;
        }

        foreach ($input["product"] as $tagId => $productForm) {

            $product = $this->product->findProductByTagByCommerceId($tagId, $commerceId);

            $directoryPhotoPath = public_path() . '/upload/product_photo/';

            if (isset($productForm['enable'])) {

                if (!isset($productForm['price'])) {
                    $res['error'] = 'El precio es obligatorio';
                    return $res;
                }

                if (!$this->valid(['price' => $productForm['price'], 'category_id' => $input["category"], 'subcategory_id' => $input["subcategory"], 'tag_id' => $tagId])) {
                    $res['error'] = $this->validator->errors();
                    return $res;
                }

                if (!is_null($product)) {

                    $this->update($product->id, ['price' => $productForm['price']]);
                    $res['msg'] = \Lang::get('segment.product.message.success.edit');
                } else {

                    //se inserta nuevo
                    $product = $this->product->create([
                        'price' => $productForm['price'],
                        'id_commerce' => $commerceId,
                        'id_category' => $input['category'],
                        'subcategory_id' => $input['subcategory'],
                        'tag_id' => $tagId
                    ]);
                    $res['msg'] = \Lang::get('segment.product.message.success.store');
                }

                if (isset($productForm['file']) && !is_null($productForm['file'])) {

                    $this->savePhoto($directoryPhotoPath, $product, $productForm);
                }

                if (isset($productForm['attribute_type'])) {

                    if (!$this->syncAttributes($productForm, $product)) {
                        $res['error'] = 'Ocurrio un error al sincronizar atributos';
                        return $res;
                    }
                }

                $branches = $this->branch->allByCommerceId($commerceId);

                if (!$branches->isEmpty()) {
                    
                    $productBranch = array();
                    
                    foreach ($branches as $branch) {

                        $productBranch[] = $branch->id;
                    }

                    $product->branches()->sync($productBranch);
                }

                $res['redirect'] = '/product#p' . $product->id;
            } else {

                if (!is_null($product)) {

                    if ($this->delete($product->id)) {

                        $Filesystem = new Filesystem();

                        $Filesystem->deleteDirectory($directoryPhotoPath . $product->id);

                        $res['redirect'] = '/product';
                        $res['msg'] = \Lang::get('segment.product.message.success.delete');
                    }
                }
            }
        }

        //$input['tags'] = $this->processTags($input['tags']);
        return $res;
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

    private function syncAttributes($productForm, $product) {

        if (isset($productForm['attribute_type']['attr'])) {

            $product->attributes()->sync($productForm['attribute_type']['attr']);
        } else {

            //$res['error'] = 'Faltan atributos';
            return false;
        }

        if (isset($productForm['attribute_type']['rules'])) {

            if (isset($productForm['attribute_type']['id'])) {

                foreach ($productForm['attribute_type']['rules'] as $rule) {

                    $product->rules()->sync(array($rule => array('attribute_type_id' => $productForm['attribute_type']['id'])));
                }
            } else {

                //$res['error'] = 'Para asignar reglas hace falta el tipo de atributo';
                return false;
            }
        }

        return true;
    }

}