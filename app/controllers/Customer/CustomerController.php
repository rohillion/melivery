<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\User\UserForm;
use App\Service\Form\AccountController\AccountForm;

class CustomerController extends BaseController {

    public function index() {
        return 'Hola usuario comensal';
    }

}
