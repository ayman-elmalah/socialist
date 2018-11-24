<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoomMember;
use App\User;
use App\Message;
use App\Room;
use Auth;

class MessagesController extends Controller
{
    /**
     * Get user messages
     *
     * @return response
     */
    public function index(Request $request) {
      // Get authenticated user rooms
      $auth_rooms = RoomMember::where('user_id', Auth::user()->id)->pluck('room_id');

      // get latest room for authenticated user
      $latest_room = Message::whereIn('room_id', $auth_rooms)->latest('id')->value('room_id');
      if ($latest_room) {
          return redirect()->route('room.show', $latest_room);
      } else {
          return redirect()->route('users.index')->with(['error' => 'You have no messages yet, you can choose user and create conversation now']);
      }
    }

    /**
     * Get not seen message count
     *
     * @return response
     */
    public function getNotSeen() {
      // Get authenticated user rooms
      $auth_rooms = RoomMember::where('user_id', Auth::user()->id)->pluck('room_id');

      $notseen_messages_count = Message::whereIn('room_id', $auth_rooms)->where('user_id', '!=', Auth::user()->id)->where('status', 'not_seen')->count();
      return response()->json(['notseen_messages_count' => $notseen_messages_count]);
    }

    /**
     * Make message seen
     *
     * @return response
     */
    public function makeSeen(Request $request) {
      // Get authenticated user rooms
      $auth_rooms = RoomMember::where('user_id', Auth::user()->id)->pluck('room_id');

      Message::where('id', $request->message_id)->whereIn('room_id', $auth_rooms)->update(['status' => 'seen']);

      return response()->json(['status' => 1]);
    }
}
