<?php

use Illuminate\Support\Carbon;
use App\Jobs\EmailJobs;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;

//use DB;
//Generate Random Password
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

//Sending Email Variables
function emailTo() {
    $data = array('from' => 'noreply@magnusmas.com',
        '<from>' => 'NoReply');

    return $data;
}

//Sending SMS
function sendSMS($phone, $randomCode, $sales_id) {
    //Recipient
    $recipient = array();
    $recipient = [
        "msisdn" => "593" . $phone
    ];

    $curl = curl_init();

    //Generate JSON to send
    $json = array();
    $json = [
        "message" => "Tu codigo de confirmacion para HIT Solution es $randomCode",
        "topa" => "Sender",
        "recipient" => $recipient
    ];

    $jsonEncode = json_encode($json);


    //Crate new SMS Log
    $smsLog = new App\sms_logs();
    $smsLog->sales_id = $sales_id;
    $smsLog->mobile_phone = $phone;
    $smsLog->data_send = $jsonEncode;
    $smsLog->send_date = now();
    $smsLog->Save();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.labsmobile.com/json/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $jsonEncode,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic Z3J1aXpAYWdwLWNvcnBvcmFjaW9uLmNvbTo3OFRKYmw4WVFLMW45azl1b01WNkdPZVdhU01lM01uWQ==",
            "Content-Type: application/json",
            "Postman-Token: 83092e0f-de83-46f6-8b31-e951c201a91a",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    //Update SMS Log with response
    $smsLogResponse = App\sms_logs::find($smsLog->id);
    $smsLogResponse->data_response = $response;
    $smsLogResponse->response_date = now();
    $smsLogResponse->save();

    //Validate Response Code
    $responseArray = json_decode($response);
    if ($responseArray->code == '0') {
        return 'success';
    } else {
        return 'error';
    }
}

//Sending Link SMS
function sendLinkSMS($phone, $randomCode, $sales_id) {
    //Format Mobile Phone
    $phone = substr($phone, 1);

    //Recipient
    $recipient = array();
    $recipient = [
        "msisdn" => "593" . $phone
    ];

    $curl = curl_init();

    //Generate JSON to send
    $json = array();
    $json = [
        "message" => "Por favor ingrese al siguiente enlace para realizar el pago: http://192.168.10.22:81/remote Su codigo es: $randomCode",
        "topa" => "Sender",
        "recipient" => $recipient
    ];

    $jsonEncode = json_encode($json);


    //Crate new SMS Log
    $smsLog = new App\sms_logs();
    $smsLog->sales_id = $sales_id;
    $smsLog->mobile_phone = $phone;
    $smsLog->data_send = $jsonEncode;
    $smsLog->send_date = now();
    $smsLog->Save();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.labsmobile.com/json/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $jsonEncode,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic Z3J1aXpAYWdwLWNvcnBvcmFjaW9uLmNvbTo3OFRKYmw4WVFLMW45azl1b01WNkdPZVdhU01lM01uWQ==",
            "Content-Type: application/json",
            "Postman-Token: 83092e0f-de83-46f6-8b31-e951c201a91a",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    //Update SMS Log with response
    $smsLogResponse = App\sms_logs::find($smsLog->id);
    $smsLogResponse->data_response = $response;
    $smsLogResponse->response_date = now();
    $smsLogResponse->save();

    //Validate Response Code
    $responseArray = json_decode($response);
    if ($responseArray->code == '0') {
        return 'success';
    } else {
        return 'error';
    }
}

function sendVehiLinkSMS($phone, $randomCode, $sales_id) {
    //Format Mobile Phone
    $phone = substr($phone, 1);

    //Recipient
    $recipient = array();
    $recipient = [
        "msisdn" => "593" . $phone
    ];

    $curl = curl_init();

    //Generate JSON to send
    $json = array();
    $json = [
        "message" => "Por favor ingrese al siguiente enlace cuando tenga las fotos del vehiculo: http://192.168.10.22:81/remoteVehicles Su codigo es: $randomCode",
        "topa" => "Sender",
        "recipient" => $recipient
    ];

    $jsonEncode = json_encode($json);


    //Crate new SMS Log
    $smsLog = new App\sms_logs();
    $smsLog->sales_id = $sales_id;
    $smsLog->mobile_phone = $phone;
    $smsLog->data_send = $jsonEncode;
    $smsLog->send_date = now();
    $smsLog->Save();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.labsmobile.com/json/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $jsonEncode,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic Z3J1aXpAYWdwLWNvcnBvcmFjaW9uLmNvbTo3OFRKYmw4WVFLMW45azl1b01WNkdPZVdhU01lM01uWQ==",
            "Content-Type: application/json",
            "Postman-Token: 83092e0f-de83-46f6-8b31-e951c201a91a",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    //Update SMS Log with response
    $smsLogResponse = App\sms_logs::find($smsLog->id);
    $smsLogResponse->data_response = $response;
    $smsLogResponse->response_date = now();
    $smsLogResponse->save();

    //Validate Response Code
    $responseArray = json_decode($response);
    if ($responseArray->code == '0') {
        return 'success';
    } else {
        return 'error';
    }
}

//Sending Account SMS
function sendAccountSMS($phone, $randomCode) {
    //Recipient
    $recipient = array();
    $recipient = [
        "msisdn" => "593" . $phone
    ];

    $curl = curl_init();

    //Generate JSON to send
    $json = array();
    $json = [
        "message" => "Tu codigo de confirmacion para La solicitus de cuenta es $randomCode",
        "topa" => "Sender",
        "recipient" => $recipient
    ];

    $jsonEncode = json_encode($json);


    //Crate new SMS Log
    $smsLog = new App\sms_logs();
    $smsLog->sales_id = 1;
    $smsLog->mobile_phone = $phone;
    $smsLog->data_send = $jsonEncode;
    $smsLog->send_date = now();
    $smsLog->Save();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.labsmobile.com/json/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $jsonEncode,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic Z3J1aXpAYWdwLWNvcnBvcmFjaW9uLmNvbTo3OFRKYmw4WVFLMW45azl1b01WNkdPZVdhU01lM01uWQ==",
            "Content-Type: application/json",
            "Postman-Token: 83092e0f-de83-46f6-8b31-e951c201a91a",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    //Update SMS Log with response
    $smsLogResponse = App\sms_logs::find($smsLog->id);
    $smsLogResponse->data_response = $response;
    $smsLogResponse->response_date = now();
    $smsLogResponse->save();

    //Validate Response Code
    $responseArray = json_decode($response);
    if ($responseArray->code == '0') {
        return 'success';
    } else {
        return 'error';
    }
}

//EXECUTE SALE
function executeSale() {
    
}

//ACTIVATE SALE
function activateSale($salesId) {
    //Validate All pictures have been uploaded
    //Variable
    $activate = false;
    //Vehicles Sales Query
    $vSalQuery = 'select id from vehicles_sales where sales_id  = ' . $salesId;
    $vSal = DB::select($vSalQuery);

    foreach ($vSal as $id) {
        //Query
        $query = 'select sal.id
                    from vehicles_sales vsal
                    join sales sal on sal.id = vsal.sales_id
                    join charges cha on cha.sales_id = sal.id and cha.types_id = 1 and cha.status_id = 9
                    where vsal.picture_front is not null and vsal.picture_back is not null and vsal.picture_right is not null 
                    and vsal.picture_left is not null and vsal.picture_roof is not null and vsal.id = ' . $id->id;
        $result = DB::select($query);
        if ($result) {
            $activate = true;
        } else {
            $activate = false;
        }

        //Query
        $query2 = 'select sal.id
                    from vehicles_sales vsal
                    join sales sal on sal.id = vsal.sales_id
                    where vsal.picture_front is not null and vsal.picture_back is not null and vsal.picture_right is not null 
                    and vsal.picture_left is not null and vsal.picture_roof is not null and vsal.id = ' . $id->id;
        $result2 = DB::select($query2);
        if ($result2) {
            $activate2 = true;
        } else {
            $activate2 = false;
        }
    }

    if ($activate) {//Its payed and all the pictures have been uploaded
        $current = Carbon::now();
        $sale = App\sales::find($salesId);
        $sale->status_id = 1;
        $sale->begin_date = Carbon::now();
        $sale->end_date = $current->addYears(1);
        $sale->allow_cancel_date = Carbon::now()->addMonths(1);
        $sale->save();

        return 'YES';
    } else {
        if ($activate2) {
            $sale = App\sales::find($salesId);
            $sale->status_id = 12;
            $sale->save();
            return 'NO';
        } else {
            $sale = App\sales::find($salesId);
            $sale->status_id = 13;
            $sale->save();
            return 'NO';
        }
    }
}

//Send Welcome Mail
function welcomeMail($customerId, $saleId) {
    //Obtatin Customer for PDF
    //Obtatin Customer
    $customerQuery = 'select concat(cus.first_name," ",cus.last_name) as "Cliente", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", cus.email
                            from customers cus
                            join sales sal on sal.customer_id = cus.id
                            where sal.id = "' . $saleId . '"';

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
    $channelQuery = 'select cha.id from channels cha join agencies agen on agen.channel_id = cha.id where agen.id = ' . \Auth::user()->agen_id;
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

    $pdf = PDF::loadView('sales.pdf', ['customer' => strtoupper($customer[0]->Cliente),
                'id' => $id,
                'benefits' => $returnBenefits,
                'year' => $customer[0]->year]);

//    PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
    $output = $pdf->output();
    file_put_contents('HIT.pdf', $output);

    $data = array('name' => $customer[0]->Cliente);
    Mail::send('emails.sale', $data, function($message) use($email, $customer) {
        $message->to($email, $customer[0]->Cliente)->subject
                ('Bienvenido a HIT Solution');
        $message->attach('C:\xampp\htdocs\MagnusHit\public\HIT.pdf');
        $message->from('info@hitsolution.ec', 'info@hitsolution.ec');
    });
}

//Send Welcome Mail Remote
function welcomeMailRemote($customerId, $saleId) {
    //Obtatin Customer for PDF
    //Obtatin Customer
    $customerQuery = 'select concat(cus.first_name," ",cus.last_name) as "Cliente", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", cus.email
                            from customers cus
                            join sales sal on sal.customer_id = cus.id
                            where sal.id = "' . $saleId . '"';

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
    $channelQuery = 'select cha.id from channels cha join agencies agen on agen.channel_id = cha.id join sales sal on sal.agen_id = agen.id where sal.id = ' . $saleId;
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

    $pdf = PDF::loadView('sales.pdf', ['customer' => strtoupper($customer[0]->Cliente),
                'id' => $id,
                'benefits' => $returnBenefits,
                'year' => $customer[0]->year]);

//    PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
    $output = $pdf->output();
    file_put_contents('HIT.pdf', $output);

    $data = array('name' => $customer[0]->Cliente);
    Mail::send('emails.sale', $data, function($message) use($email, $customer) {
        $message->to($email, $customer[0]->Cliente)->subject
                ('Bienvenido a HIT Solution');
        $message->attach('C:\xampp\htdocs\MagnusHit\public\HIT.pdf');
        $message->from('info@hitsolution.ec', 'info@hitsolution.ec');
    });
}

//STORE MASSIVE SALES
function saleStoreMassive($requests, $massiveId) {
    foreach ($requests as $request) {
        //Save or Update Customer
        $customerSql = 'select * from customers where document = ' . $request['data']['customer']['document'];
        $customer = DB::select($customerSql);

        //Validate Customer Save or Update
        if ($customer) {
            $customerUpdate = \App\customers::find($customer[0]->id);
            $customerUpdate->first_name = $request['data']['customer']['firstName'];
            $customerUpdate->last_name = $request['data']['customer']['lastName'];
            $customerUpdate->document = $request['data']['customer']['document'];
            $customerUpdate->document_id = $request['data']['customer']['documentId'];
            $customerUpdate->address = $request['data']['customer']['address'];
            $customerUpdate->city_id = $request['data']['customer']['city'];
            $customerUpdate->phone = $request['data']['customer']['phone'];
            $customerUpdate->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerUpdate->email = $request['data']['customer']['email'];
            $customerUpdate->save();
            $customerId = $customerUpdate->id;
            $customerPhone = substr($customerUpdate->mobile_phone, 1);
            $customerEmail = $customerUpdate->email;
        } else {
            $customerNew = new \App\customers();
            $customerNew->first_name = $request['data']['customer']['firstName'];
            $customerNew->last_name = $request['data']['customer']['lastName'];
            $customerNew->document = $request['data']['customer']['document'];
            $customerNew->document_id = $request['data']['customer']['documentId'];
            $customerNew->address = $request['data']['customer']['address'];
            $customerNew->city_id = $request['data']['customer']['city'];
            $customerNew->phone = $request['data']['customer']['phone'];
            $customerNew->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerNew->email = $request['data']['customer']['email'];
            $customerNew->status_id = 1;
            $customerNew->save();
            $customerId = $customerNew->id;
            $customerPhone = substr($customerNew->mobile_phone, 1);
            $customerEmail = $customerNew->email;
        }

        //Obtain Product Data
        $productSql = 'select * from products_channel where product_id = ' . $request['data']['product'];
        $productChannel = DB::select($productSql);

        //Random Code
//        $randomCode = rand(1000, 9999);
        $randomCode = '1234';

        //OBTAIN AGENCY
        $agencyQuery = 'select * from agencies where channel_id = ' . $request['data']['channel'];
        $agency = DB::select($agencyQuery);
//        return $agency;
//        
        //DateTime
        $now = new \DateTime();

        //VALIDATE  VIGENCIA
        if ($request['data']['beginDate'] > $now) {
            $status = 2;
        } else if ($request['data']['endDate'] < $now) {
            $status = 2;
        } else {
            $status = 1;
        }

        //Store Sale
        $salesNew = new \App\sales();
        $salesNew->pbc_id = $productChannel[0]->id;
        $salesNew->user_id = \Auth::user()->id;
        $salesNew->customer_id = $customerId;
        $salesNew->status_id = $status;
        $salesNew->emission_date = now();
        $salesNew->token_date_send = now();
        $salesNew->subtotal_12 = $request['data']['pricesTable'][0]['value'];
        $salesNew->subtotal_0 = $request['data']['pricesTable'][1]['value'];
        $salesNew->other_discount = $request['data']['pricesTable'][2]['value'];
        $salesNew->tax = $request['data']['pricesTable'][3]['value'];
        $salesNew->total = $request['data']['pricesTable'][4]['value'];
        $salesNew->token = $randomCode;
        $salesNew->begin_date = $request['data']['beginDate'];
        $salesNew->end_date = $request['data']['endDate'];
        $salesNew->sales_type_id = 2;
        $salesNew->agen_id = $agency[0]->id;
        $salesNew->cus_mobile_phone = $request['data']['customer']['mobilePhone'];
        $salesNew->cus_phone = $request['data']['customer']['phone'];
        $salesNew->cus_address = $request['data']['customer']['address'];
        $salesNew->cus_email = $request['data']['customer']['email'];
        $salesNew->cus_city = $request['data']['customer']['city'];
        $salesNew->save();


        //Send Welcome Mail
//        sendMailList($customerEmail);
        $job = (new EmailJobs($salesNew->customer_id, $salesNew->id, $customerEmail));
        dispatch($job);

        //Massive Sales Store
        $massiveSales = new \App\massives_sales();
        $massiveSales->sales_id = $salesNew->id;
        $massiveSales->massives_id = $massiveId;
        $massiveSales->save();

        //Send SMS
//        sendSMS($customerPhone, $randomCode, $salesNew->id);
        //Store vehicles
        $vehicleCount = 1;
//        return $request['data'];
        foreach ($request['data']['vehicles'] as $vehicle) {

            //Obtain Brand
            $brandSql = 'select * from vehicles_brands where name = "' . $vehicle['brand'] . '"';
            $brand = DB::select($brandSql);

            //If vehicle Existe Update else Store
            $vehicleSql = 'select * from vehicles where plate = "' . $vehicle['plate'] . '"';
            $vehicleSearch = DB::select($vehicleSql);

            if ($vehicleSearch) {
                //Update Vehicule
                $vehicleUpdate = \App\vehicles::find($vehicleSearch[0]->id);
                $vehicleUpdate->plate = $vehicle['plate'];
                $vehicleUpdate->brand_id = $brand[0]->id;
                $vehicleUpdate->model = $vehicle['model'];
                $vehicleUpdate->year = $vehicle['year'];
                $vehicleUpdate->color = $vehicle['color'];
                $vehicleUpdate->save();
                $vehicleId = $vehicleUpdate->id;
            } else {
                //Store Vehicle
                $vehiclesNew = new \App\vehicles();
                $vehiclesNew->plate = $vehicle['plate'];
                $vehiclesNew->brand_id = $brand[0]->id;
                $vehiclesNew->model = $vehicle['model'];
                $vehiclesNew->year = $vehicle['year'];
                $vehiclesNew->color = $vehicle['color'];
                $vehiclesNew->save();
                $vehicleId = $vehiclesNew->id;
            }

            //Store Vehicles Sales
            $vehiclesSalesNew = new \App\vehicles_sales();
            $vehiclesSalesNew->sales_id = $salesNew->id;
            $vehiclesSalesNew->vehicule_id = $vehicleId;
            $vehiclesSalesNew->status_id = 1;
            $vehiclesSalesNew->save();

            //Obtain Extra Benefits
//            $benefitsQuery = 'select * from benefits where channels_id = "' . $request['data']['channel'] . '" and status_id = 1 ';
            $benefitsQuery = 'select * from benefits where channels_id = "' . $request['data']['channel'] . '" and status_id not in (4) and "' . $request['data']['beginDate'] . '"  between begin_date and end_date';
            $benefits = DB::select($benefitsQuery);

            //DateTime
            $now = new \DateTime();

            //Beneits Array
            foreach ($benefits as $benefit) {
                //Store Benefits Vehicles Sales
                $benefitsNew = new \App\benefits_vehicles_sales();
                $benefitsNew->benefits_id = $benefit->id;
                $benefitsNew->vsal_id = $vehiclesSalesNew->id;
                $benefitsNew->date = $now;
                $benefitsNew->save();
            }

            //Obtain Vehicle 1 Price
            $product = \App\products::find($request['data']['product']);

            //Obta
            //Store Vehicles Sales Prices
            $vehicleSalesPrices = new \App\vehicles_sales_prices();
            $vehicleSalesPrices->sales_id = $salesNew->id;
            if ($vehicleCount == 1) {
                $vehicleSalesPrices->price = $product['price'];
            } else {
                //Obtain vehicle x price
                $discountSql = 'select distinct dis.* 
                                from discounts dis 
                                join products_discount pdis on pdis.discounts_id = discounts_id
                                where pdis.products_id = "' . $request['data']['product'] . '" and dis.vechicles_count = "' . $vehicleCount . '"';
                $discount = DB::select($discountSql);
                $discountPrice = ($product['price'] - (($product['price'] * $discount[0]->percentage) / 100));
                $vehicleSalesPrices->price = round($discountPrice, 2);
            }
            $vehicleSalesPrices->status_id = 1;
            $vehicleSalesPrices->save();

            $vehicleCount++;
        }

        $returnArray = array();
        $returnArray = [
            'productId' => $salesNew->id,
            'validationCode' => $salesNew->token,
            'salId' => $salesNew->id
        ];
    }

//    return $returnArray;
}

function validateUploadSales($request) {
    $returnArray = array();
    $country_isset = false;
    $province_isset = false;
    $city_isset = false;
    $begin_date_isset = false;
    $end_date_isset = false;
    //Validate Sale Data
    if (!isset($request['venta'][0])) {
        $response = ['nombre' => 'Debe incluir los datos de la venta.'];
        array_push($returnArray, $response);
    } else {
        $sale = $request['venta'][0]; // Request ID
        if (!isset($sale['request_id'])) {
            $response = ['request_id' => 'Debe incluir el request id de la venta.'];
            array_push($returnArray, $response);
        } else {
            $saleRequestId = DB::table('sales')
                    ->where('request_id', '=', $sale['request_id'])
                    ->get();
            if (!$saleRequestId->isEmpty()) {
                $response = ['request_id' => 'Ya una venta posee este request_id.'];
                array_push($returnArray, $response);
            }
        }
        if (!isset($sale['vigencia_desde'])) { //Begin Date
            $response = ['vigencia_desde' => 'Debe incluir la vigencia desde de la venta.'];
            array_push($returnArray, $response);
        } else { // Validate Begin Date
            if (!isDate($sale['vigencia_desde'])) {
                $response = ['vigencia_desde' => 'La vigencia desde de la venta no tiene el formato correcto.'];
                array_push($returnArray, $response);
            } else {
                $begin_date_isset = true;
            }
        }
        if (!isset($sale['vigencia_hasta'])) { // End Date
            $response = ['vigencia_hasta' => 'Debe incluir la vigencia hasta de la venta.'];
            array_push($returnArray, $response);
        } else { // Validate End Date
            if (!isDate($sale['vigencia_hasta'])) {
                $response = ['vigencia_hasta' => 'La vigencia hasta de la venta no tiene el formato correcto.'];
                array_push($returnArray, $response);
            } else {
                $end_date_isset = true;
            }
            if ($begin_date_isset == true && $end_date_isset == true) {
                $date = str_replace('/', '-', $sale['vigencia_desde']);
                $newBeginDate = date("Y-m-d", strtotime($date));
                $date = str_replace('/', '-', $sale['vigencia_hasta']);
                $newEndDate = date("Y-m-d", strtotime($date));

                if ($newEndDate < $newBeginDate) {
                    $response = ['vigencia_hasta' => 'La fecha vigencia hasta no puede ser menor a la fecha vigencia desde.'];
                    array_push($returnArray, $response);
                }
            }
        }
        if (!isset($sale['agencia'])) { // Agency
            $response = ['agencia' => 'Debe incluir el producto de la venta.'];
            array_push($returnArray, $response);
        } else {
            $agency = DB::table('agencies')
                    ->where('cod_equinoccial', '=', $sale['agencia'])
                    ->get();
            if ($agency->isEmpty()) {
                $response = ['agencia' => 'La agencia ingresada no se encuentra registrada en el sistema.'];
                array_push($returnArray, $response);
            }
        }
        if (!isset($sale['producto'])) { // Product
            $response = ['producto' => 'Debe incluir el producto de la venta.'];
            array_push($returnArray, $response);
        } else {
            $product = DB::table('products')
                    ->join('products_channel', 'products.id', '=', 'products_channel.product_id')
                    ->join('channels', 'channels.id', '=', 'products_channel.channel_id')
                    ->join('agencies', 'agencies.channel_id', '=', 'channels.id')
                    ->select('products.*')
                    ->where('products.id', '=', $sale['producto'])
                    ->where('agencies.id', '=', $sale['agencia'])
                    ->get();
            if ($product->isEmpty()) {
                $response = ['producto' => 'El producto no se encuentra asociado al canal.'];
                array_push($returnArray, $response);
            }
        }
    }

    //Validate Customer
    if (!isset($request['cliente'][0])) { // JSON doesnt include Customer Data
        $response = ['nombre' => 'Debe incluir los datos del cliente.'];
        array_push($returnArray, $response);
    } else {
        $customer = $request['cliente'][0];
        if (!isset($customer['nombre'])) { //First Name
            $response = ['nombre' => 'Debe incluir el nombre del cliente.'];
            array_push($returnArray, $response);
        }
        if (!isset($customer['apellido'])) { // Last Name
            $response = ['apellido' => 'Debe incluir el apellido del cliente.'];
            array_push($returnArray, $response);
        }
        if (!isset($customer['identificacion'])) { // ID
            $response = ['identificacion' => 'Debe incluir la identificacion del cliente.'];
            array_push($returnArray, $response);
        } else { // Valid ID
            $validateID = validateId($customer['identificacion']);
            if (!$validateID) {
                $response = ['identificacion' => 'El ID indicado no es una cedula valida.'];
                array_push($returnArray, $response);
            }
        }
        if (!isset($customer['direccion'])) { // Address
            $response = ['apellido' => 'Debe incluir la direccion del cliente.'];
            array_push($returnArray, $response);
        }
        if (!isset($customer['telefono1'])) { // Phone
            $response = ['telefono1' => 'Debe incluir el telefono fijo del cliente.'];
            array_push($returnArray, $response);
        } else {
            $phone = $customer['telefono1'];
            if (!is_numeric($phone) || strlen($phone) != 9) {
                $response = ['telefono1' => 'El telefono fijo del cliente no posee el formato correcto.'];
                array_push($returnArray, $response);
            }
        }
        if (!isset($customer['telefono2'])) { // Mobile Phone
            $response = ['telefono2' => 'Debe incluir el telefono movil del cliente.'];
            array_push($returnArray, $response);
        } else {
            $phone = $customer['telefono2'];
            if (!is_numeric($phone) || strlen($phone) != 10) {
                $response = ['telefono2' => 'El telefono movil del cliente no posee el formato correcto.'];
                array_push($returnArray, $response);
            }
        }
        if (!isset($customer['correo'])) { // Email
            $response = ['correo' => 'Debe incluir el correo del cliente.'];
            array_push($returnArray, $response);
        } else {
            $email = $customer['correo'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response = ['correo' => 'El correo del cliente no posee el formato correcto.'];
                array_push($returnArray, $response);
            }
        }
        if (!isset($customer['pais'])) { // Country
            $response = ['pais' => 'Debe incluir el pais del cliente.'];
            array_push($returnArray, $response);
        } else { //Validate Country
            $country = \App\country::where('name', '=', $customer['pais'])->get();
            if ($country->isEmpty()) {
                $response = ['pais' => 'El pais del cliente ingresada no se encuentra registrada en nuestro sistema.'];
                array_push($returnArray, $response);
            } else {
                $country_isset = true;
            }
        }
        if (!isset($customer['provincia'])) { // Province
            $response = ['provincia' => 'Debe incluir la provincia del cliente.'];
            array_push($returnArray, $response);
        } else { //Validate Province
            if ($country_isset) {
                $province = \App\province::where('name', '=', $customer['provincia'])
                        ->where('country_id', '=', $country[0]->id)
                        ->get();
                if ($province->isEmpty()) {
                    $response = ['provincia' => 'La provincia del cliente ingresada no se encuentra registrada en nuestro sistema.'];
                    array_push($returnArray, $response);
                } else {
                    $province_isset = true;
                }
            }
        }
        if (!isset($customer['ciudad'])) { // City
            $response = ['ciudad' => 'Debe incluir la ciudad del cliente.'];
            array_push($returnArray, $response);
        } else { //Validate City
            if ($province_isset) {
                $city = App\city::where('name', '=', $customer['ciudad'])
                        ->where('province_id', '=', $province[0]->id)
                        ->get();
                if ($city->isEmpty()) {
                    $response = ['ciudad' => 'La ciudad del cliente ingresada no se encuentra registrada en nuestro sistema.'];
                    array_push($returnArray, $response);
                }
            }
        }
    }

    // Validate Vehicles
    if (!isset($request['vehiculo'][0])) {
        $response = ['vehiculo' => 'Debe incluir el vehiculo.'];
        array_push($returnArray, $response);
    } else {
        $vehi = $request['vehiculo'][0];
        if (!isset($vehi['placa'])) { // Plate
            $response = ['placa' => 'Debe incluir la placa del vehiculo.'];
            array_push($returnArray, $response);
        } else if (strlen($vehi['placa']) == 7 || strlen($vehi['placa']) == 6) { //Validate Plate Length
            $plate = $vehi['placa'];
            //Validate if first 3 characters are letters.
            if (!string_has_letters($plate[0]) || !string_has_letters($plate[1]) || !string_has_letters($plate[2])) { //Validate Plate Format
                $response = ['placa' => 'La placa del vehiculo ingresada no cumple con el formato correcto.'];
                array_push($returnArray, $response);
                //Validate last 4 characters are numbers
            } else if (!string_has_numbers($plate[3]) || !string_has_numbers($plate[4]) || !string_has_numbers($plate[5]) || !string_has_numbers($plate[6])) {
                $response = ['placa' => 'La placa del vehiculo ingresada no cumple con el formato correcto.'];
                array_push($returnArray, $response);
            }
        } else {
            $response = ['placa' => 'La placa del vehiculo ingresada no cumple con el formato correcto.'];
            array_push($returnArray, $response);
        }
        if (!isset($vehi['color'])) { // Color
            $response = ['color' => 'Debe incluir el color del vehiculo.'];
            array_push($returnArray, $response);
        }
        if (!isset($vehi['marca'])) { // Brand
            $response = ['marca' => 'Debe incluir la marca del vehiculo.'];
            array_push($returnArray, $response);
        } else { //Validate Brand
            $brand = App\vehiclesBrands::where('cod_equinoccial', '=', $vehi['marca'])->get();
            if ($brand->isEmpty()) {
                $response = ['marca' => 'La marca del vehiculo ingresada no se encuentra registada en nuestro sistema.'];
                array_push($returnArray, $response);
            }
        }
        if (!isset($vehi['modelo'])) { // Model
            $response = ['modelo' => 'Debe incluir el modelo del vehiculo.'];
            array_push($returnArray, $response);
        }
        if (!isset($vehi['anio'])) { // Year
            $response = ['anio' => 'Debe incluir el anio del vehiculo.'];
            array_push($returnArray, $response);
        } else {
            $Date = date('Y');
            $minimunDate = $Date - 8;
            $maximunDate = $Date + 1;
            if (($vehi['anio'] < $minimunDate) || ($vehi['anio'] > $maximunDate)) {
                $response = ['anio' => 'El anio del vehiculo ingresado no cumple con los requisitos.'];
                array_push($returnArray, $response);
            }
        }
    }
    return $returnArray;
}

