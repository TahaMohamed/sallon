<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'identity_number' => $this->identity_number,
            'user_type' => $this->user_type,
            'image' => $this->image,
            'permissions' => ['*'],
            '_token' => $this->_token,
        ];
    }
}
