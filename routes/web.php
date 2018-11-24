<?php

//Auth routes
Auth::routes();

//Authenticated user
Route::middleware('auth')->group(function () {
  // Home page
  Route::get('/', 'HomeController@index')->name('home');

  // Posts page
  Route::get('/posts', 'PostsController@get')->name('posts');

  // Store post
  Route::post('/posts/store', 'PostsController@store');

  // Get post by the given id
  Route::get('/posts/{post}/show', 'PostsController@show')->name('posts.show');

  // Comment Store
  Route::post('/comments/{post}/store', 'CommentsController@store')->name('comments.store');

  Route::get('/notifications', 'NotificationsController@get')->name('notifications.get');

  // Set notifications seen
  Route::post('/set-notifications-seen', 'NotificationsController@setSeen');

  // Get users page
  Route::get('/users', 'UsersController@index')->name('users.index');

  // Get all users with search by name
  Route::get('/users/get', 'UsersController@get')->name('users.get');

  // Get user by id
  Route::get('/users/{user}', 'UsersController@show')->name('users.show');

  // Get user posts
  Route::get('/users/{user}/posts', 'UsersController@posts')->name('user.posts');

  // Messages page
  Route::get('/messages', 'MessagesController@index')->name('messages.index');

  // Room page
  Route::get('/room/{room?}', 'RoomsController@show')->name('room.show');

  // Get user rooms
  Route::get('/rooms', 'RoomsController@rooms')->name('rooms.index');

  // Get room messages
  Route::get('/rooms/{room}/messages', 'RoomsController@messages')->name('rooms.messages');

  // Send message
  Route::post('/message/send', 'RoomsController@sendMessage')->name('message.send');

  // Get not seen messages count
  Route::get('messages/not_seen', 'MessagesController@getNotSeen')->name('message.not_seen');

  // Get auth rooms
  Route::get('/auth_rooms', 'RoomsController@authRooms')->name('auth.rooms');

  // Make message seen
  Route::post('/message/seen', 'MessagesController@makeSeen')->name('message.seen');

  // Get auth user Room
  Route::get('/auth_user/room/{id}', 'RoomsController@getRoom')->name('auth_user.room');

  // Profile page
  Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
  Route::put('/profile', 'ProfileController@update')->name('profile.update');
});