function validateCancelSales($request) {
    $returnArray = array();
    $i = 1;
    //Validate request_id
    $sale = $request['venta'][0];
    $request_id_isset = false;
    $agencia_isset = false;
    $placa_isset = false;
    if (!isset($sale['request_id'])) { // Request_id
        $response = ['request_id' => 'Debe indicar el request_id.'];
        array_push($returnArray, $response);
    } else { // Validate Request_id
        $saleRequestId = App\sales::where('request_id', '=', $sale['request_id'])->get();
        if ($saleRequestId->isEmpty()) {
            $response = ['request_id' => 'El request_id enviado (' . $sale['request_id'] . ') no se encuentra registrado en el sistema.'];
            array_push($returnArray, $response);
        } else { // Validate Sale Status
            $saleStatus = App\sales::where('request_id', '=', $sale['request_id'])
                    ->where('status_id', '=', '4')
                    ->get();
            if (!$saleStatus->isEmpty()) {
                $response = ['request_id' => 'La venta ya se encuentra cancelada'];
                array_push($returnArray, $response);
            } else {
                $request_id_isset = true;
            }
        }
    }
    if (!isset($sale['agencia'])) { // Agency
        $response = ['agencia' => 'Debe indicar la Agencia.'];
        array_push($returnArray, $response);
    } else { // Validate Agency
        $agency = DB::table('agencies')
                ->where('cod_equinoccial', '=', $sale['agencia'])
                ->get();
        if ($agency->isEmpty()) {
            $response = ['agencia' => 'La agencia indicada (' . $sale['agencia'] . ') no se encuentra registrada en el sistema.'];
            array_push($returnArray, $response);
        } else {
            $agencia_isset = true;
        }
    }
    if (!isset($sale['placa'])) { // Plate 
        $response = ['placa' => 'Debe indicar la Placa.'];
        array_push($returnArray, $response);
    } else if (strlen($sale['placa']) == 7 || strlen($sale['placa']) == 6) { //Validate Plate Length
        $plate = $sale['placa'];
        //Validate if first 3 characters are letters.
        if (!string_has_letters($plate[0]) || !string_has_letters($plate[1]) || !string_has_letters($plate[2])) { //Validate Plate Format
            $response = ['placa' => 'La placa del vehiculo ingresada no cumple con el formato correcto.'];
            array_push($returnArray, $response);
            //Validate last 4 characters are numbers
        } else if (!string_has_numbers($plate[3]) || !string_has_numbers($plate[4]) || !string_has_numbers($plate[5])) {
            $response = ['placa' => 'La placa del vehiculo ingresada no cumple con el formato correcto.'];
            array_push($returnArray, $response);
        } else {
            // Validate Plate
            $validatePlate = DB::table('vehicles')
                    ->where('plate', '=', $sale['placa'])
                    ->get();
            if ($validatePlate->isEmpty()) {
                $response = ['placa' => 'La placa ingresada (' . $sale['placa'] . ') no se encuentra registrada en el sistema.'];
                array_push($returnArray, $response);
            } else {
                $placa_isset = true;
            }
        }
    } else {
        $response = ['placa' => 'La placa del vehiculo ingresada no cumple con el formato correcto.'];
        array_push($returnArray, $response);
    }
    if ($request_id_isset == true && $agencia_isset == true && $placa_isset == true) { // Validate Sale
        $agency = $sale['agencia'];
        $channel = DB::select('(select cha.id from channels cha where id = (select agen.channel_id from agencies agen where agen.cod_equinoccial = ' . $agency . '))');
        $saleValidate = DB::table('sales AS sal')
                ->join('agencies AS age', 'age.id', '=', 'sal.agen_id')
                ->join('channels AS cha', 'cha.id', '=', 'age.channel_id')
                ->join('vehicles_sales AS vsal', 'vsal.sales_id', '=', 'sal.id')
                ->join('vehicles AS vehi', 'vehi.id', '=', 'vsal.vehicule_id')
                ->where('sal.request_id', '=', $sale['request_id'])
                ->where('cha.id', '=', $channel[0]->id)
                ->where('vehi.plate', '=', $sale['placa'])
                ->get();
        if ($saleValidate->isEmpty()) {
            $response = ['venta' => 'El canal no posee una venta con ese request_id'];
            array_push($returnArray, $response);
        }
    }
    return $returnArray;
}

function processApiSale($request, $user_id) {
    $customer = $request['cliente'][0];
    $customerSearch = \App\customers::where('document', '=', $customer['identificacion'])->get();
    $city = \App\city::where('name', '=', $customer['ciudad'])->get();
    //Validate Customer Save or Update
    if (!$customerSearch->isEmpty()) {
        $customerUpdate = \App\customers::find($customerSearch[0]->id);
        $customerUpdate->address = $customer['direccion'];
        $customerUpdate->city_id = $city[0]->id;
        $customerUpdate->phone = $customer['telefono1'];
        $customerUpdate->mobile_phone = $customer['telefono2'];
        $customerUpdate->email = $customer['correo'];
        $customerUpdate->save();
        $customerId = $customerUpdate->id;
        $customerPhone = substr($customerUpdate->mobile_phone, 1);
        $customerEmail = $customerUpdate->email;
    } else {
        $customerNew = new \App\customers();
        $customerNew->first_name = $customer['nombre'];
        $customerNew->last_name = $customer['apellido'];
        $customerNew->document = $customer['identificacion'];
        $customerNew->document_id = 1;
        $customerNew->address = $customer['direccion'];
        $customerNew->city_id = $city[0]->id;
        $customerNew->phone = $customer['telefono1'];
        $customerNew->mobile_phone = $customer['telefono2'];
        $customerNew->email = $customer['correo'];
        $customerNew->status_id = 1;
        $customerNew->save();
        $customerId = $customerNew->id;
        $customerPhone = substr($customerNew->mobile_phone, 1);
        $customerEmail = $customerNew->email;
    }

    $sale = $request['venta'][0];

    //Obtain Product Data
    $productSql = 'select * from products_channel where product_id = ' . $sale['producto'];
    $productChannel = DB::select($productSql);

    //Random Code
    $randomCode = '1234';

    //OBTAIN AGENCY
    $agencyQuery = 'select * from agencies where id = ' . $sale['agencia'];
    $agency = DB::select($agencyQuery);

    //Calculate Prices
    //Obtain Product Tax
    $taxSql = 'SELECT tax.percentage FROM 
                    products_tax ptax 
                    join tax tax on tax.id = ptax.tax_id
                    where ptax.products_id = "' . $sale['producto'] . '"';
    $tax = DB::select($taxSql);

    //Generate SQL
    $productSql = 'select * from products where id = "' . $sale['producto'] . '"';
    //Obtain Product Data
    $product = DB::select($productSql);

    //Obtain First Vehicle
    $vehicles = $request['vehiculo'];

    //Declare Vehicle Count Variable
    $vehicleCount = 0;

    $arrayResponse = array();

    $data = '';
    $vehicltesTableArray = array();
    $taxTableArray = array();
    $sub12Price = 0;
    $sub0Price = 0;
    $benefitPercentage = 0;
    if ($tax[0]->percentage > 0) {
        $sub12Price += $product[0]->price;
    } else {
        $sub0Price += $product[0]->price;
    }

    //Calculate Other Discounts
    $otherDiscount = 0;

    $subTotal = (($sub12Price + $sub0Price) - $otherDiscount);

    //Calculate IVA
    $iva = (($tax[0]->percentage * $subTotal) / 100);

    //Calculate Total
    $total = $iva + $subTotal + $sub0Price;

    //Format Dates
    $date = str_replace('/', '-', $sale['vigencia_desde']);
    $newBeginDate = date("Y-m-d", strtotime($date));
    $date = str_replace('/', '-', $sale['vigencia_hasta']);
    $newEndDate = date("Y-m-d", strtotime($date));

    //DateTime
    $now = new \DateTime();
    $nowFormat = $now->format('Y-m-d');

    //VALIDATE  VIGENCIA    
    if (($newBeginDate <= $nowFormat) && ($newEndDate >= $nowFormat)) {
        $status = 1;
    } else {
        $status = 2;
    }

    $activationDate = date('Y-m-d', strtotime($nowFormat . ' + 30 days'));

    //Store Sale
    $salesNew = new \App\sales();
    $salesNew->pbc_id = $productChannel[0]->id;
    $salesNew->user_id = $user_id;
    $salesNew->customer_id = $customerId;
    $salesNew->status_id = 2;
    $salesNew->emission_date = $now;
    $salesNew->token_date_send = $now;
    $salesNew->subtotal_12 = round($sub12Price, 2);
    $salesNew->subtotal_0 = round($sub0Price, 2);
    $salesNew->other_discount = round($otherDiscount, 2);
    $salesNew->tax = round($iva, 2);
    $salesNew->total = round($total, 2);
    $salesNew->token = $randomCode;
    $salesNew->begin_date = $newBeginDate;
    $salesNew->end_date = $newEndDate;
    $salesNew->sales_type_id = 4;
    $salesNew->agen_id = $agency[0]->id;
    $salesNew->cus_mobile_phone = $customer['telefono2'];
    $salesNew->cus_phone = $customer['telefono1'];
    $salesNew->cus_address = $customer['direccion'];
    $salesNew->cus_email = $customer['correo'];
    $salesNew->cus_city = $city[0]->id;
    $salesNew->request_id = $sale['request_id'];
    $salesNew->allow_activation_date = $activationDate;
    $salesNew->save();

    $massiveNew = new \App\massives();
    $massiveNew->upload_date = $now;
    $massiveNew->upload_user = $user_id;
    $massiveNew->agencies_id = $agency[0]->id;
    $massiveNew->massive_type_id = 1;
    $massiveNew->upload_file = null;
    $massiveNew->subtotal = round($sub12Price, 2);
    $massiveNew->tax = round($iva, 2);
    $massiveNew->total = round($total, 2);
    $massiveNew->status_massive_id = $status;
    $massiveNew->status_charge_id = 12;
    $massiveNew->save();

    $massiveSalesNew = new App\massives_sales();
    $massiveSalesNew->sales_id = $salesNew->id;
    $massiveSalesNew->massives_id = $massiveNew->id;
    $massiveSalesNew->save();

    //Send Welcome Mail
//    $job = (new EmailJobs($salesNew->customer_id, $salesNew->id, $customerEmail));
//    dispatch($job);
    //Store vehicles
    $vehicle = $request['vehiculo'][0];
    //Obtain Brand
    $brandSql = 'select * from vehicles_brands where cod_equinoccial = "' . $vehicle['marca'] . '"';
    $brand = DB::select($brandSql);

    //If vehicle Existe Update else Store
    $vehicleSql = 'select * from vehicles where plate = "' . $vehicle['placa'] . '"';
    $vehicleSearch = DB::select($vehicleSql);

    if ($vehicleSearch) {
        //Update Vehicule
        $vehicleUpdate = \App\vehicles::find($vehicleSearch[0]->id);
        $vehicleUpdate->plate = $vehicle['placa'];
        $vehicleUpdate->brand_id = $brand[0]->id;
        $vehicleUpdate->model = $vehicle['modelo'];
        $vehicleUpdate->year = $vehicle['anio'];
        $vehicleUpdate->color = $vehicle['color'];
        $vehicleUpdate->save();
        $vehicleId = $vehicleUpdate->id;
    } else {
        //Store Vehicle
        $vehiclesNew = new \App\vehicles();
        $vehiclesNew->plate = $vehicle['placa'];
        $vehiclesNew->brand_id = $brand[0]->id;
        $vehiclesNew->model = $vehicle['modelo'];
        $vehiclesNew->year = $vehicle['anio'];
        $vehiclesNew->color = $vehicle['color'];
        $vehiclesNew->save();
        $vehicleId = $vehiclesNew->id;
    }

    //Store Vehicles Sales
    $vehiclesSalesNew = new \App\vehicles_sales();
    $vehiclesSalesNew->sales_id = $salesNew->id;
    $vehiclesSalesNew->vehicule_id = $vehicleId;
    $vehiclesSalesNew->status_id = 1;
    $vehiclesSalesNew->save();

    //Obtain Channel
    $channel = DB::table('channels')
            ->join('agencies', 'agencies.channel_id', '=', 'channels.id')
            ->where('agencies.id', '=', $sale['agencia'])
            ->get();

    //Obtain Extra Benefits
    $benefitsQuery = 'select * from benefits where channels_id = "' . $channel[0]->id . '" and status_id not in (4) and "' . $sale['vigencia_desde'] . '"  between begin_date and end_date';
    $benefits = DB::select($benefitsQuery);

    //DateTime
    $now = new \DateTime();

    //Beneits Array
    foreach ($benefits as $benefit) {
        //Store Benefits Vehicles Sales
        $benefitsNew = new \App\benefits_vehicles_sales();
        $benefitsNew->benefits_id = $benefit->id;
        $benefitsNew->vsal_id = $vehiclesSalesNew->id;
        $benefitsNew->date = $now;
        $benefitsNew->save();
    }

    //Obtain Vehicle 1 Price
    $product = \App\products::find($sale['producto']);

    //Obta
    //Store Vehicles Sales Prices
    $vehicleSalesPrices = new \App\vehicles_sales_prices();
    $vehicleSalesPrices->sales_id = $salesNew->id;
    $vehicleSalesPrices->price = $product['price'];
    $vehicleSalesPrices->status_id = 1;
    $vehicleSalesPrices->save();


    return 'ok';
}

function processApiCancel($request, $user_id) {
    $requestData = $request['venta'][0];
    //Obtain Sale Data
    $sale = App\sales::where('request_id', '=', $requestData['request_id'])->get();

    //Now
    $now = new DateTime();

    //Obtain Agency
    $agency = DB::table('agencies')->where('cod_equinoccial', '=', $requestData['agencia'])->get();

    //Create Massive Sale
    $massive = new \App\massives();
    $massive->upload_date = $now;
    $massive->upload_user = $user_id;
    $massive->subtotal = $sale[0]->subtotal_12;
    $massive->tax = $sale[0]->tax;
    $massive->total = $sale[0]->total;
    $massive->agencies_id = $agency[0]->id;
    $massive->status_massive_id = 15;
    $massive->status_charge_id = 15;
    $massive->massive_type_id = 2;
    $massive->save();

    //Obtain Vehicles Sales Data
    $vSalData = App\vehicles_sales::where('sales_id', '=', $sale[0]->id)->get();

    //Cancel Vehicles Sales
    $vSal = \App\vehicles_sales::find($vSalData[0]->id);
    $vSal->status_id = 2;
    $vSal->save();

    //Cancel Sales
    $sales = \App\sales::find($sale[0]->id);
    $sales->status_id = 4;
    $sales->save();

    //Massive Sales
    $massiveSales = new \App\massives_sales();
    $massiveSales->sales_id = $sale[0]->id;
    $massiveSales->massives_id = $massive->id;
    $massiveSales->save();

    //Validate ir it has a Pending scheduling
    $scheSql = 'select deta.id from scheduling_details deta
                                    join scheduling sche on sche.id = deta.scheduling_id
                                    where sche.vehicles_sales_id = ' . $vSal->id . ' and deta.status_id = 3';
    $sche = DB::select($scheSql);

    if ($sche) {
        $deta = \App\schedulingDetails::find($sche[0]->id);
        $deta->status_id = 4;
        $deta->cancel_motive_id = 12;
        $deta->cancel_date = $now;
        $deta->cancel_user_id = \Auth::user()->id;
        $deta->save();
    }
    return 'ok';
}

// Does string contain letters?
function string_has_letters($string) {
    return preg_match('/[a-zA-Z]/', $string);
}

// Does string contain numbers?
function string_has_numbers($string) {
    return preg_match('/\d/', $string);
}

// Does string contain special characters?
function string_has_special_chars($string) {
//    return preg_match('/[^a-zA-Z\d]/', $string);
    return preg_match('/[\/\*\-\_\+\.\,]/', $string);
}

// Does string contain different special characters?
function string_has_different_chars($string) {
//    return preg_match('/[^a-zA-Z\d]/', $string);
    return preg_match('/[^a-zA-Z\d\/\*\-\_\+\.\,]/', $string);
}

function string_has_other_than_letters($string) {
//    return preg_match('/[^a-zA-Z\d]/', $string);
    return preg_match('/[^a-zA-Z]/', $string);
}

function string_has_other_than_numbers($string) {
//    return preg_match('/[^a-zA-Z\d]/', $string);
    return preg_match('/[^0-9]/', $string);
}

function validateUserRoute($request) {
    if ($request->is('sales')) {
        return redirect()->route('home');
    } else {
        return view('home');
    }
}

function loadMenuMain() {
    $query = 'select  men.* from menu men join menu_rol mrol on mrol.menu_id = men.id where men.parent_id = 0 and mrol.rol_id = "' . \Auth::user()->role_id . '" order by men.order';
    $menus = DB::select($query);

    $returnData = '';

    foreach ($menus as $menu) {
        $returnData .= '<li><a href="{{ asset("/' . $menu->url . '")}}" style="cursor:pointer" ><span class="' . $menu->icon . '" style="margin-right:16px; font-size:20px;"></span>' . $menu->name . '</a></li>';
    }

//    Return '<h3>'.$menu[0]->url.'</h3>';
    Return $menus;
}

function loadMenuChild($id) {
    $query = 'select men.* from menu men join menu_rol mrol on mrol.menu_id = men.id where men.parent_id = "' . $id . '" and mrol.rol_id = "' . \Auth::user()->role_id . '" order by men.order';
    $menus = DB::select($query);

    return $menus;
}

function validateCreate() {
//    $url = \request->path();
}

function validateDate($date, $format = 'd-m-Y') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function checkmydate($date) {
    return $date;
    $tempDate = explode('/', $date);
    // checkdate(month, day, year)
    return checkdate($tempDate[1], $tempDate[0], $tempDate[2]);
}

