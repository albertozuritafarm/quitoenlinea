<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;

class FtpController extends Controller
{
    public function uploadFiles($type) // type "1-emision 2-vinculation"
    {   

        if ($type == 1){ // consult sql emision

            $folderSql = 'SELECT DISTINCT(s.sales_id), p.ramoid
            FROM sales s
            INNER JOIN products_channel pc ON pc.product_id = s.pbc_id 
            INNER JOIN products p ON p.id = pc.product_id 
            WHERE s.sales_id NOT IN (SELECT sl.sales_id FROM sftp_log sl WHERE sl.type = 1)';
            $path = base_path('public/images/sales/');

        } elseif ($type == 2){ // consult sql vinculation

            $folderSql = 'SELECT DISTINCT(vf.id), p.ramoid
            FROM vinculation_form vf
            INNER JOIN sales s ON s.id = vf.sales_id 
            INNER JOIN products_channel pc ON pc.product_id = s.pbc_id 
            INNER JOIN products p ON p.id = pc.product_id 
            WHERE vf.sales_id NOT IN (SELECT sl.sales_id FROM sftp_log sl WHERE sl.type = 2)	
            AND vf.status_id = 1';
            $path = base_path('public/images/vinculation/');

        }

        $folders = DB::select($folderSql);
      
        foreach ($folders as $folder) {
            if($folder->ramoid == 7){
                $RAMO = 1;
            }
            $path = $path.$folder->id;
            $dir = opendir($path);

            $file = 'archivos.txt'; 
            $content = '';
            $codigo_app = 1;
            $ramo = 7;
            $numero_operacion = $folder->id;

            while ($element = readdir($dir)){
                if( $element != "." && $element != ".."){
                    if(!is_dir($path.$element) ){
                        $extencion = strstr($element, '.');
                        $nombre = strstr($element, '.', true);
                        $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $nombre . "\t" . $extencion . "\n";

                        //Store data log
                        $sftp_logNew = new \App\sftp_log();
                        $sftp_logNew->type = $type;
                        $sftp_logNew->cod_app = $codigo_app;
                        $sftp_logNew->ramo = $ramo;
                        $sftp_logNew->sales_id = $numero_operacion;
                        $sftp_logNew->name = $nombre;
                        $sftp_logNew->extension = $extencion;
                        $sftp_logNew->save();

                        // upload file to sftp
                        $nameRemoteFile = 'archivos/recepcion/'.$element;
                        $nameLocalFile = 'images/vinculation/'.$folder->id.'/'.$element;
                        Storage::disk('sftp')->put($nameRemoteFile, Storage::disk('public_sftp')->get($nameLocalFile));
                    }
                }
            }
            file_put_contents(base_path('public/images/vinculation/').$file, $content, LOCK_EX);
        }
 
        $nameRemoteFile = 'archivos/recepcion/'.$file;
        $nameLocalFile = 'images/vinculation/'.$file;
        Storage::disk('sftp')->put($nameRemoteFile, Storage::disk('public_sftp')->get($nameLocalFile));
        //Storage::disk('sftp')->getAdapter()->disconnect();

        //message
        return('Operación realizada con éxito');
    }

    public function forwardFiles($response)
    {   
        
        $path = base_path('public/images/vinculation/'.$response['respuesta'][0]['numoperacion']);
        $dir = opendir($path);

        $file = 'archivos.txt'; 
        $content = '';
        $codigo_app = 1;
        $ramo = $response['respuesta'][0]['ramo'];
        $numero_operacion = $response['respuesta'][0]['numoperacion'];
        $files = $response['respuesta'][0]['archivos'];
        $sftp_logNew = new \App\sftp_log();

        while ($elemento = readdir($dir)){
            if( $elemento != "." && $elemento != "..")
            {
                if(!is_dir($path.$elemento) )
                {
                    $extencion = strstr($elemento, '.');
                    $nombre = strstr($elemento, '.', true); 
                    $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $name . "\t" . $extencion . "\n";
                    //Store data log
                        if (in_array($elemento, $files))
                        {    
                            $sftp_logNew->type = 2;
                            $sftp_logNew->cod_app = $codigo_app;
                            $sftp_logNew->ramo = $ramo;
                            $sftp_logNew->sales_id = $numero_operacion;
                            $sftp_logNew->name = $nombre;
                            $sftp_logNew->extension = $extencion;
                            $sftp_logNew->save();
                        }                  
                }
            }
        }
        
        file_put_contents(base_path('public/images/vinculation/').$file, $content, LOCK_EX);
        dd("CREA ARCHIVO");
    
        //upload file local address
        $fileLocal = base_path('public/images/vinculation/123/firma-david.png');

        //download file remote address
        $fileContents = '/archivos/recepcion';

        //upload file
        Storage::disk('sftp')->put($fileLocal, $fileContents);
        //Storage::disk('sftp')->getAdapter()->disconnect();
        //message
        return('Operación realizada con éxito');
    }

