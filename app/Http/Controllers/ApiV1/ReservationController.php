<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiV1\ReservationStoreRequest;
use App\Http\Resources\ApiV1\ReservationResource;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    )
    {
    }

    /**
     * Бронирует поездку
     *
     * @param ReservationStoreRequest $request
     * @return ReservationResource
     */
    public function store(ReservationStoreRequest $request): ReservationResource
    {
        return ReservationResource::make(
            $this->reservationService->store($request->validated())
        );
    }
}
