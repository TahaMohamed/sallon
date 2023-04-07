<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\ServiceRequest;
use Modules\Dashboard\Models\Service;
use Modules\Dashboard\Transformers\ServiceResource;

class ServiceController extends DashboardController
{
    public function __construct(protected ServiceRepository $serviceRepository)
    {
    }

    public function index(Request $request)
    {
        $services = $this->serviceRepository
            ->with(['translation'])
            ->withCount(['centers'])
            ->allPaginate($request->per_page);

        return $this->paginateResponse(data: ServiceResource::collection($services), collection: $services);
    }

    public function store(ServiceRequest $request)
    {
        $this->serviceRepository->create($request->validated() + ['added_by_id' => auth()->id()]);
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
        $repo = $this->serviceRepository;
        if (!$isShow){
            $repo->withCount(['centers']);
        }
        $service = $repo->find($id, $isShow);
        return $this->successResponse(data: ServiceResource::make($service));
    }

    public function update(ServiceRequest $request, $id)
    {
        $this->serviceRepository->update($request->validated(), $id);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->serviceRepository->delete($id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
