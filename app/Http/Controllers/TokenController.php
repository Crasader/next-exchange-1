<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Coin;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Validator;


class TokenController extends Controller
{
    public function list( Request $request ) 
    {
        $tokens = Coin::where('coin_erc', 20)->get();
        return response()->json(['data' => $tokens], 200);
    }

    public function get(Request $request , $symbol)
    {
        $token = Coin::where('coin_coin' , $symbol)->first();
        return response()->json(['data' => $token] , 200);
    }

    public function rate(Request $request)
    {
        $response = file_get_contents('https://api.coinmarketcap.com/v1/ticker/ethereum/?convert=USD');
        $object = json_decode($response , true);
        return response()->json($object , 200);   
    }
}
