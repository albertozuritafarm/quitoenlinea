<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class coverageStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $coverages;
    private $productId;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($coverages, $productId)
    {
        $this->coverages = $coverages;
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->coverages as $cobertura){
            $coverageSearch = \App\coverage::where('coberturaid','=',$cobertura['coberturaid'])->where('product_id','=',$this->productId)->get();
                if($coverageSearch->isEmpty()){
                    $cob = new \App\coverage();
                    $cob->coberturades = $cobertura['coberturades'];
                    $cob->coberturaid = $cobertura['coberturaid'];
                    $cob->principal = $cobertura['principal'];
                    $cob->tipocoberturades = $cobertura['tipocoberturades'];
                    $cob->tipocoberturaid = $cobertura['tipocoberturaid'];
                    $cob->valorasegurado = $cobertura['valorasegurado'];
                    $cob->product_id = $this->productId;
                    $cob->plandetindvis = $cobertura['plandetindvis'];
                    $cob->ordenimp = $cobertura['ordenimp'];
                    $cob->texto = $cobertura['texto'];
                    $cob->plandetindsMto = $cobertura['plandetindsMto'];
                    $cob->plandetindspri = $cobertura['plandetindspri'];
                    $cob->plandetprima = $cobertura['plandetprima'];
                    $cob->save();
                }else{
                    $cob = \App\coverage::find($coverageSearch[0]->id);
                    $cob->coberturades = $cobertura['coberturades'];
                    $cob->coberturaid = $cobertura['coberturaid'];
                    $cob->principal = $cobertura['principal'];
                    $cob->tipocoberturades = $cobertura['tipocoberturades'];
                    $cob->tipocoberturaid = $cobertura['tipocoberturaid'];
                    $cob->valorasegurado = $cobertura['valorasegurado'];
                    $cob->product_id = $this->productId;
                    $cob->plandetindvis = $cobertura['plandetindvis'];
                    $cob->ordenimp = $cobertura['ordenimp'];
                    $cob->texto = $cobertura['texto'];
                    $cob->plandetindsMto = $cobertura['plandetindsMto'];
                    $cob->plandetindspri = $cobertura['plandetindspri'];
                    $cob->plandetprima = $cobertura['plandetprima'];
                    $cob->save();
                }
            }
    }
}
