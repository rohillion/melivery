<?php

use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Service\Form\Menu\MenuForm;
use App\Service\Form\Preorder\PreorderForm;
use \Illuminate\Database\Eloquent\Collection;

class MenuController extends BaseController {

    protected $product;
    protected $category;
    protected $commerce;
    protected $menu;
    protected $preorder;

    public function __construct(ProductInterface $product, CategoryInterface $category, CommerceInterface $commerce, MenuForm $menu, PreorderForm $preorder) {
        $this->product = $product;
        $this->category = $category;
        $this->commerce = $commerce;
        $this->menu = $menu;
        $this->preorder = $preorder;
    }

    public function index($category = NULL, $items = 10) {

        // We define empty collections for the views in case no match
        $data = array(
            'pickupProducts' => new Collection,
            'deliveryProducts' => new Collection,
            'categories' => $this->category->allActive(),
            'orders' => $this->preorder->all(Session::get('orders')),
            'subcategories' => NULL
        );

        // If no category selected return to landing page
        if (is_null($category))
            return Redirect::to(Config::get('app.url'));

        // Select category by name and if null empty default value will show up
        $category = $this->category->findByName($category);

        if (!is_null($category)) {

            $data['subcategories'] = $category->subcategories;

            $position = Cookie::get('position');

            $method = Input::get('method') ? Input::get('method') : 'delivery';

            $filters = array(
                'id_category' => $category->id,
                'subcategory_id' => Input::get('subcategory'),
                'tag_id' => Input::get('tag')
            );

            $data = array_merge($data, $this->menu->products($position, $method, $filters, $items));
        }
        
        return View::make('menu.main', $data);
    }

    public function write_order() {

        $productForm = Input::get('product');
        
        $res = $this->menu->takeOrder($productForm);
        
        return Redirect::to(Request::server('HTTP_REFERER'));
    }

    public function erase_order($product_id = NULL) {
        
        $res = $this->menu->eraseOrder($product_id);
        
        return Redirect::to(Request::server('HTTP_REFERER'));
    }

}
