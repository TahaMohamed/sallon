<?php

namespace Modules\Vendor\Transformers;

use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
                'image' => $this->image,
                'description' => $this->description,
                'created_at' => $this->created_at->format('Y-m-d'),
                'actions' => $this->when($request->routeIs('vendor.services.index'), [
                    'show' => auth()->user()->hasPermission('vendor.services.show'),
                    'update' => auth()->user()->hasPermission('vendor.services.update'),
                    'destroy' => auth()->user()->hasPermission('vendor.services.destroy'),
                ])
            ] + $locales;

    }
}
