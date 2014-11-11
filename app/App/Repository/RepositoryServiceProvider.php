<?php

namespace App\Repository;

use User;
use Category;
use Subcategory;
use Attribute;
use AttributeType;
use AttributeSubcategory;
use Product;
use Commerce;
use Customer;
use Branch;
use BranchDealer;
use Tag;
use Rule;
use RuleType;
use Order;
use OrderStatus;
use OrderProduct;
use Country;
use City;

use App\Repository\User\EloquentUser;
use App\Repository\Category\EloquentCategory;
use App\Repository\Subcategory\EloquentSubcategory;
use App\Repository\Attribute\EloquentAttribute;
use App\Repository\AttributeType\EloquentAttributeType;
use App\Repository\AttributeSubcategory\EloquentAttributeSubcategory;
use App\Repository\Product\EloquentProduct;
use App\Repository\Commerce\EloquentCommerce;
use App\Repository\Customer\EloquentCustomer;
use App\Repository\Branch\EloquentBranch;
use App\Repository\BranchDealer\EloquentBranchDealer;
use App\Repository\Tag\EloquentTag;
use App\Repository\Rule\EloquentRule;
use App\Repository\RuleType\EloquentRuleType;
use App\Repository\Order\EloquentOrder;
use App\Repository\OrderStatus\EloquentOrderStatus;
use App\Repository\OrderProduct\EloquentOrderProduct;
use App\Repository\Country\EloquentCountry;
use App\Repository\City\EloquentCity;
use App\Repository\City\CacheDecorator;

use App\Service\Cache\LaravelCache;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $app = $this->app;

        $app->bind('App\Repository\User\UserInterface', function($app) {
            $user = new EloquentUser(
                    new User
            );

            return $user;
        });

        $app->bind('App\Repository\Category\CategoryInterface', function($app) {
            $category = new EloquentCategory(
                    new Category
            );

            return $category;
        });

        $app->bind('App\Repository\Subcategory\SubcategoryInterface', function($app) {
            $subcategory = new EloquentSubcategory(
                    new Subcategory
            );

            return $subcategory;
        });

        $app->bind('App\Repository\Attribute\AttributeInterface', function($app) {
            $attribute = new EloquentAttribute(
                    new Attribute
            );

            return $attribute;
        });

        $app->bind('App\Repository\AttributeType\AttributeTypeInterface', function($app) {
            $attributetype = new EloquentAttributeType(
                    new AttributeType
            );

            return $attributetype;
        });

        $app->bind('App\Repository\AttributeSubcategory\AttributeSubcategoryInterface', function($app) {
            $attributesubcategory = new EloquentAttributeSubcategory(
                    new AttributeSubcategory
            );

            return $attributesubcategory;
        });

        $this->product($app);
        $this->commerce($app);
        $this->customer($app);
        $this->branch($app);
        $this->branchDealer($app);
        $this->tag($app);
        $this->rule($app);
        $this->ruletype($app);
        $this->order($app);
        $this->orderstatus($app);
        $this->orderproduct($app);
        $this->country($app);
        $this->city($app);
    }

    private function product($app) {
        $app->bind('App\Repository\Product\ProductInterface', function($app) {
            $product = new EloquentProduct(
                    new Product
            );

            return $product;
        });
    }

    private function commerce($app) {
        $app->bind('App\Repository\Commerce\CommerceInterface', function($app) {
            $commerce = new EloquentCommerce(
                    new Commerce
            );

            return $commerce;
        });
    }

    private function customer($app) {
        $app->bind('App\Repository\Customer\CustomerInterface', function($app) {
            $customer = new EloquentCustomer(
                    new Customer
            );

            return $customer;
        });
    }

    private function branch($app) {
        $app->bind('App\Repository\Branch\BranchInterface', function($app) {
            $branch = new EloquentBranch(
                    new Branch
            );

            return $branch;
        });
    }

    private function branchDealer($app) {
        $app->bind('App\Repository\BranchDealer\BranchDealerInterface', function($app) {
            $branchDealer = new EloquentBranchDealer(
                    new BranchDealer
            );

            return $branchDealer;
        });
    }

    private function tag($app) {
        $app->bind('App\Repository\Tag\TagInterface', function($app) {
            $tag = new EloquentTag(
                    new Tag
            );

            return $tag;
        });
    }

    private function rule($app) {
        $app->bind('App\Repository\Rule\RuleInterface', function($app) {
            $rule = new EloquentRule(
                    new Rule
            );

            return $rule;
        });
    }

    private function ruletype($app) {
        $app->bind('App\Repository\RuleType\RuleTypeInterface', function($app) {
            $ruletype = new EloquentRuleType(
                    new RuleType
            );

            return $ruletype;
        });
    }

    private function order($app) {
        $app->bind('App\Repository\Order\OrderInterface', function($app) {
            $order = new EloquentOrder(
                    new Order
            );

            return $order;
        });
    }

    private function orderstatus($app) {
        $app->bind('App\Repository\OrderStatus\OrderStatusInterface', function($app) {
            $orderstatus = new EloquentOrderStatus(
                    new OrderStatus
            );

            return $orderstatus;
        });
    }

    private function orderproduct($app) {
        $app->bind('App\Repository\OrderProduct\OrderProductInterface', function($app) {
            $orderproduct = new EloquentOrderProduct(
                    new OrderProduct
            );

            return $orderproduct;
        });
    }

    private function country($app) {
        $app->bind('App\Repository\Country\CountryInterface', function($app) {
            $country = new EloquentCountry(
                    new Country
            );

            return $country;
        });
    }

    private function city($app) {
        $app->bind('App\Repository\City\CityInterface', function($app) {
            /*$city = new EloquentCity(
                    new City
            );

            return $city;*/

            $city = new EloquentCity(
                    new City
            );

            return new CacheDecorator(
                    $city, new LaravelCache($app['cache'], 'city')
            );
        });
    }

}
