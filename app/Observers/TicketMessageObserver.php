<?php

namespace App\Observers;

use App\Models\TicketMessage;
use App\Notifications\SendTicketMessageNotification;

class TicketMessageObserver
{
    /**
     * Handle the TicketMessage "created" event.
     */
    public function created(TicketMessage $ticketMessage): void
    {
        // send notification to the ticket owner
        $ticketMessage->ticket->user->notify(new SendTicketMessageNotification($ticketMessage));
    }

    /**
     * Handle the TicketMessage "updated" event.
     */
    public function updated(TicketMessage $ticketMessage): void
    {
        //
    }

    /**
     * Handle the TicketMessage "deleted" event.
     */
    public function deleted(TicketMessage $ticketMessage): void
    {
        //
    }

    /**
     * Handle the TicketMessage "restored" event.
     */
    public function restored(TicketMessage $ticketMessage): void
    {
        //
    }

    /**
     * Handle the TicketMessage "force deleted" event.
     */
    public function forceDeleted(TicketMessage $ticketMessage): void
    {
        //
    }
}
