<?php

namespace App\Repository\Country;

use App\Repository\RepositoryAbstract;

class EloquentCountry extends RepositoryAbstract implements CountryInterface {

    public function byCode($countryCode) {
        return $this->entity
                        ->where('country_code', $countryCode)
                        ->with('cities')
                        ->get();
    }

}
