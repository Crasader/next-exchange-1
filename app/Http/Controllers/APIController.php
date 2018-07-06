<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class APIController extends Controller
{
    /**
     * This is the API controller to get information from other API's
     *
     * API's that are used in NEXT.Exchange, will be routed with api.php (routes), there functions will be here.
     *
     * @return void
     *
     */

    public function __construct()
    {
    }

    public static function getPricefromIDEX($symbol)
    {

        $post = [
            'market' => $symbol
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.idex.market/returnTicker",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
            \Debugbar::error("cURL Error #:" . $err);
        } else {
            return json_decode($response);
        }


    }


}