function validateId($cedula) {
    if (is_numeric($cedula)) {
        $num = strlen("$cedula");
        if ($num == 10) {
            $total = 0;
            $digito = (1 * $cedula[9]);
            $provincia = $cedula[0] . $cedula[1];
            if (!($provincia >= 1 && $provincia <= 24) || $provincia == 30) {
                return false;
            }
            for ($i = 0; $i < ($num - 1); $i++) {
                if ($i % 2 != 0) {
                    $total += 1 * $cedula[$i];
                } else {
                    $total += 2 * $cedula[$i];
                    if ((2 * $cedula[$i]) > 9) {
                        $total -= 9;
                    }
                }
            }
            $final = (((floor($total / 10)) + 1) * 10) - $total;
            if (($final == 10 && $digito == 0) || ($final == $digito)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//======================== START OF FUNCTION ==========================//
// FUNCTION: str_contains_only                                         //
//=====================================================================//
/**
 * Checks whether a string contains only characters specified in the gama.
 * @param String $string
 * @param String $gama
 * @return boolean
 */
function str_contains_only($string, $gama) {
    $chars = mb_str_to_array($string);
    $gama2 = mb_str_to_array($gama);
    foreach ($chars as $char) {
        if (in_array($char, $gama2) == false)
            return false;
    }
    return true;
}

//=====================================================================//
//  FUNCTION: str_contains_only                                        //
//========================= END OF FUNCTION ===========================//
//======================== START OF FUNCTION ==========================//
// FUNCTION: mb_str_to_array                                           //
//=====================================================================//
/**
 * A substitution of str_split working with not only ASCII strings.
 * @param String $string
 * @return Array
 */
function mb_str_to_array($string) {
    mb_internal_encoding("UTF-8"); // Important
    $chars = array();
    for ($i = 0; $i < mb_strlen($string); $i++) {
        $chars[] = mb_substr($string, $i, 1);
    }
    return $chars;
}

//=====================================================================//
//  FUNCTION: mb_str_to_array                                          //
//========================== END OF METHOD ============================//

function changeSaleStatus($id, $status) {
    $sales = \App\sales::find($id);
    $sales->status_id = $status;
    $sales->save();
    return true;
}

/**
 * Nos da acceso a la cuenta.
 *
 * @url GET /placa/$placa
 */
function validatePlateANT($plate) {
    header("Content-Type: application/json");
    $plate = strtoupper($plate);
    $options = array(
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0", // don't return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_ENCODING => "UTF8", // handle all encodings
        CURLOPT_USERAGENT => "spider", // who am i
        CURLOPT_AUTOREFERER => true, // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 5, // timeout on connect
        CURLOPT_TIMEOUT => 10, // timeout on response
        CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false, // Quita SSL
    );

//    $c = curl_init('http://consultas.atm.gob.ec/PortalWEB/paginas/clientes/clp_grid_citaciones.jsp?ps_tipo_identificacion=PLA&ps_identificacion='.$plate.'&ps_placa=' . $plate . '&ps_placa=');
    $c = curl_init('https://sistemaunico.ant.gob.ec:5038/PortalWEB/paginas/clientes/clp_grid_citaciones.jsp?ps_tipo_identificacion=PLA&ps_identificacion=' . $plate . '&ps_placa=');

    curl_setopt_array($c, $options);
    $page = utf8_decode(curl_exec($c));
    $error = curl_error($c);
    curl_close($c);
    
    preg_match_all('|<[^>]+>(.*)</[^>]+>|U', $page, $resultado);
//    print_r($resultado[1]);die();
    $year = date("Y");
    if ($error) { //CURL ERROR - CONNECTION ERROR
        //DATETIME
        $now = new \DateTime();

        //UPDATE ANT ERROR LOG
        $ant = new \App\AntErrorLog();
        $ant->plate = $plate;
        $ant->date = $now;
        $ant->user_id = \Auth::user()->id;
        $ant->response = $error;
        $ant->save();

        $respuesta = array(
            "Error" => "<center style='font-size:14px'>El sistema de Recopilación de Datos Automático se encuentra fuera de línea, por favor, ingrese los datos manualmente.</center>",
            "success" => "antDown"
        );
    } else {
        if (isset($resultado[1][13])) { //VALID PLATE
            http_response_code(200);
            if ($resultado[1][28] < ($year - 8)) {
                $respuesta = array(
                    "Error" => "<center style='font-size:14px'>El Vehiculo no puede tener una antiguedad mayor a 8 años (" . $resultado[1][28] . ")</center>",
                    "success" => "false"
                );
            } else {
                $respuesta = array(
                    "placa" => $plate,
                    "marca" => $resultado[1][15],
                    "color" => $resultado[1][17],
                    "anioMatricula" => $resultado[1][19],
                    "modelo" => $resultado[1][22],
                    "clase" => $resultado[1][24],
                    "fechaMatricula" => $resultado[1][26],
                    "anioFabricacion" => $resultado[1][28],
                    "servicio" => $resultado[1][30],
                    "fechaCaducidad" => $resultado[1][32],
                    "success" => "true"
                );
            }
        } else if (!isset($resultado[0][13])) { // INVALID PLATE
            http_response_code(400);
            $respuesta = array(
                "Error" => "<center style='font-size:14px'>La placa (" . $plate . ") ingresada es incorrecta</center>",
                "success" => "false"
            );
        } else { // ANT ERROR - WEBSITE DOWN
            //DATETIME
            $now = new \DateTime();

            //UPDATE ANT ERROR LOG
            $ant = new \App\AntErrorLog();
            $ant->plate = $plate;
            $ant->date = $now;
            $ant->user_id = \Auth::user()->id;
            $ant->response = $error;
            $ant->save();

            $respuesta = array(
                "Error" => "<center style='font-size:14px'>El sistema de Recopilación de Datos Automático se encuentra fuera de línea, por favor, ingrese los datos manualmente.</center>",
                "success" => "antDown"
            );
        }
    }
    return $respuesta;
}

function consumeWS($request) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_PORT => "81",
        CURLOPT_URL => "http://192.168.10.22:81/api/storeUploadSales",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($request),
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic bWlkZGxld2FyZTpiOWNkOWFkZjMxN2JhMGE2NzkyMTliYjBhZTU3OTNkMio=",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: ef98e11d-57bd-bb3c-1b04-70bd17bba4e4"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    return $response;
}

function isDate($string) { //FORMAT DD/MM/YYYY
    $matches = array();
    $pattern = '/^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4})$/';
    if (!preg_match($pattern, $string, $matches))
        return false;
    if (!checkdate($matches[2], $matches[1], $matches[3]))
        return false;
    return true;
}

function activeSaleDiners($id) {
    $sale = App\sales::find($id);
    $now = new DateTime();
    $date = new DateTime($sale->allow_activation_date);
    if ($date < $now) {
        //Original Sale
        $sale->status_id = 1;
        $sale->save();

        //Promotion Sale
        $promo = \App\sales::where('sales_id', '=', $sale->id)->get();
        if (!$promo->isEmpty()) {
            $salePromo = \App\sales::find($promo[0]->id);
            $salePromo->status_id = 1;
            $salePromo->save();
        }
    }
}

function processFtpCoris() {
    //Validate File Exist in FTP Server
    if (!Storage::disk('sftp')->exists('format_coris.xlsx')) {
        $data = array('name' => "El archivo de carga de Coris no fue encontrado");
        Mail::send('mail', $data, function($message) {
            $message->to('carlos.oberto88@gmail.com')->cc('coberto@magnusmas.com')->subject('El archivo de carga de Coris no fue encontrado');
            $message->attach(public_path('coris_formato.xlsx'));
            $message->from('info@hitsolution.ec', 'Info');
        });
        die();
    }
    //Download File
    Storage::disk('public_sftp')->put('coris_formato.xlsx', Storage::disk('sftp')->get('format_coris.xlsx'));

    //Variables
    $errorReturn = array();

    //Obtain File from local server
    if (!file_exists(public_path('coris_formato.xlsx'))) {
        $data = array('name' => "El archivo de carga de Coris no pudo ser descargado");
        Mail::send('mail', $data, function($message) {
            $message->to('carlos.oberto88@gmail.com')->cc('coberto@magnusmas.com')->subject('El archivo de carga de Coris no pudo ser descargado');
            $message->attach(public_path('coris_formato.xlsx'));
            $message->from('info@hitsolution.ec', 'Info');
        });
    } else {
        $collection = (new FastExcel)->import(public_path('coris_formato.xlsx'));
        foreach ($collection as $data) {
            //Validate Data
            if (!isset($data['PAIS'])) { //PAIS
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir un Pais '
                ];
                array_push($errorReturn, $errorMessage);
            } else {
                $country = App\country::where('name', '=', $data['PAIS'])->get();
                if ($country->isEmpty()) {
                    $errorMessage = [
                        "placa" => $data['PLACA'],
                        "error" => 'El pais ingresado (' . $data['PAIS'] . ') no se encuentra registrado en el sistema.'
                    ];
                    array_push($errorReturn, $errorMessage);
                }
            }
            if (!isset($data['PROVINCIA'])) { //PROVINCIA
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir una Provincia '
                ];
                array_push($errorReturn, $errorMessage);
            } else {
                $province = App\province::where('name', '=', $data['PROVINCIA'])->get();
                if ($province->isEmpty()) {
                    $errorMessage = [
                        "placa" => $data['PLACA'],
                        "error" => 'La provincia ingresada (' . $data['PROVINCIA'] . ') no se encuentra registrada en el sistema.'
                    ];
                    array_push($errorReturn, $errorMessage);
                }
            }
            if (!isset($data['CIUDAD'])) { // CITY
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir una Ciudad '
                ];
                array_push($errorReturn, $errorMessage);
            } else {
                $city = App\city::where('name', '=', $data['CIUDAD'])->get();
                if ($city->isEmpty()) {
                    $errorMessage = [
                        "placa" => $data['PLACA'],
                        "error" => 'La ciudad ingresada (' . $data['CIUDAD'] . ') no se encuentra registrada en el sistema.'
                    ];
                    array_push($errorReturn, $errorMessage);
                }
            }
            if (!isset($data['ID'])) { // ID
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir una identificacion'
                ];
                array_push($errorReturn, $errorMessage);
            } else {
                $result = validateId(trim($data['ID']));
                if (!$result) {
                    echo 'false';
                    $errorMessage = [
                        "placa" => $data['PLACA'],
                        "error" => 'La cedula ingresada es incorrecta'
                    ];
                    array_push($errorReturn, $errorMessage);
                }
            }
            if (!isset($data['APELLIDOS'])) { // Last Name
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir un Apellido.'
                ];
                array_push($errorReturn, $errorMessage);
            }
            if (!isset($data['NOMBRES'])) { // First Name
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir un Nombre'
                ];
                array_push($errorReturn, $errorMessage);
            }
            if (!isset($data['DIRECCIÓN'])) { // Address
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir una direccion'
                ];
                array_push($errorReturn, $errorMessage);
            }
            if (!isset($data['TELÉFONO FIJO'])) { // Home Phone Number
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir un Telefon Fijo'
                ];
                array_push($errorReturn, $errorMessage);
            } else if (!is_numeric($data['TELÉFONO FIJO'])) {
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'El telefono fijo ingresado es incorrecto'
                ];
                array_push($errorReturn, $errorMessage);
            } else if (strlen($data['TELÉFONO FIJO']) != 9) {
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'El telefono fijo ingresado es incorrecto'
                ];
                array_push($errorReturn, $errorMessage);
            }
            if (!isset($data['TELÉFONO MÓVIL'])) { // Mobile Phone Number
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir un telefono movil'
                ];
                array_push($errorReturn, $errorMessage);
            } else if (!is_numeric($data['TELÉFONO MÓVIL'])) {
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'El telefono movil ingresado es incorrecto'
                ];
                array_push($errorReturn, $errorMessage);
            } else if (strlen($data['TELÉFONO MÓVIL']) != 10) {
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'El telefono movil ingresado es incorrecto'
                ];
                array_push($errorReturn, $errorMessage);
            }
            if (!isset($data['EMAIL'])) { // EMAIL
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir un Email'
                ];
                array_push($errorReturn, $errorMessage);
            } else if (!filter_var($data['EMAIL'], FILTER_VALIDATE_EMAIL)) {
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'El Email ingresado es incorrecto.'
                ];
                array_push($errorReturn, $errorMessage);
            }
            if (!isset($data['VIGENCIA DESDE'])) { // Emission Date
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir una Vigencia Desde'
                ];
                array_push($errorReturn, $errorMessage);
            } else {
                $result = isDate($data['VIGENCIA DESDE']);
                if (!$result) {
                    $errorMessage = [
                        "placa" => $data['PLACA'],
                        "error" => 'La fecha vigencia desde (' . $data['VIGENCIA DESDE'] . ') ingresada es incorrecta.'
                    ];
                    array_push($errorReturn, $errorMessage);
                }
            }
            if (!isset($data['FECHA DE VENTA'])) {
                $errorMessage = [
                    "placa" => $data['PLACA'],
                    "error" => 'Debe definir una fecha cobro123'
                ];
                array_push($errorReturn, $errorMessage);
            } else {
                $result = isDate($data['FECHA DE VENTA']);
                if (!$result) {
                    $errorMessage = [
                        "placa" => $data['PLACA'],
                        "error" => 'La fecha vigencia desde (' . $data['FECHA DE VENTA'] . ') ingresada es incorrecta.'
                    ];
                    array_push($errorReturn, $errorMessage);
                }
            }
        }
        // Validate Error
        if ($errorReturn) {
            //Generate Error Excel
            $collet = collect($errorReturn);

            $border = (new BorderBuilder())
                    ->setBorderBottom(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                    ->setBorderTop(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                    ->setBorderLeft(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                    ->setBorderRight(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                    ->build();

            $style = (new StyleBuilder())
                    ->setFontBold()
                    ->setFontSize(12)
                    ->setShouldWrapText(true)
                    ->setBorder($border)
                    ->setBackgroundColor(Color::YELLOW)
                    ->build();

            $name = rand() . 'uploadError.xlsx';
            $errorLog = (new FastExcel($collet))->headerStyle($style)->export(public_path('' . $name));
            $data = array('name' => "El archivo de carga de Coris presenta errores");
            Mail::send('mail', $data, function($message) use($name) {
                $message->to('carlos.oberto88@gmail.com')->cc('coberto@magnusmas.com')->subject('Se encontraron errores en el archivo');
                $message->attach(public_path('coris_formato.xlsx'));
                $message->attach(public_path($name));
                $message->from('info@hitsolution.ec', 'Info');
            });
        } else {
            //Process Sales
            foreach ($collection as $data) {
                //City ID
                $city_id = App\city::where('name', '=', $data['CIUDAD'])->get();

                //Customer
                $customer = \App\customers::where('document', '=', $data['ID'])->get();
                if ($customer->isEmpty()) {
                    $customerNew = new \App\customers();
                    $customerNew->document_id = 1;
                    $customerNew->document = $data['ID'];
                    $customerNew->first_name = $data['NOMBRES'];
                    $customerNew->last_name = $data['APELLIDOS'];
                    $customerNew->address = $data['DIRECCIÓN'];
                    $customerNew->city_id = $city_id[0]->id;
                    $customerNew->phone = $data['TELÉFONO FIJO'];
                    $customerNew->mobile_phone = $data['TELÉFONO MÓVIL'];
                    $customerNew->email = $data['EMAIL'];
                    $customerNew->status_id = 1;
                    $customerNew->save();
                    $customer_id = $customerNew->id;
                } else {
                    $customerUpdate = \App\customers::find($customer[0]->id);
                    $customerUpdate->address = $data['DIRECCIÓN'];
                    $customerUpdate->city_id = $city_id[0]->id;
                    $customerUpdate->phone = $data['TELÉFONO FIJO'];
                    $customerUpdate->mobile_phone = $data['TELÉFONO MÓVIL'];
                    $customerUpdate->email = $data['EMAIL'];
                    $customerUpdate->save();
                    $customer_id = $customerUpdate->id;
                }

                //Calculate Dates
                $emissionDate = Carbon::createFromFormat('d/m/Y', $data['VIGENCIA DESDE']);
                $beginDate = Carbon::createFromFormat('d/m/Y', $data['FECHA DE VENTA']);
                $endDate = Carbon::createFromFormat('d/m/Y', $data['FECHA DE VENTA'])->addYears(1);
                $activationDate = Carbon::createFromFormat('d/m/Y', $data['FECHA DE VENTA'])->addDays(30);

                //Obtain Product Data
                $product = App\products::find(7);

                //Calculate Tax
                $productTax = DB::table('tax as tax')
                        ->join('products_tax as pt', 'pt.tax_id', '=', 'tax.id')
                        ->where('pt.products_id', '=', $product->id)
                        ->select('tax.*')
                        ->get();
                $tax = 0;
                foreach ($productTax as $tx) {
                    $tax += $tx->percentage;
                }

                $calTax = (($product->prisce * $tax) / 100);

                //Sale Original
                $sale = new App\sales();
                $sale->pbc_id = 4;  // UPDATE TO NEW PRODUCT BY CHANNEL ID
                $sale->user_id = 60;
                $sale->customer_id = $customer_id;
                $sale->sales_type_id = 5;
                $sale->emission_date = $emissionDate;
                $sale->begin_date = $beginDate;
                $sale->end_date = $endDate;
                $sale->status_id = 2;
                $sale->subtotal_12 = $product->price;
                $sale->subtotal_0 = 0;
                $sale->other_discount = 0;
                $sale->tax = $calTax;
                $sale->total = $product->total_price;
                $sale->agen_id = 18; // UPDATE TO NEW AGENCY ID
                $sale->cus_mobile_phone = $data['TELÉFONO MÓVIL'];
                $sale->cus_phone = $data['TELÉFONO FIJO'];
                $sale->cus_address = $data['DIRECCIÓN'];
                $sale->cus_email = $data['EMAIL'];
                $sale->cus_city = $city_id;
                $sale->allow_activation_date = $activationDate;
                $sale->save();

                //Now
                $now = new DateTime();

                $massiveNew = new \App\massives();
                $massiveNew->upload_date = $now;
                $massiveNew->upload_user = 60; // UPDATE TO NEW USER
                $massiveNew->agencies_id = 18; // UPDATE TO NEW AGENCY ID
                $massiveNew->massive_type_id = 1;
                $massiveNew->upload_file = null;
                $massiveNew->subtotal = $sale->subtotal_12;
                $massiveNew->tax = $sale->tax;
                $massiveNew->total = $sale->total;
                $massiveNew->status_massive_id = 2;
                $massiveNew->status_charge_id = 12;
                $massiveNew->save();

                $massiveSalesNew = new App\massives_sales();
                $massiveSalesNew->sales_id = $sale->id;
                $massiveSalesNew->massives_id = $massiveNew->id;
                $massiveSalesNew->save();

                // Promotion Sale
                $salePromo = new App\sales();
                $salePromo->sales_id = $sale->id;
                $salePromo->pbc_id = 7;  // UPDATE TO NEW PRODUCT BY CHANNEL ID
                $salePromo->user_id = 60;
                $salePromo->customer_id = $customer_id;
                $salePromo->sales_type_id = 6;
                $salePromo->emission_date = $emissionDate;
                $salePromo->begin_date = $beginDate;
                $salePromo->end_date = $endDate;
                $salePromo->status_id = 2;
                $salePromo->subtotal_12 = $product->price;
                $salePromo->subtotal_0 = 0;
                $salePromo->other_discount = 0;
                $salePromo->tax = $calTax;
                $salePromo->total = $product->total_price;
                $salePromo->agen_id = 18; // UPDATE TO NEW AGENCY ID
                $salePromo->cus_mobile_phone = $data['TELÉFONO MÓVIL'];
                $salePromo->cus_phone = $data['TELÉFONO FIJO'];
                $salePromo->cus_address = $data['DIRECCIÓN'];
                $salePromo->cus_email = $data['EMAIL'];
                $salePromo->cus_city = $city_id;
                $salePromo->allow_activation_date = $activationDate;
                $salePromo->save();

                $massiveNewPromo = new \App\massives();
                $massiveNewPromo->upload_date = $now;
                $massiveNewPromo->upload_user = 60; // UPDATE TO NEW USER
                $massiveNewPromo->agencies_id = 18; // UPDATE TO NEW AGENCY ID
                $massiveNewPromo->massive_type_id = 1;
                $massiveNewPromo->upload_file = null;
                $massiveNewPromo->subtotal = $salePromo->subtotal_12;
                $massiveNewPromo->tax = $salePromo->tax;
                $massiveNewPromo->total = $salePromo->total;
                $massiveNewPromo->status_massive_id = 2;
                $massiveNewPromo->status_charge_id = 12;
                $massiveNewPromo->save();

                $massiveSalesNewPromo = new App\massives_sales();
                $massiveSalesNewPromo->sales_id = $salePromo->id;
                $massiveSalesNewPromo->massives_id = $massiveNewPromo->id;
                $massiveSalesNewPromo->save();
            }
            echo 'todo ok';
        }
    }
}

function cancelMassives(request $request) {
    return $request;
}

function processFtpCorisCancel() {
//Validate File Exist in FTP Server
    if (!Storage::disk('sftp')->exists('format_coris_cancel.xlsx')) {
        $data = array('name' => "El archivo de carga de Coris no fue encontrado");
        Mail::send('mail', $data, function($message) {
            $message->to('carlos.oberto88@gmail.com')->cc('coberto@magnusmas.com')->subject('El archivo de carga de Coris no fue encontrado');
            $message->attach(public_path('coris_formato.xlsx'));
            $message->from('info@hitsolution.ec', 'Info');
        });
        die();
    }
    //Download File
    Storage::disk('public_sftp')->put('coris_formato_cancel.xlsx', Storage::disk('sftp')->get('format_coris_cancel.xlsx'));

    //Variables
    $errorReturn = array();

    //Obtain File from local server
    if (!file_exists(public_path('coris_formato.xlsx'))) {
        $data = array('name' => "El archivo de carga de Coris no pudo ser descargado");
        Mail::send('mail', $data, function($message) {
            $message->to('carlos.oberto88@gmail.com')->cc('coberto@magnusmas.com')->subject('El archivo de carga de Coris no pudo ser descargado');
            $message->attach(public_path('coris_formato.xlsx'));
            $message->from('info@hitsolution.ec', 'Info');
        });
    } else {
        
    }
}

function checkPermits($id, $action) {
    $permit = \App\menuAction::where('menu_id', $id)->where('action', $action)->get();
    if ($permit->isEmpty()) {
        return false;
    } else {
        return true;
    }
}

function checkViewPermit($id, $user) {
    $permit = \App\menuRol::where('menu_id', $id)->where('rol_id', $user)->get();
    if ($permit->isEmpty()) {
        return false;
    } else {
        return true;
    }
}

function checkExtraPermits($id, $user) {
    $edit = DB::table('menu_action_rol')->where('menu_action', '=', $id)->where('rol_id', '=', \Auth::user()->role_id)->get();
    if ($edit->isEmpty()) {
        return false;
    } else {
        return true;
    }
}

function getAppRoute() {
    $global_var = \App\global_vars::find(1);
    if ($global_var->value == null) {
        return '';
    } else {
        return $global_var->value . 'public';
    }
}

function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateShortLink(){
    $randomString = generateRandomString();
    $isUnique = false;
    
    while(!$isUnique){
        $result = \App\short_url::where('short','=',$randomString)->get();
        if($result->isEmpty()){
            $isUnique = true;
        }else{
            $randomString = generateRandomString();
        }
    }
    return $randomString;
}

function BackupDatabase() {
    
}

function generateTokenViamatica($saleId, $document, $email, $mobilePhone, $browser) {
    $ip = Request::ip();

    //Create Json
    $data = array("claveAcceso" => 'AauC3dIowTp2A04MpkAl0I42V', "metodoEnvio" => 'A', "identificacion" => $document, "ip" => $ip, "metodoGeneracion" => 'web', "navegador" => $browser, "email" => $email, "celular" => $mobilePhone);
    $json = json_encode($data);

    //Now
    $now = new DateTime();

    //Store in Log
    $tokenLog = new \App\tokenLog();
    $tokenLog->send_data = $json;
    $tokenLog->send_date = $now;
    $tokenLog->type = 'GENERATE';
    $tokenLog->sales_id = $saleId;
    $tokenLog->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://polizael.segurossucre.fin.ec:8083/api/GeneraTokenO",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));

    $responseJson = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($responseJson);
    $responseCode = $response->codigo;
    //Now
    $now = new DateTime();

    $updateTokenLog = \App\tokenLog::find($tokenLog->id);
    $updateTokenLog->response_data = $responseJson;
    $updateTokenLog->response_date = $now;
    $updateTokenLog->response_code = $responseCode;
    $updateTokenLog->save();
}

function validateTokenViamatica($saleId, $document, $token) {
    //Create Json
    $data = array("claveAcceso" => 'AauC3dIowTp2A04MpkAl0I42V', "identificacion" => $document, "token" => $token);
    $json = json_encode($data);

    //Now
    $now = new DateTime();

    //Store in Log
    $tokenLog = new \App\tokenLog();
    $tokenLog->send_data = $json;
    $tokenLog->send_date = $now;
    $tokenLog->type = 'VALIDATE';
    $tokenLog->sales_id = $saleId;
    $tokenLog->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://polizael.segurossucre.fin.ec:8083/api/ValidaTokenO",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));

    $responseJson = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($responseJson);
    $responseCode = $response->codigo;
    //Now
    $now = new DateTime();

    $updateTokenLog = \App\tokenLog::find($tokenLog->id);
    $updateTokenLog->response_data = $responseJson;
    $updateTokenLog->response_date = $now;
    $updateTokenLog->response_code = $responseCode;
    $updateTokenLog->save();

    return $responseCode;
}

function obtainBrowser() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
        return 'Internet explorer';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
        return 'Internet explorer';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
        return 'Mozilla Firefox';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
        return 'Google Chrome';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
        return "Opera Mini";
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
        return "Opera";
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
        return "Safari";
    else
        return 'Something else';
}

function paymentPrepareCheckout() {
    $url = "https://test.oppwa.com/v1/checkouts";
    $data = "entityId=8a829418533cf31d01533d06f2ee06fa" .
            "&amount=92.00" .
            "&currency=USD" .
            "&paymentType=DB";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization:Bearer OGE4Mjk0MTg1MzNjZjMxZDAxNTMzZDA2ZmQwNDA3NDh8WHQ3RjIyUUVOWA=='));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData);
}

function processPaymentDataFast($id) {
    $url = "https://test.oppwa.com/v1/checkouts/$id/payment";
    $url .= "?entityId=8ac7a4c87112a8de0171134b4a28002b";

    $log = new App\datafastConectionLog();
    $log->request = $url;
    $log->request_date = new DateTime();
    $log->save();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization:Bearer OGE4Mjk0MTg1YTY1YmY1ZTAxNWE2YzhjNzI4YzBkOTV8YmZxR3F3UTMyWA=='));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);

    $logUpdate = App\datafastConectionLog::find($log->id);
    $logUpdate->response = $responseData;
    $logUpdate->response_date = new DateTime();
    $logUpdate->save();

    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData, true);
}

