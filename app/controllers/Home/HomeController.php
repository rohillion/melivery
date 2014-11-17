<?php

use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Country\CountryInterface;
use App\Repository\City\CityInterface;

class HomeController extends BaseController {

    protected $product;
    protected $category;
    protected $country;
    protected $city;

    public function __construct(ProductInterface $product, CategoryInterface $category, CountryInterface $country, CityInterface $city) {
        $this->product = $product;
        $this->category = $category;
        $this->country = $country;
        $this->city = $city;
    }

    public function index() {
        
<<<<<<< HEAD
        $data['cities'] = $this->city->byCountryCode('ar');
=======
        $data['cities'] = $this->city->byCountryCode(Session::get('location')['country']);
>>>>>>> origin/master
        
        $data['categories'] = $this->category->all();

        return View::make('home.main', $data);
    }

}
