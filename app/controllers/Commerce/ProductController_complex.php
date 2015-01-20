<?php

//use Illuminate\Support\MessageBag;
use App\Service\Form\ProductController\ProductForm;
use App\Repository\Category\CategoryInterface;

class ProductController extends BaseController {

    protected $product;
    protected $category;

    public function __construct(ProductForm $product, CategoryInterface $category) {
        $this->product = $product;
        $this->category = $category;
    }

    public function index() {

        $data['categories'] = $this->category->all(['*'], ['subcategories.attributes.attribute_types']);
        $data['products'] = $this->product->allByCommerceId(Auth::user()->id_commerce);

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

        $res = $this->product->save(Input::all());

        if (!isset($res['error'])) {
            // Success!
            return Redirect::to($res['redirect'])
                            ->withSuccess($res['msg'])
                            ->with('status', 'success');
        }

        return Redirect::to('/product/create')
                        ->withInput()
                        ->withErrors($res['error'])
                        ->with('status', 'error');
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