function paymentPrepareCheckoutProduction($items, $total, $iva, $totalTarifa12, $totalBase0, $email, $primer_nombre, $segundo_nombre, $apellido, $cedula, $trx, $ip_address, $finger, $telefono, $direccion_cliente, $pais_cliente, $direcccion_entrega, $pais_entrega) {
    $finger = urlencode($finger);
    $i = 0;
    $url = "https://test.oppwa.com/v1/checkouts";
    $formatIva = str_replace('.', '', $iva);
    $formatTotalTarifa12 = str_replace('.', '', $totalTarifa12);
    $formatTarifa0 = str_replace('.', '', $totalBase0);
    $valueIva = str_pad($formatIva, 12, '0', STR_PAD_LEFT);
    $valueTotalIva = str_pad($formatTotalTarifa12, 12, '0', STR_PAD_LEFT);
    $valueTotalBase0 = str_pad($formatTarifa0, 12, '0', STR_PAD_LEFT);
    $data = "authentication.entityId=8ac7a4c87112a8de0171134b4a28002b" .
            "&amount=" . $total .
            "&currency=USD" .
            "&paymentType=DB" .
            "&customer.givenName=" . $primer_nombre .
            "&customer.middleName=" . $segundo_nombre .
            "&customer.surname=" . $apellido .
            "&customer.ip=" . $ip_address .
            "&customer.merchantCustomerId=000000000001" .
            "&merchantTransactionId=transaction_" . $trx .
            "&customer.email=" . $email .
            "&customer.identificationDocType=IDCARD" .
            "&customer.identificationDocId=" . $cedula .
            "&customer.phone=" . $telefono .
            "&billing.street1=" . $direccion_cliente .
            "&billing.country=" . $pais_entrega .
            "&shipping.street1=" . $direcccion_entrega .
            "&shipping.country=" . $pais_entrega .
            "&risk.parameters[USER_DATA2]=MiComercio" .
            "&customParameters[1000000505_PD100406]=0081" . "0030070103910004012" . $valueIva . "05100817913101052012" . $valueTotalBase0 . "053012" . $valueTotalIva;
//            "&customParameters[1000000505_PD100406]=0081003007010391000401200000000001205100817913101052012000000000000053012000000000100";
    foreach ($items as $c) {
        $data .= "&cart.items[" . $i . "].name=" . $c["name"];
        $data .= "&cart.items[" . $i . "].description=" . "Descripcion:  " . $c["name"];
        $data .= "&cart.items[" . $i . "].price=" . $c["price"];
        $data .= "&cart.items[" . $i . "].quantity=" . $c["quantity"];
    }
    $data .= "&testMode=EXTERNAL"; 

    $log = new App\datafastConectionLog();
    $log->request = $data;
    $log->request_date = new DateTime();
    $log->save();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization:Bearer OGE4Mjk0MTg1YTY1YmY1ZTAxNWE2YzhjNzI4YzBkOTV8YmZxR3F3UTMyWA=='));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);

    $logUpdate = App\datafastConectionLog::find($log->id);
    $logUpdate->response = $responseData;
    $logUpdate->response_date = new DateTime();
    $logUpdate->save();

    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData);
}

function annulmentDataFast($proceso, $codigo_transaccional, $amount, $currency, $autorizacion_validacion, $referencia, $custom_parameter,
        $custom_value, $numtarjeta, $mesexp, $anioexp, $interes, $installments) {

    $url = "https://test.oppwa.com/v1/payments/".$codigo_transaccional;
    $data = "authentication.entityId=8ac7a4c87112a8de0171134b4a28002b" .
            "&paymentType=" . $proceso .
            "&amount=" . $amount .
            "&currency=" . $currency . 
            "&customParameters[AUTHCODE]=" . $autorizacion_validacion .
            "&customParameters[PAN]=" . $numtarjeta .
            "&customParameters[STAN]=" . $referencia .
            "&customParameters[expiryMonth]=" . $mesexp .
            "&customParameters[expiryYear]=" . $anioexp .
            "&customParameters[1000000505_PD100406]=" . $custom_value.
            "&customParameters[SHOPPER_interes]=" . $interes.
            "&customParameters[SHOPPER_gracia]=0".
            "&customParameters[SHOPPER_installments]=" . $installments . "";
    $data .="&testMode=EXTERNAL";
//    return $data;
    $log = new App\datafastConectionLog();
    $log->request = $data;
    $log->request_date = new DateTime();
    $log->save();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization:Bearer OGE4Mjk0MTg1YTY1YmY1ZTAxNWE2YzhjNzI4YzBkOTV8YmZxR3F3UTMyWA=='));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);

    $logUpdate = App\datafastConectionLog::find($log->id);
    $logUpdate->response = $responseData;
    $logUpdate->response_date = new DateTime();
    $logUpdate->save();

    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData,true);
}

function refVehiPrice(){
    $price = \App\vehiclesPrices::where()->get();
}

function tokenSS() {
    //Validate if the TOKEN is valid
    $date = new DateTime;
    $date->modify('-60 minutes');
    $formatted_date = $date->format('Y-m-d H:i:s');    
    
    $token = App\token_ss::where('created_at','>',$formatted_date)->get();
//    if(!$token->isEmpty()){ //Token is Still Valid
//        return $token[0]['token'];
//    }else{ //Token is Invalid
        //Start Log
        $log = new \App\log_ss();
        $log->data_date_sent = new DateTime();
        $log->save();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic VWE2dmVnaUw0UkVUMG80UjZOdHlhYWNtazNBYToxaFNldzROdXNKdVJyVTRUeENlaXZVcndlUElh",
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        //Update Log
        $logUpdate = \App\log_ss::find($log->id);
        $logUpdate->data_received = $response;
        $logUpdate->data_date_received = new DateTime();
        $logUpdate->save();

        $data = json_decode($response, true);
        
        $token = new App\token_ss();
        $token->token = $data['access_token'];
        $token->save();

        return $data['access_token'];
//    }
    
    
}

function customerSS($typeDoc, $doc) {
    $data = [
        'cliente' => [
            'tipoidentificacion' => "$typeDoc",
            'identificacion' => "$doc"
        ]
    ];

    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/informacioncliente",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ". tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
//    dd($response);
    curl_close($curl);

    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
//    dd($data);
    return $data;
}

function contratanteSS($doc) {
    $data = [
        'identificacion' => "$doc"
    ];
//    return GuzzleHttp\json_encode($data);
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/informacioncontratante",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ". tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
//    return $response;
    curl_close($curl);

    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
//    dd($data);
    return $data;
}

function costoSeguroSS($ramoId, $primaNeta) {
    $data = [
        'cabecera' => [
            'ramoid' => $ramoId,
            'tipoanexoid' => 'POLI',
            'primaneta' => str_replace(',','',$primaNeta)
        ]
    ];
//    return GuzzleHttp\json_encode($data);
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/costoseguro",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ". tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
//    return $response;
    curl_close($curl);

    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
//    dd($data);
    return $data;
}

function devolucionPOlizaEmitidaSS($saleId, $ramo) {
    $data = [
        'datapoliza' => [
            'codigoapp' => "1",
            'codigotransaccion' => "$saleId",
            'ramo' => "$ramo"
        ]
    ];

    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/polizaemitida",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ". tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    
    curl_close($curl);

    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
//    dd($data);
    return $data;
}

function anulacionEndodoSS($saleId) {
    $sale = App\sales::selectRaw('sales.poliza, sales.certificado, sales.anexoid, cus.email, pro.ramoid')
                        ->join('customers as cus','cus.id','=','sales.customer_id')
                        ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                        ->join('products as pro','pro.id','=','pbc.product_id')
                        ->where('sales.id','=',$saleId)
                        ->get();
    $data = [
        'params' => [
            'motivoanuid' => "20",
            'ramoid' => $sale[0]->ramoid,
            'polizanum' => $sale[0]->poliza,
            'certificadoid' => $sale[0]->certificado,
            'anexoid' => $sale[0]->anexoid,
            'email1' => $sale[0]->email,
            'email2' => "",
            'email3' => "",
            'email4' => "",
            'observacion' => "Venta Anulada por el Cliente",
        ]
    ];

    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/anulacionendoso",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ". tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    
    curl_close($curl);

    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
//    dd($data);
    return $data;
}

function valorAseguradoSS($doc) {
    $data = [
        'identificacion' => $doc
    ];

    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/montoasegurado",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ". tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
//    dd($data);
    return $data;
}

function viamaticaSendPdf($document, $name, $mobilePhone, $saleId, $pdf, $email, $phone, $vinculationId, $type = 1, $nombreVisible = 'Formulario de Vinculacion', $tipoDocumento = 'Formulario') {
    if($type == 1){
        $vinForm = App\vinculation_form::find($vinculationId);
        if($vinForm->viamatica_id == null){
            $idDocMigrado = $saleId;
        }else{
            $idDocMigrado = $vinForm->viamatica_id;
        }
    }else{
        $saleForm = App\sales::find($saleId);
        if($saleForm->viamatica_id == null){
            $idDocMigrado = $saleId;
        }else{
            $idDocMigrado = $saleForm->viamatica_id;
        }
    }
    
    $ramoSearch = App\sales::selectRaw('pro.ramodes, pro.ramoid, pro.productodes')
                        ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                        ->join('products as pro','pro.id','=','pbc.product_id')
                        ->where('sales.id','=',$saleId)
                        ->get();
    
    if($ramoSearch[0]->ramoid == 5 || $ramoSearch[0]->ramoid == 40){
        $ramo = 'CASA HABITACION';
    }else{
        $ramo = $ramoSearch[0]->ramodes;
    }
    
    $date = date("Y/m/d");
    $hour = date("h:i");
    $data = [
        'fecha' => $date,
        'hora' => $hour,
        'identificacion' => $document, 
        'nombre' => $name,
        'celular' => $mobilePhone,
        'email' => $email,
        'telefono' => $phone,
        'datosAdicionales' => [
            'tipoDocumento' => $tipoDocumento,
            'descripcion' => $ramo .' - ' . $ramoSearch[0]->productodes
        ],
        'pdf' => [
            [
                "archivo" => $pdf,
                "firmar" => 'SI',
                'nombreVisible' => $nombreVisible
            ]
        ],
        'idDocMigrado' => $idDocMigrado
    ];
//    return \GuzzleHttp\json_encode($data, JSON_UNESCAPED_SLASHES);
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = \GuzzleHttp\json_encode($data, JSON_UNESCAPED_SLASHES);
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://polizael.segurossucre.fin.ec:8082/api/PolizasWA/PostDocumentosM",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => \GuzzleHttp\json_encode($data, JSON_UNESCAPED_SLASHES),
        CURLOPT_HTTPHEADER => array(
            "Usuario: Sucre2020",
            "Clave: SuCR3-2020doc",
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    
   //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
//    dd($data);
    return $data;
}

function vehicleSS($plate) {
    $data = [
        'placa' => "$plate"
    ];
    
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/vehiculo",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
//    dd($response);
    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
    
    return $data;
}

function documentosPolizaSS($saleId) {
    $branch = App\sales::selectRaw('pro.ramoid')
                        ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                        ->join('products as pro','pro.id','=','pbc.product_id')
                        ->where('sales.id','=',$saleId)
                        ->get();
    
    $data = [
        'datapoliza' => [
            'codigoapp' => '1',
            'codigotransaccion' => $saleId,
            'ramo' => $branch[0]->ramoid 
        ]
    ];
    
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/documentospoliza",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
//    dd($response);
    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);

    $endoso = null;
    
    if($data['error'][0]['code'] == '000') {
        //Recorrer los array
        foreach($data['infoanexo'] as $infoAnexo){
            foreach ($infoAnexo['documentos'] as $a) {
                if (strpos($a['nombredocumento'], 'EB') !== false) {
                    $endoso = $a['url'];
                }
                if (strpos($a['nombredocumento'], 'FP') !== false) {
                    $caratula = $a['url'];
                }
                if (strpos($a['nombredocumento'], 'CP') !== false) {
                    $condiciones = $a['url'];
                }
                if (strpos($a['nombredocumento'], 'RIDE') !== false) {
                    $factura = $a['url'];
                }
                if (strpos($a['nombredocumento'], 'XML') !== false) {
                    $xml = $a['url'];
                }
            }
        }
        
        $salesUpdate = App\sales::find($saleId);
        $salesUpdate->url_caratula = $caratula;
        $salesUpdate->url_condiciones = $condiciones;
        $salesUpdate->url_endoso = $endoso;
        $salesUpdate->url_factura = $factura;
        $salesUpdate->url_xml_factura = $xml;
        $salesUpdate->documento = $data['infoanexo'][0]['numdocumento'];
        $salesUpdate->poliza = $data['infoanexo'][0]['numpoliza'];
        $salesUpdate->save();
    }

    return $data;
}

