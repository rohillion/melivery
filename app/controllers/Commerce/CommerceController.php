<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\User\UserForm;
use App\Service\Form\AccountController\AccountForm;

class CommerceController extends BaseController {

    public function index() {
        
        return View::make("commerce.main");
    }

}
