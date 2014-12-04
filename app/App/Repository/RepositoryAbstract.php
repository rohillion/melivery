<?php

namespace App\Repository;

use App\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class RepositoryAbstract implements RepositoryInterface {

    protected $entity;

    public function __construct(Model $entity) {
        $this->entity = $entity;
    }

    /**
     * Insert new entity
     *
     * @param  array $attributes
     * @return stdObject entity
     */
    public function create(array $attributes = array()) {
        return $this->entity->create($attributes);
    }

    public function edit($id, array $attributes) {
        $entity = $this->find($id);

        foreach ($attributes as $col => $value) {
            $entity->$col = $value;
        }

        if ($entity->save())
            return $entity;
    }

    public function all($columns = array('*'), $entities = array()) {

        /*if (!empty($entities)) {

            return $this->withEntities($entities)->get();
        } else {
            return $this->entity->all($columns);
        }*/
        return $this->withEntities($entities)->get();
    }

    public function find($id, $columns = array('*'), $entities = array()) {

        /*if (!empty($entities)) {

            return $this->withEntities($entities)->find($id, $columns);
        } else {
            return $this->entity->find($id, $columns);
        }*/
        return $this->withEntities($entities)->find($id, $columns);
    }

    public function destroy($id) {
        return $this->entity->destroy($id);
    }

    public function sync(array $ids) {
        return $this->entity->sync($ids);
    }

    protected function withEntities(array $entities) {
        
        if (!empty($entities))
            return $this->entity->with($entities);
        
        return $this->entity;
    }

}
