<?php

namespace App\Http\Controllers\ApiV1\Shared;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiV1\RouteResource;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        return RouteResource::collection(
            Route::with(['routeLocations', 'user', 'car', 'reservationCounts'])
                ->mainFilter()
                ->get()
        );
    }
}
