<?php

namespace Modules\Vendor\Transformers;

use App\Http\Resources\Api\BasicDataResource;
use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Dashboard\Transformers\AttachmentResource;

class ProductResource extends JsonResource
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
                'stock' => (float)$this->stock,
                'price' => (float)$this->price,
                'is_active' => (bool)$this->is_active,
                'category' => BasicDataResource::make($this->whenLoaded('category')),
                'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
                'created_at' => $this->created_at->format('Y-m-d'),
                'actions' => $this->when($request->routeIs('vendor.products.index'), [
                    'show' => auth()->user()->hasPermission('vendor.products.show'),
                    'update' => auth()->user()->hasPermission('vendor.products.update'),
                    'destroy' => auth()->user()->hasPermission('vendor.products.destroy'),
                ])
            ] + $locales;

    }
}
