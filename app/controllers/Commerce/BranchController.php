<?php

use App\Service\Form\Branch\BranchForm;
use App\Repository\City\CityInterface;

class BranchController extends BaseController {

    protected $branch;
    protected $city;

    public function __construct(BranchForm $branch, CityInterface $city) {
        $this->branch = $branch;
        $this->city = $city;
    }

    /**
     * Create branch form processing
     * POST /branch/branch
     */
    public function create() {
        
        $data['cities'] = $this->city->byCountryCode(Session::get('location')['country'])->toJson();

        return View::make("commerce.branch.create",$data);
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
