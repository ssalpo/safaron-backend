<?php

namespace App\Http\Resources\ApiV1;

use App\Models\Reservation;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $hiddenUserFields = [];

        if ($this->status !== Reservation::STATUS_CONFIRMED) {
            $hiddenUserFields[] = 'phone';
        }

        return [
            'id' => $this->id,
            'route' => RouteResource::make($this->whenLoaded('route')),
            'user' => UserResource::make($this->whenLoaded('user'))->hide($hiddenUserFields),
            'number_of_seats' => $this->number_of_seats,
            'status' => $this->status
        ];
    }
}
