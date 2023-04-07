<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\ProductRequest;
use Modules\Dashboard\Models\Product;
use Modules\Dashboard\Transformers\ProductResource;

class ProductController extends DashboardController
{

    public function __construct(protected ProductRepository $productRepository)
    {
    }

    public function index(Request $request)
    {
        $products = $this->productRepository->allPaginate(auth()->user(), $request->per_page);
        return $this->paginateResponse(data: ProductResource::collection($products), collection: $products);
    }

    public function store(ProductRequest $request)
    {
        $this->productRepository->create($request->validated(), auth()->user());
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
        $service = $this->productRepository;
        if (!$isShow){
            $service->with(['category.translation', 'attachments:id,image']);
        }
        $product = $service->find($id, auth()->user(), $isShow);
        return $this->successResponse(data: ProductResource::make($product));
    }

    public function update(ProductRequest $request, $id)
    {
        $this->productRepository->update($request->validated(), $id, auth()->user());
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $this->productRepository->delete($id, auth()->user());
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
