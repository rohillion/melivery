<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\User\UserForm;
use App\Service\Form\AccountController\AccountForm;

class CommerceController extends BaseController {

    public function index() {
        return Redirect::route('commerce.order');//TODO. Terminar Dashboard.
        return View::make("commerce.main");
    }

}
