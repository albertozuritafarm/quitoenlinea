<?php

namespace App\Http\Controllers;

use App\rc;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
Use Redirect;
use Barryvdh\DomPDF\Facade as PDF;
use Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Carbon;
use DataTables;
use Illuminate\Pagination\Paginator;
use Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Underscore\Parse;
use ArieTimmerman\Laravel\URLShortener\URLShortener;
use App\Mail\paymentSendLinkUserEmail;
use App\Mail\beneficiariesRequestSendLinkEmail;
use App\Mail\insuranceRequestSendLinkEmail;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class legalTercerosVinculationController extends Controller
{
    public function create(){
        //        $document = encrypt(1757612005);
        //        return $document;
                //Variables are Set
        //        if (!isset($_GET['document']) || !isset($_GET['sales'])) { \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo.'); return view('home'); }else{ $document_get = Crypt::decryptString($_GET['document']); $sales_get = Crypt::decryptString($_GET['sales']); }
                if (!isset($_GET['document']) || !isset($_GET['sales'])) { \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo.'); return view('home'); }else{ $document_get = decrypt($_GET['document']); $sales_get = decrypt($_GET['sales']); }
                
                //Initiate Variables
                $birth_place = '123'; $birth_date = null; $nationality_id = null;
                $sector = null; $main_road = null; $address_number = null;
                $secondary_road = null; $email = null; $secondary_email = null;
                $mobile_phone = null; $phone = null; $civil_state = null;
                $province_id = null; $addressCities = null; $optRadioYes = null; $optRadioNo = null;
                $customer_id = null; $sales_id = null; $city_id = null; $sales = null; $disable_status = null;
                $economic_activity = null; $other_economic_activity = null; $monthly_income = null;
                $monthly_outcome = null; $total_actives = null; $total_pasives = null; $other_monthly_income = null; $other_monthly_income_source = null;
                $person_exposed = null; $family_exposed = null;
                $picture_document_applicant = ''; $picture_document_spouse = ''; $picture_voting_ballot_applicant = '';
                $picture_voting_ballot_spouse = ''; $picture_service = '';
                $spouse_document = null; $spouse_name = null; $country_id = null; $annual_income = null;
                $spouse_last_name = null; $spouse_document_id = null; $occupation = null; $other_annual_income = null; $total_annual_income = null;
                $description_other_income = null; $total_assets = null; $total_assets_pasives = null;
                $document_applicant_date = null; $document_spouse_date = null; $pep_client = null;
                $personal_reference_name = null; $personal_reference_relationship = null; $personal_reference_phone = null;
                $commercial_reference_name = null; $commercial_reference_amount = null; $commercial_reference_phone = null;
                $commercial_reference_bank_name = null; $commercial_reference_product = null;
                $constitution_date = null; $legal_representative = null;
                $beneficiaryName = null; $beneficiary_document_id = null; $beneficiary_document = null;
                $beneficiary_nationality = null; $beneficiary_address = null; $beneficiary_phone = null; $beneficiary_relationship = null;
                $economic_activity_id = null; $occupation = null;
                $work_name =  null; $work_address = null; $work_phone = null; $picture_sri = null;
                //Validate Document
                $customer = \App\customers::where('document','=',$document_get)->get(); if($customer->isEmpty()){ \Session::flash('ValidateUserRoute', 'Por favor solicite un nuevo enlace1.'); return view('home'); }
        
                //Validate Sales
                $Sales = \App\sales::where('id','=',$sales_get)->get();
                if($Sales->isEmpty()){ \Session::flash('ValidateUserRoute', 'Por favor solicite un nuevo enlace2.'); return view('home'); }
                
                $vinculation = \App\vinculation_form::where('customer_id','=',$customer[0]->id)->where('sales_id','=',$sales_get)->latest()->first();
        //        dd($vinculation);
                if($vinculation){
                    if($vinculation->status_id == 1){ $disable_status = 'disabled="disabled"'; }
                    $birth_place = $vinculation->birth_place; $birth_date = $vinculation->birth_date; $nationality_id = $vinculation->nationality_id;
                    $sector = $vinculation->address_zone; $main_road = $vinculation->main_road; $address_number = $vinculation->address_number;
                    $secondary_road = $vinculation->secondary_road; $email = $vinculation->email; $secondary_email = $vinculation->secondary_email;
                    $mobile_phone = $vinculation->mobile_phone; $phone = $vinculation->phone; $civil_state = $vinculation->civil_state; $city_id = $vinculation->city_id;
                    $customer_id = $vinculation->customer_id; $sales_id = $vinculation->sales_id;
                    $economic_activity = $vinculation->economic_activity_id; $other_economic_activity = $vinculation->economic_activity_other; $monthly_income = $vinculation->monthly_income;
                    $monthly_outcome = $vinculation->monthly_outcome; $total_actives = $vinculation->total_actives; $total_pasives = number_format($vinculation->total_pasives,2); 
                    $other_monthly_income = $vinculation->other_monthly_income; $other_monthly_income_source = $vinculation->other_monthly_income_source;
                    $person_exposed = $vinculation->person_exposed; $family_exposed = $vinculation->family_exposed;
                    if($vinculation->picture_document_applicant == null) { $picture_document_applicant = ''; } else { if(pathinfo($vinculation->picture_document_applicant)['extension'] == 'pdf'){ $picture_document_applicant = '<a href="' . $vinculation->picture_document_applicant . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_document_applicant = '<a href="' . $vinculation->picture_document_applicant . '" target="_blank"><img src="' . $vinculation->picture_document_applicant . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                    if ($vinculation->picture_document_spouse == null) { $picture_document_spouse = ''; } else { if(pathinfo($vinculation->picture_document_spouse)['extension'] == 'pdf'){ $picture_document_spouse = '<a href="' . $vinculation->picture_document_spouse . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_document_spouse = '<a href="' . $vinculation->picture_document_spouse . '" target="_blank"><img src="' . $vinculation->picture_document_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                    if ($vinculation->picture_voting_ballot == null) { $picture_voting_ballot_applicant = ''; } else { if(pathinfo($vinculation->picture_voting_ballot)['extension'] == 'pdf'){ $picture_voting_ballot_applicant = '<a href="' . $vinculation->picture_voting_ballot . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_voting_ballot_applicant = '<a href="' . $vinculation->picture_voting_ballot . '" target="_blank"><img src="' . $vinculation->picture_voting_ballot . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                    if ($vinculation->picture_voting_ballot_spouse == null) { $picture_voting_ballot_spouse = ''; } else { if(pathinfo($vinculation->picture_voting_ballot_spouse)['extension'] == 'pdf'){ $picture_voting_ballot_spouse = '<a href="' . $vinculation->picture_voting_ballot_spouse . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_voting_ballot_spouse = '<a href="' . $vinculation->picture_voting_ballot_spouse . '" target="_blank"><img src="' . $vinculation->picture_voting_ballot_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                    if ($vinculation->picture_service == null) { $picture_service = ''; } else { if(pathinfo($vinculation->picture_service)['extension'] == 'pdf'){ $picture_service = '<a href="' . $vinculation->picture_service . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_service = '<a href="' . $vinculation->picture_service . '" target="_blank"><img src="' . $vinculation->picture_service . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                    if ($vinculation->picture_sri == null) { $picture_sri = ''; } else { if(pathinfo($vinculation->picture_sri)['extension'] == 'pdf'){ $picture_sri = '<a href="' . $vinculation->picture_sri . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_sri = '<a href="' . $vinculation->picture_sri . '" target="_blank"><img src="' . $vinculation->picture_sri . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                    $spouse_document = $vinculation->spouse_document; $spouse_name = $vinculation->spouse_name; $spouse_last_name = $vinculation->spouse_last_name; $annual_income = number_format($vinculation->annual_income,2); $spouse_document_id = $vinculation->spouse_document_id;
                    $occupation = $vinculation->occupation; $other_annual_income = number_format($vinculation->other_annual_income,2); $total_annual_income = number_format($vinculation->total_annual_income,2);
                    $description_other_income = $vinculation->description_other_income; $total_assets = number_format($vinculation->total_actives,2); $total_assets_pasives = number_format($vinculation->total_assets_pasives,2);
                    $document_applicant_date = $vinculation->document_applicant_date; $document_spouse_date = $vinculation->document_spouse_date; $pep_client = $vinculation->family_exposed;
                    $personal_reference_name = $vinculation->personal_reference_name; $personal_reference_relationship = $vinculation->personal_reference_relationship; $personal_reference_phone = $vinculation->personal_reference_phone;
                    $commercial_reference_name = $vinculation->commercial_reference_name; $commercial_reference_amount = number_format($vinculation->commercial_reference_amount,2); $commercial_reference_phone = $vinculation->commercial_reference_phone;
                    $commercial_reference_bank_name = $vinculation->commercial_reference_bank_name; $commercial_reference_product = $vinculation->commercial_reference_product;
                    $constitution_date = $vinculation->constitution_date; $legal_representative = \App\customerLegalRepresentative::find($vinculation->customer_legal_representative_id);
                    $beneficiaryName = $vinculation->benefitiary_name; $beneficiary_document_id = $vinculation->benefitiary_document_id; $beneficiary_document = $vinculation->benefitiary_document;
                    $beneficiary_nationality = $vinculation->benefitiary_nationality_id; $beneficiary_address = $vinculation->benefitiary_address; $beneficiary_phone = $vinculation->benefitiary_phone; $beneficiary_relationship = $vinculation->benefitiary_relationship;
                    $economic_activity_id = $vinculation->economic_activity_id;
                    $work_name =  $vinculation->work_name; $work_address = $vinculation->work_address; $work_phone = $vinculation->work_phone;
                    }else {
                    $vinculationOther = \App\vinculation_form::where('customer_id','=',$customer[0]->id)->latest()->first();
                    if($vinculationOther){
                        if($vinculationOther->status_id == 1){ $disable_status = ''; }
                        $birth_place = $vinculationOther->birth_place; $birth_date = $vinculationOther->birth_date; $nationality_id = $vinculationOther->nationality_id;
                        $sector = $vinculationOther->address_zone; $main_road = $vinculationOther->main_road; $address_number = $vinculationOther->address_number;
                        $secondary_road = $vinculationOther->secondary_road; $email = $vinculationOther->email; $secondary_email = $vinculationOther->secondary_email;
                        $mobile_phone = $vinculationOther->mobile_phone; $phone = $vinculationOther->phone; $civil_state = $vinculationOther->civil_state; $city_id = $vinculationOther->city_id;
                        $customer_id = $vinculationOther->customer_id; $sales_id = $vinculationOther->sales_id;
                        $economic_activity = $vinculationOther->economic_activity_id; $other_economic_activity = $vinculationOther->economic_activity_other; $monthly_income = $vinculationOther->monthly_income;
                        $monthly_outcome = $vinculationOther->monthly_outcome; $total_actives = $vinculationOther->total_actives; $total_pasives = $vinculationOther->total_pasives; 
                        $other_monthly_income = $vinculationOther->other_monthly_income; $other_monthly_income_source = $vinculationOther->other_monthly_income_source;
                        $person_exposed = $vinculationOther->person_exposed; $family_exposed = $vinculationOther->family_exposed;
                        if($vinculationOther->picture_document_applicant == null){ $picture_document_applicant = ''; }else{ $picture_document_applicant = '<a href="'.$vinculationOther->picture_document_applicant.'" target="_blank"><img src="' . $vinculationOther->picture_document_applicant . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                        if ($vinculationOther->picture_document_spouse == null) { $picture_document_spouse = ''; } else { $picture_document_spouse = '<a href="' . $vinculationOther->picture_document_spouse . '" target="_blank"><img src="' . $vinculationOther->picture_document_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                        if($vinculationOther->picture_voting_ballot == null){ $picture_voting_ballot_applicant = ''; }else{ $picture_voting_ballot_applicant = '<a href="'.$vinculationOther->picture_voting_ballot.'" target="_blank"><img src="' . $vinculationOther->picture_voting_ballot . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                        if($vinculationOther->picture_voting_ballot_spouse == null){ $picture_voting_ballot_spouse = ''; }else{ $picture_voting_ballot_spouse = '<a href="'.$vinculationOther->picture_voting_ballot_spouse.'" target="_blank"><img src="' . $vinculationOther->picture_voting_ballot_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                        if($vinculationOther->picture_service == null){ $picture_service = ''; }else{ $picture_service = '<a href="'.$vinculationOther->picture_service.'" target="_blank"><img src="' . $vinculationOther->picture_service . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                        $spouse_document = $vinculationOther->spouse_document; $spouse_name = $vinculationOther->spouse_name; $spouse_last_name = $vinculationOther->spouse_last_name; $annual_income = number_format($vinculationOther->annual_income,2); $spouse_document_id = $vinculationOther->spouse_document_id;
                        $occupation = $vinculationOther->occupation; $other_annual_income = $vinculationOther->other_annual_income; $total_annual_income = $vinculationOther->total_annual_income;
                        $description_other_income = $vinculationOther->description_other_income; $total_assets = $vinculation->total_actives; $total_assets_pasives = $vinculationOther->total_assets_pasives;
                        $document_applicant_date = $vinculationOther->document_applicant_date; $document_spouse_date = $vinculationOther->document_spouse_date; $pep_client = $vinculationOther->family_exposed;
                        $personal_reference_name = $vinculationOther->personal_reference_name; $personal_reference_relationship = $vinculationOther->personal_reference_relationship; $personal_reference_phone = $vinculationOther->personal_reference_phone;
                        $commercial_reference_name = $vinculationOther->commercial_reference_name; $commercial_reference_amount = $vinculationOther->commercial_reference_amount; $commercial_reference_phone = $vinculationOther->commercial_reference_phone;
                        $commercial_reference_bank_name = $vinculationOther->commercial_reference_bank_name; $commercial_reference_product = $vinculationOther->commercial_reference_product;
                        $constitution_date = $vinculationOther->constitution_date; $legal_representative = \App\customerLegalRepresentative::find($vinculationOther->customer_legal_representative);
                        $beneficiaryName = $vinculationOther->benefitiary_name; $beneficiary_document_id = $vinculationOther->benefitiary_document_id; $beneficiary_document = $vinculationOther->benefitiary_document;
                        $beneficiary_nationality = $vinculationOther->benefitiary_nationality_id; $beneficiary_address = $vinculationOther->benefitiary_address; $beneficiary_phone = $vinculationOther->benefitiary_phone; $beneficiary_relationship = $vinculationOther->benefitiary_relationship;
                        $economic_activity_id = $vinculationOther->economic_activity_id;
                        $work_name =  $vinculationOther->work_name; $work_address = $vinculationOther->work_address; $work_phone = $vinculationOther->work_phone; $picture_sri = $vinculationOther->picture_sri;
                        
                        $vinculationOld = \App\vinculation_form::find($vinculationOther->id);
                        
                        //Copy Documents
                        $vinculationNew = $vinculationOld->replicate();
                        $vinculationNew->customer_id  = $vinculationOther->customer_id;
                        $vinculationNew->sales_id = $sales_get;
                        $vinculationNew->status_id = 6;
                        $vinculationNew->save();
                        
                        //Create Dir
                        mkdir(public_path('images/vinculation/'.$vinculationNew->id));
                        
                        $vinculactionUpdate = \App\vinculation_form::find($vinculationNew->id);
                        if($picture_document_applicant != null) { $file = basename($vinculationOther->picture_document_applicant); $success = \File::copy(public_path($vinculationOther->picture_document_applicant), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_document_applicant = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                        if ($picture_document_spouse != null) { $file = basename($vinculationOther->picture_document_spouse); $success = \File::copy(public_path($vinculationOther->picture_document_spouse), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_document_spouse = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                        if ($picture_voting_ballot_applicant != null) { $file = basename($vinculationOther->picture_voting_ballot); $success = \File::copy(public_path($vinculationOther->picture_voting_ballot), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_voting_ballot = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                        if ($picture_voting_ballot_spouse != null) { $file = basename($vinculationOther->picture_voting_ballot_spouse); $success = \File::copy(public_path($vinculationOther->picture_voting_ballot_spouse), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_voting_ballot_spouse = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                        if ($picture_service != null) { $file = basename($vinculationOther->picture_service); $success = \File::copy(public_path($vinculationOther->picture_service), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_service = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                        $vinculactionUpdate->save();
        
                    }
                }
        
                //Obtain Province & Country
                if ($city_id) {
                    $province = \App\city::find($city_id);
                    if ($province) {
                        $province_id = $province->province_id;
                        $addressCities = \App\city::where('province_id', '=', $province_id)->get();
                        /* Obtain Country */
                        $province = \App\province::find($province_id);
                        $countries = \App\country::find($province->country_id);
                        $country_id = $countries->id;
                    }
                }
        //        dd($country_id);
                //Email Radio BTn
                if($secondary_email){ $optRadioYes = null; $optRadioNo = true; }else{ $optRadioYes = true; $optRadioNo = null; }
                
                //Economic Activities
                $economicActivities = \App\economic_activity::all();
                $occupationList = \App\economic_ocupation::all();
                
                //DOCUMENT
                //eyJpdiI6Ik5jT1FBTjRcL3d1OExHWU1wbmVJd0FnPT0iLCJ2YWx1ZSI6IlRRYnY3cFFRRkJINUFoTERBMUpSYkE9PSIsIm1hYyI6IjcyYTAyOGM1ZGI0N2ZlYmQ0NmQxZjZmMmIxMGQzZGYxNjBmY2M3MWFkYjhiM2I1ODQ0ZWJlYzk2MDEzYTE4YmQifQ==
                //SALE
                //eyJpdiI6IkNFQVNZdlJhV2lBSmh1VytLVW9LVWc9PSIsInZhbHVlIjoieHdnXC9OdVwvWXJUbGNNd0lsOEdoZ0lBPT0iLCJtYWMiOiJmNjg4Yjc5ODBlYzkyZTJiMGIzMzYyZDA2NTgyZjY0ZGI0MzJhYjA2NTU3MGUzMTVjOWQ0Y2FmZjlmNDkzY2I2In0=
                
                $documents = \App\document::all();
                $cities = \App\city::orderBy('name','ASC')->get();
                $countries = \App\country::all();
                $provinces = \App\province::orderBy('name','ASC')->get();
                $civisStates = \App\civilState::orderBy('name','ASC')->get();
                $migratoryStates = \App\migratoryState::orderBy('name','ASC')->get();
                
                if(isset($_GET['broker'])){
                    $disable_status = 'disabled="disabled"';
                }
                
                if($vinculation->status_id == 1){
                    $vinFormVersion = $vinculation->form_version;
                    $checked = 'true';
                }else{
                    $checked = null;
                    $result = valorAseguradoSS($document_get);
                    $saleData = \App\sales::find($sales_get);
                    $valorAsegurado = $result['monto'] + $saleData->insured_value;
                    if($valorAsegurado <= 50000){
                        $vinFormVersion = 1;
                    }elseif($valorAsegurado > 200000){
                        $vinFormVersion = 4;
                    }else{
                        $vinFormVersion = 2;
                    }
                }
                
                //Residency Country Only Ecuador
                $countryResidence = \App\country::where('id','=',1)->get();
                
                //Update Form Version
                $vinculatioUpdate = \App\vinculation_form::find($vinculation->id);
                $vinculatioUpdate->form_version = $vinFormVersion;
                $vinculatioUpdate->save();
        //        $vinFormVersion = 4;
                if($vinFormVersion == 1){
                    return view('vinculation.pn.v1',[
                        'documents' => $documents, 'cities' => $cities, 'countries' => $countries,'countriesResidence' => $countryResidence, 'customer' => $customer[0], 'provinces' => $provinces,
                        'civilStates' => $civisStates, 'migratoryStates' => $migratoryStates, 'birth_place' => $birth_place, 'birth_date' => $birth_date,
                        'nationality_id' => $nationality_id, 'sector' => $sector, 'main_road' => $main_road, 'address_number' => $address_number,
                        'secondary_road' => $secondary_road, 'email' => $email, 'secondary_email' => $secondary_email, 'mobile_phone' => $mobile_phone,
                        'phone' => $phone, 'civil_state' => $civil_state, 'city_id' => $city_id, 'province_id' => $province_id,
                        'country_id' => $country_id, 'addressCities' => $addressCities, 'optRadioYes' => $optRadioYes, 'optRadioNo' => $optRadioNo,
                        'economicActivities' => $economicActivities, 'sales_id' => $sales_get, 'disable_status' => $disable_status,
                        'economic_activity' => $economic_activity, 'other_economic_activity' => $other_economic_activity, 'monthly_income' => $monthly_income,
                        'monthly_outcome' => $monthly_outcome, 'total_actives' => $total_actives, 'total_pasives' => $total_pasives,
                        'other_monthly_income' => $other_monthly_income, 'other_monthly_income_source' => $other_monthly_income_source, 'person_exposed' => $person_exposed, 'family_exposed' => $family_exposed, 'picture_document_applicant' => $picture_document_applicant,
                        'picture_document_spouse' => $picture_document_spouse, 'picture_voting_ballot_applicant' => $picture_voting_ballot_applicant, 'picture_voting_ballot_spouse' => $picture_voting_ballot_spouse,
                        'picture_service' => $picture_service, 'spouse_document' => $spouse_document, 'spouse_name' => $spouse_name,
                        'spouse_last_name' => $spouse_last_name, 'annual_income' => $annual_income, 'spouse_document_id' => $spouse_document_id,
                        'occupation' => $occupation, 'other_annual_income' => $other_annual_income, 'total_annual_income' => $total_annual_income,
                        'description_other_income' => $description_other_income, 'total_assets' => $total_assets, 'total_pasives' => $total_pasives, 'total_assets_pasives' => $total_assets_pasives,
                        'document_applicant_date' => $document_applicant_date, 'document_spouse_date' => $document_spouse_date, 'pep_client' => $pep_client,
                        'beneficiaryName' => $beneficiaryName, 'beneficiary_document_id' => $beneficiary_document_id, 'beneficiary_document' => $beneficiary_document,
                        'occupationList' => $occupationList, 'economic_activity_id' =>  $economic_activity_id,
                        'beneficiary_nationality' => $beneficiary_nationality, 'beneficiary_address' => $beneficiary_address, 'beneficiary_phone' => $beneficiary_phone, 'beneficiary_relationship' => $beneficiary_relationship, 'checked' => $checked
                            
                    ]); 
                } 
                if($vinFormVersion == 2){
                    return view('vinculation.pn.v2',[
                        'documents' => $documents, 'cities' => $cities, 'countries' => $countries,'countriesResidence' => $countryResidence, 'customer' => $customer[0], 'provinces' => $provinces,
                        'civilStates' => $civisStates, 'migratoryStates' => $migratoryStates, 'birth_place' => $birth_place, 'birth_date' => $birth_date,
                        'nationality_id' => $nationality_id, 'sector' => $sector, 'main_road' => $main_road, 'address_number' => $address_number,
                        'secondary_road' => $secondary_road, 'email' => $email, 'secondary_email' => $secondary_email, 'mobile_phone' => $mobile_phone,
                        'phone' => $phone, 'civil_state' => $civil_state, 'city_id' => $city_id, 'province_id' => $province_id,
                        'country_id' => $country_id, 'addressCities' => $addressCities, 'optRadioYes' => $optRadioYes, 'optRadioNo' => $optRadioNo,
                        'economicActivities' => $economicActivities, 'sales_id' => $sales_get, 'disable_status' => $disable_status,
                        'economic_activity' => $economic_activity, 'other_economic_activity' => $other_economic_activity, 'monthly_income' => $monthly_income,
                        'monthly_outcome' => $monthly_outcome, 'total_actives' => $total_actives, 'total_pasives' => $total_pasives,
                        'other_monthly_income' => $other_monthly_income, 'other_monthly_income_source' => $other_monthly_income_source, 'person_exposed' => $person_exposed, 'family_exposed' => $family_exposed, 'picture_document_applicant' => $picture_document_applicant,
                        'picture_document_spouse' => $picture_document_spouse, 'picture_voting_ballot_applicant' => $picture_voting_ballot_applicant, 'picture_voting_ballot_spouse' => $picture_voting_ballot_spouse,
                        'picture_service' => $picture_service, 'spouse_document' => $spouse_document, 'spouse_name' => $spouse_name,
                        'spouse_last_name' => $spouse_last_name, 'annual_income' => $annual_income, 'spouse_document_id' => $spouse_document_id,
                        'occupation' => $occupation, 'other_annual_income' => $other_annual_income, 'total_annual_income' => $total_annual_income,
                        'description_other_income' => $description_other_income, 'total_assets' => $total_assets, 'total_pasives' => $total_pasives, 'total_assets_pasives' => $total_assets_pasives,
                        'document_applicant_date' => $document_applicant_date, 'document_spouse_date' => $document_spouse_date, 'pep_client' => $pep_client,
                        'beneficiaryName' => $beneficiaryName, 'beneficiary_document_id' => $beneficiary_document_id, 'beneficiary_document' => $beneficiary_document,
                        'occupationList' => $occupationList, 'economic_activity_id' =>  $economic_activity_id,
                        'beneficiary_nationality' => $beneficiary_nationality, 'beneficiary_address' => $beneficiary_address, 'beneficiary_phone' => $beneficiary_phone, 'beneficiary_relationship' => $beneficiary_relationship,
                        'picture_sri' => $picture_sri, 'checked' => $checked
                    ]); 
                } 
                if($vinFormVersion == 4){
                    return view('vinculation.pn.v4',[
                        'documents' => $documents, 'cities' => $cities, 'countries' => $countries,'countriesResidence' => $countryResidence, 'customer' => $customer[0], 'provinces' => $provinces,
                        'civilStates' => $civisStates, 'migratoryStates' => $migratoryStates, 'birth_place' => $birth_place, 'birth_date' => $birth_date,
                        'nationality_id' => $nationality_id, 'sector' => $sector, 'main_road' => $main_road, 'address_number' => $address_number,
                        'secondary_road' => $secondary_road, 'email' => $email, 'secondary_email' => $secondary_email, 'mobile_phone' => $mobile_phone,
                        'phone' => $phone, 'civil_state' => $civil_state, 'city_id' => $city_id, 'province_id' => $province_id,
                        'country_id' => $country_id, 'addressCities' => $addressCities, 'optRadioYes' => $optRadioYes, 'optRadioNo' => $optRadioNo,
                        'economicActivities' => $economicActivities, 'sales_id' => $sales_get, 'disable_status' => $disable_status,
                        'economic_activity' => $economic_activity, 'other_economic_activity' => $other_economic_activity, 'monthly_income' => $monthly_income,
                        'monthly_outcome' => $monthly_outcome, 'total_actives' => $total_actives, 'total_pasives' => $total_pasives,
                        'other_monthly_income' => $other_monthly_income, 'other_monthly_income_source' => $other_monthly_income_source, 'person_exposed' => $person_exposed, 'family_exposed' => $family_exposed, 'picture_document_applicant' => $picture_document_applicant,
                        'picture_document_spouse' => $picture_document_spouse, 'picture_voting_ballot_applicant' => $picture_voting_ballot_applicant, 'picture_voting_ballot_spouse' => $picture_voting_ballot_spouse,
                        'picture_service' => $picture_service, 'spouse_document' => $spouse_document, 'spouse_name' => $spouse_name,
                        'spouse_last_name' => $spouse_last_name, 'annual_income' => $annual_income, 'spouse_document_id' => $spouse_document_id,
                        'occupation' => $occupation, 'other_annual_income' => $other_annual_income, 'total_annual_income' => $total_annual_income,
                        'description_other_income' => $description_other_income, 'total_assets' => $total_assets, 'total_pasives' => $total_pasives, 'total_assets_pasives' => $total_assets_pasives,
                        'document_applicant_date' => $document_applicant_date, 'document_spouse_date' => $document_spouse_date, 'pep_client' => $pep_client,
                        'personal_reference_name' => $personal_reference_name, 'personal_reference_relationship' => $personal_reference_relationship, 'personal_reference_phone' => $personal_reference_phone,
                        'commercial_reference_name' => $commercial_reference_name, 'commercial_reference_amount' => $commercial_reference_amount, 'commercial_reference_phone' => $commercial_reference_phone,
                        'commercial_reference_bank_name' => $commercial_reference_bank_name, 'commercial_reference_product' => $commercial_reference_product,
                        'beneficiaryName' => $beneficiaryName, 'beneficiary_document_id' => $beneficiary_document_id, 'beneficiary_document' => $beneficiary_document,
                        'occupationList' => $occupationList,'economic_activity_id' =>  $economic_activity_id,
                        'beneficiary_nationality' => $beneficiary_nationality, 'beneficiary_address' => $beneficiary_address, 'beneficiary_phone' => $beneficiary_phone, 'beneficiary_relationship' => $beneficiary_relationship,
                        'work_name' => $work_name, 'work_address' => $work_address, 'work_phone' => $work_phone, 'picture_sri' => $picture_sri, 'checked' => $checked
                     ]); 
                } 
                if($vinFormVersion == 5){
                    return view('vinculation.pj.v5',[
                        'documents' => $documents, 'cities' => $cities, 'countries' => $countries,'countriesResidence' => $countryResidence, 'customer' => $customer[0], 'provinces' => $provinces,
                        'civilStates' => $civisStates, 'migratoryStates' => $migratoryStates, 'birth_place' => $birth_place, 'birth_date' => $birth_date,
                        'nationality_id' => $nationality_id, 'sector' => $sector, 'main_road' => $main_road, 'address_number' => $address_number,
                        'secondary_road' => $secondary_road, 'email' => $email, 'secondary_email' => $secondary_email, 'mobile_phone' => $mobile_phone,
                        'phone' => $phone, 'civil_state' => $civil_state, 'city_id' => $city_id, 'province_id' => $province_id,
                        'country_id' => $country_id, 'addressCities' => $addressCities, 'optRadioYes' => $optRadioYes, 'optRadioNo' => $optRadioNo,
                        'economicActivities' => $economicActivities, 'sales_id' => $sales_get, 'disable_status' => $disable_status,
                        'economic_activity' => $economic_activity, 'other_economic_activity' => $other_economic_activity, 'monthly_income' => $monthly_income,
                        'monthly_outcome' => $monthly_outcome, 'total_actives' => $total_actives, 'total_pasives' => $total_pasives,
                        'other_monthly_income' => $other_monthly_income, 'other_monthly_income_source' => $other_monthly_income_source, 'person_exposed' => $person_exposed, 'family_exposed' => $family_exposed, 'picture_document_applicant' => $picture_document_applicant,
                        'picture_document_spouse' => $picture_document_spouse, 'picture_voting_ballot_applicant' => $picture_voting_ballot_applicant, 'picture_voting_ballot_spouse' => $picture_voting_ballot_spouse,
                        'picture_service' => $picture_service, 'spouse_document' => $spouse_document, 'spouse_name' => $spouse_name,
                        'spouse_last_name' => $spouse_last_name, 'annual_income' => $annual_income, 'spouse_document_id' => $spouse_document_id,
                        'occupation' => $occupation, 'other_annual_income' => $other_annual_income, 'total_annual_income' => $total_annual_income,
                        'description_other_income' => $description_other_income, 'total_assets' => $total_assets, 'total_pasives' => $total_pasives, 'total_assets_pasives' => $total_assets_pasives,
                        'document_applicant_date' => $document_applicant_date, 'document_spouse_date' => $document_spouse_date, 'pep_client' => $pep_client,
                        'personal_reference_name' => $personal_reference_name, 'personal_reference_relationship' => $personal_reference_relationship, 'personal_reference_phone' => $personal_reference_phone,
                        'commercial_reference_name' => $commercial_reference_name, 'commercial_reference_amount' => $commercial_reference_amount, 'commercial_reference_phone' => $commercial_reference_phone,
                        'commercial_reference_bank_name' => $commercial_reference_bank_name, 'commercial_reference_product' => $commercial_reference_product,
                        'constitution_date' => $constitution_date, 'legal_representative' => $legal_representative, 'economic_activity_id' =>  $economic_activity_id,
                     ]); 
                }
            }
    public function createTercerosPerson() {
    //Validate Create Permission
   /* $create = checkExtraPermits('70', \Auth::user()->role_id);
    if (!$create) {
        \Session::flash('ValidateUserRoute', 'No tiene acceso a crear nuevas cotizaciones.');
        return view('home');
    }*/
    
    //Obtain Create Permission
    //$agency_id=\Auth::user()->agen_id;
    //$channel=\App\Agency::find($agency_id);
    $customer = new \App\customers();
    $disabled = null;
    $documents = DB::select('select * from documents where id in (1,3)');
    $emissionTypes = DB::select('select * from emission_type where id in (1,2)');
    $sale_movement = 1;
   /* $branch = \App\product_channel::selectRaw('products.ramodes')                         
                            ->join('products', 'products.id', '=', 'products_channel.product_id')
                            ->where('products_channel.agency_id', '=',$agency_id)
                            ->where('products_channel.channel_id', '=',$channel->channel_id)
                            ->groupBy('products.ramodes')
                            ->get(); */
                            //if (!isset($_GET['document']) || !isset($_GET['sales'])) { \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo.'); return view('home'); }else{ $document_get = decrypt($_GET['document']); $sales_get = decrypt($_GET['sales']); }
                
                            //Initiate Variables
                            $birth_place = '123'; $birth_date = null; $nationality_id = null;
                            $sector = null; $main_road = null; $address_number = null;
                            $secondary_road = null; $email = null; $secondary_email = null;
                            $mobile_phone = null; $phone = null; $civil_state = null;
                            $province_id = null; $addressCities = null; $optRadioYes = null; $optRadioNo = null;
                            $customer_id = null; $sales_id = null; $city_id = null; $sales = null; $disable_status = null;
                            $economic_activity = null; $other_economic_activity = null; $monthly_income = null;
                            $monthly_outcome = null; $total_actives = null; $total_pasives = null; $other_monthly_income = null; $other_monthly_income_source = null;
                            $person_exposed = null; $family_exposed = null;
                            $picture_document_applicant = ''; $picture_document_spouse = ''; $picture_voting_ballot_applicant = '';
                            $picture_voting_ballot_spouse = ''; $picture_service = '';
                            $spouse_document = null; $spouse_name = null; $country_id = null; $annual_income = null;
                            $spouse_last_name = null; $spouse_document_id = null; $occupation = null; $other_annual_income = null; $total_annual_income = null;
                            $description_other_income = null; $total_assets = null; $total_assets_pasives = null;
                            $document_applicant_date = null; $document_spouse_date = null; $pep_client = null;
                            $personal_reference_name = null; $personal_reference_relationship = null; $personal_reference_phone = null;
                            $commercial_reference_name = null; $commercial_reference_amount = null; $commercial_reference_phone = null;
                            $commercial_reference_bank_name = null; $commercial_reference_product = null;
                            $constitution_date = null; $legal_representative = null;
                            $beneficiaryName = null; $beneficiary_document_id = null; $beneficiary_document = null;
                            $beneficiary_nationality = null; $beneficiary_address = null; $beneficiary_phone = null; $beneficiary_relationship = null;
                            $economic_activity_id = null; $occupation = null;
                            $work_name =  null; $work_address = null; $work_phone = null; $picture_sri = null;
                            //Validate Document
                           // $customer = \App\customers::where('document','=',$document_get)->get(); if($customer->isEmpty()){ \Session::flash('ValidateUserRoute', 'Por favor solicite un nuevo enlace1.'); return view('home'); }
                    
                            //Validate Sales
                           /* $Sales = \App\sales::where('id','=',$sales_get)->get();
                            if($Sales->isEmpty()){ \Session::flash('ValidateUserRoute', 'Por favor solicite un nuevo enlace2.'); return view('home'); }
                            $vinculation = \App\vinculation_form::where('customer_id','=',$customer[0]->id)->where('sales_id','=',$sales_get)->latest()->first();
                            //        dd($vinculation);
                                    if($vinculation){
                                        if($vinculation->status_id == 1){ $disable_status = 'disabled="disabled"'; }
                                        $birth_place = $vinculation->birth_place; $birth_date = $vinculation->birth_date; $nationality_id = $vinculation->nationality_id;
                                        $sector = $vinculation->address_zone; $main_road = $vinculation->main_road; $address_number = $vinculation->address_number;
                                        $secondary_road = $vinculation->secondary_road; $email = $vinculation->email; $secondary_email = $vinculation->secondary_email;
                                        $mobile_phone = $vinculation->mobile_phone; $phone = $vinculation->phone; $civil_state = $vinculation->civil_state; $city_id = $vinculation->city_id;
                                        $customer_id = $vinculation->customer_id; $sales_id = $vinculation->sales_id;
                                        $economic_activity = $vinculation->economic_activity_id; $other_economic_activity = $vinculation->economic_activity_other; $monthly_income = $vinculation->monthly_income;
                                        $monthly_outcome = $vinculation->monthly_outcome; $total_actives = $vinculation->total_actives; $total_pasives = number_format($vinculation->total_pasives,2); 
                                        $other_monthly_income = $vinculation->other_monthly_income; $other_monthly_income_source = $vinculation->other_monthly_income_source;
                                        $person_exposed = $vinculation->person_exposed; $family_exposed = $vinculation->family_exposed;
                                        if($vinculation->picture_document_applicant == null) { $picture_document_applicant = ''; } else { if(pathinfo($vinculation->picture_document_applicant)['extension'] == 'pdf'){ $picture_document_applicant = '<a href="' . $vinculation->picture_document_applicant . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_document_applicant = '<a href="' . $vinculation->picture_document_applicant . '" target="_blank"><img src="' . $vinculation->picture_document_applicant . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                                        if ($vinculation->picture_document_spouse == null) { $picture_document_spouse = ''; } else { if(pathinfo($vinculation->picture_document_spouse)['extension'] == 'pdf'){ $picture_document_spouse = '<a href="' . $vinculation->picture_document_spouse . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_document_spouse = '<a href="' . $vinculation->picture_document_spouse . '" target="_blank"><img src="' . $vinculation->picture_document_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                                        if ($vinculation->picture_voting_ballot == null) { $picture_voting_ballot_applicant = ''; } else { if(pathinfo($vinculation->picture_voting_ballot)['extension'] == 'pdf'){ $picture_voting_ballot_applicant = '<a href="' . $vinculation->picture_voting_ballot . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_voting_ballot_applicant = '<a href="' . $vinculation->picture_voting_ballot . '" target="_blank"><img src="' . $vinculation->picture_voting_ballot . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                                        if ($vinculation->picture_voting_ballot_spouse == null) { $picture_voting_ballot_spouse = ''; } else { if(pathinfo($vinculation->picture_voting_ballot_spouse)['extension'] == 'pdf'){ $picture_voting_ballot_spouse = '<a href="' . $vinculation->picture_voting_ballot_spouse . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_voting_ballot_spouse = '<a href="' . $vinculation->picture_voting_ballot_spouse . '" target="_blank"><img src="' . $vinculation->picture_voting_ballot_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                                        if ($vinculation->picture_service == null) { $picture_service = ''; } else { if(pathinfo($vinculation->picture_service)['extension'] == 'pdf'){ $picture_service = '<a href="' . $vinculation->picture_service . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_service = '<a href="' . $vinculation->picture_service . '" target="_blank"><img src="' . $vinculation->picture_service . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                                        if ($vinculation->picture_sri == null) { $picture_sri = ''; } else { if(pathinfo($vinculation->picture_sri)['extension'] == 'pdf'){ $picture_sri = '<a href="' . $vinculation->picture_sri . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_sri = '<a href="' . $vinculation->picture_sri . '" target="_blank"><img src="' . $vinculation->picture_sri . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
                                        $spouse_document = $vinculation->spouse_document; $spouse_name = $vinculation->spouse_name; $spouse_last_name = $vinculation->spouse_last_name; $annual_income = number_format($vinculation->annual_income,2); $spouse_document_id = $vinculation->spouse_document_id;
                                        $occupation = $vinculation->occupation; $other_annual_income = number_format($vinculation->other_annual_income,2); $total_annual_income = number_format($vinculation->total_annual_income,2);
                                        $description_other_income = $vinculation->description_other_income; $total_assets = number_format($vinculation->total_actives,2); $total_assets_pasives = number_format($vinculation->total_assets_pasives,2);
                                        $document_applicant_date = $vinculation->document_applicant_date; $document_spouse_date = $vinculation->document_spouse_date; $pep_client = $vinculation->family_exposed;
                                        $personal_reference_name = $vinculation->personal_reference_name; $personal_reference_relationship = $vinculation->personal_reference_relationship; $personal_reference_phone = $vinculation->personal_reference_phone;
                                        $commercial_reference_name = $vinculation->commercial_reference_name; $commercial_reference_amount = number_format($vinculation->commercial_reference_amount,2); $commercial_reference_phone = $vinculation->commercial_reference_phone;
                                        $commercial_reference_bank_name = $vinculation->commercial_reference_bank_name; $commercial_reference_product = $vinculation->commercial_reference_product;
                                        $constitution_date = $vinculation->constitution_date; $legal_representative = \App\customerLegalRepresentative::find($vinculation->customer_legal_representative_id);
                                        $beneficiaryName = $vinculation->benefitiary_name; $beneficiary_document_id = $vinculation->benefitiary_document_id; $beneficiary_document = $vinculation->benefitiary_document;
                                        $beneficiary_nationality = $vinculation->benefitiary_nationality_id; $beneficiary_address = $vinculation->benefitiary_address; $beneficiary_phone = $vinculation->benefitiary_phone; $beneficiary_relationship = $vinculation->benefitiary_relationship;
                                        $economic_activity_id = $vinculation->economic_activity_id;
                                        $work_name =  $vinculation->work_name; $work_address = $vinculation->work_address; $work_phone = $vinculation->work_phone;
                                        }else {
                                        $vinculationOther = \App\vinculation_form::where('customer_id','=',$customer[0]->id)->latest()->first();
                                        if($vinculationOther){
                                            if($vinculationOther->status_id == 1){ $disable_status = ''; }
                                            $birth_place = $vinculationOther->birth_place; $birth_date = $vinculationOther->birth_date; $nationality_id = $vinculationOther->nationality_id;
                                            $sector = $vinculationOther->address_zone; $main_road = $vinculationOther->main_road; $address_number = $vinculationOther->address_number;
                                            $secondary_road = $vinculationOther->secondary_road; $email = $vinculationOther->email; $secondary_email = $vinculationOther->secondary_email;
                                            $mobile_phone = $vinculationOther->mobile_phone; $phone = $vinculationOther->phone; $civil_state = $vinculationOther->civil_state; $city_id = $vinculationOther->city_id;
                                            $customer_id = $vinculationOther->customer_id; $sales_id = $vinculationOther->sales_id;
                                            $economic_activity = $vinculationOther->economic_activity_id; $other_economic_activity = $vinculationOther->economic_activity_other; $monthly_income = $vinculationOther->monthly_income;
                                            $monthly_outcome = $vinculationOther->monthly_outcome; $total_actives = $vinculationOther->total_actives; $total_pasives = $vinculationOther->total_pasives; 
                                            $other_monthly_income = $vinculationOther->other_monthly_income; $other_monthly_income_source = $vinculationOther->other_monthly_income_source;
                                            $person_exposed = $vinculationOther->person_exposed; $family_exposed = $vinculationOther->family_exposed;
                                            if($vinculationOther->picture_document_applicant == null){ $picture_document_applicant = ''; }else{ $picture_document_applicant = '<a href="'.$vinculationOther->picture_document_applicant.'" target="_blank"><img src="' . $vinculationOther->picture_document_applicant . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                                            if ($vinculationOther->picture_document_spouse == null) { $picture_document_spouse = ''; } else { $picture_document_spouse = '<a href="' . $vinculationOther->picture_document_spouse . '" target="_blank"><img src="' . $vinculationOther->picture_document_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                                            if($vinculationOther->picture_voting_ballot == null){ $picture_voting_ballot_applicant = ''; }else{ $picture_voting_ballot_applicant = '<a href="'.$vinculationOther->picture_voting_ballot.'" target="_blank"><img src="' . $vinculationOther->picture_voting_ballot . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                                            if($vinculationOther->picture_voting_ballot_spouse == null){ $picture_voting_ballot_spouse = ''; }else{ $picture_voting_ballot_spouse = '<a href="'.$vinculationOther->picture_voting_ballot_spouse.'" target="_blank"><img src="' . $vinculationOther->picture_voting_ballot_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                                            if($vinculationOther->picture_service == null){ $picture_service = ''; }else{ $picture_service = '<a href="'.$vinculationOther->picture_service.'" target="_blank"><img src="' . $vinculationOther->picture_service . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                                            $spouse_document = $vinculationOther->spouse_document; $spouse_name = $vinculationOther->spouse_name; $spouse_last_name = $vinculationOther->spouse_last_name; $annual_income = number_format($vinculationOther->annual_income,2); $spouse_document_id = $vinculationOther->spouse_document_id;
                                            $occupation = $vinculationOther->occupation; $other_annual_income = $vinculationOther->other_annual_income; $total_annual_income = $vinculationOther->total_annual_income;
                                            $description_other_income = $vinculationOther->description_other_income; $total_assets = $vinculation->total_actives; $total_assets_pasives = $vinculationOther->total_assets_pasives;
                                            $document_applicant_date = $vinculationOther->document_applicant_date; $document_spouse_date = $vinculationOther->document_spouse_date; $pep_client = $vinculationOther->family_exposed;
                                            $personal_reference_name = $vinculationOther->personal_reference_name; $personal_reference_relationship = $vinculationOther->personal_reference_relationship; $personal_reference_phone = $vinculationOther->personal_reference_phone;
                                            $commercial_reference_name = $vinculationOther->commercial_reference_name; $commercial_reference_amount = $vinculationOther->commercial_reference_amount; $commercial_reference_phone = $vinculationOther->commercial_reference_phone;
                                            $commercial_reference_bank_name = $vinculationOther->commercial_reference_bank_name; $commercial_reference_product = $vinculationOther->commercial_reference_product;
                                            $constitution_date = $vinculationOther->constitution_date; $legal_representative = \App\customerLegalRepresentative::find($vinculationOther->customer_legal_representative);
                                            $beneficiaryName = $vinculationOther->benefitiary_name; $beneficiary_document_id = $vinculationOther->benefitiary_document_id; $beneficiary_document = $vinculationOther->benefitiary_document;
                                            $beneficiary_nationality = $vinculationOther->benefitiary_nationality_id; $beneficiary_address = $vinculationOther->benefitiary_address; $beneficiary_phone = $vinculationOther->benefitiary_phone; $beneficiary_relationship = $vinculationOther->benefitiary_relationship;
                                            $economic_activity_id = $vinculationOther->economic_activity_id;
                                            $work_name =  $vinculationOther->work_name; $work_address = $vinculationOther->work_address; $work_phone = $vinculationOther->work_phone; $picture_sri = $vinculationOther->picture_sri;
                                            
                                            $vinculationOld = \App\vinculation_form::find($vinculationOther->id);
                                            
                                            //Copy Documents
                                            $vinculationNew = $vinculationOld->replicate();
                                            $vinculationNew->customer_id  = $vinculationOther->customer_id;
                                            $vinculationNew->sales_id = $sales_get;
                                            $vinculationNew->status_id = 6;
                                            $vinculationNew->save();
                                            
                                            //Create Dir
                                            mkdir(public_path('images/vinculation/'.$vinculationNew->id));
                                            
                                            $vinculactionUpdate = \App\vinculation_form::find($vinculationNew->id);
                                            if($picture_document_applicant != null) { $file = basename($vinculationOther->picture_document_applicant); $success = \File::copy(public_path($vinculationOther->picture_document_applicant), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_document_applicant = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                                            if ($picture_document_spouse != null) { $file = basename($vinculationOther->picture_document_spouse); $success = \File::copy(public_path($vinculationOther->picture_document_spouse), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_document_spouse = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                                            if ($picture_voting_ballot_applicant != null) { $file = basename($vinculationOther->picture_voting_ballot); $success = \File::copy(public_path($vinculationOther->picture_voting_ballot), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_voting_ballot = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                                            if ($picture_voting_ballot_spouse != null) { $file = basename($vinculationOther->picture_voting_ballot_spouse); $success = \File::copy(public_path($vinculationOther->picture_voting_ballot_spouse), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_voting_ballot_spouse = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                                            if ($picture_service != null) { $file = basename($vinculationOther->picture_service); $success = \File::copy(public_path($vinculationOther->picture_service), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_service = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                                            $vinculactionUpdate->save();
                            
                                        }
                                    }*/
                            
                                    //Obtain Province & Country
                                    if ($city_id) {
                                        $province = \App\city::find($city_id);
                                        if ($province) {
                                            $province_id = $province->province_id;
                                            $addressCities = \App\city::where('province_id', '=', $province_id)->get();
                                            /* Obtain Country */
                                            $province = \App\province::find($province_id);
                                            $countries = \App\country::find($province->country_id);
                                            $country_id = $countries->id;
                                        }
                                    }
                            //        dd($country_id);
                                    //Email Radio BTn
                                    if($secondary_email){ $optRadioYes = null; $optRadioNo = true; }else{ $optRadioYes = true; $optRadioNo = null; }
                                    
                                    //Economic Activities
                                    $economicActivities = \App\economic_activity::all();
                                    $occupationList = \App\economic_ocupation::all();
                                    
                                    //DOCUMENT
                                    //eyJpdiI6Ik5jT1FBTjRcL3d1OExHWU1wbmVJd0FnPT0iLCJ2YWx1ZSI6IlRRYnY3cFFRRkJINUFoTERBMUpSYkE9PSIsIm1hYyI6IjcyYTAyOGM1ZGI0N2ZlYmQ0NmQxZjZmMmIxMGQzZGYxNjBmY2M3MWFkYjhiM2I1ODQ0ZWJlYzk2MDEzYTE4YmQifQ==
                                    //SALE
                                    //eyJpdiI6IkNFQVNZdlJhV2lBSmh1VytLVW9LVWc9PSIsInZhbHVlIjoieHdnXC9OdVwvWXJUbGNNd0lsOEdoZ0lBPT0iLCJtYWMiOiJmNjg4Yjc5ODBlYzkyZTJiMGIzMzYyZDA2NTgyZjY0ZGI0MzJhYjA2NTU3MGUzMTVjOWQ0Y2FmZjlmNDkzY2I2In0=
                                    
                                    $documents = \App\document::all();
                                    $cities = \App\city::orderBy('name','ASC')->get();
                                    $countries = \App\country::all();
                                    $provinces = \App\province::orderBy('name','ASC')->get();
                                    $civisStates = \App\civilState::orderBy('name','ASC')->get();
                                    $migratoryStates = \App\migratoryState::orderBy('name','ASC')->get();
                                    
                                    if(isset($_GET['broker'])){
                                        $disable_status = 'disabled="disabled"';
                                    }
                                    
                                   /* if($vinculation->status_id == 1){
                                        $vinFormVersion = $vinculation->form_version;
                                        $checked = 'true';
                                    }else{
                                        $checked = null;
                                        $result = valorAseguradoSS($document_get);
                                        $saleData = \App\sales::find($sales_get);
                                        $valorAsegurado = $result['monto'] + $saleData->insured_value;
                                        if($valorAsegurado <= 50000){
                                            $vinFormVersion = 1;
                                        }elseif($valorAsegurado > 200000){
                                            $vinFormVersion = 4;
                                        }else{
                                            $vinFormVersion = 2;
                                        }
                                    }
                                    
                                    //Residency Country Only Ecuador
                                    $countryResidence = \App\country::where('id','=',1)->get();
                                    
                                    //Update Form Version
                                    $vinculatioUpdate = \App\vinculation_form::find($vinculation->id);
                                    $vinculatioUpdate->form_version = $vinFormVersion;
                                    $vinculatioUpdate->save();*/
                                    //Residency Country Only Ecuador
                                    $countryResidence = \App\country::where('id','=',1)->get();
                                    

   // $countries = [];
    //$cities = [];
    $civilStates = [];
    //$countriesResidence = [];
    //$provinces = [];
   // $migratoryStates = [];
    //$economicActivities = [];
    //$occupationList = [];

    

    return view('vinculation.pj.tercerosPerson.create', [
        'documents' => $documents,
        'customer' => $customer,
        'disabled' => $disabled,
        'emissionTypes'=>$emissionTypes,
        'sale_movement' => $sale_movement,
        'sale_id' => null,
        //'branch' => $branch,
        'secondary_email' => null,
        'economic_activity' => null,
        'other_monthly_income' => null,
        'civil_state' => null,
        'beneficiaryName' => null,
        'sales_id' => null,
        'nationality_id' => null,
        'disable_status' => null,
        'countries' => $countries,
        'birth_place' => null,
        'cities' => $cities,
        'birth_date' => null,
        'civilStates' => $civilStates,
        'countriesResidence' => $countryResidence,
        'provinces' => $provinces,
        'addressCities' =>$addressCities,
        'main_road' => null,
        'address_number' => null,
        'mobile_phone' => null,
        'migratoryStates' => $migratoryStates,
        'spouse_document' => null,
        'spouse_document_id' => null,
        'spouse_name' => null,
        'spouse_last_name' => null,
        'beneficiary_document' => null,
        'beneficiary_document_id' => null,
        'beneficiary_address' => null,
        'beneficiary_phone' => null,
        'beneficiary_relationship' => null,
        'economicActivities' => $economicActivities,
        'occupationList' => $occupationList,
        'annual_income' => null,
        'other_annual_income' => null,
        'total_annual_income' => null,
        'description_other_income' => null,
        'person_exposed' => null,
        'picture_document_applicant' => null,
        'document_applicant_date' => null,
        'picture_document_spouse' => null,
        'document_spouse_date' => null,
        'picture_service' => null,
        'checked' => null,
        'documents' => $documents, 'cities' => $cities, 
        'countries' => $countries,
        //'countriesResidence' => $countryResidence,
         'customer' => $customer[0], 'provinces' => $provinces,
            'civilStates' => $civisStates, 'migratoryStates' => $migratoryStates, 'birth_place' => $birth_place, 'birth_date' => $birth_date,
            'nationality_id' => $nationality_id, 'sector' => $sector, 'main_road' => $main_road, 'address_number' => $address_number,
            'secondary_road' => $secondary_road, 'email' => $email, 'secondary_email' => $secondary_email, 'mobile_phone' => $mobile_phone,
            'phone' => $phone, 'civil_state' => $civil_state, 'city_id' => $city_id, 'province_id' => $province_id,
            'country_id' => $country_id, 'addressCities' => $addressCities, 'optRadioYes' => $optRadioYes, 'optRadioNo' => $optRadioNo,
            'economicActivities' => $economicActivities,
             //'sales_id' => $sales_get,
              'disable_status' => $disable_status,
            'economic_activity' => $economic_activity, 'other_economic_activity' => $other_economic_activity, 'monthly_income' => $monthly_income,
            'monthly_outcome' => $monthly_outcome, 'total_actives' => $total_actives, 'total_pasives' => $total_pasives,
            'other_monthly_income' => $other_monthly_income, 'other_monthly_income_source' => $other_monthly_income_source, 'person_exposed' => $person_exposed, 'family_exposed' => $family_exposed, 'picture_document_applicant' => $picture_document_applicant,
            'picture_document_spouse' => $picture_document_spouse, 'picture_voting_ballot_applicant' => $picture_voting_ballot_applicant, 'picture_voting_ballot_spouse' => $picture_voting_ballot_spouse,
            'picture_service' => $picture_service, 'spouse_document' => $spouse_document, 'spouse_name' => $spouse_name,
            'spouse_last_name' => $spouse_last_name, 'annual_income' => $annual_income, 'spouse_document_id' => $spouse_document_id,
            'occupation' => $occupation, 'other_annual_income' => $other_annual_income, 'total_annual_income' => $total_annual_income,
            'description_other_income' => $description_other_income, 'total_assets' => $total_assets, 'total_pasives' => $total_pasives, 'total_assets_pasives' => $total_assets_pasives,
            'document_applicant_date' => $document_applicant_date, 'document_spouse_date' => $document_spouse_date, 'pep_client' => $pep_client,
            'beneficiaryName' => $beneficiaryName, 'beneficiary_document_id' => $beneficiary_document_id, 'beneficiary_document' => $beneficiary_document,
            'occupationList' => $occupationList, 'economic_activity_id' =>  $economic_activity_id,
            'beneficiary_nationality' => $beneficiary_nationality, 'beneficiary_address' => $beneficiary_address, 'beneficiary_phone' => $beneficiary_phone, 'beneficiary_relationship' => $beneficiary_relationship, 
            //'checked' => $checked
          

        ]);
    }
    public function storeTercerosPerson(request $request) {
        set_time_limit(120);
       $fecha = $request['fecha_constitucion'];
       $economic_activity = $request['economic_activity'];
       $razon_social = $request['razon_social'];
       $calle_principal = $request['calle_principal'];
       $numero_calle = $request['numero_calle'];
       $parroquia = $request['representante_parroquia'];
       $sector = $request['sector'];
       $calle_transversal = $request['calle_transversal'];
       $telefonos = $request['phones'];
       $celular = $request['celular'];
       $email = $request['email'];

       if(isset($request['ruc'])){
       $document = $request['ruc'];
       $document_id = 3;
       }
       else if(isset($request['pasaporte'])){
        $document = $request['pasaporte'];
        $document_id = 3;
       }
    
       // Save into companys
       $company = new \App\companys();
       $company->document_id = $document_id;
       $company->document= $document;
       $company->constitution_date = $fecha;
       $company->economic_activity_id = $economic_activity;
       $company->business_name = $razon_social;
       $company->address = $calle_principal;
       $company->address_number = $numero_calle;
       $company->parroquia = $parroquia;
       $company->sector = $sector;
       $company->phone = $telefonos;
       $company->mobile_phone = $celular;
       $company->email = $email;
       $company->cross_street = $calle_transversal;
       $company->save();

       //Save Representate legal
       $legal_representative = new \App\customerLegalRepresentative();

       $apellidos = $request['representante_apellidos'];
       $nombres = $request['representante_nombres'];
       $lugar_nacimiento = $request['representante_lugar_nacimiento'];
       $fecha_nacimiento = $request['representante_fecha_nacimiento'];
       $calle_principal = $request['representante_calle_principal'];
       $sector = $request['representante_sector'];

       if(isset($request['cedula'])){
        $document = $request['cedula'];
        $document_id = 1;
        }
        else if(isset($request['pasaporte'])){
         $document = $request['pasaporte'];
         $document_id = 3;
        }

        $legal_representative->last_name = $apellidos;
        $legal_representative->first_name = $nombres;

        $legal_representative->save();






       
    
        /*
        //Save or Update Company
        $companySql = 'select * from companys where document = "'. $request['data']['documentCompany'] . '"';
        $company = DB::select($companySql);
        
           $businessName = $request['data']['business_name'];
           $tradename = $request['data']['tradename'];
        //Validate Customer Save or Update
        if ($company) {
            $companyUpdate = \App\companys::find($company[0]->id);
            $companyUpdate->business_name  = $request['data']['business_name'];
            $companyUpdate->tradename = $request['data']['tradename'];
            $companyUpdate->save();
            $companyId = $companyUpdate->id;
            $companyDocument = $companyUpdate->document;
        } else {
            $companyNew = new \App\companys();
            $companyNew->document_id = $request['data']['documentCompanyid'];
            $companyNew->document= $request['data']['documentCompany'];
            $companyNew->business_name =str_replace('.','',$businessName);
            $companyNew->tradename = str_replace('.','',$tradename );
            $companyNew->save();
            $companyId = $companyNew->id;
            $companyDocument=$companyNew->document;
        }
        //Save or Update Legal Representative
        $legalRepresentativeSql = 'select * from customer_legal_representative where document = "'. $request['data']['documentNumber'] . '"';
        $legalRepresentative = DB::select($legalRepresentativeSql);
        if($legalRepresentative){
            $legalRepresentativeUpdate = \App\customerLegalRepresentative::find($legalRepresentative[0]->id);
            $legalRepresentativeUpdate->second_name=$request['data']['second_name'];
            $legalRepresentativeUpdate->second_last_name=$request['data']['second_last_name'];
            $legalRepresentativeUpdate->mobile_phone=$request['data']['mobile_phone'];
            $legalRepresentativeUpdate->email=$request['data']['email'];
            $legalRepresentativeUpdate->save();
            $legalRepresentativeId = $legalRepresentativeUpdate->id;
            $legalRepresentativePhone = substr($legalRepresentativeUpdate->mobile_phone, 1);
            $legalRepresentativeEmail = $legalRepresentativeUpdate->email;
            $legalRepresentativeDocument = $legalRepresentativeUpdate->document;
        }else{
            $legalRepresentativeNew= new \App\customerLegalRepresentative();
            $legalRepresentativeNew->document=$request['data']['documentNumber'];
            $legalRepresentativeNew->document_id=$request['data']['document_id'];
            $legalRepresentativeNew->first_name=$request['data']['first_name'];
            $legalRepresentativeNew->second_name=$request['data']['second_name'];
            $legalRepresentativeNew->last_name=$request['data']['last_name'];
            $legalRepresentativeNew->second_last_name=$request['data']['second_last_name'];
            $legalRepresentativeNew->mobile_phone=$request['data']['mobile_phone'];
            $legalRepresentativeNew->email=$request['data']['email'];
            $legalRepresentativeNew->save();
            $legalRepresentativeId = $legalRepresentativeNew->id;        
            $legalRepresentativePhone = substr($legalRepresentativeNew->mobile_phone, 1);
            $legalRepresentativeEmail = $legalRepresentativeNew->email;
            $legalRepresentativeDocument = $legalRepresentativeNew->document;
        }
           //Form variables
           $now = new \DateTime();
           $branch = $request['data']['branch'];
           $emissionType = $request['data']['emissionType'];
           $insuredValue = $request['data']['insuredValue'];
           $netPremium = $request['data']['netPremium'];
           $product=$request['data']['product'];
           //Products by Channel
    //       $pbc = DB::table('products_channel')->latest('created_at')->first();
           
           //GET PBD ID BY RAMO
           $agency_id=\Auth::user()->agen_id;
           $pbc = \App\product_channel::where('products_channel.product_id','=',$product)
                                        ->where('products_channel.agency_id','=',$agency_id)
                                        ->get();   
    
           //Store new SALES
           $massivesVinculationLegalPersonNew= new \App\sales();
           $massivesVinculationLegalPersonNew->agen_id = $agency_id;
           $massivesVinculationLegalPersonNew->user_id = \Auth::user()->id;
           $massivesVinculationLegalPersonNew->company_id = $companyId;
           $massivesVinculationLegalPersonNew->customer_legal_representative_id= $legalRepresentativeId;
           $massivesVinculationLegalPersonNew->pbc_id = $pbc[0]->id;
           $massivesVinculationLegalPersonNew->status_id = 23;
           $massivesVinculationLegalPersonNew->emission_date = $now;
           $massivesVinculationLegalPersonNew->sales_type_id = 7;
           $massivesVinculationLegalPersonNew->branch = $branch;
           $massivesVinculationLegalPersonNew->emission_type_id = $emissionType;
           $massivesVinculationLegalPersonNew->insured_value = str_replace(',','',$insuredValue);
           $massivesVinculationLegalPersonNew->net_premium = str_replace(',','',$netPremium);
           $massivesVinculationLegalPersonNew->total = str_replace(',','',$netPremium);
           $massivesVinculationLegalPersonNew->insured_value = str_replace(',','',$insuredValue);
           $massivesVinculationLegalPersonNew->save();
           
            //Vinculation_form
            $vinculationLegalPerson = new \App\vinculation_form();
            $vinculationLegalPerson->company_id = $companyId;
            $vinculationLegalPerson->customer_legal_representative_id = $legalRepresentativeId;
            $vinculationLegalPerson->sales_id = $massivesVinculationLegalPersonNew->id;
            $vinculationLegalPerson->status_id = 6;
            $vinculationLegalPerson->mobile_phone = $request['data']['mobile_phone'];
            $vinculationLegalPerson->email = $request['data']['email'];
            $vinculationLegalPerson->save();
            
            \Session::flash('SendEmailMessage', '  Se envío el link del Formulario a su correo');
            */
            //Send Link Vinculation Form Email
          //$job = (new \App\Jobs\VinculationLegalPersonSendLinkEmailJobs($massivesVinculationLegalPersonNew->id, $legalRepresentativeEmail, $legalRepresentativeDocument,$companyDocument));
           //dispatch($job);
           return $fecha;
    }
}
