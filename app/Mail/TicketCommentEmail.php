<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Adapter\PDFLib;

class TicketCommentEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $ticketDetailId;
    public $ticketId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticketDetailId, $ticketId) {
        $this->ticketDetailId = $ticketDetailId;
        $this->ticketId = $ticketId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        //Ticket Data
//        $ticketData = \App\ticket::selectRaw('type.name as "tipo2", typeDetail.name as "typeDetail2", ticket.title, deta.description, CONCAT(users.first_name," ",users.last_name) as "user", menu.name as "menu2"')
//                                ->join('ticket_type_detail as typeDetail','typeDetail.id','=','ticket.ticket_type_detail_id')
//                                ->join('ticket_type as type','type.id','=','typeDetail.ticket_type_id')
//                                ->join('ticket_detail as deta','deta.ticket_id','=','ticket.id')
//                                ->join('users','users.id','=','ticket.assign_user_id')
//                                ->join('menu','menu.id','=','ticket.menu_id')
//                                ->where('ticket.id','=','41')
//                                ->get();
        $ticketData = \App\ticket_detail::selectRaw('type.name as "tipo2", typeDetail.name as "typeDetail2", ticket.title, ticket_detail.description, CONCAT(users.first_name," ",users.last_name) as "user", menu.name as "menu2"')
                                ->join('ticket','ticket.id','=','ticket_detail.ticket_id')
                                ->join('ticket_type_detail as typeDetail','typeDetail.id','=','ticket.ticket_type_detail_id')
                                ->join('ticket_type as type','type.id','=','typeDetail.ticket_type_id')
                                ->join('users','users.id','=','ticket.assign_user_id')
                                ->join('menu','menu.id','=','ticket.menu_id')
                                ->where('ticket_detail.id','=',$this->ticketDetailId)
                                ->get();
        
        return $this->view('emails.ticketComment')
                        ->from('demo@magnusmas.com', 'Info')
                        ->subject('Nuevo Comentario')
                        ->replyTo('demo@magnusmas.com', 'Info')
                        ->with([
                            'user' => $ticketData[0]->user,
                            'menu' => $ticketData[0]->menu2,
                            'tipo' => $ticketData[0]->tipo2,
                            'typeDetail' => $ticketData[0]->typeDetail2,
                            'title' => $ticketData[0]->title,
                            'description' => $ticketData[0]->description,
                            'ticketId' => $this->ticketId
                        ]);
    }

}
