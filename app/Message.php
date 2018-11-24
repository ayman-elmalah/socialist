<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'room_id', 'user_id', 'details', 'status',
  ];

  /**
   * Get message room data
   *
   * @return relation
   */
  public function room() {
    return $this->belongsTo('App\Room');
  }

  /**
   * Get message user data
   *
   * @return relation
   */
  public function user() {
    return $this->belongsTo('App\User');
  }
}
