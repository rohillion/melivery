<?php

//use Illuminate\Support\MessageBag;
use App\Service\Form\ProductController\productForm;
use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Branch\BranchInterface;

class ProductController extends BaseController {

    protected $product;
    protected $productForm;
    protected $category;
    protected $branch;

    public function __construct(BranchInterface $branch, ProductInterface $product, productForm $productForm, CategoryInterface $category) {
        $this->product = $product;
        $this->productForm = $productForm;
        $this->category = $category;
        $this->branch = $branch;
    }

    public function index() {

        $data['productsByCategory'] = array();

        $data['categories'] = $this->category->all(['*'], [], ['active' => 1]);

        //$data['productCategories'] = $this->product->listProductCategoriesByCommerceId(Session::get('user.id_commerce'));

        $branch = $this->branch->find(Session::get('user.branch_id'), ['*'], ['products.categories.subcategories', 'products.tags', 'products.attributes.attribute_types', 'products.productPrice.productPriceSize']);

        foreach ($branch->products as $product) {
            $data['productsByCategory'][$product->categories->category_name]['data'] = $product->categories;
            $data['productsByCategory'][$product->categories->category_name]['products'][] = $product;
        }

        //$data['products'] = $this->productForm->allByCommerceId(Session::get('user.id_commerce'));

        return View::make("commerce.product.index", $data);
    }

    /**
     * Create product form processing
     * POST /product/category
     */
    public function create($category_id = NULL) {

        $data['category'] = NULL;

        $data['categories'] = $this->category->allActive();

        if ($category_id)
            $data['category'] = $this->category->findWithCustomTag($category_id, Auth::user()->id_commerce);

        return View::make("commerce.product.create", $data);
    }

    /**
     * Create product form processing
     * POST /product/category
     */
    public function store() {

        $product = $this->productForm->save(Input::all());

        if ($product) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.product.message.success.store'),
                        'product' => $product->toJson())
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
     * Edit category form processing
     * POST /category
     */
    public function update($id) {

        $input['category_name'] = Input::get('category_name');

        if ($this->category->edit($id, $input)) {
            // Success!
            return Redirect::to('/category')
                            ->withSuccess(Lang::get('segment.category.message.success.edit'))
                            ->with('status', 'success');
        }

        return Redirect::to('/category')
                        ->withInput()
                        ->withErrors($this->category->errors())
                        ->with('status', 'error');
    }

}
