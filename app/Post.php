<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'user_id', 'details',
  ];

  /**
   * Get post user data
   *
   * @return relation
   */
  public function user() {
    return $this->belongsTo('App\User');
  }

  /**
   * Get post comments
   *
   * @return relation
   */
   public function comments() {
     return $this->hasMany('App\Comment')->orderBy('id', 'desc');
   }
}
