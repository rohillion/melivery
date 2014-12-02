<?php

use App\Repository\Commerce\CommerceInterface;

class LandingController extends BaseController {

    protected $commerce;

    public function __construct(CommerceInterface $commerce) {
        $this->commerce = $commerce;
    }

    public function index($commerceName) {

        $data['commerce'] = $this->commerce->findByName($commerceName);

        return View::make('landing.main', $data);
    }

}
