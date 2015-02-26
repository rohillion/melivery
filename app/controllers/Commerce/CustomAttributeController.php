<?php

use App\Service\Form\Attribute\AttributeForm;
use App\Service\Form\AttributeSubcategory\AttributeSubcategoryForm;

class CustomAttributeController extends BaseController {

    protected $attribute;
    protected $attributeSubcategory;

    public function __construct(AttributeForm $attribute, AttributeSubcategoryForm $attributeSubcategory) {
        $this->attribute = $attribute;
        $this->attributeSubcategory = $attributeSubcategory;
    }

    /**
     * Create custom attribute
     * POST /admin/attribute
     */
    public function store() {

        $input['attribute_name'] = Input::get('attribute_name');
        $input['id_attribute_type'] = Input::get('attribute_type_id');
        $input['subcategory_id'] = Input::get('subcategory');

        // Start transaction
        DB::beginTransaction();

        $attribute = $this->attribute->save($input);

        if ($attribute) {

            // Proceed with attribute => subcategory relation
            $attributeSubcategory[$input['subcategory_id']] = array($attribute->id);

            $attributeBySubcategory = $this->attributeSubcategory->save($attributeSubcategory);

            if (!$attributeBySubcategory) {

                // If success commit
                DB::rollback();

                return Response::json(
                                array(
                                    'status' => FALSE,
                                    'message' => $this->attributeSubcategory->errors()->all()
                                )
                );
            } else {

                // Else commit the queries
                DB::commit();

                return Response::json(
                                array(
                                    'status' => TRUE,
                                    'message' => Lang::get('segment.attribute.message.success.store'),
                                    'attribute' => $attribute->toJson()
                                )
                );
            }
        }

        return Response::json(
                        array(
                            'status' => FALSE,
                            'message' => $this->attribute->errors()->all()
                        )
        );
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
        } else {

            return Redirect::to('/attribute')
                            ->withInput()
                            ->withErrors($this->attribute->errors())
                            ->with('status', 'error');
        }
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
        } else {

            return Redirect::to('/attribute')
                            ->withInput()
                            ->withErrors($this->attribute->errors())
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
        $id_attribute = Input::get('attribute_id');

        if ($this->attribute->changeStatus($id_attribute, $input)) {
            // Success!
            return Redirect::to('/attribute')
                            ->withSuccess(Lang::get('segment.attribute.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/attribute')
                            ->withInput()
                            ->withErrors($this->attribute->errors())
                            ->with('status', 'error');
        }
    }

}
