<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'phone' => 'required|numeric|digits_between:5,20|unique:users,phone,'. $this->employee,
            'center_id' => 'required|exists:centers,id',
            'department_id' => 'required|exists:departments,id',
            'seat_id' => 'nullable|exists:seats,id,center_id,' . $this->center_id,
            'salary' => 'required|decimal:0,2|gt:0',
            'email' => 'nullable|email|max:50|unique:users,email,'. $this->employee,
            'identity_number' => 'nullable|numeric|digits_between:10,20|unique:users,identity_number,'. $this->employee,
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'banned_at' => 'nullable|date',
            'unbanned_at' => 'nullable|date|after:banned_at',
            'reason' => 'nullable|string|max:500',
            'is_phone_verified' => 'nullable|boolean',
            'is_email_verified' => 'nullable|boolean',
        ];
    }
}
