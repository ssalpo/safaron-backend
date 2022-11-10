<?php

namespace App\Services;

use App\Models\Route;
use Carbon\Carbon;
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
            $route = Route::create([
                    'user_id' => auth()->id(),
                    'status' => Route::STATUS_ACTIVE
                ] + $data);

            $route->routeLocations()->createMany($data['locations']);

            $route->load(['routeLocations']);

            return $route;
        });
    }

    /**
     * Обновляет данные поездки
     *
     * @param string $id
     * @param array $data
     * @return Route
     */
    public function update(string $id, array $data): Route
    {
        $route = Route::forUser()->with(['routeLocations'])->findOrFail($id);

        $dataGoTime = Carbon::parse($data['go_time']);

        // Менять можно только время поездки, поэтому делаем проверя и при необходимости меняем
        if (!$dataGoTime->eq($route->go_time)) {
            $data['go_time'] = $route->go_time->setTime(
                $dataGoTime->format('H'), $dataGoTime->format('i')
            )->format('Y-m-d H:i');
        }

        $route->update($data);

        return $route;
    }

    /**
     * Отменяет поездку
     *
     * @param string $id
     * @param array $data
     * @return void
     */
    public function cancel(string $id, array $data): void
    {
        $route = Route::forUser()->findOrFail($id);

        if ($route->isCancel) {
            $route->update(['status' => Route::STATUS_CANCELED] + $data);
        }
    }
}
