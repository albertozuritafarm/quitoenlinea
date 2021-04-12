<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Validator;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Gallib\ShortUrl\Facades\ShortUrl;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class VinculationController extends Controller
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
    
    public function createPayer(){
        //Variables are Set
//        if (!isset($_GET['document']) || !isset($_GET['sales'])) { \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo.'); return view('home'); }else{ $document_get = Crypt::decryptString($_GET['document']); $sales_get = Crypt::decryptString($_GET['sales']); }
        if (!isset($_GET['document']) || !isset($_GET['sales'])) { \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo.'); return view('home'); }else{ $document_get = decrypt($_GET['document']); $sales_get = decrypt($_GET['sales']); }

        //Initiate Variables
        $birth_place = null; $birth_date = null; $nationality_id = null;
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
        $spouse_document = null; $spouse_name = null; $spouse_last_name = null; $spouse_document_id = null;

        //Validate Document
        $customer = \App\customers::where('document','=',$document_get)->get(); if($customer->isEmpty()){ \Session::flash('ValidateUserRoute', 'Por favor solicite un nuevo enlace1.'); return view('home'); }

        //Validate Sales
        $Sales = \App\sales::where('id','=',$sales_get)->get();
        if($Sales->isEmpty()){ \Session::flash('ValidateUserRoute', 'Por favor solicite un nuevo enlace2.'); return view('home'); }
        
        $vinculation = \App\vinculation_form::where('customer_id','=',$customer[0]->id)->where('sales_id','=',$sales_get)->latest()->first();
        if($vinculation){
            if($vinculation->status_id == 1){ $disable_status = 'readonly="readonly"'; }
            $birth_place = $vinculation->birth_place; $birth_date = $vinculation->birth_date; $nationality_id = $vinculation->nationality_id;
            $sector = $vinculation->address_zone; $main_road = $vinculation->main_road; $address_number = $vinculation->address_number;
            $secondary_road = $vinculation->secondary_road; $email = $vinculation->email; $secondary_email = $vinculation->secondary_email;
            $mobile_phone = $vinculation->mobile_phone; $phone = $vinculation->phone; $civil_state = $vinculation->civil_state; $city_id = $vinculation->city_id;
            $customer_id = $vinculation->customer_id; $sales_id = $vinculation->sales_id;
            $economic_activity = $vinculation->economic_activity_id; $other_economic_activity = $vinculation->economic_activity_other; $monthly_income = $vinculation->monthly_income;
            $monthly_outcome = $vinculation->monthly_outcome; $total_actives = $vinculation->total_actives; $total_pasives = $vinculation->total_pasives; 
            $other_monthly_income = $vinculation->other_monthly_income; $other_monthly_income_source = $vinculation->other_monthly_income_source;
            $person_exposed = $vinculation->person_exposed; $family_exposed = $vinculation->family_exposed;
            if($vinculation->picture_document_applicant == null){ $picture_document_applicant = ''; }else{ $picture_document_applicant = '<a href="'.$vinculation->picture_document_applicant.'" target="_blank"><img src="' . $vinculation->picture_document_applicant . '" class="img-thumbnail" width="300" height="300" /></a>'; }
            if ($vinculation->picture_document_spouse == null) { $picture_document_spouse = ''; } else { $picture_document_spouse = '<a href="' . $vinculation->picture_document_spouse . '" target="_blank"><img src="' . $vinculation->picture_document_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; }
            if($vinculation->picture_voting_ballot == null){ $picture_voting_ballot_applicant = ''; }else{ $picture_voting_ballot_applicant = '<a href="'.$vinculation->picture_voting_ballot.'" target="_blank"><img src="' . $vinculation->picture_voting_ballot . '" class="img-thumbnail" width="300" height="300" /></a>'; }
            if($vinculation->picture_voting_ballot_spouse == null){ $picture_voting_ballot_spouse = ''; }else{ $picture_voting_ballot_spouse = '<a href="'.$vinculation->picture_voting_ballot_spouse.'" target="_blank"><img src="' . $vinculation->picture_voting_ballot_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; }
            if($vinculation->picture_service == null){ $picture_service = ''; }else{ $picture_service = '<a href="'.$vinculation->picture_service.'" target="_blank"><img src="' . $vinculation->picture_service . '" class="img-thumbnail" width="300" height="300" /></a>'; }
            $spouse_document = $vinculation->spouse_document; $spouse_name = $vinculation->spouse_name; $spouse_last_name = $vinculation->spouse_last_name; $spouse_document_id = $vinculation->spouse_document_id;
        }else {
            $vinculationOther = \App\vinculation_form::where('customer_id','=',$customer[0]->id)->where('status_id','=','1')->latest()->first();
//            return $vinculationOther;
            if($vinculationOther){
                if($vinculationOther->status_id == 1){ $disable_status = 'readonly="readonly"'; }
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
                $spouse_document = $vinculationOther->spouse_document; $spouse_name = $vinculationOther->spouse_name; $spouse_last_name = $vinculation->spouse_last_name; $spouse_document_id = $vinculation->spouse_document_id;
            }
        }
        
        //Obtain Province
        if ($city_id) { $province = \App\city::find($city_id); if ($province) { $province_id = $province->province_id; $addressCities = \App\city::where('province_id','=',$province_id)->get(); } }

        //Email Radio BTn
        if($secondary_email){ $optRadioYes = null; $optRadioNo = true; }else{ $optRadioYes = true; $optRadioNo = null; }
        
        //Economic Activities
        $economicActivities = \App\economic_activity::all();
        
        //DOCUMENT
        //eyJpdiI6Ik5jT1FBTjRcL3d1OExHWU1wbmVJd0FnPT0iLCJ2YWx1ZSI6IlRRYnY3cFFRRkJINUFoTERBMUpSYkE9PSIsIm1hYyI6IjcyYTAyOGM1ZGI0N2ZlYmQ0NmQxZjZmMmIxMGQzZGYxNjBmY2M3MWFkYjhiM2I1ODQ0ZWJlYzk2MDEzYTE4YmQifQ==
        //SALE
        //eyJpdiI6IkNFQVNZdlJhV2lBSmh1VytLVW9LVWc9PSIsInZhbHVlIjoieHdnXC9OdVwvWXJUbGNNd0lsOEdoZ0lBPT0iLCJtYWMiOiJmNjg4Yjc5ODBlYzkyZTJiMGIzMzYyZDA2NTgyZjY0ZGI0MzJhYjA2NTU3MGUzMTVjOWQ0Y2FmZjlmNDkzY2I2In0=
        
        $documents = \App\document::all();
        $cities = \App\city::all();
        $countries = \App\country::all();
        $provinces = \App\province::orderBy('name','ASC')->get();
        $civisStates = \App\civilState::orderBy('name','ASC')->get();
        $migratoryStates = \App\migratoryState::orderBy('name','ASC')->get();
        
        return view('vinculation.createPayer',[
            'documents' => $documents,
            'cities' => $cities,
            'countries' => $countries,
            'customer' => $customer[0],
            'provinces' => $provinces,
            'civilStates' => $civisStates,
            'migratoryStates' => $migratoryStates,
            'birth_place' => $birth_place,
            'birth_date' => $birth_date,
            'nationality_id' => $nationality_id,
            'sector' => $sector,
            'main_road' => $main_road, 
            'address_number' => $address_number,
            'secondary_road' => $secondary_road,
            'email' => $email, 
            'secondary_email' => $secondary_email,
            'mobile_phone' => $mobile_phone,
            'phone' => $phone, 
            'civil_state' => $civil_state,
            'city_id' => $city_id,
            'province_id' => $province_id,
            'addressCities' => $addressCities,
            'optRadioYes' => $optRadioYes,
            'optRadioNo' => $optRadioNo,
            'economicActivities' => $economicActivities,
            'sales_id' => $sales_get,
            'disable_status' => $disable_status,
            'economic_activity' => $economic_activity,
            'other_economic_activity' => $other_economic_activity,
            'monthly_income' => $monthly_income,
            'monthly_outcome' => $monthly_outcome,
            'total_actives' => $total_actives,
            'total_pasives' => $total_pasives,
            'other_monthly_income' => $other_monthly_income,
            'other_monthly_income_source' => $other_monthly_income_source,
            'person_exposed' => $person_exposed,
            'family_exposed' => $family_exposed,
            'picture_document_applicant' => $picture_document_applicant,
            'picture_document_spouse' => $picture_document_spouse,
            'picture_voting_ballot_applicant' => $picture_voting_ballot_applicant,
            'picture_voting_ballot_spouse' => $picture_voting_ballot_spouse,
            'picture_service' => $picture_service,
            'spouse_document' => $spouse_document,
            'spouse_name' => $spouse_name,
            'spouse_last_name' => $spouse_last_name,
            'spouse_document_id' => $spouse_document_id
        ]);
    }

    public function firstStepForm(request $request){
//        return $request;
        //Store Vinculation Data
        //return $request['fecha_consitucion'];
        $vinculationSearch = \App\vinculation_form::where('customer_id','=',$request['documentId'])->where('sales_id','=',$request['saleId'])->latest()->first();
        if($vinculationSearch){
            if($vinculationSearch->status_id == 1){
                //NADA
            }else{
                $customer = \App\customers::find($request['documentId']);
                $customer->phone = $request['phone'];
                $customer->mobile_phone = $request['mobile_phone'];
                $customer->city_id = $request['city'];
                $customer->save();
                
                //V1
                $vinculation = \App\vinculation_form::find($vinculationSearch->id);
                $vinculation->nationality_id = $request['nationality'];
                $vinculation->birth_place = $request['birth_city'];
                $vinculation->birth_date = $request['birth_date'];
                $vinculation->civil_state = $request['civilState'];
                $vinculation->city_id = $request['city'];
                $vinculation->main_road = $request['main_road'];
                $vinculation->secondary_road = $request['secondary_road'];
                $vinculation->address_number = $request['number'];
                $vinculation->address_zone = $request['sector'];
                $vinculation->mobile_phone = $request['mobile_phone'];
                $vinculation->phone = $request['phone'];
                $vinculation->email = $request['email'];
                $vinculation->spouse_document = $request['spouseDocument'];
                $vinculation->spouse_name = $request['spouseFirstName'];
                $vinculation->spouse_last_name = $request['spouseLastName'];
                $vinculation->spouse_document_id = $request['spouse_document_id'];
                
                $vinculation->secondary_email = $request['email_secondary'];
                $vinculation->passport_number = $request['passportNumber'];
                $vinculation->begin_date = $request['passportBeginDate'];
                $vinculation->end_date = $request['passportEndDate'];
                $vinculation->migration_status_id = $request['migratoryState'];
                $vinculation->entry_date = $request['passportEntryDate'];
                $vinculation->status_id = 6;
                $vinculation->sales_id = $request['saleId'];
                $vinculation->customer_id = $request['documentId'];
                $vinculation->benefitiary_name = $request['beneficiaryName'];
                $vinculation->benefitiary_document_id = $request['beneficiary_nationality'];
                $vinculation->benefitiary_document = $request['beneficiary_document'];
                $vinculation->benefitiary_phone = $request['beneficiary_phone'];
                $vinculation->benefitiary_nationality_id = $request['beneficiary_nationality'];
                $vinculation->benefitiary_address = $request['beneficiary_address'];
                $vinculation->benefitiary_relationship = $request['beneficiary_relationship'];
                $vinculation->save();
            }
        }else{
            $customer = \App\customers::find($request['documentId']);
            $customer->phone = $request['phone'];
            $customer->mobile_phone = $request['mobile_phone'];
            $customer->city_id = $request['city'];
            $customer->save();
            
            $vinculation = new \App\vinculation_form();
            $vinculation->nationality_id = $request['nationality'];
            $vinculation->birth_place = $request['birth_city'];
            $vinculation->birth_date = $request['birth_date'];
            $vinculation->civil_state = $request['civilState'];
            $vinculation->city_id = $request['city'];
            $vinculation->main_road = $request['main_road'];
            $vinculation->secondary_road = $request['secondary_road'];
            $vinculation->address_number = $request['number'];
            $vinculation->address_zone = $request['sector'];
            $vinculation->mobile_phone = $request['mobile_phone'];
            $vinculation->phone = $request['phone'];
            $vinculation->email = $request['email'];
            $vinculation->spouse_document = $request['spouseDocument'];
            $vinculation->spouse_name = $request['spouseFirstName'];
            $vinculation->spouse_last_name = $request['spouseLastName'];
            $vinculation->spouse_document_id = $request['spouse_document_id'];

            $vinculation->secondary_email = $request['email_secondary'];
            $vinculation->passport_number = $request['passportNumber'];
            $vinculation->begin_date = $request['passportBeginDate'];
            $vinculation->end_date = $request['passportEndDate'];
            $vinculation->migration_status_id = $request['migratoryState'];
            $vinculation->entry_date = $request['passportEntryDate'];
            $vinculation->status_id = 6;
            $vinculation->sales_id = $request['saleId'];
            $vinculation->customer_id = $request['documentId'];
            $vinculation->benefitiary_name = $request['beneficiaryName'];
            $vinculation->benefitiary_document_id = $request['beneficiary_nationality'];
            $vinculation->benefitiary_document = $request['beneficiary_document'];
            $vinculation->benefitiary_phone = $request['beneficiary_phone'];
            $vinculation->benefitiary_nationality_id = $request['beneficiary_nationality'];
            $vinculation->benefitiary_address = $request['beneficiary_address'];
            $vinculation->benefitiary_relationship = $request['beneficiary_relationship'];
            $vinculation->save();
            
            
            
        }
    }
    
    public function secondStepForm(request $request){
//        return $request;
        //Store Vinculation Data
        $vinculationSearch = \App\vinculation_form::where('customer_id','=',$request['documentId'])->where('sales_id','=',$request['saleId'])->latest()->first();
        if($vinculationSearch){
            if($vinculationSearch->status_id == 1 || $vinculationSearch->status_id == 23){
                //NADA
            }else{
                //Validate Amounts 
                if(isset($request['annual_income'])){ $annualIncome = str_replace(',','',$request['annual_income']); } else{ $annualIncome = null; }
                if(isset($request['other_annual_income'])){ $otherAnnualIncome = str_replace(',','',$request['other_annual_income']); } else{ $otherAnnualIncome = null; }
                if(isset($request['total_annual_income'])){ $totalAnnualIncome = str_replace(',','',$request['total_annual_income']); } else{ $totalAnnualIncome = null; }
                if(isset($request['total_assets'])){ $totalAssets = str_replace(',','',$request['total_assets']); } else{ $totalAssets = null; }
                if(isset($request['total_passives'])){ $totalPassives = str_replace(',','',$request['total_passives']); } else{ $totalPassives = null; }
                if(isset($request['total_assets_pasives'])){ $totalAssetsPassives = str_replace(',','',$request['total_assets_pasives']); } else{ $totalAssetsPassives = null; }
                if(isset($request['commercial_reference_amount'])){ $comercialReferenceAmount = str_replace(',','',$request['commercial_reference_amount']); } else{ $comercialReferenceAmount = null; }
                if(isset($request['monthly_income'])){ $monthlyIncome = str_replace(',','',$request['monthly_income']); } else{ $monthlyIncome = null; }
                if(isset($request['monthly_outcome'])){ $monthlyOutcome = str_replace(',','',$request['monthly_outcome']); } else{ $monthlyOutcome = null; }
                if(isset($request['other_monthly_income'])){ $otherMonthlyIncome = str_replace(',','',$request['other_monthly_income']); } else{ $otherMonthlyIncome = null; }
                
                $vinculation = \App\vinculation_form::find($vinculationSearch->id);
                $vinculation->occupation = $request['occupation'];
                $vinculation->annual_income = $annualIncome;
                $vinculation->other_annual_income = $otherAnnualIncome;
                $vinculation->total_annual_income = $totalAnnualIncome;
                $vinculation->description_other_income = $request['description_other_income'];
                $vinculation->total_actives = $totalAssets;
                $vinculation->total_pasives = $totalPassives;
                $vinculation->total_assets_pasives = $totalAssetsPassives;
                $vinculation->personal_reference_name = $request['personal_reference_name'];
                $vinculation->personal_reference_relationship = $request['personal_reference_relationship'];
                $vinculation->personal_reference_phone = $request['personal_reference_phone'];
                $vinculation->commercial_reference_name = $request['commercial_reference_name'];
                $vinculation->commercial_reference_amount = $comercialReferenceAmount;
                $vinculation->commercial_reference_phone = $request['commercial_reference_phone'];
                $vinculation->commercial_reference_bank_name = $request['commercial_reference_bank_name'];
                $vinculation->commercial_reference_product = $request['commercial_reference_product'];
                
                $vinculation->economic_activity_id = $request['economic_activity'];
                $vinculation->monthly_income = $monthlyIncome;
                $vinculation->monthly_outcome = $monthlyOutcome;
                $vinculation->other_monthly_income = $otherAnnualIncome;
                $vinculation->other_monthly_income_source = $request['other_monthly_income_source'];
                $vinculation->economic_activity_other = $request['other_economic_activity'];
                $vinculation->work_name = $request['work_name'];
                $vinculation->work_phone = $request['work_phone'];
                $vinculation->work_address = $request['work_address'];
                $vinculation->save();
            }
        }
    }
    
    public function thirdStepForm(request $request){
        //Store Vinculation Data
        $vinculationSearch = \App\vinculation_form::where('customer_id','=',$request['documentId'])->where('sales_id','=',$request['saleId'])->latest()->first();
        if($vinculationSearch){
            if($vinculationSearch->status_id == 1){
                //NADA
            }else{
                $vinculation = \App\vinculation_form::find($vinculationSearch->id);
                $vinculation->person_exposed = $request['optradio3'];
                $vinculation->family_exposed = $request['pep_client'];
                $vinculation->save();
            }
        }
    }
    
    public function upload(request $request){
        $validation = Validator::make($request->all(), [
            'select_file'.$request['uploadType'] => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($validation->passes()) {
//        return $request;
            $vinculationSearch = \App\vinculation_form::where('customer_id','=',$request['documentId'])->where('sales_id','=',$request['saleId'])->latest()->first();
            if($vinculationSearch){
                if($vinculationSearch->status_id == 1 || $vinculationSearch->status_id == 23){
                    //NADA
                }else{
                    //Vehicle Folder
                    $vehiFolder = public_path('images/vinculation/'.$vinculationSearch->id.'/');
                    //Create Vehicle Folder
                    if (!file_exists($vehiFolder)) {
                        mkdir($vehiFolder, 0777, true);
                    }

                    $image = $request->file('select_file'.$request['uploadType']);
                    $new_name = rand() . '_'.$request['uploadType'].'.' . $image->getClientOriginalExtension();
                    
                    $name = 'Images/Vinculation/'.$vinculationSearch->sales_id.'/'.$new_name;
                    $path = Storage::disk('s3')->put($name, file_get_contents($image));
                    
                    $url = Storage::disk('s3')->url($name);
                                        
                    $vinculation = \App\vinculation_form::find($vinculationSearch->id);
                    if($request['uploadType'] == 'DocumentApplicant'){ $vinculation->picture_document_applicant  = $url; $vinculation->save(); $picture = $vinculation->picture_document_applicant; }
                    if($request['uploadType'] == 'DocumentSpouse'){ $vinculation->picture_document_spouse  = $url; $vinculation->save(); $picture = $vinculation->picture_document_spouse; }
                    if($request['uploadType'] == 'VotingBallotApplicant'){ $vinculation->picture_voting_ballot  = $url; $vinculation->save(); $picture = $vinculation->picture_voting_ballot; }
                    if($request['uploadType'] == 'VotingBallotSpouse'){ $vinculation->picture_voting_ballot_spouse  = $url; $vinculation->save(); $picture = $vinculation->picture_voting_ballot_spouse; }
                    if($request['uploadType'] == 'Service'){ $vinculation->picture_service  = $url; $vinculation->save(); $picture = $vinculation->picture_service; }
                    if($request['uploadType'] == 'Sri'){ $vinculation->picture_sri  = $url; $vinculation->save(); $picture = $vinculation->picture_sri; }
                    
                    if($image->getClientOriginalExtension() == 'pdf'){
                        return response()->json([
                                    'message' => 'Image Upload Successfully',
                                    'uploaded_image' => '<a href="'.$url.'" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" /></a>',
                                    'class_name' => 'alert-success',
                                    'Success' => 'true'
                        ]);
                    }else{
                        return response()->json([
                                    'message' => 'Image Upload Successfully',
                                    'uploaded_image' => '<a href="'.$url.'" target="_blank"><img src="' . $url . '" class="img-thumbnail" width="300" /></a>',
                                    'class_name' => 'alert-success',
                                    'Success' => 'true'
                        ]);
                    }
                }
            }
                        
        } else {
            return response()->json([
                        'message' => 'El documento debe ser en formato jpeg, png, jpg ó pdf y pesar un maximo de 2MB',
                        'uploaded_image' => '',
                        'class_name' => 'alert-danger',
                        'vSalId' => $request->vSalId,
                        'Success' => 'false'
            ]);
        }
    }
    
    public function delete(request $request){
        $vinculationSearch = \App\vinculation_form::where('customer_id','=',$request['data']['customer'])->where('sales_id','=',$request['data']['sale'])->latest()->first();
            if($vinculationSearch){
                if($vinculationSearch->status_id == 1 || $vinculationSearch->status_id == 23){
                    //NADA
                }else{
                    $vinculation = \App\vinculation_form::find($vinculationSearch->id);
                    if ($request['data']['id'] == 'DocumentApplicant') { Storage::disk('s3')->delete($vinculation->picture_document_applicant); $vinculation->picture_document_applicant = null; $vinculation->save(); }
                    if ($request['data']['id'] == 'DocumentSpouse') { Storage::disk('s3')->delete($vinculation->picture_document_spouse); $vinculation->picture_document_spouse = null; $vinculation->save(); }
                    if ($request['data']['id'] == 'VotingBallotApplicant') { Storage::disk('s3')->delete($vinculation->picture_voting_ballot); $vinculation->picture_voting_ballot = null; $vinculation->save(); }
                    if ($request['data']['id'] == 'VotingBallotSpouse') { Storage::disk('s3')->delete($vinculation->picture_voting_ballot_spouse); $vinculation->picture_voting_ballot_spouse = null; $vinculation->save(); }
                    if ($request['data']['id'] == 'Service') { Storage::disk('s3')->delete($vinculation->picture_service); $vinculation->picture_service = null; $vinculation->save(); }

                return response()->json([
                        'message' => 'Image Upload Successfully',
                        'uploaded_image' => '>',
                        'class_name' => 'alert-success',
                        'Success' => 'true'
                    ]);
                }
            }
    }
    
    public function complete(request $request){
        $sale = $request['saleId'];
//        $userEmail = \App\User::selectRaw('users.email')->join('sales','sales.user_id','=','users.id')->where('sales.id','=',$sale) ->get();
//        $job = (new \App\Jobs\VinculationCompleteEmailJobs($sale, $userEmail[0]->email));
//        dispatch($job);

//        $customer = \App\customers::where('document','=',$request['customerId'])->get();
//        return $customer;
        $vinculationSearch = \App\vinculation_form::where('customer_id','=',$request['customerId'])->where('sales_id','=',$sale)->get();
        $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
//        $vinculation->status_id = 1;
        $vinculation->document_applicant_date = $request['documentApplicantDate']; 
        $vinculation->document_spouse_date = $request['documentSpouseDate']; 
        $vinculation->viamatica_date = new \DateTime();
        $vinculation->save();

        //Variables for Vinculation PDF
        $sales = \App\sales::find($sale);
        $customer = \App\customers::find($sales->customer_id);
        $birthCountry = \App\country::find($vinculation->nationality_id);
        $birthCity = \App\city::find($vinculation->birth_place);
        $city = \App\city::find($vinculation->city_id);
        $province = \App\province::find($city->province_id);
        $country = \App\country::find($province->country_id);
        $broker = \App\sales::selectRaw('agent_ss.agentedes, agent_ss.agentedes as "canalnegodes", pbc.ejecutivo_ss as "ejecutivo", IF(channels.canalnegoid = 1, "DIRECTO", "BROKER") as "canal", CONCAT(usr.first_name," ",usr.last_name) as "ejecutivo_ss", agen.puntodeventades, pro.ramodes, sales.insured_value,agen.channel_id as"channelId"')
                                ->join('agencies as agen','agen.id','=','sales.agen_id')
                                ->join('channels','channels.id','=','agen.channel_id')
                                ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                                ->join('products as pro','pro.id','=','pbc.product_id')
                                ->join('agent_ss','agent_ss.id','=','pbc.agent_ss')
                                ->join('users as usr','usr.id','=','sales.user_id')
                                ->where('sales.id','=',$sales->id)
                                ->get();

        $ecoActivity=\App\economic_activity::find($vinculation->economic_activity_id);
        $occupation=\App\economic_ocupation::find($vinculation->occupation);
        
        $viamaticaDate = date_format($vinculation->viamatica_date, 'd-m-Y');

        $pdf = PDF::loadView('vinculation.pdf',[
                    'customer' => $customer,
                    'sales' => $sales,
                    'vinculation' => $vinculation,
                    'ecoActivity' => $ecoActivity,
                    'occupation' => $occupation,
                    'birthCountry' => $birthCountry,
                    'birthCity' => $birthCity,
                    'city' => $city,
                    'province' => $province,
                    'country' => $country,
                    'broker' => $broker[0],
                    'viamaticaDate' => $viamaticaDate
        ]);
        
        $output = $pdf->output();
        file_put_contents(public_path('vinculacion.pdf'), $output);
        
        $b64Doc = chunk_split(base64_encode(file_get_contents(public_path('vinculacion.pdf'))));
        
        $result = viamaticaSendPdf($customer->document, $customer->first_name, $customer->mobile_phone, $sales->id, $b64Doc, $customer->email, $customer->phone, $vinculation->id);
        
        $vinculationUpdate = \App\vinculation_form::find($vinculationSearch[0]->id);
        $vinculationUpdate->viamatica_id = $result['data'][0];
        $vinculationUpdate->save();
        
        return $result;
        
    }
    
    public function sendLink(request $request){

        $saleId = decrypt($request['saleId']);
        
        $sale = \App\sales::find($saleId);
        
        $customer = \App\customers::find($sale->customer_id);
        
        //Send Link Vinculation Form Email
        $job = (new \App\Jobs\VinculationSendLinkEmailJobs($sale->id, $customer->email, $customer->document));
        dispatch($job);
    }
    
    public function confirmComplete(request $request){
        return 'success';
        //Vinculation
        $sale = decrypt($request['saleId']);
        $vinculationSearch = \App\vinculation_form::where('sales_id','=',$sale)->get();
        
        //Payment
        $charge = \App\Charge::where('sales_id','=',$sale)->get();
        if($vinculationSearch->isEmpty() || $charge->isEmpty()){
            return 'Debe completar el Formulario de Vinculación';
        }else{
            if($vinculationSearch[0]->status_id == 1){
                if($charge[0]->status_id == 8){
                    return 'La poliza no se encuentra paga';
                }else{
                    return 'success';
                }
            }else{
                return 'Debe completar el Formulario de Vinculación';
            }
        }
    }
    
    public function tokenGenerate(request $request){
        //Store Vinculation Data
        $vinculationSearch = \App\vinculation_form::where('customer_id','=',$request['customerId'])->where('sales_id','=',$request['saleId'])->latest()->first();
        if($vinculationSearch){
            if($vinculationSearch->status_id == 1){
                //NADA
            }else{
                $vinculation = \App\vinculation_form::find($vinculationSearch->id);
                $vinculation->document_applicant_date = $request['documentApplicantDate'];
                $vinculation->document_spouse_date = $request['documentSpouseDate'];
                $vinculation->save();
            }
            //Obtain Json Data
            $sale = \App\sales::find($request['saleId']);
            $customer = \App\customers::find($sale->customer_id);
            $browser = obtainBrowser();

            generateTokenViamatica($sale->id, $customer->document, $customer->email, $customer->mobile_phone, $browser);
        }
    }
    
    public function tokenValidate(request $request){
        //Obtain Json Data
        $sale = \App\sales::find($request['saleId']);
        $customer = \App\customers::find($sale->customer_id);
        
        $responseCode = validateTokenViamatica($sale->id, $customer->document, $request['tokenCode']);
        
        return $responseCode;
    }
    
    public function send(request $request){
        //Update Customer Contact Data
        $customerSearch = \App\customers::find($request['customerId']);
        $customerSearch->mobile_phone = $request['mobilePhone'];
        $customerSearch->email = $request['email'];
        $customerSearch->save();
        
        //Update Sale Status
        $sale = \App\sales::find($request['saleId']);
        $sale->status_id = 23;
        $sale->save();
        
        //Vinculation Status
        $vinculationSearch = \App\vinculation_form::where('sales_id','=',$sale->id)->get();
        $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
        $vinculation->status_id = 6;
        $vinculation->save();
        
        //Send Link Vinculation Form
        $job = (new \App\Jobs\VinculationSendLinkEmailJobs($sale->id, $customerSearch->email, $customerSearch->document));
        dispatch($job);
    }

    public function validateDocument(request $request) {
        if (!validateId($request['data']['document'])) {
            return 'invalid';
        } else {
            return 'valid';
        }
    }
    
    public function pdf(){
//        \App\Jobs\emisionJob::dispatch(1749);
//        dd('hola');
//        \App\Jobs\listaObservadosyCarteraJobs::dispatch();
//        $emailData = \App\sales::selectRaw('usr.email, cus.document')
//                                    ->join('customers as cus','cus.id','=','sales.customer_id')
//                                    ->join('users as usr','usr.id','=','sales.user_id')
//                                    ->where('sales.id','=',1453)
//                                    ->get();
//        return $emailData;
//        \App\Jobs\paymentAceptedUserEmailJobs::dispatch(1453,'carlos.oberto88@gmail.com',1757612005);
//        dd('aqui');
//        $result = documentosPolizaSS(1583);
//        return $result;
//        $result = carteraVencidaSS('175761204');
//        if($result['error'][0]['code'] == '003'){
//            $carteraVencida = false;
//        }else{
//            if($resultCarteraVencida['carteravencida'] == 'false'){
//                $carteraVencida = false;
//            }else{
//                $carteraVencida = true;
//            }
//        }
//        if($carteraVencida == false){
//            return 'false';
//        }else{
//            return 'true';
//        }
//        \App\Jobs\vinculaClientesJob::dispatch(1407);
//        dd('aqui');
//        $result = sftpPaymentsSS();
//        return $result;
//        $name = 'test321.pdf';
//        $path = Storage::disk('s3')->put('Vinculation/'.$name, public_path('cotization.pdf'));
//        return $path;
//        $s3 = \AWS::createClient('s3');
//        $s3->putObject(array(
//            'Bucket' => 'filesmagnusmas',
//            'Key' => 'Vinculation/prueba.pdf',
//            'SourceFile' => public_path('cotizacion.pdf'),
//        ));
//        $result = $s3->deleteObject(array(
//            'Bucket' => 'filesmagnusmas',
//            'Key'    => 'Vinculation/prueba.pdf'
//        ));
//        return $result;
//        dd('aqui');
//        $result = uploadFilesSftpSS(2);
//        return $result;
//        $email = new \App\Mail\QuotationR1Email('1494', '1757612005');
//        return $email;
//        Mail::to('carlos.oberto88@gmail.com')->send($email);
//        return 'aqui';
//        $result = vinculaClienteeSS(1407);
//        $result = emisionSS(1450); //VEHICULOS
//        $result = emisionSS(1440); //INCENDIO 
//        $result = emisionSS(1401); //VIDA -- CAMBIAR PRIMA VALUE QUE NO SE ESTA GUARDANDO
//        return $result;
        //-- CAMBIAR PRIMA VALUE QUE NO SE ESTA GUARDANDO
 //       return $result;
        set_time_limit(300);
        $customer = \App\customers::find(144);
        $sales = \App\sales::find(1578);
        $vinculation = \App\vinculation_form::find(1068);
        $birthCountry = \App\country::find($vinculation->nationality_id);
        $birthCity = \App\city::find($vinculation->birth_place);
        $city = \App\city::find($vinculation->city_id);
        $province = \App\province::find($city->province_id);
        $country = \App\country::find($province->country_id);
        $broker = \App\sales::selectRaw('agent_ss.agentedes, agent_ss.agentedes as "canalnegodes", pbc.ejecutivo_ss as "ejecutivo", IF(channels.canalnegoid = 1, "DIRECTO", "BROKER") as "canal", CONCAT(usr.first_name," ",usr.last_name) as "ejecutivo_ss", agen.puntodeventades, pro.ramodes, sales.insured_value,agen.channel_id as"channelId"')
                                ->join('agencies as agen','agen.id','=','sales.agen_id')
                                ->join('channels','channels.id','=','agen.channel_id')
                                ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                                ->join('products as pro','pro.id','=','pbc.product_id')
                                ->join('agent_ss','agent_ss.id','=','pbc.agent_ss')
                                ->join('users as usr','usr.id','=','sales.user_id')
                                ->where('sales.id','=',$sales->id)
                                ->get();
        
        $ecoActivity=\App\economic_activity::find($vinculation->economic_activity_id);
        $occupation=\App\economic_ocupation::find($vinculation->occupation);
        
//        if($vinculation->viamatica_date == null){
            $viamaticaDate = date('d-m-Y');
//        }else{
//            $viamaticaDate = date_format($vinculation->viamatica_date, 'd-m-Y');
//        }
        
// return view('vinculation.pdf',[
//     'customer' => $customer,
//     'sales' => $sales,
//     'vinculation' => $vinculation,
//     'ecoActivity' => $ecoActivity,
//     'occupation' => $occupation,
//     'birthCountry' => $birthCountry,
//     'birthCity' => $birthCity,
//     'city' => $city,
//     'province' => $province,
//     'country' => $country,
//     'broker' => $broker[0],
//     'viamaticaDate' => $viamaticaDate
// ]);
        // A few settings
        $image = public_path().'/images/logo_seguros_sucre.jpg';

        // Read image path, convert to base64 encoding
        $imageData = base64_encode(file_get_contents($image));

        // Format the image SRC:  data:{mime};base64,{data};
        $src = 'data:'.mime_content_type($image).';base64,'.$imageData;
            
        $html = view('vinculation.pdf',[
                    'customer' => $customer,
                    'sales' => $sales,
                    'vinculation' => $vinculation,
                    'ecoActivity' => $ecoActivity,
                    'occupation' => $occupation,
                    'birthCountry' => $birthCountry,
                    'birthCity' => $birthCity,
                    'city' => $city,
                    'province' => $province,
                    'country' => $country,
                    'broker' => $broker[0],
                    'viamaticaDate' => $viamaticaDate,
                    'logo' => $src
        ]);    
//        return $html;
        $pdf = PDF::loadHTML($html);
        return $pdf->stream();
        $pdf = PDF::loadView('vinculation.pdf',[
                    'customer' => $customer,
                    'sales' => $sales,
                    'vinculation' => $vinculation,
                    'ecoActivity' => $ecoActivity,
                    'occupation' => $occupation,
                    'birthCountry' => $birthCountry,
                    'birthCity' => $birthCity,
                    'city' => $city,
                    'province' => $province,
                    'country' => $country,
                    'broker' => $broker[0],
                    'viamaticaDate' => $viamaticaDate
        ]);
        return $pdf->stream('vinculacion.pdf');
        $output = $pdf->output();
        file_put_contents(public_path('vinculacion.pdf'), $output);
        
        $b64Doc = chunk_split(base64_encode(file_get_contents(public_path('vinculacion.pdf'))));
//        return $b64Doc;

        $result = viamaticaSendPdf($customer->document, $customer->first_name, $customer->mobile_phone, $sales->id, $b64Doc, $customer->email, $customer->phone, $vinculation->id);
        
        $vinculationUpdate = \App\vinculation_form::find(213);
        $vinculationUpdate->viamatica_id = $result['data'][0];
        $vinculationUpdate->save();
        
        return $result;
//        PDF::setOptions(['dpi' => 150]);
//        return $pdf->stream('magnus.pdf');
//        return view('vinculation.pdf');
    }
    
    public function update(request $request){
        $vinFormSearch = \App\vinculation_form::where('sales_id','=',$request['saleId'])->get();
        if(!$vinFormSearch->isEmpty()){
            if($vinFormSearch[0]->status_id == 1){
                $saleSearch = \App\sales::find($request['saleId']);
                $pbc = \App\product_channel::find($saleSearch->pbc_id);
                \App\Jobs\listaObservadosyCarteraJobs::dispatch($pbc->canal_plan_id, $saleSearch->customer_id, $request['saleId'], \Auth::user()->email);

                \App\Jobs\vinculaClientesJob::dispatch($request['saleId']);
                
                $vinForm = \App\vinculation_form::find($vinFormSearch[0]->id);
                $vinForm->user_validate = new \DateTime();
                $vinForm->save();
                
                $sale = \App\sales::find($request['saleId']);
                $sale->status_id = 34;
                $sale->save();
                
                \Session::flash('successMessage', 'La vinculación ha sido enviada a Seguros Sucre.');
                
                return 'true';
                
                //CHECK RAMO
                $pro = \App\products::selectRaw('products.ramoid')
                                                ->join('products_channel as pbc','pbc.product_id','=','products.id')
                                                ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                                ->where('sal.id','=',$request['saleId'])
                                                ->get();
                
                if($pro[0]->ramoid == 1 || $pro[0]->ramoid == 2 || $pro[0]->ramoid == 4){ //VIDA Y AP
                    $sale = \App\sales::find($request['saleId']);
                    $sale->status_id = 29;
                    $sale->save();
                }elseif($pro[0]->ramoid == 7){ // VEHICULOS
                    $newVehicle = 'false';
                    //See if Vehi are new
                    $vehi = \App\vehicles::selectRaw('vehicles.new_vehicle')->join('vehicles_sales','vehicles_sales.vehicule_id','=','vehicles.id')->where('vehicles_sales.sales_id','=',$request['saleId'])->get();
                    foreach($vehi as $v){
                        if($v->new_vehicle == 1){
                            $newVehicle = 'true';
                        }
                    }
                    if($newVehicle == 'true'){
                        $sale = \App\sales::find($request['saleId']); // NUEVO
                        $sale->status_id = 27;
                        $sale->save();
                    }else{
                        $sale = \App\sales::find($request['saleId']); // USADO
                        $sale->status_id = 22;
                        $sale->save();
                    }
                }else{ // INCENDIO
                    $sale = \App\sales::find($request['saleId']);
                    $sale->status_id = 22;
                    $sale->save();
                }
                
                
                \Session::flash('successMessage', 'El formulario de vinculacion se encuentra validado correctamente.');
                
                return 'true';
            }else{
                return 'false';
            }
        }
    }
    
    public function validateEconomicActivity(request $request){
        $search = \App\economic_activity::where('name','like','%'.$request['searchEconomicActivity'].'%')->get();
        $response = '<option value="">--Escoja Una--</option>';
        foreach($search as $s){
            $response .= '<option value="'.$s->id.'">'.$s->name.'</option>';
        }
        return $response;
    }

    public function beneficiariosPDF(){
//        \App\Jobs\emisionJob::dispatch(1749);
//        dd('hola');
//        \App\Jobs\listaObservadosyCarteraJobs::dispatch();
//        $emailData = \App\sales::selectRaw('usr.email, cus.document')
//                                    ->join('customers as cus','cus.id','=','sales.customer_id')
//                                    ->join('users as usr','usr.id','=','sales.user_id')
//                                    ->where('sales.id','=',1453)
//                                    ->get();
//        return $emailData;
//        \App\Jobs\paymentAceptedUserEmailJobs::dispatch(1453,'carlos.oberto88@gmail.com',1757612005);
//        dd('aqui');
//        $result = documentosPolizaSS(1583);
//        return $result;
//        $result = carteraVencidaSS('175761204');
//        if($result['error'][0]['code'] == '003'){
//            $carteraVencida = false;
//        }else{
//            if($resultCarteraVencida['carteravencida'] == 'false'){
//                $carteraVencida = false;
//            }else{
//                $carteraVencida = true;
//            }
//        }
//        if($carteraVencida == false){
//            return 'false';
//        }else{
//            return 'true';
//        }
//        \App\Jobs\vinculaClientesJob::dispatch(1407);
//        dd('aqui');
//        $result = sftpPaymentsSS();
//        return $result;
//        $name = 'test321.pdf';
//        $path = Storage::disk('s3')->put('Vinculation/'.$name, public_path('cotization.pdf'));
//        return $path;
//        $s3 = \AWS::createClient('s3');
//        $s3->putObject(array(
//            'Bucket' => 'filesmagnusmas',
//            'Key' => 'Vinculation/prueba.pdf',
//            'SourceFile' => public_path('cotizacion.pdf'),
//        ));
//        $result = $s3->deleteObject(array(
//            'Bucket' => 'filesmagnusmas',
//            'Key'    => 'Vinculation/prueba.pdf'
//        ));
//        return $result;
//        dd('aqui');
//        $result = uploadFilesSftpSS(2);
//        return $result;
//        $email = new \App\Mail\QuotationR1Email('1494', '1757612005');
//        return $email;
//        Mail::to('carlos.oberto88@gmail.com')->send($email);
//        return 'aqui';
//        $result = vinculaClienteeSS(1407);
//        $result = emisionSS(1450); //VEHICULOS
//        $result = emisionSS(1440); //INCENDIO 
//        $result = emisionSS(1401); //VIDA -- CAMBIAR PRIMA VALUE QUE NO SE ESTA GUARDANDO
//        return $result;
        //-- CAMBIAR PRIMA VALUE QUE NO SE ESTA GUARDANDO
 //       return $result;
        set_time_limit(300);
        $customer = \App\customers::find(144);
        $sales = \App\sales::find(1578);
        $vinculation = \App\vinculation_form::find(1068);
        $birthCountry = \App\country::find($vinculation->nationality_id);
        $birthCity = \App\city::find($vinculation->birth_place);
        $city = \App\city::find($vinculation->city_id);
        $province = \App\province::find($city->province_id);
        $country = \App\country::find($province->country_id);
        $broker = \App\sales::selectRaw('agent_ss.agentedes, agent_ss.agentedes as "canalnegodes", pbc.ejecutivo_ss as "ejecutivo", IF(channels.canalnegoid = 1, "DIRECTO", "BROKER") as "canal", CONCAT(usr.first_name," ",usr.last_name) as "ejecutivo_ss", agen.puntodeventades, pro.ramodes, sales.insured_value,agen.channel_id as"channelId"')
                                ->join('agencies as agen','agen.id','=','sales.agen_id')
                                ->join('channels','channels.id','=','agen.channel_id')
                                ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                                ->join('products as pro','pro.id','=','pbc.product_id')
                                ->join('agent_ss','agent_ss.id','=','pbc.agent_ss')
                                ->join('users as usr','usr.id','=','sales.user_id')
                                ->where('sales.id','=',$sales->id)
                                ->get();
        
        $ecoActivity=\App\economic_activity::find($vinculation->economic_activity_id);
        $occupation=\App\economic_ocupation::find($vinculation->occupation);
        
//        if($vinculation->viamatica_date == null){
            $viamaticaDate = date('d-m-Y');
//        }else{
//            $viamaticaDate = date_format($vinculation->viamatica_date, 'd-m-Y');
//        }
        
// return view('vinculation.pdf',[
//     'customer' => $customer,
//     'sales' => $sales,
//     'vinculation' => $vinculation,
//     'ecoActivity' => $ecoActivity,
//     'occupation' => $occupation,
//     'birthCountry' => $birthCountry,
//     'birthCity' => $birthCity,
//     'city' => $city,
//     'province' => $province,
//     'country' => $country,
//     'broker' => $broker[0],
//     'viamaticaDate' => $viamaticaDate
// ]);
        // A few settings
        $image = public_path().'/images/logo_seguros_sucre.jpg';

        // Read image path, convert to base64 encoding
        $imageData = base64_encode(file_get_contents($image));

        // Format the image SRC:  data:{mime};base64,{data};
        $src = 'data:'.mime_content_type($image).';base64,'.$imageData;
            
        $html = view('vinculation.pdf_beneficiario',[
                    'customer' => $customer,
                    'sales' => $sales,
                    'vinculation' => $vinculation,
                    'ecoActivity' => $ecoActivity,
                    'occupation' => $occupation,
                    'birthCountry' => $birthCountry,
                    'birthCity' => $birthCity,
                    'city' => $city,
                    'province' => $province,
                    'country' => $country,
                    'broker' => $broker[0],
                    'viamaticaDate' => $viamaticaDate,
                    'logo' => $src
        ]);    
//        return $html;
        $pdf = PDF::loadHTML($html);
        return $pdf->stream();
        $pdf = PDF::loadView('vinculation.pdf',[
                    'customer' => $customer,
                    'sales' => $sales,
                    'vinculation' => $vinculation,
                    'ecoActivity' => $ecoActivity,
                    'occupation' => $occupation,
                    'birthCountry' => $birthCountry,
                    'birthCity' => $birthCity,
                    'city' => $city,
                    'province' => $province,
                    'country' => $country,
                    'broker' => $broker[0],
                    'viamaticaDate' => $viamaticaDate
        ]);
        return $pdf->stream('vinculacion.pdf');
        $output = $pdf->output();
        file_put_contents(public_path('vinculacion.pdf'), $output);
        
        $b64Doc = chunk_split(base64_encode(file_get_contents(public_path('vinculacion.pdf'))));
//        return $b64Doc;

        $result = viamaticaSendPdf($customer->document, $customer->first_name, $customer->mobile_phone, $sales->id, $b64Doc, $customer->email, $customer->phone, $vinculation->id);
        
        $vinculationUpdate = \App\vinculation_form::find(213);
        $vinculationUpdate->viamatica_id = $result['data'][0];
        $vinculationUpdate->save();
        
        return $result;
//        PDF::setOptions(['dpi' => 150]);
//        return $pdf->stream('magnus.pdf');
//        return view('vinculation.pdf');
    }

    public function tercerosPDF(){
        //        \App\Jobs\emisionJob::dispatch(1749);
        //        dd('hola');
        //        \App\Jobs\listaObservadosyCarteraJobs::dispatch();
        //        $emailData = \App\sales::selectRaw('usr.email, cus.document')
        //                                    ->join('customers as cus','cus.id','=','sales.customer_id')
        //                                    ->join('users as usr','usr.id','=','sales.user_id')
        //                                    ->where('sales.id','=',1453)
        //                                    ->get();
        //        return $emailData;
        //        \App\Jobs\paymentAceptedUserEmailJobs::dispatch(1453,'carlos.oberto88@gmail.com',1757612005);
        //        dd('aqui');
        //        $result = documentosPolizaSS(1583);
        //        return $result;
        //        $result = carteraVencidaSS('175761204');
        //        if($result['error'][0]['code'] == '003'){
        //            $carteraVencida = false;
        //        }else{
        //            if($resultCarteraVencida['carteravencida'] == 'false'){
        //                $carteraVencida = false;
        //            }else{
        //                $carteraVencida = true;
        //            }
        //        }
        //        if($carteraVencida == false){
        //            return 'false';
        //        }else{
        //            return 'true';
        //        }
        //        \App\Jobs\vinculaClientesJob::dispatch(1407);
        //        dd('aqui');
        //        $result = sftpPaymentsSS();
        //        return $result;
        //        $name = 'test321.pdf';
        //        $path = Storage::disk('s3')->put('Vinculation/'.$name, public_path('cotization.pdf'));
        //        return $path;
        //        $s3 = \AWS::createClient('s3');
        //        $s3->putObject(array(
        //            'Bucket' => 'filesmagnusmas',
        //            'Key' => 'Vinculation/prueba.pdf',
        //            'SourceFile' => public_path('cotizacion.pdf'),
        //        ));
        //        $result = $s3->deleteObject(array(
        //            'Bucket' => 'filesmagnusmas',
        //            'Key'    => 'Vinculation/prueba.pdf'
        //        ));
        //        return $result;
        //        dd('aqui');
        //        $result = uploadFilesSftpSS(2);
        //        return $result;
        //        $email = new \App\Mail\QuotationR1Email('1494', '1757612005');
        //        return $email;
        //        Mail::to('carlos.oberto88@gmail.com')->send($email);
        //        return 'aqui';
        //        $result = vinculaClienteeSS(1407);
        //        $result = emisionSS(1450); //VEHICULOS
        //        $result = emisionSS(1440); //INCENDIO 
        //        $result = emisionSS(1401); //VIDA -- CAMBIAR PRIMA VALUE QUE NO SE ESTA GUARDANDO
        //        return $result;
                //-- CAMBIAR PRIMA VALUE QUE NO SE ESTA GUARDANDO
         //       return $result;
                set_time_limit(300);
                $customer = \App\customers::find(144);
                $sales = \App\sales::find(1578);
                $vinculation = \App\vinculation_form::find(1068);
                $birthCountry = \App\country::find($vinculation->nationality_id);
                $birthCity = \App\city::find($vinculation->birth_place);
                $city = \App\city::find($vinculation->city_id);
                $province = \App\province::find($city->province_id);
                $country = \App\country::find($province->country_id);
                $broker = \App\sales::selectRaw('agent_ss.agentedes, agent_ss.agentedes as "canalnegodes", pbc.ejecutivo_ss as "ejecutivo", IF(channels.canalnegoid = 1, "DIRECTO", "BROKER") as "canal", CONCAT(usr.first_name," ",usr.last_name) as "ejecutivo_ss", agen.puntodeventades, pro.ramodes, sales.insured_value,agen.channel_id as"channelId"')
                                        ->join('agencies as agen','agen.id','=','sales.agen_id')
                                        ->join('channels','channels.id','=','agen.channel_id')
                                        ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                                        ->join('products as pro','pro.id','=','pbc.product_id')
                                        ->join('agent_ss','agent_ss.id','=','pbc.agent_ss')
                                        ->join('users as usr','usr.id','=','sales.user_id')
                                        ->where('sales.id','=',$sales->id)
                                        ->get();
                
                $ecoActivity=\App\economic_activity::find($vinculation->economic_activity_id);
                $occupation=\App\economic_ocupation::find($vinculation->occupation);
                
        //        if($vinculation->viamatica_date == null){
                    $viamaticaDate = date('d-m-Y');
        //        }else{
        //            $viamaticaDate = date_format($vinculation->viamatica_date, 'd-m-Y');
        //        }
                
        // return view('vinculation.pdf',[
        //     'customer' => $customer,
        //     'sales' => $sales,
        //     'vinculation' => $vinculation,
        //     'ecoActivity' => $ecoActivity,
        //     'occupation' => $occupation,
        //     'birthCountry' => $birthCountry,
        //     'birthCity' => $birthCity,
        //     'city' => $city,
        //     'province' => $province,
        //     'country' => $country,
        //     'broker' => $broker[0],
        //     'viamaticaDate' => $viamaticaDate
        // ]);
                // A few settings
                $image = public_path().'/images/logo_seguros_sucre.jpg';
        
                // Read image path, convert to base64 encoding
                $imageData = base64_encode(file_get_contents($image));
        
                // Format the image SRC:  data:{mime};base64,{data};
                $src = 'data:'.mime_content_type($image).';base64,'.$imageData;
                    
                $html = view('vinculation.pdf_terceros',[
                            'customer' => $customer,
                            'sales' => $sales,
                            'vinculation' => $vinculation,
                            'ecoActivity' => $ecoActivity,
                            'occupation' => $occupation,
                            'birthCountry' => $birthCountry,
                            'birthCity' => $birthCity,
                            'city' => $city,
                            'province' => $province,
                            'country' => $country,
                            'broker' => $broker[0],
                            'viamaticaDate' => $viamaticaDate,
                            'logo' => $src
                ]);    
        //        return $html;
                $pdf = PDF::loadHTML($html);
                return $pdf->stream();
                $pdf = PDF::loadView('vinculation.pdf',[
                            'customer' => $customer,
                            'sales' => $sales,
                            'vinculation' => $vinculation,
                            'ecoActivity' => $ecoActivity,
                            'occupation' => $occupation,
                            'birthCountry' => $birthCountry,
                            'birthCity' => $birthCity,
                            'city' => $city,
                            'province' => $province,
                            'country' => $country,
                            'broker' => $broker[0],
                            'viamaticaDate' => $viamaticaDate
                ]);
                return $pdf->stream('vinculacion.pdf');
                $output = $pdf->output();
                file_put_contents(public_path('vinculacion.pdf'), $output);
                
                $b64Doc = chunk_split(base64_encode(file_get_contents(public_path('vinculacion.pdf'))));
        //        return $b64Doc;
        
                $result = viamaticaSendPdf($customer->document, $customer->first_name, $customer->mobile_phone, $sales->id, $b64Doc, $customer->email, $customer->phone, $vinculation->id);
                
                $vinculationUpdate = \App\vinculation_form::find(213);
                $vinculationUpdate->viamatica_id = $result['data'][0];
                $vinculationUpdate->save();
                
                return $result;
        //        PDF::setOptions(['dpi' => 150]);
        //        return $pdf->stream('magnus.pdf');
        //        return view('vinculation.pdf');
    }
        

}
