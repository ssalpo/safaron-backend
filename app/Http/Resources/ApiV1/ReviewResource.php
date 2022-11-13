<?php

namespace App\Http\Resources\ApiV1;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'user_id' => $this->user_id,
            'route_id' => $this->route_id,
            'rating' => $this->rating,
            'body' => $this->body,
            'reply' => $this->reply,
        ];
    }
}
