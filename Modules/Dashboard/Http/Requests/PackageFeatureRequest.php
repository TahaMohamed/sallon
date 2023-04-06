<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageFeatureRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [];
        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = 'required|string|max:255|unique:package_feature_translations,name,' . $this->package_feature . ',package_feature_id';
            $rules["$locale.description"] = 'nullable|string|max:500';
        }
        return $rules;
    }
}
