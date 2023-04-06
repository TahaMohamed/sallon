<?php

namespace Modules\Dashboard\Transformers;

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
                'centers_count' => $this->whenCounted('centers'),
                'created_at' => $this->created_at->format('Y-m-d'),
            ] + $locales;

    }
}
