<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\SupportTicketManager;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use SupportTicketManager;


    public function __construct()
    {
        parent::__construct();
        $this->user = seller();
        $this->column = 'seller_id';
        $this->userType = 'seller';
        $this->layout = 'seller';
        $this->redirectLink = 'seller.ticket.view';
    }

    public function index()
    {
        $pageTitle      = "All Support Tickets";
        $tickets        = SupportTicket::where('seller_id', seller()->id)
            ->orderBy('priority', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
        return view('seller.support.index', compact('tickets', 'pageTitle'));
    }

    public function viewTicket($ticket)
    {
        $pageTitle  = "View Ticket";
        $myTicket   = SupportTicket::where('ticket', $ticket)->where('seller_id', seller()->id)->firstOrFail();
        $messages   = SupportMessage::where('support_ticket_id', $myTicket->id)->orderBy('id', 'desc')->get();
        return view('seller.support.view', compact('myTicket', 'messages', 'pageTitle'));
    }

    public function openNewTicket()
    {
        $pageTitle  = "Open New Ticket";
        return view('seller.support.create', compact('pageTitle'));
    }

    public function reply(Request $request, $id)
    {
        $reply = $this->replyTicket($request, $id, 'seller');
        return redirect()->route('seller.ticket.view', $reply['ticket'])->withNotify($reply['message']);
    }
}
