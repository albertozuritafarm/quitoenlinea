<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    public function redirect($short){
        $shortUrl = \App\short_url::where('short','=',$short)->get();
        if($shortUrl->isEmpty()){
            return 'error';
        }else{
            return redirect($shortUrl[0]->url);
        }
    }
}
