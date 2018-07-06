<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Blockchain\Blockchain;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Auth;

use App\Models\Addresses;
use App\Models\AddressesGenerate;

class BitcoinController extends Controller
{

    protected $response;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBitcoin(Request $request)
    {
        $ajaxRequest    = $request->input('ajaxRequest', false);

        $currentUser = Auth::user();
        $user_id = $currentUser->id;

        // ToDo: Check if the bitcoin address is already existed by userID 
        $current_btc_address = $this->_getBitcoinAddressByUserID($user_id);
        if (!$current_btc_address) {
            $new_address = $this->_generateBitcoinAddress();
            $address = $new_address['address'];
            $pk = $new_address['pk'];
            $this->_saveBitcoinAddress($user_id, $address, $pk);
        } else {
            $address = $current_btc_address->address_address;
        }

        if($ajaxRequest) {
            die(json_encode(compact('address')));
        }

        return view('pages.bitcoin',compact('address'));
    }

    public function getBalance(Request $request = null) {
        // $ajaxRequest = $request->input('ajaxRequest', false);
        $currentUser = Auth::user();
        $current_btc_address = $this->_getBitcoinAddressByUserID($currentUser->id);
        if (!$current_btc_address) {
            return false;
        }
        $client = new Client(); //GuzzleHttp\Client
        $node_server = env("COIN_NODE_SERVER", "http://localhost:3000");
        $uri = $node_server . '/wallet/balance/btc?address='.$current_btc_address->address_address;
        $res = $client->request('GET', $uri);
        $res_status = $res->getStatusCode();
        $result = json_decode($res->getBody()->getContents(), true);

        if ($res_status == 200) {
            return $result;
        } else {
            return false;
        }
    }
    public function postBitcoinTransfer(Request $request) {
        $ajaxRequest = $request->input('ajaxRequest', false);
        $toAddress = $request->input('toaddress', false);
        $amount = $request->input('amount', false);

        $currentUser = Auth::user();
        $current_btc_address = $this->_getBitcoinAddressByUserID($currentUser->id);

        $transfer_res = $this->_transfer($current_btc_address->pk, $current_btc_address->address_address, $toAddress, $amount);
        if ($transfer_res) {
            die(json_encode($transfer_res));
        } else {
            die(json_encode(array('status'=>false, 'message'=>'unknown server error!')));
        }
        
    }

    private function _saveBitcoinAddress($user_id, $address, $pk) {
        $db_address = new Addresses();
        $db_address->address_user_id = $user_id;
        $db_address->address_address = $address;
        $db_address->pk = $pk;
        $db_address->address_coin = '1'; //BTC
        $db_address->address_name = '1'; //BTC
        $db_address->address_type = '1'; //BTC
        $db_address->save();
        return true;
    }
    public function getBitcoinAddress() {

        $unique_id = str_random(64);

        // TODO take address if not expired after 15 minutes!
        // So check database generated address and give this one out and set the status to 2 (locked) for a period.
        // Check 30 minutes expire
        $expired_date = Carbon::now()->subMinutes(30)->toDateTimeString();
        $result = AddressesGenerate::where('coin_market','=', 1)
            ->where('expired','=',1)
            ->where('updated_at','<',$expired_date)
            ->first();

        if(count($result)) {
            $address    = $result->address;
            // Update status of expired
            AddressesGenerate::where('address', $address)->update(['expired'=> 0]);
        }
        else {
            $blockchain = new Blockchain(env('BITCOIN_API'));

            $v2ApiKey = env("BITCOIN_API");
            $xPub = env("BITCOIN_XPUB");

            // TODO: Send unique tx unique_id
            $callbackUrl = 'https://next.exchange/callback/bitcoin?secret=' . env('BITCOIN_SECRET') . '&unique_id=' . $unique_id;
            $gap_limit = 21; // optional - how many unused addresses are allowed before erroring out

            $response = $blockchain->ReceiveV2->generate($v2ApiKey, $xPub, $callbackUrl, $gap_limit);
            $address = $response->getReceiveAddress();

            $coin_market = '1'; // BTC

            // Put address in database
            $db_address = new AddressesGenerate();
            $db_address->address = $address;
            $db_address->coin_market = $coin_market;
            $db_address->save();
        }

            $bitcoin = new \stdClass();
            $bitcoin->address = $address;
            $bitcoin->txid = $unique_id;


        return $bitcoin;
    }

