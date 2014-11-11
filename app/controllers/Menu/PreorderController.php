<?php

use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Service\Form\Preorder\PreorderForm;

class PreorderController extends BaseController {

    protected $product;
    protected $category;
    protected $commerce;
    protected $preorder;

    public function __construct(ProductInterface $product, CategoryInterface $category, CommerceInterface $commerce, PreorderForm $preorder) {
        $this->product = $product;
        $this->category = $category;
        $this->commerce = $commerce;
        $this->preorder = $preorder;
    }

    public function store() {

        $order = $this->preorder->process(Auth::user()->id, Session::get('orders'));
        
        if (!isset($order['error'])) {

            // Success!
            return Redirect::to(Request::server('HTTP_REFERER'))
                            ->withSuccess(Lang::get('segment.order.message.success.save'))
                            ->with('status', 'success');
        }

        return Redirect::to(Request::server('HTTP_REFERER'))
                        ->withError($order['error'])
                        ->with('status', 'error');
    }

    public function confirm() {

        $data['orders'] = $this->preorder->all(Session::get('orders'));
        
        return View::make('menu.preorder.confirm',$data);
    }

    public function addItem() {

        $productForm = Input::get('product');

        $res = $this->preorder->add($productForm);

        return Redirect::to(Request::server('HTTP_REFERER'));
    }

    public function configItem() {

        $productForm = Input::get('product');

        $res = $this->preorder->config($productForm);

        return Redirect::to(Request::server('HTTP_REFERER'));
    }

    public function removeItem() {

        $input = array(
            'branch' => Input::get('branch'),
            'item' => Input::get('item')
        );

        $res = $this->preorder->remove($input);

        return Redirect::to(Request::server('HTTP_REFERER'));
    }

}
