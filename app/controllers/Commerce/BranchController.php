<?php

use App\Repository\Branch\BranchInterface;
use App\Service\Form\Branch\BranchForm;
use App\Repository\City\CityInterface;

class BranchController extends BaseController {

    protected $branch;
    protected $branchForm;
    protected $city;

    public function __construct(BranchInterface $branch, BranchForm $branchForm, CityInterface $city) {
        $this->branch = $branch;
        $this->branchForm = $branchForm;
        $this->city = $city;
    }
    
    public function index() {

        $data['branches'] = $this->branch->allByCommerceId(Session::get('user.id_commerce'));

        return View::make("commerce.branch.index", $data);
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

        if ($this->branchForm->save(Input::all())) {
            // Success!
            return Redirect::route('commerce.profile')
                            ->withSuccess(Lang::get('segment.branch.message.success.save'))
                            ->with('status', 'success');
        }

        return Redirect::to('/branch/create')
                        ->withInput()
                        ->withErrors($this->branchForm->errors())
                        ->with('status', 'error');
    }
    
    /**
     * Create branch form processing
     * POST /branch/branch
     */
    public function edit($branch_id) {
        
        $branch = $this->branch->findByCommerceId($branch_id, Session::get('user.id_commerce'), ['openingHours', 'phones', 'dealers', 'city', 'areas']);
        
        if(is_null($branch))
            App::abort(403, 'Unauthorized action.');
        
        $data['branch'] = $branch;
        
        return View::make("commerce.branch.edit", $data);
    }

    /**
     * Edit branch form processing
     * POST /branch
     */
    public function update($id) {
        
        $input = Input::only('street','city','email','position','delivery','pickup','radio','delivery_area','phone','dealer','days');
        
        if ($this->branchForm->update($id, $input)) {
            // Success!
            return Redirect::route('commerce.branch.index')
                            ->withSuccess(Lang::get('segment.branch.message.success.edit'))
                            ->with('status', 'success');
        }
        
        return Redirect::to('/branch/'.$id.'/edit')
                        ->withInput()
                        ->withErrors($this->branchForm->errors())
                        ->with('status', 'error');
    }

    /**
     * Edit branch form processing
     * POST /branch
     */
    public function destroy($id) {

        if ($this->branchForm->delete($id)) {
            // Success!
            return Redirect::route('commerce.branch.index')
                            ->withSuccess(Lang::get('segment.branch.message.success.delete'))
                            ->with('status', 'success');
        }

        return Redirect::route('commerce.branch.index')
                        ->withInput()
                        ->withErrors($this->branchForm->errors())
                        ->with('status', 'error');
    }

}
