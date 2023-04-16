<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\CustomerRequest;
use Modules\Dashboard\Transformers\CustomerResource;

class CustomerController extends DashboardController
{
    public function __construct(protected UserRepository $customerRepository)
    {
        $this->customerRepository->where(['user_type' => User::CUSTOMER]);
    }

    public function index(Request $request)
    {
        $customers = $this->customerRepository
            ->allPaginate($request->per_page);

        return $this->paginateResponse(data: CustomerResource::collection($customers), collection: $customers);
    }

    public function store(CustomerRequest $request)
    {
        $this->customerRepository->create($request->validated() + [
                'added_by_id' => auth()->id(),
                'user_type' => User::CUSTOMER
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
        $customer = $this->customerRepository->find($id, $isShow);
        return $this->successResponse(data: CustomerResource::make($customer));
    }

    public function update(CustomerRequest $request, $id)
    {
        $this->customerRepository->update($request->validated(), $id);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->customerRepository->delete($id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
