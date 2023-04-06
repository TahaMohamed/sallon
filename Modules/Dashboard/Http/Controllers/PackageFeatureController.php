<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\PackageFeatureRequest;
use Modules\Dashboard\Models\PackageFeature;
use Modules\Dashboard\Transformers\PackageFeatureResource;

class PackageFeatureController extends DashboardController
{
    public function index()
    {
        $features = PackageFeature::query()
            ->with('translation')
            ->withCount('packages')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: PackageFeatureResource::collection($features), collection: $features);
    }

    public function store(PackageFeatureRequest $request)
    {
        PackageFeature::create($request->validated() + ['added_by_id' => auth()->id()]);
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
        $feature = PackageFeature::query()
            ->withCount('packages')
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: PackageFeatureResource::make($feature));
    }

    public function update(PackageFeatureRequest $request, $id)
    {
        $feature = PackageFeature::query()->findOrFail($id);
        $feature->update($request->validated());
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $feature = PackageFeature::query()->findOrFail($id);
        $feature->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
