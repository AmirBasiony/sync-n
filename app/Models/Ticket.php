<?php

namespace App\Models;


use App\Enums\TicketStatusEnum;
use App\Enums\TicketTypeEnum;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\HasMany;

#[ObservedBy(TicketObserver::class)]
class Ticket extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'tickets';

    protected $guarded = [];

    protected $dates
        = [
            'last_reply_at',
            'closed_at',
            'created_at',
            'updated_at',
        ];

    protected $casts
        = [
            'type'          => TicketTypeEnum::class,
            'status'        => TicketStatusEnum::class,
            'last_reply_at' => 'datetime:Y-m-d H:i:s',
            'closed_at'     => 'datetime:Y-m-d H:i:s',
            'created_at'    => 'datetime:Y-m-d H:i:s',
            'updated_at'    => 'datetime:Y-m-d H:i:s',
        ];

    protected $appends = [
        'closed' => 'closed',
    ];

    /**
     * ==============
     * CUSTOM METHODS
     * ==============
     */
    public function hasMessages(): bool
    {
        return $this->messages()->count() > 0;
    }

    public function getLastMessageTime()
    {
        return optional($this->messages()->latest()->first())->created_at?->diffForHumans();
    }

    /**
     * ==============
     * ATTRIBUTES
     * ==============
     */

    public function closed(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === TicketStatusEnum::CLOSED,
        );
    }

    /**
     * ==============
     * RELATIONSHIPS
     * ==============
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }
}
