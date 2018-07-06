<?php

namespace App\Jobs;

use App\Helpers\Helper;
use App\Models\Addresses;
use App\Models\Coin;
use App\Models\Fee;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Blockchainservice;
use App\Traits\WalletTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class TransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, WalletTrait;

    private $_orderID;

    /**
     * Create a new job instance.
     *
     * @param $order_id
     */
    public function __construct($order_id)
    {
        $this->_orderID   = $order_id;
    }

    /**
     * Debug log.
     *
     * 
     */
    public function debug($label, $obj) {
        $fp = fopen("debug.txt", "a");
        fputs($fp, print_r($label, true)."\n");
        fputs($fp, print_r($result, true)."\n");
        fclose($fp);        
    }

    /**
     * Execute the job.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::notice('ORDER_EXECUTION STARTED OF ORDER_ID ' . $this->_orderID);
        \Debugbar::notice('ORDER_EXECUTION STARTED OF ORDER_ID ' . $this->_orderID);

        DB::select("CALL order_execute( '". $this->_orderID . "', @return_data)");

        $result = DB::select('select @return_data as return_data');
        $result = json_decode($result[0]->return_data);

        // Log the transaction
        Log::debug($result);

        // Publishing the activity. Client side listeners will read this.
        $redis  = Redis::connection();
        $redis->publish('update-order-vue', json_encode(['event' => 'user_transactions', 'data' => $result]));

        //If no orders executed return from here
        if(!$result) return false;

        $blockChain = new Blockchainservice;
        $fiats  = [10, 11];
        $tx_id  = null;
        $to_address = null;

        $transfer_fee_percentage    = Fee::getFee( 'transfer' );

        //Looping through the transactions which are returned from the MySQL Procedure
        \Debugbar::debug($result);
        //for ($i = 0; $i < count($result); $i++) {
        foreach ($result as $transaction_order) {

            //if BUY need to find amount of main coin used, if SELL executed amount of  market coin
            $total_amount   = $transaction_order->order_buysell == 1 ? bcmul($transaction_order->order_price, $transaction_order->order_amount, 9) : $transaction_order->order_amount;

            $user_id            = $transaction_order->user_id;
            $wallet_updated     = false;

            try {

                $transaction_confirmations  = 0;

                \Debugbar::notice('Transaction loop');
                \Debugbar::info($transaction_order);

                /**
                 * if Market is NEXT we are not charging any transaction fees
                 */
                if ($transaction_order->coin_id == 12) {

                    $fee_amount     = 0;
                } else {

                    $fee_amount     = bcmul($total_amount, bcdiv($transfer_fee_percentage, 100, 9), 9);
                }


                if(! in_array( $transaction_order->coin_id, $fiats )) {
                    // If the coin is not a fiat

                    $blockChain->setCoin( $transaction_order->coin_id );
                    $gas_fee            = $blockChain->getFee( $total_amount );
                    $total_gas          = $gas_fee['data'] * 2;

                    /**
                     * if Family coins reducing gas fee from first coin in array and updates the that coins wallet
                     * Otherwise it will  deduct from the Market itself
                     * while calculating the total amount to transfer we will deduct the gas fee for our fees transaction
                     * from the user itself, i.e; else part
                     */
                    $familyCoins    = Coin::getFamilyCoins( $transaction_order->coin_id );

                    if (in_array($transaction_order->coin_id, $familyCoins)) {
                        // If the coin is a family coin (ERC20)
                        \Debugbar::notice('Transaction Order still exist?');
                        \Debugbar::info($transaction_order);

                        \Debugbar::notice('Transaction Family coin?');
                        \Debugbar::info($familyCoins[0]); // ERC is coin_id 2

                        $wallet             = Wallet::where('user_id', $user_id)
                            ->where('coin_id', $familyCoins[0])
                            ->first();

                        $wallet->amount         = bcsub($wallet->amount, $total_gas, 9);
                        $wallet->amount_inorder = bcsub($wallet->amount_inorder, $total_gas, 9);
                        $wallet->save();
                        $wallet_updated     = true;

                        $blockChain_credit  = bcsub($total_amount, $fee_amount, 9);
                    } else {

                        $blockChain_credit  = bcsub(bcsub($total_amount, $fee_amount, 9), $gas_fee, 9);
                    }

                    \Debugbar::notice('Transaction Order still exist here?');
                    \Debugbar::info($transaction_order);

                    //Getting the to and from address
                    // Omdat de coin niet bestaat in de database komt er geen resultaat terug!!!! Dus inbouwen indien coin een family coin is, dat het ethereum address dient te worden geexecuted!

                    //if ($coin_id == $familyCoins[0]) {
                    //    $transaction_order->coin_id = $maincoin;
                    //}


                    $from_address       = Addresses::getAddressByUserID( $user_id, $transaction_order->coin_id );
                    $to_address         = Addresses::getAddressByUserID( $transaction_order->to_user_id, $transaction_order->coin_id);

                    \Debugbar::debug('From: ' . $from_address);
                    \Debugbar::debug('To: ' . $to_address);


                    $coin       = Coin::getNameOrTypeByID( $transaction_order->coin_id );

                    \Debugbar::debug('Coin: ' . $coin);

                    // Transfer the coins from the order execution
                    $transfer = $blockChain->transfer($coin, $from_address['pk'], $from_address['address_address'], $to_address['address_address'], $blockChain_credit);

                    if( $transfer ) {

                        $tx_id  = isset($transfer['data']['txid']) ? $transfer['data']['txid'] : $tx_id;

                        // TODO: Disable the fee's since it's blocking a correct execution
                        //$this->executeFees($blockChain, $transaction_order->order_id, $fee_amount, $gas_fee['data'], $from_address, $transaction_order->coin_id);
                        $transaction_confirmations  = 1;
                    }

                    $actual_credit      = number_format( ($blockChain_credit - $gas_fee['data']), 9);
                } else {
                    $gas_fee['data']    = 0;
                    $total_gas          = 0;
                    $actual_credit      =  number_format( ($total_amount - $fee_amount), 9);
                }

                // ToDo: put order amount from the existing order!!

                $transaction                            = Transaction::find( $transaction_order->transaction_id );
                $transaction->transaction_addr          = $to_address != null ? $to_address->address_address : ''; //Transfer to which address
                $transaction->transaction_amount        = $actual_credit;
                $transaction->transaction_rxid          = $tx_id;
                $transaction->transaction_fee           = $fee_amount;
                $transaction->gas_fee_amount            = bcmul($gas_fee['data'], 2, 9);
                $transaction->transaction_confirmations = $transaction_confirmations;
                $transaction->transaction_type          = 3;
                $transaction->save();

                \Debugbar::debug('Transaction: ' . $transaction);

            } catch (\Exception $e) {

                Helper::LogError([

                    'error_code'    => $e->getCode(),
                    'message'       => $e->getMessage(),
                    'line_no'       => $e->getLine(),
                    'trace'         => $e->getTrace()
                ], 'Transaction and Blockchain updating error while order executing');

                if($wallet_updated) {
                    $wallet             = Wallet::where('user_id', $user_id)
                        ->where('coin_id', $transaction_order->coin_id)
                        ->first();

                    $wallet->amount         = bcadd($wallet->amount, $total_gas, 9);
                    $wallet->amount_inorder = bcadd($wallet->amount_inorder, $total_gas, 9);
                    $wallet->save();
                }
            }
        }
    }
}
