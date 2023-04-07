<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Resources\Api\BasicDataResource;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\ProductRequest;
use Modules\Dashboard\Models\Product;
use Modules\Dashboard\Transformers\ProductResource;

class ProductController extends DashboardController
{

    public function index()
    {
        $products = Product::query()
            ->with('translation','category.translation','attachments:id,image')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: ProductResource::collection($products), collection: $products);
    }

    public function list(Request $request)
    {
        $products = Product::query()->listsTranslations('name')->latest()->get();
        return $this->apiResource(BasicDataResource::collection($products));
    }

    public function store(ProductRequest $request, Product $product)
    {
        $product->fill($request->validated()+ ['added_by_id' => auth()->id()])->save();
        if ($request->attachments){
            $product->attachments()->createMany($request->validated(['attachments']));
        }
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
        $product = Product::query()
            ->with('category.translation', 'attachments:id,image')
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: ProductResource::make($product));
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::query()->findOrFail($id);
        $product->fill($request->validated()+ ['added_by_id' => auth()->id()])->save();
        if ($request->attachments){
            $product->attachments()->createMany($request->validated(['attachments']));
        }
        if ($request->deleted_attachments){
            $product->attachments()->whereIn('product_media.id',$request->deleted_attachments)->delete();
        }
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $product = Product::query()->findOrFail($id);
        $product->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
