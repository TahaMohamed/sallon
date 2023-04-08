<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Repositories\SeatRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\SeatRequest;
use Modules\Dashboard\Transformers\SeatResource;

class SeatController extends DashboardController
{
    public function __construct(protected SeatRepository $seatRepository)
    {
    }

    public function index(Request $request)
    {
        $seats = $this->seatRepository
            ->with(['center'])
            ->allPaginate($request->per_page);

        return $this->paginateResponse(data: SeatResource::collection($seats), collection: $seats);
    }

    public function store(SeatRequest $request)
    {
        $this->seatRepository->create($request->validated() + ['added_by_id' => auth()->id()]);
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
        $seat = $this->seatRepository->with(['center'])->find($id, $isShow);
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
