<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Services\ETNService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ETNController extends Controller
{

    /**
     * Check for user's existing ETN address and return if exists
     *
     * @param Request $request
     * @return int
     */
    public function myAddress(Request $request) {

        $coin_id    = $request->input('coin_id', 0);
        $user_id    = Auth::id();

        $exists     = Addresses::select('address_address')
            ->where('address_user_id', $user_id)
            ->where('address_coin', $coin_id)
            ->first();

        return $exists && property_exists($exists, 'address_address') ? $exists->address_address : 0;
    }

    /**
     * Search for address in Addresses table, if not exists will generate new one from node and save to table
     *
     * @param Request $request
     * @return int|string
     */
    public function getAddress(Request $request) {

        $coin_id    = $request->input('coin_id', 0);
        $user_id    = Auth::id();

        $exists     = Addresses::select('address_address')
            ->where('address_user_id', $user_id)
            ->where('address_coin', $coin_id)
            ->first();

        if ( $exists && property_exists($exists, 'address_address') ) {

            return $exists->address_address;
        }

        try {

            $ETNService     = new ETNService;
            $ETNaddress     = $ETNService->getAddress();

            Addresses::create([

                'address_user_id'           => $user_id,
                'address_address'           => $ETNaddress,
                'address_payment_id'        => 0,
                'address_payment_id_type'   => 0,
                'address_coin'              => $coin_id,
                'address_name'              => 'ETN',
                'address_type'              => 'generate',
                'pk'                        => 0
            ]);
            
            return $ETNaddress;

        } catch (Exception $exception) {

            Log::error( $exception->getMessage() );
            return 0;
        }
    }

    public function withDraw( Request $request ) {

        $user_id    = Auth::id();
        $coin_id    = $request->input('coin_id');
        $address    = $request->input('address');
        $amount     = $request->input('amount');


        $ETNService = new ETNService;
        $data   = $ETNService->with_draw($user_id, $coin_id, $address, $amount);

        return response()->json(['data' => $data], 200);
    }
}
