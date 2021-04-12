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

class schedulingDetailReports implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle 
{
    use Exportable;

    public function __construct(String $sql) {
        $this->sql = $sql;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        $query = 'select DATE_FORMAT(sche.date, "%d-%m-%Y")  as "date",
                    cou.name as "country",
                    pro.name as "province",
                    cit.name as "city",
                    agen.name as "agency",
                    concat(usr.first_name," ",usr.last_name) as "adviser",
                    vehi.plate as "plate",
                    vehi.color as "color",
                    concat(vehi.model, " ",bran.name) as "brand",
                    vehi.year as "year",
                    cus.document as "document",
                    cus.last_name as "last_name",
                    cus.first_name as "first_name",
                    sal.cus_mobile_phone as "phone",
                    sal.cus_email as "email",
                    chan.name as "channel",
                    sType.name as "type",
                    if(sal.status_id = 1, "SI","NO") as "coverage",
                    deta.paint as "paint",
                    loca.name as "location",
                    deta.address as "address",
                    DATE_FORMAT(deta.begin_date, "%d-%m-%Y") as "dateDetail",
                    DATE_FORMAT(deta.begin_date, "%H:%i") as "hour",
                    CONCAT(FLOOR(deta.estimated_time/60),":",LPAD(MOD(deta.estimated_time,60),2,"0")) as "time",
                    dama.name as "damage",
                    sta.name as "status",
                    moti.name as "observations"
                     from scheduling sche
                    join scheduling_details deta on deta.scheduling_id = sche.id
                    join vehicles_sales vSal on vSal.id = sche.vehicles_sales_id
                    join vehicles vehi on vehi.id = vSal.vehicule_id
                    join vehicles_brands bran on bran.id = vehi.brand_id
                    join sales sal on sal.id = vSal.sales_id
                    join customers cus on cus.id = sal.customer_id
                    join users usr on usr.id = sal.user_id
                    join agencies agen on agen.id = sal.agen_id
                    join channels chan on chan.id = agen.channel_id
                    join cities cit on cit.id = agen.city_id
                    join provinces pro on pro.id = cit.province_id
                    join countries cou on cou.id = pro.country_id
                    join sales_type sType on sType.id = sal.sales_type_id
                    join damage_type dType on dType.id = deta.damage_type_id
                    join service_location loca on loca.id = deta.service_location_id
                    join damage_type dama on dama.id = deta.damage_type_id
                    left join cancel_motives moti on moti.id = deta.cancel_motive_id
                    join status sta on sta.id = deta.status_id
                    where deta.scheduling_id is not null '.$this->sql.'';

        $datas = DB::select($query);
//        $total = 0;
//        //sum all Totals
//        foreach ($datas as $data) {
//            $total += $data->count;
//        }
//        //Calculate Percentage
//        foreach ($datas as $data) {
//            $value = round((($data->count * 100) / $total), 2);
//            $data->percentage = $value . '%';
//        }
//        //Add Total Row
//        $add = [
//            'country' => '',
//            'province' => '',
//            'city' => '',
//            'channel' => '',
//            'type' => '',
//            'adviser' => '',
//            'location' => '',
//            'paint' => '',
//            'damage' => '',
//            'status' => 'Total',
//            "count" => $total,
//            'percentage' => ''
//        ];
//        array_push($datas, $add);

        $collet = collect($datas);
        return $collet;
    }

    public function headings(): array {
        return [
            'Fecha',
            'Pais',
            'Provincia',
            'Ciudad',
            'Agencia',
            'Asesor',
            'Placa',
            'Color',
            'Marca|Modelo',
            'Año',
            'CI|RUC',
            'Apellidos',
            'Nombres',
            'Telefono Movil',
            'Email',
            'Canal',
            'Tipo',
            'Cobertura',
            'Pintura',
            'Lugar Servicio',
            'Dirección',
            'Fecha',
            'Hora',
            'Tiempo Estimado',
            'Tipo Golpe',
            'Estado',
            'Observacionaes',
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:AB1'; // All headers
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
