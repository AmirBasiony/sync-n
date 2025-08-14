<?php

namespace App\Services;

use App\Enums\TicketMessageTypeEnum;
use App\Enums\TicketStatusEnum;
use App\Http\Requests\Ticket\StoreMessageRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketService
{
    public function sendMessage(StoreMessageRequest $request, Ticket $ticket, TicketMessageTypeEnum $type)
    {
        if (!$ticket->hasMessages() || $ticket->status === TicketStatusEnum::REOPENED) {
            $ticket->status = TicketStatusEnum::OPEN;
            $ticket->save();
        }

        return $ticket->messages()->create([
            'user_id' => auth()->id(),
            'type'    => $type,
            'message' => $request->message,
        ]);
    }

    public function changeTicketStatus(Ticket $ticket, Request $request): Ticket
    {
        $ticket->status = $request->status;
        if ($request->status === TicketStatusEnum::CLOSED) {
            $ticket->closed_at = now();

        } else if ($request->status === TicketStatusEnum::REOPENED) {
            $ticket->closed_at = null;
        }

        $ticket->save();

        return $ticket->refresh();
    }

    public function getTicketMessages(Ticket $ticket)
    {
        return $ticket->messages()->latest()->get();
    }

    public function getUserTickets(Ticket $ticket)
    {
        return $ticket->orderBy('created_at', 'desc')->get();
    }
}
