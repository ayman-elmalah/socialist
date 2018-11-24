<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfile;
use Auth;

class ProfileController extends Controller
{
    /**
     * Get profile page
     *
     * @return response
     */
    public function edit() {
      return view('profile');
    }

    /**
     * Update profile data
     *
     * @return response
     */
    public function update(UpdateProfile $request) {
      $user = Auth::user();
      $data = $request->except('password', 'views');
      if($request->password != null) {$data = array_merge($data, ['password' => bcrypt($request->password)]);}

      $user->fill($data)->save();

      return redirect()->back()->with(['success' => __('lang.updated_successfully')]);
    }
}
