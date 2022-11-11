<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Route;
use Illuminate\Support\Arr;

class ReservationService
{
    public function store(array $data)
    {
        $route = Route::with('reservations')->findOrFail($data['route_id']);

        // Если включена мгновенная бронь, то сразу подтверждаем
        $status = $route->fast_reservation
            ? Reservation::STATUS_CONFIRMED
            : Reservation::STATUS_UNDER_CONSIDERATION;

        $data = ['user_id' => auth()->id(), 'status' => $status] + $data;

        $totalReservedSeats = $route->reservations->where('status', Reservation::STATUS_CONFIRMED)->sum();

        // Водитель не может бронировать свою же поездку
        abort_if(
            $route->user_id === $data['user_id'],
            403,
            'Бронирование своей поездки запрещено.'
        );

        // Проверяем повторную бронь
        abort_if(
            $route->reservations->where('user_id', $data['user_id'])->count() > 0,
            403,
            'Вы ранее бронировали место в данной поездке.'
        );

        // Проверяем превышение лимита на бронирование
        abort_if(
            ($data['number_of_seats'] + $totalReservedSeats) > $route->free_places,
            403,
            'Количество мест превышает максимум.'
        );

        return Reservation::create($data);
    }

    /**
     * Отменяет поездку пассажира
     *
     * @param string $id
     * @param array $data
     * @return void
     */
    public function cancelPassengerReservation(string $id, array $data = []): void
    {
        $route = Reservation::forUser(Arr::get($data, 'user_id'))->findOrFail($id);

        if ($route->status === Reservation::STATUS_CANCEL_BY_DRIVER) {
            abort(403, 'Водитель уже отменил бронирование.');
        }

        $route->update(['status' => Reservation::STATUS_CANCEL_BY_PASSENGER]);
    }

    /**
     * Отмена поездки пассажира водителем
     *
     * @param string $routeId
     * @param string $reservationId
     * @param array $data
     * @return void
     */
    public function cancelDriverReservation(string $routeId, string $reservationId, array $data = []): void
    {
        $route = Reservation::whereHas(
            'route', static fn($q) => $q->where('id', $routeId)
            ->where('user_id', Arr::get($data, 'user_id', auth()->id()))
        )->findOrFail($reservationId);

        if ($route->status === Reservation::STATUS_CANCEL_BY_PASSENGER) {
            abort(403, 'Пассажир уже отменил бронирование.');
        }

        $route->update(['status' => Reservation::STATUS_CANCEL_BY_DRIVER]);
    }

    /**
     * Подтверждает бронирование пассажира
     *
     * @param string $routeId
     * @param string $reservationId
     * @param array $data
     * @return void
     */
    public function confirmDriverReservation(string $routeId, string $reservationId, array $data = []): void
    {
        $route = Reservation::whereStatus(Reservation::STATUS_UNDER_CONSIDERATION)->whereHas(
            'route', static fn($q) => $q->where('id', $routeId)
            ->where('user_id', Arr::get($data, 'user_id', auth()->id()))
        )->findOrFail($reservationId);

        $route->update(['status' => Reservation::STATUS_CONFIRMED]);
    }
}
