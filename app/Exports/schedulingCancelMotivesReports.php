<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class schedulingCancelMotivesReports implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents 
{
    use Exportable;

    public function __construct(String $sql) {
        $this->sql = $sql;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        $query = 'select moti.name as "name", count(moti.id) as "count", "0" as "percentage"
                    from cancel_motives moti
                    join scheduling_details deta on deta.cancel_motive_id = moti.id
                    join scheduling sche on sche.id = deta.scheduling_id
                    join vehicles_sales vsal on vsal.id = sche.vehicles_sales_id
                    join sales sal on sal.id = vsal.sales_id
                    join users usr on usr.id = sal.user_id
                    join cities cit on cit.id = usr.city_id
                    join provinces pro on pro.id = cit.province_id
                    join countries cou on cou.id = pro.country_id
                    join agencies agen on agen.id = sal.agen_id
                    join channels chan on chan.id = agen.channel_id
                    where deta.status_id = 4 '.$this->sql.'
                    group by moti.id ';

        $datas = DB::select($query);
        $total = 0;
        //sum all Totals
        foreach($datas as $data){
            $total += $data->count;
        }
        //Calculate Percentage
        foreach($datas as $data){
            $value = round((($data->count * 100)/$total),2);
            $data->percentage = $value . '%';
        }
        $collet = collect($datas);
        return $collet;
    }

    public function headings(): array {
        return [
            'Motive',
            'Cantidad',
            '% Acumulado'
        ];
    }

      public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:C1'; // All headers
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
