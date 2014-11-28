<?php

use App\Repository\Category\CategoryInterface;
use App\Repository\City\CityInterface;

class HomeController extends BaseController {

    protected $category;
    protected $city;

    public function __construct(CategoryInterface $category, CityInterface $city) {
        $this->category = $category;
        $this->city = $city;
    }

    public function index() {

        $data['cities'] = $this->city->allByCountryCodeInUse(Session::get('location')['country']);

        $data['categories'] = $this->category->all();

        return View::make('home.main', $data);
    }

}
