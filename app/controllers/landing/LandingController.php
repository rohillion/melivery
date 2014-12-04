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
                    $branches = $branches[0]->id;

                $commerce->setRelations([
                    'branches' => $this->branch->findByCommerceId($branchId, $commerce->id, ['products.categories.subcategories', 'products.tags'])
                ]);
            }
        }

        $data = array(
            'commerce' => $commerce,
            'orders' => $this->preorder->all(Session::get('orders'))
        );

        return View::make('landing.main', $data);
    }

}
