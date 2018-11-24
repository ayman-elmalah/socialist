<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;
use App\Notification;
use Auth;
use App\Events\NewComment;
use App\Events\NewNotification;

class CommentsController extends Controller
{
    /**
     * Store new comment for post
     *
     * @return response
     */
    public function store($post_id, Request $request) {
      $post = Post::find($post_id);
      if (! $post) {
        return response()->json(['status' => 0, 'message' => 'Post Not found']);
      }

      if (! $request->details) {
        return response()->json(['status' => 0, 'message' => 'details required']);
      }

      $data = ['user_id' => Auth::user()->id, 'post_id' => $post_id, 'details' => $request->details];
      $comment = Comment::create($data);

      $comment_data = Comment::where('id', $comment->id)->with('user')->first();

      if ($post_id != Auth::user()->id) {
        $notification_data = ['from_id' => Auth::user()->id, 'to_id' => $post->user_id, 'post_id' => $post_id, 'type' => 'comment'];
        $notification = Notification::create($notification_data);
        $notification = Notification::where('id', $notification->id)->with('to', 'from')->first();
        event(New NewNotification($notification));
      }

      broadcast(New NewComment($comment_data))->toOthers();

      return response()->json(['status' => 1, 'comment' => $comment_data]);
    }
}
