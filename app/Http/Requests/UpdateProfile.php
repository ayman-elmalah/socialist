<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
      return auth()->check();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
      $id = auth()->user()->id;
      return [
          'name' => 'required|string|min:2|max:191',
          'email' => 'required|email|min:2|max:191|unique:users,email,'.$id,
          'password' => 'nullable|min:6|max:191',
      ];
  }
}
