<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\VendorRequest;
use Modules\Dashboard\Transformers\VendorResource;

class VendorController extends DashboardController
{
    public function __construct(protected UserRepository $vendorRepository)
    {
        $this->vendorRepository->where(['user_type' => User::VENDOR]);
    }

    public function index(Request $request)
    {
        $vendors = $this->vendorRepository
            ->with(['center.translation'])
            ->allPaginate($request->per_page);

        return $this->paginateResponse(data: VendorResource::collection($vendors), collection: $vendors);
    }

    public function store(VendorRequest $request)
    {
        $this->vendorRepository->create($request->validated() + [
                'added_by_id' => auth()->id(),
                'user_type' => User::VENDOR,
                'password' => User::getIntialPassword()
            ]);
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
        $vendor = $this->vendorRepository->with(['center.translation'])->find($id, $isShow);
        return $this->successResponse(data: VendorResource::make($vendor));
    }

    public function update(VendorRequest $request, $id)
    {
        $this->vendorRepository->update($request->validated(), $id);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->vendorRepository->delete($id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
