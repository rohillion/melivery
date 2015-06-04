<?php

use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Service\Form\Preorder\PreorderForm;
use App\Service\Form\Customer\CustomerForm;

class PreorderController extends BaseController {

    protected $product;
    protected $category;
    protected $commerce;
    protected $preorder;
    protected $customer;

    public function __construct(ProductInterface $product, CategoryInterface $category, CommerceInterface $commerce, PreorderForm $preorder, CustomerForm $customer) {
        $this->product = $product;
        $this->category = $category;
        $this->commerce = $commerce;
        $this->preorder = $preorder;
        $this->customer = $customer;
    }

    public function show() {

        $viewPath = Input::get('confirm') ? 'menu.preorder.basket' : 'landing.basket';

        $data['orders'] = $this->preorder->all(Session::get('orders'));

        $view = View::make($viewPath, $data);

        $orders = $view->render();
        // Success!
        return Response::json(array(
                    'status' => TRUE,
                    'type' => 'success',
                    'message' => Lang::get('segment.product.message.success.store'),
                    'basket' => $orders)
        );
    }

    public function store() {

        /* $queue = CommonEvents::getLastAction();

          //check if queue data when came from login
          if ($queue && $queue['action'] == Route::getCurrentRoute()->getAction()['controller']) {

          CommonEvents::setLastAction(FALSE); //Delete last attempt action;
          $payWith = $queue['post'];
          } else {

          $payWith = Input::only('pay', 'amount');
          } */

        $order = $this->preorder->process(Session::get('user.id'), Session::get('orders'), Input::only('pay', 'amount'));

        if ($order) {

            // Success!
            return Redirect::to(Request::server('HTTP_REFERER'))
                            ->withSuccess(Lang::get('segment.order.message.success.save'))
                            ->with('status', 'success');
        }

        return Redirect::to(Request::server('HTTP_REFERER'))
                        ->withErrors($this->preorder->errors())
                        ->with('status', 'error');
    }

    public function confirm() {

        $data['orders'] = $this->preorder->all(Session::get('orders'));

        return View::make('menu.preorder.confirm', $data);
    }

    public function addItem() {

        $viewPath = Input::get('confirm') ? 'menu.preorder.basket' : 'landing.basket';

        $basket = $this->preorder->add(Input::all());

        if ($basket) {

            $data['orders'] = $this->preorder->all($basket);

            $view = View::make($viewPath, $data);

            $orders = $view->render();
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => $this->preorder->messages()->all(),
                        'basket' => $orders)
            );
        }

        // Error!
        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->preorder->errors()->all())
        );
    }

    public function configQty() {

        $viewPath = Input::get('confirm') ? 'menu.preorder.basket' : 'landing.basket';

        $input = array(
            'branch' => Input::get('branch'),
            'item' => Input::get('item'),
            'qty' => Input::get('qty')
        );

        $basket = $this->preorder->configQty($input);

        $data['orders'] = $this->preorder->all($basket);

        $view = View::make($viewPath, $data);

        $orders = $view->render();
        // Success!
        return Response::json(array(
                    'status' => TRUE,
                    'type' => 'success',
                    'message' => Lang::get('segment.product.message.success.config'),
                    'basket' => $orders)
        );
    }

    public function configAttr() {

        $viewPath = Input::get('confirm') ? 'menu.preorder.basket' : 'landing.basket';

        $input = array(
            'branch' => Input::get('branch'),
            'item' => Input::get('item'),
            'attr' => Input::get('attr')
        );

        $basket = $this->preorder->configAttr($input);

        $data['orders'] = $this->preorder->all($basket);

        $view = View::make($viewPath, $data);

        $orders = $view->render();
        // Success!
        return Response::json(array(
                    'status' => TRUE,
                    'type' => 'success',
                    'message' => Lang::get('segment.product.message.success.config'),
                    'basket' => $orders)
        );
    }

    public function removeItem() {

        $viewPath = Input::get('confirm') ? 'menu.preorder.basket' : 'landing.basket';

        $input = array(
            'branch' => Input::get('branch'),
            'item' => Input::get('item')
        );

        $basket = $this->preorder->remove($input);

        $data['orders'] = $this->preorder->all($basket);

        $view = View::make($viewPath, $data);

        $orders = $view->render();
        // Success!
        return Response::json(array(
                    'status' => TRUE,
                    'type' => 'success',
                    'message' => Lang::get('segment.product.message.success.remove'),
                    'basket' => $orders)
        );
    }

    public function customer() {

        $input = array(
            'city_id' => Input::get('city'),
            'user_id' => Session::get('user.id'),
            'position' => Input::get('position')
        );

        $customer = $this->customer->save($input);

        if ($customer) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.customer.message.success.create'),
                        'customer' => $customer)
            );
        }

        // Error!
        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->customer->errors()->all())
        );
    }

}
