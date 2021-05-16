<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //

            'username'=> 'bail|required|string|max:255|unique:users,username',
            'email' => 'bail|required|string|email|max:255|unique:users,email',
            'password' => 'bail|required|min:6|max:255'
        ];
    }
    // public function messages()
    // {
    //     return [
    //         'email.required' => 'Email is required!',
    //         'username.required' => 'Name is required!',
    //         'password.required' => 'Password is required!'
    //     ];
    // }
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
