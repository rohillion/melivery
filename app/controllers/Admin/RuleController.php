<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\Rule\RuleForm;
use App\Service\Form\RuleType\RuleTypeForm;

class RuleController extends BaseController {

    protected $rule;
    protected $ruletype;

    public function __construct(RuleForm $rule, RuleTypeForm $ruletype) {
        $this->rule = $rule;
        $this->ruletype = $ruletype;
    }

    public function index() {

        $data['rules'] = $this->rule->all(['*'], ['rule_type']);
        $data['ruletypes'] = $this->ruletype->all();

        return View::make('admin.main', $data);
    }

    /**
     * Create rule form processing
     * POST /admin/rule
     */
    public function store() {

        $input['rule_value'] = Input::get('rule_value');
        $input['rule_type_id'] = Input::get('rule_type_id');

        if ($this->rule->save($input)) {
            // Success!
            return Redirect::to('/rule')
                            ->withSuccess(Lang::get('segment.rule.message.success.store'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/rule')
                            ->withInput()
                            ->withErrors($this->rule->errors())
                            ->with('status', 'error');
        }
    }

    /**
     * Create rule form processing
     * POST /admin/rule
     */
    public function update($id) {

        $input['id_rule_type'] = Input::get('rule_type_id');

        if ($this->rule->update($id, $input)) {
            // Success!
            return Redirect::to('/rule')
                            ->withSuccess(Lang::get('segment.rule.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/rule')
                            ->withInput()
                            ->withErrors($this->rule->errors())
                            ->with('status', 'error');
        }
    }
    
    /**
     * Create rule form processing
     * POST /admin/rule
     */
    public function destroy($id) {
        
        $input['id'] = $id;
        
        if ($this->rule->delete($input)) {
            // Success!
            return Redirect::to('/rule')
                            ->withSuccess(Lang::get('segment.rule.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/rule')
                            ->withInput()
                            ->withErrors($this->rule->errors())
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
        $id_rule = Input::get('rule_id');

        if ($this->rule->changeStatus($id_rule, $input)) {
            // Success!
            return Redirect::to('/rule')
                            ->withSuccess(Lang::get('segment.rule.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/rule')
                            ->withInput()
                            ->withErrors($this->rule->errors())
                            ->with('status', 'error');
        }
    }

}
