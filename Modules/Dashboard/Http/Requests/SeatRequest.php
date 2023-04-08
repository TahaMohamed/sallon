<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeatRequest extends FormRequest
{

    public function rules(): array
    {
        $rules = [
            'center_id' => 'required|exists:centers,id',
            'code' => 'nullable|alpha_dash:ascii|max:8|unique:seats,code,' . $this->seat . ',id,center_id,' . $this->center_id,
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = 'required|string|max:50|unique:seat_translations,name,' . $this->seat . ',seat_id';
            $rules["$locale.description"] = 'nullable|string|max:500';
        }
        return $rules;
    }
}
