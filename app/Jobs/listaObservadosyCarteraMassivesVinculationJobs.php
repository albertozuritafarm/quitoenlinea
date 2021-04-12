<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class listaObservadosyCarteraMassivesVinculationJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;
    protected $customerId;
    protected $saleId;
    protected $email;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product, $customerId, $saleId, $email)
    {
        $this->product = $product;
        $this->customerId = $customerId;
        $this->saleId = $saleId;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(3000);
        $customer = \App\customers::find($this->customerId);
        $motive = '';
        $validate;
        //Consulta Lista Observados y Cartera Vencida SS
        $resultListaObservados = listaObservadosSS($this->product, 'N', $customer->first_name, $customer->second_name, $customer->last_name, $customer->second_last_name, 'C', $customer->document);
        $resultCarteraVencida = carteraVencidaSS($customer->document);
        
        $carteraVencida = false;
        $listaObservados = false;
        
        if($resultCarteraVencida['error'][0]['code'] == '003') { $carteraVencida = false; } elseif ($resultCarteraVencida['error'][0]['code'] == '000') { if ($resultCarteraVencida['carteravencida'] == 'false') { $carteraVencida = false; } else { $motive .= 'Cartera Vencida'; $carteraVencida = true; } } else { $carteraVencida = '500'; }
        if ($resultListaObservados['error'][0]['code'] == '003') { $listaObservados = false; } elseif ($resultListaObservados['error'][0]['code'] == '000') { if ($resultListaObservados['listaobservados']['indicador'] == 1) { $listaObservados = false; } else { $motive .= ', Lista de Observados'; $listaObservados = true; } } else { $listaObservados = '500'; }
        //ERROR NO IDENTIFICADO
        if($carteraVencida === '500' || $listaObservados === '500'){
            $salesUpdate = \App\sales::find($this->saleId);
            $salesUpdate->status_id = 32;
            $salesUpdate->save();
        }else{
            if($carteraVencida == true || $listaObservados == true){
                $salesUpdate = \App\sales::find($this->saleId);
                $salesUpdate->status_id = 24;
                $salesUpdate->codigo_solicitud_ipla = $resultListaObservados['listaobservados']['codigosolicitud'];
                $salesUpdate->save();

                $customerUpdate = \App\customers::find($this->customerId);
                $customerUpdate->status_id = 24;                                                                                
                $customerUpdate->save();            

                $job = (new \App\Jobs\infoListsUserEmailMassivesVinculationJobs($this->saleId, $this->email, $customer->document));
                dispatch($job);

                //Ejecutivo Email
                $email = \App\product_channel::selectRaw('products_channel.ejecutivo_ss_email')
                                                ->join('sales as sal','sal.pbc_id','=','products_channel.id')
                                                ->where('sal.id','=',$this->saleId)
                                                ->get();

                infoListsEjecutivoSSEmailMassivesVinculationJobs::dispatch($this->saleId, $email[0]->ejecutivo_ss_email, $customer->document, $motive);
                $validate=false;
            }else{
                $salesUpdate = \App\sales::find($this->saleId);
                $salesUpdate->status_id = 20;
                $salesUpdate->save();
                $validate=true;
            }
        }
        return $validate;
    }
}