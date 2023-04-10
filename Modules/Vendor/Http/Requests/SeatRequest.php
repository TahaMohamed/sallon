<?php

namespace Modules\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Dashboard\Models\Seat;

class SeatRequest extends FormRequest
{

    public function rules(): array
    {
        $center_id = $this->user()->ceneter?->id;
        $rules = [
            'code' => 'required|alpha_dash:ascii|max:8|unique:seats,code,' . $this->seat . ',id,center_id,' . $center_id,
            'employees' => 'nullable|array',
            'employees.*' => 'nullable|exists:employees,user_id,center_id,'. $center_id ,
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = ['required','string','max:50', function($attr, $value, $fail) use($locale, $center_id) {
                $seat = Seat::query()
                    ->whereTranslation('name', $value, $locale)
                    ->where('seats.center_id', $center_id)
                    ->when($this->seat, function ($q) {
                        $q->where('seats.id', "<>", $this->seat);
                    })
                    ->exists();
                if ($seat){
                    $fail(__('validation.unique'));
                }
            }];
            $rules["$locale.description"] = 'nullable|string|max:500';
        }
        return $rules;
    }
}
