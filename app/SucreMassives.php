<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;

class SucreMassives extends Model
{
    protected $table = 'sucre_massives';
    protected $hidden = ['created_at', 'updated_at', 'id'];
    
    public static function storeExcel(){
    	flush();
        ob_flush();
    	set_time_limit(0);
        ini_set('max_execution_time', 60000);
        ini_set('memory_limit', '-1');
        echo now();
        echo 'empieza';
        echo '<br>';
        $collection = (new FastExcel)->configureCsv(';', '#', ''. PHP_EOL, 'gbk')->import(public_path('sucre_masivos.xlsx'));

        echo now();
        echo count($collection);echo'<br>';

        echo now();
        echo 'empieza a hacer los insert';
        echo '<br>';
        foreach($collection as $row_index=>$row){
            $col_headers = array_keys($row);

            //Store Sucre Massives
            $sucreMassives = new SucreMassives();
            $sucreMassives->channel_sponsor = $row['CANAL / SPONSOR'];
            $sucreMassives->agency_sponsor = $row['AGENCIA SPONSOR'];
            $sucreMassives->advisor_sponsor = $row['ASESOR'];
            $sucreMassives->operation_number_sponsor = $row['No. DE OPERACIÓN'];
            $sucreMassives->agency_insurance = $row['AGENCIA'];
            $sucreMassives->agent_insurance = $row['AGENTE'];
            $sucreMassives->operation_number_insurance = $row['No. DE CERTIFICADO'];
            $sucreMassives->branch_product = $row['RAMO'];
            $sucreMassives->sale_type_product = $row['TIPO DE VENTA'];
            $sucreMassives->policy_number_product = $row['NUMERO DE POLIZA'];
            $sucreMassives->name_product = $row['PRODUCTO'];
            $sucreMassives->fee_net_product = $row['PRIMA NETA'];
            $sucreMassives->fee_total_product = $row['PRIMA TOTAL'];
            $sucreMassives->begin_date_validity_product = $row['FEC. INICIO VIGENCIA'];
            $sucreMassives->end_date_validity_product = $row['FEC. FIN VIGENCIA'];
            $sucreMassives->begin_date_operation_product = $row['FEC. INICIO OPERACIÓN'];
            $sucreMassives->end_date_operation_product = $row['FEC. FIN OPERACIÓN'];
            $sucreMassives->date_cut_product = $row['FEC. DE CORTE'];
            $sucreMassives->province_customer = $row['PROVINCIA'];
            $sucreMassives->city_customer = $row['CANTON'];
            $sucreMassives->nationality_customer = $row['NACIONALIDAD'];
            $sucreMassives->document_customer = $row['No. CEDULA'];
            $sucreMassives->last_name_customer = $row['APELLIDOS'];
            $sucreMassives->first_name_customer = $row['NOMBRES'];
            $sucreMassives->gender_customer = $row['GENERO'];
            $sucreMassives->birth_date_customer = $row['FECHA NACIMIENTO'];
            $sucreMassives->occupation_customer = $row['OCUPACIÓN / ACTIVIDAD ECONÓMICA'];
            $sucreMassives->civil_state_customer = $row['ESTADO CIVIL'];
            $sucreMassives->address_customer = $row['DIRECCION'];
            $sucreMassives->phone_customer = $row['TELEFONO FIJO'];
            $sucreMassives->mobile_phone_customer = $row['TELEFONO MOVIL'];
            $sucreMassives->email_customer = $row['E-MAIL'];
            $sucreMassives->save();
        }

    echo now();
    echo 'termina de hacer los insert';
    echo '<br>';
    }
    
