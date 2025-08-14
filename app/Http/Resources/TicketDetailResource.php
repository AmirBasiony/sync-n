<?php

namespace App\Http\Resources;

use App\Enums\TicketStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $ticket_number
 * @property string $subject
 * @property string $message
 * @property TicketStatusEnum $status
 * @property string $priority
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_reply_at
 * @property string $closed_at
 * @property bool $closed
 * @property UserResource $user
 * @property TicketMessageResource[] $replies
 */
class TicketDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'ticket_number'   => $this->ticket_number,
            'subject'         => $this->subject,
            'initial_message' => $this->message,
            'type'            => $this->type->label(),
            'status_badge'    => $this->status->badge(),
            'status'          => $this->status->label(),
            'priority'        => $this->priority,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'last_reply_at'   => $this->last_reply_at,
            'closed_at'       => $this->closed_at,
            'closed'          => $this->closed,
            'user'            => $this->whenLoaded('user'),
            'replies'         => TicketMessageResource::collection($this->whenLoaded('messages')),
        ];
    }
}
