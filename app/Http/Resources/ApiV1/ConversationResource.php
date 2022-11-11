<?php

namespace App\Http\Resources\ApiV1;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
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
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'sender' => UserResource::make($this->whenLoaded('sender'))->hide(['phone']),
            'receiver' => UserResource::make($this->whenLoaded('receiver'))->hide(['phone']),
            'read' => (bool)$this->read,
            'read_at' => $this->read_at,
            'message' => $this->message
        ];
    }
}
