<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\Subcategory\SubcategoryForm;
use App\Repository\Category\CategoryInterface;

class AdminSubcategoryController extends BaseController {

    protected $subcategory;
    protected $category;

    public function __construct(SubcategoryForm $subcategory, CategoryInterface $category) {
        $this->subcategory = $subcategory;
        $this->category = $category;
    }

    public function index() {

        $data['subcategories'] = $this->subcategory->all('*',['categories']);
        $data['categories'] = $this->category->all();
        
        return View::make('admin.main', $data);
    }

    /**
     * Create subcategory form processing
     * POST /subcategory
     */
    public function store() {

        $input['subcategory_name'] = Input::get('subcategory_name');
        $input['id_category'] = Input::get('category_id');

        if ($this->subcategory->save($input)) {
            // Success!
            return Redirect::to('/subcategory')
                            ->withSuccess(Lang::get('segment.subcategory.message.success.store'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/subcategory')
                            ->withInput()
                            ->withErrors($this->subcategory->errors())
                            ->with('status', 'error');
        }
    }
    
    /**
     * Edit subcategory form processing
     * POST /subcategory
     */
    public function update($id) {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['subcategory_name'] = Input::get('subcategory_name');
        $input['id_category'] = Input::get('category_id');

        if ($this->subcategory->update($id, $input)) {
            // Success!
            return Redirect::to('/subcategory')
                            ->withSuccess(Lang::get('segment.subcategory.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/subcategory')
                            ->withInput()
                            ->withErrors($this->subcategory->errors())
                            ->with('status', 'error');
        }
    }
    
    /**
     * Delete subcategory
     * POST /admin/subcategory
     */
    public function destroy($id) {
        
        $input['id'] = $id;
        
        if ($this->subcategory->delete($input)) {
            // Success!
            return Redirect::to('/subcategory')
                            ->withSuccess(Lang::get('segment.subcategory.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/subcategory')
                            ->withInput()
                            ->withErrors($this->subcategory->errors())
                            ->with('status', 'error');
        }
    }
    
    /**
     * Create subcategory form processing
     * POST /admin/subcategory
     */
    public function changeStatus() {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['active'] = Input::get('status');
        $id_subcategory = Input::get('subcategory_id');

        if ($this->subcategory->changeStatus($id_subcategory, $input)) {
            // Success!
            return Redirect::to('/subcategory')
                            ->withSuccess(Lang::get('segment.subcategory.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/subcategory')
                            ->withInput()
                            ->withErrors($this->subcategory->errors())
                            ->with('status', 'error');
        }
    }
    
}
