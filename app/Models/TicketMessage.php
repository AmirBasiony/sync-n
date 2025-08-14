<?php

namespace App\Models;

use App\Enums\TicketMessageTypeEnum;
use App\Observers\TicketMessageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

#[ObservedBy(TicketMessageObserver::class)]
class TicketMessage extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'ticket_messages';

    protected $guarded = [];

    protected $dates
        = [
            'created_at',
            'updated_at',
        ];
    protected $cast
        = [
            'type'       => TicketMessageTypeEnum::class,
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

    /**
     * =================
     * Relationships
     * =================
     */

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