    public function receivingPayments() 
    {
        $consultSQL = 'SELECT S.sale_id, s.poliza, c.document, ch.invoice, p.value, df.lot, df.reference, p2.ramoid
        FROM sales s
        INNER JOIN customers c ON c.id = s.customer_id 
        INNER JOIN charges ch ON ch.customers_id = c.id
        INNER JOIN payments p ON p.customer_id = s.customer_id 
        INNER JOIN datafast_log df ON df.sales_id = s.id 
        INNER JOIN products_channel pc ON pc.id = s.pbc_id 
        INNER JOIN products p2 ON p2.id = pc.product_id 
        WHERE p.created_at = NOW();';
        $payments = DB::select($consultSQL);

        $consultSQL = 'SELECT COUNT(*) as registros , SUM(p.value) as total 
        FROM sales s
        INNER JOIN customers c ON c.id = s.customer_id 
        INNER JOIN charges ch ON ch.customers_id = c.id
        INNER JOIN payments p ON p.customer_id = s.customer_id 
        INNER JOIN datafast_log df ON df.sales_id = s.id 
        INNER JOIN products_channel pc ON pc.id = s.pbc_id 
        INNER JOIN products p2 ON p2.id = pc.product_id 
        WHERE p.created_at = NOW();';
        $total_data = DB::select($consultSQL);

        $date = date("Ymd");
        $version = 1;
        $file = 'PAGOS_1_'.$date.'_' .$version. '.txt'; 
        $content = '';

        // file header
        $cod_app = 1;
        $numregistros = $total_data[0]->registros;
        $sumtotalpagos = $total_data[0]->total;

        $content .= $cod_app . "\t" . $numregistros . "\t" . $sumtotalpagos . "\n";

        foreach ($payments as $payment)
        {
            // file detail
            $numero_operacion = $payment->sale_id;
            $ramo = $payment->ramoid;
            $poliza = $payment->poliza; 
            $certificado = ''; //no aplica
            $contratante = $payment->document; 
            $factura = $payment->invoice; 
            $formaPago = 82;
            $marcatrj = ''; // no aplica
            $entidadfinanciera = ''; // no aplica
            $valorpago = $payment->value; 
            $lote = $payment->lot; 
            $recap = $payment->lot; 
            $referencia = $payment->reference;

            $content .= $numero_operacion . "\t" . $ramo . "\t" . $poliza . "\t" . $certificado . "\t" . $contratante . "\t" . $factura . "\t" . $formatoPago . "\t" . $marcatrj . "\t" . $entidadfinanciera . "\t" . $valorpago . "\t" . $lote . "\t" . $recap . "\t" . $referencia . "\t" . "\n"; 
        }

        file_put_contents(base_path('public/images/vinculation/').$file, $content, LOCK_EX);
        dd("CREA ARCHIVO");
            
        //upload file local address
        $fileLocal = base_path('public/images/vinculation/'.$file);

        //download file remote address
        $fileContents = '/pagos/recepcion';

        //upload file
        Storage::disk('sftp')->put($fileLocal, $fileContents);

        //message
        return('Operación realizada con éxito');
    }


    public function response()
    {

        $date = date("Ymd");
        $version = 1;
        $file = 'PAGOS_1_'.$date.'_' .$version. '.txt'; 

        $filepath = '/pagos/respuesta'.$file;
        Storage::disk('sftp')->get($filepath); 

        return('Operación realizada con éxito');
    }


}
