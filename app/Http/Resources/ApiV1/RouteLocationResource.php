<?php

namespace App\Http\Resources\ApiV1;

use Illuminate\Http\Resources\Json\JsonResource;

class RouteLocationResource extends JsonResource
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
            'from_place_id' => $this->from_place_id,
            'to_place_id' => $this->to_place_id,
            'from_place' => new PlaceResource($this->whenLoaded('fromPlace')),
            'to_place' => new PlaceResource($this->whenLoaded('toPlace')),
        ];
    }
}
