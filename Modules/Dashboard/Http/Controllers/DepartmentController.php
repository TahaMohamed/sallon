<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Resources\Api\BasicDataResource;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\DepartmentRequest;
use Modules\Dashboard\Models\Department;
use Modules\Dashboard\Transformers\DepartmentResource;

class DepartmentController extends DashboardController
{

    public function index()
    {
        $department = Department::query()
            ->with('translation')
            ->withCount('centers')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: DepartmentResource::collection($department), collection: $department);
    }

    public function list(Request $request)
    {
        $department = Department::query()->listsTranslations('name')->latest()->get();
        return $this->apiResource(BasicDataResource::collection($department));
    }


    public function store(DepartmentRequest $request, Department $department)
    {
        $department->fill($request->validated()+ ['added_by_id' => auth()->id()])->save();
        if ($request->centers){
            $department->centers()->attach($request->validated(['centers']));
        }
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
        $department = Department::query()
            ->withCount('centers')
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: DepartmentResource::make($department));
    }

    public function update(DepartmentRequest $request, $id)
    {
        $department = Department::query()->findOrFail($id);
        $department->fill($request->validated())->save();
        if ($request->centers){
            $department->centers()->attach($request->validated(['centers']));
        }
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $department = Department::query()->withCount('centers')->findOrFail($id);
//        if ($department->employees_count) {
//            return $this->errorResponse(message: __('validation.department.restrict.cannot_delete_category_has_employees'));
//        }
        $department->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
