<?php

use App\Repository\Commerce\CommerceInterface;
use App\Repository\Branch\BranchInterface;
use App\Service\Form\Preorder\PreorderForm;

class LandingController extends BaseController {

    protected $commerce;
    protected $branch;
    protected $preorder;

    public function __construct(CommerceInterface $commerce, BranchInterface $branch, PreorderForm $preorder) {
        $this->commerce = $commerce;
        $this->branch = $branch;
        $this->preorder = $preorder;
    }

    public function index($commerceName) {

        $branchId = Input::get('branch');

        $commerce = $this->commerce->findByName($commerceName);

        if (!is_null($commerce)) {

            $branches = $this->branch->allByCommerceId($commerce->id);

            if (!$branches->isEmpty()) {

                if (!$branchId)
                    $branchId = $branches[0]->id;

                $branch = $this->branch->find($branchId, ['*'], ['products.categories.subcategories', 'products.tags'], ['commerce_id' => $commerce->id]);

                foreach ($branch->products as $product) {
                        $data['productByCategory'][$product->categories->category_name]['data'] = $product->categories;
                        $data['productByCategory'][$product->categories->category_name]['products'][] = $product;
                }
                
                if (!$branch)
                    return Redirect::to('/' . $commerce->commerce_url);

                $commerce->setRelations(['branch' => $branch]);

                $data['branches'] = $branches;
            }
        }

        $data['commerce'] = $commerce;
        $data['orders'] = $this->preorder->all(Session::get('orders'));

        return View::make('landing.main', $data);
    }

}
