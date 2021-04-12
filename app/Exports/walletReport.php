<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class walletReport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents {

    use Exportable;

    public function __construct(String $sql) {
        $this->sql = $sql;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);
        
        //Validate User Type Sucre
        $rol = \App\rols::find(\Auth::user()->role_id);
        
        //ROL SEGUROS SUCRE
        if ($rol->rol_entity_id == 1) {
            //ROL TIPO GERENCIA/EJECUTIVO
            if($rol->rol_type_id == 1 || $rol->rol_type_id == 2){
                $userSucre = null;
                $userSucreQuery = '';
            }else{
            // ROL TIPO EJECUTIVO
                $userSucre = true;
                $userSucreQuery = ' and pc.ejecutivo_ss_email = "'.\Auth::user()->email.'"';
            }
        }
        //ROL CANAL
        if($rol->rol_entity_id == 2){
            //ROL TIPO GERENCIA
            if($rol->rol_type_id == 1){
                $userSucre = true;
                $userSucreQuery = ' and c.id = ' . $channel[0]->id;
            }elseif($rol->rol_type_id == 2){
            // ROL TIPO JEFATURA
                $userSucre = true;
                $userSucreQuery = ' and a.id = "'.\Auth::user()->agen_id.'"';
            }else{
            // ROL TIPO EJECUTIVO
                $userSucre = true;
                $userSucreQuery = ' and s.user_id = "'.\Auth::user()->id.'"';
            }
        }
        
        $query = 'SELECT  p2.ramodes as ramo, s.poliza,  s.insured_value as valor_asegurado, c2.document as dni_aseguado, s.documento as establecimiento
        from sales s
        inner join products_channel pc on pc.id = s.pbc_id
        inner join products p2 on p2.id = pc.product_id 
        inner join channels c on c.id = pc.channel_id 
        inner join agent_ss gs on gs.id =  pc.agent_ss 
        inner join agencies a on a.id = s.agen_id
        inner join customers c2 on c2.id = s.customer_id 
        left join cities ci on ci.id = s.cus_city
        left join cities pro on pro.province_id = ci.province_id 
        left join charges cha2 on cha2.sales_id = s.id
        left join payments p on p.id = cha2.payments_id  
        left join payments_types pt on pt.id = p.payment_type_id
        WHERE s.status_id in (21)' . $this->sql . ' '.$userSucreQuery.' group by s.poliza';
        DB::statement("SET lc_time_names = 'es_ES'");
        $datas = DB::select($query);
        $arrFactura = array();
        
        foreach($datas as $data){
            if($data->establecimiento == null || $data->establecimiento == ''){
                $data->establecimiento = '';
                $data->pemisor = '';
                $data->factura = '';
            }else{
                $arrFactura = explode("-", $data->establecimiento);
                $data->establecimiento = $arrFactura[0];
                $data->pemisor = $arrFactura[1];
                $data->factura = $arrFactura[2];
            }
        }
        $collet = collect($datas);
        return $collet;
    }

    public function headings(): array {
        return [
            'RAMO',
            'PÓLIZA',
            'VALOR',
            'IDENTIFICADOR',
            'ESTABLECIMIENTO',
            'PEMISOR',
            'NUMFAC',
        ];
    }

      public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:G1'; // All headers
                $bodyRange = 'A2:W222';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'ffffff'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle($bodyRange)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle($cellRange)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('000099');
            },
        ];
    }

}
