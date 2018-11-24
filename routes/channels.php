<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// New comment Channel
Broadcast::channel('post.{id}.comment', function ($user, $id) {
    return \App\User::where('id', $user->id)->first();
});

// New Notification Channel
Broadcast::channel('notification.{id}.user', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// New Message Channel
Broadcast::channel('messages', function ($user) {
    return \App\RoomMember::where('user_id', Auth::user()->id)->exists();
});

// Room Channel
Broadcast::channel('room.{id}', function ($user, $id) {
    return \App\RoomMember::where('user_id', Auth::user()->id)->where('room_id', $id)->exists();
});
