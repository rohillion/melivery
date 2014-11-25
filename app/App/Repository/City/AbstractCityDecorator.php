<?php namespace App\Repository\City;

abstract class AbstractCityDecorator implements CityInterface {

    protected $nextCity;

    public function __construct(CityInterface $nextCity)
    {
        $this->nextCity = $nextCity;
    }

    /**
     * {@inheritdoc}
     */
    public function byCountryCode($id)
    {
        return $this->nextCity->byCountryCode($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function byCountryCodeByCityName($countryCode, $query)
    {
        return $this->nextCity->byCountryCodeByCityName($countryCode, $query);
    }

    /**
     * {@inheritdoc}
     */
    public function byCountryId($page=1, $limit=10, $all=false)
    {
        return $this->nextCity->byCountryId($page, $limit, $all);
    }

}