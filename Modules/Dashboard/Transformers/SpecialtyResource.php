<?php

namespace Modules\Dashboard\Transformers;

use App\Http\Resources\Api\BasicDataResource;
use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialtyResource extends JsonResource
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
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => (bool)$this->is_active,
                'centers_count' => $this->whenCounted('centers'),
                'created_at' => $this->created_at->format('Y-m-d'),
                'actions' => $this->when($request->routeIs('dashboard.specialties.index'), [
                    'show' => auth()->user()->hasPermission('dashboard.specialties.show'),
                    'update' => auth()->user()->hasPermission('dashboard.specialties.update'),
                    'destroy' => auth()->user()->hasPermission('dashboard.specialties.destroy'),
                ])
            ] + $locales;

    }
}