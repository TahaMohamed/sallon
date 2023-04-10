<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'phone' => 'required|numeric|digits_between:5,20|unique:users,phone,'. $this->admin,
            'email' => 'nullable|email|max:50|unique:users,email,'. $this->admin,
            'identity_number' => 'nullable|numeric|digits_between:10,20|unique:users,identity_number,'. $this->admin,
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'banned_at' => 'nullable|date',
            'unbanned_at' => 'nullable|date|after:banned_at',
            'reason' => 'nullable|string|max:500',
            'is_phone_verified' => 'nullable|boolean',
            'is_email_verified' => 'nullable|boolean',
            'roles' => 'required|array',
            'roles.*' => 'required|exists:roles,id',
        ];
    }
}
