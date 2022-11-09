<?php

namespace App\Services;

use App\Models\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RouteService
{
    /**
     * Создает новую поездку
     *
     * @param array $data
     * @return Route
     */
    public function store(array $data): Route
    {
        return DB::transaction(static function () use ($data) {
            $route = Route::create($data);

            $route->routeLocations()->createMany($data['locations']);

            $route->load(['routeLocations.fromPlace', 'routeLocations.toPlace']);

            return $route;
        });
    }
}
