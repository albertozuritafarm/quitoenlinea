<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTableNoOrdering" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr style="background-color: #44444496; color: white;">
                    <th align="center">Ticket</th>
                    <th align="center">Estado</th>
                    <th align="center">Modulo</th>
                    <th align="center">Tipo Ticket</th>
                    <th align="center">Categoria</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="center">{{$ticket->id}}</td>
                    <td align="center">{{$ticketStatus->name}}</td>
                    <td align="center">{{$menu->name}}</td>
                    <td align="center">{{$ticketType->name}}</td>
                    <td align="center">{{$ticketTypeDetail->name}}</td>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-12 borderTable" style="margin-top:10px;padding: 10px;">
         @foreach($ticketDetail as $deta)
         <div class="col-md-12 border" style="margin-top: 10px;">
             <div class="col-md-4">
                 <h5 style="font-weight: 700;">{{$deta->user}}@if($deta->type_ticket_access == 1)&nbsp;<span class="label label-primary">Soporte</span> @else <span class="label label-success">Usuario</span> @endif</h5>
                 <h6 style="font-weight: 100;font-size: 11px;">{{$deta->date}}</h6>
             </div>
             <div class="col-md-8">
                 <h6 style="font-weight: 400;">{!! nl2br(e($deta->description)) !!}</h6>
                 @if($deta->picture1 != null)
                 <hr>
                 <h6><a href="{{asset('')}}/ticket/picture/1/{{$deta->id}}" target="_blank">{{$deta->picture2}}</a></h6>
                 @endif
             </div>
         </div>
         @endforeach
</div>