<?php

namespace App\Http\Controllers;
use Event;
use App\Helpers\Helper;
use App\Notifications\SuccessfulTransaction;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Coin;
use App\Models\Wallet;
use App\Models\Addresses;
use App\Events\Transactionupdate;
use App\Services\Blockchainservice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $content = [];

	    $id = Auth::id();

        $data   = Transaction::where('transaction_user_id', $id )
        ->orderBy('updated_at', 'desc')
        ->paginate(25);

	foreach($data as $alldata)
	{

		$market         = $alldata->market_name;
		$cointitle      = $alldata->maincoin_name;
        $coinamount = $alldata->transaction_amount;

		$coindate=date('d M y H:m ', strtotime($alldata->created_at));

        // FIAT is 2 decimals
        if ($alldata->maincoin_name == 'EUR' OR $alldata->maincoin_name == 'USD') {
            $coinamount = number_format($coinamount, 2, '.', ',');
        } else {
            $coinamount = rtrim($coinamount, '0');
        }

		$content[]=array(
            'transaction_id' => $alldata->transaction_id,
		    'transaction_date'      => $coindate,
            'transaction_type'      => $alldata->transaction_type,
            'transaction_buysell'   => $alldata->transaction_buysell,
            'gas_fee_amount'        => $alldata->gas_fee_amount,
            'amount' => $coinamount,
            'main_coin'             => $cointitle,
            'status'                => $alldata->transaction_status,
            'rxid' => $alldata->transaction_rxid,
		);
	}
		$response = [
				'pagination' => [
					'total' => $data->total(),
					'per_page' => $data->perPage(),
					'current_page' => $data->currentPage(),
					'last_page' => $data->lastPage(),
					'from' => $data->firstItem(),
					'to' => $data->lastItem()
				],
				'data' => $content
				];

	 //event(new Transactionupdate($data));
     return response()->json($response);
}

    public function getTransactionView(Request $request)
    {
        $transactions = Transaction::where('transaction_user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('transactions.load', ['transactions' => $transactions])->render();
        }


        return view('transactions.index', compact('transactions'));
    }

	public function userdisclaimer(Request $request)
	{

		$id = Auth::user()->id;
		$user=User::where('user_disclaimer',1)->where('id', $id)->first();
		if($user)
		{
			$success=1;
		}
		else{
			$success=0;
		}
		$data=array('success'=>$success);
		return response()->json(['data'=>$data]);

	}

    public function saveuserdisclaimer(Request $request)
	{

		$id = Auth::user()->id;
		$user=User::find($id);

		$user->user_disclaimer = 1;
		$user->save();


		return response()->json(1);
	}


    // TODO: new private function to insert transactions in the table
    // We have 3 types of transactions.
    // Incoming = people deposit money
    // Internal = an exchange has happen
    // Deposit = people withdraw money
    public function saveTransaction() {

        $transaction = new Transaction();
        $id = Auth::id();
        $transaction->transaction_user_id = $id;
        $transaction->transaction_txid = $response["TRANSACTIONID"];
        $transaction->transaction_rxid = str_random(60);
        $transaction->transaction_addr = 'paypal';
        $transaction->transaction_amount = $request->get('amount');
        $transaction->transaction_market = 1;
        $transaction->transaction_fee = 000000000000000000;
        $transaction->transaction_cost = 000000000000000000;
        $transaction->transaction_ip = $request->ip();
        $transaction->transaction_price = 0;
        $transaction->transaction_buysell = 0; // 0 = buy, 1 = sell
        $transaction->transaction_maincoin = $crt;
        $transaction->transaction_maincoin_wallet_id = '1';
        $transaction->transaction_maincoin_amount = 100;
        $transaction->transaction_maincoin_wallet_balance = '0';
        $transaction->transaction_confirmations = 0;
        $transaction->transaction_status = 1;
        $transaction->created_at = \Carbon\Carbon::now();
        $transaction->updated_at = \Carbon\Carbon::now();
        $transaction->save();

        // Here comes the execution of Crypto
        // So blockchain exchange here like:
        // cryptoswap(ETH, 1000, $buyerID, NEXT, 10, $sellerID);
    }

    // TODO: getting more details about the transaction
    // This function will get the transactionID and date of a specific user id
    public function getTransactions() {
        
        // get all addresses from addresses table to get transactions
        $addresses = Addresses::get();

        foreach ($addresses as $address) {
            // get every address and symbol in addresses table

            $txResponse = $this->saveTransactions($address['address_user_id'], strtolower($address['address_type']), $address['address_address']);
            // $this->debug($txResponse);
        }
    }

    // Fetch a list of tranasaction from blockchain service and save it to db.
    public function saveTransactions($user_id, $symbol, $address) {
        $blockChain = new Blockchainservice;
        $response  = $blockChain->getTransactionList( $symbol, $address );

        if ($response['status'] == true) {
                $token = Coin::getCoinIDByName($symbol);
                foreach ($response['data'] as $deposit) {
                    $wallet = Wallet::walletIDbyUserAndCoinID($user_id, $token['coin_id']);
                    $tx = Transaction::where('transaction_txid', $deposit['txid'])->first();
                    if (!(isset($tx) && $tx->transaction_id != NULL)) {
                        
                        $user = User::where('id', $user_id)->first();
                        $coin_name  = Coin::getNameByID( $token['coin_id'] );

                        // Email notify the user
                        $user->notify( new SuccessfulTransaction(
                            $deposit['amount'],
                            $deposit['txid'],
                            'deposit',
                            strtoupper($coin_name)
                        ));

                        $transaction = new Transaction();
                        $transaction->transaction_user_id = $user_id;
                        $transaction->transaction_txid = $deposit['txid'];
                        $transaction->transaction_rxid = str_random(60);
                        $transaction->transaction_amount = $deposit['amount'];
                        // $transaction->transaction_ip = $deposit->ip();
                        $transaction->transaction_type = 1;
                        $transaction->transaction_maincoin = $token['coin_id'];
                        $transaction->maincoin_name = $coin_name;
                        $transaction->transaction_maincoin_wallet_id = $wallet->id;
                        $transaction->transaction_status = 1;
                        $transaction->created_at = \Carbon\Carbon::createFromTimestamp($deposit['time']);
                        $transaction->updated_at = \Carbon\Carbon::createFromTimestamp($deposit['time']);
                        $transaction->save();
                    }                
                }
        }
        return response()->json(['status' => true]);
    }
}
