<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
Use Redirect;
use Barryvdh\DomPDF\Facade as PDF;
use Mail;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use DataTables;
use Illuminate\Pagination\Paginator;
use Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Underscore\Parse;

use Illuminate\Http\Request;

class PaymentsButtonController extends Controller
{
    public function paymentsCreate(request $request) {
        try {
            $saleId = decrypt($request['sales']);
            
        } catch (DecryptException $ex) {
            return 'Solicite un nuevo enlace';
        }
        //Obtain Sale Data
        $sale = \App\sales::find($saleId);
        
        //Obtain Charge Data
        $chargeValidate = \App\Charge::where('sales_id',$sale->id)->get();
        if($chargeValidate->isEmpty()){
            $charge = new \App\Charge();
            $charge->sales_id = $sale->id;
            $charge->customers_id = $sale->customer_id;
            $charge->types_id = 1;
            $charge->motives_id = 1;
            $charge->status_id = 8;
            $charge->date = new \DateTime();
            $charge->value = $sale->total;
            $charge->save();
        }else{
            $charge = \App\Charge::find($chargeValidate[0]->id);
        }
        //Validate Charge Status
        if($charge->status_id == 9){
            return view('sales.paymentsResult',[
                'success' => 'false',
                'code' => '888',
                'message' => 'La poliza ya se encuentra pagada'
            ]);
        }
        
        //Obtain Charge Status
        $status = \App\status::find($sale->status_id);
        //Obtain Customer Data
        $customer = \App\customers::find($sale->customer_id);
        //Obtain Documents
        $documents = \App\document::all();
        
        //Customer Country, Province, City
        $cusCity = \App\city::find($customer->city_id);
        $cusCityList = true;
        $cusProvince = \App\province::find($cusCity->province_id);
        $cusProvinceList = true;
        $cusCountry = \App\country::find($cusProvince->country_id);
        $cusCountryList = true;
        
        $countries = DB::select('select * from countries');
        
        $pbc = \App\product_channel::find($sale->pbc_id);
        $product = \App\products::find($pbc->product_id);

        return view('sales.paymentsCreate', [
            'sale' => $sale,
            'charge' => $charge,
            'status' => $status,
            'customer' => $customer,
            'documents' => $documents,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusProvinceList' => $cusProvinceList,
            'cusCountryList' => $cusCountryList,
            'cusCityList' => $cusCityList,
            'countries' => $countries,
            'product' => $product
        ]);
    }

    public function paymentsStore(request $request) {
        return $request;
    }

    public function paymentsPay(request $request) {
        //Obtain Sale Data
        $sale = \App\sales::find($request['chargeId']);
        //Obtain Charge
        $chargeSearch = \App\Charge::where('sales_id',$sale->id)->get();
        $charge = \App\Charge::find($chargeSearch[0]->id);  
        //Obtain Charge Status
        $status = \App\status::find($sale->status_id);
        //Obtain Customer Data
        $customer = \App\customers::find($sale->customer_id);
        $address = substr($customer->address, 0, 100);
        
        $previousUrl = url()->previous();
        if (strpos($previousUrl, 'sales/payments/pay/result') !== false) {
            return redirect('sales/payments/create?document='. encrypt($customer->document).'&sales='. encrypt($request['chargeId']));
        }
        
        if(session('paymentResult')){
            return redirect('sales/payments/create?document='. encrypt($customer->document).'&sales='. encrypt($request['chargeId']));
        }
//        $paymentPrepareCheckout = paymentPrepareCheckout();
        $items = array();
        $product = array( "name" => "test",
                "description" => "test",
                "price" => "$sale->total",
                "quantity" => "1");
        array_push($items,$product);
//        $paymentPrepareCheckout = paymentPrepareCheckoutProduction($items, $sale->total, $sale->tax, $sale->subtotal_12, $sale->subtotal_0, $customer->email, $customer->first_name, $customer->second_name, $customer->last_name, $customer->document, $sale->id, '192.168.100.22', 'finger', $customer->mobile_phone, $address, 'EC', $address, 'EC');
        $paymentPrepareCheckout = paymentPrepareCheckoutProduction($items, '5.00', '0.54', '4.46', '0.00', $customer->email, $customer->first_name, $customer->second_name, $customer->last_name, $customer->document, $sale->id, '192.168.100.22', 'finger', $customer->mobile_phone, $address, 'EC', $address, 'EC');
//        $paymentPrepareCheckout = annulmentDataFast('RF', '8ac7a4a0712bc2c201712c3a0aaa327e', '1.12', 'USD', '043464', '001877', '1000000505_PD100406', '0081003007010391000401200000000001205100817913101052012000000000000053012000000000100', '4540633170010000', '04', '22');
//        dd($paymentPrepareCheckout);
        return view('sales.paymentsCreate2', [
            'checkoutId' => $paymentPrepareCheckout->id,
            'sale' => $sale,
            'charge' => $charge,
            'status' => $status,
            'customer' => $customer
        ]);
    }
    
