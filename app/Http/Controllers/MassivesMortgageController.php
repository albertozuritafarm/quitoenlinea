<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use File;
use Session;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use App\Jobs\EmailJobs;
use Illuminate\Pagination\Paginator;

class MassivesMortgageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }
    
    public function index(request $request){
        
    }
    
    public function storeExcel(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $excelArray = array();
        echo now();
        $collection = (new FastExcel)->configureCsv(';', '#', '\n', 'gbk')->import(public_path('format_hipoteca.xlsx'));

        foreach($collection as $c){
            $mortgage = new \App\massives_mortgage();
            $mortgage->codigo_suc_apertura = $c['Codigo Suc-Apertura'];
            $mortgage->codigo_age_apertura = $c['Codigo Age-Apertura'];
            $mortgage->sucursal_agencia_apertura = $c['Sucursal Agencia Apertura'];
            $mortgage->codigo_suc_manejo = $c['Codigo Suc-Manejo'];
            $mortgage->codigo_age_manejo = $c['Codigo Age-Manejo'];
            $mortgage->sucursal_agencia_manejo = $c['Sucursal Agencia Manejo'];
            $mortgage->producto = $c['Producto'];
            $mortgage->nombre_cliente = $c['Nombre Cliente'];
            $mortgage->identificacion = $c['Identificacion'];
            $mortgage->sexo = $c['Sexo'];
            $mortgage->fecha_nacimiento = $c['F.Nacimiento'];
            $mortgage->fecha_desembolso = $c['F.Desembolso/F.Compra'];
            $mortgage->fecha_vencimiento = $c['F.Vencimiento'];
            $mortgage->fecha_vencimiento_original = $c['F.Vcto Original'];
            $mortgage->numero_operacion = $c['No.Operacion'];
            $mortgage->status = $c['Status'];
            $mortgage->monto_prestamo = $c['Monto Prestamo'];
            $mortgage->saldo = $c['Saldo'];
            $mortgage->saldo_capital_cuota = $c['Saldo Capital Cuota'];
            $mortgage->saldo_interes_corriente_cuota = $c['Saldo Interes Corriente de Cuota'];
            $mortgage->tasa = $c['Tasa'];
            $mortgage->numero_cuota = $c['No.Cuota'];
            $mortgage->fecha_inicio_cuota = $c['F.Inicio Cuota'];
            $mortgage->fecha_cuota = $c['F.Cuota'];
            $mortgage->tipo_seguro = $c['Tipo Seguro'];
            $mortgage->factor_gravamen = $c['Factor Gravamen'];
            $mortgage->factor_incendio = $c['Factor Incendio'];
            $mortgage->factor_deuda_protegida = $c['Factor Deuda Protegida'];
            $mortgage->monto_asegurado = $c['Monto Asegurado'];
            $mortgage->valor_desgravamen = $c['Valor Desgravamen'];
            $mortgage->valor_incendio = $c['Valor Incendio'];
            $mortgage->valor_deuda_protegida = $c['Valor Deuda Protegida'];
            $mortgage->valor_seguro_vehiculo = $c['Valor Seguro de Vehiculo'];
            $mortgage->valor_seguro_dispositivo_rastreo = $c['Valor Seguro Dispositivo de Rastreo'];
            $mortgage->valor_seguros_premium = $c['Valor Seguros Premium'];
            $mortgage->numero_poliza_incendio = $c['No.Poliza Incendio'];
            $mortgage->nombre_conyuge = $c['Nombre del Conyuge'];
            $mortgage->identificacion_conyuge = $c['Id. del Conyuge'];
            $mortgage->fecha_nacimiento_conyuge = '';
            $mortgage->extraprimido = $c['C.Extraprimado'];
            $mortgage->nombre_oficial = $c['Nombre Oficial'];
            $mortgage->identificacion_aseguradora = $c['Identificacion Aseguradora'];
            $mortgage->nombre_aseguradora = $c['Nombre Aseguradora'];
            $mortgage->operacion_referencia = $c['Operacion Referencia'];
            $mortgage->save();
        }
        echo now();
    }
}
