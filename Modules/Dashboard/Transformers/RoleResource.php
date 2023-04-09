<?php

namespace Modules\Dashboard\Transformers;

use App\Http\Resources\Api\GlobalTransResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
                'created_at' => $this->created_at->format('Y-m-d'),
                'permissions_count' => $this->whenCounted('permissions'),
                'users_count' => $this->whenCounted('users'),
                'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
                'actions' => $this->when($request->routeIs('dashboard.roles.index'), [
                    'show' => auth()->user()->hasPermission('dashboard.roles.show'),
                    'update' => auth()->user()->hasPermission('dashboard.roles.update'),
                    'destroy' => auth()->user()->hasPermission('dashboard.roles.destroy'),
                ])
            ] + $locales;

    }
}
