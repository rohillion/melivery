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

    public function all(array $columns = array('*'), array $entities = array(), array $where = array()) {

        /* if (!empty($entities)) {

          return $this->withEntities($entities)->get();
          } else {
          return $this->entity->all($columns);
          } */
        //return $this->withEntities($entities)->get();
        return $this->whereMatch($where)->with($entities)->get($columns);
    }

    public function find($id, array $columns = array('*'), array $entities = array(), array $where = array()) {

        return $this->whereMatch($where)->with($entities)->find($id, $columns);
    }

    public function destroy($id) {
        return $this->entity->destroy($id);
    }

    public function sync(array $ids) {
        return $this->entity->sync($ids);
    }
    
    public function withEntities(array $entities) {
        
        return $this->entity->with($entities);
    }

    public function whereMatch(array $where) {
        
        $entity = $this->entity;
        
        if (!empty($where)) {

            foreach ($where as $field => $value) {
                $entity = $entity->where($field, '=', $value);
            }
            
        }

        return $entity;
    }

}
