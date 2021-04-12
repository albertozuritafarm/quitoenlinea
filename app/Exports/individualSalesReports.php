<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class individualSalesReports implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents 
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
        
        $query = 'select sal.id as "id",
                    mas.massives_id as "masId",
                    cou.name as "country",
                    pro.name as "province",
                    cit.name as "city",
                    agen.name as "agency",
                    chan.name as "channel",
                    if(sType.name = "WS","Masivo",sType.name) as "type",
                    concat(usr.first_name," ", usr.last_name) as "adviser",
                    count(vSal.id) as "vehicles",
                    cus.document as "document",
                    cus.last_name as "last_name",
                    cus.first_name as "first_name",
                    sal.cus_address as "address",
                    sal.cus_phone as "phone",
                    sal.cus_mobile_phone as "mobile_phone",
                    sal.cus_email as "email",
                    sal.total as "total",
                    sal.emission_date as "emission_date",
                    pType.name as "payment_type",
                    pay.date as "payment_date",
                     IF(sal.sales_type_id = 1, sal.begin_date, sal.emission_date) as "activation_date",
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
                    join customers cus on cus.id = sal.customer_id
                    left join payments pay on pay.id = cha.payments_id
                    left join payments_types pType on pType.id = pay.payment_type_id
                    left join massives_sales mas on mas.sales_id = sal.id
                    where sal.emission_date is not null '.$this->sql.' '.$sql.'
                    group by sal.id';
        $max = 1;
        $datas = DB::select($query);
//        $total = 0;
//        //sum all Totals
//        foreach($datas as $data){
//            $total += $data->total;
//        }
//        //Calculate Percentage
//        foreach($datas as $data){
//            $value = round((($data->total * 100)/$total),2);
//            $data->percentage = $value . '%';
//        }
        $collet = collect($datas);
        return $collet;
    }

    public function headings(): array {
        return [
            'ID Venta',
            'ID Masiva',
            'Pais',
            'Provincia',
            'Ciudad',
            'Agencia',
            'Canal',
            'Tipo Venta',
            'Asesor',
            '# Vehiculos',
            'ID',
            'Apellidos',
            'Nombres',
            'Direccion',
            'Telefono Fijo',
            'Telefono Movil',
            'Email',
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
                $cellRange = 'A1:X1'; // All headers
                $bodyRange = 'A2:X2222';
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
