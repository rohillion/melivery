<?php

use App\Service\Form\Attribute\AttributeForm;

class AdminController extends BaseController {

    protected $attribute;

    public function __construct(AttributeForm $attribute) {
        $this->attribute = $attribute;
    }

    public function index() {

        //$data['attributes'] = $this->attribute->all(['*'], ['attribute_types']);

        return View::make('admin.main');
    }

}
