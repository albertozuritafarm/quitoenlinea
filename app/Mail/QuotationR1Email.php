<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Adapter\PDFLib;
use Illuminate\Encryption\Encrypter;

class QuotationR1Email extends Mailable {

    use Queueable,
        SerializesModels;

    public $saleId;
    public $customerDocument;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($saleId, $customerDocument) {
        $this->saleId = $saleId;
        $this->customerDocument = $customerDocument;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.productodes as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birthdate as "cusBirthdate", pro.id as "idProduct", ci.name as "city"
                                        from customers cus
                                        join sales sal on sal.customer_id = cus.id
                                        join products_channel pbc on pbc.id = sal.pbc_id
                                        join products pro on pro.id = pbc.product_id
                                        join users on users. id = sal.user_id
                                        join documents doc on cus.document_id = doc.id
                                        join channels ch on ch.id = pbc.channel_id
                                        join  cities ci on ci.id = sal.cus_city
                                        where sal.id = "' . $this->saleId . '"';
        $customer = DB::select($customerQuery);

        $carQuery = 'select  vh.plate as "plate", vhb.name as "brand", vh.model as "model", vh.year as "year", vhsal.reference_value as "refValue", vhsal.insured_value as "ins_value", vhsal.prima_value as "prima_value", vhsal.acc_value as "acc_value"
                            FROM vehicles_sales vhsal
                            join vehicles vh on vhsal.vehicule_id = vh.id
                            join vehicles_brands vhb on vhb.id = vh.brand_id
                            where vhsal.sales_id = "' . $this->saleId . '"';
        $car = DB::select($carQuery);

        //Obtain Coverages
        $coverageQuery = 'SELECT cob.coberturades as "cobPrincipales", cob.valorasegurado as "valorAsegurado" , cob.tipocoberturades as "cobtipocoberturades"
                                        FROM coverage cob
                                        join products pro on pro.id = cob.product_id
                                        where pro.id =  '. $customer[0]->idProduct  .'
                                        and cob.tipocoberturades not in ("02. FORMA PAGO", "01. CARTA", "03. FIN CONDICIONES PARTICULARES", "07. FORMA PAGO", "08. FIN CONDICIONES PARTICULARES")

                                        GROUP BY cob.tipocoberturades
                                        ORDER BY cob.tipocoberturades';
        $coverage = DB::select($coverageQuery);
        
        $sales = \App\sales::find($this->saleId);
        
        $productName = \App\sales::selectRaw('products.name, products.productodes, products.price, cus.document')
                                    ->join('products_channel as pbc','pbc.id','sales.pbc_id')
                                    ->join('products','products.id','=','pbc.product_id')
                                    ->join('customers as cus','cus.id','=','sales.customer_id')
                                    ->where('sales.id','=',$this->saleId)
                                    ->get();
        
         $vehiTable = '<tr>
                        <th width="70%" style="text-align:left; padding:0 0 0 15px">'.$productName[0]->productodes.'</th>
                        <th width="30%" style="text-align:right; padding:0 5px 0 15px">$'.$sales->prima_total.'</th>
                    </tr>';
         
        //Tax Table Resume
        $superBancos = $sales->super_bancos;
        $seguCampesino = $sales->seguro_campesino;
        $deEmision = $sales->derecho_emision;
        $subTotal = $sales->subtotal_12;
        $tax = $sales->tax;
        $total = $sales->total;
        
        $taxTable = '<tr class="spaceUnder" style="text-align:right; border: 1px solid #E6E6E6">
                    	<td style="width:70%; background-color:#E6E6E6; text-align:right; padding:0 5px 0 15px;">S. de Bancos (3.5%)</td>
                        <td style="text-align:right; padding:0 5px 0 15px">$' . $superBancos . '</td>
                    </tr>
                    <tr class="spaceUnder" style="text-align:right; border: 1px solid #E6E6E6">
                        <td style="width:70%; background-color:#E6E6E6; text-align:right; padding:0 5px 0 15px">S. Campesino (0.5%)</td>
                        <td style="text-align:right; padding:0 5px 0 15px">$' . $seguCampesino . '</td>
                    </tr>
                    <tr class="spaceUnder" style="text-align:right; border: 1px solid #E6E6E6">
                        <td style="width:70%; background-color:#E6E6E6; text-align:right; padding:0 5px 0 15px">D. de Emisión</td>
                        <td style="text-align:right; padding:0 5px 0 15px">$' . $deEmision . '</td>
                    </tr>
                    <tr class="spaceUnder" style="text-align:right; border: 1px solid #E6E6E6">
                        <td style="width:70%; background-color:#E6E6E6; text-align:right; padding:0 5px 0 15px">Subtotal 12%</td>
                        <td style="text-align:right; padding:0 5px 0 15px">$' . $sales->subtotal_12 . '</td>
                    </tr>
                    <tr style="text-align:right; border: 1px solid #E6E6E6; margin:10px"> 
                        <td style="width:70%; background-color:#E6E6E6; text-align:right; padding:0 5px 0 15px; margin:10px">Subtotal 0%</td>
                        <td style="text-align:right; padding:0 5px 0 15px; margin:10px">$' . $sales->subtotal_0 . '</td>
                    </tr>
                    <tr style="text-align:right; border: 1px solid #E6E6E6; border-bottom: 1px solid #003B71;">
                        <td style="width:70%; background-color:#E6E6E6; text-align:right; padding:0 5px 0 15px">Iva</td>
                        <td style="text-align:right; padding:0 5px 0 15px">$' . $tax . '</td>
                    </tr>
                    <tr style="text-align:right; border:1px solid #003B71">
                        <td style="width:70%; background-color:#003B71; text-align:right; color:white; padding:0 5px 0 15px">Total</td>
                        <td style="text-align:right;color:#003B71; padding:0 5px 0 15px">$' . $total . '</td>
                    </tr>';

        $email = $customer[0]->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'coberto@magnusmas.com';
        }
        
