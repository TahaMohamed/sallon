<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

    public function rules(): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'center_id' => 'required|exists:centers,id',
            'is_active' => 'nullable|boolean',
            'price' => 'required|decimal:0,2|gt:0',
            'stock' => 'required|decimal:0,2|gt:0',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|mimes:jpg,jpeg,png,svg',

            'deleted_attachments' => 'nullable|array',
            'deleted_attachments.*' => 'nullable|exists:product_media,id',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = 'required|string|max:150';
            $rules["$locale.description"] = 'nullable|string|max:500';
        }
        return $rules;
    }
}
