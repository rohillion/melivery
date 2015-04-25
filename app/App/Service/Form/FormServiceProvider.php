<?php

namespace App\Service\Form;

use Illuminate\Support\ServiceProvider;
use App\Service\Form\User\UserForm;
use App\Service\Form\User\UserValidator;
use App\Service\Form\Category\CategoryForm;
use App\Service\Form\Category\CategoryValidator;
use App\Service\Form\Subcategory\SubcategoryForm;
use App\Service\Form\Subcategory\SubcategoryValidator;
use App\Service\Form\Attribute\AttributeForm;
use App\Service\Form\Attribute\AttributeValidator;
use App\Service\Form\AttributeType\AttributeTypeForm;
use App\Service\Form\AttributeType\AttributeTypeValidator;
use App\Service\Form\AttributeSubcategory\AttributeSubcategoryForm;
use App\Service\Form\AttributeSubcategory\AttributeSubcategoryValidator;
use App\Service\Form\AccountController\AccountForm;
use App\Service\Form\AccountController\AccountValidator;
use App\Service\Form\AccountController\Request\RequestForm;
use App\Service\Form\AccountController\Request\RequestValidator;
use App\Service\Form\AccountController\Reset\ResetForm;
use App\Service\Form\AccountController\Reset\ResetValidator;
use App\Service\Form\AccountController\Verification\VerificationForm;
use App\Service\Form\AccountController\Verification\VerificationValidator;
use App\Service\Form\Product\ProductForm;
use App\Service\Form\Product\ProductValidator;
use App\Service\Form\Commerce\CommerceForm;
use App\Service\Form\Commerce\CommerceValidator;
use App\Service\Form\Customer\CustomerForm;
use App\Service\Form\Customer\CustomerValidator;
use App\Service\Form\Branch\BranchForm;
use App\Service\Form\Branch\BranchValidator;
use App\Service\Form\BranchOpening\BranchOpeningForm;
use App\Service\Form\BranchOpening\BranchOpeningValidator;
use App\Service\Form\BranchPhone\BranchPhoneForm;
use App\Service\Form\BranchPhone\BranchPhoneValidator;
use App\Service\Form\BranchArea\BranchAreaForm;
use App\Service\Form\BranchArea\BranchAreaValidator;
use App\Service\Form\BranchProduct\BranchProductForm;
use App\Service\Form\BranchProduct\BranchProductValidator;
use App\Service\Form\BranchProductPrice\BranchProductPriceForm;
use App\Service\Form\BranchProductPrice\BranchProductPriceValidator;
use App\Service\Form\BranchDealer\BranchDealerForm;
use App\Service\Form\BranchDealer\BranchDealerValidator;
use App\Service\Form\BranchUser\BranchUserForm;
use App\Service\Form\BranchUser\BranchUserValidator;
use App\Service\Form\TagController\TagForm;
use App\Service\Form\TagController\TagValidator;
use App\Service\Form\Rule\RuleForm;
use App\Service\Form\Rule\RuleValidator;
use App\Service\Form\RuleType\RuleTypeForm;
use App\Service\Form\RuleType\RuleTypeValidator;
use App\Service\Form\Menu\MenuForm;
use App\Service\Form\Preorder\PreorderForm;
use App\Service\Form\Preorder\PreorderValidator;
use App\Service\Form\Order\OrderForm;
use App\Service\Form\Order\OrderValidator;
use App\Service\Form\OrderStatus\OrderStatusForm;
use App\Service\Form\OrderStatus\OrderStatusValidator;
use App\Service\Form\OrderCash\OrderCashForm;
use App\Service\Form\OrderCash\OrderCashValidator;
use App\Service\Form\OrderProduct\OrderProductForm;
use App\Service\Form\OrderProduct\OrderProductValidator;
use App\Service\Form\AttributeOrderProduct\AttributeOrderProductForm;
use App\Service\Form\AttributeOrderProduct\AttributeOrderProductValidator;
use App\Service\Form\Country\CountryForm;
use App\Service\Form\Country\CountryValidator;
use App\Service\Form\City\CityForm;
use App\Service\Form\City\CityValidator;

