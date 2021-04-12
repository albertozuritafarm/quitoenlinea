<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class schedulingReports implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle {

    use Exportable;

    public function __construct(String $sql) {
        $this->sql = $sql;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        $query = 'select cou.name as "country",
                    pro.name as "province",
                    cit.name as "city",
                    chan.name as "channel",
                    sType.name as "type",
                    concat(usr.first_name," ",usr.last_name) as "adviser",
                    loca.name as "location",
                    deta.paint as "paint",
                    dType.name as "damage",
                    sta.name as "status",
                    count(deta.id) as "count",
                    0 as "percentage"
                    from scheduling sche
                    join scheduling_details deta on deta.scheduling_id = sche.id
                    join vehicles_sales vSal on vSal.id = sche.vehicles_sales_id
                    join sales sal on sal.id = vSal.sales_id
                    join users usr on usr.id = sal.user_id
                    join agencies agen on agen.id = sal.agen_id
                    join channels chan on chan.id = agen.channel_id
                    join cities cit on cit.id = agen.city_id
                    join provinces pro on pro.id = cit.province_id
                    join countries cou on cou.id = pro.country_id
                    join sales_type sType on sType.id = sal.sales_type_id
                    join damage_type dType on dType.id = deta.damage_type_id
                    join service_location loca on loca.id = deta.service_location_id
                    join status sta on sta.id = deta.status_id
                    where deta.damage_type_id is not null '.$this->sql.'
                    group by sta.id, loca.id, usr.id, deta.paint';
        
        $datas = DB::select($query);
        $total = 0;
        //sum all Totals
        foreach ($datas as $data) {
            $total += $data->count;
        }
        //Calculate Percentage
        foreach ($datas as $data) {
            $value = round((($data->count * 100) / $total), 2);
            $data->percentage = $value . '%';
        }
        //Add Total Row
        $add = [
            'country' => '',
            'province' => '',
            'city' => '',
            'channel' => '',
            'type' => '',
            'adviser' => '',
            'location' => '',
            'paint' => '',
            'damage' => '',
            'status' => 'Total',
            "count" => $total,
            'percentage' => ''
        ];
        array_push($datas, $add);

        $collet = collect($datas);
        return $collet;
    }

    public function headings(): array {
        return [
            'Pais',
            'Provincia',
            'Ciudad',
            'Canal',
            'Tipo',
            'Asesor',
            'Lugar de Servicio',
            'Pintura',
            'Tipo de Golpe',
            'Estado',
            'Cantidad',
            '%',
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:L1'; // All headers
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
    public function title(): string
    {
        return 'Reporte';
    }

}
