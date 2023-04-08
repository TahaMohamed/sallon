<?php

namespace Modules\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeatRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'code' => 'required|alpha_dash:ascii|max:8|unique:seats,code,' . $this->seat . ',id,center_id,' . $this->user()->ceneter?->id,
        ];
    }
}
