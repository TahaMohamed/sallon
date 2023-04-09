<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'is_active' => 'nullable|boolean',
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,id',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = 'required|string|max:255|unique:role_translations,name,' . $this->role . ',role_id';
            $rules["$locale.description"] = 'nullable|string|max:2000';
        }
        return $rules;
    }
}
