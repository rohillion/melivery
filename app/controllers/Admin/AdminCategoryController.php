<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\Category\CategoryForm;

class AdminCategoryController extends BaseController {

    protected $category;

    public function __construct(CategoryForm $category) {
        $this->category = $category;
    }

    public function index() {

        $data['categories'] = $this->category->all();
        //$data['categories'] = $this->category->all('*',['attributes']);
        
        return View::make('admin.main', $data);
    }

    /**
     * Create category form processing
     * POST /category
     */
    public function store() {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['category_name'] = Input::get('category_name');

        if ($this->category->save($input)) {
            // Success!
            return Redirect::to('/category')
                            ->withSuccess(Lang::get('segment.category.message.success.store'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/category')
                            ->withInput()
                            ->withErrors($this->category->errors())
                            ->with('status', 'error');
        }
    }
    
    /**
     * Edit category form processing
     * POST /category
     */
    public function update($id) {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['category_name'] = Input::get('category_name');

        if ($this->category->update($id, $input)) {
            // Success!
            return Redirect::to('/category')
                            ->withSuccess(Lang::get('segment.category.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/category')
                            ->withInput()
                            ->withErrors($this->category->errors())
                            ->with('status', 'error');
        }
    }
    
    /**
     * Delete category
     * POST /admin/category
     */
    public function destroy($id) {
        
        $input['id'] = $id;
        
        if ($this->category->delete($input)) {
            // Success!
            return Redirect::to('/category')
                            ->withSuccess(Lang::get('segment.category.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/subcategory')
                            ->withInput()
                            ->withErrors($this->category->errors())
                            ->with('status', 'error');
        }
    }
    
    /**
     * Create category form processing
     * POST /admin/category
     */
    public function changeStatus() {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['active'] = Input::get('status');
        $id_category = Input::get('category_id');

        if ($this->category->changeStatus($id_category, $input)) {
            // Success!
            return Redirect::to('/category')
                            ->withSuccess(Lang::get('segment.category.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/category')
                            ->withInput()
                            ->withErrors($this->category->errors())
                            ->with('status', 'error');
        }
    }
    
}
