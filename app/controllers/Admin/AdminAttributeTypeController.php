<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\AttributeType\AttributeTypeForm;

class AdminAttributeTypeController extends BaseController {

    protected $attributetype;

    public function __construct(AttributeTypeForm $attributetype) {
        $this->attributetype = $attributetype;
    }

    /*public function index() {

        $data['attributetypes'] = $this->attributetype->all();

        return View::make('admin.main', $data);
    }*/

    /**
     * Create attribute form processing
     * POST /admin/attribute
     */
    public function store() {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['d_attribute_type'] = Input::get('attributetype_name');
        $input['rules'] = Input::get('rules');

        if ($this->attributetype->save($input)) {
            // Success!
            return Redirect::to('/attribute')
                            ->withSuccess(Lang::get('segment.attributetype.message.success.store'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/attribute')
                            ->withInput()
                            ->withErrors($this->attributetype->errors())
                            ->with('status', 'error');
        }
    }

    /**
     * Create attribute form processing
     * POST /admin/attribute
     */
    public function update($id) {
        
        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['d_attribute_type'] = Input::get('attributetype_name');

        if ($this->attributetype->update($id, $input)) {
            // Success!
            return Redirect::to('/attribute')
                            ->withSuccess(Lang::get('segment.attributetype.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/attribute')
                            ->withInput()
                            ->withErrors($this->attributetype->errors())
                            ->with('status', 'error');
        }
    }

    /**
     * Create attribute form processing
     * POST /admin/attribute
     */
    public function changeStatus() {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['active'] = Input::get('status');
        $id_attribute_type = Input::get('attributetype_id');

        if ($this->attributetype->changeStatus($id_attribute_type, $input)) {
            // Success!
            return Redirect::to('/attribute')
                            ->withSuccess(Lang::get('segment.attribute.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/attribute')
                            ->withInput()
                            ->withErrors($this->attributetype->errors())
                            ->with('status', 'error');
        }
    }

}
