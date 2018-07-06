<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinFeeTransaction extends Model
{
    protected $fillable = [
        'from_user_id',
        'transaction_id',
        'transaction_amount',
        'transaction_fee',
        'gas_fee',
        'transaction_ip',
        'transaction_type',
        'transaction_maincoin'
    ];
}
