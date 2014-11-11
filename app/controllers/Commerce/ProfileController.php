<?php

//use Illuminate\Support\MessageBag;
use App\Service\Form\Commerce\CommerceForm;
use App\Service\Form\Branch\BranchForm;

class ProfileController extends BaseController {

    protected $commerce;
    protected $branch;

    public function __construct(CommerceForm $commerce, BranchForm $branch) {
        $this->commerce = $commerce;
        $this->branch = $branch;
    }

    public function index() {

        $data['commerce'] = $this->commerce->find(Auth::user()->id_commerce, ['*'], ['branches']);

        return View::make("commerce.profile.index", $data);
    }

    /**
     * Edit category form processing
     * POST /category
     */
    public function update() {

        $input['commerce_name'] = Input::get('name');
        $input['commerce_url'] = Input::get('url');

        if ($this->commerce->update(Auth::user()->id_commerce, $input)) {

            if (Input::has('address')) {

                if (!$this->branch->save(Input::all())) {

                    return Redirect::to('/profile')
                                    ->withInput()
                                    ->withErrors($this->branch->errors())
                                    ->with('status', 'error');
                }
            }

            // Success!
            return Redirect::to('/profile')
                            ->withSuccess(Lang::get('segment.profile.message.success.edit'))
                            ->with('status', 'success');
        }

        return Redirect::to('/profile')
                        ->withInput()
                        ->withErrors($this->commerce->errors())
                        ->with('status', 'error');
    }

}
