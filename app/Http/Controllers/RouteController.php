<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiV1\Route\RouteStoreRequest;
use App\Http\Resources\ApiV1\RouteResource;
use App\Models\Route;
use App\Services\RouteService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RouteController extends Controller
{
    public function __construct(
        private RouteService $routeService
    )
    {
    }

    /**
     * Возвращает список всех поездок
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $routes = Route::forUser()
            ->with(['routeLocations'])
            ->filter()
            ->get();

        return RouteResource::collection($routes);
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

    /**
     * Просмотр поездки
     *
     * @param string $id
     * @return RouteResource
     */
    public function show(string $id): RouteResource
    {
        return RouteResource::make(
            Route::forUser()->with(['routeLocations'])->findOrFail($id)
        );
    }
}
