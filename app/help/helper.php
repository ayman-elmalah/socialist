<?php

/**
 * Convert date time to human readable
 *
 * @return string
 */
function human_date($date) {
    return $date?$date->diffForHumans():'Un known';
}

/**
 * Get notification data
 *
 * @return response
 */
function notification_data($notification) {
   if ($notification->type == 'comment') {
     $notification->url = route('posts.show', $notification->post_id);
     $notification->details = $notification->from->name . ' Commented on your post';
     $notification->user_image = asset('images/avatar.png');
   } else if ($notification->type == 'message') {
     $notification->url = route('room.show', $notification->room_id);
     $notification->details = $notification->from->name . ' Send new message to you';
     $notification->user_image = asset('images/avatar.png');
   }

   return $notification;
}

/**
 * Get comment data
 *
 * @return response
 */
function comment_data($comment) {
  $comment->user->url = route('users.show', $comment->user->id);

  return $comment;
}
