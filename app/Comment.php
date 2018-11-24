<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'user_id', 'post_id', 'details',
  ];

  /**
   * Get comment user data
   *
   * @return relation
   */
  public function user() {
    return $this->belongsTo('App\User');
  }
}
