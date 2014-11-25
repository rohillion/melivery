<?php namespace App\Repository\City;

use App\Service\Cache\CacheInterface;

class StateCacheDecorator extends AbstractCityDecorator {

    protected $cache;

    public function __construct(CityInterface $nextCity, CacheInterface $cache)
    {
        parent::__construct($nextCity);
        $this->cache = $cache;
    }

    /**
     * Attempt to retrieve from cache
     * by ID
     * {@inheritdoc}
     */
    public function byCountryCode($country_code)
    {
        
        $key = md5('id.'.$country_code);

        if( $this->cache->has($key) )
        {
            return $this->cache->get($key);
        }

        $city = $this->nextCity->byCountryCode($country_code);

        $this->cache->put($key, $city, 5);

        return $city;
    }

    /**
     * Attempt to retrieve from cache
     * {@inheritdoc}
     */
    public function byCountryId($country_id)
    {
        $key = md5('slug.'.$country_id);

        if( $this->cache->has($key) )
        {
            return $this->cache->get($key);
        }

        $city = $this->nextCity->byCountryId($country_id);

        $this->cache->put($key, $city, 5);

        return $city;
    }

}