function vinculaClienteeSS($saleId) {
    try{
        $cliente = App\vinculation_form::selectRaw('vinculation_form.*,
                                                    DATE_FORMAT(vinculation_form.birth_date, "%d/%m/%Y") as "birthDate",
                                                    pro.ramoid, sal.insured_value,
                                                    sal.prima_total, act.sbs as "activity",
                                                    ocu.id as "ocupation",
                                                    sal.total as "salTotal", 
                                                    pbc.canal_plan_id,
                                                    cit2.name as "birthCity",
                                                    cus.phone, cus.email,
                                                    vinculation_form.id as "vinId",
                                                    cit.PROVINCIAID_SS as "provId", 
                                                    cit.CANTONID_SS as "citId",
                                                    cus.document, cus.first_name,
                                                    cus.document_id as "cusDocType",
                                                    IFNULL(cus.second_name, "") as "second_name", cus.last_name,
                                                    civilState.code_ss as "civilState",
                                                    chan.canalnegoid,
                                                    cus.second_last_name')                                                
                                    ->join('sales as sal','sal.id','=','vinculation_form.sales_id')
                                    ->join('products_channel as pbc','pbc.id','=','sal.pbc_id')
                                    ->join('channels as chan','chan.id','=','pbc.channel_id')
                                    ->join('products as pro','pro.id','=','pbc.product_id')
                                    ->join('customers as cus','cus.id','=','sal.customer_id')
                                    ->join('cities as cit','cit.id','=','cus.city_id')
                                    ->join('civil_state as civilState','civilState.id','=','vinculation_form.civil_state')
                                    ->leftJoin('cities as cit2','cit2.id','=','vinculation_form.birth_place')
                                    ->leftJoin('economic_activity as act','act.id','=','vinculation_form.economic_activity_id')
                                    ->leftJoin('economic_ocupation as ocu','ocu.id','=','vinculation_form.occupation')
                                    ->where('vinculation_form.sales_id','=',$saleId)
                                    ->get();
                
        $birthCity = \App\cities_ss::where('CIUDADNOM','like','%'.TRIM($cliente[0]->birthCity).'%')->get();
        if($birthCity->isEmpty()){
            $birthCityFinal = TRIM($cliente[0]->birthCity);
            $birthCity2 = App\city::where('id','=',$cliente[0]->birth_place)->get();
            $bithProvince = App\province::where('id','=',$birthCity2[0]->province_id)->get();
            $birthCountry = App\country::where('id','=',$bithProvince[0]->country_id)->get();
        }else{
            $birthCityFinal = $birthCity[0]->CIUDADID;
            $birthCity2 = App\cities_ss::where('id','=',$cliente[0]->birth_place)->get();
            $bithProvince = App\province::where('id','=',$birthCity2[0]->CIUDADPROVINID)->get();
            $birthCountry = App\country::where('id','=',$bithProvince[0]->country_id)->get();
        }
        $fromDate = contratanteSS($cliente[0]->document);
        if($fromDate['infocontratante']['clientedesde'] == ''){
            $fromDate2 = date('d/m/Y');
        }else{
            $fromDate2 = $fromDate['infocontratante']['clientedesde'];
        }
        $vinculationUpdate = App\vinculation_form::find($cliente[0]->vinId);
        $vinculationUpdate->from_date = $fromDate2;
        $vinculationUpdate->save();
        
        if($birthCountry->isEmpty()){
            $country = 'EC';
        }else{
            $country = $birthCountry[0]->code_ss;
        }
        if($cliente[0]->cusDocType == '1'){
            $docType = 'C';
        }else{
            $docType = 'P';
        }
        
        if($cliente[0]->canalnegoid == 1){
            $vinculation = 1;
        }else{
            $vinculation = 6;
        }
        
        $datosCliente = [
            'canalplanid' => $cliente[0]->canal_plan_id,
            'identificacion' => $cliente[0]->document,
            'tipoidentificacion' => $docType,
            'tipocliente' => 'N',
            'tiposector' => 'PB',
            'nombrerazonsocial' => '',
            'replegaltipoid' => '',
            'repLegalid' => '',
            'primerape' => $cliente[0]->last_name,
            'segundoape' => $cliente[0]->second_last_name,
            'primernombre' => $cliente[0]->first_name,
            'segundonombre' => $cliente[0]->second_name,
            'fechaconstitucion' => '',
            'paisconstitucion' => $country,
            'paisresidencia' => 'EC',
            'provincia' => $cliente[0]->provId,
            'canton' => $cliente[0]->citId,
            'telefono' => $cliente[0]->phone,
            'email' => $cliente[0]->email,
            'estadocivil' => $cliente[0]->civilState,
            'sexo' => 'M',
            'clientedesde' => $fromDate2,
            'vinculacion' => $vinculation,
            'terceraedadcapespecial' => 'N',
            'fechanacimiento' => $cliente[0]->birthDate,
            'ciudadnacimiento' => $birthCityFinal,
            'residenciadireccion' => $cliente[0]->address_zone,
            'residenciacalleprincipal' => $cliente[0]->main_road,
            'residenciacalletransversal' => $cliente[0]->secondary_road,
            'residencialnumero' => $cliente[0]->address_number,
            'ingresos' => str_replace(',','',$cliente[0]->total_annual_income),
            'origen' => '2'
        ];

        if($cliente[0]->civil_state == 2){
            if($cliente[0]->spouse_document_id == '1'){
                $docType = 'C';
            }else{
                $docType = 'P';
            }
            $datosConyuge = [
                'tipoidentificacion' => $docType,
                'identificacion' => $cliente[0]->spouse_document,
                'lugarnacimiento' => '',
                'fechanacimiento' => '',
                'apellidos' => $cliente[0]->spouse_name,
                'nombres' => $cliente[0]->spouse_last_name,
                'idnacionalidad' => '',
                'actividadeconomicaid' => '',
                'ingresomensual' => '',
                'nombrempresa' => '',
                'telefono' => '',
                'direccion' => '',
                'email' => ''
            ];
        }else{
            $datosConyuge = [
                'tipoidentificacion' => '',
                'identificacion' => '',
                'lugarnacimiento' => '',
                'fechanacimiento' => '',
                'apellidos' => '',
                'nombres' => '',
                'idnacionalidad' => '',
                'actividadeconomicaid' => '',
                'ingresomensual' => '',
                'nombrempresa' => '',
                'telefono' => '',
                'direccion' => '',
                'email' => ''
            ];
        }

        $datosActividadEconomica = [
            'relaciondependencia' => '',
            'actividadeconomica' => $cliente[0]->activity,
            'nombrempresa' => '',
            'cargo' => '',
            'ingresomensual' => '',
            'pep' => '',
            'calleprincipal' => '',
            'callenumero' => '',
            'calletransversal' => '',
            'sector' => '',
            'actividadempresa' => '',
            'mail' => '',
            'telefono' => '',
            'fax' => ''
        ];

        $datosRelacionComercial = [
            'ramo' => $cliente[0]->ramoid,
            'tipo' => 'N',
            'cantidadpolizas' => '1',
            'valorasegurado' => str_replace(',','',$cliente[0]->insured_value),
            'primatotal' => str_replace(',','',$cliente[0]->salTotal)
        ];
        if($cliente[0]->total_assets_pasives == null){
            $totalActivosPasivos = '0.00';
        }else{
            $totalActivosPasivos = str_replace(',','',$cliente[0]->total_assets_pasives);
        }
        $datosSituacionFinanciera = [
            'otrosingresos' => str_replace(',','',$cliente[0]->other_annual_income),
            'descripcionotros' => str_replace(',','',$cliente[0]->description_other_income),
            'totalactivopasivo' => str_replace(',','',$totalActivosPasivos)
        ];

        $datosBeneficiarios = [
            'tipo' => '',
            'tiporelacion' => '',
            'especificarotros' => '',
            'tipoidentificacion' => '',
            'identificacion' => '',
            'sexo' => '',
            'nacionalidad' => '',
            'apellidos' => '',
            'nombres' => '',
            'razonsocial' => '',
            'direccion' => '',
            'telefono' => ''
        ];

        $datosReferenciasPersonales = [
            'nombres' => '',
            'parentezco' => '',
            'telefono' => '',
            'direccion' => ''
        ];

        $datosReferenciasComerciales = [
            'nombreestablecimiento' => '',
            'compraspromedio' => '',
            'telefono' => '',
            'ejecutivocuenta' => '',
            'direccion' => ''
        ];

        $datosReferenciasBancarias = [
            'institucionfinanciera' => '',
            'tipocuenta' => '',
            'numerocuenta' => '',
            'saldopromedio' => '',
            'cupo' => '',
            'fechaemision' => '',
            'fechavencimiento' => ''
        ];
        
        $arrayDatosDocumentos = array();
        
        if($vinculationUpdate->picture_document_applicant != null){
            $array = [
                'iddocumento' => '2',
                'fechaVigente' => date("d/m/Y", strtotime($vinculationUpdate->document_applicant_date)),
                'checkeado' => 'S'
            ];
            array_push($arrayDatosDocumentos, $array);
        }
        if($vinculationUpdate->picture_document_spouse != null){
            $array = [
                'iddocumento' => '7',
                'fechaVigente' => date("d/m/Y", strtotime($vinculationUpdate->document_spouse_date)),
                'checkeado' => 'S'
            ];
            array_push($arrayDatosDocumentos, $array);
        }
        if($vinculationUpdate->picture_service != null){
            $array = [
                'iddocumento' => '4',
                'fechaVigente' => date("d/m/Y"),
                'checkeado' => 'S'
            ];
            array_push($arrayDatosDocumentos, $array);
        }
        if($vinculationUpdate->picture_sri != null){
            $array = [
                'iddocumento' => '19',
                'fechaVigente' => date("d/m/Y"),
                'checkeado' => 'S'
            ];
            array_push($arrayDatosDocumentos, $array);
        }

        $json = [
            'cliente' => [
                'datoscliente' => $datosCliente,
                'datosconyugue' => $datosConyuge,
                'datosactividadeconomica' => $datosActividadEconomica,
                'datosrelacioncomercial' => $datosRelacionComercial,
                'datossituacionfinanciera' => [$datosSituacionFinanciera],
                'datosbeneficiarios' => [$datosBeneficiarios],
                'datosreferenciaspersonales' => [$datosReferenciasPersonales],
                'datosreferenciascomerciales' => [$datosReferenciasComerciales],
                'datosreferenciasbancarias' => [$datosReferenciasBancarias],
                'datosdocumentos' => $arrayDatosDocumentos
            ]
        ];
//        dd($json);
//        return json_encode($json, JSON_UNESCAPED_SLASHES);
        //Start Log
        $log = new \App\log_ss();
        $log->data_date_sent = new DateTime();
        $log->data_sent = json_encode($json);
        $log->save();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/pla/1.0/vinculacliente",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json, JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . tokenSS(),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        //Update Log
        $logUpdate = \App\log_ss::find($log->id);
        $logUpdate->data_received = $response;
        $logUpdate->data_date_received = new DateTime();
        $logUpdate->save();

        $data = json_decode($response, true);
        
        if($data['error'][0]['code'] == 000){
            //Update Vinculation Form
            $vin = App\vinculation_form::find($cliente[0]->id);
            $vin->alertas = $data['respuesta']['alertas'][0]['nomalerta'];
            $vin->lista_observado = $data['respuesta']['listaobservado'][0]['nomlista'];
            $vin->medidas_abreviadas = $data['respuesta']['medidasabreviadas'];
            $vin->observacion = $data['respuesta']['medidasabreviadas'];
            $vin->riesgo = $data['respuesta']['riesgo'];
            $vin->valor_asegurado = $data['respuesta']['valorasegurado'];
            $vin->valor_preventivo = $data['respuesta']['valorpreventivo'];
            $vin->id_formulario = $data['respuesta']['idformulario'];
            $vin->save();
            
            //CHECK RAMO
            $pro = \App\products::selectRaw('products.ramoid')
                                            ->join('products_channel as pbc','pbc.product_id','=','products.id')
                                            ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                            ->where('sal.id','=',$saleId)
                                            ->get();

            if($pro[0]->ramoid == 1 || $pro[0]->ramoid == 2 || $pro[0]->ramoid == 4){ //VIDA Y AP
                $sale = \App\sales::find($saleId);
                $sale->status_id = 29;
                $sale->save();
            }elseif($pro[0]->ramoid == 7){ // VEHICULOS
                $newVehicle = 'false';
                //See if Vehi are new
                $vehi = \App\vehicles::selectRaw('vehicles.new_vehicle')->join('vehicles_sales','vehicles_sales.vehicule_id','=','vehicles.id')->where('vehicles_sales.sales_id','=',$saleId)->get();
                foreach($vehi as $v){
                    if($v->new_vehicle == 1){
                        $newVehicle = 'true';
                    }
                }
                if($newVehicle == 'true'){
                    $sale = \App\sales::find($saleId); // NUEVO
                    $sale->status_id = 27;
                    $sale->save();
                }else{
                    $sale = \App\sales::find($saleId); // USADO
                    $sale->status_id = 22;
                    $sale->save();
                }
                //Check if the vehicle has other sales.
                $otherVehi = \App\vehicles::selectRaw('vehicles.plate')
                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
                                ->where('vsal.sales_id','=',$saleId)
                                ->get();
                foreach($otherVehi as $vehi){
                    $otherSale = \App\vehicles::selectRaw('vsal.sales_id')
                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
                                ->join('vehicles as vehi','vehi.id','=','vsal.vehicule_id')
                                ->where('vsal.sales_id','!=',$saleId)
                                ->where('vehi.plate','=',$vehi->plate)
                                ->get();
                    foreach($otherSale as $sal){
                        $sales = \App\sales::find($sal->sales_id);
                        $sales->status_id = 4;
                        $sales->save();
                    }
                }
            }else{ // INCENDIO
                $sale = \App\sales::find($saleId);
                $sale->status_id = 22;
                $sale->save();
            }
            
        }else{
            //Update Sales
            $sale = \App\sales::find($saleId);
            $sale->status_id = 31;
            $sale->save();

            \App\Jobs\webserviceEmailJobs::dispatch('Vincula Clientes - Seguros Sucre', json_encode($json, JSON_UNESCAPED_SLASHES), $data,'email');
        }

        return $data;
        
    } catch (Throwable $ex) { 
        //Update Sales
        $sale = \App\sales::find($saleId);
        $sale->status_id = 31;
        $sale->save();
       \App\Jobs\webserviceEmailMagnusJobs::dispatch('Vincula Clientes - Seguros Sucre', 'Error en la venta: '.$saleId.'<br>', $ex->getMessage().'<br>'.$ex->getFile().'<br>'.$ex->getLine().'<br>','email');
    }
}

function vinculaClienteLegalPersonSS($saleId) {
    try{
        $cliente = App\vinculation_form::selectRaw('vinculation_form.*,
                                                    DATE_FORMAT(vinculation_form.birth_date, "%d/%m/%Y") as "birthDate",
                                                    pro.ramoid, sal.insured_value,
                                                    sal.prima_total, act.sbs as "activity",
                                                    ocu.id as "ocupation",
                                                    sal.total as "salTotal", 
                                                    pbc.canal_plan_id,
                                                    cit2.name as "birthCity",
                                                    rep.phone, rep.email,
                                                    vinculation_form.id as "vinId",
                                                    cit.PROVINCIAID_SS as "provId", 
                                                    cit.CANTONID_SS as "citId",
                                                    rep.document, rep.first_name,
                                                    rep.document_id as "cusDocType",
                                                    IFNULL(rep.second_name, "") as "second_name", rep.last_name,
                                                    civilState.code_ss as "civilState",
                                                    chan.canalnegoid,
                                                    com.document as "companyDocument",
                                                    com.business_name as "companyBusinessName",
                                                    DATE_FORMAT(com.constitution_date, "%d/%m/%Y") as "companyConstitutionDate",
                                                    com.city_id as "companyCityId",
                                                    rep.second_last_name')                                                
                                    ->join('sales as sal','sal.id','=','vinculation_form.sales_id')
                                    ->join('products_channel as pbc','pbc.id','=','sal.pbc_id')
                                    ->join('channels as chan','chan.id','=','pbc.channel_id')
                                    ->join('products as pro','pro.id','=','pbc.product_id')
                                    ->leftJoin('customers as cus','cus.id','=','sal.customer_id')
                                    ->leftJoin('customer_legal_representative as rep','rep.id','=','sal.customer_legal_representative_id')
                                    ->leftJoin('companys as com','com.id','=','sal.company_id')
                                    ->leftJoin('cities as cit','cit.id','=','rep.city_id')
                                    ->join('civil_state as civilState','civilState.id','=','vinculation_form.civil_state')
                                    ->leftJoin('cities as cit2','cit2.id','=','vinculation_form.birth_place')
                                    ->leftJoin('economic_activity as act','act.id','=','vinculation_form.economic_activity_id')
                                    ->leftJoin('economic_ocupation as ocu','ocu.id','=','vinculation_form.occupation')
                                    ->where('vinculation_form.sales_id','=',$saleId)
                                    ->get();
        
        $birthCity = \App\cities_ss::where('CIUDADNOM','like','%'.TRIM($cliente[0]->birthCity).'%')->get();
        if($birthCity->isEmpty()){
            $birthCityFinal = TRIM($cliente[0]->birthCity);
            $birthCity2 = App\city::where('id','=',$cliente[0]->birth_place)->get();
            $bithProvince = App\province::where('id','=',$birthCity2[0]->province_id)->get();
            $birthCountry = App\country::where('id','=',$bithProvince[0]->country_id)->get();
        }else{
            $birthCityFinal = $birthCity[0]->CIUDADID;
            $birthCity2 = App\cities_ss::where('id','=',$cliente[0]->birth_place)->get();
            $bithProvince = App\province::where('id','=',$birthCity2[0]->CIUDADPROVINID)->get();
            $birthCountry = App\country::where('id','=',$bithProvince[0]->country_id)->get();
        }
        $fromDate = contratanteSS($cliente[0]->document);
        if($fromDate['infocontratante']['clientedesde'] == ''){
            $fromDate2 = date('d/m/Y');
        }else{
            $fromDate2 = $fromDate['infocontratante']['clientedesde'];
        }
        $vinculationUpdate = App\vinculation_form::find($cliente[0]->vinId);
        $vinculationUpdate->from_date = $fromDate2;
        $vinculationUpdate->save();
        
        if($birthCountry->isEmpty()){
            $country = 'EC';
        }else{
            $country = $birthCountry[0]->code_ss;
        }
        if($cliente[0]->cusDocType == '1'){
            $docType = 'C';
        }else{
            $docType = 'P';
        }
        
        if($cliente[0]->canalnegoid == 1){
            $vinculation = 1;
        }else{
            $vinculation = 6;
        }
        
        $datosCliente = [
            'canalplanid' => $cliente[0]->canal_plan_id,
            'identificacion' => $cliente[0]->companyDocument,
            'tipoidentificacion' => 'R',
            'tipocliente' => 'J',
            'tiposector' => 'PB',
            'nombrerazonsocial' => $cliente[0]->companyBusinessName,
            'replegaltipoid' => $docType,
            'repprimernombre' => $cliente[0]->first_name,
            'repprimerapellido' => $cliente[0]->last_name,
            'repLegalid' => $cliente[0]->document,
            'primerape' => $cliente[0]->last_name,
            'segundoape' => $cliente[0]->second_last_name,
            'primernombre' => $cliente[0]->first_name,
            'segundonombre' => $cliente[0]->second_name,
            'fechaconstitucion' => $cliente[0]->companyConstitutionDate,
            'paisconstitucion' => 'EC',
            'paisresidencia' => 'EC',
            'provincia' => $cliente[0]->provId,
            'canton' => $cliente[0]->citId,
            'telefono' => $cliente[0]->phone,
            'email' => $cliente[0]->email,
            'estadocivil' => $cliente[0]->civilState,
            'sexo' => 'M',
            'clientedesde' => $fromDate2,
            'vinculacion' => $vinculation,
            'terceraedadcapespecial' => 'N',
            'fechanacimiento' => $cliente[0]->birthDate,
            'ciudadnacimiento' => $birthCityFinal,
            'residenciadireccion' => $cliente[0]->address_zone,
            'residenciacalleprincipal' => $cliente[0]->main_road,
            'residenciacalletransversal' => $cliente[0]->secondary_road,
            'residencialnumero' => $cliente[0]->address_number,
            'ingresos' => str_replace(',','',$cliente[0]->annual_income),
            'origen' => '2'
        ];

        if($cliente[0]->civil_state == 2){
            if($cliente[0]->spouse_document_id == '1'){
                $docType = 'C';
            }else{
                $docType = 'P';
            }
            $datosConyuge = [
                'tipoidentificacion' => $docType,
                'identificacion' => $cliente[0]->spouse_document,
                'lugarnacimiento' => '',
                'fechanacimiento' => '',
                'apellidos' => $cliente[0]->spouse_name,
                'nombres' => $cliente[0]->spouse_last_name,
                'idnacionalidad' => '',
                'actividadeconomicaid' => '',
                'ingresomensual' => '',
                'nombrempresa' => '',
                'telefono' => '',
                'direccion' => '',
                'email' => ''
            ];
        }else{
            $datosConyuge = [
                'tipoidentificacion' => '',
                'identificacion' => '',
                'lugarnacimiento' => '',
                'fechanacimiento' => '',
                'apellidos' => '',
                'nombres' => '',
                'idnacionalidad' => '',
                'actividadeconomicaid' => '',
                'ingresomensual' => '',
                'nombrempresa' => '',
                'telefono' => '',
                'direccion' => '',
                'email' => ''
            ];
        }

        $datosActividadEconomica = [
            'relaciondependencia' => '',
            'actividadeconomica' => $cliente[0]->activity,
            'nombrempresa' => '',
            'cargo' => '',
            'ingresomensual' => '',
            'pep' => '',
            'calleprincipal' => '',
            'callenumero' => '',
            'calletransversal' => '',
            'sector' => '',
            'actividadempresa' => '',
            'mail' => '',
            'telefono' => '',
            'fax' => ''
        ];

        $datosRelacionComercial = [
            'ramo' => $cliente[0]->ramoid,
            'tipo' => 'N',
            'cantidadpolizas' => '1',
            'valorasegurado' => str_replace(',','',$cliente[0]->insured_value),
            'primatotal' => str_replace(',','',$cliente[0]->salTotal)
        ];
        if($cliente[0]->total_assets_pasives == null){
            $totalActivosPasivos = '0.00';
        }else{
            $totalActivosPasivos = str_replace(',','',$cliente[0]->total_assets_pasives);
        }
        $datosSituacionFinanciera = [
            'otrosingresos' => str_replace(',','',$cliente[0]->other_annual_income),
            'descripcionotros' => str_replace(',','',$cliente[0]->description_other_income),
            'totalactivopasivo' => str_replace(',','',$totalActivosPasivos)
        ];

        $datosBeneficiarios = [
            'tipo' => '',
            'tiporelacion' => '',
            'especificarotros' => '',
            'tipoidentificacion' => '',
            'identificacion' => '',
            'sexo' => '',
            'nacionalidad' => '',
            'apellidos' => '',
            'nombres' => '',
            'razonsocial' => '',
            'direccion' => '',
            'telefono' => ''
        ];

        $datosReferenciasPersonales = [
            'nombres' => '',
            'parentezco' => '',
            'telefono' => '',
            'direccion' => ''
        ];

        $datosReferenciasComerciales = [
            'nombreestablecimiento' => '',
            'compraspromedio' => '',
            'telefono' => '',
            'ejecutivocuenta' => '',
            'direccion' => ''
        ];

        $datosReferenciasBancarias = [
            'institucionfinanciera' => '',
            'tipocuenta' => '',
            'numerocuenta' => '',
            'saldopromedio' => '',
            'cupo' => '',
            'fechaemision' => '',
            'fechavencimiento' => ''
        ];
        
        $arrayDatosDocumentos = array();
        
        if($vinculationUpdate->picture_document_applicant != null){
            $array = [
                'iddocumento' => '2',
                'fechaVigente' => date("d/m/Y", strtotime($vinculationUpdate->document_applicant_date)),
                'checkeado' => 'S'
            ];
            array_push($arrayDatosDocumentos, $array);
        }
        if($vinculationUpdate->picture_document_spouse != null){
            $array = [
                'iddocumento' => '7',
                'fechaVigente' => date("d/m/Y", strtotime($vinculationUpdate->document_spouse_date)),
                'checkeado' => 'S'
            ];
            array_push($arrayDatosDocumentos, $array);
        }
        if($vinculationUpdate->picture_service != null){
            $array = [
                'iddocumento' => '4',
                'fechaVigente' => date("d/m/Y"),
                'checkeado' => 'S'
            ];
            array_push($arrayDatosDocumentos, $array);
        }
        if($vinculationUpdate->picture_sri != null){
            $array = [
                'iddocumento' => '19',
                'fechaVigente' => date("d/m/Y"),
                'checkeado' => 'S'
            ];
            array_push($arrayDatosDocumentos, $array);
        }

        $json = [
            'cliente' => [
                'datoscliente' => $datosCliente,
                'datosconyugue' => $datosConyuge,
                'datosactividadeconomica' => $datosActividadEconomica,
                'datosrelacioncomercial' => $datosRelacionComercial,
                'datossituacionfinanciera' => [$datosSituacionFinanciera],
                'datosbeneficiarios' => [$datosBeneficiarios],
                'datosreferenciaspersonales' => [$datosReferenciasPersonales],
                'datosreferenciascomerciales' => [$datosReferenciasComerciales],
                'datosreferenciasbancarias' => [$datosReferenciasBancarias],
                'datosdocumentos' => $arrayDatosDocumentos
            ]
        ];
//        dd($json);
//        return json_encode($json, JSON_UNESCAPED_SLASHES);
        //Start Log
        $log = new \App\log_ss();
        $log->data_date_sent = new DateTime();
        $log->data_sent = json_encode($json);
        $log->save();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/pla/1.0/vinculacliente",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json, JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . tokenSS(),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        //Update Log
        $logUpdate = \App\log_ss::find($log->id);
        $logUpdate->data_received = $response;
        $logUpdate->data_date_received = new DateTime();
        $logUpdate->save();

        $data = json_decode($response, true);
        
        if($data['error'][0]['code'] == 000){
            //Update Vinculation Form
            $vin = App\vinculation_form::find($cliente[0]->id);
            $vin->alertas = $data['respuesta']['alertas'][0]['nomalerta'];
            $vin->lista_observado = $data['respuesta']['listaobservado'][0]['nomlista'];
            $vin->medidas_abreviadas = $data['respuesta']['medidasabreviadas'];
            $vin->observacion = $data['respuesta']['medidasabreviadas'];
            $vin->riesgo = $data['respuesta']['riesgo'];
            $vin->valor_asegurado = $data['respuesta']['valorasegurado'];
            $vin->valor_preventivo = $data['respuesta']['valorpreventivo'];
            $vin->id_formulario = $data['respuesta']['idformulario'];
            $vin->save();
            
            //CHECK RAMO
            $pro = \App\products::selectRaw('products.ramoid')
                                            ->join('products_channel as pbc','pbc.product_id','=','products.id')
                                            ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                            ->where('sal.id','=',$saleId)
                                            ->get();

            if($pro[0]->ramoid == 1 || $pro[0]->ramoid == 2 || $pro[0]->ramoid == 4){ //VIDA Y AP
                $sale = \App\sales::find($saleId);
                $sale->status_id = 29;
                $sale->save();
            }elseif($pro[0]->ramoid == 7){ // VEHICULOS
                $newVehicle = 'false';
                //See if Vehi are new
                $vehi = \App\vehicles::selectRaw('vehicles.new_vehicle')->join('vehicles_sales','vehicles_sales.vehicule_id','=','vehicles.id')->where('vehicles_sales.sales_id','=',$saleId)->get();
                foreach($vehi as $v){
                    if($v->new_vehicle == 1){
                        $newVehicle = 'true';
                    }
                }
                if($newVehicle == 'true'){
                    $sale = \App\sales::find($saleId); // NUEVO
                    $sale->status_id = 27;
                    $sale->save();
                }else{
                    $sale = \App\sales::find($saleId); // USADO
                    $sale->status_id = 22;
                    $sale->save();
                }
                //Check if the vehicle has other sales.
                $otherVehi = \App\vehicles::selectRaw('vehicles.plate')
                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
                                ->where('vsal.sales_id','=',$saleId)
                                ->get();
                foreach($otherVehi as $vehi){
                    $otherSale = \App\vehicles::selectRaw('vsal.sales_id')
                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
                                ->join('vehicles as vehi','vehi.id','=','vsal.vehicule_id')
                                ->where('vsal.sales_id','!=',$saleId)
                                ->where('vehi.plate','=',$vehi->plate)
                                ->get();
                    foreach($otherSale as $sal){
                        $sales = \App\sales::find($sal->sales_id);
                        $sales->status_id = 4;
                        $sales->save();
                    }
                }
            }else{ // INCENDIO
                $sale = \App\sales::find($saleId);
                $sale->status_id = 22;
                $sale->save();
            }
            
        }else{
            //Update Sales
            $sale = \App\sales::find($saleId);
            $sale->status_id = 31;
            $sale->save();

            \App\Jobs\webserviceEmailJobs::dispatch('Vincula Clientes - Seguros Sucre', json_encode($json, JSON_UNESCAPED_SLASHES), $data,'email');
        }

        return $data;
        
    } catch (Throwable $ex) { 
        //Update Sales
        $sale = \App\sales::find($saleId);
        $sale->status_id = 31;
        $sale->save();
       \App\Jobs\webserviceEmailMagnusJobs::dispatch('Vincula Clientes - Seguros Sucre', 'Error en la venta: '.$saleId.'<br>', $ex->getMessage().'<br>'.$ex->getFile().'<br>'.$ex->getLine().'<br>','email');
    }
}

function emisionSS($saleId){
    try {
        $infoCustomer = App\sales::selectRaw('DISTINCT(sales.id) as "salId",
                                                CONCAT(cus.last_name," ",cus.second_last_name) as "lastName",
                                                CONCAT(cus.first_name," ", IFNULL(cus.second_name,"") ) as "firstName",
                                                IF(cus.gender_id = 1, "M","F") as "gender",
                                                civilState.code_ss as "civilState",
                                                cus.document as "cusDocument",
                                                DATE_FORMAT(cus.birthdate, "%d/%m/%Y") as "birthDate",
                                                cus.email as "email",
                                                cus.phone as "phone",
                                                cus.mobile_phone as "mobilePhone",
                                                eco.sbs as "ecoActivity",
                                                cus.address as "address",
                                                prov.id as "provinceId",
                                                cit.CANTONID_SS as "cantonId",
                                                cit2.name as "birthPlace",
                                                vin.annual_income as "annualIncome",
                                                vin.occupation as "occupation",
                                                IF(vin.person_exposed = "NO", "N","S") as "pep",
                                                cou.code_na_ss as "nationality",
                                                cou.code_ss as "paisResidencia",
                                                pro.canalplanid as "canalPlanId",
                                                DATE_FORMAT(sales.begin_date, "%d-%m-%Y") as "beginDate",
                                                DATE_FORMAT(sales.end_date, "%d-%m-%Y") as "endDate",
                                                DATE_FORMAT(pay.date, "%d-%m-%Y") as "paymentDate",
                                                pro.ramoid as "ramoId",
                                                sales.insured_value "insuredValue",
                                                sales.prima_total as "primaValue",
                                                sales.super_bancos as "superCompanias",
                                                sales.seguro_campesino as "segCampesino",
                                                sales.derecho_emision as "derechoEmision",
                                                sales.tax as "iva",
                                                sales.rate as "rate",
                                                vin.id as "vin",
                                                sales.total as "total",
                                                prov.id as "parroquiaId"')
                                            ->leftJoin('customers as cus','cus.id','=','sales.customer_id')
                                            ->leftJoin('vinculation_form as vin','vin.sales_id','=','sales.id')
                                            ->leftJoin('civil_state as civilState','civilState.id','=','vin.civil_state')
                                            ->leftJoin('economic_activity as eco','eco.id','=','vin.economic_activity_id')
                                            ->leftJoin('cities as cit','cit.id','=','vin.city_id')
                                            ->leftJoin('cities as cit2','cit2.id','=','vin.birth_place')
                                            ->leftJoin('provinces as prov','prov.id','=','cit.province_id')
                                            ->leftJoin('countries as cou','cou.id','=','vin.nationality_id')
                                            ->leftJoin('products_channel as pbc','pbc.id','=','sales.pbc_id')
                                            ->leftJoin('products as pro','pro.id','=','pbc.product_id')
                                            ->leftJoin('charges as char','char.sales_id','=','sales.id')
                                            ->leftJoin('payments as pay','pay.id','=','char.payments_id')
                                            ->where('sales.id','=',$saleId)
                                            ->get();

        $parroquiaCustomer = App\parroquia::where('PROVINCIAID','=',$infoCustomer[0]->provinceId)->where('CANTONID','=',$infoCustomer[0]->cantonId)->get();

        //Valor Asegurado Incendio
        if($infoCustomer[0]->ramoId == 5 || $infoCustomer[0]->ramoId == 40){
            $rubros = App\properties_rubros::selectRaw('DISTINCT(pro.id), properties_rubros.value, properties_rubros.rubros_cod, properties_rubros.rate, properties_rubros.prima_value, rub.description')
                                                ->join('properties as pro','pro.id','=','properties_rubros.property_id')
                                                ->join('products_rubros as rub','rub.cod','=','properties_rubros.rubros_cod')
                                                ->where('pro.sales_id','=',$saleId)
                                                ->get();
            $valorAsegurado = 0;
            foreach($rubros as $rub){
                $valorAsegurado += $rub->value;
            };
        }else{
            $valorAsegurado = $infoCustomer[0]->insuredValue;
        }
        
        $infoContratante = [
            'tipopersona' => 'N',
            'tipoidentificacion' => 'C',
            'identificacion' => $infoCustomer[0]->cusDocument, 
            'apellidos' => $infoCustomer[0]->lastName, 
            'nombres' => $infoCustomer[0]->firstName,
            'sexo' => $infoCustomer[0]->gender,
            'estadocivil' => $infoCustomer[0]->civilState,
            'fechanacimiento' => $infoCustomer[0]->birthDate,
            'email1' => $infoCustomer[0]->email,
            'telefonoconvencional' => $infoCustomer[0]->phone,
            'celular' => $infoCustomer[0]->mobilePhone,
            'acteconomica' => $infoCustomer[0]->ecoActivity,
            'direccion' => $infoCustomer[0]->address,
            'provinciaid' => $infoCustomer[0]->provinceId, 
            'cantonid' => $infoCustomer[0]->cantonId,
            'lugarnacimiento' => $infoCustomer[0]->birthPlace,
            'ingresoanual' => $infoCustomer[0]->annualIncome, 
            'pep' => $infoCustomer[0]->pep,
            'nacionalidad' => $infoCustomer[0]->nationality,
            'paisresidencia' => $infoCustomer[0]->paisResidencia,
            'parroquiaid' => $parroquiaCustomer[0]->PARROQUIAID
        ];

        $infoCertificado = [
                'canalplanID' => $infoCustomer[0]->canalPlanId,
                'numeropoliza' => '',
                'certificado' => '',
                'anexo' => '',
                'codigooperacion' => $infoCustomer[0]->salId,
                'fchinivig' => $infoCustomer[0]->beginDate,
                'fchfinvig' => $infoCustomer[0]->endDate,
                'fchfactura' => $infoCustomer[0]->paymentDate,
                'ramoid' => $infoCustomer[0]->ramoId,
                'tipoanxid' => 'POLI',
                'codtransaccion' => $infoCustomer[0]->salId
        ];

        $infoCostoSeguro = [
            'numdocumento' => '',
            'valorasegurado' => str_replace(',','',$valorAsegurado),
            'primaneta' => str_replace(',','',$infoCustomer[0]->primaValue),
            'supercompanias' => $infoCustomer[0]->superCompanias,
            'segcampesino' => $infoCustomer[0]->segCampesino,
            'derechoemision' => $infoCustomer[0]->derechoEmision,
            'iva' => $infoCustomer[0]->iva,
            'interes' => '0.00',
            'total' => str_replace(',','',$infoCustomer[0]->total)
        ];

        $infoFinanciamiento = [
            'tipopago' => '1',
            'formapago' => '19',
            'cuotaincial' => '0',
            'valorcuota' => '0',
            'numcuota' => '0'
        ];

        $infoSegVida = [

        ];

        $infoGenerales = [

        ];

        $infoVehiculos = [

        ];
        //FIND RAMO//
        //VEHICULOS//
        if($infoCustomer[0]->ramoId == 7) {
            $vehi = App\vehicles::selectRaw('vehicles.*, vehicles_sales.id as "vsalId", vehicles_brands.name, vehicles_sales.insured_value, vehicles_sales.rate, vehicles_sales.prima_value, vehicles_sales.indprifija')
                    ->join('vehicles_sales', 'vehicles_sales.vehicule_id', '=', 'vehicles.id')
                    ->join('vehicles_brands', 'vehicles_brands.id', '=', 'vehicles.brand_id')
                    ->leftJoin('vehicles_accesories', 'vehicles_accesories.vehicles_sales_id', '=', 'vehicles_sales.id')
                    ->where('vehicles_sales.sales_id', '=', $infoCustomer[0]->salId)
                    ->get();
            $infoVehiculosArray = [];
            foreach ($vehi as $v) {
                $vehiAcc = App\sales::selectRaw('vacc.name, vacc.value, vacc.rate')
                        ->join('vehicles_sales as vsal', 'vsal.sales_id', '=', 'sales.id')
                        ->join('vehicles_accesories as vacc', 'vacc.vehicles_sales_id', '=', 'vsal.id')
                        ->where('vsal.id', '=', $v->vsalId)
                        ->get();
                
                $infoAccArray = [];
                if (!$vehiAcc->isEmpty()) {
                    foreach ($vehiAcc as $acc) {
                        $array = [
                            'accesorio' => $acc->name,
                            'monto' => str_replace(',','',$acc->value),
                            'tasa' => $acc->rate,
                            'primaneta' => '0.00',
                            'sumamonto' => 'S'
                        ];
                        array_push($infoAccArray, $array);
                    }
                }
                $vehiType = \App\vehicles_type::find($v->vehicles_type_id);
                
                $vehiClass = App\vehicles_class::find($v->vehicles_class_id);

                $vehiCountryCode = App\country::where('id', '=', $v->pais)->get();
                if ($vehiCountryCode->isEmpty()) {
                    $vehiCountry = 'EC';
                } else {
                    $vehiCountry = $vehiCountryCode[0]->code_ss;
                }
                
                $infoVehiculos = [
                    'infoasegurado' => [
                        'tipoidentificacion' => 'C',
                        'identificacion' => $infoCustomer[0]->cusDocument, 
                        'apellidos' => $infoCustomer[0]->lastName, 
                        'nombres' => $infoCustomer[0]->firstName,
                        'sexo' => $infoCustomer[0]->gender,
                        'estadocivil' => $infoCustomer[0]->civilState,
                        'fechanacimiento' => $infoCustomer[0]->birthDate,
                        'email1' => $infoCustomer[0]->email,
                        'telefonoconvencional' => $infoCustomer[0]->phone,
                        'celular' => $infoCustomer[0]->mobilePhone,
                        'direccion' => $infoCustomer[0]->address,
                        'provinciaid' => $infoCustomer[0]->provinceId, 
                        'cantonid' => $infoCustomer[0]->cantonId,
                        'parroquiaid' => $parroquiaCustomer[0]->PARROQUIAID
                    ],
                    'infoitem' => [
                        'codoperacion' => $v->chassis,
                        'tipoidenvh' => '1',
                        'identificacionvh' => $v->plate,
                        'anio' => $v->year,
                        'marca' => $v->name,
                        'modelo' => $v->model,
                        'color' => $v->color,
                        'tipovh' => $vehiClass->name,
                        'numpas' => $v->capacidad,
                        'cilindraje' => $v->cilindraje,
                        'motor' => $v->matricula,
                        'chasis' => $v->chassis,
                        'tonelaje' => $v->tonelaje,
                        'dispseguridad' => '',
                        'usovh' => $vehiType->full_name,
                        'paisorigen' => $vehiCountry
                    ],
                    'infocostoseguro' => [
                        'valorasegurado' => str_replace(',','',$v->insured_value),
                        'tasa' => $v->rate,
                        'primaneta' => str_replace(',','',$v->prima_value),
                        'indprifija' => $v->indprifija
                    ],
                   'infoaccesorios' => $infoAccArray,
                   'infoatributos' => []
                ];
                array_push($infoVehiculosArray, $infoVehiculos);
            }
            

            $infoBienAsegurado = [
                'infosegvida' => [],
                'infogenerales' => [],
                'infovehiculos' => $infoVehiculosArray

            ];
            $infoBenefAcreedores = [];
            $endoso = \App\sales::find($saleId);
            if($endoso->endorsement_document == NULL){
            }else{
                $infoBenefAcreedores = [
                    [
                        'tipoidentificacion' => 'R',
                        'identificacion' => $endoso->endorsement_document,
                        'valorendoso' => str_replace(',','',$endoso->amount_endorsement),
                        'itemcod' => $vehi[0]->chassis
                    ]
                ];
                
            }

        }   
        // VIDA INDIVIDUAL Y EN GRUPO// //AP//
        if($infoCustomer[0]->ramoId == 1 || $infoCustomer[0]->ramoId == 2 || $infoCustomer[0]->ramoId == 4){
            $bene = App\beneficiary::where('sales_id','=',$saleId)->get();
            $beneArray = [];
            foreach($bene as $b){
                $array = [
                    'apellidos' => $b->last_name.' '.$b->second_last_name,
                    'nombres' => $b->first_name.' '.$b->second_name,
                    'parentezco' => $b->relationship_id,
                    'participacion' => $b->porcentage
                ];
                array_push($beneArray, $array);
            };
            $infoSegVida = [
                'infoitem' => [
                    'codoperacion' => $infoCustomer[0]->cusDocument,
                    'tipoidentificacion' => 'C',
                    'identificacion' => $infoCustomer[0]->cusDocument, 
                    'apellidos' => $infoCustomer[0]->lastName, 
                    'nombres' => $infoCustomer[0]->firstName,
                    'sexo' => $infoCustomer[0]->gender,
                    'estadocivil' => $infoCustomer[0]->civilState,
                    'fechanacimiento' => $infoCustomer[0]->birthDate,
                    'email1' => $infoCustomer[0]->email,
                    'telefonoconvencional' => $infoCustomer[0]->phone,
                    'celular' => $infoCustomer[0]->mobilePhone,
                    'direccion' => $infoCustomer[0]->address,
                    'provinciaid' => $infoCustomer[0]->provinceId, 
                    'cantonid' => $infoCustomer[0]->cantonId,
                    'tipoocupacion' => $infoCustomer[0]->occupation,
                    'parroquiaid' => $parroquiaCustomer[0]->PARROQUIAID
                ],
                'infocostoseguro' => [
                    'valorasegurado' => str_replace(',','',$infoCustomer[0]->insuredValue),
                    'tasa' => $infoCustomer[0]->rate,
                    'primaneta' => str_replace(',','',$infoCustomer[0]->primaValue)
                ],
                'infobenef' => $beneArray,
               'infoatributos' => [],
                'infodepen' => []
            ];
            $infoBienAsegurado = [
                'infosegvida' => [$infoSegVida],
                'infogenerales' => [],
                'infovehiculos' => []

            ];
            
            $infoBenefAcreedores = [];
        }
        // INCENDIO Y LINEAS ALIADAS //
        if($infoCustomer[0]->ramoId == 5 || $infoCustomer[0]->ramoId == 40){
            $properties = App\properties::selectRaw('CONCAT(properties.main_street," ",properties.secondary_street," ",properties.number) as "ubicacion",
                                                    prov.name as "provincia",
                                                    cit.CANTONID_SS as "ciudad",
                                                    properties.id as "propertiesId",
                                                    prov.id as "provincia"')
                                        ->join('cities as cit','cit.id','=','properties.city_id')           
                                        ->join('provinces as prov','prov.id','=', 'cit.province_id')   
                                        ->join('properties_rubros as proRub','proRub.property_id','=','properties.id')
                                        ->where('properties.sales_id','=',$saleId)
                                        ->get();

            if($properties[0]->provincia == 'GUAYAS'){
                $zonaRiesgo = 1;
            }else if($properties[0]->provincia == 'PICHINCHA'){
                $zonaRiesgo = 2;
            }else{
                $zonaRiesgo = 3;
            }

            $rubros = App\properties_rubros::selectRaw('DISTINCT(pro.id), properties_rubros.value, properties_rubros.rubros_cod, properties_rubros.rate, properties_rubros.prima_value, rub.description')
                                            ->join('properties as pro','pro.id','=','properties_rubros.property_id')
                                            ->join('products_rubros as rub','rub.cod','=','properties_rubros.rubros_cod')
                                            ->where('pro.sales_id','=',$saleId)
                                            ->get();

            $rubrosArray = [];
            $valorAsegurado = 0;
            $valorPrima = 0;
            foreach($rubros as $rub){
                $valorAsegurado += $rub->value;
                $valorPrima += $rub->prima_value;
                $array = [
                    'descriptivo' => $rub->rubros_cod,
                    'valorasegurado' => str_replace(',','',$rub->value),
                    'tasa' => $rub->rate,
                    'primaneta' => str_replace(',','',$rub->prima_value)
                ];
                array_push($rubrosArray, $array);
            };


            $infoGenerales = [
                'infoasegurado' => [
                    'tipoidentificacion' => 'C',
                    'identificacion' => $infoCustomer[0]->cusDocument, 
                    'apellidos' => $infoCustomer[0]->lastName, 
                    'nombres' => $infoCustomer[0]->firstName,
                    'sexo' => $infoCustomer[0]->gender,
                    'estadocivil' => $infoCustomer[0]->civilState,
                    'fechanacimiento' => $infoCustomer[0]->birthDate,
                    'email1' => $infoCustomer[0]->email,
                    'telefonoconvencional' => $infoCustomer[0]->phone,
                    'celular' => $infoCustomer[0]->mobilePhone,
                    'direccion' => $infoCustomer[0]->address,
                    'provinciaid' => $infoCustomer[0]->provinceId, 
                    'cantonid' => $infoCustomer[0]->cantonId,
                    'parroquiaid' => $parroquiaCustomer[0]->PARROQUIAID
                ],
                'infoitem' => [
                    'codoperacion' => $properties[0]->propertiesId,
                    'ubicacion' => $properties[0]->ubicacion,
                    'categoriaconstruccion' => '1',
                    'gironegocio' => '1332',
                    'zonariesgo' => $zonaRiesgo,
                    'provincia' => $properties[0]->provincia,
                    'canton' => $properties[0]->ciudad
                ],
                'infocostoseguro' => [
                    'valorasegurado' => str_replace(',','',$valorAsegurado),
                    'tasa' => $rubros[0]->rate,
                    'primaneta' => str_replace(',','',$valorPrima)
                ],
                'infodescriptivos' => $rubrosArray,
                'infoatributos' => []
            ];
            $infoBienAsegurado = [
                'infosegvida' => [],
                'infogenerales' => [$infoGenerales],
                'infovehiculos' => []

            ];
            $infoBenefAcreedores = [];
            $endoso = \App\sales::find($saleId);
            if($endoso->endorsement_document == NULL){
            }else{
                $infoBenefAcreedores = [
                    [
                        'tipoidentificacion' => 'R',
                        'identificacion' => $endoso->endorsement_document,
                        'valorendoso' => str_replace(',','',$endoso->amount_endorsement),
                        'itemcod' => $properties[0]->propertiesId
                    ]
                ];
                
            }
        }
        $json = [
            'certificados' => [
                [
                'infocontratante' => $infoContratante,
                'infocertificado' => $infoCertificado,
                'infocostoseguro' => $infoCostoSeguro,
                'infofinanciamiento' => $infoFinanciamiento,
                'infobienasegurado' => $infoBienAsegurado,
                'infoatributos' => [],
                'infobenefacreedores' => $infoBenefAcreedores

                ]
            ]
        ];  
    //    return tokenSS();
//        $json_string = json_encode($json, JSON_UNESCAPED_SLASHES);
//        return($json_string);
        //Start Log
        $log = new \App\log_ss();
        $log->data_sent = json_encode($json);
        $log->data_date_sent = new DateTime();
        $log->save();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/emisioncertificado",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json,JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . tokenSS(),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        //dd($response);
        curl_close($curl);

        //Update Log
        $logUpdate = \App\log_ss::find($log->id);
        $logUpdate->data_received = $response;
        $logUpdate->data_date_received = new DateTime();
        $logUpdate->save();

        $data = json_decode($response, true);
        
        if(isset($data['error']['code'])){
            \App\Jobs\webserviceEmailJobs::dispatch('Emision - Seguros Sucre', json_encode($json,JSON_UNESCAPED_SLASHES), $data);
            //Update Data
            $sales = \App\sales::find($saleId);
            $sales->status_id = 32;
            $sales->save();
        }else{
            if($data['respuesta']['code'] == 000){
                //Update Data
                $sales = \App\sales::find($saleId);
                $sales->certificado = $data['respuesta']['respuesta'][0]['certificadoid'];
                $sales->save();
            }else{
                \App\Jobs\webserviceEmailJobs::dispatch('Emision - Seguros Sucre', json_encode($json,JSON_UNESCAPED_SLASHES), $data);
                //Update Data
                $sales = \App\sales::find($saleId);
                $sales->status_id = 32;
                $sales->save();
            }
        }

        return $data;
        
    } catch (Exception $ex) {
        //Update Data
        $sales = \App\sales::find($saleId);
        $sales->status_id = 32;
        $sales->save();
        \App\Jobs\webserviceEmailMagnusJobs::dispatch('Emision - Seguros Sucre', 'error', $ex->getMessage(),'email');

    }
}

function productChannelSS() {
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/productocanal",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"codigoapp\": \"1\"\n}",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
   
    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
        
    return $data;
}

function agenteSS() {
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->save();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/agentes",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"codigoapp\": \"1\"\n}",
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    
    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
    
    return $data;
}

function carteraVencidaSS($document) {
    $data = [
        'codigoapp' => "1",
        'identificacion' => "$document"
    ];
    
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();

    $curl = curl_init();
    
    $json = json_encode($data);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/carteravencida",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    
    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
    
    if($data['error'][0]['code'] == '003' || $data['error'][0]['code'] == '000'){
    }else{
        \App\Jobs\webserviceEmailJobs::dispatch('Cartera Vencida - Seguros Sucre', $json, $data,'email');
    }
    
    return $data;
}

function listaObservadosSS($canalPlanId, $cusType, $firstName, $secondName, $lastName, $secondLastName, $docType, $document, $socialReason = null) {
    
    $secondName = ($secondName==null)?"": $secondName;
    $socialReason = ($socialReason==null)?"": $socialReason;
    
    $data = [
        'cliente' => [
            'canalplanid' => $canalPlanId,
            'tipocliente' => $cusType,
            'primernombre' => $firstName,
            'segundonombre' => $secondName,
            'primerapellido' => $lastName,
            'segundoapellido' => $secondLastName,
            'razonsocial' => $socialReason,
            'tipoidentificacion' => $docType,
            'identificacion' => $document
        ]
    ];

    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();
    $curl = curl_init();
    
    $json = json_encode($data);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/pla/1.0/listasobservados",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    
    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
    
    if($data['error'][0]['code'] == '003' || $data['error'][0]['code'] == '000'){
    }else{
        \App\Jobs\webserviceEmailJobs::dispatch('Cartera Vencida - Seguros Sucre', $json, $data,'email');
    }
    
    return $data;
}


function tarifarioVehiculoSS() {
    $data = [
        'canalplanid' =>  '1'
    ];
    
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/tarifariovh",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    
    //Update Log
//    $logUpdate = \App\log_ss::find($log->id);
//    $logUpdate->data_received = $response;
//    $logUpdate->data_date_received = new DateTime();
//    $logUpdate->save();
    
    $data = json_decode($response, true);
    
    return $data;
}

function montoAseguradoSS($document) {
    $data = [
        'identificacion' =>  $document
    ];
    
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/montoasegurado",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    
    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
    
    return $data;
}

function calculoPrimaSS($canalPlanId, $beginDate, $endDate, $agencyId, $vehiType, $assuredValue) {
    $data = [
        'cabecera' => [
            'canalplanid' => $canalPlanId,
            'tipoanexoid' => 'POLI',
            'fchinicio' => $beginDate,
            'fchfin' => $endDate,
            'detalle' => [
                'agenciaid' => $agencyId,
                'tipovehiculo' => $vehiType,
                'valorasegurado' => str_replace(',','',$assuredValue)
            ]
        ]
    ];
//    dd($data);
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/calculoprimas",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
//dd($response);
    curl_close($curl);

    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
//    dd($data);
    return $data;
}

function calculoPrimaR2($canalPlanId, $beginDate, $endDate) {
    $data = [
        'cabecera' => [
            'canalplanid' => $canalPlanId,
            'tipoanexoid' => 'POLI',
            'fchinicio' => $beginDate,
            'fchfin' => $endDate
        ]
    ];
//    return json_encode($data, JSON_UNESCAPED_SLASHES);
//    Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/calculoprimasvida",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_SLASHES),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    
    curl_close($curl);

    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
    return $data;
}

function calculoPrimaR4($canalPlanId, $rubro_cod, $assuredValue, $beginDate, $endDate) {
    $data = [
        'cabecera' => [
            'canalplanid' => $canalPlanId,
            'tipoanexoid' => 'POLI',
            'codigorubro' => $rubro_cod,
            'valorasegurado' => str_replace(',','',$assuredValue),
            'fchinicio' => $beginDate,
            'fchfin' => $endDate
        ]
    ];
//    return \GuzzleHttp\json_encode($data);
    //Start Log
    $log = new \App\log_ss();
    $log->data_date_sent = new DateTime();
    $log->data_sent = json_encode($data);
    $log->save();
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.segurossucre.fin.ec:9988/integrador/1.0/calculoprimasincendio",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . tokenSS(),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    //Update Log
    $logUpdate = \App\log_ss::find($log->id);
    $logUpdate->data_received = $response;
    $logUpdate->data_date_received = new DateTime();
    $logUpdate->save();
    
    $data = json_decode($response, true);
    return $data;
}



function changeStatusIpla($data){
    //Variables
    $result = true;
    $returnArray = array();
    //Validate Data
    if(isset($data['codigosolicitud'])){ if($data['codigosolicitud'] == ''){ $response = ['codigosolicitud' => 'Debe indicar el codigo de solicitud indicado en el IPLA.']; array_push($returnArray, $response); } }else{ $response = ['codigosolicitud' => 'Debe indicar el codigo de solicitud indicado en el IPLA.']; array_push($returnArray, $response); }
    if(isset($data['indicador'])){ if($data['indicador'] == ''){ $response = ['indicador' => 'Debe indicar el Indicador.']; array_push($returnArray, $response); } else if($data['indicador'] == '1' || $data['indicador'] == '2'){ }else{ $response = ['indicador' => 'Debe indicar el Indicador.']; array_push($returnArray, $response); } }else{ $response = ['indicador' => 'Debe indicar el Indicador.']; array_push($returnArray, $response); }
    
    return $returnArray;
}

function sftpResponse($datas){
    //Variables
    $returnArray = array();
    $i = 1;
    foreach($datas as $data){
        //Validat Data
        if (isset($data['codigoapp'])) { if ($data['codigoapp'] == '') { $data = ['codigoapp '.$i => 'Debe indicar el codigo de la app1.']; array_push($returnArray, $data); } } else { $data = ['codigoapp '.$i => 'Debe indicar el codigo de la app1.']; array_push($returnArray, $data); }
        if (isset($data['ramo'])) { if ($data['ramo'] == '') { $data = ['ramo '.$i => 'Debe indicar el ramo.']; array_push($returnArray, $data); } } else { $data = ['ramo '.$i => 'Debe indicar el ramo.']; array_push($returnArray, $data); }
        if (isset($data['numoperacion'])) { if ($data['numoperacion'] == '') { $data = ['numoperacion '.$i => 'Debe indicar el numero de operacion.']; array_push($returnArray, $data); } } else { $data = ['numoperacion '.$i => 'Debe indicar el numero de operacion.']; array_push($returnArray, $data); }

        foreach($data['archivos'] as $file){
            if (isset($file['nombrearchivo'])) { if ($file['nombrearchivo'] == '') { $file = ['nombrearchivo' => 'Debe indicar el nombre del archivo ' . $i]; array_push($returnArray, $file); } }else{ $file = ['nombrearchivo' => 'Debe indicar el nombre del archivo ' . $i]; array_push($returnArray, $file); }
            if (isset($file['extension'])) { if ($file['extension'] == '') { $file = ['extension' => 'Debe indicar la extension del archivo ' . $i]; array_push($returnArray, $file); } }else{ $file = ['extension' => 'Debe indicar la extension del archivo ' . $i]; array_push($returnArray, $file); }
            if (isset($file['url'])) { }else{ $file = ['url' => 'Debe indicar la url ' . $i]; array_push($returnArray, $file); }
            if (isset($file['estado'])) { if ($file['estado'] == '') { $file = ['estado' => 'Debe indicar el estado ' . $i]; array_push($returnArray, $file); } }else{ $file = ['estado' => 'Debe indicar el estado ' . $i]; array_push($returnArray, $file); }
            if (isset($file['observacion'])) { }else{ $file = ['observacion' => 'Debe indicar la observacion ' . $i]; array_push($returnArray, $file); }
        }
        $i++;
    }

    return $returnArray;
}

function viamaticaResponse($data){
    //Variables
    $returnArray = array();
    
    //Validate Data
    if (isset($data['idDocMigrado'])) { if ($data['idDocMigrado'] == '') { $data = ['idDocMigrado' => 'Debe indicar el codigo del idDocMigrado.']; array_push($returnArray, $data); } }else{ $data = ['idDocMigrado' => 'Debe indicar el codigo del idDocMigrado.']; array_push($returnArray, $data); }
    if (isset($data['estado'])) { if ($data['estado'] == '') { $data = ['estado' => 'Debe indicar el codigo del estado.']; array_push($returnArray, $data); } }else{ $data = ['estado' => 'Debe indicar el codigo del estado.']; array_push($returnArray, $data); }
    if (isset($data['url'])) { if ($data['url'] == '') { $data = ['url' => 'Debe indicar la url.']; array_push($returnArray, $data); } }else{ $data = ['url' => 'Debe indicar la url.']; array_push($returnArray, $data); }
    
    return $returnArray;
}

function emisionResponse($json){
    //Variables
    $returnArray = array();
    
    if (isset($json['codtransaccion'])) { if ($json['codtransaccion'] == '') { $data = ['codtransaccion' => 'Debe indicar el codigo del codtransaccion.']; array_push($returnArray, $data); } } else { $data = ['codtransaccion' => 'Debe indicar el codigo del codtransaccion.']; array_push($returnArray, $data); }

    return $returnArray;
}

function uploadFilesSftpSS(){

        $folderSql = 'SELECT distinct(s.id), s.picture_factura, s.picture_carta, p.ramoid
        FROM sales s
        INNER JOIN products_channel pc ON pc.id = s.pbc_id 
        INNER JOIN products p ON p.id = pc.product_id 
        WHERE s.status_id in (21,26) and s.picture_carta is not null and s.id NOT IN (SELECT sl.sales_id FROM sftp_log sl WHERE sl.type = 1);';
        $foldersEmit = DB::select($folderSql);
        
    
        $folderSql = 'SELECT DISTINCT(vf.id), vf.sales_id, vf.picture_document_applicant, vf.picture_document_spouse, vf.picture_voting_ballot, picture_voting_ballot_spouse, vf.picture_service, vf.picture_sri, p.ramoid
        FROM vinculation_form vf
        INNER JOIN sales s ON s.id = vf.sales_id 
        INNER JOIN products_channel pc ON pc.id = s.pbc_id 
        INNER JOIN products p ON p.id = pc.product_id 
        WHERE vf.sales_id NOT IN (SELECT sl.sales_id FROM sftp_log sl WHERE sl.type = 2)	
        AND vf.user_validate is not null;';
        $foldersVinculation = DB::select($folderSql);
        
        $file = 'archivos.txt'; 
        $content = '';
        $codigo_app = 1;
        if(!empty($foldersEmit) || !empty($foldersVinculation)){
            if(!empty($foldersVinculation)){
                $operacion = '_2';
                $type = 2;
                //Vinculation
                    foreach ($foldersVinculation as $folder) {
                        //TXT File Variables
                        $ramo = $folder->ramoid;
                        $numero_operacion = $folder->sales_id.$operacion;

                        //Document Applicant
                        if($folder->picture_document_applicant != null){
                            $str = (explode("/",$folder->picture_document_applicant));
                            // upload file to sftp
                            $nameRemoteFile = 'archivos/recepcion/'.$str[6];
                            \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $str[3].'/'.$str[4].'/'.$str[5].'/'.$str[6], 1);

                            //Create TXT File
                            $extencion = strstr($str[6], '.');
                            $nombre = strstr($str[6], '.', true);
                            $extencion = str_replace(".", "", $str[6]);
                            $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $nombre . "\t" . $extencion . "\n";

                            //Store data log
                            $sftp_logNew = new \App\sftp_log();
                            $sftp_logNew->type = $type;
                            $sftp_logNew->cod_app = $codigo_app;
                            $sftp_logNew->ramo_id = $ramo;
                            $sftp_logNew->sales_id = $numero_operacion;
                            $sftp_logNew->name = $nombre;
                            $sftp_logNew->extension = $extencion;
                            $sftp_logNew->save();
                        }
                        //Document Spouse
                        if($folder->picture_document_spouse != null){
                            $str = (explode("/",$folder->picture_document_spouse));
                            // upload file to sftp
                            $nameRemoteFile = 'archivos/recepcion/'.$str[6];
                            \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $str[3].'/'.$str[4].'/'.$str[5].'/'.$str[6], 1);

                            //Create TXT File
                            $extencion = strstr($str[6], '.');
                            $nombre = strstr($str[6], '.', true);
                            $extencion = str_replace(".", "", $str[6]);
                            $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $nombre . "\t" . $extencion . "\n";

                            //Store data log
                            $sftp_logNew = new \App\sftp_log();
                            $sftp_logNew->type = $type;
                            $sftp_logNew->cod_app = $codigo_app;
                            $sftp_logNew->ramo_id = $ramo;
                            $sftp_logNew->sales_id = $numero_operacion;
                            $sftp_logNew->name = $nombre;
                            $sftp_logNew->extension = $extencion;
                            $sftp_logNew->save();
                        }
                        //Voting Ballot
                        if($folder->picture_voting_ballot != null){
                            $str = (explode("/",$folder->picture_voting_ballot));
                            // upload file to sftp
                            $nameRemoteFile = 'archivos/recepcion/'.$str[6];
                            \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $str[3].'/'.$str[4].'/'.$str[5].'/'.$str[6], 1);

                            //Create TXT File
                            $extencion = strstr($str[6], '.');
                            $nombre = strstr($str[6], '.', true);
                            $extencion = str_replace(".", "", $str[6]);
                            $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $nombre . "\t" . $extencion . "\n";

                            //Store data log
                            $sftp_logNew = new \App\sftp_log();
                            $sftp_logNew->type = $type;
                            $sftp_logNew->cod_app = $codigo_app;
                            $sftp_logNew->ramo_id = $ramo;
                            $sftp_logNew->sales_id = $numero_operacion;
                            $sftp_logNew->name = $nombre;
                            $sftp_logNew->extension = $extencion;
                            $sftp_logNew->save();
                        }
                        //Voting Ballot Spouse
                        if($folder->picture_voting_ballot_spouse != null){
                            $str = (explode("/",$folder->picture_voting_ballot_spouse));
                            // upload file to sftp
                            $nameRemoteFile = 'archivos/recepcion/'.$str[6];
                            \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $str[3].'/'.$str[4].'/'.$str[5].'/'.$str[6], 1);

                            //Create TXT File
                            $extencion = strstr($str[6], '.');
                            $nombre = strstr($str[6], '.', true);
                            $extencion = str_replace(".", "", $str[6]);
                            $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $nombre . "\t" . $extencion . "\n";

                            //Store data log
                            $sftp_logNew = new \App\sftp_log();
                            $sftp_logNew->type = $type;
                            $sftp_logNew->cod_app = $codigo_app;
                            $sftp_logNew->ramo_id = $ramo;
                            $sftp_logNew->sales_id = $numero_operacion;
                            $sftp_logNew->name = $nombre;
                            $sftp_logNew->extension = $extencion;
                            $sftp_logNew->save();
                        }
                        //Picture Service
                        if($folder->picture_service != null){
                            $str = (explode("/",$folder->picture_service));
                            // upload file to sftp
                            $nameRemoteFile = 'archivos/recepcion/'.$str[6];
                            \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $str[3].'/'.$str[4].'/'.$str[5].'/'.$str[6], 1);

                            //Create TXT File
                            $extencion = strstr($str[6], '.');
                            $nombre = strstr($str[6], '.', true);
                            $extencion = str_replace(".", "", $str[6]);
                            $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $nombre . "\t" . $extencion . "\n";

                            //Store data log
                            $sftp_logNew = new \App\sftp_log();
                            $sftp_logNew->type = $type;
                            $sftp_logNew->cod_app = $codigo_app;
                            $sftp_logNew->ramo_id = $ramo;
                            $sftp_logNew->sales_id = $numero_operacion;
                            $sftp_logNew->name = $nombre;
                            $sftp_logNew->extension = $extencion;
                            $sftp_logNew->save();
                        }
                        //Picture Sri
                        if($folder->picture_sri != null){
                            $str = (explode("/",$folder->picture_sri));
                            // upload file to sftp
                            $nameRemoteFile = 'archivos/recepcion/'.$str[6];
                            \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $str[3].'/'.$str[4].'/'.$str[5].'/'.$str[6], 1);

                            //Create TXT File
                            $extencion = strstr($str[6], '.');
                            $nombre = strstr($str[6], '.', true);
                            $extencion = str_replace(".", "", $str[6]);
                            $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $nombre . "\t" . $extencion . "\n";

                            //Store data log
                            $sftp_logNew = new \App\sftp_log();
                            $sftp_logNew->type = $type;
                            $sftp_logNew->cod_app = $codigo_app;
                            $sftp_logNew->ramo_id = $ramo;
                            $sftp_logNew->sales_id = $numero_operacion;
                            $sftp_logNew->name = $nombre;
                            $sftp_logNew->extension = $extencion;
                            $sftp_logNew->save();
                        }
                    }
                }
                $operacion = '_1';
                if(!empty($foldersEmit)){
                    foreach ($foldersEmit as $folder) {
                        $type = 1;
                        //TXT File Variables
                        $ramo = $folder->ramoid;
                        $numero_operacion = $folder->id.$operacion;

                        //Picture Factura
                        if($folder->picture_factura != null){
                            $str = (explode("/",$folder->picture_factura));
                            // upload file to sftp
                            $nameRemoteFile = 'archivos/recepcion/'.$str[6];
                            $extencion = str_replace(".", "", $str[6]);
                            \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $str[3].'/'.$str[4].'/'.$str[5].'/'.$str[6], 1);

                            //Create TXT File
                            $extencion = strstr($str[6], '.');
                            $nombre = strstr($str[6], '.', true);
                            $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $nombre . "\t" . $extencion . "\n";

                            //Store data log
                            $sftp_logNew = new \App\sftp_log();
                            $sftp_logNew->type = $type;
                            $sftp_logNew->cod_app = $codigo_app;
                            $sftp_logNew->ramo_id = $ramo;
                            $sftp_logNew->sales_id = $numero_operacion;
                            $sftp_logNew->name = $nombre;
                            $sftp_logNew->extension = $extencion;
                            $sftp_logNew->save();
                        }
                        //Picture Carta
                        if($folder->picture_carta != null){
                            $str = (explode("/",$folder->picture_carta));
                            // upload file to sftp
                            $nameRemoteFile = 'archivos/recepcion/'.$str[6];
                            \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $str[3].'/'.$str[4].'/'.$str[5].'/'.$str[6], 1);

                            //Create TXT File
                            $extencion = strstr($str[6], '.');
                            $nombre = strstr($str[6], '.', true);
                            $extencion = str_replace(".", "", $str[6]);
                            $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $nombre . "\t" . $extencion . "\n";

                            //Store data log
                            $sftp_logNew = new \App\sftp_log();
                            $sftp_logNew->type = $type;
                            $sftp_logNew->cod_app = $codigo_app;
                            $sftp_logNew->ramo_id = $ramo;
                            $sftp_logNew->sales_id = $numero_operacion;
                            $sftp_logNew->name = $nombre;
                            $sftp_logNew->extension = $extencion;
                            $sftp_logNew->save();
                        }
                    }
                }
            //Store File
            file_put_contents(public_path($file), $content, LOCK_EX);
            $nameRemoteFile = 'archivos/recepcion/'.$file;
            //Subir archivo al SFTP de SS
            \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $file, 2);
            
            //Store Copy of file
            Storage::disk('s3')->put('Images/Sftp/Files/Sent/'.$file, $file);
        }
        //message
        return('Operación realizada con éxito');
} 


