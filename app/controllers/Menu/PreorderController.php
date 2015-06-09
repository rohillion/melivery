<?php

use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Repository\Customer\CustomerInterface;
use App\Service\Form\Preorder\PreorderForm;
use App\Service\Form\Customer\CustomerForm;

class PreorderController extends BaseController {

    protected $product;
    protected $category;
    protected $commerce;
    protected $preorder;
    protected $customer;
    protected $customerForm;

    public function __construct(ProductInterface $product, CategoryInterface $category, CommerceInterface $commerce, PreorderForm $preorder, CustomerInterface $customer, CustomerForm $customerForm) {
        $this->product = $product;
        $this->category = $category;
        $this->commerce = $commerce;
        $this->preorder = $preorder;
        $this->customer = $customer;
        $this->customerForm = $customerForm;
    }

    public function show() {

        $orders = $this->render(Session::get('orders'));
        // Success!
        return Response::json(array(
                    'status' => TRUE,
                    'type' => 'success',
                    'message' => Lang::get('segment.product.message.success.store'),
                    'basket' => $orders)
        );
    }

    public function store() {

        $order = $this->preorder->process(Session::get('user.id'), Session::get('orders'), Input::only('pay', 'amount'), Input::get('address',0), Input::get('delivery'));

        if ($order) {

            // Success!
            return Redirect::route('customer')
                            //return Redirect::to(Request::server('HTTP_REFERER'))
                            ->withSuccess(Lang::get('segment.order.message.success.save'))
                            ->with('status', 'success');
        }

        return Redirect::to(Request::server('HTTP_REFERER'))
                        ->withErrors($this->preorder->errors())
                        ->with('status', 'error');
    }

    public function confirm() {

        $data['orders'] = $this->preorder->all(Session::get('orders'));
        $data['addresses'] = $this->customer->all(['*'], [], ['user_id' => Session::get('user.id')]);

        return View::make('menu.preorder.confirm', $data);
    }

    public function addItem() {

        $basket = $this->preorder->add(Input::all());

        if ($basket) {

            $orders = $this->render($basket);
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

        $input = array(
            'branch' => Input::get('branch'),
            'item' => Input::get('item'),
            'qty' => Input::get('qty')
        );

        $basket = $this->preorder->configQty($input);
        $orders = $this->render($basket);
        // Success!
        return Response::json(array(
                    'status' => TRUE,
                    'type' => 'success',
                    'message' => Lang::get('segment.product.message.success.config'),
                    'basket' => $orders)
        );
    }

    public function configAttr() {

        $input = array(
            'branch' => Input::get('branch'),
            'item' => Input::get('item'),
            'attr' => Input::get('attr')
        );

        $basket = $this->preorder->configAttr($input);
        $orders = $this->render($basket);
        // Success!
        return Response::json(array(
                    'status' => TRUE,
                    'type' => 'success',
                    'message' => Lang::get('segment.product.message.success.config'),
                    'basket' => $orders)
        );
    }

    public function removeItem() {

        $input = array(
            'branch' => Input::get('branch'),
            'item' => Input::get('item')
        );

        $basket = $this->preorder->remove($input);
        $orders = $this->render($basket);
        // Success!
        return Response::json(array(
                    'status' => TRUE,
                    'type' => 'success',
                    'message' => Lang::get('segment.product.message.success.remove'),
                    'basket' => $orders)
        );
    }

    private function render($basket) {

        $viewPath = Input::get('confirm') ? 'menu.preorder.basket' : 'landing.basket';
        $data['orders'] = $this->preorder->all($basket);
        $data['addresses'] = $this->customer->all(['*'], [], ['user_id' => Session::get('user.id')]);
        $view = View::make($viewPath, $data);
        return $view->render();
    }

    public function customer() {

        $input = array(
            'floor_apt' => Input::get('residence'),
            'city_id' => Input::get('city'),
            'user_id' => Session::get('user.id'),
            'position' => Input::get('position'),
            'street' => Input::get('street')
        );

        $customer = $this->customerForm->save($input);

        if ($customer) {

            $position = array(
                'id' => $customer->id,
                'coords' => $input['position'],
                'address' => $input['street'],
                'city' => $input['city_id'],
            );

            // Success!
            $response = \CommonEvents::createPositionCookie($position);
            $response->status = TRUE;
            $response->type = 'success';
            $response->message = Lang::get('segment.customer.message.success.create');
            $response->customer = $customer;
            return $response;
        }

        // Error!
        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->customerForm->errors()->all())
        );
    }

}