    public static function storeExcelBiess(){
    	flush();
        ob_flush();
    	set_time_limit(0);
        ini_set('max_execution_time', 60000);
        ini_set('memory_limit', '-1');
        echo now();
        echo '<br>';
        echo 'empieza';
        echo '<br>';
//        $collection = (new FastExcel)->configureCsv(';', '#', ''. PHP_EOL, 'gbk')->import(public_path('biess_masivo.xlsx'));
//        $collection = (new FastExcel)->import(public_path('biess_masivo.xlsx'));
        $collection = (new FastExcel)->import(public_path('biess_incendio.xlsx'));

        echo now();
        echo '<br>';
        echo count($collection);echo'<br>';

        echo now();
        echo '<br>';
        echo 'empieza a hacer los insert';
        echo '<br>';
        foreach($collection as $row){
            //Store Sucre Massives
            $sucreMassives = new SucreMassives();
            $sucreMassives->channel_sponsor = $row['CANAL / SPONSOR'];
            $sucreMassives->agency_sponsor = $row['AGENCIA SPONSOR'];
            $sucreMassives->advisor_sponsor = $row['ASESOR'];
            $sucreMassives->operation_number_sponsor = $row['No. DE OPERACIÓN'];
            $sucreMassives->agency_insurance = $row['AGENCIA'];
            $sucreMassives->agent_insurance = $row['AGENTE'];
            $sucreMassives->operation_number_insurance = $row['No. DE CERTIFICADO'];
            $sucreMassives->branch_product = $row['RAMO'];
            $sucreMassives->sale_type_product = $row['TIPO DE VENTA'];
            $sucreMassives->policy_number_product = $row['NUMERO DE POLIZA'];
            $sucreMassives->name_product = $row['PRODUCTO'];
            $sucreMassives->fee_net_product = $row['PRIMA NETA'];
            $sucreMassives->fee_total_product = $row['PRIMA TOTAL'];
            $sucreMassives->begin_date_validity_product = $row['FEC. INICIO VIGENCIA'];
            $sucreMassives->end_date_validity_product = $row['FEC. FIN VIGENCIA'];
            $sucreMassives->begin_date_operation_product = $row['FEC. INICIO OPERACIÓN'];
            $sucreMassives->end_date_operation_product = $row['FEC. FIN OPERACIÓN'];
            $sucreMassives->date_cut_product = $row['FEC. DE CORTE'];
            $sucreMassives->province_customer = $row['PROVINCIA'];
            $sucreMassives->city_customer = $row['CANTON'];
            $sucreMassives->nationality_customer = $row['NACIONALIDAD'];
            $sucreMassives->document_customer = $row['No. CEDULA'];
            $sucreMassives->last_name_customer = $row['APELLIDOS'];
            $sucreMassives->first_name_customer = $row['NOMBRES'];
            $sucreMassives->gender_customer = $row['GENERO'];
            $sucreMassives->birth_date_customer = $row['FECHA NACIMIENTO'];
            $sucreMassives->occupation_customer = $row['OCUPACIÓN / ACTIVIDAD ECONÓMICA'];
            $sucreMassives->civil_state_customer = $row['ESTADO CIVIL'];
            $sucreMassives->address_customer = $row['DIRECCION'];
            $sucreMassives->phone_customer = $row['TELEFONO FIJO'];
            $sucreMassives->mobile_phone_customer = $row['TELEFONO MOVIL'];
            $sucreMassives->email_customer = $row['E-MAIL'];
            $sucreMassives->save();
        }

    echo now();
    echo '<br>';
    echo 'termina de hacer los insert';
    echo '<br>';
    }
    
    public static function storeExcelPacifico(){
    	flush();
        ob_flush();
    	set_time_limit(0);
        ini_set('max_execution_time', 60000);
        ini_set('memory_limit', '-1');
        echo now();
        echo 'empieza';
        echo '<br>';
        $collection = (new FastExcel)->configureCsv(';', '#', ''. PHP_EOL, 'gbk')->import(public_path('sucre_masivos.xlsx'));

        echo now();
        echo count($collection);echo'<br>';

        echo now();
        echo 'empieza a hacer los insert';
        echo '<br>';
        foreach($collection as $row_index=>$row){
            $col_headers = array_keys($row);

            //Store Sucre Massives
            $sucreMassives = new SucreMassives();
            $sucreMassives->channel_sponsor = $row['CANAL / SPONSOR'];
            $sucreMassives->agency_sponsor = $row['AGENCIA SPONSOR'];
            $sucreMassives->advisor_sponsor = $row['ASESOR'];
            $sucreMassives->operation_number_sponsor = $row['No. DE OPERACIÓN'];
            $sucreMassives->agency_insurance = $row['AGENCIA'];
            $sucreMassives->agent_insurance = $row['AGENTE'];
            $sucreMassives->operation_number_insurance = $row['No. DE CERTIFICADO'];
            $sucreMassives->branch_product = $row['RAMO'];
            $sucreMassives->sale_type_product = $row['TIPO DE VENTA'];
            $sucreMassives->policy_number_product = $row['NUMERO DE POLIZA'];
            $sucreMassives->name_product = $row['PRODUCTO'];
            $sucreMassives->fee_net_product = $row['PRIMA NETA'];
            $sucreMassives->fee_total_product = $row['PRIMA TOTAL'];
            $sucreMassives->begin_date_validity_product = $row['FEC. INICIO VIGENCIA'];
            $sucreMassives->end_date_validity_product = $row['FEC. FIN VIGENCIA'];
            $sucreMassives->begin_date_operation_product = $row['FEC. INICIO OPERACIÓN'];
            $sucreMassives->end_date_operation_product = $row['FEC. FIN OPERACIÓN'];
            $sucreMassives->date_cut_product = $row['FEC. DE CORTE'];
            $sucreMassives->province_customer = $row['PROVINCIA'];
            $sucreMassives->city_customer = $row['CANTON'];
            $sucreMassives->nationality_customer = $row['NACIONALIDAD'];
            $sucreMassives->document_customer = $row['No. CEDULA'];
            $sucreMassives->last_name_customer = $row['APELLIDOS'];
            $sucreMassives->first_name_customer = $row['NOMBRES'];
            $sucreMassives->gender_customer = $row['GENERO'];
            $sucreMassives->birth_date_customer = $row['FECHA NACIMIENTO'];
            $sucreMassives->occupation_customer = $row['OCUPACIÓN / ACTIVIDAD ECONÓMICA'];
            $sucreMassives->civil_state_customer = $row['ESTADO CIVIL'];
            $sucreMassives->address_customer = $row['DIRECCION'];
            $sucreMassives->phone_customer = $row['TELEFONO FIJO'];
            $sucreMassives->mobile_phone_customer = $row['TELEFONO MOVIL'];
            $sucreMassives->email_customer = $row['E-MAIL'];
            $sucreMassives->save();
        }

    echo now();
    echo 'termina de hacer los insert';
    echo '<br>';
    }
}
