<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Addresses;
use App\Models\Coin;
use App\Models\Transaction;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\AddressesGenerate;
use Carbon\Carbon;
use App\Services\Blockchainservice;
use GenPhrase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WalletController extends Controller
{
    use CaptureIpTrait;

    public static function genPassphrase()
    {
        $gen = new GenPhrase\Password();
        $gen->disableSeparators(true);
        $gen->disableWordModifier(true);
        return $gen->generate(120);
    }

     /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function initWallet()
    {
        try {
            $user_id   = Auth::id();
            //Get the list of default wallet required
            $default_wallets    = Coin::defaultWallets();

            if (count($default_wallets)) {
                // Check if the defaults wallets are already existing
                $wallet_created = Wallet::createdDefaultWallets( $default_wallets, $user_id );
                // Check the array differences, if not presented at to the $to_create array
                $to_create      = array_diff($default_wallets, $wallet_created);

                // If no default wallets are there, create them
                if(count($to_create)) {
                    foreach ($to_create as $coin_id) {
                            Addresses::generateAddressByUserID($user_id, $coin_id);
                    }
                }
            }

            // If default wallets are there, get Wallets and Addresses from the database!
            $enabled_wallets = Wallet::getWalletWithAddress($user_id);
            return response()->json($enabled_wallets);

        } catch (\Exception $exception) {
            $result = [
                'message'   => $exception->getMessage(),
                'file'      => $exception->getFile(),
                'line No'   => $exception->getLine(),
                'trace'     => $exception->getTrace()
            ];
            Helper::LogError($result, 'WalletController, initWallet Error');
        }
    }

    /**
     * Fetch the coins
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCoins()
    {

        $defaultWallets = Coin::defaultWallets();

        $coins  = Coin::select('coin_id', 'coin_coin AS symbol', DB::raw("CONCAT(coin_title, ' (', coin_coin, ')') AS coin_title"))
            ->where('coin_enabled', 1)
            ->whereNotIn('coin_id', $defaultWallets)
            ->get();

        return response()->json($coins);
    }

    /**
     * Show the selected coin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showCoin(Request $request)
    {
        $coin_id    = $request->input('coin_id', 0);
        $wallet = Wallet::where('coin_id', $coin_id)
            ->where('user_id', Auth::id())
            ->first();


        $wallet_data    = [
            'wallet_id'         => 0,
            'wallet_enabled'    => 0
        ];

        if ($wallet) {

            $wallet_data    = [
                'wallet_id'         => $wallet->id,
                'wallet_enabled'    => $wallet->wallet_enabled
            ];
        }

        return response()->json($wallet_data);
    }

    public function createUpdateWallet(Request $request)
    {

        try {

            $wallet_id      = $request->input('wallet_id', 0);
            $coin_id        = $request->input('coin_id');
            $wallet_enabled = $request->input('wallet_enabled', 0);
            $user_id        = Auth::id();
            $wallet         = null;

            $coin = Coin::find($coin_id);
            /**
             * If we don't send wallet id (i.e. it's value 0)
             * we create new wallet and enable it
             */
            if (!$wallet_id) {

                /**
                 * If coin fiat we won't call blockchain for creating wallet.
                 * Instead we creating wallet without wallet
                 */
                if ($coin->coin_fiat) {

                    $wallet = new Wallet([
                        'name'      => $coin->coin_coin,
                        'tx_id'     => Helper::uniqueID(),
                        'amount'    => 0.0,
                        'user_id'   => $user_id,
                        'coin_id'   => $coin_id
                    ]);
                    $wallet->save();
                } else {
                    Addresses::generateAddressByUserID( $user_id, $coin_id );
                    $wallet = Wallet::where('user_id', $user_id)
                        ->where('coin_id', $coin_id)
                        ->first();
                }

                $wallet->wallet_enabled = $wallet_enabled;
                $wallet->save();
            } else {
                /**
                 * In other case we fetch wallet by it's id and update it
                 */
                $wallet = Wallet::find($wallet_id);
                $wallet->wallet_enabled = $wallet_enabled;
                $wallet->save();
            }

            return response()->json([
                'wallet_id'         => (int) $wallet->id,
                'wallet_enabled'    => (bool) $wallet_enabled
            ]);
        } catch (\Exception $exception) {
            $result = [

                'message'   => $exception->getMessage(),
                'file'      => $exception->getFile(),
                'line No'   => $exception->getLine(),
                'trace'     => $exception->getTrace()
            ];
            Helper::LogError($result, 'WalletController, createUpdateWallet Error');
        }
    }

    public function updateExpiredAddresses($iCoinMarket) {
        $expired_date = Carbon::now()->subMinutes(30)->toDateTimeString();
        $result = AddressesGenerate::join('transactions', 'addresses_generate.address', '=', 'transactions.transaction_addr')
            ->where('addresses_generate.coin_market','=', $iCoinMarket)
            ->where('addresses_generate.expired','=',0)
            ->where('addresses_generate.updated_at','<',$expired_date)
            ->where('transactions.transaction_status','=',0)
            ->get();

        if($result->count()) {
            foreach($result as $item) {
                // Update status of expired
                AddressesGenerate::where('address', $item->address)->update(['expired' => 1]);
            }
        }
    }

    public function getAddrBitcoin(Request $request, $txid = '') { // Depriciated
        $bitcoin = new BitcoinController();
        $oBitcoin = $bitcoin->getBitcoinAddress();

        $btc_price = \App\Helpers\Helper::getCryptoPrice('','BTC');
        $eth_price = \App\Helpers\Helper::getCryptoPrice('','ETH');

        $next_amount = $request->amount;
        $next_price = $next_amount * ($eth_price / 1000);

        $next_btc_amount = $next_price / $btc_price;

        if (empty($txid)) {
            $txid = str_random(64);
        }

        $transaction = new Transaction();
        $transaction->transaction_user_id = Auth::id();
        $transaction->transaction_txid = $oBitcoin->txid;
        $transaction->transaction_addr = $oBitcoin->address;
        $transaction->transaction_amount = $next_btc_amount;
        $transaction->transaction_market = 1; // BTC
        $transaction->transaction_ip = self::getClientIp();
        $transaction->transaction_price = '0';
        $transaction->transaction_buysell = '0'; // 0 = buy, 1 = sell
        $transaction->transaction_maincoin_amount = $next_amount;
        $transaction->transaction_maincoin = '12';
        $transaction->save();

        $html = '
<style>
#qr-code { text-align: center !important; }
</style>
        <div class="card ptb40 col-12" style="min-height: 260px">

<div class="row">
                    <div class="col-3">
                    <script src="components/jquery-qrcode/dist/jquery-qrcode.js"></script>
                
                    <div id="qrcode"><a href="bitcoin:'.$oBitcoin->address.'?amount='.$next_btc_amount.'"></a></div>
                    <script>
                        $(function() {
                            $(\'#qrcode a\').qrcode({
                                render: \'div\',
                                text: "bitcoin:'.$oBitcoin->address.'?amount='.$next_btc_amount.'",
                                ecLevel: \'L\',
                                size: "120"
                            });
                        });
                    </script>
                    </div>
                    <div class="col-9" style="text-align: left">
                     <br>Send manual <b>'.$next_btc_amount.' BTC</b> to:<br> <span class="f16"><b>'.$oBitcoin->address.'</b></span>
                     </div>
                     </div>
                     <div class="col-12">
                    <br><small>Payment is only possible within 15 minutes.</small>
                    <br> After completing the payment, wait for <b>3</b> confirmations</a>. <br>After confirmation we will add <b>'.$next_amount.' NEXT</b> tokens to your wallet.
                    </div>

                </div>
        ';

        return $html;
    }
}
