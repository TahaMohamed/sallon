<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\EmployeeRequest;
use Modules\Dashboard\Transformers\EmployeeResource;

class EmployeeController extends DashboardController
{
    public function __construct(protected UserRepository $employeeRepository)
    {
        $this->employeeRepository->where(['user_type' => User::EMPLOYEE]);
    }

    public function index(Request $request)
    {
        $employees = $this->employeeRepository
            ->with(['employeeCenter.translation','employeeSeat.translation','employeeDepartment.translation'])
            ->allPaginate($request->per_page);

        return $this->paginateResponse(data: EmployeeResource::collection($employees), collection: $employees);
    }

    public function store(EmployeeRequest $request)
    {
        $this->employeeRepository->create($request->validated() + [
                'added_by_id' => auth()->id(),
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
        return $this->showOrEdit($id, false);
    }

    private function showOrEdit(int $id, bool $isShow = true)
    {
        $employee = $this->employeeRepository->with(['employeeCenter.translation','employeeSeat.translation','employeeDepartment.translation'])->find($id, $isShow);
        return $this->successResponse(data: EmployeeResource::make($employee));
    }

    public function update(EmployeeRequest $request, $id)
    {
        $this->employeeRepository->update($request->validated(), $id);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->employeeRepository->delete($id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