class FormServiceProvider extends ServiceProvider {

    /**
     * Register the binding
     *
     * @return void
     */
    public function register() {
        $app = $this->app;

        $app->bind('App\Service\Form\User\UserForm', function($app) {

            return new UserForm(
                    new UserValidator($app['validator']), $app->make('App\Repository\User\UserInterface')
            );
        });

        $app->bind('App\Service\Form\Category\CategoryForm', function($app) {

            return new CategoryForm(
                    new CategoryValidator($app['validator']), $app->make('App\Repository\Category\CategoryInterface')
            );
        });

        $app->bind('App\Service\Form\Subcategory\SubcategoryForm', function($app) {

            return new SubcategoryForm(
                    new SubcategoryValidator($app['validator']), $app->make('App\Repository\Subcategory\SubcategoryInterface')
            );
        });

        $app->bind('App\Service\Form\Attribute\AttributeForm', function($app) {

            return new AttributeForm(
                    new AttributeValidator($app['validator']), $app->make('App\Repository\Attribute\AttributeInterface')
            );
        });

        $app->bind('App\Service\Form\AttributeType\AttributeTypeForm', function($app) {

            return new AttributeTypeForm(
                    new AttributeTypeValidator($app['validator']), $app->make('App\Repository\AttributeType\AttributeTypeInterface'), $app->make('App\Repository\Rule\RuleInterface')
            );
        });

        $app->bind('App\Service\Form\AttributeSubcategory\AttributeSubcategoryForm', function($app) {

            return new AttributeSubcategoryForm(
                    new AttributeSubcategoryValidator($app['validator']), $app->make('App\Repository\AttributeSubcategory\AttributeSubcategoryInterface'), $app->make('App\Repository\Category\CategoryInterface'), $app->make('App\Repository\Attribute\AttributeInterface')
            );
        });

        $app->bind('App\Service\Form\AccountController\AccountForm', function($app) {

            return new AccountForm(
                    new AccountValidator($app['validator']), $app->make('App\Repository\BranchUser\BranchUserInterface')
            );
        });

        $this->request($app);
        $this->reset($app);
        $this->verification($app);
        $this->product($app);
        $this->commerce($app);
        $this->customer($app);
        $this->branch($app);
        $this->branchOpening($app);
        $this->branchPhone($app);
        $this->branchArea($app);
        $this->branchProduct($app);
        $this->branchProductPrice($app);
        $this->branchDealer($app);
        $this->branchUser($app);
        $this->tag($app);
        $this->rule($app);
        $this->ruletype($app);
        $this->menu($app);
        $this->order($app);
        $this->ordercash($app);
        $this->orderstatus($app);
        $this->preorder($app);
        $this->orderProduct($app);
        $this->attributeOrderProduct($app);
        $this->country($app);
        $this->city($app);
    }

    private function request($app) {
        $app->bind('App\Service\Form\AccountController\Request\RequestForm', function($app) {

            return new RequestForm(
                    new RequestValidator($app['validator'])
            );
        });
    }

    private function reset($app) {
        $app->bind('App\Service\Form\AccountController\Reset\ResetForm', function($app) {

            return new ResetForm(
                    new ResetValidator($app['validator'])
            );
        });
    }

    private function verification($app) {
        $app->bind('App\Service\Form\AccountController\Verification\VerificationForm', function($app) {

            return new VerificationForm(
                    new VerificationValidator($app['validator']), $app->make('App\Repository\User\UserInterface'), $app->make('App\Repository\Commerce\CommerceInterface')
            );
        });
    }

    private function product($app) {
        $app->bind('App\Service\Form\Product\ProductForm', function($app) {

            return new ProductForm(
                    new ProductValidator($app['validator']), $app->make('App\Repository\Product\ProductInterface'), $app->make('App\Repository\Branch\BranchInterface'), $app->make('App\Repository\BranchProduct\BranchProductInterface')
            );
        });
    }

