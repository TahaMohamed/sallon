<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\VendorRequest;
use Modules\Dashboard\Transformers\VendorResource;

class VendorController extends DashboardController
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function index(Request $request)
    {
        $vendors = $this->userRepository
            ->with(['translation'])
            ->withCount(['centers'])
            ->allPaginate($request->per_page);

        return $this->paginateResponse(data: VendorResource::collection($vendors), collection: $vendors);
    }

    public function store(VendorRequest $request)
    {
        $this->userRepository->create($request->validated() + ['added_by_id' => auth()->id()]);
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
        $repo = $this->userRepository;
        if (!$isShow){
            $repo->withCount(['centers']);
        }
        $service = $repo->find($id, $isShow);
        return $this->successResponse(data: VendorResource::make($service));
    }

    public function update(VendorRequest $request, $id)
    {
        $this->userRepository->update($request->validated(), $id);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->userRepository->delete($id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
