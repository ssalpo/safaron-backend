<?php

namespace App\Http\Controllers\ApiV1\Account;

use App\Http\Controllers\Controller;
use App\Services\ReservationService;

class RouteReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    )
    {
    }

    /**
     * Отклоняет бронирование пользователя в поездке водителя
     *
     * @param string $routeId
     * @param string $reservationId
     * @return void
     */
    public function cancel(string $routeId, string $reservationId): void
    {
        $this->reservationService->cancelDriverReservation($routeId, $reservationId);
    }

    /**
     * Подтверждает бронирование пассажира
     *
     * @param string $routeId
     * @param string $reservationId
     * @return void
     */
    public function confirm(string $routeId, string $reservationId): void
    {
        $this->reservationService->confirmDriverReservation($routeId, $reservationId);
    }
}
