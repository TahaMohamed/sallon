<?php

namespace Modules\Vendor\Transformers;

use App\Http\Resources\Api\BasicDataResource;
use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'status' => [
                'key' => $this->status,
                'value' => __('dashboard.attendance.cases.' . $this->status)
            ],
            'employee' => BasicDataResource::make($this->whenLoaded('employee')),
            'reason' => $this->reason,
            'date' => $this->date?->format('Y-m-d H:i'),
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