    private function commerce($app) {
        $app->bind('App\Service\Form\Commerce\CommerceForm', function($app) {

            return new CommerceForm(
                    new CommerceValidator($app['validator']), $app->make('App\Repository\Commerce\CommerceInterface')
            );
        });
    }
    
    private function customer($app) {
        $app->bind('App\Service\Form\Customer\CustomerForm', function($app) {

            return new CustomerForm(
                    new CustomerValidator($app['validator']), $app->make('App\Repository\Customer\CustomerInterface')
            );
        });
    }

    private function branch($app) {
        $app->bind('App\Service\Form\Branch\BranchForm', function($app) {

            return new BranchForm(
                    new BranchValidator($app['validator']), $app->make('App\Repository\Branch\BranchInterface'), $app->make('App\Service\Form\BranchOpening\BranchOpeningForm'), $app->make('App\Service\Form\BranchPhone\BranchPhoneForm'), $app->make('App\Service\Form\BranchArea\BranchAreaForm'), $app->make('App\Service\Form\BranchDealer\BranchDealerForm'), $app->make('App\Repository\Product\ProductInterface')
            );
        });
    }
    
    private function branchOpening($app) {
        $app->bind('App\Service\Form\BranchOpening\BranchOpeningForm', function($app) {

            return new BranchOpeningForm(
                    new BranchOpeningValidator($app['validator']), $app->make('App\Repository\Branch\BranchInterface')
            );
        });
    }
    
    private function branchPhone($app) {
        $app->bind('App\Service\Form\BranchPhone\BranchPhoneForm', function($app) {

            return new BranchPhoneForm(
                    new BranchPhoneValidator($app['validator']), $app->make('App\Repository\Branch\BranchInterface')
            );
        });
    }
    
    private function branchArea($app) {
        $app->bind('App\Service\Form\BranchArea\BranchAreaForm', function($app) {

            return new BranchAreaForm(
                    new BranchAreaValidator($app['validator']), $app->make('App\Repository\Branch\BranchInterface'), $app->make('App\Repository\BranchArea\BranchAreaInterface')
            );
        });
    }
    
    private function branchProduct($app) {
        $app->bind('App\Service\Form\BranchProduct\BranchProductForm', function($app) {

            return new BranchProductForm(
                    new BranchProductValidator($app['validator']), $app->make('App\Repository\Branch\BranchInterface'), $app->make('App\Repository\BranchProduct\BranchProductInterface'), $app->make('App\Repository\BranchProductPrice\BranchProductPriceInterface'), $app->make('App\Service\Form\Product\ProductForm'), $app->make('App\Service\Form\BranchProductPrice\BranchProductPriceForm')
            );
        });
    }
    
    private function branchProductPrice($app) {
        $app->bind('App\Service\Form\BranchProductPrice\BranchProductPriceForm', function($app) {

            return new BranchProductPriceForm(
                    new BranchProductPriceValidator($app['validator']), $app->make('App\Repository\BranchProductPrice\BranchProductPriceInterface'), $app->make('App\Repository\Product\ProductInterface')
            );
        });
    }
    
    private function branchDealer($app) {
        $app->bind('App\Service\Form\BranchDealer\BranchDealerForm', function($app) {

            return new BranchDealerForm(
                    new BranchDealerValidator($app['validator']), $app->make('App\Repository\Branch\BranchInterface'), $app->make('App\Repository\BranchDealer\BranchDealerInterface'), $app->make('App\Service\Form\OrderStatus\OrderStatusForm')
            );
        });
    }
    
    private function branchUser($app) {
        $app->bind('App\Service\Form\BranchUser\BranchUserForm', function($app) {

            return new BranchUserForm(
                    new BranchUserValidator($app['validator']), $app->make('App\Repository\Branch\BranchInterface'), $app->make('App\Repository\BranchUser\BranchUserInterface')
            );
        });
    }

    private function tag($app) {
        $app->bind('App\Service\Form\TagController\TagForm', function($app) {

            return new TagForm(
                    new TagValidator($app['validator']), $app->make('App\Repository\Tag\TagInterface')
            );
        });
    }