function forwardFilesSftpSS($response){
        $file = 'archivos.txt'; 
        $content = '';
        $codigo_app = 1;
        
        foreach($response as $res){
            $saleSearch = explode("_", $res['numoperacion']);
            if($saleSearch[1] == 1){ //EMISION
                if($res['ramo'] == 7){
                    $RAMO = 'r1/';
                }
                if($res['ramo'] == 1 || $res['ramo'] == 2){
                    $RAMO = 'r2/';
                }
                if($res['ramo'] == 4){
                    $RAMO = 'r3/';
                }
                if($res['ramo'] == 5 || $res['ramo'] == 40){
                    $RAMO = 'r4/';
                }
                $path = base_path('public/images/sales/'.$RAMO.$saleSearch[0]);
            }else{ //VINCULACION
                $path = base_path('public/images/vinculation/'.$saleSearch[0]);
            }
            $dir = opendir($path);

            $file = 'archivos.txt'; 
            $content = '';
            $codigo_app = 1;
            $ramo = $res['ramo'];
            $numero_operacion = $saleSearch[0];
            $files = $res['archivos'];
            $sftp_logNew = new \App\sftp_log();

            while ($elemento = readdir($dir)){
                if( $elemento != "." && $elemento != "..")
                {
                    if(!is_dir($path.$elemento) )
                    {
                        $extencion = strstr($elemento, '.');
                        $nombre = strstr($elemento, '.', true); 
                        $content .= $codigo_app . "\t" . $ramo . "\t" . $numero_operacion . "\t" . $res['archivos'][0]['nombrearchivo'] . "\t" . $extencion . "\n";
                        //Store data log
                            if (in_array($elemento, $files))
                            {    
                                $sftp_logNew->type = 2;
                                $sftp_logNew->cod_app = $codigo_app;
                                $sftp_logNew->ramo = $ramo;
                                $sftp_logNew->sales_id = $numero_operacion;
                                $sftp_logNew->name = $nombre;
                                $sftp_logNew->extension = $extencion;
                                $sftp_logNew->save();
                                
                                // upload file to sftp
                                $nameRemoteFile = 'archivos/recepcion/'.$elemento;
                                $nameLocalFile = $path.$elemento;
                                //Subir archivo al SFTP de SS
                                \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $nameLocalFile);
                                
                                file_put_contents($path.$file, $content, LOCK_EX);
                            }                  
                    }
                }
            }
            $nameRemoteFile = 'archivos/recepcion/'.$file;
            $nameLocalFile = $path.$file;
            
            return('Operación realizada con éxito');
        }
}

