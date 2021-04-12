<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Adapter\PDFLib;

class Email extends Mailable {

    use Queueable,
        SerializesModels;

    public $customerId;
    public $saleId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerId, $saleId) {
        $this->customerId = $customerId;
        $this->saleId = $saleId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $customerQuery = 'select concat(cus.first_name," ",cus.last_name) as "Cliente", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", cus.email, sal.agen_id as "agen_id"
                                    from customers cus
                                    join sales sal on sal.customer_id = cus.id
                                    where sal.id = "' . $this->saleId . '"';

        $customer = DB::select($customerQuery);

        //Validate ID Length
        if (strlen($customer[0]->id) == 3) {
            $id = '00' . $customer[0]->id;
        } else if (strlen($customer[0]->id) == 4) {
            $id = '0' . $customer[0]->id;
        } else {
            $id = $customer[0]->id;
        }

        //        $customer = strtoupper($customer[0]->Cliente);
        //Obtain Channel
        $channelQuery = 'select cha.id from channels cha join agencies agen on agen.channel_id = cha.id where agen.id = ' . $customer[0]->agen_id;
        $channel = DB::select($channelQuery);

        //Obtain Benefits
        $benefitsQuery = 'select * from benefits where status_id = "1" and channels_id = "' . $channel[0]->id . '"';
        $benefits = DB::select($benefitsQuery);

        if ($benefits) {
            $returnBenefits = $benefits;
        } else {
            $returnBenefits = 'false';
        }

        $email = $customer[0]->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'info@magnusmas.com';
        }


        //    PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        //Validate Product ID and PDF
        $productQuery = 'select pro.id as "id" from products pro join products_channel pbc on pbc.product_id = pro.id join sales sal on sal.pbc_id = pbc.id where sal.id = "' . $this->saleId . '"';
        $product = DB::select($productQuery);
        if ($product[0]->id == 1) {
            $pdf = PDF::loadView('sales.pdf', ['customer' => strtoupper($customer[0]->Cliente),
                        'id' => $id,
                        'benefits' => $returnBenefits,
                        'year' => $customer[0]->year]);
            $output = $pdf->output();
            file_put_contents('C:/wamp64/www/MagnusHit/public/filename.pdf', $output);
            return $this->view('emails.sale')
                            ->from('demo@magnusmas.com', 'Info')
                            ->subject('Bienvenido a HIT Solution')
                            ->replyTo('demo@magnusmas.com', 'Info')
                            ->attach('C:/wamp64/www/MagnusHit/public/filename.pdf')
                            ->with([
                                'id' => $this->saleId,
                                'benefits' => $benefits,
                                'year' => $customer[0]->year,
                                'name' => $customer[0]->Cliente
            ]);
        } else {
            $pdf = PDF::loadView('sales.pdf_2', ['customer' => strtoupper($customer[0]->Cliente),
                        'id' => $id,
                        'benefits' => $returnBenefits,
                        'year' => $customer[0]->year]);
            $output = $pdf->output();
            file_put_contents('C:/wamp64/www/MagnusHit/public/filename.pdf', $output);
            return $this->view('emails.sale')
                            ->from('demo@magnusmas.com', 'Info')
                            ->subject('Bienvenido a HIT Solution')
                            ->replyTo('demo@magnusmas.com', 'Info')
                            ->attach('C:/wamp64/www/MagnusHit/public/filename.pdf')
                            ->with([
                                'id' => $this->saleId,
                                'benefits' => $benefits,
                                'year' => $customer[0]->year,
                                'name' => $customer[0]->Cliente
            ]);
        }
    }

}
