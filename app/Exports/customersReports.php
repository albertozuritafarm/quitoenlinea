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

class customersReports implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle 
{
    use Exportable;

    public function __construct(String $sql) {
        $this->sql = $sql;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        $query = 'select distinct sal.id as "id",
                    cou.name as "country",
                    pro.name as "province",
                    cit.name as "city",
                    agen.name as "agency",
                    chan.name as "channel",
                    sType.name as "type",
                    concat(usr.first_name," ", usr.last_name) as "adviser",
                    cus.document as "document",
                    cus.last_name as "last_name",
                    cus.first_name as "first_name",
                    cus.address as "address",
                    cus.phone as "phone",
                    cus.mobile_phone as "mobile_phone",
                    cus.email as "email",
                    vehi.plate as "plate",
                    vehi.color as "color",
                    concat(vehi.model, " ",bran.name) as "brand",
                    vehi.year as "year",
                    sal.total as "total",
                    sal.emission_date as "emission_date",
                    pType.name as "payment_type",
                    pay.date as "payment_date",
                    sal.begin_date as "activation_date",
                    sta.name as "status",
                    sal.begin_date as "begin_date",
                    sal.end_date as "end_date"
                    from sales sal
                    left join charges cha on cha.sales_id = sal.id
                    join users usr on usr.id = sal.user_id
                    join cities cit on cit.id = usr.city_id
                    join provinces pro on pro.id = cit.province_id
                    join countries cou on cou.id = pro.country_id
                    join agencies agen on agen.id = sal.agen_id
                    join channels chan on chan.id = agen.channel_id
                    join sales_type sType on sType.id = sal.sales_type_id
                    join status sta on sta.id = sal.status_id
                    join vehicles_sales vSal on vSal.sales_id = sal.id
                    join vehicles vehi on vehi.id = vSal.vehicule_id
                    join vehicles_brands bran on bran.id = vehi.brand_id
                    join customers cus on cus.id = sal.customer_id
                    left join payments pay on pay.id = cha.payments_id
                    left join payments_types pType on pType.id = pay.payment_type_id
                    where sal.emission_date is not null '.$this->sql.'';
        
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
            'ID',
            'Pais',
            'Provincia',
            'Ciudad',
            'Agencia',
            'Canal',
            'Tipo',
            'Asesor',
            'ID',
            'Apellidos',
            'Nombres',
            'Direccion',
            'Telefono Fijo',
            'Telefono Movil',
            'Email',
            'Placa',
            'Color',
            'Marca-Modelo',
            'Año',
            'Valor',
            'Fecha Compra',
            'Tipo de Pago',
            'Fecha Pago',
            'Fecha Activacion',
            'Estado',
            'Fecha Desde',
            'Fecha Hasta',
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:AA1'; // All headers
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