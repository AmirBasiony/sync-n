<?php

namespace App\Observers;

use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use App\Notifications\TicketClosedNotification;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        // send notification if ticket status changed to closed
        if ($ticket->isDirty('status') && $ticket->status === TicketStatusEnum::CLOSED) {
            $ticket->user->notify(new TicketClosedNotification($ticket));
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
