<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Dashboard\Models\Department;
use Modules\Vendor\Http\Requests\{DepartmentRequest, AssignExistsDepartmentRequest};
use Modules\Vendor\Transformers\DepartmentResource;

class DepartmentController extends VendorController
{
    public function index(Request $request)
    {
        $departments = Department::query()
            ->with(['translation','centers' => fn($q) => $q->where('user_id', auth()->id())])
            ->where(function ($q){
                $q->active()->whereRelation('centers','user_id', '<>' , auth()->id());
                $q->orWhereRelation('centers','user_id' , auth()->id());
            })
            ->when(@$request->filter['my_departments'], function ($q){
                $q->whereHas('centers', fn($q) => $q->where('centers.user_id', auth()->id()));
            })
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: DepartmentResource::collection($departments), collection: $departments);
    }

    public function store(DepartmentRequest $request)
    {
        $department = Department::where(function ($q) use ($request){
            foreach (config('translatable.locales') as $locale){
                $q->orWhereTranslation('name', $request->{$locale}['name'], $locale);
            }
        })->firstOrNew();
        $this->create($request->validated() + ['added_by_id' => auth()->id()], $department);
        return $this->successResponse(message: __('dashboard.message.success_add'), code: 201);
    }

    public function assignToMe(AssignExistsDepartmentRequest $request)
    {
        auth()->user()->center->departments()->sync($request->validated(['departments']));
        return $this->successResponse(message: __('dashboard.message.success_update'), code: 201);
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
            ->where(function ($q){
                $q->active()->whereRelation('centers','user_id', '<>' , auth()->id());
                $q->orWhereRelation('centers','user_id' , auth()->id());
            })
            ->with(['centers' => fn($q) => $q->where('user_id', auth()->id())])
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: DepartmentResource::make($department));
    }

    public function update(DepartmentRequest $request, $id)
    {
        $department = Department::query()
            ->withCount('centers')
            ->whereHas('centers', fn($q) => $q->where('centers.user_id', auth()->id()))
            ->findOrFail($id);
        $this->create($request->validated(), $department);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $department = Department::query()
            ->withCount('centers')
            ->whereHas('centers', fn($q) => $q->where('centers.user_id', auth()->id()))
            ->findOrFail($id);
        if ($department->added_by_id === auth()->id() && $department->centers_count === 1){
            $department->delete();
        }
        $department->centers()->detach(auth()->user()->center?->id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }

    private function create($data, $department)
    {
        if (!$department->getKey() || ($department->added_by_id === auth()->id() && $department->centers_count === 1)){
            $department->fill($data)->save();
        }
        $department->centers()->sync(auth()->user()->center?->id);
    }
}
