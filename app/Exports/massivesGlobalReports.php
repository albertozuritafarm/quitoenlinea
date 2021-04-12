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

class massivesGlobalReports implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle 
{
    use Exportable;

    public function __construct($channel, $agency, $beginDate, $endDate, $province, $city, $policyNumber, $advisor) {
        $this->channel = $channel;
        $this->agency = $agency;
        $this->beginDate = $beginDate;
        $this->endDate = $endDate;
        $this->province = $province;
        $this->city = $city;
        $this->policyNumber = $policyNumber;
        $this->advisor = $advisor;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        $channel = $this->channel;
        $agency = $this->agency;
        $beginDate = $this->beginDate;
        $endDate = $this->endDate;
        $province = $this->province;
        $city = $this->city;
        $policyNumber = $this->policyNumber;
        $advisor = $this->advisor;
        
        $datas = \App\SucreMassives::selectRaw('sucre_massives.channel_sponsor, agency_sponsor, sucre_massives.advisor_sponsor, sucre_massives.agency_insurance, sucre_massives.agent_insurance, sucre_massives.branch_product, sucre_massives.sale_type_product, sucre_massives.name_product, count(sucre_massives.id), sum(sucre_massives.fee_net_product) as "primaNeta", SUM(sucre_massives.fee_total_product), 0 as "percentage"')
                                    ->when($channel != null, function ($datas) use ($channel) {
                                        return $datas->where('sucre_massives.channel_sponsor', $channel);
                                    })
                                    ->when($agency != null, function ($datas) use ($agency) {
                                        return $datas->where('sucre_massives.agency_sponsor', $agency);
                                    })
                                    ->when($beginDate != null, function ($datas) use ($beginDate, $endDate) {
                                        return $datas->whereRaw('DATE_FORMAT(sucre_massives.begin_date_validity_product,"%Y-%m-%d") between "' . $beginDate . '" and "' . $endDate . '"');
                                    })
                                    ->when($province != null, function ($datas) use ($province) {
                                        return $datas->where('sucre_massives.province_customer', $province);
                                    })
                                    ->when($city != null, function ($datas) use ($city) {
                                        return $datas->where('sucre_massives.city_customer', $city);
                                    })
                                    ->when($policyNumber != null, function ($datas) use ($policyNumber) {
                                        return $datas->where('sucre_massives.policy_number_product', $policyNumber);
                                    })
                                    ->when($advisor != null, function ($datas) use ($advisor) {
                                        return $datas->where('sucre_massives.advisor_sponsor', $advisor);
                                    })
                                    ->groupBy('sucre_massives.advisor_sponsor','sucre_massives.branch_product', 'sucre_massives.sale_type_product')
                                    
                                    ->skip(0)->take(1000)
                                    ->get();
        $total = 0;
        //sum all Totals
        foreach($datas as $data){
            $total += $data->primaNeta;
        }

        //Calculate Percentage
        foreach($datas as $data){
            $value = round((($data->primaNeta * 100)/$total),2);
            $data->percentage = $value . '%';
        }
        $collet = collect($datas);
        return $collet;
    }

    public function headings(): array {
        return [
            'Canal / Sponsor',
            'Agencia / Sponsor',
            'Usuario',
            'Agencia',
            'Agente',
            'Ramo',
            'Tipo',
            'Producto',
            'Cantidad',
            'Prima Neta',
            'Prima Total',
            'Porcentaje',
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