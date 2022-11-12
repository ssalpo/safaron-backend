<?php

namespace App\Http\Resources\ApiV1;

use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'locations' => RouteLocationResource::collection($this->whenLoaded('routeLocations')),
            'go_time' => $this->go_time,
            'free_places' => $this->free_places,
            'fast_reservation' => $this->fast_reservation,
            'baggage_transportation' => $this->baggage_transportation,
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->status,
            'reservations' => ReservationResource::collection($this->whenLoaded('reservations')),
            'car' => CarResource::make($this->whenLoaded('car')),
            'driver' => UserResource::make($this->whenLoaded('driver'))->hide(['phone']),
            'reserved' => $this->whenLoaded(
                'reservationCounts',
                function () {
                    $data = $this->reservationCounts->first();

                    return [
                        'seats' => (int)($data->seats ?? 0),
                        'passengers' => (int)($data->passengers ?? 0)
                    ];
                }
            )
        ];
    }
}
