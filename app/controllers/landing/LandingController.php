<?php

use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Service\Form\Preorder\PreorderForm;
use App\Repository\Branch\BranchInterface;

class LandingController extends PreorderController {

    protected $branch;

    public function __construct(BranchInterface $branch, ProductInterface $product, CategoryInterface $category, CommerceInterface $commerce, PreorderForm $preorder) {
        parent::__construct($product, $category, $commerce, $preorder);
        $this->branch = $branch;
    }

    public function index($commerceName) {

        $branchId = Input::get('branch');

        $commerce = $this->commerce->findByName($commerceName);

        if (is_null($commerce))
            return Redirect::to(Config::get('app.url'));


        $branches = $this->branch->allByCommerceId($commerce->id);

        if (!$branches->isEmpty()) {

            if (!$branchId)
                $branchId = $branches[0]->id;

            $branch = $this->branch->find($branchId, ['*'], ['branchProducts.product.categories.subcategories', 'branchProducts.product.tags', 'branchProducts.prices.size'], ['commerce_id' => $commerce->id]);

            foreach ($branch->branchProducts as $branchProduct) {
                $data['productByCategory'][$branchProduct->product->categories->category_name]['data'] = $branchProduct->product->categories;
                $data['productByCategory'][$branchProduct->product->categories->category_name]['products'][] = $branchProduct;
            }

            if (!$branch)
                return Redirect::to('/' . $commerce->commerce_url);

            $commerce->setRelations(['branch' => $branch]);

            $data['branches'] = $branches;
        }

        $data['commerce'] = $commerce;
        $data['orders'] = $this->preorder->all(Session::get('orders'));

        return View::make('landing.main', $data);
    }

}
