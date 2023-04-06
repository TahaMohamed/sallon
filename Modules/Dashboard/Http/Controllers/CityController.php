<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Resources\Api\BasicDataResource;
use Illuminate\Http\Request;
use Modules\Dashboard\Http\Requests\CityRequest;
use Modules\Dashboard\Models\City;
use Modules\Dashboard\Transformers\CityResource;

class CityController extends DashboardController
{

    public function index($country_id = null)
    {
        $cities = City::query()
            ->with('translation','country.translation')
            ->when($country_id, fn($q) => $q->where('country_id',$country_id))
            ->latest('id')
            ->paginate((int)($request->per_page ?? config("globals.pagination.per_page")));

        return $this->paginateResponse(data: CityResource::collection($cities), collection: $cities);
    }
    public function list(Request $request)
    {
        $countries = City::query()->listsTranslations('name')->latest()->get();
        return $this->apiResource(BasicDataResource::collection($countries));
    }

    public function store(CityRequest $request, City $city)
    {
        $city->fill($request->validated() + ['added_by_id' => auth()->id()])->save();
        $this->updateCapitalsIfExists($city, $request);
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
        $city = City::query()
            ->with('country.translation')
            ->when(!$show, fn($q) => $q->with('translations'))
            ->when($show, fn($q) => $q->with('translation'))
            ->findOrFail($id);

        return $this->successResponse(data: CityResource::make($city));
    }

    public function update(CityRequest $request, $id)
    {
        $city = City::query()->where('country_id', $request->country_id)->findOrFail($id);
        $city->fill($request->validated())->save();
        $this->updateCapitalsIfExists($city, $request);
        return $this->successResponse(message: __('dashboard.message.success_update'));
    }

    public function destroy($id)
    {
        $city = City::query()->findOrFail($id);
        $city->delete();
        return $this->successResponse(message: __('dashboard.message.success_delete'));
    }

    private function updateCapitalsIfExists($city, $request): void
    {
        if ($request->is_country_capital){
            $city->country()->update(['capital_city_id' => $city->id]);
        }
    }
}
