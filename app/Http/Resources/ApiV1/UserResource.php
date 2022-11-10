<?php

namespace App\Http\Resources\ApiV1;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Traits\DynamicHideResource;

class UserResource extends JsonResource
{
    use DynamicHideResource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'birthday' => $this->birthday?->format('d-m-Y'),
            'gender' => $this->gender,
        ]);
    }
}
