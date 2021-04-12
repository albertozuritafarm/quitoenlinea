<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class financingController extends Controller {

    public function index(request $request) {
        //Obtain Payments
        $accountsQuery = 'select
                         cr.id as "id",
                         concat(cus.first_name," ",cus.last_name) as "customer",
                         cus.document as "document_number",
                         doc.name as "document",
                         sta.name as "status",
                         cr.updated_at as "date",
                         ban.name as "bank",
                         cr.number as "number",
                         cr.amount as "amount",
                         cr.total_amount as "total_amount",
                         DATE_FORMAT(cr.date,"%d-%m-%Y") as "date",
                         cr.status_id as "status"
                         from credit_requests cr
                         join customers cus on cus.id = cr.customer_id
                         join status sta on sta.id = cr.status_id
                         join banks ban on ban.id = cr.bank_id
                         join documents doc on doc.id = cus.document_id where cr.status_id not in (11)';

        //VALIDATE FILTERS
        if ($request['crId'] != null) {//DOCUMENT
            $accountsQuery .= ' and cr.id = "' . $request['crId'] . '"';
        }
        if ($request['beginDate'] != null) {//DATE   
            $accountsQuery .= ' and DATE_FORMAT(cr.date,"%Y-%m-%d") BETWEEN "' . $request['beginDate'] . '" and "' . $request['endDate'] . '"';
        }
        if ($request['document'] != null) {//SALID
            $accountsQuery .= ' and cus.document = "' . $request['document'] . '"';
        }
        if ($request['bank'] != '') {//PAYMENT TYPE
            $accountsQuery .= ' and ban.id = "' . $request['bank'] . '"';
        }
        $accountsQuery .= ' ORDER BY id DESC';
        $accounts = DB::select($accountsQuery);

        //Status
        $status = \App\status::find([18, 19]);
        
        //Banks
        $banks = \App\bank::all();

        //Filter Data Array
        $data = array('crId' => $request['crId'],
            'beginDate' => $request['beginDate'],
            'endDate' => $request['endDate'],
            'saleId' => $request['saleId'],
            'document' => $request['document'],
            'status' => $request['status'],
            'bank' => $request['bank'],
            'charge_type' => $request['charge_type']);

        return view('financing.index', [
            'accounts' => $accounts,
            'data' => $data,
            'status' => $status,
            'banks' => $banks
        ]);
    }

    public function create() {
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "' . $channel[0]->id . '" and pro.product_type = "INDIVIDUAL"');
        $documents = DB::select('select * from documents where id in (1,2,3)');
        $countries = DB::select('select * from countries');
        $genders = DB::select('select * from gender');
        $nacionalities = DB::select('select * from nacionality');
        $civilStates = DB::select('select * from civil_state');
        $correspondences = DB::select('select * from correspondence');
        $relationships = DB::select('select * from relationships');
        $banks = DB::select('select * from banks');

        return view('financing.create', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'genders' => $genders,
            'nacionalities' => $nacionalities,
            'civilStates' => $civilStates,
            'correspondences' => $correspondences,
            'relationships' => $relationships,
            'banks' => $banks
        ]);
    }

    public function validateCredit(request $request) {
        $amount = $request['data']['amount'];
        $bankId = $request['data']['bank'];
        $documentNumber = $request['data']['documentNumber'];
        $query = ' SELECT     cus.document as "cusDocument", 
                               doc.name as "docName",
                               concat(cus.first_name, " ", cus.first_name) as "customer",
                               gen.name as "genName",
                               cus.address as "cusAddress",
                               cus.email as "cusEmail",
                               cus.phone as "cusPhone",
                               cus.mobile_phone as "cusMobilePhone",
                               ban.name as "banName",
                               cre.amount as "creAmount",
                       (SELECT SUM(CRE.amount) FROM credit_requests CRE WHERE CRE.customer_id = cus.id AND CRE.bank_id = "' . $bankId . '") as "consumed"
                    FROM       customers cus
                    JOIN 	   documents doc ON doc.id = cus.document_id
                    JOIN       banks ban ON ban.id = "' . $bankId . '"
                    LEFT JOIN  credits cre ON cre.id_customer = cus.id AND cre.id_bank = "' . $bankId . '"
                    JOIN       gender gen on gen.id = cus.gender_id
                    WHERE      Upper(Trim(cus.document)) = "' . $documentNumber . '" LIMIT 1';
        $data = DB::select($query);
        if (count($data) == 0) {
            $code = 0;
            $returnData = "";
        } else {
            $creditValue = $data[0]->creAmount;
            $consumed = $data[0]->consumed;
            $bank = $data[0]->banName;
            $available = $creditValue - $consumed;
            $docName = $data[0]->docName;
            $customer = $data[0]->customer;
            $genName = $data[0]->genName;
            $cusAddress = $data[0]->cusAddress;
            $cusEmail = $data[0]->cusEmail;
            $cusPhone = $data[0]->cusPhone;
            $cusMobilePhone = $data[0]->cusMobilePhone;
            $returnData = '';
            if ($creditValue == null) {
                $code = 1;
                $returnData .= $this->getCabeceraDIVComprobacionCliente("600", "CRÉDITO INEXISTENTE", "error");
                $returnData .= $this->getLineaFicha("Solicitado:", "$" . $amount);
                $returnData .= $this->getLineaFicha("Disponible con '$bank':", "Sin Créditos otorgados.");
            } else if (($creditValue - $consumed) < $amount) {
                $code = 2;
                $returnData = $this->getCabeceraDIVComprobacionCliente("600", "CRÉDITO INSUFICIENTE", "error");
                $returnData .= $this->getLineaFicha("Solicitado:", "$" . $amount);
                $returnData .= $this->getLineaFicha("Disponible con '$bank':", "$" . $available);
            } else {
                $code = 3;
                $returnData = $this->getCabeceraDIVComprobacionCliente("060", "CRÉDITO DISPONIBLE", "ok");
                $returnData .= $this->getLineaFicha("Solicitado:", "$" . $amount);
                $returnData .= $this->getLineaFicha("Disponible con '$bank':", "$" . $available);
            }
            $returnData .= '<hr style="margin:3px 15px;"/>';
            $returnData .= '<div class="row ficha"><div class="col-xs-12" align="left" style="font-size:14px; color:#888"><b>FICHA DEL CLIENTE</b></div></div>';
            $returnData .= $this->getLineaFicha("$docName", $documentNumber);
            $returnData .= $this->getLineaFicha("Nombre(s) y Apellidos:", $customer);
            $returnData .= $this->getLineaFicha("Género:", $genName);
            $returnData .= $this->getLineaFicha("Dirección:", $cusAddress);
            $returnData .= $this->getLineaFicha("Correo electrónico:", $cusEmail);
            $returnData .= $this->getLineaFicha("Teléfono(s):", $cusPhone . ' - ' . $cusMobilePhone);
        }
        $returnArray = array();
        $returnArray = [
            "data" => $returnData,
            "code" => $code
        ];
        return $returnArray;
    }

    private function getCabeceraDIVComprobacionCliente($color, $texto, $imagen) {
        return '<div class="row" style="margin:6px auto"><div class="col-xs-12" align="center" style="font-size:18px; color:#' . $color . ';">
			  <img src="../images/iconos/' . $imagen . '.png" width="20px" height="20px" /><b style="padding-left:10px;">' . $texto . '</b>
			  </div></div><hr style="margin:3px 15px;"/>';
    }

    private function getLineaFicha($left = "", $right = "") {
        return '<div class="row ficha"><div class="col-xs-12" align="left"><b>' . $left . ' </b>' . $right . '</div></div>';
    }

    public function sendCode(request $request) {
        //Variables
        $amount = $request['data']['amount'];
        $bank = $request['data']['bank'];
        $number = $request['data']['number'];
        $documentNumber = $request['data']['documentNumber'];
        $financingCH = $request['data']['financingCH'];
        $radioPlan = $request['data']['RadioPlan'];

        //Obtain Customer
        $customer = \App\customers::where('document', '=', $documentNumber)->get();
        
        //Now
        $now = new \DateTime();
        
        //Random Code
        $randomCode = rand(100000, 999999);
        
        //Calculate Total Amount
        switch($radioPlan){
                case "1":
                    $interestRate = 2;
                    break;
                case "2":
                    $interestRate = 4;
                    break;
                case "3":
                    $interestRate = 6;
                    break;
                case "4":
                    $interestRate = 8;
                    break;
                default:
                    $interestRate = 10;
            }
        $interest = (($amount * $interestRate) / 100);
        $total = $amount + $interest;
        
        //Store Credit Request
        $cr = new \App\credit_requests();
        $cr->customer_id = $customer[0]->id;
        $cr->bank_id = $bank;
        $cr->amount = $amount;
        $cr->financing = $financingCH;
        $cr->number = $number;
        $cr->date = $now;
        $cr->product_id = $radioPlan;
        $cr->status_id = 10;
        $cr->code = $randomCode;
        $cr->date_code_send = $now;
        $cr->total_amount = $total;
        $cr->save();
        
        //Mail Variables
        $name = $customer[0]->first_name. ' '.$customer[0]->last_name;
        $bankName = \App\bank::find($bank);

        //Send Mail
        $job = (new \App\Jobs\financingCodeEmailJobs($name, $amount, $bankName->name,$randomCode, $customer[0]->email));
        dispatch($job);
        
        return $cr->id;
    }
    
    public function validateCode(request $request){
        $crId = $request['data']['crId'];
        $code = $request['data']['code'];
        $returnArray = array();
        //Obtain Credit Request Data
        $cr = \App\credit_requests::find($crId);
        
        //Validate Code 
        if($cr->code == $code){
            $cr->status_id = 1;
            $cr->save();
            \Session::flash('successFinancing', 'La solicitud fue generada correctamente.');
            $returnArray = [
                'success' => 'true'
            ];
        }else{
            $returnArray = [
                'success' => 'false',
                'msg' => 'El codigo ingresado es incorrecto'
            ];
        }
        return $returnArray;
    }
    
    public function deleteCreditRequest(request $request){
        $cr = \App\credit_requests::find($request['data']['crId']);
        $cr->status_id = 11;
        $cr->save();
        \Session::flash('successFinancing', 'La solicitud fue eliminada correctamente.');
    }
    
    public function resendCode(request $request){
        //Random Code
        $randomCode = rand(100000, 999999);
        
        //Now
        $now = new \DateTime();
        
        //Credit Request
        $cr = \App\credit_requests::find($request['data']['crId']);
        $cr->code = $randomCode;
        $cr->date_code_send = $now;
        $cr->save();
        
        $amount = $cr->amount;
        
        //Customer
        $customer = \App\customers::find($cr->customer_id);
        $name = $customer->first_name. ' '.$customer->last_name;
        
        //Bank
        $bank = \App\bank::find($cr->bank_id);
        
        //Send Mail
        $job = (new \App\Jobs\financingCodeEmailJobs($name, $amount, $bank->name,$randomCode, $customer->email));
        dispatch($job);
    }
    
    public function delete(request $request){
        foreach($request['data']['cr'] as $crId){
            $cr = \App\credit_requests::find($crId);
            $cr->status_id = 11;
            $cr->save();
        }
        \Session::flash('successFinancing', 'La(s) solicitud(es) fue(ron) eliminada(s) correctamente.');
    }
    
    public function productTable(request $request){
        $weeksArray = array(1,2,3,4);
        $amount = $request['data']['amount'];
        $returnData = '';
        $returnData .= '<table class="table table-responsive table-striped table-no-bordered table-hover MiTabla">
                    <thead class="MiTablaCabecera">
                        <th align="left"></th>
                        <th>SEMANAS</th>
                        <th>VALOR FINANCIADO</th>
                        <th>INTERÉS (%)</th>
                        <th>COMISIÓN (5%)</th>
                        <th>TOTAL</th>
                    </thead>
                    <tbody>';
        foreach($weeksArray as $week){
            if($week == 1){$checked = 'checked="checked"'; $TRseleccionado = 'TRSeleccionado';}else{$checked = '';$TRseleccionado = '';}
            $comission = (($amount * 5)/100);
            switch($week){
                case "1":
                    $interestRate = 2;
                    break;
                case "2":
                    $interestRate = 4;
                    break;
                case "3":
                    $interestRate = 6;
                    break;
                case "4":
                    $interestRate = 8;
                    break;
                default:
                    $interestRate = 10;
            }
            $interest = (($amount * $interestRate) / 100);
            $total = $amount + $interest;
            $returnData .= '<tr role="row" class="even '.$TRseleccionado.'" style="cursor:pointer" align="right">
                            <td align="left"><input type="radio" id="RadioPlan_'.$week.'" name="RadioPlan" value="'.$week.'" style="cursor:pointer" '.$checked.'></td>
                            <td align="center">'.$week.'</td>
                            <td align="center">$'.$amount.'</td>
                            <td align="center">$'.$interest.' ('.$interestRate.'%)</td>
                            <td align="center">$'.$comission.'</td>
                            <td align="center">$'.$total.'</td>
                        </tr>';
        }
        $returnData .= '</tbody>
                </table>';
        return $returnData;
    }
}
