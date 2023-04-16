<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Dashboard\Models\Service;
use Modules\Vendor\Http\Requests\{ServiceRequest, AssignExistsServiceRequest};
use Modules\Vendor\Transformers\ServiceResource;

class ServiceController extends VendorController
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->with(['translation','centers' => fn($q) => $q->where('user_id', auth()->id())])
            ->where(function ($q){
                $q->active()->whereRelation('centers','user_id', '<>' , auth()->id());
                $q->orWhereRelation('centers','user_id' , auth()->id());
            })
            ->when(@$request->filter['my_services'], function ($q){
                $q->whereHas('centers', fn($q) => $q->where('centers.user_id', auth()->id()));
            })
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: ServiceResource::collection($services), collection: $services);
    }

    public function store(ServiceRequest $request)
    {
        $service = Service::where(function ($q) use ($request){
            foreach (config('translatable.locales') as $locale){
                $q->orWhereTranslation('name', $request->{$locale}['name'], $locale);
            }
        })->firstOrNew();
        if (!$service->getKey()){
           $service->fill($request->validated() + ['added_by_id' => auth()->id()])->save();
        }
        $service->centers()->sync([
            auth()->user()->center?->id => $request->validated(['price','is_soon','is_available'])]);
        return $this->successResponse(message: __('dashboard.message.success_add'), code: 201);
    }

    public function assignToMe(AssignExistsServiceRequest $request)
    {
        auth()->user()->center->services()->sync(Arr::keyBy($request->validated(['services']), 'service_id'));
        return $this->successResponse(message: __('dashboard.message.success_add'), code: 201);
    }

    public function show(int $id)
    {
        return $this->showOrEdit($id, true);
    }

    public function edit(int $id)
    {
        return $this->showOrEdit($id, false);
    }

    private function showOrEdit(int $id, bool $show)
    {
        $service = Service::query()
            ->where(function ($q){
                $q->active()->whereRelation('centers','user_id', '<>' , auth()->id());
                $q->orWhereRelation('centers','user_id' , auth()->id());
            })
            ->with(['centers' => fn($q) => $q->where('user_id', auth()->id())])
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: ServiceResource::make($service));
    }

    public function update(ServiceRequest $request, $id)
    {
        $service = Service::query()
            ->withCount('centers')
            ->whereHas('centers', fn($q) => $q->where('centers.user_id', auth()->id()))
            ->findOrFail($id);
        if ($service->added_by_id === auth()->id() && $service->centers_count === 1){
            $service->fill($request->validated())->save();
        }
        $service->centers()->sync([
            auth()->user()->center?->id => $request->validated(['price','is_soon','is_available'])]);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $service = Service::query()
            ->withCount('centers')
            ->whereHas('centers', fn($q) => $q->where('centers.user_id', auth()->id()))
            ->findOrFail($id);
        if ($service->added_by_id === auth()->id() && $service->centers_count === 1){
            $service->delete();
        }
        $service->centers()->detach(auth()->user()->center?->id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
