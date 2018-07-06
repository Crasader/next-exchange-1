<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{

    /**
     * Returns the fees by key, success returns fees otherwise false
     *
     * @param null $key
     * @return String / bool
     */
    public static function getFee( $key = null ) {

        $fee    = Fee::select('value')
            ->where('key', $key)
            ->first();

        return $fee ? $fee['value'] : false;
    }
}
