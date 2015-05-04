<?php

use App\Service\Form\BranchDealer\BranchDealerForm;

class DealerController extends BaseController {

    protected $branchDealerForm;

    public function __construct(BranchDealerForm $branchDealerForm) {
        $this->branchDealerForm = $branchDealerForm;
    }

    /**
     * Create dealer
     * POST /dealer
     */
    public function save() {

        $input = array(
            "dealer_name" => Input::get('name'),
            "branch_id" => Session::get('user.branch_id')
        );

        $dealer = $this->branchDealerForm->save($input);

        if ($dealer) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('dealer.save.success'),
                        'dealer' => $dealer)
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchDealerForm->errors()->all())
        );
    }

}
