<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Dashboard\Models\Seat;

class SeatRequest extends FormRequest
{

    public function rules(): array
    {
        $rules = [
            'center_id' => 'required|exists:centers,id',
            'code' => 'nullable|alpha_dash:ascii|max:8|unique:seats,code,' . $this->seat . ',id,center_id,' . $this->center_id,
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = ['required','string','max:50', function($attr, $value, $fail) use($locale) {
                $seat = Seat::query()
                    ->whereTranslation('name', $value, $locale)
                    ->where('center_id', $this->center_id)
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
