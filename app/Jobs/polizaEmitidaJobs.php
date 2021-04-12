<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class polizaEmitidaJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $saleId;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($saleId)
    {
        $this->saleId = $saleId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sale = \App\sales::selectRaw('sales.id, pro.ramoid')
                            ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                            ->join('products as pro','pro.id','=','pbc.product_id')
                            ->where('sales.id','=',$this->saleId)
                            ->get();
        
        $result = devolucionPOlizaEmitidaSS($sale[0]->id, $sale[0]->ramoid);
        
        $saleUpdate = \App\sales::find($this->saleId);
        $saleUpdate->anexoid = $result['polizacertificado']['anexoid'];
        $saleUpdate->certificado = $result['polizacertificado']['certificado'];
        $saleUpdate->contratante = $result['polizacertificado']['contratante'];
        $saleUpdate->documento = $result['polizacertificado']['documento'];
        $saleUpdate->poliza = $result['polizacertificado']['poliza'];
        $saleUpdate->tipodoc = $result['polizacertificado']['tipodoc'];
        $saleUpdate->status_id = 21;
        $saleUpdate->save();
        
        //Update Documentos
        documentosPolizaSS($this->saleId);
    }
}
