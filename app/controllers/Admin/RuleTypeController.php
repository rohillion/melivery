<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\RuleType\RuleTypeForm;

class RuleTypeController extends BaseController {

    protected $ruletype;

    public function __construct(RuleTypeForm $ruletype) {
        $this->ruletype = $ruletype;
    }

    /*public function index() {

        $data['ruletypes'] = $this->ruletype->all();

        return View::make('admin.main', $data);
    }*/

    /**
     * Create rule form processing
     * POST /admin/rule
     */
    public function store() {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['rule_type_name'] = Input::get('ruletype_name');

        if ($this->ruletype->save($input)) {
            // Success!
            return Redirect::to('/rule')
                            ->withSuccess(Lang::get('segment.ruletype.message.success.store'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/rule')
                            ->withInput()
                            ->withErrors($this->ruletype->errors())
                            ->with('status', 'error');
        }
    }

    /**
     * Create rule form processing
     * POST /admin/rule
     */
    public function update($id) {
        
        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['d_rule_type'] = Input::get('ruletype_name');

        if ($this->ruletype->update($id, $input)) {
            // Success!
            return Redirect::to('/rule')
                            ->withSuccess(Lang::get('segment.ruletype.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/rule')
                            ->withInput()
                            ->withErrors($this->ruletype->errors())
                            ->with('status', 'error');
        }
    }
    
    /**
     * Create rule form processing
     * POST /admin/rule
     */
    public function destroy($id) {
        
        $input['id'] = $id;
        
        if ($this->ruletype->delete($input)) {
            // Success!
            return Redirect::to('/rule')
                            ->withSuccess(Lang::get('segment.ruletype.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/rule')
                            ->withInput()
                            ->withErrors($this->ruletype->errors())
                            ->with('status', 'error');
        }
    }

    /**
     * Create rule form processing
     * POST /admin/rule
     */
    public function changeStatus() {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['active'] = Input::get('status');
        $id_rule_type = Input::get('ruletype_id');

        if ($this->ruletype->changeStatus($id_rule_type, $input)) {
            // Success!
            return Redirect::to('/rule')
                            ->withSuccess(Lang::get('segment.rule.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/rule')
                            ->withInput()
                            ->withErrors($this->ruletype->errors())
                            ->with('status', 'error');
        }
    }

}
