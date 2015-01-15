<?php

use App\Repository\Branch\BranchInterface;
use App\Service\Form\Branch\BranchForm;
use App\Service\Form\BranchArea\BranchAreaForm;
use App\Repository\City\CityInterface;

class BranchController extends BaseController {

    protected $branch;
    protected $branchForm;
    protected $branchAreaForm;
    protected $city;

    public function __construct(BranchInterface $branch, BranchForm $branchForm, BranchAreaForm $branchAreaForm, CityInterface $city) {
        $this->branch = $branch;
        $this->branchForm = $branchForm;
        $this->branchAreaForm = $branchAreaForm;
        $this->city = $city;
    }

    public function index() {

        $data['branches'] = $this->branch->allByCommerceId(Session::get('user.id_commerce'));

        return View::make("commerce.branch.index", $data);
    }

    /**
     * Create branch form processing
     * POST /branch/branch
     */
    public function create() {

        return View::make("commerce.branch.create");
    }

    /**
     * Create branch form processing
     * POST /branch/branch
     */
    public function store() {

        $branch = $this->branchForm->save(Input::only('position', 'street', 'city', 'email', 'phone'));

        if ($branch) {

            // Success!
            return Redirect::route('commerce.branch.edit', array('branch_id' => $branch->id, 'step' => 2));
        }

        return Redirect::route('commerce.branch.create')
                        ->withInput()
                        ->withErrors($this->branchForm->errors())
                        ->with('status', 'error');
    }

    /**
     * Create branch form processing
     * POST /branch/branch
     */
    public function edit($branch_id) {

        $branch = $this->branch->findByCommerceId($branch_id, Session::get('user.id_commerce'), ['openingHours', 'phones', 'dealers', 'city', 'areas']);

        if (is_null($branch))
            App::abort(403, 'Unauthorized action.');

        $data['branch'] = $branch;

        return View::make("commerce.branch.create", $data);
    }

    /**
     * Edit branch form processing
     * POST /branch
     */
    public function update($id) {

        $input = Input::only('position', 'street', 'city', 'email', 'phone');

        $branch = $this->branchForm->update($id, $input);

        if ($branch) {

            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.branch.message.success.edit'),
                        'branch' => $branch->toArray())
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchForm->errors()->all())
        );
    }

    /**
     * Edit branch form processing
     * POST /branch
     */
    public function destroy($id) {

        if ($this->branchForm->delete($id)) {
            // Success!
            return Redirect::route('commerce.branch.index')
                            ->withSuccess(Lang::get('segment.branch.message.success.delete'))
                            ->with('status', 'success');
        }

        return Redirect::route('commerce.branch.index')
                        ->withInput()
                        ->withErrors($this->branchForm->errors())
                        ->with('status', 'error');
    }

    /**
     * Edit branch pickup field
     * POST /branch/{branch_id}/pickup
     */
    public function delivery($branch_id) {

        $input['delivery'] = Input::get('delivery', '0');

        $branch = $this->branchForm->delivery($branch_id, $input);

        if ($branch) {

            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.branch.message.success.edit'),
                        'branch' => $branch->toArray())
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchForm->errors()->all())
        );
    }
    
    /**
     * Edit branch pickup field
     * POST /branch/{branch_id}/pickup
     */
    public function pickup($branch_id) {

        $input['pickup'] = Input::get('pickup', '0');

        $branch = $this->branchForm->pickup($branch_id, $input);

        if ($branch) {

            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.branch.message.success.edit'),
                        'branch' => $branch->toArray())
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchForm->errors()->all())
        );
    }

    /**
     * Create branch delivery area
     * POST /branch/area
     */
    public function areaCreate($branch_id) {

        $input = Input::only('area_name', 'cost', 'min', 'area');

        $input['branch_id'] = $branch_id;

        $branchArea = $this->branchAreaForm->save($input);

        if ($branchArea) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.branch_area.message.success.create'),
                        'area' => $branchArea->toArray())
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchAreaForm->errors()->all())
        );
    }

    /**
     * Update branch delivery area
     * POST /branch/area/$areaId
     */
    public function areaUpdate($branch_id, $area_id) {

        $input = Input::only('area_name', 'cost', 'min', 'area');

        $input['branch_id'] = $branch_id;

        $branchArea = $this->branchAreaForm->update($area_id, $input);

        if ($branchArea) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.branch_area.message.success.edit'),
                        'area' => $branchArea->toArray())
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchAreaForm->errors()->all())
        );
    }
    
    /**
     * Update branch delivery area
     * POST /branch/area/$areaId
     */
    public function areaDelete($branch_id, $area_id) {

        $branchArea = $this->branchAreaForm->delete($area_id, $branch_id);

        if ($branchArea) {
            // Success!
            return Response::json(array(
                        'status' => TRUE,
                        'type' => 'success',
                        'message' => Lang::get('segment.branch_area.message.success.delete'))
            );
        }

        return Response::json(array(
                    'status' => FALSE,
                    'type' => 'error',
                    'message' => $this->branchAreaForm->errors()->all())
        );
    }

}
