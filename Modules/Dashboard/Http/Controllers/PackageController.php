<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\PackageRequest;
use Modules\Dashboard\Models\Package;
use Modules\Dashboard\Transformers\PackageResource;

class PackageController extends DashboardController
{
    public function index()
    {
        $packages = Package::query()
            ->with('translation')
            ->withCount('features')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: PackageResource::collection($packages), collection: $packages);
    }

    public function store(PackageRequest $request)
    {
        $package = Package::create($request->validated() + ['added_by_id' => auth()->id()]);
        $package->features()->attach($request->validated(['features']));
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
        $package = Package::query()
            ->withCount('features')
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: PackageResource::make($package));
    }

    public function update(PackageRequest $request, $id)
    {
        $package = Package::query()->findOrFail($id);
        $package->update($request->validated());
        $package->features()->sync($request->validated(['features']));
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $package = Package::query()->findOrFail($id);
        $package->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
