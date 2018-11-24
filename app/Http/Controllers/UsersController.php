<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use Auth;

class UsersController extends Controller
{
    /**
     * Users page
     *
     * @return response
     */
    public function index(Request $request) {
      return view('users');
    }

    /**
     * Get users with search by name
     *
     * @return response
     */
    public function get(Request $request) {
      $users = User::where('id', '!=', Auth::user()->id);
      if ($request->has('name')) {
        $users = $users->where('name', 'like', '%' . $request->name . '%');
      }
      $users = $users->orderBy('id', 'desc')->paginate(10);

      foreach($users as $user) {
        $user->profile_url = route('users.show', $user->id);
        $user->message_url = route('room.show', ['room' => null, 'user_id' => $user->id]);
      }

      return response()->json(['users' => $users]);
    }

    /**
     * Show user profile
     *
     * @return response
     */
    public function show($id) {
      $user = User::where('id', $id)->withCount('posts')->first();
      if ($user->id != Auth::user()->id) {
        ++$user->views;
        $user->save();
      }

      return $user ? view('user', compact('user')) : view('layouts.partials.not_found');
    }

    /**
     * Get posts for user
     *
     * @return resposne
     */
    public function posts($user_id) {
      $posts = Post::where('user_id', $user_id)->with('user')->orderBy('id', 'desc')->paginate(10);
      foreach($posts as $post) {
        $post->details = str_limit(html_entity_decode(strip_tags($post->details)), 200);
        $post->url = route('posts.show', $post->id);
        $post->user_url = route('users.show', $post->user_id);
      }

      return response()->json(['posts' => $posts]);
    }
}
