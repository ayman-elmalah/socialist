<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;

class NotificationsController extends Controller
{
    /**
     * Get notifications
     *
     * @return response
     */
    public function get(Request $request) {
      $notifications = Notification::where('to_id', Auth::user()->id)->with('to', 'from')->orderBy('id', 'desc')->paginate(10);

      foreach($notifications as $notification) {
        $notification = notification_data($notification);
      }

      $notseend_notifications_count = Notification::where('to_id', Auth::user()->id)->where('status', 'not_seen')->count();

      return response()->json(['notifications' => $notifications, 'notseend_notifications_count' => $notseend_notifications_count]);
    }

    /**
     * Set notification seen
     *
     * @return response
     */
    public function setSeen(Request $request) {
        Notification::where('to_id', Auth::user()->id)->update(['status' => 'seen']);
    }
}
