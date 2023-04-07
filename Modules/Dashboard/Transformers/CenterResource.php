<?php

namespace Modules\Dashboard\Transformers;

use App\Http\Resources\Api\BasicDataResource;
use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CenterResource extends JsonResource
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
                'is_active' => (bool)$this->is_active,
                'short_description' => $this->short_description,
                'description' => $this->description,
                'phone' => $this->phone,
                'email' => $this->email,
                'opend_at' => $this->opend_at?->format("H:i"),
                'cloased_at' => $this->cloased_at?->format("H:i"),
                'days_off' => $this->days_off,
                'city' => BasicDataResource::make($this->whenLoaded('city')),
                'user' => BasicDataResource::make($this->whenLoaded('user')),
                'services_count' => $this->whenCounted('services'),
                'categories_count' => $this->whenCounted('categories'),
                'products_count' => $this->whenCounted('products'),
                'created_at' => $this->created_at->format('Y-m-d'),
            ] + $locales;

    }
}
