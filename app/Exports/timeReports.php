<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class timeReports implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents {

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
        $query = 'SELECT cou.name                           AS "country",
       pro.name                                             AS "province",
       cit.name                                             AS "city",
       IF(agen.name = "todos", "magnus", agen.name)         AS "agency",
       IF(chan.name = "todos", "magnus", chan.name)         AS "channel",
       sType.name                                           AS "Tipo de Venta",
       Concat(usr.first_name, "", usr.last_name)           AS "adviser",
       Round(Avg(Datediff(cha.DATE, sal.emission_date)), 0) AS "diff1",
       "0"                                                  AS "percentage1",
       ABS(Round(Avg(Datediff(cha.DATE, sal.begin_date)), 0))    AS "diff2",
       "0"                                                  AS "percentage2",
             ( ABS(( Round(Avg(Datediff(cha.DATE, sal.emission_date)), 0) )) + ( ABS(Round(
         Avg(Datediff(cha.DATE, sal.begin_date)), 0) ) ))    AS "diff3"
                    from sales sal
                    join charges cha on cha.sales_id = sal.id
                    join users usr on usr.id = sal.user_id
                    join cities cit on cit.id = usr.city_id
                    join provinces pro on pro.id = cit.province_id
                    join countries cou on cou.id = pro.country_id
                    join agencies agen on agen.id = sal.agen_id
                    join channels chan on chan.id = agen.channel_id
                    join sales_type sType on sType.id = sal.sales_type_id
                    join status sta on sta.id = sal.status_id
                    join vehicles_sales vSal on vSal.sales_id = sal.id
                    where sal.sales_type_id = 1 '.$this->sql.' '.$sql.' group by usr.id';
//        print_r($query);die();
        $datas = DB::select($query);
        $total = 0;
//        sum all Totals
//        if(){
//            
//        }
        foreach($datas as $data){
            $total = $data->diff3;
            if($data->diff1 ==0){
                $data->diff1 = "0";
            }
            if($data->diff2 ==0){
                $data->diff2 = "0";
            }
            
        }
        //Calculate Percentage
        foreach($datas as $data){
            $total = $data->diff3;
            if($total == 0){
                $data->percentage1 = '0%';
                $data->percentage2 = '0%';
            }else{
                $value = round((($data->diff1 * 100)/$total),2);
                $data->percentage1 = $value . '%';
                $value = round((($data->diff2 * 100)/$total),2);
                $data->percentage2 = $value . '%';
            }
        }
        $collet = collect($datas);
        return $collet;
    }

    public function headings(): array {
        return [
            'Pais',
            'Provincia',
            'Canton',
            'Canal',
            'Agencia',
            'Tipo',
            'Usuario',
            'Comptra|Pago',
            '%',
            'Pago|Activacion',
            '%',
            'Compra|Activacion',
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

}
