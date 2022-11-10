<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Route;

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

        $totalReservedSeats = $route->reservations->sum('number_of_seats');

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
}
