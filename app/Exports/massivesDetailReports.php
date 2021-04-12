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

class massivesDetailReports implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle 
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
        
        $datas = \App\SucreMassives::selectRaw('sucre_massives.*')
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
                                    ->skip(0)->take(1000)
                                    ->get();
        $collet = collect($datas);
        return $collet;
    }

    public function headings(): array {
        return [
            'CANAL / SPONSOR',
            'AGENCIA SPONSOR',
            'ASESOR',
            'No. DE OPERACIÓN',
            'AGENCIA',
            'AGENTE',
            'No. DE CERTIFICADO',
            'RAMO',
            'TIPO DE VENTA',
            'NUMERO DE POLIZA',
            'PRODUCTO',
            'PRIMA NETA',
            'PRIMA TOTAL',
            'FEC. INICIO VIGENCIA',
            'FEC. FIN VIGENCIA',
            'FEC. INICIO OPERACIÓN',
            'FEC. FIN OPERACIÓN',
            'FEC. DE CORTE',
            'PROVINCIA',
            'CANTON',
            'NACIONALIDAD',
            'No. CEDULA',
            'APELLIDOS',
            'NOMBRES',
            'GENERO',
            'FECHA NACIMIENTO',
            'OCUPACIÓN / ACTIVIDAD ECONÓMICA',
            'ESTADO CIVIL',
            'DIRECCION',
            'TELEFONO FIJO',
            'TELEFONO MOVIL',
            'E-MAIL',
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:AF1'; // All headers
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