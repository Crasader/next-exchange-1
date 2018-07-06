<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Traits\CaptureIpTrait;
use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Srmklive\Authy\Services\Authy as TwoFactorProvider;
use TwoFactorAuth;

class HomeController extends Controller
{
    use CaptureIpTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $app = app();
        $referall = $app->make('stdClass');
        $referall->id = Helper::encryptId(Auth::id());
        $referall->count = User::where(['referred_by' => Auth::id()])->count();

        // Count refs during ICO
        $referalls =  User::where(['referred_by' => Auth::id()])->get();
        $ico_ref = '0';

        foreach ($referalls as $refs) {
            if ($refs->created_at < '2018-01-15') {
                $ico_ref++;
            }
        }

        $referall->ico = $ico_ref;

        $wallet = Wallet::getBalance(12, Auth::id());

        $wallet_balance = bcsub($wallet['amount'], $wallet['inorder'], 9);

        $user = auth()->user();

        $twofactor_enabled = TwoFactorAuth::isEnabled($user);

        $lastLoginIp    = $this->getClientIp();


        return view('home', compact('referall', 'wallet_balance', 'twofactor_enabled', 'lastLoginIp'));
    }


}
