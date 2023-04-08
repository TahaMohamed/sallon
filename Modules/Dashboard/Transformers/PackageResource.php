<?php

namespace Modules\Dashboard\Transformers;

use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
                'short_description' => $this->short_description,
                'description' => $this->description,
                'is_active' => (bool)$this->is_active,
                'created_at' => $this->created_at->format('Y-m-d'),
                'actions' => $this->when($request->routeIs('dashboard.packages.index'), [
                    'show' => auth()->user()->hasPermission('dashboard.packages.show'),
                    'update' => auth()->user()->hasPermission('dashboard.packages.update'),
                    'destroy' => auth()->user()->hasPermission('dashboard.packages.destroy'),
                ])
            ] + $locales;

    }
}
