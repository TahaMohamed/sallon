<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\ServiceRequest;
use Modules\Dashboard\Models\Service;
use Modules\Dashboard\Transformers\ServiceResource;

class ServiceController extends DashboardController
{
    public function index()
    {
        $packages = Service::query()
            ->with('translation')
            ->withCount('centers')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: ServiceResource::collection($packages), collection: $packages);
    }

    public function store(ServiceRequest $request)
    {
        $package = Service::create($request->validated() + ['added_by_id' => auth()->id()]);
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
        $package = Service::query()
            ->withCount('centers')
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: ServiceResource::make($package));
    }

    public function update(ServiceRequest $request, $id)
    {
        $package = Service::query()->findOrFail($id);
        $package->update($request->validated());
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $package = Service::query()->findOrFail($id);
        $package->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
