<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Resources\Api\BasicDataResource;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\CategoryRequest;
use Modules\Dashboard\Models\Category;
use Modules\Dashboard\Transformers\CategoryResource;

class CategoryController extends DashboardController
{

    public function index()
    {
        $categories = Category::query()
            ->with('translation')
            ->withCount('products')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: CategoryResource::collection($categories), collection: $categories);
    }

    public function list(Request $request)
    {
        $categories = Category::query()->listsTranslations('name')->latest()->get();
        return $this->apiResource(BasicDataResource::collection($categories));
    }


    public function store(CategoryRequest $request)
    {
        Category::create($request->validated()+ ['added_by_id' => auth()->id()]);
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
        $category = Category::query()
            ->withCount('products')
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: CategoryResource::make($category));
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = Category::query()->findOrFail($id);
        $category->update($request->validated());
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $category = Category::query()->withCount('products')->findOrFail($id);
        if ($category->products_count) {
            return $this->errorResponse(message: __('validation.category.restrict.cannot_delete_category_has_products'));
        }
        $category->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
