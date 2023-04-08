<?php

namespace Modules\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignExistsServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
         return [
             'services' => 'nullable|array',
             'services.*' => 'nullable|array',
             'services.*.service_id' => 'nullable|exists:services,id',
             'services.*.is_available' => 'nullable|boolean',
             'services.*.is_soon' => 'nullable|boolean',
             'services.*.price' => 'required|numeric|gte:0',
        ];
    }
}
