<?php

class Subcategory extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subcategory';

    /**
     * To protect against mass assignment, we need to specify which of the columns can be mass assigned.
     * 
     * @var array 
     */
    protected $fillable = array('subcategory_name', 'id_category');

    /**
     * Attribute relationship
     */
    public function attributes() {
        return $this->belongsToMany('Attribute', 'attribute_subcategory', 'id_subcategory', 'id_attribute')->withPivot('id');
    }

    /**
     * Tag relationship
     */
    public function tags() {
        return $this->hasMany('Tag', 'subcategory_id');
    }
    
    /**
     * Tag relationship
     */
    public function activeTags() {
        return $this->hasMany('Tag', 'subcategory_id')->where('active', '=', 1);
    }
    
    /**
     * Tag relationship
     */
    public function activeTagsWithCustom() {
        return $this->hasMany('Tag', 'subcategory_id')->where('active', '=', 1)->where('commerce_id', '=', Session::get('user.id_commerce'))->orWhere('commerce_id', '=', NULL);
    }

    /**
     * Tag by commerce_id relationship
     */
    public function tagsByCommerceId() {
        return $this->hasMany('Tag', 'subcategory_id');
    }

    /* public function tagsByCommerceId() {
      return Subcategory::join('tag', function($join) {
      $join->on('subcategory.id', '=', 'tag.subcategory_id');
      $join->on('tag.active', '=', 1);
      $join->on('tag.commerce_id', '=', NULL);
      $join->orOn('tag.commerce_id', '=', Auth::user()->id_commerce);
      })
      ->where('subcategory.active', '=', 1)
      ->where('subcategory.id', '=', $this->id)
      ->get();
      } */

    public function connections() {
        return
                        User::join('connections', function($join) {
                            $join->on('users.id', '=', 'connections.user_id_from');
                            $join->or_on('users.id', '=', 'connections.user_id_to');
                        })
                        ->where('user.id', '=', $this->id)
                        ->get();
    }

    // Category relationship
    public function categories() {
        return $this->belongsTo('Category', 'id_category');
    }

}
