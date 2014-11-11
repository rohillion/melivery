<?php

namespace App\Repository\User;

interface UserInterface {

    public function findByVCode($vcode);
}
