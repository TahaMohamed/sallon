<?php

namespace Modules\Vendor\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\EmployeeRequest;
use Modules\Vendor\Transformers\EmployeeResource;

class EmployeeController extends VendorController
{
    public function __construct(protected UserRepository $employeeRepository)
    {
        $this->employeeRepository->where(['user_type' => User::EMPLOYEE]);
    }

    public function index(Request $request)
    {
        $employees = $this->employeeRepository
            ->with(['employeeSeat.translation','employeeDepartment.translation'])
            ->getQuery()
            ->whereRelation('employeeCenter','centers.user_id', auth()->id())
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: EmployeeResource::collection($employees), collection: $employees);
    }

    public function store(EmployeeRequest $request)
    {
        $this->employeeRepository->create($request->validated() + [
                'added_by_id' => auth()->id(),
                'center_id' => auth()->user()->center?->id,
                'user_type' => User::EMPLOYEE
            ]);
        return $this->successResponse(message: __('dashboard.message.success_add'), code: 201);
    }

    public function show(int $id)
    {
        return $this->showOrEdit($id);
    }

    public function edit(int $id)
    {
        return $this->showOrEdit($id);
    }

    private function showOrEdit(int $id)
    {
        $employee = $this->employeeRepository
            ->with(['employeeSeat.translation','employeeDepartment.translation'])
            ->getQuery()
            ->whereRelation('employeeCenter','user_id', auth()->id())
            ->findOrFail($id);
        return $this->successResponse(data: EmployeeResource::make($employee));
    }

    public function update(EmployeeRequest $request, $id)
    {
        $user = User::where(['user_type' => User::EMPLOYEE])
            ->whereRelation('employeeCenter','user_id', auth()->id())
            ->findOrFail($id);

        $user->update($request->validated());
        $user->employee()->updateOrCreate(
            ['center_id' => auth()->user()->center?->id],
            array_only($request->validated(),['department_id','seat_id','salary'])
        );
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $user = User::where(['user_type' => User::EMPLOYEE])
            ->whereRelation('employeeCenter','user_id', auth()->id())
            ->findOrFail($id);
        $user->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
