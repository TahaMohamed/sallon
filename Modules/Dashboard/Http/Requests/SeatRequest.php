<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeatRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'center_id' => 'required|exists:centers,id',
            'code' => 'required|alpha_dash:ascii|max:8|unique:seats,code,' . $this->seat . ',id,center_id,' . $this->center_id,
        ];
    }
}
