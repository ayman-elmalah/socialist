<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $most_viewed_users = User::where('id', '!=', Auth::user()->id)->limit(10)->orderBy('views', 'desc')->get();
        $recently_joined_users = User::where('id', '!=', Auth::user()->id)->limit(10)->orderBy('id', 'desc')->get();

        return view('home', compact('most_viewed_users', 'recently_joined_users'));
    }
}
