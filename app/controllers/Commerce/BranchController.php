<?php

use App\Service\Form\Branch\BranchForm;

class BranchController extends BaseController {

    protected $branch;

    public function __construct(BranchForm $branch) {
        $this->branch = $branch;
    }

    public function index() {

        $data['branches'] = $this->branch->allByCommerceId(Auth::user()->id_commerce);

        return View::make("commerce.main", $data);
    }

    /**
     * Create branch form processing
     * POST /branch/branch
     */
    public function create() {

        return View::make("commerce.branch.create");
    }

    /**
     * Create branch form processing
     * POST /branch/branch
     */
    public function store() {

        if ($this->branch->save(Input::all())) {
            // Success!
            return Redirect::route('commerce.profile')
                            ->withSuccess(Lang::get('segment.branch.message.success.save'))
                            ->with('status', 'success');
        }

        return Redirect::to('/branch/create')
                        ->withInput()
                        ->withErrors($this->branch->errors())
                        ->with('status', 'error');
    }
    
    /**
     * Create branch form processing
     * POST /branch/branch
     */
    public function edit($branch_id) {
        
        $data['branch'] = $this->branch->find($branch_id,['*'],['openingHours','phones','dealers']);

        return View::make("commerce.branch.edit", $data);
    }

    /**
     * Edit branch form processing
     * POST /branch
     */
    public function update($id) {
        
        $input = Input::only('address','email','position','delivery','pickup','radio','delivery_area','phone','dealer','days');
        
        if ($this->branch->update($id, $input)) {
            // Success!
            return Redirect::to('/profile')
                            ->withSuccess(Lang::get('segment.branch.message.success.edit'))
                            ->with('status', 'success');
        }
        
        return Redirect::to('/branch/'.$id.'/edit')
                        ->withInput()
                        ->withErrors($this->branch->errors())
                        ->with('status', 'error');
    }

    /**
     * Edit branch form processing
     * POST /branch
     */
    public function destroy($id) {

        if ($this->branch->delete($id)) {
            // Success!
            return Redirect::to('/profile')
                            ->withSuccess(Lang::get('segment.branch.message.success.delete'))
                            ->with('status', 'success');
        }

        return Redirect::to('/profile')
                        ->withInput()
                        ->withErrors($this->branch->errors())
                        ->with('status', 'error');
    }

}
