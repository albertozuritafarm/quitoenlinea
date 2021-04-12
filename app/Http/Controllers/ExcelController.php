<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use DB;

class ExcelController extends Controller {

    public function salesReports(request $request) {
        \Excel::create('Users', function($excel) {

            $query = 'select
                    cou.name as "country",
                    pro.name as "province",
                    cit.name as "city",
                    IF(agen.name = "TODOS", "Magnus", agen.name) as "agency",
                    IF(chan.name = "TODOS", "Magnus", chan.name) as "channel",
                    sType.name as "Tipo de Venta",
                    concat(usr.first_name," ",usr.last_name) as "adviser",
                    sta.name as "status",
                    count(distinct vSal.vehicule_id) as "vehiculos",
                    count(distinct vSal.sales_id) as "sales count",
                    round(sum(sal.total),2) as "sales total"
                    from sales sal
                    join users usr on usr.id = sal.user_id
                    join cities cit on cit.id = usr.city_id
                    join provinces pro on pro.id = cit.province_id
                    join countries cou on cou.id = pro.country_id
                    join agencies agen on agen.id = usr.agen_id
                    join channels chan on chan.id = agen.channel_id
                    join sales_type sType on sType.id = sal.sales_type_id
                    join status sta on sta.id = sal.status_id
                    join vehicles_sales vSal on vSal.sales_id = sal.id
                    where sal.sales_type_id = 1 group by sal.status_id
                    union
                    select
                    cou.name as "country",
                    pro.name as "province",
                    cit.name as "city",
                    IF(agen.name = "TODOS", "Magnus", agen.name) as "agency",
                    IF(chan.name = "TODOS", "Magnus", chan.name) as "channel",
                    sType.name as "Tipo de Venta",
                    concat(usr.first_name," ",usr.last_name) as "adviser",
                    mType.name as "status",
                    count(vSal.vehicule_id) as "vehiculos",
                    count(distinct mas.id) as "sales count",
                    round(sum(sal.total),2) as "sales total"
                    from massives mas
                    join massives_sales mSal on mSal.massives_id = mas.id
                    join sales sal on sal.id = mSal.sales_id
                    join users usr on usr.id = sal.user_id
                    join cities cit on cit.id = usr.city_id
                    join provinces pro on pro.id = cit.province_id
                    join countries cou on cou.id = pro.country_id
                    join agencies agen on agen.id = usr.agen_id
                    join channels chan on chan.id = agen.channel_id
                    join sales_type sType on sType.id = sal.sales_type_id
                    join massive_type mType on mType.id = mas.massive_type_id
                    join vehicles_sales vSal on vSal.sales_id = sal.id
                    where sal.sales_type_id = 2 group by mas.massive_type_id';
            
            $data = DB::select($query);

            $excel->sheet('Venta', function($sheet) use($data) {

                $sheet->fromArray($data);
            });
        })->export('xlsx');
    }

}