    public function getBitCoinGen($amount){
        return 'You will send '.$amount.' NEXT';
    }

    public function callback() {
        // TEST LINE
        // callback/bitcoin?secret=ZzsMLGKe162CfA5EcG6j&value=0.02&unique_id=1234567890&transaction_hash=93jd43fjwe90diu90sdj&confirmations=2
        if ($_GET['secret'] != env('BITCOIN_SECRET')) {
            die('Stop doing that');
        }
        else {

            //$blockchain = new Blockchain(env('BITCOIN_API'));

            //$v2ApiKey = env("BITCOIN_API");
            //$callbackUrl = 'https://next.exchange/callback/bitcoin?secret=' . env('BITCOIN_SECRET');

            // Needs one or more confirms
            if ($_GET['confirmations'] >= 3) {
                // Callback values
                $confirmations = $_GET['confirmations'];
                $unique_id = $_GET['unique_id'];
                $transaction_hash = $_GET['transaction_hash'];
                $value_in_btc = $_GET['value'] / 100000000;

                // TODO: toevoegen aan database indien betaling geslaagd is
                // UPDATE confirmation on database
                // TODO: Controle of overgemaakt btc_price overeenkomt.
                Transaction::where('transaction_txid', $unique_id)->update(['transaction_rxid'=> $transaction_hash, 'transaction_confirmations' => $confirmations, 'transaction_status' => 1]);
                $transaction =  Transaction::where('transaction_txid', $unique_id)->first();
                // Verwijder het gebruikte address uit de addresses_generate tabel
                AddressesGenerate::where('address', '=', $transaction->transaction_addr)->delete();

                // TODO: Automatisch toevoegen aan wallet.

                // Authotized ID is not working since it a callback

                $wallet = Wallet::firstOrNew(['coin_id' => 12, 'name' => 'NEXT', 'user_id' => $transaction->transaction_user_id]);

                if(count($wallet)) {
                    // Controleren of unique_id al gebruikt is bij de update van de wallet, anders updaten!
                    if ($wallet->tx_id != $unique_id) {
                        $wallet->amount = $wallet->amount + $transaction->transaction_maincoin_amount;
                    }
                }
                else {
                    // update record
                    $wallet->amount = $transaction->transaction_maincoin_amount;
                }

                $wallet->tx_id = $unique_id;
                $wallet->save();

                // Update the transaction table with wallet balance and wallet id
                $transaction->transaction_maincoin_wallet_id = $wallet->id;
                $transaction->transaction_maincoin_wallet_balance = $wallet->amount;
                $transaction->save();

                echo '*ok*';

            }


            //$logs = $blockchain->ReceiveV2->callbackLogs($v2ApiKey, $callbackUrl);

            //foreach ($logs as $log) {
            //    $log->getCallback();
            //    $log->getCalledAt(); // DateTime instance
            //    $log->getResponseCode();
            //    $log->getResponse();
            //}
        }
    }

    private function _getBitcoinAddressByUserID($user_id) {
        // ToDo: get Bitcoin Address by User ID
        $result = Addresses::where('address_coin','=', 1)
            ->where('address_user_id','=',$user_id)
            ->first();
        if ((is_object($result) || is_array($result)) && count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    private function _generateBitcoinAddress() {
        $new_address = array('pk' => null, 'address' => null);
        $client = new Client(); //GuzzleHttp\Client
        $node_server = env("COIN_NODE_SERVER", "http://localhost:3000");
        $uri = $node_server . '/wallet/create/btc';

        $res = $client->request('POST', $uri);
        $res_status = $res->getStatusCode();
        $result = json_decode($res->getBody()->getContents(), true);
        
        if ($res_status == 200 && $result['status']) {
            $new_address['address'] = $result['data']['addr'];
            $new_address['pk'] = $result['data']['pk'];
        }

        return $new_address;
    }

    private function _transfer($pk, $from, $to, $amount) {
        $client = new Client(); //GuzzleHttp\Client
        $node_server = env("COIN_NODE_SERVER", "http://localhost:3000");
        $uri = $node_server . '/withdraw/create/btc';
        $data = array(
            'pk' => $pk,
            'from' => $from,
            'to' => $to,
            'amount' => $amount
        );
        $res = $client->request('POST', $uri, ['form_params' => $data]);
        $res_status = $res->getStatusCode();
        $result = json_decode($res->getBody()->getContents(), true);

        if ($res_status == 200) {
            return $result;
        } else {
            return false;
        }
    }

}
