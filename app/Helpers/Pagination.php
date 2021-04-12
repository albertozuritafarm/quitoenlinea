<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function benefitsPagination($channelForm, $beginDate, $endDate, $statusForm, $items, $userRol, $channelQueryForm) {
    $newBenefits = \App\benefits::selectRaw('benefits.*, 
                                            DATE_FORMAT(benefits.begin_date,"%d-%m-%Y") as "beginDate",
                                            DATE_FORMAT(benefits.end_date,"%d-%m-%Y") as "endDate",
                                            IF(channels.name = "Todos", "Magnus", channels.name) as "channel", 
                                            status.name as "status"')
            ->join('channels', 'channels.id', '=', 'benefits.channels_id')
            ->join('status', 'status.id', '=', 'benefits.status_id')
            ->whereRaw('benefits.channels_id is not null')
            ->when($channelForm != null, function ($newBenefits) use ($channelForm) {
                return $newBenefits->where('channels.id', $channelForm);
            })
            ->when($beginDate != null, function ($newBenefits) use ($beginDate, $endDate) {
                return $newBenefits->whereRaw('DATE_FORMAT(benefits.begin_date,"%Y-%m-%d") between "' . $beginDate . '" and "' . $endDate . '"');
            })
            ->when($statusForm != null, function ($newBenefits) use ($statusForm) {
                return $newBenefits->where('status.id', $statusForm);
            })
            ->when($userRol != null, function ($newBenefits) use ($channelQueryForm) {
                return $newBenefits->whereRaw($channelQueryForm);
            })
            ->orderBy('benefits.id', 'DESC')
            ->paginate($items);

    return $newBenefits;
}

function benefisSecondaryPagination($plate, $beginDate, $endDate, $firstName, $lastName, $document, $items, $userRol, $channelQueryForm) {
    $newBenefits = \App\benefits::selectRaw('bVSal.id, 
                                            vehi.plate as "plate",
                                            benefits.name as "name",
                                            DATE_FORMAT(bVSal.date, "%d-%m-%Y") as "date",
                                            concat(cus.last_name," ",cus.first_name) as "customer",
                                            sal.cus_mobile_phone as "phone"')
            ->join('benefits_vehicles_sales as bvehi', 'bvehi.benefits_id', '=', 'benefits.id')
            ->join('benefits_vehicles_sales_uses as bVSal', 'bVSal.benefits_vsal_id', '=', 'bvehi.id')
            ->join('vehicles_sales as vsal', 'vsal.id', '=', 'bvehi.vsal_id')
            ->join('sales as sal', 'sal.id', '=', 'vsal.sales_id')
            ->join('customers as cus', 'cus.id', '=', 'sal.customer_id')
            ->join('vehicles as vehi', 'vehi.id', '=', 'vsal.vehicule_id')
            ->join('channels as cha', 'cha.id', '=', 'benefits.channels_id')
            ->join('agencies as age', 'age.channel_id', '=', 'cha.id')
            ->join('users as usr', 'usr.id', '=', 'bVSal.user_id')
            ->when($plate != null, function ($newBenefits) use ($plate) {
                return $newBenefits->where('vehi.plate', 'like', '%' . $plate . '%');
            })
            ->when($beginDate != null, function ($newBenefits) use ($beginDate, $endDate) {
                return $newBenefits->whereRaw('DATE_FORMAT(bVSal.date,"%Y-%m-%d") between "' . $beginDate . '" and "' . $endDate . '"');
            })
            ->when($firstName != null, function ($newBenefits) use ($firstName) {
                return $newBenefits->where('cus.first_name', 'like', '%' . $firstName . '%');
            })
            ->when($lastName != null, function ($newBenefits) use ($lastName) {
                return $newBenefits->where('cus.last_name', 'like', '%' . $lastName . '%');
            })
            ->when($document != null, function ($newBenefits) use ($document) {
                return $newBenefits->where('cus.document', $document);
            })
            ->when($userRol != null, function ($newBenefits) use ($channelQueryForm) {
                return $newBenefits->whereRaw($channelQueryForm);
            })
            ->groupBy('bVSal.id')
            ->paginate($items);

    return $newBenefits;
}

function individual($policyNumber, $customer, $document, $plate, $dateFrom, $dateUntil, $saleId, $adviser, $statusForm, $userRol, $userQueryForm, $salesMovementsForm, $items, $userSucre, $userSucreQuery) {
    $newSales = \App\sales::selectRaw('sales.id as "salesId",
                                    concat(customers.first_name," ",customers.last_name) as "customer",
                                    customers.document as "document",
                                    customers.id as "cusId",
                                    sales.total as "total",
                                    DATE_FORMAT(sales.emission_date,"%d-%m-%Y") as "date",
                                    DATE_FORMAT(sales.begin_date,"%d-%m-%Y") as "beginDate",
                                    DATE_FORMAT(sales.end_date,"%d-%m-%Y") as "endDate",
                                    status.name as "status",
                                    sales.status_id as "status_id",
                                    status.name as "statuSal",
                                    sales.poliza as "poliza",
                                    products.ramoid as "proRamoid",
                                    products.name as "proName",
                                    products.ramodes as "proSegment",
                                    sales_movements.name as "movName",
                                    sales.number_insurance_policy as "numPolicy",
                                    sales.has_been_renewed as "hasRenew",
                                    charges.id as "chargesId",
                                    vehicles.new_vehicle as "newVehicle",
                                    IF(sales.allow_cancel_date is not null, IF(NOW()<=sales.allow_cancel_date, "NO", "YES"),"NO") as "allow_cancel"')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->join('status', 'status.id', '=', 'sales.status_id')
            ->leftJoin('vehicles_sales', 'vehicles_sales.sales_id', '=', 'sales.id')
            ->leftJoin('vehicles', 'vehicles.id', '=', 'vehicles_sales.vehicule_id')
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->join('agencies', 'agencies.id', '=', 'sales.agen_id')
            ->join('products_channel', 'products_channel.id', '=', 'sales.pbc_id')
            ->join('channels', 'channels.id', '=', 'products_channel.channel_id')
            ->join('products', 'products.id', '=', 'products_channel.product_id')
            ->leftJoin('sales_movements', 'sales_movements.id', '=', 'sales.sales_movements_id')
            ->leftJoin('charges', 'charges.sales_id', '=', 'sales.id')
            ->whereIn('sales.sales_type_id', array(1, 3))
            ->when($customer != null, function ($newSales) use ($customer) {
                return $newSales->whereRaw('CONCAT(customers.first_name," ",customers.last_name) like "%'.$customer.'%"');
            })
            ->when($document != null, function ($newSales) use ($document) {
                return $newSales->where('customers.document', $document);
            })
            ->when($plate != null, function ($newSales) use ($plate) {
                return $newSales->where('vehicles.plate', $plate);
            })
            ->when($dateFrom != null, function ($newSales) use ($dateFrom, $dateUntil) {
                return $newSales->whereRaw('DATE_FORMAT(sales.emission_date, "%m/%d/%Y") BETWEEN "'.$dateFrom.'" AND "'.$dateUntil.'"');
            })
            ->when($policyNumber != null, function ($newSales) use ($policyNumber) {
                return $newSales->where('sales.poliza', $policyNumber);
            })
            ->when($saleId != null, function ($newSales) use ($saleId) {
                return $newSales->where('sales.id', $saleId);
            })
            ->when($adviser != null, function ($newSales) use ($adviser) {
                return $newSales->where('users.id', $adviser);
            })
            ->when($statusForm != null, function ($newSales) use ($statusForm) {
                return $newSales->where('status.id', $statusForm);
            })
            ->when($userRol != null, function ($newSales) use ($userQueryForm) {
                return $newSales->whereRaw($userQueryForm);
            })
            ->when($salesMovementsForm != null, function ($newSales) use ($salesMovementsForm) {
                return $newSales->where('sales.sales_movements_id', $salesMovementsForm);
            })
            ->when($userSucre != null, function ($newSales) use ($userSucreQuery) {
                return $newSales->whereRaw($userSucreQuery);
            })
            ->groupBy('sales.id')
            ->orderBy('sales.id', 'DESC')
            ->paginate($items);
     
    return $newSales;
}

function massivesVinculation($customer, $document, $plate, $dateFrom, $dateUntil,  $updateDateFrom, $updateDateUntil,$saleId, $adviser, $statusForm, $userRol, $userQueryForm, $salesMovementsForm, $items, $userSucre, $userSucreQuery,$channel,$nameBusiness) {
    $newSales = \App\sales::selectRaw('sales.id as "salesId",
                                    concat(customers.first_name," ",customers.last_name) as "customer",
                                    concat(rep.first_name," ",rep.last_name) as "rep",
                                    rep.document as "documentRep",
                                    customers.document as "document",
                                    customers.id as "cusId",
                                    sales.total as "total",
                                    DATE_FORMAT(sales.emission_date,"%d-%m-%Y") as "date",
                                    DATE_FORMAT(sales.created_at,"%d-%m-%Y") as "beginDate",
                                    DATE_FORMAT(sales.updated_at,"%d-%m-%Y") as "endDate",
                                    DATE_FORMAT(vinculation_form.updated_at,"%d-%m-%Y") as "updateDate",
                                    status.name as "status",
                                    vinculation_form.url as "vinculation_form",
                                    agencies.channel_id as "agencies",
                                    status.id as "status_id",
                                    status.name as "statuSal",
                                    sales.poliza as "poliza",
                                    products.ramoid as "proRamoid",
                                    products.name as "proName",
                                    products.ramodes as "proSegment",
                                    sales_movements.name as "movName",
                                    sales.number_insurance_policy as "numPolicy",
                                    sales.has_been_renewed as "hasRenew",
                                    charges.id as "chargesId",
                                    agent_ss.agentedes as "agentedes",
                                    channels.canalnegodes as "canalnegodes",
                                    IF(sales.allow_cancel_date is not null, IF(NOW()<=sales.allow_cancel_date, "NO", "YES"),"NO") as "allow_cancel"')
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->leftJoin('vinculation_form', 'sales.id', '=', 'vinculation_form.sales_id')
            ->leftJoin('status', 'status.id', '=', 'sales.status_id')
            ->leftJoin('status as status2', 'status2.id', '=', 'vinculation_form.status_id')
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->leftJoin('agencies', 'agencies.id', '=', 'sales.agen_id')
            ->leftJoin('products_channel', 'products_channel.id', '=', 'sales.pbc_id')
            ->leftJoin('agent_ss', 'agent_ss.id', '=', 'products_channel.agent_ss')
            ->leftJoin('channels', 'channels.id', '=', 'agencies.channel_id')
            ->leftJoin('products', 'products.id', '=', 'products_channel.product_id')
            ->leftJoin('sales_movements', 'sales_movements.id', '=', 'sales.sales_movements_id')
            ->leftJoin('charges', 'charges.sales_id', '=', 'sales.id')
            ->leftJoin('customer_legal_representative as rep', 'rep.id', '=', 'vinculation_form.customer_legal_representative_id')
            ->whereIn('sales.sales_type_id', array(7))
            ->when($customer != null, function ($newSales) use ($customer) {
                return $newSales->whereRaw('CONCAT(customers.first_name," ",customers.last_name) like "%'.$customer.'%"');
            })
            ->when($document != null, function ($newSales) use ($document) {
                return $newSales->where('customers.document', $document);
            })
            ->when($dateFrom != null, function ($newSales) use ($dateFrom,$dateUntil) {
                return $newSales->whereRaw('DATE_FORMAT(sales.created_at, "%Y-%m-%d") BETWEEN "'.$dateFrom.'" AND "'.$dateUntil.'"');
            })
            ->when($updateDateFrom != null, function ($newSales) use ($updateDateFrom, $updateDateUntil) {
                return $newSales->whereRaw('DATE_FORMAT(vinculation_form.updated_at, "%Y-%m-%d") BETWEEN "'.$updateDateFrom.'" AND "'.$updateDateUntil.'"');
            })
            ->when($statusForm != null, function ($newSales) use ($statusForm) {
                return $newSales->where('status.id', $statusForm);
            })
            ->when($channel != null, function ($newSales) use ($channel) {
                return $newSales->where('channels.id', $channel);
            })
            ->when($nameBusiness != null, function ($newSales) use ($nameBusiness) {
                return $newSales->where('agent_ss.id',$nameBusiness);
            })
            ->when($userRol != null, function ($newSales) use ($userQueryForm) {
                return $newSales->whereRaw($userQueryForm);
            })
            ->when($userSucre != null, function ($newSales) use ($userSucreQuery) {
                return $newSales->whereRaw($userSucreQuery);
            })
            ->groupBy('sales.id')
            ->orderBy('sales.id', 'DESC')
            ->paginate($items);
     
    return $newSales;
}

function massivesFirstView($channelForm, $beginDate, $endDate, $type, $statusMassiveForm, $statusPayment, $items, $userRol, $channel) {
    $newMassives = \App\massives::selectRaw('massives.id as "id",  
                                        massives.total as "total", 
                                        count(vehicles_sales.id) as "cantidad", 
                                        channels.name as "canal",
                                        sta.name as "estadoMasivo",
                                        sta2.name as "estadoCobro",
                                        massive_type.name as "tipo",
                                        DATE_FORMAT(massives.upload_date, "%d-%m-%Y") as "fecha" ')
            ->leftJoin('massives_sales', 'massives_sales.massives_id', '=', 'massives.id')
            ->leftJoin('sales', 'sales.id', '=', 'massives_sales.sales_id')
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->leftJoin('agencies', 'agencies.id', '=', 'massives.agencies_id')
            ->leftJoin('channels', 'channels.id', '=', 'agencies.channel_id')
            ->leftJoin('status as sta', 'sta.id', '=', 'sales.status_id')
            ->leftJoin('status as sta2', 'sta2.id', '=', 'massives.status_charge_id')
            ->leftJoin('massive_type', 'massive_type.id', '=', 'massives.massive_type_id')
            ->leftJoin('vehicles_sales', 'vehicles_sales.sales_id', '=', 'sales.id')
            ->leftJoin('vehicles', 'vehicles.id', '=', 'vehicles_sales.vehicule_id')
            ->when($channelForm != null, function ($newMassives) use ($channelForm) {
                return $newMassives->where('channels.id', $channelForm);
            })
            ->when($beginDate != null, function ($newMassives) use ($beginDate, $endDate) {
                return $newMassives->whereRaw('DATE_FORMAT(massives.upload_date,"%Y-%m-%d") between "' . $beginDate . '" and "' . $endDate . '"');
            })
            ->when($type != null, function ($newMassives) use ($type) {
                return $newMassives->where('massive_type.id', $type);
            })
            ->when($statusMassiveForm != null, function ($newMassives) use ($statusMassiveForm) {
                return $newMassives->where('sales.status_id', $statusMassiveForm);
            })
            ->when($statusPayment != null, function ($newMassives) use ($statusPayment) {
                return $newMassives->where('massives.status_charge_id', $statusPayment);
            })
            ->when($userRol != null, function ($newScheduling) use ($channel) {
                return $newScheduling->where('channels.id', $channel[0]->id);
            })
            ->groupBy('massives.id')
            ->orderBy('massives.id', 'DESC')
            ->paginate($items);
    return $newMassives;
}

function massivesSecondary($channelForm, $beginDate, $endDate, $type, $statusMassiveForm, $statusPayment, $plate, $sale, $items, $userRol, $channel, $massive, $cusName, $cusDoc) {
    $newMassives = \App\sales::selectRaw('sales.id as "salId",
                                                massives.id as "massId",
                                                channels.name as "chanName",
                                                customers.document as "cusDocument",
                                                concat(customers.first_name," ",customers.last_name) as "cusName",
                                                sales.total as "salTotal", 
                                                DATE_FORMAT(massives.upload_date,"%d-%m-%Y") as "salDate", 
                                                sta.name as "salStatus", 
                                                sta2.name as "massStatus",
                                                massive_type.name as "tipo",
                                                massives.upload_file as "upload_file",
                                                count(vehicles_sales.id) as "count"')
            ->join('massives_sales', 'massives_sales.sales_id', '=', 'sales.id')
            ->join('massives', 'massives.id', '=', 'massives_sales.massives_id')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->join('agencies', 'agencies.id', '=', 'sales.agen_id')
            ->join('channels', 'channels.id', '=', 'agencies.channel_id')
            ->join('status as sta', 'sta.id', '=', 'sales.status_id')
            ->join('status as sta2', 'sta2.id', '=', 'massives.status_charge_id')
            ->join('massive_type', 'massive_type.id', '=', 'massives.massive_type_id')
            ->leftJoin('vehicles_sales', 'vehicles_sales.id', '=', 'sales.id')
            ->leftJoin('vehicles', 'vehicles.id', '=', 'vehicles_sales.vehicule_id')
            ->whereIn('sales.sales_type_id', array(2, 4, 5))
            ->when($channelForm != null, function ($newMassives) use ($channelForm) {
                return $newMassives->where('channels.id', $channelForm);
            })
            ->when($beginDate != null, function ($newMassives) use ($beginDate, $endDate) {
                return $newMassives->whereRaw('DATE_FORMAT(massives.upload_date,"%Y-%m-%d") between "' . $beginDate . '" and "' . $endDate . '"');
            })
            ->when($type != null, function ($newMassives) use ($type) {
                return $newMassives->where('massive_type.id', $type);
            })
            ->when($statusMassiveForm != null, function ($newMassives) use ($statusMassiveForm) {
                return $newMassives->where('sales.status_id', $statusMassiveForm);
            })
            ->when($statusPayment != null, function ($newMassives) use ($statusPayment) {
                return $newMassives->where('massives.status_charge_id', $statusPayment);
            })
            ->when($plate != null, function ($newMassives) use ($plate) {
                return $newMassives->where('vehicles.plate', $plate);
            })
            ->when($sale != null, function ($newMassives) use ($sale) {
                return $newMassives->where('sales.id', $sale);
            })
            ->when($massive != null, function ($newMassives) use ($massive) {
                return $newMassives->where('massives.id', $massive);
            })
            ->when($cusName != null, function ($newMassives) use ($cusName) {
                return $newMassives->whereRaw('customers.first_name like "%' . $cusName . '%" OR customers.last_name like "%' . $cusName . '%"');
            })
            ->when($cusDoc != null, function ($newMassives) use ($cusDoc) {
                return $newMassives->where('customers.document', $cusDoc);
            })
            ->when($userRol != null, function ($newScheduling) use ($channel) {
                return $newScheduling->where('channels.id', $channel[0]->id);
            })
            ->groupBy('sales.id')
            ->groupBy('massives_sales.id')
            ->orderBy('massives.id', 'DESC')
            ->orderBy('sales.id', 'DESC')
            ->paginate($items);

    return $newMassives;
}

function scheduling($plate, $beginDate, $endDate, $firstName, $lastName, $document, $statusForm, $items, $userRol, $channelForm) {
    $newScheduling = \App\schedulingDetails::selectRaw('vehicles.plate as "plate",
                                        damage_type.name as "damage",
                                        scheduling_details.address as "location",
                                        DATE_FORMAT(scheduling_details.begin_date,"%d-%m-%Y %H:%i:%s") as "beginDate",
                                        DATE_FORMAT(scheduling_details.end_date,"%d-%m-%Y %H:%i:%s") as "endDate",
                                        scheduling_details.id as "detaId",
                                        scheduling.id as "scheId",
                                        scheduling_details.status_id as "status",
                                        status.name as "statusName",  
                                        scheduling_details.estimated_time as "time",
                                        scheduling_details.confirm_file as "file"')
            ->join('scheduling', 'scheduling.id', '=', 'scheduling_details.scheduling_id')
            ->join('vehicles_sales', 'vehicles_sales.id', '=', 'scheduling.vehicles_sales_id')
            ->join('sales', 'sales.id', '=', 'vehicles_sales.sales_id')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->join('vehicles', 'vehicles.id', '=', 'vehicles_sales.vehicule_id')
            ->join('damage_type', 'damage_type.id', '=', 'scheduling_details.damage_type_id')
            ->join('status', 'status.id', '=', 'scheduling_details.status_id')
            ->join('users', 'users.id', '=', 'scheduling.user_id')
            ->join('agencies', 'agencies.id', '=', 'users.agen_id')
            ->join('channels', 'channels.id', '=', 'agencies.channel_id')
            ->when($plate != null, function ($newScheduling) use ($plate) {
                return $newScheduling->where('vehicles.plate', $plate);
            })
            ->when($beginDate != null, function ($newScheduling) use ($beginDate, $endDate) {
                return $newScheduling->whereRaw('DATE_FORMAT(scheduling_details.begin_date,"%Y-%m-%d") between "' . $beginDate . '" and "' . $endDate . '"');
            })
            ->when($firstName != null, function ($newScheduling) use ($firstName) {
                return $newScheduling->where('customers.first_name', $firstName);
            })
            ->when($lastName != null, function ($newScheduling) use ($lastName) {
                return $newScheduling->where('customers.last_name', $lastName);
            })
            ->when($document != null, function ($newScheduling) use ($document) {
                return $newScheduling->where('customers.document', $document);
            })
            ->when($statusForm != null, function ($newScheduling) use ($statusForm) {
                return $newScheduling->where('status.id', $statusForm);
            })
            ->when($userRol != null, function ($newScheduling) use ($channelForm) {
                return $newScheduling->where('channels.id', $channelForm[0]->id);
            })
            ->orderBy('scheduling_details.id', 'DESC')
            ->paginate($items);

    return $newScheduling;
}

function user($firstName, $lastName, $document, $email, $rol, $channelForm, $agencyForm, $items, $sqlUser) {
    $newUsers = \App\User::selectRaw('users.*, 
                                    cha.canalnegodes as "channel", 
                                    sta.name as "estado", 
                                    typeUser.name as "typUser",
                                    doc.name as "documento"')
            ->join('agencies as age', 'age.id', '=', 'users.agen_id')
            ->join('channels as cha', 'cha.id', '=', 'age.channel_id')
            ->join('status as sta', 'sta.id', '=', 'users.status_id')
            ->join('documents as doc', 'doc.id', '=', 'users.document_id')
            ->leftJoin('type_user_sucre as typeUser','typeUser.id','=','users.type_user_sucre_id')
            ->where('users.email','!=','middleware')
            ->when($firstName != null, function ($newUsers) use ($firstName) {
                return $newUsers->where('users.first_name','like', '%'.$firstName.'%');
            })
            ->when($lastName != null, function ($newUsers) use ($lastName) {
                return $newUsers->where('users.last_name', 'like','%'.$lastName.'%');
            })
            ->when($document != null, function ($newUsers) use ($document) {
                return $newUsers->where('users.document', $document);
            })
            ->when($email != null, function ($newUsers) use ($email) {
                return $newUsers->where('users.email', $email);
            })
            ->when($rol != null, function ($newUsers) use ($rol) {
                return $newUsers->where('users.role_id', $rol);
            })
            ->when($channelForm != null, function ($newUsers) use ($channelForm) {
                return $newUsers->where('cha.id', $channelForm);
            })
            ->when($agencyForm != null, function ($newUsers) use ($agencyForm) {
                return $newUsers->where('age.id', $agencyForm);
            })
            ->whereRaw($sqlUser)
            ->orderBy('users.id','DESC')
            ->paginate($items);
    return $newUsers;
}

function charges($document, $date, $saleId, $salesType, $chargesType, $statusForm, $userRol, $userRolQuery, $items, $userSucre, $userSucreQuery) {
//    DB::enableQueryLog();
    $newCharges = \App\Charge::selectRaw('DISTINCT(charges.id) as "id",
                                    charges.sales_id as "salId",
                                    cus.document as "document",
                                    DATE_FORMAT(charges.date, "%d-%m-%Y") as "date",
                                    charges.value as "value",
                                    sta2.name as "status",
                                    sta2.id as "statusId",
                                    sal.id as "salId",
                                    typ.name as "type",
                                    IF(charges.date >= DATE_SUB(CURDATE(), INTERVAL 1 DAY), "true", "false") as "cancel",
                                    ctype.name as "typeCharge"')
            ->join('customers as cus', 'cus.id', '=', 'charges.customers_id')
            ->join('status as sta', 'sta.id', '=', 'charges.status_id')
            ->leftJoin('payments as pay', 'pay.id', '=', 'charges.payments_id')
            ->leftJoin('payments_types as typ', 'typ.id', '=', 'pay.payment_type_id')
            ->join('sales as sal', 'sal.id', '=', 'charges.sales_id')
            ->join('status as sta2', 'sta2.id', '=', 'sal.status_id')
            ->join('products_channel as pbc', 'pbc.id', '=', 'sal.pbc_id')
            ->join('channels as chan', 'chan.id', '=', 'pbc.channel_id')
            ->join('agencies', 'agencies.id', '=', 'sal.agen_id')
            ->join('charges_types as ctype', 'ctype.id', '=', 'charges.types_id')
            ->join('datafast_log as dt','dt.id_cart','=','sal.id')
            ->whereNotIn('sal.status_id', array(11, 10))
            ->where('dt.code','=','000.100.112')
            ->where('sal.sales_type_id', '1')
            ->when($document != null, function ($newCharges) use ($document) {
                return $newCharges->where('cus.document', $document);
            })
            ->when($date != null, function ($newCharges) use ($date) {
                return $newCharges->whereRaw('DATE_FORMAT(charges.date,"%Y-%m-%d") = "' . $date . '"');
            })
            ->when($saleId != null, function ($newCharges) use ($saleId) {
                return $newCharges->where('sal.id', $saleId);
            })
            ->when($salesType != null, function ($newCharges) use ($salesType) {
                return $newCharges->where('typ.id', $salesType);
            })
            ->when($chargesType != null, function ($newCharges) use ($chargesType) {
                return $newCharges->where('ctype.id', $chargesType);
            })
            ->when($statusForm != null, function ($newCharges) use ($statusForm) {
                return $newCharges->where('sal.status_id', $statusForm);
            })
            ->when($userRol != null, function ($newCharges) use ($userRolQuery) {
                return $newCharges->whereRaw($userRolQuery);
            })
            ->when($userSucre != null, function ($newCharges) use ($userSucreQuery) {
                return $newCharges->whereRaw($userSucreQuery);
            })
            ->orderBy('charges.id', 'DESC')
            ->paginate($items);
//    dd(DB::getQueryLog()); 
    return $newCharges;
}

function channels($id, $name, $items, $userRol, $userQueryForm) {
    //CHANNELS OLD//
//    $channels = \App\channels::selectRaw('channels.id, channels.document, channels.name, cit.name as "city", channels.contact, channels.phone, channels.mobile_phone, channels_types.name as "typeName"')
//            ->join('cities  as cit', 'cit.id', '=', 'channels.city_id')
//            ->join('channels_types', 'channels_types.id', '=', 'channels.channel_type_id')
//            ->orderBy('channels.id', 'DESC')
//            ->when($userRol != null, function ($channels) use ($userQueryForm) {
//                return $channels->whereRaw($userQueryForm);
//            })
//            ->when($document != null, function ($channels) use ($document) {
//                return $channels->where('channels.document', '=', $document);
//            })
//            ->when($name != null, function ($channels) use ($name) {
//                return $channels->where('channels.name', '=', $name);
//            })
//            ->when($city != null, function ($channels) use ($city) {
//                return $channels->where('channels.city_id', '=', $city);
//            })
//            ->paginate($items);
    $channels = App\channels::selectRaw('channels.id, channels.canalnegodes, channels.canalnegoid')
                ->whereNotNull('channels.canalnegodes')
                ->when($id != null, function ($channels) use ($id) {
                    return $channels->where('channels.canalnegoid','=',$id);
                })
                ->when($name != null, function ($channels) use ($name) {
                    return $channels->where('channels.canalnegodes','LIKE','%'.$name.'%');
                })
                ->where('channels.status_id','=','1') 
                ->orderBy('channels.id','DESC')
                ->paginate($items);
    return $channels;
}

function agencies($items, $channel) {
//    $agencies = \App\Agency::selectRaw('agencies.puntodeventades, agencies.puntodeventaid')
//            ->when($channel != null, function ($agencies) use ($channel) {
//                return $agencies->where('agencies.channel_id', '=', $channel);
//            })
//            ->orderBy('agencies.id', 'DESC')
//            ->paginate($items);
//    DB::enableQueryLog(); 
    $agencies = \App\product_channel::selectRaw('agen.puntodeventaid as "agenId", agen.puntodeventades as "agenName", pro.productodes as "proName", agente.agentedes as "agenteName"')
                                        ->leftJoin('agencies as agen','agen.id','=','products_channel.agency_id')
                                        ->leftJoin('products as pro','pro.id','=','products_channel.product_id')
                                        ->leftJoin('agent_ss as agente','agente.id','=','products_channel.agent_ss')
                                        ->where('agen.channel_id','=',$channel)
                                        ->where('products_channel.status_id','=','1')
                                        ->paginate($items);
    
//    dd(DB::getQueryLog());
    return $agencies;
}

function providers($document, $name, $city, $items) {
    $providers = \App\providers::selectRaw('providers.id, providers.document, providers.name, providers.address, cit.name as "citName", providers.contact, providers.phone, providers.mobile_phone, providers.email')
            ->join('cities as cit', 'cit.id', '=', 'providers.city_id')
            ->when($document != null, function ($providers) use ($document) {
                return $providers->where('providers.document', '=', $document);
            })
            ->when($name != null, function ($providers) use ($name) {
                return $providers->where('providers.name', '=', $name);
            })
            ->when($city != null, function ($providers) use ($city) {
                return $providers->where('providers.city_id', '=', $city);
            })
            ->paginate($items);

    return $providers;
}

function providersBranch($items, $providersId) {
    $providersBranch = \App\providers_branch::selectRaw('providers_branch.id, providers_branch.document, providers_branch.name, providers_branch.address, cit.name as "citName", providers_branch.contact, providers_branch.phone, providers_branch.mobile_phone, providers_branch.email')
            ->join('cities as cit', 'cit.id', '=', 'providers_branch.city_id')
            ->where('providers_branch.providers_id', '=', $providersId)
            ->paginate($items);

    return $providersBranch;
}

function customers($document, $lastName, $firstName, $city, $items) {
    $customers = \App\customers::selectRaw('customers.id, customers.document, customers.first_name, customers.last_name, customers.address, cit.name as "citName", doc.name as "docName"')
            ->join('cities as cit', 'cit.id', '=', 'customers.city_id')
            ->join('documents as doc', 'doc.id', '=', 'customers.document_id')
            ->when($document != null, function ($customers) use ($document) {
                return $customers->where('customers.document', '=', $document);
            })
            ->when($lastName != null, function ($customers) use ($lastName) {
                return $customers->where('customers.last_name', '=', $lastName);
            })
            ->when($firstName != null, function ($customers) use ($firstName) {
                return $customers->where('customers.first_name', '=', $firstName);
            })
            ->when($city != null, function ($customers) use ($city) {
                return $customers->where('customers.city_id', '=', $city);
            })
            ->orderBy('customers.document', 'DESC')
            ->paginate($items);
    return $customers;
}

function tickets($number, $firstName, $lastName, $statusForm, $beginDate, $endDate, $menuForm, $ticketTypeForm, $adminUser, $items) {
    $tickets = App\ticket::selectRaw('ticket.id, DATE_FORMAT(ticket.begin_date,"%d-%m-%Y") as "beginDate", ticket.title, CONCAT(user.first_name," ",user.last_name) as "user", CONCAT(user2.first_name," ",user2.last_name) as "user2", sta.name as "status", deta.description as "description", menu.name as "menuName", type.name as "typeName"')
            ->join('ticket_detail as deta', 'deta.ticket_id', '=', 'ticket.id')
            ->join('status as sta', 'sta.id', '=', 'ticket.status_id')
            ->join('users as user', 'user.id', '=', 'ticket.request_user_id')
            ->join('users as user2', 'user2.id', '=', 'ticket.assign_user_id')
            ->join('menu', 'menu.id', '=', 'ticket.menu_id')
            ->join('ticket_type_detail', 'ticket_type_detail.id', '=', 'ticket.ticket_type_detail_id')
            ->join('ticket_type as type', 'type.id', '=', 'ticket_type_detail.ticket_type_id')
            ->when($number != null, function ($tickets) use ($number) {
                return $tickets->where('ticket.id', '=', $number);
            })
            ->when($firstName != null, function ($tickets) use ($firstName) {
                return $tickets->where('user.first_name', 'like', '%' . $firstName . '%');
            })
            ->when($lastName != null, function ($tickets) use ($lastName) {
                return $tickets->where('user.last_name', 'like', '%' . $lastName . '%');
            })
            ->when($statusForm != null, function ($tickets) use ($statusForm) {
                return $tickets->where('ticket.status_id', '=', $statusForm);
            })
            ->when($beginDate != null, function ($tickets) use ($beginDate, $endDate) {
                return $tickets->whereRaw('DATE_FORMAT(ticket.begin_date,"%Y-%m-%d") between "' . $beginDate . '" and "' . $endDate . '"');
            })
            ->when($menuForm != null, function ($tickets) use ($menuForm) {
                return $tickets->where('ticket.menu_id', '=', $menuForm);
            })
            ->when($menuForm != null, function ($tickets) use ($menuForm) {
                return $tickets->where('ticket.menu_id', '=', $menuForm);
            })
            ->when($ticketTypeForm != null, function ($tickets) use ($ticketTypeForm) {
                return $tickets->where('type.id', '=', $ticketTypeForm);
            })
            ->when($adminUser == false, function ($tickets) use ($adminUser) {
                return $tickets->where('ticket.request_user_id', '=', \Auth::user()->id);
            })
            ->orderBy('ticket.id', 'DESC')
            ->groupBy('ticket.id')
            ->paginate($items);
    return $tickets;
}

function ticketsDetail($ticket) {
    $ticketsDetail = App\ticket_detail::selectRaw('ticket_detail.id, ticket_detail.description, CONCAT(users.first_name," ",users.last_name) as "user", DATE_FORMAT(ticket_detail.date, "%d %b %Y %h:%i:%s %p") as "date", sta.name as "status", menu.name as "menuName", type.name as "typeName", ticket_detail.picture1, ticket_detail.picture2, ticket_detail.picture3, users.type_ticket_access')
            ->join('users', 'users.id', '=', 'ticket_detail.request_user_id')
            ->join('status as sta', 'sta.id', '=', 'ticket_detail.status_id')
            ->join('ticket', 'ticket.id', '=', 'ticket_detail.ticket_id')
            ->join('ticket_type_detail', 'ticket_type_detail.id', '=', 'ticket.ticket_type_detail_id')
            ->join('ticket_type as type', 'type.id', '=', 'ticket_type_detail.ticket_type_id')
            ->join('menu', 'menu.id', '=', 'ticket.menu_id')
            ->where('ticket_detail.ticket_id', '=', $ticket)
            ->orderBy('ticket_detail.id', 'DESC')
            ->get();
    return $ticketsDetail;
}

function inspections($salesId, $statusForm, $items, $userRol, $userQuery) {
    $inspections =  \App\inspection::selectRaw('inspection.inspector_updated, DATE_FORMAT(inspection.created_at, "%d-%m-%Y") as "dateCreated", inspection.id as "id",inspection.sales_id as "salesId",sta.name as "staName", sta.id as "staId", inspection.file as "file", products.ramodes as "proSegment", products.ramoid as "ramoId", sal.status_id as "salStaId", sal.id as "salId", sal.url_viamatica as "urlViamatica", sal.viamatica_id as "salViamaticaId", products_channel.ejecutivo_ss')
            ->leftJoin('status as sta', 'sta.id', '=', 'inspection.status_id')
            ->leftJoin('sales as sal', 'sal.id', '=', 'inspection.sales_id')
            ->leftJoin('products_channel', 'products_channel.id', '=', 'sal.pbc_id')
            ->leftJoin('products', 'products.id', '=', 'products_channel.product_id')
            ->when($salesId != null, function ($inspections) use ($salesId) {
                return $inspections->where('inspection.sales_id', $salesId);
            })
            ->when($statusForm != null, function ($inspections) use ($statusForm) {
                return $inspections->where('inspection.status_id', $statusForm);
            })
            ->when($userRol != null, function ($inspections) use ($userQuery) {
                return $inspections->whereRaw($userQuery);
            })
            ->orderBy('inspection.id', 'DESC')
            ->paginate($items);
    return $inspections;
}

function datafast($idCart, $order, $lot, $reference, $beginDate, $endDate, $authCode, $items, $userRol, $userQuery) {
    $datafast = \App\datafast_log::selectRaw('datafast_log.*')
                            ->join('sales as sal','sal.id','=','datafast_log.id_cart')
                            ->join('agencies as agen','agen.id','=','sal.agen_id')
                            ->join('channels as chan','chan.id','=','agen.channel_id')
                            ->join('products_channel as pbc','pbc.id','=','sal.pbc_id')
                            ->when($idCart != null, function ($datafast) use ($idCart) {
                                return $datafast->where('datafast_log.id_cart', $idCart);
                            })
                            ->when($order != null, function ($datafast) use ($order) {
                                return $datafast->where('datafast_log.order', $order);
                            })
                            ->when($lot != null, function ($datafast) use ($lot) {
                                return $datafast->where('datafast_log.lot', $lot);
                            })
                            ->when($reference != null, function ($datafast) use ($reference) {
                                return $datafast->where('datafast_log.reference', $reference);
                            })
                            ->when($beginDate != null, function ($datafast) use ($beginDate, $endDate) {
                                return $datafast->whereRaw('DATE_FORMAT(datafast_log.order_date,"%Y-%m-%d") between "' . $beginDate . '" and "' . $endDate . '"');
                            })
                            ->when($authCode != null, function ($datafast) use ($authCode) {
                                return $datafast->where('datafast_log.auth_code', $authCode);
                            })
                            ->when($userRol != null, function ($datafast) use ($userQuery) {
                                return $datafast->whereRaw($userQuery);
                            })
                            ->orderBy('datafast_log.id','DESC')
                            ->paginate($items);
    return $datafast;
}

function insurance($transId, $cusId, $firstName, $lastName, $statusId, $items){
    $insurances = \App\insurance::selectRaw('insurance.*, '
                                            . 'pro.name, '
                                            . 'sta.name as "status", '
                                            . 'cus.document as "cusDocument", '
                                            . 'CONCAT(cus.first_name," ",cus.last_name) as "customer"')
                                ->leftJoin('products_insurance as pro','pro.id','=','insurance.product_insurance_id')
                                ->leftJoin('customers as cus','cus.id','=','insurance.customer_id')
                                ->leftJoin('status as sta','sta.id','=','insurance.status_id')
                                ->when($transId != null, function ($insurances) use ($transId) {
                                    return $insurances->where('insurance.transaction_code', $transId);
                                })
                                ->when($cusId != null, function ($insurances) use ($cusId) {
                                    return $insurances->where('cus.document', $cusId);
                                })
                                ->when($firstName != null, function ($insurances) use ($firstName) {
                                    return $insurances->where('cus.first_name', 'like', '%' . $firstName . '%');
                                })
                                ->when($firstName != null, function ($insurances) use ($lastName) {
                                    return $insurances->where('cus.last_name', 'like', '%' . $lastName . '%');
                                })
                                ->when($statusId != null, function ($insurance) use ($statusId) {
                                    return $insurance->where('insurance.status_id', $statusId);
                                })
                                ->orderBy('insurance.id','DESC')
                                ->paginate($items);
        return $insurances;
}
