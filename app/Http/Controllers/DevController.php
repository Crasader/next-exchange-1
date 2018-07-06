<?php

// THIS IS THE DEV (DEVELOPMENT) CONTROLLER FOR TESTING FUNCTIONS BEFORE PUTTING THEM SOMEWHERE ELSE!


namespace App\Http\Controllers;

use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\CaptureIpTrait;

class DevController extends Controller
{

    use CaptureIpTrait;

    public function showETN() {
        return view('dev.etnwallet');
    }

    public function testPrivate(){
        $addr = Addresses::where('address_id', 7)->first();
        $mix_salt = EncryptionController::generateSecureSalt($addr->salt);
        return EncryptionController::regeneratePk($addr->pk,$mix_salt,$addr->IV);
    }

    public function getRefTokens() {
        // First get all referred_by and count them on group
        $results = DB::table('users')
            ->leftjoin('users_details', 'users_details.user_id', '=', 'users.referred_by')
            ->select('ether', 'referred_by', DB::raw('count(*) as amount'))
            ->where('referred_by', '>', 0)
            ->groupBy('referred_by')
            ->orderBy('amount', 'DESC')
            ->get();

        return view('dev.reftoken')->withResults($results);
    }

    public function getIpAddress()
    {
        return $this->getClientIp();
    }
}