    public function paymentsPayResult(request $request){
        \Session::flash('paymentResult', 'paymentResult');
        
        set_time_limit(300);
        $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

        if($pageWasRefreshed ) {
            return view('sales.paymentsResult',[
                'success' => 'false',
                'code' => '999',
                'message' => 'Debe intentar el pago nuevamente'
            ]);
        } 
        $response = processPaymentDataFast($request['id']);
//        dd($response);
        //Variables
        $merchantTransactionId = (explode("_",$response['merchantTransactionId'])); 
        if(isset($response['resultDetails']['ReferenceNbr'])){
            $ReferenceNbr = (explode("_",$response['resultDetails']['ReferenceNbr'])); 
            $lot = $ReferenceNbr[0];
            $reference_number = $ReferenceNbr[1];
        }else{
            $lot = 'NULL';
            $reference_number = 'NULL';
        }
        if(isset($response['resultDetails']['OrderId'])){
            $orderId = $response['resultDetails']['OrderId'];
        }else{
            $orderId = 'NULL';
        }
        if(isset($response['resultDetails']['AuthCode'])){ $authCode = $response['resultDetails']['AuthCode']; }else{ $authCode = "null"; }
        
        //Update DataFast Log
        $log = new \App\datafast_log();
        $log->id_cart = $merchantTransactionId[1];
        $log->order = $orderId;
        $log->order_date = new \DateTime();
        $log->id_transaction = $response['merchantTransactionId'];
        $log->lot = $lot;
        $log->reference = $reference_number;
        $log->auth_code = $authCode;
        $log->code = $response['result']['code'];
        $log->response = $response['result']['description'];
        $log->id_response = $response['id'];
        $log->custom_value = $response['customParameters']['1000000505_PD100406'];
        $log->type = 'PAYMENT';
        $log->save();
        
        //Vaidate Response
        if($response['result']['code'] == '000.100.112'){ $success = 'true'; }else{ $success = 'false'; }
        
        //Send Payment Accepted Email
        $emailData = \App\sales::selectRaw('usr.email, cus.document')
                                ->join('customers as cus','cus.id','=','sales.customer_id')
                                ->join('users as usr','usr.id','=','sales.user_id')
                                ->where('sales.id','=',$merchantTransactionId[1])
                                ->get();
        //SE DEBE COMENTAR AL ENTRAR A PRODUCCION
        //$success = 'true';
        if($success == 'true'){
            \App\Jobs\paymentAceptedUserEmailJobs::dispatch($merchantTransactionId[1],$emailData[0]->email,$emailData[0]->document);
            
            //Update Sale
            $sale = \App\sales::find($merchantTransactionId[1]);
            $sale->status_id = 9;
            $sale->save();
            
            //Validate Installmets  
            if(isset($response['recurring']['numberOfInstallments'])){
                $installments = $response['recurring']['numberOfInstallments'];
            }else{
                $installments = null;
            }
            
            //Validate givenName and middleName
            if(isset($response['middleName'])){
                $firstName = $response['customer']['givenName'].' '.$response['customer']['middleName'];
            }else{
                $firstName = $response['customer']['givenName'];
            }
            
            //Update Payment
            $payment = new \App\payments();
            $payment->payment_type_id = 2;
            $payment->date = new \DateTime();
            $payment->number = null;
            $payment->first_name = $firstName;
            $payment->last_name = $response['customer']['surname'];
            $payment->email = $response['customer']['email'];
            $payment->document = $response['customer']['identificationDocId'];
            $payment->cvc =  null;
            $payment->month = null;
            $payment->year = null;
            $payment->value = $response['amount'];
            $payment->user_id = null;
            $payment->auth_code = $log->auth_code;
            $payment->reference = $log->reference;
            $payment->transaction_id = $log->id_transaction;
            $payment->custom_parameters = '1000000505_PD100406';
            $payment->custom_value = $log->custom_value;
            $payment->shopper_interes = '0';
            $payment->shopper_installments = $installments;
            $payment->id_response = $log->id_response; 
            $payment->save();
            
            //Update Charge
            $chargeSearch = \App\Charge::where('sales_id','=',$sale->id)->get();
            $charge = \App\Charge::find($chargeSearch[0]->id);
            $charge->payments_id = $payment->id;
            $charge->status_id = 9;
            $charge->date = new \DateTime();
            $charge->save();
            
            //EMISION
            \App\Jobs\emisionJob::dispatch($sale->id);
        }else{
            \App\Jobs\paymentRejectedUserEmailJobs::dispatch($merchantTransactionId[1],$emailData[0]->email,$emailData[0]->document);
        }
        
        //Validate Response Code
        $responseCode = \App\datafast_response::where('code','=',$log->code)->get();
        if($responseCode->isEmpty()){
            return view('sales.paymentsResult',[
                'success' => $success,
                'code' => '999',
                'message' => 'Error de DATAFAST'
            ]);
        }else{
            return view('sales.paymentsResult',[
                'success' => $success,
                'code' => $responseCode[0]->code,
                'message' => $responseCode[0]->name
            ]);
        }
    }
}
