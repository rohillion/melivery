<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\TagController\TagForm;
use App\Repository\Category\CategoryInterface;
use App\Repository\Subcategory\SubcategoryInterface;

class TagController extends BaseController {

    protected $tag;
    protected $subcategory;
    protected $category;

    public function __construct(TagForm $tag, SubcategoryInterface $subcategory, CategoryInterface $category) {
        $this->tag = $tag;
        $this->subcategory = $subcategory;
        $this->category = $category;
    }

    public function index() {

        $data['tags'] = $this->tag->all(['*'], ['subcategories']);
        $data['subcategories'] = $this->subcategory->all();
        $data['categories'] = $this->category->all(['*'], ['subcategories']);

        return View::make('admin.main', $data);
    }

    /**
     * Create tag form processing
     * POST /admin/tag
     */
    public function store() {

        $input['tag_name'] = Input::get('tag_name');
        $input['subcategory_id'] = Input::get('subcategory_id');

        if ($this->tag->save($input)) {
            // Success!
            return Redirect::to('/tag')
                            ->withSuccess(Lang::get('segment.tag.message.success.store'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/tag')
                            ->withInput()
                            ->withErrors($this->tag->errors())
                            ->with('status', 'error');
        }
    }

    /**
     * Create tag form processing
     * POST /admin/tag
     */
    public function update($id) {

        $input['tag_name'] = Input::get('tag_name');
        $input['subcategory_id'] = Input::get('subcategory_id');

        if ($this->tag->update($id, $input)) {
            // Success!
            return Redirect::to('/tag')
                            ->withSuccess(Lang::get('segment.tag.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/tag')
                            ->withInput()
                            ->withErrors($this->tag->errors())
                            ->with('status', 'error');
        }
    }
    
    /**
     * Create tag form processing
     * POST /admin/tag
     */
    public function destroy($id) {
        
        $input['id'] = $id;
        
        if ($this->tag->delete($input)) {
            // Success!
            return Redirect::to('/tag')
                            ->withSuccess(Lang::get('segment.tag.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/tag')
                            ->withInput()
                            ->withErrors($this->attribute->errors())
                            ->with('status', 'error');
        }
    }

    /**
     * Create tag form processing
     * POST /admin/tag
     */
    public function changeStatus() {

        //$input = array_merge(Input::all(), array('user_id' => 1));
        $input['active'] = Input::get('status');
        $id_tag = Input::get('tag_id');

        if ($this->tag->changeStatus($id_tag, $input)) {
            // Success!
            return Redirect::to('/tag')
                            ->withSuccess(Lang::get('segment.tag.message.success.edit'))
                            ->with('status', 'success');
        } else {

            return Redirect::to('/tag')
                            ->withInput()
                            ->withErrors($this->tag->errors())
                            ->with('status', 'error');
        }
    }

}
