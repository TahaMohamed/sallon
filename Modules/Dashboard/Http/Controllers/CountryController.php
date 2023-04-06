<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Resources\Api\BasicDataResource;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\CountryRequest;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Transformers\CountryResource;

class CountryController extends DashboardController
{

    public function index()
    {
        $countries = Country::query()
            ->with('translation','capital.translation')
            ->withCount('cities')
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: CountryResource::collection($countries), collection: $countries);
    }

    public function list(Request $request)
    {
        $countries = Country::query()->listsTranslations('name')->latest()->get();
        return $this->apiResource(BasicDataResource::collection($countries));
    }


    public function store(CountryRequest $request)
    {
        Country::create($request->validated()+ ['added_by_id' => auth()->id()]);
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
        $country = Country::query()
            ->withCount('cities')
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation','capital.translation'))
            ->findOrFail($id);

        return $this->successResponse(data: CountryResource::make($country));
    }

    public function update(CountryRequest $request, $id)
    {
        $country = Country::query()->findOrFail($id);
        $country->update($request->validated());
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $country = Country::query()->withCount('cities')->findOrFail($id);
        if ($country->cities_count) {
            return $this->errorResponse(message: __('validation.country.restrict.cannot_delete_country_has_cities'));
        }
        $country->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }
}
