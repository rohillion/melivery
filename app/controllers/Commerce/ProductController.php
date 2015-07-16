<?php

//use Illuminate\Support\MessageBag;
use App\Service\Form\Product\ProductForm;
use App\Service\Form\BranchProduct\BranchProductForm;
use App\Repository\Product\ProductInterface;
use App\Repository\BranchProduct\BranchProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Country\CountryInterface;
use App\Repository\Branch\BranchInterface;
use App\Repository\Rule\RuleInterface;

class ProductController extends BaseController {

    protected $product;
    protected $branchProduct;
    protected $productForm;
    protected $branchProductForm;
    protected $category;
    protected $country;
    protected $branch;
    protected $rule;

    public function __construct(BranchInterface $branch, ProductInterface $product, BranchProductInterface $branchProduct, ProductForm $productForm, BranchProductForm $branchProductForm, CategoryInterface $category, CountryInterface $country, RuleInterface $rule) {
        $this->product = $product;
        $this->productForm = $productForm;
        $this->branchProduct = $branchProduct;
        $this->branchProductForm = $branchProductForm;
        $this->category = $category;
        $this->country = $country;
        $this->branch = $branch;
        $this->rule = $rule;
    }

    public function index() {

        $data['productsByCategory'] = $data['rules'] = array();

        $data['categories'] = $this->category->all(['*'], [], ['country_id' => Session::get('user.country_id'), 'active' => 1]);

        $rules = $this->rule->all(['*'], ['rule_type'], ['active' => 1]);

        foreach ($rules as $rule) {
            $data['rules'][$rule->rule_type->rule_type_name][] = $rule;
        }

        $branch = $this->branch->find(Session::get('user.branch_id'), ['*'], ['branchProductsAll.product.categories.subcategories', 'branchProductsAll.product.tags', 'branchProductsAll.product.attributes.attribute_types', 'branchProductsAll.prices.size']);

        if (isset($branch->branchProductsAll)) {
            foreach ($branch->branchProductsAll as $branchProduct) {
                $data['productsByCategory'][$branchProduct->product->categories->category_name]['data'] = $branchProduct->product->categories;
                $data['productsByCategory'][$branchProduct->product->categories->category_name]['branchProducts'][] = $branchProduct;
            }
        }

        return View::make("commerce.product.index", $data);
    }

    /**
     * Create product form processing
     * POST /product/category
     */
    public function view($branch_product_id = NULL) {

        $product = NULL;

        if (!is_null($branch_product_id))
            $product = $this->branchProduct->find($branch_product_id, ['*'], ['prices.size', 'product.tags', 'product.attributes', 'product.rules.rule_type'], ['branch_id' => Session::get('user.branch_id')]);

        // Success!
        return Response::json(array(
                    'status' => TRUE,
                    'type' => 'success',
                    'product' => $product
                        )
        );
    }

    /**
     * Create product form processing
     * POST /product/category
     */
    public function store() {

        $product = $this->branchProductForm->save(Input::all());

        if ($product) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.product.message.success.store'),
                        'product' => $product)
            );
        }

        // Error!
        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchProductForm->errors()->all())
        );
    }

    /**
     * Edit product form processing
     * POST /product/{id}
     */
    public function update($id) {

        $product = $this->branchProductForm->update($id, Input::all());

        if ($product) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.product.message.success.update'),
                        'product' => $product)
            );
        }

        // Error!
        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchProductForm->errors()->all())
        );
    }

    /**
     * Edit product form processing
     * POST /product/{id}
     */
    public function changeStatus($id) {

        $product = $this->branchProductForm->changeStatus($id);

        if ($product) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.product.message.success.update'),
                        'product' => $product)
            );
        }

        // Error!
        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchProductForm->errors()->all())
        );
    }

    /**
     * Upload product image
     * POST /product/image
     */
    public function imagetmp() {

        $image = $this->productForm->saveTmpPhoto(Input::file('image'));

        if ($image) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.image.message.success.store'),
                        'image' => $image)
            );
        }

        // Error!
        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->productForm->errors()->all())
        );
    }

    /**
     * Delete product form processing
     * DELETE /product
     */
    public function delete($product_id) {

        if ($this->branchProductForm->delete($product_id)) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.product.message.success.delete'))
            );
        }

        // Error!
        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchProductForm->errors()->all())
        );
    }

}
