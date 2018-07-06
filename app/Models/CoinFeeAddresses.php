<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinFeeAddresses extends Model
{
    protected $table    = 'coin_fee_addresses';

    public static function getAddress( $coin_id = 0 ) {

        $address    = CoinFeeAddresses::select(
                'coin_fee_address as address',
                'pk'
            )
            ->where('coin_fee_coin', $coin_id)
            ->first();


        return $address;
    }
}
