<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use Auth;

class PostsController extends Controller
{
    /**
     * Get posts except user posts
     *
     * @return resposne
     */
    public function get(Request $request) {
      $posts = Post::where('user_id', '!=', Auth::user()->id)->with('user')->orderBy('id', 'desc')->paginate(10);
      foreach($posts as $post) {
        $post->details = str_limit(html_entity_decode(strip_tags($post->details)), 200);
        $post->url = route('posts.show', $post->id);
        $post->user_url = route('users.show', $post->user_id);
      }

      return response()->json(['posts' => $posts]);
    }

    /**
     * Store new post
     *
     * @retrun response
     */
    public function store(Request $request) {
      if ($request->details) {
        $data = ['user_id' => Auth::user()->id, 'details' => $request->details];
        Post::create($data)->save();

        return response()->json(['status' => 1]);
      }
      return response()->json(['status' => 0]);
    }

    /**
     * Show post by id
     *
     * @return view
     */
    public function show($id) {
      $post = Post::where('id', $id)->with('comments', 'comments.user', 'user')->first();
      foreach($post->comments as $comment) {
        $comment = comment_data($comment);
      }

      $most_viewed_users = User::where('id', '!=', Auth::user()->id)->limit(10)->orderBy('views', 'desc')->get();
      $recently_joined_users = User::where('id', '!=', Auth::user()->id)->limit(10)->orderBy('id', 'desc')->get();

      return $post ? view('post', compact('post', 'most_viewed_users', 'recently_joined_users')) : view('layouts.partials.not_found');
    }
}
