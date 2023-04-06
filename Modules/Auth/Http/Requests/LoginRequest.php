<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
            'login_key' => 'required|in:email,phone'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'login_key' => filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone'
        ]);
    }
}
