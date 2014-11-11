<?php

use App\Service\Form\AttributeSubcategory\AttributeSubcategoryForm;
use App\Repository\Category\CategoryInterface;
use App\Repository\Attribute\AttributeInterface;

class AdminAttributeSubcategoryController extends BaseController {

    protected $attributeSubcategory;
    protected $category;
    protected $attribute;

    public function __construct(AttributeSubcategoryForm $attributeSubcategory, CategoryInterface $category, AttributeInterface $attribute) {
        $this->attributeSubcategory = $attributeSubcategory;
        $this->category = $category;
        $this->attribute = $attribute;
    }
    
    public function index() {
        
        $data['attributes'] = $this->attribute->all(['*'], ['attribute_types']);
        $data['categories'] = $this->category->all(['*'], ['subcategories.attributes.attribute_types']);

        return View::make('admin.main', $data);
    }

    /**
     * Create attribute form processing
     * POST /admin/attribute
     */
    public function store() {

        $input = Input::get('attribute');

        if ($this->attributeSubcategory->save($input)) {
            // Success!
            return Redirect::to('/attribute_subcategory')
                            ->withSuccess(Lang::get('segment.attributetype.message.success.store'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/attribute_subcategory')
                            ->withInput()
                            ->withErrors($this->attributeSubcategory->errors())
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
            return Redirect::to('/attribute_subcategory')
                            ->withSuccess(Lang::get('segment.attributetype.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/attribute_subcategory')
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
        
        if ($this->attributeSubcategory->delete($input)) {
            // Success!
            return Redirect::to('/attribute_subcategory')
                            ->withSuccess(Lang::get('segment.attribute.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/attribute_subcategory')
                            ->withInput()
                            ->withErrors($this->attributetype->errors())
                            ->with('status', 'error');
        }
    }

}
