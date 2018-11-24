<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Room extends Model
{
  /**
   * Get Room members
   *
   * @return relation
   */
  public function members() {
    return $this->hasMany('App\RoomMember', 'room_id', 'id');
  }

  /**
   * Get Room messages
   *
   * @return relation
   */
  public function messages() {
    return $this->hasMany('App\Message', 'room_id', 'id');
  }

  /**
   * Get latest Room message
   *
   * @return relation
   */
  public function latest_message() {
    return $this->hasOne('App\Message', 'room_id', 'id')->orderBy('id', 'desc');
  }

  /**
   * Get Other member Room
   *
   * @return relation
   */
  public function other_member() {
    return $this->hasOne('App\RoomMember', 'room_id', 'id')->where('user_id', '!=', Auth::user()->id);
  }

  /**
   * Get un not seen messages for Room
   *
   * @return relation
   */
  public function notseen_messages() {
    return $this->hasMany('App\Message', 'room_id', 'id')->where('user_id', '!=', Auth::user()->id)->where('status', 'not_seen');
  }
}