function sftpPaymentsSS(){
      $today = date('Y-m-d');
      $date = date('Y-m-d');
      $day_before = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );
      $consultSQL = 'SELECT distinct(s.id) as "id", s.certificado, s.poliza, c.document, s.documento as "invoice", s.total as "value", df.lot, df.reference, p2.ramoid, df.created_at
        FROM sales s
        JOIN datafast_log df ON df.id_cart = s.id and df.code = "000.100.112" 
        JOIN customers c ON c.id = s.customer_id 
        JOIN products_channel pc ON pc.id = s.pbc_id 
        JOIN products p2 ON p2.id = pc.product_id 
        where s.status_id in (21) and s.sftp_payments_sent is NULL;';
        $payments = DB::select($consultSQL);
        
        if(empty($payments)){
            dd('empty');
        }
        
        $consultSQL = 'SELECT COUNT(*) as registros , SUM(s.total) as total 
        FROM sales s
        JOIN datafast_log df ON df.id_cart = s.id and df.code = "000.100.112" 
        JOIN customers c ON c.id = s.customer_id 
        JOIN products_channel pc ON pc.id = s.pbc_id 
        JOIN products p2 ON p2.id = pc.product_id 
        where s.status_id in (21) and s.sftp_payments_sent is NULL;';
        $total_data = DB::select($consultSQL);
        
        if($total_data[0]->registros == 0){
            dd('empty');
        }

        $date = date("Ymd");
        $sftpPagosLog = \App\sftp_pagos_log::count();
        if($sftpPagosLog == 0){
            $version = 1;
        }else{
            $version = 1 + $sftpPagosLog;
        }
        
        $sftpPagosLogNew = new \App\sftp_pagos_log();
        $sftpPagosLogNew->date = $date;
        $sftpPagosLogNew->version = $version;
        $sftpPagosLogNew->save();
        
        $file = 'PAGOS_1_'.$date.'_' .$version. '.txt'; 
        $content = '';

        // file header
        $cod_app = 1;
        $numregistros = $total_data[0]->registros;
        $sumtotalpagos = $total_data[0]->total;

        $content .= $cod_app . "\t" . $numregistros . "\t" . $sumtotalpagos . "\n";

        foreach ($payments as $payment)
        {
            //Update Sales
            $sale = App\sales::find($payment->id);
            $sale->sftp_payments_sent = new \DateTime();
            $sale->save();
            
            // file detail
            $numero_operacion = $payment->id;
            $ramo = $payment->ramoid;
            $poliza = $payment->poliza; 
            $certificado = $payment->certificado; 
            $contratante = $payment->document; 
            $factura = $payment->invoice; 
            $formatoPago = '82';
            $marcatrj = '1';
            $entidadfinanciera = '0990005737001';
            $valorpago = $payment->value; 
            $lote = $payment->lot; 
            $recap = $payment->lot; 
            $referencia = $payment->reference;

            $content .= $numero_operacion . "\t" . $ramo . "\t" . $poliza . "\t" . $certificado . "\t" . $contratante . "\t" . $factura . "\t" . $formatoPago . "\t" . $marcatrj . "\t" . $entidadfinanciera . "\t" . $valorpago . "\t" . $lote . "\t" . $recap . "\t" . $referencia . "\n"; 
        }

        file_put_contents(public_path($file), $content, LOCK_EX);

        //download file remote address
        $nameRemoteFile = '/pagos/recepcion/'.$file;

        //upload file
        Storage::disk('sftp')->put($nameRemoteFile, Storage::disk('public_sftp')->get($file));
//        \App\Jobs\ftpSucreJobs::dispatch($nameRemoteFile, $file, 2);
        
        //Store a Copy of the file
        Storage::disk('s3')->put('Images/Sftp/Payments/Sent/'.$file, $file);

        //message
        return('Operación realizada con éxito');
}

function sftpPaymentsReceiveSS(){  
        $sftpPagosLog = \DB::table('sftp_pagos_log')->orderBy('created_at','desc')->get();
        $date = $sftpPagosLog[0]->date;
        $version = $sftpPagosLog[0]->version;

        $file = 'PAGOS_1_'.$date.'_' .$version. '_RESP.txt'; 

        $filepath = '/pagos/respuesta/'.$file;
        //Download File
        Storage::disk('s3')->put('Images/Sftp/Payments/Resp/'.$file, Storage::disk('sftp')->get($filepath));
        
        $url = Storage::disk('s3')->url('Images/Sftp/Payments/Resp/'.$file);
        
        //Check File
        $txt_file = file_get_contents($url);
        $rows = explode("\n", $txt_file);
        array_shift($rows);
        
        foreach($rows as $data)
        {
            //get row data
            $row_data = explode("\t", $data);

            if(isset($row_data[13])){
                if($row_data[13] == 'PROCESO EXITOSO'){
                }else{
                    //Update Sales
                    $sale = App\sales::find($row_data[0]);
                    $sale->sftp_payments_sent = null;
                    $sale->save();
                    echo $row_data;
                }
            }else{
                if($row_data[0] != ''){
                    //Update Sales
                    $sale = App\sales::find($row_data[0]);
                    $sale->sftp_payments_sent = null;
                    $sale->save();
                    echo $row_data;
                }
            }
        }

        return('Operación realizada con éxito');
}

function XMLtoArray($XML)
{
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $XML, $vals);
    xml_parser_free($xml_parser);
    // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
    $_tmp='';
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_level!=1 && $x_type == 'close') {
            if (isset($multi_key[$x_tag][$x_level]))
                $multi_key[$x_tag][$x_level]=1;
            else
                $multi_key[$x_tag][$x_level]=0;
        }
        if ($x_level!=1 && $x_type == 'complete') {
            if ($_tmp==$x_tag)
                $multi_key[$x_tag][$x_level]=1;
            $_tmp=$x_tag;
        }
    }
    // jedziemy po tablicy
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_type == 'open')
            $level[$x_level] = $x_tag;
        $start_level = 1;
        $php_stmt = '$xml_array';
        if ($x_type=='close' && $x_level!=1)
            $multi_key[$x_tag][$x_level]++;
        while ($start_level < $x_level) {
            $php_stmt .= '[$level['.$start_level.']]';
            if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
            $start_level++;
        }
        $add='';
        if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
            if (!isset($multi_key2[$x_tag][$x_level]))
                $multi_key2[$x_tag][$x_level]=0;
            else
                $multi_key2[$x_tag][$x_level]++;
            $add='['.$multi_key2[$x_tag][$x_level].']';
        }
        if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
            if ($x_type == 'open')
                $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
            else
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
            eval($php_stmt_main);
        }
        if (array_key_exists('attributes', $xml_elem)) {
            if (isset($xml_elem['value'])) {
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            foreach ($xml_elem['attributes'] as $key=>$value) {
                $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                eval($php_stmt_att);
            }
        }
    }
    return $xml_array;
}

function smsEclipsoft($mobilePhone, $message) {
    $date = date("m/d/Y");
    $hour = date("h:i");
    $data = '<?xml version="1.0" encoding="utf-8"?><soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope"><soap12:Body><EnviarSMS xmlns="http://secure.eclipsoft.com/wsSMSEmpresarial/wscSMSEmp"><parEmisor><emServicio>CONTACTOSMS</emServicio><emEmisor>SEGSUCREONLINE</emEmisor><emLogin>SEGSUCREONLINE</emLogin><emPwd>sgsn@csms</emPwd><emReferencia>1</emReferencia><emFechaEnv>'.$date.'</emFechaEnv><emHoraEnv>'.$hour.'</emHoraEnv><emNombrePC>laptop</emNombrePC><emKey>8e7109d47083fb3a9a4818d1850ac786</emKey><emLimite>1</emLimite><emTotalMes>1</emTotalMes></parEmisor><parDestinatarios>'.$mobilePhone.'</parDestinatarios><parMensaje>'.$message.'</parMensaje></EnviarSMS></soap12:Body></soap12:Envelope>';
    
    //Save Log
    $smsLog = new App\sms_logs();
    $smsLog->sales_id = 1;
    $smsLog->mobile_phone = $mobilePhone;
    $smsLog->data_send = GuzzleHttp\json_encode($data);
    $smsLog->send_date = now();
    $smsLog->Save();


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://app2.eclipsoft.com:9443/wsSMSEmpresarial/wscSMSEmp.asmx?op=EnviarSMS",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: text/xml"
        ),
    ));

    $response = curl_exec($curl);
//    dd($response);
    curl_close($curl);
    
    $array = XMLtoArray($response);
    
    //Update SMS Log with response
    $smsLogResponse = App\sms_logs::find($smsLog->id);
    $smsLogResponse->data_response = serialize($array);
    $smsLogResponse->response_date = now();
    $smsLogResponse->save();
    
    return $response;
}

function getCoverageDetails($covDes, $productId){
     $coverage = \App\coverage::selectRaw('texto as coberturades, valorasegurado')
                                     ->where('product_id','=',$productId)
                                     ->where('texto','!=',null)
                                    ->orderBy('ordenimp')
                                     ->distinct('coberturaid')
                                     ->get();

    //$coverage = DB::select($coverageQuery);
    $returnData = '';
    $string = '';
    foreach($coverage as $cov){
        $returnData .= strip_tags(str_replace('width: 800px;','',$cov->coberturades),'<p><u><ul><li><strong>&nbsp;<br><table><tbody><tr><td>');
//        $returnData .= $cov->coberturades;
                        // $returnData .= '<tr>
                        //     <td style="width:100%">'.$cov->coberturades.'</td>
                        // </tr>';
    }
    
    return $returnData;
}

function findRamo($salId){
    $ramo = \App\products::selectRaw('products.ramoid')
                            ->join('products_channel as pbc','pbc.product_id','=','products.id')
                            ->join('sales as sal','sal.pbc_id','=','pbc.id')
                            ->where('sal.id','=',$salId)
                            ->get();
    return $ramo[0]->ramoid;
}

function inspectionValidateR2($id) {
    $inspection = App\inspection::find($id);
    $sales = \App\sales::find($inspection->sales_id);
    if($sales->url_viamatica == null){
        return 'false';
    }else{
        return 'true';
    }
}

