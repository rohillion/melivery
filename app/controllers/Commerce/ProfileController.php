<?php

//use Illuminate\Support\MessageBag;
use App\Service\Form\Commerce\CommerceForm;
use App\Service\Form\Branch\BranchForm;

class ProfileController extends BaseController {

    protected $commerce;
    protected $branch;

    public function __construct(CommerceForm $commerce, BranchForm $branch) {
        $this->commerce = $commerce;
        $this->branch = $branch;
    }

    public function index() {

        $data['commerce'] = $this->commerce->find(Auth::user()->id_commerce, ['*'], ['branches']);

        return View::make("commerce.profile.index", $data);
    }

    /**
     * Edit category form processing
     * POST /category
     */
    public function update() {

        $input['commerce_name'] = Input::get('name');
        $input['commerce_url'] = Input::get('url');

        if ($this->commerce->update(Auth::user()->id_commerce, $input)) {

            if (Input::has('address')) {

                if (!$this->branch->save(Input::all())) {

                    return Redirect::to('/profile')
                                    ->withInput()
                                    ->withErrors($this->branch->errors())
                                    ->with('status', 'error');
                }
            }

            // Success!
            return Redirect::to('/profile')
                            ->withSuccess(Lang::get('segment.profile.message.success.edit'))
                            ->with('status', 'success');
        }

        return Redirect::to('/profile')
                        ->withInput()
                        ->withErrors($this->commerce->errors())
                        ->with('status', 'error');
    }

    /**
     * Edit category form processing
     * POST /category
     */
    public function checkBrandUrl($url) {

        if ($this->commerce->checkBrandUrl($url)) {

            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => 'URL disponible!')//TODO. Lang::get('segment.profile.message.success.edit')
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->commerce->errors()->all())
        );
    }

    /**
     * Edit category form processing
     * POST /category
     */
    public function cover() {

        $file = Input::file('cover');

        if (!is_null($file)) {
            
            $imagePath = $this->commerce->uploadImage($file, Config::get('cons.image.commerceCover'));

            if ($imagePath) {

                return Response::json(array(
                            'status' => TRUE,
                            'type' => 'success',
                            'data' => ['src' => $imagePath])
                );
            }

            return Response::json(array(
                        'status' => FALSE,
                        'type' => 'error',
                        'message' => $this->commerce->errors()->all())
            );
        }
    }

    /**
     * Edit category form processing
     * POST /category
     */
    public function logo() {

        $file = Input::file('logo');

        if (!is_null($file)) {

            $imagePath = $this->commerce->uploadImage($file, Config::get('cons.image.commerceLogo'));

            if ($imagePath) {

                return Response::json(array(
                            'status' => TRUE,
                            'type' => 'success',
                            'data' => ['src' => $imagePath])
                );
            }

            return Response::json(array(
                        'status' => FALSE,
                        'type' => 'error',
                        'message' => $this->commerce->errors()->all())
            );
        }
    }

}
