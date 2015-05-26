<?php

use App\Service\Form\Attribute\AttributeForm;

class AdminController extends BaseController {

    protected $attribute;

    public function __construct(AttributeForm $attribute) {
        $this->attribute = $attribute;
    }

    public function index() {
        return Redirect::route('admin');
    }

    public function dashboard() {
        return View::make('admin.main');
    }

}
