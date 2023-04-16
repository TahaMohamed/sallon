<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\AdminRequest;
use Modules\Dashboard\Transformers\AdminResource;

class AdminController extends DashboardController
{
    public function __construct(protected UserRepository $adminRepository)
    {
        $this->adminRepository->where(['user_type' => User::ADMIN]);
    }

    public function index(Request $request)
    {
        $admins = $this->adminRepository
            ->withCount(['roles'])
            ->allPaginate($request->per_page);

        return $this->paginateResponse(data: AdminResource::collection($admins), collection: $admins);
    }

    public function store(AdminRequest $request)
    {
        $this->adminRepository->create($request->validated() + [
                'added_by_id' => auth()->id(),
                'user_type' => User::ADMIN
            ]);
        //TODO: Send Login Data to user
        return $this->successResponse(message: __('dashboard.message.success_add'), code: 201);
    }

    public function show(int $id)
    {
        return $this->showOrEdit($id);
    }

    public function edit(int $id)
    {
        return $this->showOrEdit($id, false);
    }

    private function showOrEdit(int $id, bool $isShow = true)
    {
        $admin = $this->adminRepository->with(['roles.translation'])->find($id, $isShow);
        return $this->successResponse(data: AdminResource::make($admin));
    }

    public function update(AdminRequest $request, $id)
    {
        $this->adminRepository->update($request->validated(), $id);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->adminRepository->delete($id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
