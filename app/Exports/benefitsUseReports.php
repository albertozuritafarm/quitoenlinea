<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class benefitsUseReports implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents 
{
     use Exportable;

    public function __construct(String $sql) {
        $this->sql = $sql;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "'.\Auth::user()->agen_id.'"';
        $channel = DB::select($channelQuery);
        
        $sql = '';
        //Validate User Role
        if(\Auth::user()->role_id == 4){
            $sql .= ' AND chan.id = '.$channel[0]->id;
        }
        
        $query = 'select
                    cou.name as "country",
                    pro.name as "province",
                    cit.name as "city",
                    agen.name as "agency",
                    chan.name as "channel",
                    sType.name as "type",
                    ben.name as "benefit",
                    vehi.plate as "plate"
                    from benefits ben
                    join benefits_vehicles_sales benVSal on benVSal.benefits_id = ben.id
                    join benefits_vehicles_sales_uses benVSalUse on benVSalUse.benefits_vsal_id = benVSal.id
                    join vehicles_sales vSal on vSal.id = benVSal.vsal_id
                    join sales sal on sal.id = vSal.sales_id
                    join channels chan on chan.id = ben.channels_id
                    join agencies agen on agen.channel_id = chan.id
                    join cities cit on cit.id = agen.city_id
                    join provinces pro on pro.id = cit.province_id
                    join countries cou on cou.id = pro.country_id
                    join sales_type sType on sType.id = sal.sales_type_id
                    join vehicles vehi on vehi.id = vSal.vehicule_id
                    where vehi.plate is not null '.$this->sql.' '.$sql;

        $datas = DB::select($query);
//        $total = 0;
//        $totalUse = 0;
//        //sum all Totals
//        foreach($datas as $data){
//            $total += $data->vehiCount;
//            $totalUse += $data->vehiUseCount;
//        }
//        //Calculate Percentage
//        foreach($datas as $data){
//            $value = round((($data->vehiUseCount * 100)/$total),2);
//            $data->percentage = $value . '%';
//            if($data->vehiUseCount == 0){
//                $data->vehiUseCount = "0";
//            }
//        }
        $collet = collect($datas);
        return $collet;
    }

    public function headings(): array {
        return [
            'Pais',
            'Provincia',
            'Ciudad',
            'Agencia',
            'Canal',
            'Tipo',
            'Beneficio',
            'Placa',
        ];
    }

      public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:H1'; // All headers
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
