<?php

namespace Modules\Dashboard\Transformers;

use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageFeatureResource extends JsonResource
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
                'packages_count' => $this->whenCounted('packages'),
                'created_at' => $this->created_at->format('Y-m-d'),
                'actions' => $this->when($request->routeIs('dashboard.package_features.index'), [
                    'show' => auth()->user()->hasPermission('dashboard.package_features.show'),
                    'update' => auth()->user()->hasPermission('dashboard.package_features.update'),
                    'destroy' => auth()->user()->hasPermission('dashboard.package_features.destroy'),
                ])
            ] + $locales;
    }
}
