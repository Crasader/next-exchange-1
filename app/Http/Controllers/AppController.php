<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AppController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     *
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function links_items($links){

        foreach ($links as $title => $url) {
            $encoded = base64_encode($title);
            ?>
            <a class="item" href="<?php echo "go.php?go=$encoded"; ?>" target="_blank">
                <i class="external icon"></i>
                <?php echo $title; ?>
            </a>
            <?php
        }
    }

    public function social_items($networks){
        global $socials;
        foreach ($networks as $name => $url)
            if(isset($socials[$name]) && is_string($url)){
                $details = $socials[$name];
                ?>
                <a class="item" target="_blank" href="<?php echo $url ?>">
                    <i class="<?php echo $details['icon'] ?> icon"></i>
                    <?php echo $details['text'] ?>
                </a>
                <?php
            }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function getHome()
    {
        $data = null;
        return view('app.home')->with('data', $data);
    }

    public function getDashboard()
    {
        $data = null;
        return view('app.home');
    }

    public function getVueDashboard()
    {
        $user_disclaimer    = Auth::user()->user_disclaimer;
        $user_id            = Auth::id();
        return view('app.vue-dashboard', compact('user_disclaimer', 'user_id') );
    }

    public function getWallet()
    {
        $wallet = Auth::user()->wallet;
        return view('dashboard.wallet')->withWallets($wallet);
    }

    public function buyTokens()
    {
        return view('dashboard.token-buy');
    }

    public function showExchange()
    {
        return view('dashboard.exchange');
    }

    public function showApi()
    {
        return view('dashboard.api-development');
    }

    public function showMessages()
    {
        return view('dashboard.messages');
    }


}