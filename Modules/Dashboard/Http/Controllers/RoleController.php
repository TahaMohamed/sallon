<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Resources\Api\BasicDataResource;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\RoleRequest;
use Modules\Dashboard\Models\Permission;
use Modules\Dashboard\Models\Role;
use Modules\Dashboard\Transformers\RoleResource;

class RoleController extends DashboardController
{

    public function index()
    {
        $roles = Role::query()
            ->with('translation')
            ->withCount('permissions','users')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: RoleResource::collection($roles), collection: $roles);
    }

    public function list(Request $request)
    {
        $roles = Role::query()->listsTranslations('name')->latest()->get();
        return $this->apiResource(BasicDataResource::collection($roles));
    }

    public function listPermissions(Request $request)
    {
        $permissions = Permission::query()->latest()->get();
        return $this->apiResource(BasicDataResource::collection($permissions));
    }


    public function store(RoleRequest $request)
    {
        $role = Role::create($request->validated()+ ['added_by_id' => auth()->id()]);
        if ($request->permissions){
            $role->permissions()->sync($request->validated('permissions'));
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
        $role = Role::query()
            ->withCount('permissions','users')
            ->when(!$show, fn($q) => $q->with('translations','permissions'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: RoleResource::make($role));
    }

    public function update(RoleRequest $request, $id)
    {
        $role = Role::query()->findOrFail($id);
        $role->update($request->validated());
        if ($request->permissions){
            $role->permissions()->sync($request->validated('permissions'));
        }
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $role = Role::query()->findOrFail($id);
        $role->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
