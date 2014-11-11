<?php

use App\Service\Form\AttributeCategory\AttributeCategoryForm;

class AdminAttributeCategoryController extends BaseController {

    protected $attributeCategory;

    public function __construct(AttributeCategoryForm $attributeCategory) {
        $this->attributeCategory = $attributeCategory;
    }
    
    public function index() {
        
        $data['attributesCategories'] = $this->attributeCategory->all();
        
        echo '<pre>', var_dump($data), '</pre>';
        exit;

        return View::make('admin.main', $data);
    }

    /**
     * Create attribute form processing
     * POST /admin/attribute
     */
    public function store() {

        $input = Input::get('attribute');

        if ($this->attributeCategory->save($input)) {
            // Success!
            return Redirect::to('/attribute')
                            ->withSuccess(Lang::get('segment.attributetype.message.success.store'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/attribute')
                            ->withInput()
                            ->withErrors($this->attributeCategory->errors())
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
    public function destroy($id) {
        
        $input['id'] = $id;
        
        if ($this->attributeCategory->delete($input)) {
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