    private function rule($app) {
        $app->bind('App\Service\Form\Rule\RuleForm', function($app) {

            return new RuleForm(
                    new RuleValidator($app['validator']), $app->make('App\Repository\Rule\RuleInterface')
            );
        });
    }

    private function ruletype($app) {
        $app->bind('App\Service\Form\RuleType\RuleTypeForm', function($app) {

            return new RuleTypeForm(
                    new RuleTypeValidator($app['validator']), $app->make('App\Repository\RuleType\RuleTypeInterface')
            );
        });
    }

    private function menu($app) {
        $app->bind('App\Service\Form\Menu\MenuForm', function($app) {

            return new MenuForm(
                    $app->make('App\Repository\Product\ProductInterface'), $app->make('App\Repository\Category\CategoryInterface'), $app->make('App\Repository\Commerce\CommerceInterface')
            );
        });
    }
    
    private function preorder($app) {
        $app->bind('App\Service\Form\Preorder\PreorderForm', function($app) {

            return new PreorderForm(
                    new PreorderValidator($app['validator']), $app->make('App\Repository\Product\ProductInterface'), $app->make('App\Repository\BranchProduct\BranchProductInterface'), $app->make('App\Repository\BranchProductPrice\BranchProductPriceInterface'), $app->make('App\Repository\Category\CategoryInterface'), $app->make('App\Repository\Commerce\CommerceInterface'), $app->make('App\Repository\Branch\BranchInterface'), $app->make('App\Service\Form\Order\OrderForm'), $app->make('App\Service\Form\OrderCash\OrderCashForm'), $app->make('App\Service\Form\OrderStatus\OrderStatusForm'), $app->make('App\Service\Form\OrderProduct\OrderProductForm'), $app->make('App\Service\Form\AttributeOrderProduct\AttributeOrderProductForm')
            );
        });
    }
    
    private function order($app) {
        $app->bind('App\Service\Form\Order\OrderForm', function($app) {

            return new OrderForm(
                    new OrderValidator($app['validator']), $app->make('App\Repository\Order\OrderInterface'), $app->make('App\Repository\BranchDealer\BranchDealerInterface'), $app->make('App\Service\Form\OrderCash\OrderCashForm')
            );
        });
    }
    
    private function orderstatus($app) {
        $app->bind('App\Service\Form\OrderStatus\OrderStatusForm', function($app) {

            return new OrderStatusForm(
                    new OrderStatusValidator($app['validator']), $app->make('App\Repository\OrderStatus\OrderStatusInterface'), $app->make('App\Repository\Order\OrderInterface')
            );
        });
    }
    
    private function ordercash($app) {
        $app->bind('App\Service\Form\OrderCash\OrderCashForm', function($app) {

            return new OrderCashForm(
                    new OrderCashValidator($app['validator']), $app->make('App\Repository\OrderCash\OrderCashInterface')
            );
        });
    }
    
    private function orderProduct($app) {
        $app->bind('App\Service\Form\OrderProduct\OrderProductForm', function($app) {

            return new OrderProductForm(
                    new OrderProductValidator($app['validator']), $app->make('App\Repository\OrderProduct\OrderProductInterface')
            );
        });
    }
    
    private function attributeOrderProduct($app) {
        $app->bind('App\Service\Form\AttributeOrderProduct\AttributeOrderProductForm', function($app) {

            return new AttributeOrderProductForm(
                    new AttributeOrderProductValidator($app['validator']), $app->make('App\Repository\OrderProduct\OrderProductInterface')
            );
        });
    }
    
    private function country($app) {
        $app->bind('App\Service\Form\Country\CountryForm', function($app) {

            return new CountryForm(
                    new CountryValidator($app['validator']), $app->make('App\Repository\Country\CountryInterface')
            );
        });
    }
    
    private function city($app) {
        $app->bind('App\Service\Form\City\CityForm', function($app) {

            return new CityForm(
                    new CityValidator($app['validator']), $app->make('App\Repository\City\CityInterface')
            );
        });
    }

}
