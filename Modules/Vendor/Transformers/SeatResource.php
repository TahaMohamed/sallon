<?php

namespace Modules\Vendor\Transformers;

use App\Http\Resources\Api\BasicDataResource;
use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
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
                'code' => $this->code,
                'is_available' => (bool)$this->is_available,
                'created_at' => $this->created_at->format('Y-m-d'),
                'actions' => $this->when($request->routeIs('vendor.seats.index'), [
                    'show' => auth()->user()->hasPermission('vendor.seats.show'),
                    'update' => auth()->user()->hasPermission('vendor.seats.update'),
                    'destroy' => auth()->user()->hasPermission('vendor.seats.destroy'),
                ])
            ];
    }
}
