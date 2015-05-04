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

            $method = Session::get('delivery') ? 'delivery' : 'pickup';

            $filters = array(
                'id_category' => $category->id,
                'subcategory_id' => Input::get('subcategory'),
                'tag_id' => Input::get('tag'),
                'sort' => Input::get('sort')
            );
            
            $sort = array(
                'by' => Input::get('sort','price'),
                'order'=> Input::get('order','asc')
            );

            $data['products'] = $this->menu->products($position, $method, $filters, $items, $sort);

        }
        
        return View::make('menu.main', $data);
    }
    
    public function changeType($delivery){
        
        if($delivery){
            Session::put('delivery', TRUE);
        }else{
            Session::put('delivery', FALSE);
        }
        
        return Redirect::back();
    }

}
