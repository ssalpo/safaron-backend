<?php

namespace App\Http\Resources\ApiV1;

use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
        ];
    }
}
