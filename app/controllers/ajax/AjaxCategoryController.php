<?php

use App\Repository\Category\CategoryInterface;

class AjaxCategoryController extends BaseController {

    protected $category;

    public function __construct(CategoryInterface $category) {
        $this->category = $category;
    }

    public function index() {

        $response = Response::json(
                        array(
                            'status' => FALSE,
                            'code' => 404
                        )
        );


        $categories = $this->category->all(['*'], ['activeSubcategories.activeTagsWithCustom'], ['active' => 1]);

        if (!$categories->isEmpty()) {

            foreach ($categories as $category) {

                $data[$category->id] = $category->toArray();
                $data[$category->id]['active_subcategories'] = NULL;

                if (!$category->activeSubcategories->isEmpty()) {
                    foreach ($category->activeSubcategories as $subcategory) {

                        $data[$category->id]['active_subcategories'][$subcategory->id] = $subcategory->toArray();
                    }
                }
            }
  
            
            $response = Response::json(
                            array(
                                'status' => TRUE,
                                'code' => 200,
                                'categories' => $data
                            )
            );
        }


        return $response;
    }

    public function find() {

        $response = Response::json(
                        array(
                            'status' => FALSE,
                            'code' => 404
                        )
        );

        $query = Input::get('query');

        if ($query) {

            $categories = $this->category->likeCategoryName(Session::get('location')['country'], $query);

            if (!$categories->isEmpty()) {

                $response = Response::json(
                                array(
                                    'status' => TRUE,
                                    'code' => 200,
                                    'categories' => $categories
                                )
                );
            }
        }

        return $response;
    }

}
