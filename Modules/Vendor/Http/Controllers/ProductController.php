<?php

namespace Modules\Vendor\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Modules\Vendor\Http\Requests\ProductRequest;
use Modules\Vendor\Transformers\ProductResource;

class ProductController extends VendorController
{

    public function __construct(protected ProductRepository $productRepository)
    {
        $this->productRepository->where(['center_id',auth()->user()->center?->id]);
    }

    public function index(Request $request)
    {
        $products = $this->productRepository
            ->with(['category.translation', 'attachments:id,image'])
            ->allPaginate($request->per_page);
        return $this->paginateResponse(data: ProductResource::collection($products), collection: $products);
    }

    public function store(ProductRequest $request)
    {
        $this->productRepository->create($request->validated() + ['added_by_id' => auth()->id(), 'center_id', auth()->user()->center?->id]);
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
        $product = $this->productRepository
            ->with(['category.translation', 'attachments:id,image'])
            ->find($id, $isShow);
        return $this->successResponse(data: ProductResource::make($product));
    }

    public function update(ProductRequest $request, $id)
    {
        $this->productRepository->update($request->validated(), $id);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->productRepository->delete($id);
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
