<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiV1\Route\RouteStoreRequest;
use App\Http\Resources\ApiV1\RouteResource;
use App\Services\RouteService;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function __construct(
        private RouteService $routeService
    )
    {
    }

    /**
     * Создает новую поездку
     *
     * @param RouteStoreRequest $request
     * @return RouteResource
     */
    public function store(RouteStoreRequest $request): RouteResource
    {
        return RouteResource::make(
            $this->routeService->store($request->validated())
        );
    }
}
