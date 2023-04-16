<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Resources\Api\BasicDataResource;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\SpecialtyRequest;
use Modules\Dashboard\Models\Specialty;
use Modules\Dashboard\Transformers\SpecialtyResource;

class SpecialtyController extends DashboardController
{

    public function index()
    {
        $specialties = Specialty::query()
            ->with('translation')
            ->withCount('centers')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: SpecialtyResource::collection($specialties), collection: $specialties);
    }

    public function list(Request $request)
    {
        $specialties = Specialty::query()->listsTranslations('name')->latest()->get();
        return $this->apiResource(BasicDataResource::collection($specialties));
    }


    public function store(SpecialtyRequest $request)
    {
        Specialty::create($request->validated()+ ['added_by_id' => auth()->id()]);
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
        $specialty = Specialty::query()
            ->withCount('centers')
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: SpecialtyResource::make($specialty));
    }

    public function update(SpecialtyRequest $request, $id)
    {
        $specialty = Specialty::query()->findOrFail($id);
        $specialty->update($request->validated());
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $specialty = Specialty::query()->withCount('centers')->findOrFail($id);
        if ($specialty->centers_count) {
            return $this->errorResponse(message: __('validation.specialty.restrict.cannot_delete_specialty_has_centers'));
        }
        $specialty->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
