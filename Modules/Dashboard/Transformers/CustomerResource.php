<?php

namespace Modules\Dashboard\Transformers;

use App\Http\Resources\Api\BasicDataResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'identity_number' => $this->identity_number,
            'email' => $this->email,
            'phone' => $this->phone,
            'reason' => $this->reason,
            'image' => $this->image,
            'email_verified_at' => $this->email_verified_at?->format('Y-m-d H:i'),
            'phone_verified_at' => $this->phone_verified_at?->format('Y-m-d H:i'),
            'banned_at' => $this->banned_at?->format('Y-m-d H:i'),
            'unbanned_at' => $this->unbanned_at?->format('Y-m-d H:i'),
            'is_banned' => $this->isBanned(),
            'created_at' => $this->created_at->format('Y-m-d'),
            'actions' => $this->when($request->routeIs('dashboard.customers.index'), [
                'show' => auth()->user()->hasPermission('dashboard.customers.show'),
                'update' => auth()->user()->hasPermission('dashboard.customers.update'),
                'destroy' => auth()->user()->hasPermission('dashboard.customers.destroy'),
            ])
        ];

    }
}
