<?php

use App\Repository\Commerce\CommerceInterface;
use App\Service\Form\Preorder\PreorderForm;

class LandingController extends BaseController {

    protected $commerce;
    protected $preorder;

    public function __construct(CommerceInterface $commerce, PreorderForm $preorder) {
        $this->commerce = $commerce;
        $this->preorder = $preorder;
    }

    public function index($commerceName) {

        $data = array(
            'commerce' => $this->commerce->findByName($commerceName),
            'orders' => $this->preorder->all(Session::get('orders'))
        );
        //die('<pre>' . var_dump($data['commerce']->branches[0]->products) . '</pre>');
        return View::make('landing.main', $data);
    }

}
