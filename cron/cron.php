<?php

    //Query
        $query = 'SELECT * FROM benefits where end_date < NOW()';
        $result = DB::select($query);
        if($result){
           echo $result;
        }else{
            echo 'false';
        }
