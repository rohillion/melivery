<?php

namespace App\Repository\Country;

use App\Repository\RepositoryInterface;

interface CountryInterface extends RepositoryInterface {

    public function byCode($countryCode);
}