function productChannelStoreSS(){
    set_time_limit(60000);
    $result = productChannelSS();
    if($result['canalproducto'] != null){
        $delete = \App\products_agency::truncate();
        $delete = \App\coverage::truncate();
        $delete = \App\products_rubros::truncate();
    }
    $arrayChannels = array();
    $arrayAgents = array();
    $arrayAgencies = array();
    $arrayProducts = array();
    $arrayRubros = array();
    foreach($result['canalproducto'] as $res){
        //Store Agencia SS
        $agenciaSSFind = \App\agencia_ss::where('agenciaid','=',$res['agenciaid'])->get();
        if($agenciaSSFind->isEmpty()){
            $agenciaSS = new \App\agencia_ss();
            $agenciaSS->agenciades = $res['agenciades'];
            $agenciaSS->agenciaid = $res['agenciaid'];
            $agenciaSS->save();
            $agenciaSSId = $agenciaSS->id;
        }else{
            $agenciaSS = \App\agencia_ss::find($agenciaSSFind[0]->id);
            $agenciaSS->agenciades = $res['agenciades'];
            $agenciaSS->agenciaid = $res['agenciaid'];
            $agenciaSS->save();
            $agenciaSSId = $agenciaSSFind[0]->id;
        }
        //Array Agents
        array_push($arrayAgents,$res['agenteid']);
        //Store Agente SS
        $agentSSFind = \App\agent_ss::where('agenteid','=',$res['agenteid'])->get();
        if($agentSSFind->isEmpty()){
            $agentSS = new \App\agent_ss();
            $agentSS->agentedes = $res['agentedes'];
            $agentSS->agenteid = $res['agenteid'];
            $agentSS->status_id = 1;
            $agentSS->save();
            $agenteSSId = $agentSS->id;
        }else{
            $agentSS = \App\agent_ss::find($agentSSFind[0]->id);
            $agentSS->agentedes = $res['agentedes'];
            $agentSS->agenteid = $res['agenteid'];
            $agentSS->status_id = 1;
            $agentSS->save();
            $agenteSSId = $agentSSFind[0]->id;
        }
        //Array Canales
        array_push($arrayChannels,$res['canalnegoid']);
        //Store Channel
        $channelFind = \App\channels::where('canalnegoid','=',$res['canalnegoid'])->get();
        if($channelFind->isEmpty()){
            $channel = new \App\channels();
            $channel->canalnegodes = $res['canalnegodes'];
            $channel->canalnegoid = $res['canalnegoid'];
            $channel->status_id = 1;
            $channel->save();
            $channelId = $channel->id;
        }else{
            $channel = \App\channels::find($channelFind[0]->id);
            $channel->canalnegodes = $res['canalnegodes'];
            $channel->canalnegoid = $res['canalnegoid'];
            $channel->status_id = 1;
            $channel->save();
            $channelId = $channelFind[0]->id;
        }
        //Array Agencias
        array_push($arrayAgencies,$res['puntoventaid']);
        //Store Agencia (Punto de Venta)
        $agencieFind = \App\Agency::where('puntodeventaid','=',$res['puntoventaid'])->get();
        if($agencieFind->isEmpty()){
            $agency = new \App\Agency();
            $agency->puntodeventades = $res['puntoventades'];
            $agency->puntodeventaid = $res['puntoventaid'];
            $agency->channel_id = $channelId;
            $agency->save();
            $agencyId = $agency->id;
        }else{
            $agency = \App\Agency::find($agencieFind[0]->id);
            $agency->puntodeventades = $res['puntoventades'];
            $agency->puntodeventaid = $res['puntoventaid'];
            $agency->channel_id = $channelId;
            $agency->save();
            $agencyId = $agency->id;
        }
        //Array Products
        array_push($arrayProducts,$res['canalplanid']);
        //Store Product
        $productFind = \App\products::where('canalplanid','=',$res['canalplanid'])->get();
        if($productFind->isEmpty()){
            $product = new \App\products();
            $product->productodes = $res['productodes'];
            $product->productoid = $res['productoid'];
            $product->ramodes = $res['ramodes'];
            $product->ramoid = $res['ramoid'];
            $product->agency_id = $agencyId;
            $product->canalplanid = $res['canalplanid'];
            $product->status_id = 1;
            $product->save();
            $productId = $product->id;
        }else{
            $product = \App\products::find($productFind[0]->id);
            $product->productodes = $res['productodes'];
            $product->productoid = $res['productoid'];
            $product->ramodes = $res['ramodes'];
            $product->ramoid = $res['ramoid'];
            $product->agency_id = $agencyId;
            $product->canalplanid = $res['canalplanid'];
            $product->status_id = 1;
            $product->save();
            $productId = $productFind[0]->id;
        }
        $proAgency = new \App\products_agency();
        $proAgency->product_id = $productId;
        $proAgency->agency_id = $agencyId;
        $proAgency->status_id = 1;
        $proAgency->save();

        //Delete Coverage Data
        $result = \App\coverage::where('product_id','=',$productId)->delete();
        //Store Coverage (Coberturas)
        \App\Jobs\coverageStoreJob::dispatch($res['coberturas'], $productId);
        if(!empty($res['rubros'])){
            foreach($res['rubros'] as $rubros){
                $rubroFind = \App\products_rubros::where('cod','=',$rubros['codigo'])->where('product_id','=',$productId)->get();
                if($rubroFind->isEmpty()){
                    //Array Rubros
                    array_push($arrayRubros,$rubros['codigo']);
                    $rubro = new \App\products_rubros();
                    $rubro->cod = $rubros['codigo'];
                    $rubro->description = $rubros['descripcion'];
                    $rubro->indicator = $rubros['indicador'];
                    $rubro->max_value = $rubros['montomaximo'];
                    $rubro->min_value = $rubros['montominimo'];
                    $rubro->value = number_format($rubros['valorasegurado'], 2, '.', ',');
                    $rubro->status_id = 1;
                    $rubro->product_id = $productId;
                    $rubro->save();
                }
            }
        }
        //Product Chanel
        $productChannel = \App\product_channel::where('canal_plan_id','=',$res['canalplanid'])->get();
        if($productChannel->isEmpty()){
            $pro = new \App\product_channel();
            $pro->channel_id = $channelId;
            $pro->product_id = $productId;
            $pro->status_id = 1;
            $pro->agency_id = $agencyId;
            $pro->canal_plan_id = $res['canalplanid'];
            $pro->agency_ss_id = $agenciaSSId;
            $pro->agent_ss = $agenteSSId;
            $pro->ejecutivo_ss = $res['atributos']['1']['valor'];
            $pro->ejecutivo_ss_email = $res['atributos']['0']['valor'];
            $pro->save();
        }else{
            $pro = \App\product_channel::find($productChannel[0]->id);
            $pro->channel_id = $channelId;
            $pro->product_id = $productId;
            $pro->status_id = 1;
            $pro->agency_id = $agencyId;
            $pro->canal_plan_id = $res['canalplanid'];
            $pro->agency_ss_id = $agenciaSSId;
            $pro->agent_ss = $agenteSSId;
            $pro->ejecutivo_ss = $res['atributos']['1']['valor'];
            $pro->ejecutivo_ss_email = $res['atributos']['0']['valor'];
            $pro->save();

        }
    }
    //Validate Products
    $products = \App\product_channel::whereNotIn('canal_plan_id',$arrayProducts)->get();
    foreach($products as $p){
       $pro = \App\product_channel::find($p->id);
       $pro->status_id = 2;
       $pro->save();
    }
    //Validate Rubros
//        $rubros = \App\products_rubros::whereNotIn('cod', $arrayRubros)->get();
//        foreach($rubros as $ru){
//            $rubroSearch = \App\products_rubros::where('cod','=',$ru->cod)->get();
//            $rubro = \App\products_rubros::find($rubroSearch[0]->id);
//            $rubro->status_id = 2;
//            $rubro->save();
//        }
    //Validate Agents
    $agents = \App\agent_ss::whereNotIn('agenteid',$arrayAgents)->get();
    foreach($agents as $c){
       $agent = \App\agent_ss::find($c->id);
       $agent->status_id = 2;
       $agent->save();
    }
    //Validate Channels
    $channels = \App\channels::whereNotIn('canalnegoid',$arrayChannels)->get();
    foreach($channels as $c){
       $channel = \App\channels::find($c->id);
       $channel->status_id = 2;
       $channel->save();
       $agencies = \App\Agency::where('channel_id','=',$c->id)->get();
       foreach($agencies as $a){
           $user = \App\User::where('agen_id','=',$a->id)->whereNotIn('type_user_sucre_id',[1])->get();
           foreach($user as $u){
               $usr = \App\User::find($u->id);
               $usr->status_id = 2;
               $usr->save();
           }
       }
    }
    //Validate Agency
    $agency = \App\Agency::whereNotIn('puntodeventaid', $arrayAgencies)->get();
    foreach($agency as $a){
       $user = \App\User::where('agen_id','=',$a->id)->whereNotIn('type_user_sucre_id',[1])->get();
       foreach($user as $u){
           $usr = \App\User::find($u->id);
           $usr->status_id = 2;
           $usr->save();
       }
    }
}

function listaObservadosYCarteraFunction($saleId, $customerId, $email, $product){
    $customer = \App\customers::find($customerId);
    $motive = '';

    //Consulta Lista Observados y Cartera Vencida SS
    $resultListaObservados = listaObservadosSS($product, 'N', $customer->first_name, $customer->second_name, $customer->last_name, $customer->second_last_name, 'C', $customer->document);
    $resultCarteraVencida = carteraVencidaSS($customer->document);

    $carteraVencida = false;
    $listaObservados = false;

    if($resultCarteraVencida['error'][0]['code'] == '003') { $carteraVencida = false; } elseif ($resultCarteraVencida['error'][0]['code'] == '000') { if ($resultCarteraVencida['carteravencida'] == 'false') { $carteraVencida = false; } else { $motive .= 'Cartera Vencida'; $carteraVencida = true; } } else { $carteraVencida = '500'; }
    if ($resultListaObservados['error'][0]['code'] == '003') { $listaObservados = false; } elseif ($resultListaObservados['error'][0]['code'] == '000') { if ($resultListaObservados['listaobservados']['indicador'] == 1) { $listaObservados = false; } else { $motive .= ', Lista de Observados'; $listaObservados = true; } } else { $listaObservados = '500'; }
    //ERROR NO IDENTIFICADO
    if($carteraVencida === '500' || $listaObservados === '500'){
        $salesUpdate = \App\sales::find($saleId);
        $salesUpdate->status_id = 33;
        $salesUpdate->save();
    }else{
        if($carteraVencida == true || $listaObservados == true){
            $salesUpdate = \App\sales::find($saleId);
            $salesUpdate->status_id = 24;
            $salesUpdate->codigo_solicitud_ipla = $resultListaObservados['listaobservados']['codigosolicitud'];
            $salesUpdate->save();

            $customerUpdate = \App\customers::find($customerId);
            $customerUpdate->status_id = 24;
            $customerUpdate->save();            

            $job = (new \App\Jobs\infoListsUserEmailJobs($saleId, $email, $customer->document));
            dispatch($job);

            //Ejecutivo Email
            $email = \App\product_channel::selectRaw('products_channel.ejecutivo_ss_email')
                                            ->join('sales as sal','sal.pbc_id','=','products_channel.id')
                                            ->where('sal.id','=',$saleId)
                                            ->get();

            App\Jobs\infoListsEjecutivoSSEmailJobs::dispatch($saleId, $email[0]->ejecutivo_ss_email, $customer->document, $motive);
        }
    }
}

function getRubrosPdf($saleId){
    $bienAsegurado = \App\properties::selectRaw(' DISTINCT(properties.id), rub.description, proRub.value')
                                                    ->join('properties_rubros as proRub','proRub.property_id','=','properties.id')
                                                    ->join('cities as cit','cit.id','=','properties.city_id')
                                                    ->join('products_rubros as rub','rub.cod','=','proRub.rubros_cod')
                                                    ->where('properties.sales_id','=',$saleId)
                                                    ->get();
    $returnData = '';
    $i = 1;
    foreach($bienAsegurado as $b){
        if ($i % 2 == 0) {
            $divData = '';
        }else{
            $divData = 'class="divData"';
        }
        
        $returnData .= '<tr '.$divData.'>
                            <td style="width:75%;padding-left:5px">'.$b->description.'</td>
                            <td style="width:25%;padding-left:5px">'.asCurrency($b->value).'</td>
                            <td style="width:25%;padding-left:5px"></td>
                            <td style="width:25%;padding-left:5px"></td>
                        </tr>';
    $i++;    
    }
    return $returnData;
}

function asCurrency($value){
    return number_format($value,2);
}

function validateUserMenu($rolId, $menuId){
    $menuRol = \App\menuRol::where('menu_id','=',$menuId)->where('rol_id','=',$rolId)->get();
    if($menuRol->isEmpty()){ return false; }else{ return true; }
}

function vehicleSearchApp($plate, $status){
    $queryValidate = 'select vehi.*
                from vehicles vehi
                join vehicles_sales vsal on vsal.vehicule_id = vehi.id
                join sales sal on sal.id = vsal.sales_id
                where sal.sales_type_id = 1 and sal.status_id not in(3, 5, 7, 11, 4, 28, 20, 35, 21, 23) and vehi.plate = "'.$plate.'"';

    $validate = DB::select($queryValidate);

    if($validate){ //IT HAS A SALE
        //DATA
        $vehicleArray = [
        'brand' => '0',
        'year' => '',
        'color' => '',
        'model' => '',
        'type' => '',
        'class' => '',
        'validate' => 'true'
    ];
    }else{
        $data = vehicleSS($plate);

        //Validate Error
        if($data['error'][0]['code'] == '006'){
            $vehicleArray = [
                'brand' => '0',
                'year' => '',
                'color' => '',
                'model' => '',
                'type' => '',
                'class' => '',
                'price' => '0',
                'validate' => 'sistemDown',
                'message' => ''
            ];
            return $vehicleArray;
        }

        //Validate Year
        if($status == 'Nuevo'){
            $year = date('Y');
            $nextYear = $year + 1;
            if($data['datosvehiculo']['anio'] == $year || $data['datosvehiculo']['anio'] == $nextYear){
            }else{
                $vehicleArray = [
                    'brand' => '0',
                    'year' => '',
                    'color' => '',
                    'model' => '',
                    'type' => '',
                    'plate' => $plate,
                    'class' => '',
                    'validate' => 'error',
                    'message' => 'Para un vehiculo nuevo, el año del vehiculo debe ser '.$year.' o '.$nextYear
                ];
                return $vehicleArray;
            }
        }

        //Validate Vehicle Price
        $arr = explode(' ', trim($data['datosvehiculo']['modelo'] . ' '));
        $model = $arr[0];

        //Find by Model
        $price = \App\vehiclesPrices::where('description', '=', $data['datosvehiculo']['modelo'])->get();
        if ($price->isEmpty()) {
            //Find by Like Model
            $price = \App\vehiclesPrices::where('brand', 'LIKE', '%' . $data['datosvehiculo']['marca'] . '%')
                    ->where('model', 'LIKE', '%' . $model . '%')
                    ->where('year', '=', $data['datosvehiculo']['anio'])
                    ->get();
        }
        if ($price->isEmpty()) {
            $priceReturn = null;
            $validate = 'priceError';
            $message = 'No es posible obtener un precio referencial para el vehiculo ' . $data['datosvehiculo']['modelo'] . '.<br>Por favor contacte con Seguros Sucre';
        } else {
            $priceReturn = $price[0]->price;
            $validate = 'false';
            $message = 'null';
        }
        $classSearch = \App\vehicle_class::where('name','=',$data['datosvehiculo']['clase'])->get();
        if($classSearch->isEmpty()){
            $class = '';
        }else{
            $class = $classSearch[0]->name;
        }
        //Validate Year
        $year = date('Y');
        $yearDiff = $year - $data['datosvehiculo']['anio'];
        if($yearDiff > 12){
            $validate = 'error';
            $message = 'El vehiculo no puede ser mayor de 12 años.';
        }
        //DATA
        $vehicleArray = [
            'brand' => $data['datosvehiculo']['marca'],
            'year' => $data['datosvehiculo']['anio'],
            'color' => $data['datosvehiculo']['color'],
            'model' => $data['datosvehiculo']['modelo'],
            'type' => $data['datosvehiculo']['tipo'],
            'plate' => $data['datosvehiculo']['placa'],
            'ramv' => $data['datosvehiculo']['camv'],
            'chasis' => $data['datosvehiculo']['chasis'],
            'motor' => $data['datosvehiculo']['motor'],
            'class' => $class,
            'price' => $priceReturn,
            'validate' => $validate,
            'message' => $message
        ];
    }
    return $vehicleArray;
}

function productsVehiclesApp($json) {
    $returnArray = array();
    $user = App\User::find($json['userId']);
    $agency = \App\Agency::find($user->agen_id);
    
    $returnData = '';
    $date = new \DateTime();
    $today = $date->format('d-m-Y');
    $oneYear = date('d-m-Y', strtotime('+1 years'));
    $products = \App\product_channel::selectRaw('products_channel.*, pro.productodes')
            ->join('products as pro', 'pro.id', '=', 'products_channel.product_id')
            ->where('products_channel.agency_id', '=', $agency->id)
            //               ->where('products_channel.channel_id', '=', $agency->channel_id)
            ->where('pro.ramoid', '=', '7')
            ->where('products_channel.status_id', '=', '1')
            ->get();
    
    if ($products->isEmpty()) {
        $response = ["productError" => 'Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo']; array_push($returnArray, $response);
        array_push($returnArray, $response);
        return $returnArray;
    } else {
        foreach ($products as $pro) {
            $agencySS = \App\agencia_ss::find($pro->agency_ss_id);
            $prima = 0;
            $contribucion = 0;
            $sCam = 0;
            $derEmision = 0;
            $subSinIva = 0;
            $subConIva = 0;
            $iva = 0;
            $total = 0;
            foreach ($json['vehicles'] as $vehi) {
                $vehiValue = 0;
                $accValue = 0;
                $vehiValue += str_replace(',', '', $vehi['value']);
                $accValue += str_replace(',', '', $vehi['accValue']);
                $value = $vehiValue + $accValue;
                
                //Consulta Prima por cada vehiculo
                $result = calculoPrimaSS($pro->canal_plan_id, $today, $oneYear, $agencySS->agenciaid, $vehi['vehiType'], $value);
//                $result = calculoPrimaSS(388, $today, $oneYear, $agencySS->agenciaid, $vehi['vehiType'], $value);
//                return $result;
                //Validate if result is valid
                if ($result['error'][0]['code'] != '000') {
                    continue;
                } else {
                    $prima += $result['rubrofactura']['primaneta'];
                }
            }
            //Validate if prima is 0.00
            if ($prima == '0') {
                continue;
            }
            
            $response = [
                "productName" => $pro->productodes,
                "productPrice" => number_format((float)$prima, 2, '.', ''),
                "productId" => $pro->product_id,
                "productCanalPlanId" => $pro->canal_plan_id
            ];
            array_push($returnArray, $response);
           
        }
    }
    if ($returnArray == null) {
//    if (1 == 1) {
        $response = ["productError" => 'Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo']; array_push($returnArray, $response);
        
        return $returnArray;
    } else {
        return $returnArray;
    }
}

function r2CheckPrice($json) {
    $returnArray = array();
    $user = App\User::find($json['userId']);
    $agency = \App\Agency::find($user->agen_id);
    $count_products = 0;

    $returnData = '';
    $date = new \DateTime();
    $today = $date->format('d-m-Y');
    $oneYear = date('d-m-Y', strtotime('+1 years'));
    
    //Validate R2 or R3
    if($json['branch'] == 'R2'){
        $products = \App\product_channel::selectRaw('products_channel.*, pro.productodes')
                ->join('products as pro', 'pro.id', '=', 'products_channel.product_id')
                ->where('products_channel.agency_id', '=', $agency->id)
    //                                            ->where('products_channel.channel_id', '=', $agency->channel_id)
                ->whereIn('pro.ramoid', array(1, 2))
                ->get();
    }else{
        $products = \App\product_channel::selectRaw('products_channel.*, pro.productodes')
                ->join('products as pro', 'pro.id', '=', 'products_channel.product_id')
                ->where('products_channel.agency_id', '=', $agency->id)
    //                                            ->where('products_channel.channel_id', '=', $agency->channel_id)
                ->whereIn('pro.ramoid', array(4))
                ->get();
    }
    if ($products->isEmpty()) {
        $response = ["productError" => 'Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo']; array_push($returnArray, $response);
        array_push($returnArray, $response);
        return $returnArray;
    } else {
        foreach ($products as $pro) {
            $productsChannel = \App\product_channel::where('canal_plan_id', '=', $pro->canal_plan_id)->get();
            $agencySS = \App\agencia_ss::find($productsChannel[0]->agency_ss_id);
            $prima = 0;
            $contribucion = 0;
            $sCam = 0;
            $derEmision = 0;
            $subSinIva = 0;
            $subConIva = 0;
            $iva = 0;
            $total = 0;

            //Consulta Prima por cada vehiculo
            $result = calculoPrimaR2($pro->canal_plan_id, $today, $oneYear);

            if ($result['error'][0]['code'] != '000') {
                continue;
            } else {
                foreach ($result['rubrofactura']['rubros'] as $a) {
                    if ($a['rubro'] == 'PRIMA NETA') {
                        $prima += $a['valor'];
                    }
                    if ($a['rubro'] == 'CONTRIBUCIÓN') {
                        $contribucion += $a['valor'];
                    }
                    if ($a['rubro'] == 'S. SOCIAL CAMPESINO') {
                        $sCam += $a['valor'];
                    }
                    if ($a['rubro'] == 'DERECHO DE EMISION') {
                        $derEmision += $a['valor'];
                    }
                    if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '0.00%') {
                        $subSinIva += $a['valor'];
                    }
                    if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '12.00%') {
                        $subConIva += $a['valor'];
                    }
                    if ($a['rubro'] == 'I.V.A') {
                        $iva += $a['valor'];
                    }
                    if ($a['rubro'] == 'TOTAL') {
                        $total += $a['valor'];
                    }
                    $count_products++;
                }
                
                $response = [
                    "productName" => $pro->productodes,
                    "productPrice" => number_format((float)$prima, 2, '.', ''),
                    "productId" => $pro->product_id,
                    "productCanalPlanId" => $pro->canal_plan_id
                ];
                array_push($returnArray, $response);
            }
        }
    }
    if ($returnArray == null) {
//    if (1 == 1) {
        $response = ["productError" => 'Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo']; array_push($returnArray, $response);
        
        return $returnArray;
    } else {
        return $returnArray;
    }

    return $returnData;
}

function R4CheckPrice($json) {
    $returnArray = array();
    set_time_limit(300);
    $user = App\User::find($json['userId']);
    $agency = \App\Agency::find($user->agen_id);

    $returnData = '';
    $date = new \DateTime();
    $today = $date->format('d-m-Y');
    $oneYear = date('d-m-Y', strtotime('+1 years'));

    $products = \App\product_channel::selectRaw('products_channel.*, pro.productodes')
            ->join('products as pro', 'pro.id', '=', 'products_channel.product_id')
            ->where('products_channel.status_id', '=', '1')
            ->where('products_channel.agency_id', '=', $agency->id)
//                                            ->where('products_channel.channel_id', '=', $agency->channel_id)
            ->where('products_channel.status_id', '=', '1')
            ->whereIn('pro.ramoid', ['40', '5'])
            ->get();
//        dd($products);
    if ($products->isEmpty()) {
        $response = ["productError" => 'Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo'];
        array_push($returnArray, $response);
        array_push($returnArray, $response);
        return $returnArray;
    } else {
        foreach ($products as $pro) {
            $productsChannel = \App\product_channel::where('canal_plan_id', '=', $pro->canal_plan_id)->get();
            $agencySS = \App\agencia_ss::find($productsChannel[0]->agency_ss_id);
            $prima = 0;
            $contribucion = 0;
            $sCam = 0;
            $derEmision = 0;
            $subSinIva = 0;
            $subConIva = 0;
            $iva = 0;
            $total = 0;

            //Consultar la Cobertura Principal con el valor asegurado enviado por el usuario. 
            $rubroCod = \App\products_rubros::where('description', '=', $json['rubro'])->where('product_id', '=', $pro->product_id)->get();

            //Consulta Prima por cada asegurado para accidente personal
            $result = calculoPrimaR4($pro->canal_plan_id, $rubroCod[0]->cod, str_replace(',', '', $json['value']), $today, $oneYear);

            //Obtener la prima
            foreach ($result['rubrofactura']['rubros'] as $a) {
                if ($a['rubro'] == 'PRIMA NETA') {
                    $prima += $a['valor'];
                }
            }

            //Consulta Prima por los rubros adicionales
            $rubroAdd = \App\products_rubros::where('cod', '!=', $rubroCod[0]->cod)->where('product_id', '=', $pro->product_id)->get();

            //Sumar las primas
            foreach ($rubroAdd as $r) {
                $result = calculoPrimaR4($pro->canal_plan_id, $r->cod, $r->value, $today, $oneYear);

                foreach ($result['rubrofactura']['rubros'] as $a) {
                    if ($a['rubro'] == 'PRIMA NETA') {
                        $prima += $a['valor'];
                    }
                }
            }

            //Consultar las coberturas que sumen valor de prima
            $coverages = \App\coverage::where('plandetindspri', '=', 'S')->where('product_id', '=', $pro->product_id)->get();

            //Sumar las primas
            foreach ($coverages as $c) {
                $prima += $c->plandetprima;
            }

//                $costoSeguro = c

            $rate = $result['rubrofactura']['tasa'];

            $coverage = \App\coverage::where('product_id', '=', $pro->product_id)->where('plandetindvis', '=', 'S')->where('coberturades', '!=', NULL)->skip(0)->take(4)->get();

            $agent = \App\agent_ss::find($productsChannel[0]->agent_ss);

            $response = [
                "productName" => $pro->productodes,
                "productPrice" => number_format((float) $prima, 2, '.', ''),
                "productId" => $pro->product_id,
                "productCanalPlanId" => $pro->canal_plan_id
            ];
            array_push($returnArray, $response);
        }
    }
    if ($returnArray == null) {
//    if (1 == 1) {
        $response = ["productError" => 'Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo'];
        array_push($returnArray, $response);

        return $returnArray;
    } else {
        return $returnArray;
    }

    return $returnData;
}

function r4CheckRubros($id){
    $user = App\User::find($id);
    $rubros = DB::select('select DISTINCT(pr.cod), pr.description, pr.value, pr.max_value, pr.min_value from products_rubros pr inner join products p on p.id = pr.product_id where pr.status_id = 1 AND pr.value = 0 AND p.agency_id ="' . $user->agen_id . '"');
    return $rubros;
}
