<?php

namespace Modules\Vendor\Transformers;

use App\Http\Resources\Api\BasicDataResource;
use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'identity_number' => $this->identity_number,
            'email' => $this->email,
            'phone' => $this->phone,
            'reason' => $this->reason,
            'email_verified_at' => $this->email_verified_at?->format('Y-m-d H:i'),
            'phone_verified_at' => $this->phone_verified_at?->format('Y-m-d H:i'),
            'banned_at' => $this->banned_at?->format('Y-m-d H:i'),
            'unbanned_at' => $this->unbanned_at?->format('Y-m-d H:i'),
            'is_banned' => $this->isBanned(),
            'department' => BasicDataResource::make($this->whenLoaded('employeeDepartment')),
            'seat' => BasicDataResource::make($this->whenLoaded('employeeSeat')),
            'created_at' => $this->created_at->format('Y-m-d'),
        ];

    }
}
