<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{

    public function rules(): array
    {
        $rules = [
            'is_active' => 'nullable|boolean',
            'centers' => 'nullable|array',
            'centers.*' => 'nullable|exists:centers,id',
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = 'required|string|max:255|unique:department_translations,name,' . $this->department . ',department_id';
            $rules["$locale.description"] = 'nullable|string|max:500';
        }
        return $rules;
    }
}
