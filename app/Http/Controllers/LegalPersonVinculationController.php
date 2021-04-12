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

class LegalPersonVinculationController extends Controller
{
    public function create(){
        if (!isset($_GET['document']) || !isset($_GET['sales'])|| !isset($_GET['companys'])) { \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo.'); return view('home'); }else{ $document_get = decrypt($_GET['document']); $sales_get = decrypt($_GET['sales']); $companys_get = decrypt($_GET['companys']);}
        
        $birth_place = '123'; $birth_date = null; $nationality_id = null;
        $sector = null; $main_road = null; $address_number = null;
        $secondary_road = null; $email = null; $secondary_email = null;
        $mobile_phone = null; $phone = null; $civil_state = null;
        $province_id = null; $addressCities = null; $optRadioYes = null; $optRadioNo = null;
        $company_id=null;$customer_id = null; $sales_id = null; $city_id = null; $sales = null; $disable_status = null;
        $economic_activity = null; $other_economic_activity = null; $monthly_income = null;
        $monthly_outcome = null; $total_actives = null; $total_pasives = null; $other_monthly_income = null; $other_monthly_income_source = null;
        $person_exposed = null; $family_exposed = null;
        $picture_document_applicant = ''; $picture_document_spouse = ''; $picture_voting_ballot_applicant = '';
        $picture_voting_ballot_spouse = ''; $picture_service = '';$picture_sri= '';
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
        $work_name =  null; $work_address = null; $work_phone = null; $picture_sri = null;$provincePlace_id=null;$cityLegalRepresentative_id=null; $countryPlace_id=null;$provinceLegalRepresentative_id =null; $countryPlace_id=null;$countryLegalRepresentative_id=null;
        $picture_constitution_deed='';
        $picture_certificate_appointment='';
        $picture_shareholders_payroll='';
        $picture_certificate_obligations='';
        $picture_financial_state='';
        $picture_ruc='';
       
        //Validate Document
        $legalRepresentative= \App\customerLegalRepresentative::where('document','=',$document_get)->get(); if($legalRepresentative->isEmpty()){ \Session::flash('ValidateUserRoute', 'Por favor solicite un nuevo enlace1.'); return view('home'); }

        //Validate Sales
        $Sales = \App\sales::where('id','=',$sales_get)->get();
        if($Sales->isEmpty()){ \Session::flash('ValidateUserRoute', 'Por favor solicite un nuevo enlace2.'); return view('home'); }
       
        //Validate Companys
        $Companys = \App\companys::where('document','=',$companys_get)->get();
        if($Companys->isEmpty()){ \Session::flash('ValidateUserRoute', 'Por favor solicite un nuevo enlace3.'); return view('home'); }
        
        $legalPersonVinculation = \App\vinculation_form::where('company_id','=',$Companys[0]->id)->where('customer_legal_representative_id','=',$legalRepresentative[0]->id)->where('sales_id','=',$sales_get)->latest()->first();
        
        if($legalPersonVinculation){
            if($legalPersonVinculation->status_id == 1){ $disable_status = 'disabled="disabled"'; }
            $birth_place = $legalPersonVinculation->birth_place; $birth_date = $legalPersonVinculation->birth_date; $nationality_id = $legalPersonVinculation->nationality_id;
            $sector = $legalPersonVinculation->address_zone; $main_road = $legalPersonVinculation->main_road; $address_number = $legalPersonVinculation->address_number;
            $secondary_road = $legalPersonVinculation->secondary_road; $email = $legalPersonVinculation->email; $secondary_email = $legalPersonVinculation->secondary_email;
            $mobile_phone = $legalPersonVinculation->mobile_phone; $phone = $legalPersonVinculation->phone; $civil_state = $legalPersonVinculation->civil_state; $city_id = $legalPersonVinculation->city_id;
            $company_id=$legalPersonVinculation->company_id; $customer_id = $legalPersonVinculation->customer_id; $sales_id = $legalPersonVinculation->sales_id;
            $economic_activity = $legalPersonVinculation->economic_activity_id; $other_economic_activity = $legalPersonVinculation->economic_activity_other; $monthly_income = $legalPersonVinculation->monthly_income;
            $monthly_outcome = $legalPersonVinculation->monthly_outcome; $total_actives = $legalPersonVinculation->total_actives; $total_pasives = number_format($legalPersonVinculation->total_pasives,2); 
            $other_monthly_income = $legalPersonVinculation->other_monthly_income; $other_monthly_income_source = $legalPersonVinculation->other_monthly_income_source;
            $person_exposed = $legalPersonVinculation->person_exposed; $family_exposed = $legalPersonVinculation->family_exposed;
            if($legalPersonVinculation->picture_document_applicant == null) { $picture_document_applicant = ''; } else { if(pathinfo($legalPersonVinculation->picture_document_applicant)['extension'] == 'pdf'){ $picture_document_applicant = '<a href="' . $legalPersonVinculation->picture_document_applicant . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_document_applicant = '<a href="' . $legalPersonVinculation->picture_document_applicant . '" target="_blank"><img src="' . $legalPersonVinculation->picture_document_applicant . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_document_spouse == null) { $picture_document_spouse = ''; } else { if(pathinfo($legalPersonVinculation->picture_document_spouse)['extension'] == 'pdf'){ $picture_document_spouse = '<a href="' . $legalPersonVinculation->picture_document_spouse . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_document_spouse = '<a href="' . $legalPersonVinculation->picture_document_spouse . '" target="_blank"><img src="' . $legalPersonVinculation->picture_document_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_voting_ballot == null) { $picture_voting_ballot_applicant = ''; } else { if(pathinfo($legalPersonVinculation->picture_voting_ballot)['extension'] == 'pdf'){ $picture_voting_ballot_applicant = '<a href="' . $legalPersonVinculation->picture_voting_ballot . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_voting_ballot_applicant = '<a href="' . $legalPersonVinculation->picture_voting_ballot . '" target="_blank"><img src="' . $legalPersonVinculation->picture_voting_ballot . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_voting_ballot_spouse == null) { $picture_voting_ballot_spouse = ''; } else { if(pathinfo($legalPersonVinculation->picture_voting_ballot_spouse)['extension'] == 'pdf'){ $picture_voting_ballot_spouse = '<a href="' . $legalPersonVinculation->picture_voting_ballot_spouse . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_voting_ballot_spouse = '<a href="' . $legalPersonVinculation->picture_voting_ballot_spouse . '" target="_blank"><img src="' . $legalPersonVinculation->picture_voting_ballot_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_service == null) { $picture_service = ''; } else { if(pathinfo($legalPersonVinculation->picture_service)['extension'] == 'pdf'){ $picture_service = '<a href="' . $legalPersonVinculation->picture_service . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_service = '<a href="' . $legalPersonVinculation->picture_service . '" target="_blank"><img src="' . $legalPersonVinculation->picture_service . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_sri == null) { $picture_sri = ''; } else { if(pathinfo($legalPersonVinculation->picture_sri)['extension'] == 'pdf'){ $picture_sri = '<a href="' . $legalPersonVinculation->picture_sri . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_sri = '<a href="' . $legalPersonVinculation->picture_sri . '" target="_blank"><img src="' . $legalPersonVinculation->picture_sri . '" class="img-thumbnail" width="300" height="300" /></a>'; } }

            if($legalPersonVinculation->picture_ruc == null) { $picture_ruc = ''; } else { if(pathinfo($legalPersonVinculation->picture_ruc)['extension'] == 'pdf'){ $picture_ruc = '<a href="' . $legalPersonVinculation->picture_ruc . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_ruc = '<a href="' . $legalPersonVinculation->picture_ruc . '" target="_blank"><img src="' . $legalPersonVinculation->picture_ruc . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_constitution_deed == null) { $picture_constitution_deed = ''; } else { if(pathinfo($legalPersonVinculation->picture_constitution_deed)['extension'] == 'pdf'){ $picture_constitution_deed = '<a href="' . $legalPersonVinculation->picture_constitution_deed . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_constitution_deed = '<a href="' . $legalPersonVinculation->picture_constitution_deed . '" target="_blank"><img src="' . $legalPersonVinculation->picture_constitution_deed . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_certificate_appointment == null) { $picture_certificate_appointment = ''; } else { if(pathinfo($legalPersonVinculation->picture_certificate_appointment)['extension'] == 'pdf'){ $picture_certificate_appointment = '<a href="' . $legalPersonVinculation->picture_certificate_appointment . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_certificate_appointment = '<a href="' . $legalPersonVinculation->picture_certificate_appointment . '" target="_blank"><img src="' . $legalPersonVinculation->picture_certificate_appointment . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_shareholders_payroll == null) { $picture_shareholders_payroll = ''; } else { if(pathinfo($legalPersonVinculation->picture_shareholders_payroll)['extension'] == 'pdf'){ $picture_shareholders_payroll = '<a href="' . $legalPersonVinculation->picture_shareholders_payroll . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_shareholders_payroll = '<a href="' . $legalPersonVinculation->picture_shareholders_payroll . '" target="_blank"><img src="' . $legalPersonVinculation->picture_shareholders_payroll . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_service == null) { $picture_service = ''; } else { if(pathinfo($legalPersonVinculation->picture_service)['extension'] == 'pdf'){ $picture_service = '<a href="' . $legalPersonVinculation->picture_service . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="300" height="300" /></a>'; }else{ $picture_service = '<a href="' . $legalPersonVinculation->picture_service . '" target="_blank"><img src="' . $legalPersonVinculation->picture_service . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_certificate_obligations == null) { $picture_certificate_obligations = ''; } else { if(pathinfo($legalPersonVinculation->picture_certificate_obligations)['extension'] == 'pdf'){ $picture_certificate_obligations = '<a href="' . $legalPersonVinculation->picture_certificate_obligations . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_certificate_obligations = '<a href="' . $legalPersonVinculation->picture_certificate_obligations . '" target="_blank"><img src="' . $legalPersonVinculation->picture_certificate_obligations . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            if ($legalPersonVinculation->picture_financial_state == null) { $picture_financial_state = ''; } else { if(pathinfo($legalPersonVinculation->picture_financial_state)['extension'] == 'pdf'){ $picture_financial_state = '<a href="' . $legalPersonVinculation->picture_financial_state . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" height="150" /></a>'; }else{ $picture_financial_state = '<a href="' . $legalPersonVinculation->picture_financial_state . '" target="_blank"><img src="' . $legalPersonVinculation->picture_financial_state . '" class="img-thumbnail" width="300" height="300" /></a>'; } }
            
            $spouse_document = $legalPersonVinculation->spouse_document; $spouse_name = $legalPersonVinculation->spouse_name; $spouse_last_name = $legalPersonVinculation->spouse_last_name; $annual_income = number_format($legalPersonVinculation->annual_income,2); $spouse_document_id = $legalPersonVinculation->spouse_document_id;
            $occupation = $legalPersonVinculation->occupation; $other_annual_income = number_format($legalPersonVinculation->other_annual_income,2); $total_annual_income = number_format($legalPersonVinculation->total_annual_income,2);
            $description_other_income = $legalPersonVinculation->description_other_income; $total_assets = number_format($legalPersonVinculation->total_actives,2); $total_assets_pasives = number_format($legalPersonVinculation->total_assets_pasives,2);
            $document_applicant_date = $legalPersonVinculation->document_applicant_date; $document_spouse_date = $legalPersonVinculation->document_spouse_date; $pep_client = $legalPersonVinculation->family_exposed;
            $personal_reference_name = $legalPersonVinculation->personal_reference_name; $personal_reference_relationship = $legalPersonVinculation->personal_reference_relationship; $personal_reference_phone = $legalPersonVinculation->personal_reference_phone;
            $commercial_reference_name = $legalPersonVinculation->commercial_reference_name; $commercial_reference_amount = number_format($legalPersonVinculation->commercial_reference_amount,2); $commercial_reference_phone = $legalPersonVinculation->commercial_reference_phone;
            $commercial_reference_bank_name = $legalPersonVinculation->commercial_reference_bank_name; $commercial_reference_product = $legalPersonVinculation->commercial_reference_product;
            $constitution_date = $legalPersonVinculation->constitution_date; $legal_representative = \App\customerLegalRepresentative::find($legalPersonVinculation->customer_legal_representative_id);
            $beneficiaryName = $legalPersonVinculation->benefitiary_name; $beneficiary_document_id = $legalPersonVinculation->benefitiary_document_id; $beneficiary_document = $legalPersonVinculation->benefitiary_document;
            $beneficiary_nationality = $legalPersonVinculation->benefitiary_nationality_id; $beneficiary_address = $legalPersonVinculation->benefitiary_address; $beneficiary_phone = $legalPersonVinculation->benefitiary_phone; $beneficiary_relationship = $legalPersonVinculation->benefitiary_relationship;
            $economic_activity_id = $legalPersonVinculation->economic_activity_id;
            $work_name =  $legalPersonVinculation->work_name; $work_address = $legalPersonVinculation->work_address; $work_phone = $legalPersonVinculation->work_phone;
            }else {
            $legalPersonvinculationOther = \App\vinculation_form::where('customer_id','=',$customer[0]->id)->latest()->first();
            if($legalPersonvinculationOther){
                if($legalPersonvinculationOther->status_id == 1){ $disable_status = ''; }
                $birth_place = $legalPersonvinculationOther->birth_place; $birth_date = $legalPersonvinculationOther->birth_date; $nationality_id = $legalPersonvinculationOther->nationality_id;
                $sector = $legalPersonvinculationOther->address_zone; $main_road = $legalPersonvinculationOther->main_road; $address_number = $legalPersonvinculationOther->address_number;
                $secondary_road = $legalPersonvinculationOther->secondary_road; $email = $legalPersonvinculationOther->email; $secondary_email = $legalPersonvinculationOther->secondary_email;
                $mobile_phone = $legalPersonvinculationOther->mobile_phone; $phone = $legalPersonvinculationOther->phone; $civil_state = $legalPersonvinculationOther->civil_state; $city_id = $legalPersonvinculationOther->city_id;
                $company_id=$legalPersonvinculationOther->company_id;$customer_id = $legalPersonvinculationOther->customer_id; $sales_id = $legalPersonvinculationOther->sales_id;
                $economic_activity = $legalPersonvinculationOther->economic_activity_id; $other_economic_activity = $legalPersonvinculationOther->economic_activity_other; $monthly_income = $legalPersonvinculationOther->monthly_income;
                $monthly_outcome = $legalPersonvinculationOther->monthly_outcome; $total_actives = $legalPersonvinculationOther->total_actives; $total_pasives = $legalPersonvinculationOther->total_pasives; 
                $other_monthly_income = $legalPersonvinculationOther->other_monthly_income; $other_monthly_income_source = $legalPersonvinculationOther->other_monthly_income_source;
                $person_exposed = $legalPersonvinculationOther->person_exposed; $family_exposed = $legalPersonvinculationOther->family_exposed;
                if($legalPersonvinculationOther->picture_document_applicant == null){ $picture_document_applicant = ''; }else{ $picture_document_applicant = '<a href="'.$legalPersonvinculationOther->picture_document_applicant.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_document_applicant . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if ($legalPersonvinculationOther->picture_document_spouse == null) { $picture_document_spouse = ''; } else { $picture_document_spouse = '<a href="' . $legalPersonvinculationOther->picture_document_spouse . '" target="_blank"><img src="' . $legalPersonvinculationOther->picture_document_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if($legalPersonvinculationOther->picture_voting_ballot == null){ $picture_voting_ballot_applicant = ''; }else{ $picture_voting_ballot_applicant = '<a href="'.$legalPersonvinculationOther->picture_voting_ballot.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_voting_ballot . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if($legalPersonvinculationOther->picture_voting_ballot_spouse == null){ $picture_voting_ballot_spouse = ''; }else{ $picture_voting_ballot_spouse = '<a href="'.$legalPersonvinculationOther->picture_voting_ballot_spouse.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_voting_ballot_spouse . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if($legalPersonvinculationOther->picture_service == null){ $picture_service = ''; }else{ $picture_service = '<a href="'.$legalPersonvinculationOther->picture_service.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_service . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if($legalPersonvinculationOther->picture_sri == null){ $picture_sri = ''; }else{ $picture_sri = '<a href="'.$legalPersonvinculationOther->picture_sri.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_sri . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                
                if($legalPersonvinculationOther->picture_ruc == null){ $picture_ruc = ''; }else{ $picture_ruc = '<a href="'.$legalPersonvinculationOther->picture_ruc.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_ruc . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if ($legalPersonvinculationOther->picture_constitution_deed == null) { $picture_constitution_deed = ''; } else { $picture_constitution_deed = '<a href="' . $legalPersonvinculationOther->picture_constitution_deed . '" target="_blank"><img src="' . $legalPersonvinculationOther->picture_constitution_deed . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if($legalPersonvinculationOther->picture_certificate_appointment == null){ $picture_certificate_appointment = ''; }else{ $picture_certificate_appointment = '<a href="'.$legalPersonvinculationOther->picture_certificate_appointment.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_certificate_appointment . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if($legalPersonvinculationOther->picture_shareholders_payroll == null){ $picture_shareholders_payroll = ''; }else{ $picture_shareholders_payroll = '<a href="'.$legalPersonvinculationOther->picture_shareholders_payroll.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_shareholders_payroll . '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if($legalPersonvinculationOther->picture_certificate_obligations == null){ $picture_certificate_obligations = ''; }else{ $picture_certificate_obligations = '<a href="'.$legalPersonvinculationOther->picture_certificate_obligations.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_certificate_obligations. '" class="img-thumbnail" width="300" height="300" /></a>'; }
                if($legalPersonvinculationOther->picture_financial_state == null){ $picture_financial_state = ''; }else{ $picture_financial_state = '<a href="'.$legalPersonvinculationOther->picture_financial_state.'" target="_blank"><img src="' . $legalPersonvinculationOther->picture_financial_state. '" class="img-thumbnail" width="300" height="300" /></a>'; }
                
                $spouse_document = $legalPersonvinculationOther->spouse_document; $spouse_name = $legalPersonvinculationOther->spouse_name; $spouse_last_name = $legalPersonvinculationOther->spouse_last_name; $annual_income = number_format($legalPersonvinculationOther->annual_income,2); $spouse_document_id = $legalPersonvinculationOther->spouse_document_id;
                $occupation = $legalPersonvinculationOther->occupation; $other_annual_income = $legalPersonvinculationOther->other_annual_income; $total_annual_income = $legalPersonvinculationOther->total_annual_income;
                $description_other_income = $legalPersonvinculationOther->description_other_income; $total_assets = $vinculation->total_actives; $total_assets_pasives = $legalPersonvinculationOther->total_assets_pasives;
                $document_applicant_date = $legalPersonvinculationOther->document_applicant_date; $document_spouse_date = $legalPersonvinculationOther->document_spouse_date; $pep_client = $legalPersonvinculationOther->family_exposed;
                $personal_reference_name = $legalPersonvinculationOther->personal_reference_name; $personal_reference_relationship = $legalPersonvinculationOther->personal_reference_relationship; $personal_reference_phone = $legalPersonvinculationOther->personal_reference_phone;
                $commercial_reference_name = $legalPersonvinculationOther->commercial_reference_name; $commercial_reference_amount = $legalPersonvinculationOther->commercial_reference_amount; $commercial_reference_phone = $legalPersonvinculationOther->commercial_reference_phone;
                $commercial_reference_bank_name = $legalPersonvinculationOther->commercial_reference_bank_name; $commercial_reference_product = $legalPersonvinculationOther->commercial_reference_product;
                $constitution_date = $legalPersonvinculationOther->constitution_date; $legal_representative = \App\customerLegalRepresentative::find($legalPersonvinculationOther->customer_legal_representative);
                $beneficiaryName = $legalPersonvinculationOther->benefitiary_name; $beneficiary_document_id = $legalPersonvinculationOther->benefitiary_document_id; $beneficiary_document = $legalPersonvinculationOther->benefitiary_document;
                $beneficiary_nationality = $legalPersonvinculationOther->benefitiary_nationality_id; $beneficiary_address = $legalPersonvinculationOther->benefitiary_address; $beneficiary_phone = $legalPersonvinculationOther->benefitiary_phone; $beneficiary_relationship = $legalPersonvinculationOther->benefitiary_relationship;
                $economic_activity_id = $legalPersonvinculationOther->economic_activity_id;
                $work_name =  $legalPersonvinculationOther->work_name; $work_address = $legalPersonvinculationOther->work_address; $work_phone = $legalPersonvinculationOther->work_phone; $picture_sri = $legalPersonvinculationOther->picture_sri;
                
                $vinculationOld = \App\vinculation_form::find($legalPersonvinculationOther->id);
                
                //Copy Documents
                $vinculationNew = $vinculationOld->replicate();
                $vinculationNew->customer_id  = $legalPersonvinculationOther->customer_id;
                $vinculationNew->sales_id = $sales_get;
                $vinculationNew->status_id = 6;
                $vinculationNew->save();
                
                //Create Dir
                mkdir(public_path('images/vinculation/'.$vinculationNew->id));
                
                $vinculactionUpdate = \App\vinculation_form::find($vinculationNew->id);
                if($picture_document_applicant != null) { $file = basename($legalPersonvinculationOther->picture_document_applicant); $success = \File::copy(public_path($legalPersonvinculationOther->picture_document_applicant), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_document_applicant = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_document_spouse != null) { $file = basename($legalPersonvinculationOther->picture_document_spouse); $success = \File::copy(public_path($legalPersonvinculationOther->picture_document_spouse), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_document_spouse = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_voting_ballot_applicant != null) { $file = basename($legalPersonvinculationOther->picture_voting_ballot); $success = \File::copy(public_path($legalPersonvinculationOther->picture_voting_ballot), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_voting_ballot = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_voting_ballot_spouse != null) { $file = basename($legalPersonvinculationOther->picture_voting_ballot_spouse); $success = \File::copy(public_path($legalPersonvinculationOther->picture_voting_ballot_spouse), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_voting_ballot_spouse = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_service != null) { $file = basename($legalPersonvinculationOther->picture_service); $success = \File::copy(public_path($legalPersonvinculationOther->picture_service), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_service = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_ruc != null) { $file = basename($legalPersonvinculationOther->picture_ruc); $success = \File::copy(public_path($legalPersonvinculationOther->picture_ruc), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_ruc = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_constitution_deed!= null) { $file = basename($legalPersonvinculationOther->picture_constitution_deed); $success = \File::copy(public_path($legalPersonvinculationOther->picture_constitution_deed), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_constitution_deed = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_certificate_appointment != null) { $file = basename($legalPersonvinculationOther->picture_certificate_appointment); $success = \File::copy(public_path($legalPersonvinculationOther->picture_certificate_appointment), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_certificate_appointment = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_shareholders_payroll != null) { $file = basename($legalPersonvinculationOther->picture_shareholders_payroll); $success = \File::copy(public_path($legalPersonvinculationOther->picture_shareholders_payroll), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_shareholders_payroll = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_certificate_obligations != null) { $file = basename($legalPersonvinculationOther->picture_certificate_obligations); $success = \File::copy(public_path($legalPersonvinculationOther->picture_certificate_obligations), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_certificate_obligations = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_financial_state!= null) { $file = basename($legalPersonvinculationOther->picture_financial_state); $success = \File::copy(public_path($legalPersonvinculationOther->picture_financial_state), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_financial_state = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                if ($picture_sri != null) { $file = basename($legalPersonvinculationOther->picture_sri); $success = \File::copy(public_path($legalPersonvinculationOther->picture_sri), public_path('images/vinculation/' . $vinculationNew->id . '/' . $file)); $vinculactionUpdate->picture_sri = getAppRoute() . '/images/vinculation/' . $vinculationNew->id . '/' . $file; }
                
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
                if ($birth_place) {
                    $provincePlace= \App\city::find($birth_place);
                    if ($provincePlace) {
                        $provincePlace_id = $provincePlace->province_id;
                        $CitiesPlace = \App\city::where('province_id', '=', $provincePlace_id)->get();
                        /* Obtain Country */
                        $provincePlace = \App\province::find($provincePlace_id);
                        $countriesPlace= \App\country::find($provincePlace->country_id);
                        $countryPlace_id = $countriesPlace->id;
                    }
                }
                $cityLegalRepresentative=\App\city::find($legalRepresentative[0]->city_id);                
                    if ($cityLegalRepresentative) {
                        $cityLegalRepresentative_id=$cityLegalRepresentative->id;
                        $provinceLegalRepresentative_id = $cityLegalRepresentative->province_id;
                        $CitiesLegalRepresenative= \App\city::where('province_id', '=', $provinceLegalRepresentative_id)->get();
                        /* Obtain Country */
                        $provinceLegalRepresentative = \App\province::find($provinceLegalRepresentative_id);
                        $countriesLegalRepresentative= \App\country::find($provinceLegalRepresentative->country_id);
                        $countryLegalRepresentative_id = $countriesLegalRepresentative->id;
                    }
            
                               
        //        dd($country_id);
                //Email Radio BTn
                if($secondary_email){ $optRadioYes = null; $optRadioNo = true; }else{ $optRadioYes = true; $optRadioNo = null; }
                
                //Economic Activities
                $economicActivities = \App\economic_activity::all();
                $occupationList = \App\economic_ocupation::all();

        $documents = \App\document::all();
        $cities = \App\city::orderBy('name','ASC')->get();
        $countries = \App\country::all();
        $provinces = \App\province::orderBy('name','ASC')->get();
        $civisStates = \App\civilState::orderBy('name','ASC')->get();
        $migratoryStates = \App\migratoryState::orderBy('name','ASC')->get();
        
        if(isset($_GET['broker'])){
            $disable_status = 'disabled="disabled"';
        }
        $vinFormVersion=null;
        if($legalPersonVinculation->status_id == 1){
            $vinFormVersion = $legalPersonVinculation->form_version;
            $checked = 'true';
        }else{
            $checked = null;
            $result = valorAseguradoSS($document_get);
            $saleData = \App\sales::find($sales_get);
            $valorAsegurado = $result['monto'] + $saleData->insured_value;
            if($valorAsegurado > 200000){
                $vinFormVersion = 4;
            }
        }
        $vinculatioUpdate = \App\vinculation_form::find($legalPersonVinculation->id);
        $vinculatioUpdate->form_version = $vinFormVersion;
        $vinculatioUpdate->save();
        return view('legalPersonVinculation.create',[
            'vinFormVersion'=>$vinFormVersion,
            'company'=>$Companys[0],'legalRepresentative'=>$legalRepresentative[0],
            'documents' => $documents, 'cities' => $cities, 'countries' => $countries, 'provinces' => $provinces,
            'civilStates' => $civisStates, 'migratoryStates' => $migratoryStates, 'birth_place' => $birth_place, 'birth_date' => $birth_date,
            'nationality_id' => $nationality_id, 'sector' => $sector, 'main_road' => $main_road, 'address_number' => $address_number,
            'secondary_road' => $secondary_road, 'email' => $email, 'secondary_email' => $secondary_email, 'mobile_phone' => $mobile_phone,
            'phone' => $phone, 'civil_state' => $civil_state, 'city_id' => $city_id, 'province_id' => $province_id,'provinceLegalRepresentative_id'=>$provinceLegalRepresentative_id,'cityLegalRepresentative_id'=>$cityLegalRepresentative_id,'countryLegalRepresentative_id'=>$countryLegalRepresentative_id,
            'provincePlace_id'=>$provincePlace_id,'country_id' => $country_id, 'addressCities' => $addressCities, 'optRadioYes' => $optRadioYes, 'optRadioNo' => $optRadioNo,
            'economicActivities' => $economicActivities, 'sales_id' => $sales_get, 'disable_status' => $disable_status,
            'economic_activity' => $economic_activity, 'other_economic_activity' => $other_economic_activity, 'monthly_income' => $monthly_income,
            'monthly_outcome' => $monthly_outcome, 'total_actives' => $total_actives, 'total_pasives' => $total_pasives,
            'other_monthly_income' => $other_monthly_income, 'other_monthly_income_source' => $other_monthly_income_source, 'person_exposed' => $person_exposed, 'family_exposed' => $family_exposed, 'picture_document_applicant' => $picture_document_applicant,
            'picture_document_spouse' => $picture_document_spouse, 'picture_voting_ballot_applicant' => $picture_voting_ballot_applicant, 'picture_voting_ballot_spouse' => $picture_voting_ballot_spouse,
            'picture_sri' => $picture_sri,'picture_service' => $picture_service, 'spouse_document' => $spouse_document, 'spouse_name' => $spouse_name,
            'spouse_last_name' => $spouse_last_name, 'annual_income' => $annual_income, 'spouse_document_id' => $spouse_document_id,
            'occupation' => $occupation, 'other_annual_income' => $other_annual_income, 'total_annual_income' => $total_annual_income,
            'description_other_income' => $description_other_income, 'total_assets' => $total_assets, 'total_pasives' => $total_pasives, 'total_assets_pasives' => $total_assets_pasives,
            'document_applicant_date' => $document_applicant_date, 'document_spouse_date' => $document_spouse_date, 'pep_client' => $pep_client,
            'beneficiaryName' => $beneficiaryName, 'beneficiary_document_id' => $beneficiary_document_id, 'beneficiary_document' => $beneficiary_document,
            'occupationList' => $occupationList, 'economic_activity_id' =>  $economic_activity_id,
            'beneficiary_nationality' => $beneficiary_nationality, 'beneficiary_address' => $beneficiary_address, 'beneficiary_phone' => $beneficiary_phone, 'beneficiary_relationship' => $beneficiary_relationship, 'checked' => $checked,
            'picture_constitution_deed'=>$picture_constitution_deed,       
            'picture_certificate_appointment'=> $picture_certificate_appointment,           
            'picture_shareholders_payroll'=> $picture_shareholders_payroll,            
            'picture_certificate_obligations'=>$picture_certificate_obligations,            
            'picture_financial_state'=>$picture_financial_state,          
            'picture_ruc'=>$picture_ruc
        ]); 
    }
    public function validateDocument(request $request) {
        // return $request['data']['document'];
         if (!validateId($request['data']['document'])) {
            return 'invalid';
            } else {
                return 'valid';
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
    public function firstStepForm(request $request){
        $legalPersonvinculationSearch = \App\vinculation_form::where('customer_legal_representative_id','=',$request['documentId'])->where('sales_id','=',$request['saleId'])->where('company_id','=',$request['companyId'])->latest()->first();
        if($legalPersonvinculationSearch){
            if($legalPersonvinculationSearch->status_id == 1){
                //NADA
            }else{
                $companyUpdate = \App\companys::find($request['companyId']);
                $companyUpdate->economic_activity_id=$request['occupation'];
                $companyUpdate->constitution_date=$request['constitution_dateCompany'];
                $companyUpdate->address=$request['main_roadCompany'];
                $companyUpdate->city_id=$request['cityCompany'];
                $companyUpdate->cross_street=$request['secondary_roadCompany'];
                $companyUpdate->address_number=$request['numberCompany'];
                $companyUpdate->parroquia=$request['parroquiaCompany'];
                $companyUpdate->sector=$request['sectorCompany'];
                $companyUpdate->phone=$request['phoneCompany'];
                $companyUpdate->mobile_phone=$request['mobile_phoneCompany'];
                $companyUpdate->email=$request['emailCompany'];
                $companyUpdate->save();
                $companyId = $companyUpdate->id;

        
                $legalRepresentativeUpdate = \App\customerLegalRepresentative::find($request['documentId']);
                $legalRepresentativeUpdate->second_name=$request['second_name'];
                $legalRepresentativeUpdate->second_last_name=$request['second_last_name'];
                $legalRepresentativeUpdate->birth_date=$request['birthdate'];
                $legalRepresentativeUpdate->birth_city=$request['birthCity'];
                $legalRepresentativeUpdate->phone=$request['phone'];
                $legalRepresentativeUpdate->mobile_phone=$request['mobile_phone'];
                $legalRepresentativeUpdate->address=$request['address'];
                $legalRepresentativeUpdate->email=$request['email'];
                $legalRepresentativeUpdate->city_id=$request['city'];
                $legalRepresentativeUpdate->nacionality_id=$request['nationality'];
                $legalRepresentativeUpdate->civil_status_id=$request['civilState'];
                $legalRepresentativeUpdate->sector=$request['sector'];
                $legalRepresentativeUpdate->parroquia=$request['parroquia'];
                $legalRepresentativeUpdate->save();
                $legalRepresentativeId = $legalRepresentativeUpdate->id;        
                
                        
                $legalPersonVinculation =\App\vinculation_form::find($legalPersonvinculationSearch->id);
                $legalPersonVinculation->sales_id=$request['saleId'];
                $legalPersonVinculation->company_id=$companyId;
                $legalPersonVinculation->economic_activity_id=$request['occupation'];
                $legalPersonVinculation->constitution_date=$request['constitution_dateCompany'];
                $legalPersonVinculation->main_road=$request['main_roadCompany'];
                $legalPersonVinculation->city_id=$request['cityCompany'];
                $legalPersonVinculation->secondary_road=$request['secondary_roadCompany'];
                $legalPersonVinculation->address_number=$request['numberCompany'];
                $legalPersonVinculation->address_zone=$request['sectorCompany'];
                $legalPersonVinculation->phone=$request['phone'];
                $legalPersonVinculation->mobile_phone=$request['mobile_phone'];
                $legalPersonVinculation->email=$request['email'];

                $legalPersonVinculation->customer_legal_representative_id=$legalRepresentativeId;
                $legalPersonVinculation->nationality_id=$request['nationality'];
                $legalPersonVinculation->birth_place=$request['birthCity'];
                $legalPersonVinculation->birth_date=$request['birthdate'];
                $legalPersonVinculation->civil_state=$request['civilState'];

                $legalPersonVinculation->spouse_document=$request['spouseDocument'];
                $legalPersonVinculation->spouse_document_id=$request['spouse_document_id'];
                $legalPersonVinculation->spouse_name=$request['spouseFirstName'];
                $legalPersonVinculation->spouse_last_name=$request['spouseLastName'];

                $legalPersonVinculation->benefitiary_document=$request['beneficiary_document'];
                $legalPersonVinculation->benefitiary_document_id=$request['beneficiary_document_id'];
                $legalPersonVinculation->benefitiary_nationality_id=$request['beneficiary_nationality'];
                $legalPersonVinculation->benefitiary_name=$request['beneficiaryName'];
                $legalPersonVinculation->benefitiary_address=$request['beneficiary_address'];
                $legalPersonVinculation->benefitiary_phone=$request['beneficiary_phone'];
                $legalPersonVinculation->benefitiary_relationship=$request['beneficiary_relationship'];
                $legalPersonVinculation->save();
            }
        } 
     }    
      
     public function secondStepForm(request $request){
        //        return $request;
                //Store Vinculation Data
                $legalPersonvinculationSearch = \App\vinculation_form::where('customer_legal_representative_id','=',$request['documentId'])->where('sales_id','=',$request['saleId'])->where('company_id','=',$request['companyId'])->latest()->first();
                if($legalPersonvinculationSearch){
                    if($legalPersonvinculationSearch->status_id == 1){
                        //NADA
                    }else{
                        $annualIncome=$request['annual_income'];
                        $legalPersonVinculation = \App\vinculation_form::find($legalPersonvinculationSearch->id);
                        $legalPersonVinculation->annual_income = str_replace(',','',$annualIncome);                        
                        $legalPersonVinculation->save();
                    }
                }
            }
        public function thirdStepForm(request $request){
                //Store Vinculation Data
                $legalPersonvinculationSearch = \App\vinculation_form::where('customer_legal_representative_id','=',$request['documentId'])->where('sales_id','=',$request['saleId'])->where('company_id','=',$request['companyId'])->latest()->first();
                
                if($legalPersonvinculationSearch){
                    if($legalPersonvinculationSearch->status_id == 1){
                        //NADA
                    }else{
                        $vinculation = \App\vinculation_form::find($legalPersonvinculationSearch->id);
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
                  $legalPersonvinculationSearch = \App\vinculation_form::where('customer_legal_representative_id','=',$request['documentId'])->where('sales_id','=',$request['saleId'])->where('company_id','=',$request['companyId'])->latest()->first();
                
                    if($legalPersonvinculationSearch){
                        if($legalPersonvinculationSearch->status_id == 1 || $legalPersonvinculationSearch->status_id == 23){
                            //NADA
                        }else{
                            //Vehicle Folder
                            $vehiFolder = public_path('images/vinculation/'.$legalPersonvinculationSearch->id.'/');
                            //Create Vehicle Folder
                            if (!file_exists($vehiFolder)) {
                                mkdir($vehiFolder, 0777, true);
                            }
        
                            $image = $request->file('select_file'.$request['uploadType']);
                            $new_name = rand() . '_'.$request['uploadType'].'.' . $image->getClientOriginalExtension();
                            
                            $name = 'Images/Vinculation/'.$legalPersonvinculationSearch->sales_id.'/'.$new_name;
                            $path = Storage::disk('s3')->put($name, file_get_contents($image));
                            
                            $url = Storage::disk('s3')->url($name);
                                                
                            $vinculation = \App\vinculation_form::find($legalPersonvinculationSearch->id);
                            if($request['uploadType'] == 'DocumentApplicant'){ $vinculation->picture_document_applicant  = $url; $vinculation->save(); $picture = $vinculation->picture_document_applicant; }
                            if($request['uploadType'] == 'DocumentSpouse'){ $vinculation->picture_document_spouse  = $url; $vinculation->save(); $picture = $vinculation->picture_document_spouse; }
                            if($request['uploadType'] == 'VotingBallotApplicant'){ $vinculation->picture_voting_ballot  = $url; $vinculation->save(); $picture = $vinculation->picture_voting_ballot; }
                            if($request['uploadType'] == 'VotingBallotSpouse'){ $vinculation->picture_voting_ballot_spouse  = $url; $vinculation->save(); $picture = $vinculation->picture_voting_ballot_spouse; }
                            if($request['uploadType'] == 'Service'){ $vinculation->picture_service  = $url; $vinculation->save(); $picture = $vinculation->picture_service; }
                            if($request['uploadType'] == 'Sri'){ $vinculation->picture_sri  = $url; $vinculation->save(); $picture = $vinculation->picture_sri; }
                            
                            if($request['uploadType'] == 'DocumentRuc'){ $vinculation->picture_ruc  = $url; $vinculation->save(); $picture = $vinculation->picture_ruc; }
                            if($request['uploadType'] == 'ConstitutionDeed'){ $vinculation->picture_constitution_deed  = $url; $vinculation->save(); $picture = $vinculation->picture_constitution_deed; }
                            if($request['uploadType'] == 'CertificateAppointment'){ $vinculation->picture_certificate_appointment  = $url; $vinculation->save(); $picture = $vinculation->picture_certificate_appointment; }
                            if($request['uploadType'] == 'ShareholdersPayroll'){ $vinculation->picture_shareholders_payroll  = $url; $vinculation->save(); $picture = $vinculation->picture_shareholders_payroll; }
                            if($request['uploadType'] == 'CertificateObligations'){ $vinculation->picture_certificate_obligations  = $url; $vinculation->save(); $picture = $vinculation->picture_certificate_obligations; }
                            if($request['uploadType'] == 'FinancialState'){ $vinculation->picture_financial_state  = $url; $vinculation->save(); $picture = $vinculation->picture_financial_state; }
                            
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
                $legalPersonVinculationSearch = \App\vinculation_form::where('customer_legal_representative_id','=',$request['data']['representativeLegal'])->where('sales_id','=',$request['data']['sale'])->where('company_id','=',$request['data']['company'])->latest()->first();
                    if($legalPersonVinculationSearch){
                        if($legalPersonVinculationSearch->status_id == 1 || $legalPersonVinculationSearch->status_id == 23){
                            //NADA
                        }else{
                            $vinculation = \App\vinculation_form::find($legalPersonVinculationSearch->id);
                            if ($request['data']['id'] == 'DocumentApplicant') { Storage::disk('s3')->delete($vinculation->picture_document_applicant); $vinculation->picture_document_applicant = null; $vinculation->save(); }
                            if ($request['data']['id'] == 'DocumentSpouse') { Storage::disk('s3')->delete($vinculation->picture_document_spouse); $vinculation->picture_document_spouse = null; $vinculation->save(); }
                            if ($request['data']['id'] == 'VotingBallotApplicant') { Storage::disk('s3')->delete($vinculation->picture_voting_ballot); $vinculation->picture_voting_ballot = null; $vinculation->save(); }
                            if ($request['data']['id'] == 'VotingBallotSpouse') { Storage::disk('s3')->delete($vinculation->picture_voting_ballot_spouse); $vinculation->picture_voting_ballot_spouse = null; $vinculation->save(); }
                            if ($request['data']['id'] == 'Service') { Storage::disk('s3')->delete($vinculation->picture_service); $vinculation->picture_service = null; $vinculation->save(); }
                            if ($request['data']['id'] == 'Sri') { Storage::disk('s3')->delete($vinculation->picture_sri); $vinculation->picture_sri = null; $vinculation->save(); }
        

                            if ($request['data']['id'] == 'DocumentRuc') { Storage::disk('s3')->delete($vinculation->picture_ruc); $vinculation->picture_ruc = null; $vinculation->save(); }
                            if ($request['data']['id'] == 'ConstitutionDeed') { Storage::disk('s3')->delete($vinculation->picture_constitution_deed); $vinculation->picture_constitution_deed = null; $vinculation->save(); }
                            if ($request['data']['id'] == 'CertificateAppointment') { Storage::disk('s3')->delete($vinculation->picture_certificate_appointment); $vinculation->picture_certificate_appointment= null; $vinculation->save(); }
                            if ($request['data']['id'] == 'ShareholdersPayroll') { Storage::disk('s3')->delete($vinculation->picture_shareholders_payroll); $vinculation->picture_shareholders_payroll = null; $vinculation->save(); }
                            if ($request['data']['id'] == 'CertificateObligations') { Storage::disk('s3')->delete($vinculation->picture_certificate_obligations); $vinculation->picture_certificate_obligations = null; $vinculation->save(); }
                            
                            if ($request['data']['id'] == 'FinancialState') { Storage::disk('s3')->delete($vinculation->picture_financial_state); $vinculation->picture_financial_state = null; $vinculation->save(); }
        
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
                $company = $request['companyId'];
                $vinculationSearch = \App\vinculation_form::where('customer_legal_representative_id','=',$request['legalRepresentativeId'])->where('sales_id','=',$sale)->where('company_id','=',$company)->get();
                $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
                $vinculation->document_applicant_date = $request['documentApplicantDate']; 
                $vinculation->document_spouse_date = $request['documentSpouseDate']; 
                $vinculation->viamatica_date = new \DateTime();
                $vinculation->save();
        
                //Variables for Vinculation PDF
                $sales = \App\sales::find($sale);
                $company = \App\companys::find($company);
                $legalRepresentative = \App\customerLegalRepresentative::find($sales->customer_legal_representative_id);
                $birthCountry = \App\country::find($vinculation->nationality_id);
                $birthCity = \App\city::find($vinculation->birth_place);
                $city = \App\city::find($vinculation->city_id);
                $province = \App\province::find($city->province_id);
                $country = \App\country::find($province->country_id);
                        
                $legalRepresentativeCity = \App\city::find($legalRepresentative->city_id);
                $legalRepresentativeProvince = \App\province::find($legalRepresentativeCity->province_id);
                $legalRepresentativeCountry = \App\country::find($legalRepresentativeProvince->country_id);

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
        
                $pdf = PDF::loadView('legalPersonVinculation.pdf',[
                            'legalRepresentative' => $legalRepresentative,
                            'company'=>$company,
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
                            'legalRepresentativeCity'=>$legalRepresentativeCity,
                            'legalRepresentativeCountry'=>$legalRepresentativeCountry,
                            'legalRepresentativeProvince'=>$legalRepresentativeProvince,
                ]);
                
                $output = $pdf->output();
                file_put_contents(public_path('legalPersonVinculation.pdf'), $output);
                
                $b64Doc = chunk_split(base64_encode(file_get_contents(public_path('legalPersonVinculation.pdf'))));
                
                $result = viamaticaSendPdf($legalRepresentative->document, $legalRepresentative->first_name, $legalRepresentative->mobile_phone, $sales->id, $b64Doc, $legalRepresentative->email, $legalRepresentative->phone, $vinculation->id);
                
                $vinculationUpdate = \App\vinculation_form::find($vinculationSearch[0]->id);
                $vinculationUpdate->viamatica_id = $result['data'][0];
                $vinculationUpdate->save();
                
                return $result;
                
            }
            public function send(request $request){
                //Update Customer Contact Data
                $legalRepresentativeSearch = \App\customerLegalRepresentative::find($request['legalRepresentativeId']);
                $legalRepresentativeSearch->mobile_phone = $request['mobilePhone'];
                $legalRepresentativeSearch->email = $request['email'];
                $legalRepresentativeSearch->save();

                $companySearch = \App\companys::find($request['companyId']);                
                
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
                $job = (new \App\Jobs\VinculationLegalPersonSendLinkEmailJobs($sale->id, $legalRepresentativeSearch->email, $legalRepresentativeSearch->document,$companySearch->document));
                dispatch($job);
            }
            
            public function update(request $request){
                $vinFormSearch = \App\vinculation_form::where('sales_id','=',$request['saleId'])->get();
                if(!$vinFormSearch->isEmpty()){
                    if($vinFormSearch[0]->status_id == 1){
                        $saleSearch = \App\sales::find($request['saleId']);
                        $pbc = \App\product_channel::find($saleSearch->pbc_id);
                        $result=\App\Jobs\listaObservadosyCarteraLegalPersonJobs::dispatchNow($pbc->canal_plan_id, $saleSearch->customer_legal_representative_id, $request['saleId'], \Auth::user()->email);
                                                    
                          if($result=='true'){
                              
                            \App\Jobs\vinculaClientesLegalPersonJob::dispatch($request['saleId']);  
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
                                
                            }
                        \Session::flash('informativeList', 'El cliente se encuentra en listas informativas.');   
                         return 'result';                            
                    }else{
                        return 'false';
                    }
                }
            }            
                         
    public function pdf(){
         set_time_limit(300);
         $company = \App\companys::find(1);
         $legalRepresentative = \App\customerLegalRepresentative::find(3);
         $sales = \App\sales::find(1582);
         $vinculation = \App\vinculation_form::find(1078);
         $birthCountry = \App\country::find($vinculation->nationality_id);
         $birthCity = \App\city::find($vinculation->birth_place);
         $city = \App\city::find($vinculation->city_id);
         $province = \App\province::find($city->province_id);
         $country = \App\country::find($province->country_id);
         $legalRepresentativeCity = \App\city::find($legalRepresentative->city_id);
         $legalRepresentativeProvince = \App\province::find($legalRepresentativeCity->province_id);
         $legalRepresentativeCountry = \App\country::find($legalRepresentativeProvince->country_id);
         $benefitiaryBirthCountry = \App\country::find($vinculation->benefitiary_nationality_id);
         
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
                    $viamaticaDate = date('d-m-Y');
                    $pdf = PDF::loadView('legalPersonVinculation.pdf',[
                          'legalRepresentative' => $legalRepresentative,
                          'company'=>$company,
                          'sales' => $sales,
                          'vinculation' => $vinculation,
                          'ecoActivity' => $ecoActivity,
                          'occupation' => $occupation,
                          'birthCountry' => $birthCountry,
                          'birthCity' => $birthCity,
                          'city' => $city,
                          'province' => $province,
                          'country' => $country,
                          'legalRepresentativeCity'=>$legalRepresentativeCity,
                          'legalRepresentativeCountry'=>$legalRepresentativeCountry,
                          'legalRepresentativeProvince'=>$legalRepresentativeProvince,
                          'benefitiaryBirthCountry'=>$benefitiaryBirthCountry,
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
    }
  //Validate Customer Document
  public static function documentAutoFill($id) {
    $validateId = validateId($id); if($validateId == true){ $typeDoc = 'C'; $documentId = 1; }else{ $typeDoc = 'P'; $documentId = 2; }
    $data = customerSS($typeDoc, $id);
    $string = explode(",", $data['infocliente']['cedula'][0]['celulares']);
    $mobilePhone = $string[0];
    
    $string = explode(",", $data['infocliente']['cedula'][0]['telefonos']);
    $phone = $string[0];

    $birthdate = str_replace("/", "-", $data['infocliente']['cedula'][0]['fechanacimiento']);
    $date = date_create($birthdate);
    $date = date_format($date,"Y-m-d");

    if ($data['error'][0]['code'] === '000') {
        $data = array('first_name' => $data['infocliente']['cedula'][0]['nombre1'],
            'last_name' => $data['infocliente']['cedula'][0]['apellido1'],
            'document' => $data['infocliente']['cedula'][0]['cedula'],
            'email' => $data['infocliente']['cedula'][0]['email'],
            'phone' => str_replace('-','',$phone),
            'mobile_phone' => str_replace('-','',$mobilePhone),
            'address' => $data['infocliente']['cedula'][0]['direcciones'],
            'province_id' => null,
            'province_name' => null,
            'city_id' => null,
            'city_name' => null,
            'country' => '0',
            'province' => '0',
            'document_id' => $documentId,
            'birthdate' => $data['infocliente']['cedula'][0]['fechanacimiento'],
            'nacionality' => null,
            'gender' => null,
            'civil_state' => null,
            'profession' => null,
            'activity' => null,
            'work_address' => null,
            'correspondence' => null,
            'birthdate' => $date,
            'second_name' => $data['infocliente']['cedula'][0]['nombre2'],
            'second_last_name' => $data['infocliente']['cedula'][0]['apellido2'],
            'success' => 'true');
    } else {
            $data = array('first_name' => '',
                'last_name' => '',
                'second_name' => '',
                'second_last_name' => '',
                'document' => $id,
                'email' => '',
                'city' => '',
                'document_id' => '0',
                'success' => 'false');
    }
    
    return $data;
  }

  public function beneficiariosPDF(){

    //return view('vinculation.pdf_beneficiario');
    set_time_limit(300);
         $company = \App\companys::find(1);
         $legalRepresentative = \App\customerLegalRepresentative::find(3);
         $sales = \App\sales::find(1582);
         $vinculation = \App\vinculation_form::find(1078);
         $birthCountry = \App\country::find($vinculation->nationality_id);
         $birthCity = \App\city::find($vinculation->birth_place);
         $city = \App\city::find($vinculation->city_id);
         $province = \App\province::find($city->province_id);
         $country = \App\country::find($province->country_id);
         $legalRepresentativeCity = \App\city::find($legalRepresentative->city_id);
         $legalRepresentativeProvince = \App\province::find($legalRepresentativeCity->province_id);
         $legalRepresentativeCountry = \App\country::find($legalRepresentativeProvince->country_id);
         $benefitiaryBirthCountry = \App\country::find($vinculation->benefitiary_nationality_id);
         
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
                    $viamaticaDate = date('d-m-Y');
                    $pdf = PDF::loadView('legalPersonVinculation.pdf_beneficiario',[
                          'legalRepresentative' => $legalRepresentative,
                          'company'=>$company,
                          'sales' => $sales,
                          'vinculation' => $vinculation,
                          'ecoActivity' => $ecoActivity,
                          'occupation' => $occupation,
                          'birthCountry' => $birthCountry,
                          'birthCity' => $birthCity,
                          'city' => $city,
                          'province' => $province,
                          'country' => $country,
                          'legalRepresentativeCity'=>$legalRepresentativeCity,
                          'legalRepresentativeCountry'=>$legalRepresentativeCountry,
                          'legalRepresentativeProvince'=>$legalRepresentativeProvince,
                          'benefitiaryBirthCountry'=>$benefitiaryBirthCountry,
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
}
}
