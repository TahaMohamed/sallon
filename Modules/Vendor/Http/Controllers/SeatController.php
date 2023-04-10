<?php

namespace Modules\Vendor\Http\Controllers;

use App\Repositories\SeatRepository;
use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\SeatRequest;
use Modules\Vendor\Transformers\SeatResource;

class SeatController extends VendorController
{
    public function __construct(protected SeatRepository $seatRepository)
    {
        $this->seatRepository->where(['center_id', auth()->user()?->center?->id]);
    }

    public function index(Request $request)
    {
        $seats = $this->seatRepository
            ->with(['center.translation'])
            ->withCount(['employees'])
            ->allPaginate($request->per_page);

        return $this->paginateResponse(data: SeatResource::collection($seats), collection: $seats);
    }

    public function store(SeatRequest $request)
    {
        $this->seatRepository->create($request->validated() + ['added_by_id' => auth()->id(), 'center_id' => auth()->user()->center?->id]);
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
        $seat = $this->seatRepository
            ->with(['center.translation'])
            ->withCount(['employees'])
            ->find($id, $isShow);
        return $this->successResponse(data: SeatResource::make($seat));
    }

    public function update(SeatRequest $request, $id)
    {
        $this->seatRepository->update($request->validated(), $id);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->seatRepository->delete($id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
