<?php

namespace Modules\Dashboard\Http\Requests;

use App\Enums\Package;
use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            'price' => 'required|decimal:0,2|gt:0',
            'duration' => 'required|in:' . join(',', Package::casesValues()),
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = 'required|string|max:255|unique:package_translations,name,' . $this->package . ',package_id';
            $rules["$locale.short_description"] = 'nullable|string|max:300';
            $rules["$locale.description"] = 'nullable|string|max:2000';
        }
        return $rules;
    }
}
