<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Resources\Api\BasicDataResource;
use App\Repositories\CenterRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\CenterRequest;
use Modules\Dashboard\Transformers\CenterResource;

class CenterController extends DashboardController
{

    public function __construct(protected CenterRepository $centerRepository)
    {
    }

    public function index(Request $request)
    {
        $centers = $this->centerRepository
            ->with(['user','city','specialty'])
            ->withCount(['products','categories','services'])
            ->allPaginate($request->per_page);
        return $this->paginateResponse(data: CenterResource::collection($centers), collection: $centers);
    }

    public function list(Request $request)
    {
        $centers = $this->centerRepository->getQuery()->listsTranslations('name')->get();
        return $this->successResponse(BasicDataResource::collection($centers));
    }

    public function store(CenterRequest $request)
    {
        $this->centerRepository->create($request->validated() + ['added_by_id' => auth()->id()]);
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
        $service = $this->centerRepository->with(['user','city','specialty']);
        if ($isShow){
            $service->withCount(['products','categories','services']);
        }

        $center = $service->find($id, $isShow);
        return $this->successResponse(data: CenterResource::make($center));
    }

    public function update(CenterRequest $request, $id)
    {
        $this->centerRepository->update($request->validated(), $id);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->centerRepository->delete($id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