        $document = encrypt($this->customerDocument);
        $sale = encrypt($this->saleId);
//        $document = $this->customerDocument;
//        $sale = $this->saleId;
        
         //Validate ID Length
        if (strlen($customer[0]->id) == 3) {
            $id = '00' . $customer[0]->id;
        } else if (strlen($customer[0]->id) == 4) {
            $id = '0' . $customer[0]->id;
        } else {
            $id = $customer[0]->id;
        }

        $returnBenefits = false;
        
        $pdf = PDF::loadView('sales.R1.pdf_quotation', ['customer' => strtoupper($customer[0]->Cliente),
                    'id' => $id,
                    'benefits' => $returnBenefits,
                    'customer' => $customer,
                    'car'=> $car,
                    'sales'=>$sales,
                    'coverage' => $coverage                   
                    ]);

        $output = $pdf->output();
        file_put_contents(public_path('cotizacion.pdf'), $output);
        
        //Store PDF base 64
        $b64Doc = chunk_split(base64_encode(file_get_contents(public_path('cotizacion.pdf'))));
        $saleUpdate = \App\sales::find($this->saleId);
        $saleUpdate->pdf_quotation = $b64Doc;
        $saleUpdate->save();
        
        $email_SS = \App\global_vars::find(2);
        $tag_SS = \App\global_vars::find(3);

        return $this->view('emails.quotationR1')
                    ->from($email_SS->value, $tag_SS->value)
                    ->subject('Cotización - Tu Auto tu lugar seguro')
                    ->replyTo($email_SS->value, $tag_SS->value)
                             ->attach(public_path('cotizacion.pdf'))
                    ->with([
                        'id' => $this->saleId,
                        'document' => $document,
                        'sale' => $sale,
                        'vehiTable' => $vehiTable,
                        'taxTable' => $taxTable,
                        'customer' => $customer
        ]);
    }
}
