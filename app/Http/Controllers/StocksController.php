<?php

namespace App\Http\Controllers;

class StocksController extends Controller
{

    public function getStockData($ticker) {

        /* setup CURL */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.quandl.com/api/v3/datasets/WIKI/". $ticker . ".json?api_key=dnasGRs1wRQrsRzortuC");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* execute curl and return the response as JSON */
        $response = curl_exec($ch);
        return json_decode($response);
    }


}