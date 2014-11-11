<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\TagController\TagForm;

class CustomTagController extends BaseController {

    protected $tag;

    public function __construct(TagForm $tag) {
        $this->tag = $tag;
    }

    /**
     * Create tag form processing
     * POST /admin/tag
     */
    public function store() {

        $input['tag_name'] = strtolower(Input::get('tag'));
        $input['subcategory_id'] = Input::get('subcategory');
        $input['commerce_id'] = Auth::user()->id_commerce;
        $input['active'] = 1;

        if ($this->tag->save($input)) {
            // Success!
            return Response::json(
                            array(
                                'status' => 'success',
                                'message' => Lang::get('segment.tag.message.success.store')
                            )
            );
        }
        
        /*foreach($this->tag->errors() as $msg){
            $erros
        }*/

        return Response::json(
                        array(
                            'status' => 'error',
                            'message' => $this->tag->errors()
                        )
        );
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

}
