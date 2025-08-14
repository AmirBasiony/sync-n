<?php

namespace App\Http\Controllers;

use App\Enums\TicketMessageTypeEnum;
use App\Http\Requests\Ticket\StoreMessageRequest;
use App\Http\Resources\TicketDetailResource;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\TicketService;

class TicketController extends Controller
{

    public function __construct(protected TicketService $ticketService)
    {

    }

    public function index()
    {
        $query = Ticket::with('user')->orderBy('created_at', 'desc');

        if ($search = request()->input('search')) {
            $query->where('ticket_number', 'like', '%' . $search . '%');
        }

        if ($createdAt = request()->input('created_at')) {
            $query->whereDate('created_at', $createdAt);
        }

        $result = $query->paginate(10)->withQueryString();

        $tickets = TicketResource::collection($result);

        return inertia('tickets/Index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket = TicketDetailResource::make($ticket->load(['user', 'messages']));

        return inertia('tickets/Show', compact('ticket'));
    }

    public function closeTicket(Ticket $ticket)
    {
        $ticket = $this->ticketService->changeTicketStatus($ticket, request());

        return redirect()->route('tickets.show', $ticket);
    }

    public function sendReply(StoreMessageRequest $request, Ticket $ticket)
    {
        $message = $this->ticketService->sendMessage($request, $ticket, TicketMessageTypeEnum::AGENT);

        return redirect()->route('tickets.show', $ticket);

    }
}
