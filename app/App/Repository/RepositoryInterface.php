<?php

namespace App\Repository;

/**
 * RepositoryInterface provides the standard functions to be expected of ANY 
 * repository.
 */
interface RepositoryInterface {

    public function create(array $attributes = array());

    public function edit($id, array $attributes);

    public function all($columns = array('*'), $entities = array());

    public function find($id, $columns = array('*'), $entities = array());

    public function destroy($ids);

    public function sync(array $ids);
}
