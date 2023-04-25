<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Vendor\Models\Attendance;
use Modules\Vendor\Http\Requests\{AttendanceRequest, AssignExistsAttendanceRequest};
use Modules\Vendor\Transformers\AttendanceResource;

class AttendanceController extends VendorController
{
    public function index(Request $request)
    {
        $attendences = Attendance::query()
            ->where('vendor_id', auth()->id())
            ->with('employee')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: AttendanceResource::collection($attendences), collection: $attendences);
    }

    public function store(AttendanceRequest $request, Attendance $attendance)
    {
        $this->create(
            $request->validated() + [
                'added_by_id' => auth()->id(),
                'vendor_id' => auth()->id()
            ],
            $attendance
        );
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
        $attendance = Attendance::query()
            ->where('vendor_id', auth()->id())
            ->with('employee')
            ->findOrFail($id);

        return $this->successResponse(data: AttendanceResource::make($attendance));
    }

    public function update(AttendanceRequest $request, $id)
    {
        $attendance = Attendance::query()
            ->where('vendor_id', auth()->id())
            ->findOrFail($id);

        $this->create($request->validated(), $attendance);

        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        abort(404);
    }

    private function create($data, $attendance)
    {
        $attendance->fill($data)->save();
    }
}
