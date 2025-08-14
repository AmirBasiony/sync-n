<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'message'     => $this->message,
            'user'        => $this->whenLoaded('user', UserResource::make($this->user)),
            'ticket'      => $this->whenLoaded('ticket'),
            'type'        => $this->type,
            'created_at'  => $this->created_at->diffForHumans(),
        ];
    }
}
