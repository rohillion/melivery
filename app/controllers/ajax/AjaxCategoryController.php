<?php

use App\Repository\Category\CategoryInterface;

class AjaxCategoryController extends BaseController {

    protected $category;

    public function __construct(CategoryInterface $category) {
        $this->category = $category;
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
                                    'cities' => $categories
                                )
                );
            }
        }

        return $response;
    }

}
