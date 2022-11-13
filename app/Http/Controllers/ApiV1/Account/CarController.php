<?php

namespace App\Http\Controllers\ApiV1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiV1\Account\CarRequest;
use App\Http\Resources\ApiV1\CarResource;
use App\Models\Car;
use App\Services\CarService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CarController extends Controller
{
    public function __construct(
        private CarService $carService
    )
    {
    }

    /**
     * Возвращает список всех машин пользователя
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return CarResource::collection(Car::forUser()->get());
    }

    /**
     * Добавляет новое авто
     *
     * @param CarRequest $request
     * @return CarResource
     */
    public function store(CarRequest $request): CarResource
    {
        $data = $request->validated();

        return CarResource::make(
            $this->carService->store($data)
        );
    }

    /**
     * Обновляет данные авто
     *
     * @param string $id
     * @param CarRequest $request
     * @return CarResource
     */
    public function update(string $id, CarRequest $request): CarResource
    {
        $data = $request->validated();

        return CarResource::make(
            $this->carService->update($id, $data)
        );
    }

    /**
     * Удаляет авто
     *
     * @param string $id
     * @return void
     */
    public function destroy(string $id): void
    {
        $this->carService->destroy($id);
    }
}
