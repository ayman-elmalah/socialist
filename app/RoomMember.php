<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomMember extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'room_id', 'user_id',
  ];

  /**
   * Get room data
   *
   * @return relation
   */
  public function room() {
    return $this->belongsTo('App\Room');
  }

  /**
   * Get user data
   *
   * @return relation
   */
  public function user() {
    return $this->belongsTo('App\User');
  }
}
