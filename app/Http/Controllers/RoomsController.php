<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoomMember;
use App\Room;
use App\User;
use App\Message;
use App\Notification;
use Auth;
use DB;
use App\Events\NewNotification;
use App\Events\NewMessage;

class RoomsController extends Controller
{
    /**
     * Show room by the given id
     *
     * @return view
     */
    public function show($room_id = null, Request $request) {
      $auth_rooms = RoomMember::where('user_id', Auth::user()->id)->pluck('room_id');
      $user = '';

      if (isset($request->user_id) && $request->user_id != null && User::where('id', $request->user_id)->where('id', '!=', Auth::user()->id)->exists() && $room_id == null) {
        // Check if users have conversations
        $room = RoomMember::whereIn('room_id', $auth_rooms)->where('user_id', $request->user_id)->where('user_id', '!=', Auth::user()->id)->latest('id')->first();
        if ($room) {
          return redirect()->route('room.show', $room->room_id);
        } else {
          $user = User::where('id', $request->user_id)->first();
        }
      } else if ($room_id != null && RoomMember::where('room_id', $room_id)->where('user_id', Auth::user()->id)->exists() && ! isset($request->user_id)) {
          Message::where('room_id', $room_id)->where('user_id', '!=', Auth::user()->id)->update(['status' => 'seen']);
      } else {
        return redirect()->route('users.index')->with(['error' => 'Opps, Some thing went wrong']);
      }

      return view('room', compact('user'));
    }

    /**
     * Get user rooms
     *
     * @return response
     */
    public function rooms(Request $request) {
        // Get authenticated user rooms
        $auth_rooms = RoomMember::where('user_id', Auth::user()->id)->pluck('room_id');

       $latest_rooms = Room::whereIn('rooms.id', $auth_rooms)
                            ->leftJoin('messages', 'rooms.id', '=', 'messages.room_id')
                            ->groupBy(['rooms.id'])
                            ->orderByRaw('max(messages.id) desc')
                            ->select('rooms.id')
                            ->with('other_member.user', 'latest_message')
                            ->withCount('notseen_messages')
                            ->paginate();

      foreach($latest_rooms as $room) {
        $room->url = route('room.show', $room->id);
      }

      return response()->json(['rooms' => $latest_rooms]);
    }

    /**
     * Messages for room
     *
     * @return response
     */
    public function messages($room_id) {
      $auth_rooms = RoomMember::where('user_id', Auth::user()->id)->pluck('room_id');
      $room = Room::whereIn('id', $auth_rooms)->where('id', $room_id)->with('other_member', 'other_member.user')->first();
      $room->user_url = route('users.show', $room->other_member->user_id);

      if ($room) {
        $messages = Message::where('room_id', $room->id)->orderBy('id', 'desc')->with('user')->paginate();

        return response()->json(['room' => $room, 'messages' => $messages]);
      }
    }

    /**
     * Send message
     *
     * @return response
     */
    public function sendMessage(Request $request) {
      if ($request->text_message != '') {
        if ($request->room_id != null) {
          $room = RoomMember::where('room_id', $request->room_id)->where('user_id', Auth::user()->id)->first();
          if (! $room) {
            return response()->json(['message'=> 'Room Not found, please refresh page'], 422);
          } else {
            $message = Message::create(['room_id' => $request->room_id, 'user_id' => Auth::user()->id, 'details' => $request->text_message]);
            $message = Message::where('id', $message->id)->with('user')->first();

            broadcast(New NewMessage($message))->toOthers();
            return response()->json(['message' => $message]);
          }
        } else if ($request->user_id != '' && User::where('id', $request->user_id)->where('id', '!=', Auth::user()->id)->exists()) {
          $auth_rooms = RoomMember::where('user_id', Auth::user()->id)->pluck('room_id');
          if (RoomMember::where('user_id', $request->user_id)->whereIn('room_id', $auth_rooms)->exists()) {
            return response()->json(['message'=> 'Room Aalready exist'], 422);
          } else {
            $room = Room::create();
            RoomMember::create(['user_id' => Auth::user()->id, 'room_id' => $room->id]);
            RoomMember::create(['user_id' => $request->user_id, 'room_id' => $room->id]);
            $message = Message::create(['room_id' => $room->id, 'user_id' => Auth::user()->id, 'details' => $request->text_message]);
            $message = Message::where('id', $message->id)->with('user')->first();

            $notification_data = ['from_id' => Auth::user()->id, 'to_id' => $request->user_id, 'room_id' => $room->id, 'type' => 'message'];
            $notification = Notification::create($notification_data);
            $notification = Notification::where('id', $notification->id)->with('to', 'from')->first();
            event(New NewNotification($notification));

            return response()->json(['message' => $message, 'redirect_url' => route('room.show', $room->id)]);
          }
        } else {
          return response()->json(['message'=> 'Opps, Something went wrong'], 422);
        }
      } else {
        return response()->json(['message'=> 'Message cant be empty'], 422);
      }
    }

    /**
     * Get auth rooms
     *
     * @return response
     */
    public function authRooms(Request $request) {
      return response()->json(['auth_rooms' => RoomMember::where('user_id', Auth::user()->id)->pluck('room_id')]);
    }

    /**
     * Get auth user room
     *
     * @return response
     */
    public function getRoom($room) {
      // Get authenticated user rooms
      $auth_rooms = RoomMember::where('user_id', Auth::user()->id)->pluck('room_id');

      $room = Room::whereIn('id', $auth_rooms)->where('id', $room)
                          ->with('other_member.user', 'latest_message')
                          ->withCount('notseen_messages')
                          ->first();

      $room->url = route('room.show', $room->id);

      return response()->json(['room' => $room]);
    }
}
