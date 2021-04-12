<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Pagination\Paginator;
use Validator;

class InsuranceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }
    
    public function index(request $request) {
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('60', \Auth::user()->role_id);
        if (!$viewPermit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de ventas Individual.');
            return view('home');
        }

        //Obtain Edit Permission
        $edit = checkExtraPermits('51', \Auth::user()->role_id);

        //Obtain Create Permission
        $create = checkExtraPermits('53', \Auth::user()->role_id);

        //Obtain Cancel Permission
        $cancel = checkExtraPermits('52', \Auth::user()->role_id);

        //Store Form Variables in Session
        if ($request->isMethod('post')) {
            session(['insuranceItems' => $request->items]);
            session(['insuranceTransId' => $request->transId]);
            session(['insuranceCusId' => $request->cusId]);
            session(['insuranceFirstName' => $request->first_name]);
            session(['insuranceLastName' => $request->last_name]);
            session(['insuranceStatusId' => $request->status]);
            $currentPage = 1;
            session(['insurancePage' => 1]);
        } else {
            $currentPage = session('insurancePage');
        }

        $status = \App\status::find([1,4,6]);

        //Pagination Items
        if (session('insuranceItems') == null) { $items = 10; } else { $items = session('insuranceItems'); }

        //Form Variables
        $transId = session('insuranceTransId');
        $cusId = session('insuranceCusId');
        $firstName = session('insuranceFirstNameId');
        $lastName = session('insuranceLastNameId');
        $statusId = session('insuranceStatusId');

        // Make sure that you call the static method currentPageResolver()
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        
        //Insurances
        $insurances = insurance($transId, $cusId, $firstName, $lastName, $statusId, $items);
        

        return view('insurance.index', [
            'insurances' => $insurances,
            'items' => $items,
            'status' => $status,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create
        ]);
    }
    
    function fetch_data(Request $request) {
        if ($request->ajax()) {
            //Page
            session(['insurancePage' => $request->page]);

            //Validate if User has view Permit

            //Obtain Edit Permission
            $edit = checkExtraPermits('51', \Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('53', \Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('52', \Auth::user()->role_id);

            $status = \App\status::find([1,4]);

            //Pagination Items
            if (session('insuranceItems') == null) { $items = 10; } else { $items = session('insuranceItems'); }

            //Form Variables
            $transId = session('insuranceTransId');
            $cusId = session('insuranceCusId');
            $firstName = session('insuranceFirstNameId');
            $lastName = session('insuranceLastNameId');
            $statusId = session('insuranceStatusId');

            //Insurances
            $insurances = insurance($transId, $cusId, $firstName, $lastName, $statusId, $items);


            return view('pagination.insurance', [
                'insurances' => $insurances,
                'items' => $items,
                'status' => $status,
                'edit' => $edit,
                'cancel' => $cancel,
                'create' => $create
            ]);
        }
    }
    
    public function create(){
         //Validate Create Permission
        $edit = checkExtraPermits('19', \Auth::user()->role_id);
        if (!$edit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear ventas Individuales.');
            return view('home');
        }

        $customer = new \App\customers();

        //Customer Country, Province, City
        $cusCity = new \App\city();
        $cusCityList = false;
        $cusProvince = new \App\province();
        $cusProvinceList = false;
        $cusCountry = new \App\country();
        $cusCountryList = false;

        $disabled = null;
        $vehiBodyTable = '';

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        $documents = DB::select('select * from documents where id in (1,3)');
        $countries = DB::select('select * from countries');

        $sale_movement = 1;
        
        //Product Data
        $products = \App\productsInsurance::all();
        $productsTable = '';
        foreach($products as $pro){
            $productsCoverage = \App\productsInsuranceCoverage::where('product_insurance_id','=',$pro->id)->get();
            $coverage = '';
            $amount = '';
            foreach($productsCoverage as $cov){
                $coverage .= '<li>'.$cov->name.'</li>';
                $amount .= '<li style="list-style-type:none;">$'.$cov->value.'</li>';
            }
            $productsTable .= '<tr>
                                <td style="text-align:left;"><input type="radio" name="proId" class="checkProductId" checked value="'.$pro->id.'"> '.$pro->name.'</td>
                                <td style="text-align:left;padding-left:25px !important;">'.$coverage.'</td>
                                <td>'.$amount.'</td>
                                <td>'.$pro->value_month.'</td>
                                <td>'.$pro->value_year.'</td>
                              </tr>';
        }
        
        return view('insurance.create', [
            'documents' => $documents,
            'countries' => $countries,
            'customer' => $customer,
            'disabled' => $disabled,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusCityList' => $cusCityList,
            'cusProvinceList' => $cusProvinceList,
            'vehiBodyTable' => $vehiBodyTable,
            'sale_movement' => $sale_movement,
            'sale_id' => null,
            'products_table' => $productsTable
        ]);
    }
    
    public function store(request $request){
        //Update or Insert Customer
        $customerSql = 'select * from customers where document = "' . $request['customerData']['document'] . '"';
        $customer = DB::select($customerSql);

        //Validate Customer Save or Update
        if ($customer) {
            $customerUpdate = \App\customers::find($customer[0]->id);
            $customerUpdate->address = $request['customerData']['address'];
            $customerUpdate->city_id = $request['customerData']['city'];
            $customerUpdate->phone = $request['customerData']['phone'];
            $customerUpdate->mobile_phone = $request['customerData']['mobilePhone'];
            $customerUpdate->email = $request['customerData']['email'];
            $customerId = $customer[0]->id;
            $customerEmail = $customerUpdate->email;
        } else {
            $customerNew = new \App\customers();
            $customerNew->first_name = $request['customerData']['firstName'];
            $customerNew->second_name = $request['customerData']['secondName'];
            $customerNew->last_name = $request['customerData']['lastName'];
            $customerNew->second_last_name = $request['customerData']['secondLastName'];
            $customerNew->document = $request['customerData']['document'];
            $customerNew->document_id = $request['customerData']['documentId'];
            $customerNew->address = $request['customerData']['address'];
            $customerNew->city_id = $request['customerData']['city'];
            $customerNew->phone = $request['customerData']['phone'];
            $customerNew->mobile_phone = $request['customerData']['mobilePhone'];
            $customerNew->email = $request['customerData']['email'];
            $customerNew->status_id = 1;
            $customerNew->save();
            $customerId = $customerNew->id;
            $customerEmail = $customerNew->email;
        }
        
        //Insurance Variables
        $product = \App\productsInsurance::find($request['product']);
        $trans = 'AA';
        $insuranceCount = \App\insurance::count();
        $transCode = $insuranceCount + 1;
        $code = rand(111111,999999);

        //Store Insurance
        $insurance = new \App\insurance();
        $insurance->transaction_code = $trans . $transCode;
        $insurance->customer_id = $customerId;
        $insurance->product_insurance_id = $request['product'];
        $insurance->status_id = 6;
        $insurance->value_month = $product->value_month;
        $insurance->value_year = $product->value_year;
        $insurance->code = $code;
        $insurance->save();
        
        $job = (new \App\Jobs\InsuranceEmailJobs($insurance->id, $customerEmail, $code));
        dispatch($job);
        
        //Beneficiaries
        foreach($request['beneTable'] as $bene){
            //Update or Insert Customer
            $customerSql = 'select * from customers where document = "' . $bene['document'] . '"';
            $customer = DB::select($customerSql);

            //Validate Customer Save or Update
            if ($customer) {
                $beneId = $customer[0]->id;
            } else {
                $documentType = \App\document::where('name','=',$bene['type'])->get();
                
                //Document Type
                $customerNew = new \App\customers();
                $customerNew->first_name = $request['customerData']['firstName'];
                $customerNew->last_name = $request['customerData']['lastName'];
                $customerNew->document = $request['customerData']['document'];
                $customerNew->document_id = $documentType[0]->id;
                $customerNew->status_id = 1;
                $customerNew->save();
                $beneId = $customerNew->id;
            }
            
            //Insert Insurance Beneficiary
            $insuranceBene = new \App\insurance_beneficiary();
            $insuranceBene->customer_id = $beneId;
            $insuranceBene->insurance_id = $insurance->id;
            $insuranceBene->porcentage = $bene['porcentage'];
            $insuranceBene->save();
        }
        //Return Data
        return $insurance->id;
    }
    
    public function resendCode(request $request){
        $code = rand(111111,999999);
        
        $insurance = \App\insurance::find($request['insuranceId']);
        $insurance->code = $code;
        $insurance->save();
        
        $customer = \App\customers::find($insurance->customer_id);
        
        $job = (new \App\Jobs\InsuranceEmailJobs($insurance->id, $customer->email, $code));
        dispatch($job);
    }
    
    public function validateCode(request $request){
        $insurance = \App\insurance::find($request['insuranceId']);
        if($insurance->code == $request['validationCode']){
            $insurance->code_date = new \DateTime();
            $insurance->status_id = 1;
            $insurance->save();
            return 'success';
        }else{
            return 'false';
        }
    }
    
    public function cancel(request $request){
        $insurance = \App\insurance::find($request['insuranceId']);
        $insurance->status_id = 4;
        $insurance->save();
    }
}
