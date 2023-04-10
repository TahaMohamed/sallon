<?php

namespace Modules\Dashboard\Transformers;

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
        $locales = [];
        if ($this->relationLoaded('translations')) {
            foreach (config('translatable.locales') as $locale) {
                $locales['translations'][$locale] = GlobalTransResource::make($this->translations->firstWhere('locale', $locale));
            }
        }
        return [
            'id' => $this->id,
            'code' => $this->code,
            'is_available' => (bool)$this->is_available,
            'center' => BasicDataResource::make($this->whenLoaded('center')),
            'created_at' => $this->created_at->format('Y-m-d'),
            'actions' => $this->when($request->routeIs('dashboard.seats.index'), [
                'show' => auth()->user()->hasPermission('dashboard.seats.show'),
                'update' => auth()->user()->hasPermission('dashboard.seats.update'),
                'destroy' => auth()->user()->hasPermission('dashboard.seats.destroy'),
            ])
        ] + $locales;
    }
}
