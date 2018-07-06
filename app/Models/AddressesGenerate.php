<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressesGenerate extends Model
{
    use SoftDeletes;

    protected $table = 'addresses_generate';

    protected $fillable = ['address', 'coin_market'];

    protected $dates = ['deleted_at'];
    //
}
