<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'from_id', 'to_id', 'post_id', 'room_id', 'type', 'status',
  ];

  /**
   * Get comment user data
   *
   * @return relation
   */
  public function from() {
    return $this->hasOne('App\User', 'id', 'from_id');
  }

  /**
   * Get comment user data
   *
   * @return relation
   */
  public function to() {
    return $this->hasOne('App\User', 'id', 'to_id');
  }

  /**
   * Get comment user data
   *
   * @return relation
   */
  public function post() {
    return $this->belongsTo('App\Post');
  }

  /**
   * Get eoom data
   *
   * @return relation
   */
  public function room() {
    return $this->belongsTo('App\Room');
  }
}
