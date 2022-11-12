<?php

namespace App\Http\Controllers\ApiV1\Shared;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiV1\RouteResource;
use App\Models\Route;

class SearchController extends Controller
{
    public function search()
    {
        return RouteResource::collection(
            Route::with(['routeLocations', 'user', 'car', 'reservationCounts'])
                ->mainFilter()
                ->get()
        );
    }
}
