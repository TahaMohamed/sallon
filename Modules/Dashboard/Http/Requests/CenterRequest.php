<?php

namespace Modules\Dashboard\Http\Requests;

use App\Enums\WeekDays;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CenterRequest extends FormRequest
{

    public function rules(): array
    {
        $rules = [
            'user_id' => 'required|exists:users,id,user_type,' . User::VENDOR,
            'city_id' => 'nullable|exists:cities,id',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'opened_at' => 'nullable|date_format:H:i',
            'closed_at' => 'nullable|date_format:H:i|after:opened_at',
            'days_off' => 'nullable|in:' . join(',', WeekDays::casesValues()),
            'phone' => 'required|numeric|digits_between:6,20',
            'email' => 'nullable|email|max:50',
            'services' => 'nullable|array',
            'services.*' => 'nullable|array',
            'services.*.service_id' => 'nullable|exists:services,id',
            'services.*.is_available' => 'nullable|boolean',
            'services.*.is_soon' => 'nullable|boolean',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = 'required|string|max:255|unique:center_translations,name,' . $this->center . ',center_id';
            $rules["$locale.short_description"] = 'nullable|string|max:500';
            $rules["$locale.description"] = 'nullable|string|max:500';
        }
        return $rules;
    }
}
