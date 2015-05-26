<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\Attribute\AttributeForm;
use App\Service\Form\AttributeType\AttributeTypeForm;
use App\Repository\Category\CategoryInterface;
use App\Repository\Rule\RuleInterface;

class AdminAttributeController extends BaseController {

    protected $attribute;
    protected $attributetype;
    protected $category;
    protected $rule;

    public function __construct(AttributeForm $attribute, AttributeTypeForm $attributetype, CategoryInterface $category, RuleInterface $rule) {
        $this->attribute = $attribute;
        $this->attributetype = $attributetype;
        $this->category = $category;
        $this->rule = $rule;
    }

    public function index() {

        $data['attributes'] = $this->attribute->all(['*'], ['attribute_types']);
        $data['attributetypes'] = $this->attributetype->all();
        $data['categories'] = $this->category->all(['*'], ['subcategories.attributes.attribute_types']);
        $data['rules'] = $this->rule->all(['*'], ['rule_type']);

        return View::make('admin.main', $data);
    }

    /**
     * Create attribute form processing
     * POST /admin/attribute
     */
    public function store() {

        $input['attribute_name'] = Input::get('attribute_name');
        $input['id_attribute_type'] = Input::get('attribute_type_id');

        if ($this->attribute->save($input)) {
            // Success!
            return Redirect::to('/attribute')
                            ->withSuccess(Lang::get('segment.attribute.message.success.store'))
                            ->with('status', 'success');
        }

        return Redirect::to('/attribute')
                        ->withInput()
                        ->withErrors($this->attribute->errors())
                        ->with('status', 'error');
    }

    /**
     * Create attribute form processing
     * POST /admin/attribute
     */
    public function update($id) {

        $input['attribute_name'] = Input::get('attribute_name');
        $input['id_attribute_type'] = Input::get('attribute_type_id');

        if ($this->attribute->update($id, $input)) {
            // Success!
            return Redirect::to('/attribute')
                            ->withSuccess(Lang::get('segment.attribute.message.success.edit'))
                            ->with('status', 'success');
        }

        return Redirect::to('/attribute')
                        ->withInput()
                        ->withErrors($this->attribute->errors())
                        ->with('status', 'error');
    }

    /**
     * Create attribute form processing
     * POST /admin/attribute
     */
    public function destroy($id) {

        $input['id'] = $id;

        if ($this->attribute->delete($input)) {
            // Success!
            return Redirect::to('/attribute')
                            ->withSuccess(Lang::get('segment.attribute.message.success.edit'))
                            ->with('status', 'success');
        }

        return Redirect::to('/attribute')
                        ->withInput()
                        ->withErrors($this->attribute->errors())
                        ->with('status', 'error');
    }

    /**
     * Create attribute form processing
     * POST /admin/attribute
     */
    public function status() {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['active'] = Input::get('status');
        $id_attribute = Input::get('attribute_id');

        if ($this->attribute->changeStatus($id_attribute, $input)) {
            // Success!
            return Redirect::to('/attribute')
                            ->withSuccess(Lang::get('segment.attribute.message.success.edit'))
                            ->with('status', 'success');
        }

        return Redirect::to('/attribute')
                        ->withInput()
                        ->withErrors($this->attribute->errors())
                        ->with('status', 'error');
    }

}
