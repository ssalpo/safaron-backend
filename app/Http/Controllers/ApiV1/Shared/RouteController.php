<?php

namespace App\Http\Controllers\ApiV1\Shared;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiV1\RouteResource;
use App\Models\Route;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RouteController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    )
    {
    }

    /**
     * Поиск и фильтрация списка поездок
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return RouteResource::collection(
            Route::with(['routeLocations', 'user', 'car', 'reservationCounts'])
                ->mainFilter()
                ->get()
        );
    }

    /**
     * Просмотр конкретной поездки
     *
     * @param string $id
     * @return RouteResource
     */
    public function show(string $id): RouteResource
    {
        $isAuth = auth('sanctum')->check();
        $authId = auth('sanctum')->id();
        $route = Route::with(['routeLocations', 'driver', 'car', 'reservationCounts'])->findOrFail($id);

        // Список бронирований показываем, только если пользователь
        // авторизован и имеет бронирование в данной поездке или если
        // поездка относится к водителю
        if (
            $route->driver->id === $authId ||
            ($isAuth && $this->reservationService->existsForPassengerInRoute($id, $authId))
        ) {
            $route->load('reservations');
        }

        return RouteResource::make($route);
    }
}
