<?php

namespace App\Http\Resources;

use App\Enums\TicketStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $ticket_number
 * @property string $subject
 * @property TicketStatusEnum $status
 * @property string $priority
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_reply_at
 * @property string $closed_at
 */
class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'ticket_number' => $this->ticket_number,
            'subject'       => $this->subject,
            'status_badge'  => $this->status->badge(),
            'status'        => $this->status->label(),
            'created_at'    => $this->created_at->diffForHumans(),
            'last_reply_at' => $this->last_reply_at?->diffForHumans(),
            'closed_at'     => $this->closed_at?->diffForHumans(),
            'user'          => $this->whenLoaded('user'),
        ];
    }
}
