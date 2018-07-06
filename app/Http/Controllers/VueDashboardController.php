<?php

/**
 * Controlls all functionality in the vue dashboard page
 */

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Jobs\TransactionsJob;
use App\Models\Addresses;
use App\Models\Coin;
use App\Models\Fee;
use App\Models\MarketCap;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Blockchainservice;
use App\Traits\CaptureIpTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class VueDashboardController extends Controller
{
    use CaptureIpTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Gets the coin price by symbol
     *
     * @return mixed
     */
    public function getTransactionCoins()
    {

        return Coin::getCoinsBySymbol();
    }

    /**
     * Getting coins ID and Symbol from Coins table
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCoinsByStatusAndFiat()
    {
        $coins  = Coin::getIDAndSymbol();

        return response()->json($coins);
    }

    /**
     * Saving BUY/SELL orders and Publish with Redis
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveOrders(Request $request)
    {

        $type       = $request->input('order_type');

        $field      = $type == 1 ? 'order_total' : 'order_amount';
        $coinId     = $type == 1 ? $request->input('order_maincoin_id') : $request->input('order_coin_id');
        $price      = bcadd(0, $request->input('order_price'), 9);

        //Validating input
        $validator  = $this->validateInput($request->all(), $field);

        if($validator->fails()) {

            return response()->json(['errors' => $validator->errors()]);
        }

        $user_id        = Auth::id();

        /**
         * Generate wallet and coin address if user have no wallet exists.
         * if type is BUY need to check wallet exists for Market else for Main coin
         */
        $toCheckWallet  = $type == 1 ? $request->input('order_coin_id') : $request->input('order_maincoin_id');

        if(Coin::isNotFiatAndWalletExists( $toCheckWallet, $user_id)) {

            Addresses::generateAddressByUserID( $user_id, $toCheckWallet );
        }

        /**
         * While inserting to db table some float values are not properly inserting to the field in MySQL, to avoid that
         * issues we are using bcadd function and adding 0 this will convert the value to real float number.
         */
        $order_amount   = bcadd(0, $request->input('order_amount'), 9);
        $total          = bcadd(0, $request->input('order_total'), 9);

        $coin           = Coin::find( $coinId );
        $gasFrom        = $coinId;

        \Debugbar::info('Transaction saveorder started');

        if(! $coin->coin_fiat) {

            $familyCoins = Coin::getFamilyCoins($coinId);

            if( count($familyCoins) ) {

                $gasFrom    = $familyCoins[0];
            }
        }


        /**
         * if type is BUY need to calculate fees for total amount else for SELL need to calculate fees for amount.
         */
        $amount_for_fee     = $type == 1 ? $total : $order_amount;


        /**
         * Below is executed on the blockchain, if the blockchain is offline, this will not work!!
         */
        //$gasFee = $this->getGasFee( $gasFrom, $amount_for_fee );

        $gasFee = 0.001;

        if (!$gasFee) {
            return response()->json([
                'gas_error' => true,
            ]);
        }

        \Debugbar::info('Transaction still working');

        /**
         * Same with this!
         */
        //$transactionFee     = $this->getTransactionFee( $amount_for_fee );

        $transactionFee = 0;

        /**
         * Use strict comparision because we may get `0` transaction fee
         * which will count as false for if operator.
         */
        if($transactionFee === false) {

            return response()->json([
                'gas_error' => true,
            ]);
        }

        \Debugbar::info('Transaction still working');

        /**
         * Here something is not working well!
         */
        $requiredFeeAmount  = bcadd($gasFee, $transactionFee, 9);

        //if no amount to transfer to user after the fees or fee amount is higher than the order amount in case of buy
        // and order total in case of sell
        if($requiredFeeAmount >= $total) {

            return response()->json([
                'fee_error' => true,
                'req_amt'   => $requiredFeeAmount
            ]);
        }

        //saving orders
        $order  = new Order;

        try {

            $order->order_user_id       = $user_id;
            $order->order_amount        = $order_amount;
            $order->order_market        = $request->input('order_market');
            $order->order_fee           = 0;
            $order->order_ip            = $request->ip();
            $order->order_price         = $price;
            $order->order_total         = $total;
            $order->gas_fee             = $gasFee;
            $order->order_buysell       = $type;
            $order->order_cost          = '0';
            $order->order_maincoin      = $request->input('order_maincoin');
            $order->order_coin_id       = $request->input('order_coin_id');
            $order->order_executed      = 0;
            $order->order_maincoin_id   = $request->input('order_maincoin_id');

            if( $request->input('order_exchange', 0) == 1 ) {

                $order->order_exchange  = 1;
            } else {

                $order->order_exchange  = 0;
            }

            $order->save();

            //Preparing the array for laravel echo server
            $key            = $order->order_maincoin . '-' . $order->order_market;
            $returnOrders[$key]   = [
                'type'   => 0,
                'order' => [
                    'order_id'          => $order->order_id,
                    'order_price'       => $order->order_price,
                    'order_amount'      => $order->order_amount,
                    'order_total'       => $order->order_total,
                    'order_maincoin'    => $order->order_maincoin,
                    'order_market'      => $order->order_market,
                    'order_buysell'     => $order->order_buysell,
                    'order_user_id'     => $user_id,
                    'order_exchange'    => $order->order_exchange
                ]
            ];

            //Updating the Wallet table with order_total
            $wallet = Wallet::where('user_id', $user_id)
                ->where('coin_id', $coinId)->first();

            if( $type == 1 ) {

                $returnOrders[$key]['type'] = 1;
                $wallet->amount_inorder = bcadd($wallet->amount_inorder, $total, 9);
            } else {

                $returnOrders[$key]['type'] = 2;
                $wallet->amount_inorder = bcadd($wallet->amount_inorder, $order_amount, 9);
            }

            $wallet->save();

            try {

                // Publishing the activity. Client side listeners will read this.
                $redis  = Redis::connection();
                $redis->publish('update-order-vue', json_encode(['event' => 'ordersaved', 'data' => $returnOrders]));
            } catch (Exception $exception) {

                Helper::LogError([
                    'Error Code'    => $exception->getCode(),
                    'Error Message' => $exception->getMessage(),
                    'Trace'         => $exception->getTrace()
                ], 'Reids Exeception, Save Orders');
            }
            
            $this->dispatch(new TransactionsJob($order->order_id));
        }
        catch (Exception $e)
        {
            Helper::LogError([
                'message'   => $e->getMessage(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTrace()
            ], 'Save order Exeception');

            return response()->json([false]);
        }

        return response()->json([
            'order_total'   => $total,
            'order_amount'  => $order_amount,
        ]);
    }

    /**
     * Validates the from fields
     *
     * @param $inputs
     * @param $field
     * @return mixed
     */
    public function validateInput($inputs, $field)
    {
        Validator::extend('greater_than_equalto', function ($attribute, $value, $params, $validator) {

            $other = $params[0];

            return $value <= $other;
        });

        Validator::extend('greater_than', function ($attribute, $value, $params, $validator) {

            $other = $params[0];

            return $value > $other;
        });

        if ($inputs['order_type'] == 1) {
            $rules = [
                'order_amount' => 'required|numeric|greater_than:0',
                'order_price' => 'required|numeric|greater_than:0',
                $field => 'greater_than_equalto:' . $inputs['wallet_balance']
            ];
        } else {
            $rules = [
                'order_amount' => 'required|numeric|greater_than:0|greater_than_equalto:' . $inputs['wallet_balance'],
                'order_price' => 'required|numeric|greater_than:0',
            ];
        }

        $validator = Validator::make($inputs,
            $rules,
            [
                'order_amount' => 'Please fill this field with valid amount',
                'order_price' => 'Please fill this field with valid amount',
                'greater_than' => 'Total amount should be greater than Zero',
                'greater_than_equalto' => 'Amount should be lesser than available balance'
            ]
        );

        return $validator;
    }

    /**
     * Returns the maximum gas fee required for Transaction
     *
     * @param $coin_id
     * @param $amount
     * @return bool|float
     */
    public function getGasFee( $coin_id, $amount ) {

        $blockChain = new Blockchainservice;

        $blockChain->setCoin( $coin_id );
        $gas    = $blockChain->getFee( $amount );

        if($gas['status']) {

            //* 2 because for 2 transactions -> gas fee for company fee as well as gas fee for transaction
            return doubleval($gas['data']) * 2;
        }

        return false;
    }

    /**
     * Returns the transaction fee required for this transaction
     *
     * @param $amount
     * @return bool|float
     */
    public function getTransactionFee($amount)
    {

        try {

            $feePercentage = Fee::getFee('exchange');
            $companyFee = bcmul($amount, bcdiv($feePercentage, 100, 9), 9);

            return doubleval($companyFee);
        } catch (Exception $e) {

            Helper::LogError('Transaction fee Exeception: ' . $e->getMessage() . ' - Line: ' . $e->getLine() . ' - File :' . $e->getFile());
        }

        return false;
    }

    /**
     * Exchanging coins eachother when user clicks on Accept button
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchangeOrders(Request $request) {

        $order_id       = $request->input('order_id', 0);

        $order          = Order::getOrderByOrderID( $order_id );
        if( $order ) {

            //Checking wallet availability or market
            $wallet = Wallet::getBalance( $order->order_coin_id, Auth::id() );

            $wallet_balance = bcsub($wallet['amount'], $wallet['inorder'], 9);

            if( $order->order_amount > $wallet_balance ) {

                return response()->json("low-wallet");
            }

            //Transaction starts
            DB::beginTransaction();

                $order->order_executed  = 1;
                $order->save();

                //Creating the SELL order for this exchange

                //In the case of exchange SELL order we are not updating the wallet amount in order balance when order is placed.
                //Instead of that we are suddenly updating the wallet balance when transaction is happen.

                //Copying buy order object to new sell object and push to database after field modification
                $exchangeOrderSELL      = $order->replicate();

                $exchangeOrderSELL->order_user_id      = Auth::id();
                $exchangeOrderSELL->order_ip           = $this->getClientIp();
                $exchangeOrderSELL->order_buysell      = 2;
                $exchangeOrderSELL->order_executed     = 1;
                $exchangeOrderSELL->save();

                //updating wallet for current user main coin
                $walletSELL     = Wallet::where('user_id', Auth::id())
                                        ->where('coin_id', $order->order_coin_id)
                                        ->first();

                $wallet_market_new  = bcsub($walletSELL->amount, $order->order_amount, 9);
                $walletSELL->amount = $wallet_market_new;
                $walletSELL->save();

                //getting current user's maincoin balance
                $wallet_MC_sell_user    = Wallet::where('user_id', Auth::id())
                    ->where('coin_id', $order->order_maincoin_id)
                    ->first();

                //IF no main coin in wallet add new row otherwise update exisitng
                if( $wallet_MC_sell_user ) {

                    $wallet_MC_sell_ID              = $wallet_MC_sell_user->id;
                    $wallet_MC_sell_balance         = bcadd($wallet_MC_sell_user->amount, $order->order_total, 9);
                    $wallet_MC_sell_user->amount    = $wallet_MC_sell_balance;
                    $wallet_MC_sell_user->save();
                } else {

                    $wallet = Wallet::create([

                        'tx_id'     => 0,
                        'user_id'   => Auth::id(),
                        'coin_id'   => $order->order_maincoin_id,
                        'name'      => $order->order_maincoin,
                        'amount'    => $order->order_total
                    ]);

                    $wallet_MC_sell_balance = $order->order_total;
                    $wallet_MC_sell_ID      = $wallet->id;
                }

                //Transaction table entries
                $transactionSELL    = new Transaction;

                $transactionSELL->transaction_user_id                   = Auth::id();
                $transactionSELL->transaction_txid                      = md5( $exchangeOrderSELL->order_id . '&' . $order->order_id );
                $transactionSELL->transaction_addr                      = md5($this->getClientIp());
                $transactionSELL->transaction_amount                    = $order->order_amount;
                $transactionSELL->transaction_market                    = $order->order_coin_id;
                $transactionSELL->market_name                           = $order->order_market;
                $transactionSELL->transaction_ip                        = $this->getClientIp();
                $transactionSELL->transaction_price                     = $order->order_price;
                $transactionSELL->transaction_buysell                   = 2;
                $transactionSELL->transaction_maincoin_amount           = $order->order_total;
                $transactionSELL->transaction_maincoin                  = $order->order_maincoin_id;
                $transactionSELL->maincoin_name                         = $order->order_maincoin;
                $transactionSELL->transaction_maincoin_wallet_id        = $wallet_MC_sell_ID;
                $transactionSELL->transaction_maincoin_wallet_balance   = $wallet_MC_sell_balance;
                $transactionSELL->transaction_status                    = 1;
                $transactionSELL->order_id                              = $exchangeOrderSELL->order_id;
                $transactionSELL->order_matched                         = $order->order_id;

                $transactionSELL->save();

                //updating the wallet with transaction_id for current user
                $wallet = Wallet::find($wallet_MC_sell_ID);

                $wallet->tx_id  = $transactionSELL->transaction_id;
                $wallet->save();

                //Getting maincoin wallet for BUY user and updating it
                $wallet_MC_buy_user    = Wallet::where('user_id', $order->order_user_id)
                                            ->where('coin_id', $order->order_maincoin_id)
                                            ->first();

                if( $wallet_MC_buy_user ) {

                    $wallet_MC_buy_user->amount         = bcsub($wallet_MC_buy_user->amount, $order->order_total, 9);
                    $wallet_MC_buy_user->amount_inorder = bcsub($wallet_MC_buy_user->amount_inorder, $order->order_total, 9);
                    $wallet_MC_buy_user->save();
                }

                //Getting Market wallet for BUY user and updating if exists other wise inserting
                $wallet_M_buy_user    = Wallet::where('user_id', $order->order_user_id)
                                            ->where('coin_id', $order->order_coin_id)
                                            ->first();

                if( $wallet_M_buy_user ) {

                    $wallet_M_buy_user->amount     = bcadd($wallet_M_buy_user->amount, $order->order_amount, 9);
                    $wallet_M_buy_user->save();
                } else {

                    $wallet_M_buy_user  = Wallet::create([

                        'tx_id'     => 0,
                        'user_id'   => $order->order_user_id,
                        'coin_id'   => $order->order_coin_id,
                        'name'      => $order->order_market,
                        'amount'    => $order->order_amount
                    ]);
                }

                //Cloning the row.
                $transactionBuy   = $transactionSELL->replicate();

                //Updating the required fields for buy transaction
                $transactionBuy->transaction_txid   = md5( $order->order_id . '&' . $exchangeOrderSELL->order_id );

                $transactionBuy->transaction_buysell                    = 1;
                $transactionBuy->transaction_maincoin_wallet_id         = $wallet_MC_buy_user->id;
                $transactionBuy->transaction_maincoin_wallet_balance    = $wallet_MC_buy_user->amount;
                $transactionBuy->order_id                               = $order->order_id;
                $transactionBuy->order_matched                          = $exchangeOrderSELL->order_id;

                $transactionBuy->save();

                $wallet = Wallet::find($wallet_M_buy_user->id);
                $wallet->tx_id  = $transactionBuy->transaction_id;
                $wallet->save();

            DB::commit(); //Transaction ends here.

            // Publishing the activity. Client side listeners will read this.
            $redis  = Redis::connection();
            $redis->publish('update-order-vue', json_encode(['event' => 'hide_exchange_selection',
                'data' => [
                    'order_id'      => $order_id,
                    'market'        => $order->order_market,
                    'order_user_id' => $order->order_user_id,
                    'sell_user_id'  => Auth::id(),
                    'amount'        => $order->order_amount
                ]
            ]));
        }

        return response()->json([]);
    }

    public function tradeStrategy($order_id) {
        $this->executeOrder($order_id);
    }

    public function executeOrder($order_id) {


        $order          = Order::getOrderByOrderID( $order_id );
        if( $order ) {

            //Checking wallet availability or market
            $wallet = Wallet::getBalance( $order->order_coin_id, Auth::id() );

            $wallet_balance = bcsub($wallet['amount'], $wallet['inorder'], 9);

            if( $order->order_amount > $wallet_balance ) {

                return response()->json("low-wallet");
            }

            //Transaction starts
            DB::beginTransaction();

                $order->order_executed  = 1;
                $order->save();

                //Creating the SELL order for this exchange

                //In the case of exchange SELL order we are not updating the wallet amount in order balance when order is placed.
                //Instead of that we are suddenly updating the wallet balance when transaction is happen.

                //Copying buy order object to new sell object and push to database after field modification
                $exchangeOrderSELL      = $order->replicate();

                $exchangeOrderSELL->order_user_id      = Auth::id();
                $exchangeOrderSELL->order_ip           = $this->getClientIp();
                $exchangeOrderSELL->order_buysell      = 2;
                $exchangeOrderSELL->order_executed     = 1;
                $exchangeOrderSELL->save();

                //updating wallet for current user main coin
                $walletSELL     = Wallet::where('user_id', Auth::id())
                                        ->where('coin_id', $order->order_coin_id)
                                        ->first();

                $wallet_market_new  = bcsub($walletSELL->amount, $order->order_amount, 9);
                $walletSELL->amount = $wallet_market_new;
                $walletSELL->save();

                //getting current user's maincoin balance
                $wallet_MC_sell_user    = Wallet::where('user_id', Auth::id())
                    ->where('coin_id', $order->order_maincoin_id)
                    ->first();

                //IF no main coin in wallet add new row otherwise update exisitng
                if( $wallet_MC_sell_user ) {

                    $wallet_MC_sell_ID              = $wallet_MC_sell_user->id;
                    $wallet_MC_sell_balance         = bcadd($wallet_MC_sell_user->amount, $order->order_total, 9);
                    $wallet_MC_sell_user->amount    = $wallet_MC_sell_balance;
                    $wallet_MC_sell_user->save();
                } else {

                    $wallet = Wallet::create([

                        'tx_id'     => 0,
                        'user_id'   => Auth::id(),
                        'coin_id'   => $order->order_maincoin_id,
                        'name'      => $order->order_maincoin,
                        'amount'    => $order->order_total
                    ]);

                    $wallet_MC_sell_balance = $order->order_total;
                    $wallet_MC_sell_ID      = $wallet->id;
                }

                //Transaction table entries
                $transactionSELL    = new Transaction;

                $transactionSELL->transaction_user_id                   = Auth::id();
                $transactionSELL->transaction_txid                      = md5( $exchangeOrderSELL->order_id . '&' . $order->order_id );
                $transactionSELL->transaction_addr                      = md5($this->getClientIp());
                $transactionSELL->transaction_amount                    = $order->order_amount;
                $transactionSELL->transaction_market                    = $order->order_coin_id;
                $transactionSELL->market_name                           = $order->order_market;
                $transactionSELL->transaction_ip                        = $this->getClientIp();
                $transactionSELL->transaction_price                     = $order->order_price;
                $transactionSELL->transaction_buysell                   = 2;
                $transactionSELL->transaction_maincoin_amount           = $order->order_total;
                $transactionSELL->transaction_maincoin                  = $order->order_maincoin_id;
                $transactionSELL->maincoin_name                         = $order->order_maincoin;
                $transactionSELL->transaction_maincoin_wallet_id        = $wallet_MC_sell_ID;
                $transactionSELL->transaction_maincoin_wallet_balance   = $wallet_MC_sell_balance;
                $transactionSELL->transaction_status                    = 1;
                $transactionSELL->order_id                              = $exchangeOrderSELL->order_id;
                $transactionSELL->order_matched                         = $order->order_id;

                $transactionSELL->save();

                //updating the wallet with transaction_id for current user
                $wallet = Wallet::find($wallet_MC_sell_ID);

                $wallet->tx_id  = $transactionSELL->transaction_id;
                $wallet->save();

                //Getting maincoin wallet for BUY user and updating it
                $wallet_MC_buy_user    = Wallet::where('user_id', $order->order_user_id)
                                            ->where('coin_id', $order->order_maincoin_id)
                                            ->first();

                if( $wallet_MC_buy_user ) {

                    $wallet_MC_buy_user->amount         = bcsub($wallet_MC_buy_user->amount, $order->order_total, 9);
                    $wallet_MC_buy_user->amount_inorder = bcsub($wallet_MC_buy_user->amount_inorder, $order->order_total, 9);
                    $wallet_MC_buy_user->save();
                }

                //Getting Market wallet for BUY user and updating if exists other wise inserting
                $wallet_M_buy_user    = Wallet::where('user_id', $order->order_user_id)
                                            ->where('coin_id', $order->order_coin_id)
                                            ->first();

                if( $wallet_M_buy_user ) {

                    $wallet_M_buy_user->amount     = bcadd($wallet_M_buy_user->amount, $order->order_amount, 9);
                    $wallet_M_buy_user->save();
                } else {

                    $wallet_M_buy_user  = Wallet::create([

                        'tx_id'     => 0,
                        'user_id'   => $order->order_user_id,
                        'coin_id'   => $order->order_coin_id,
                        'name'      => $order->order_market,
                        'amount'    => $order->order_amount
                    ]);
                }

                //Cloning the row.
                $transactionBuy   = $transactionSELL->replicate();

                //Updating the required fields for buy transaction
                $transactionBuy->transaction_txid   = md5( $order->order_id . '&' . $exchangeOrderSELL->order_id );

                $transactionBuy->transaction_buysell                    = 1;
                $transactionBuy->transaction_maincoin_wallet_id         = $wallet_MC_buy_user->id;
                $transactionBuy->transaction_maincoin_wallet_balance    = $wallet_MC_buy_user->amount;
                $transactionBuy->order_id                               = $order->order_id;
                $transactionBuy->order_matched                          = $exchangeOrderSELL->order_id;

                $transactionBuy->save();

                $wallet = Wallet::find($wallet_M_buy_user->id);
                $wallet->tx_id  = $transactionBuy->transaction_id;
                $wallet->save();

            DB::commit(); //Transaction ends here.

            // Publishing the activity. Client side listeners will read this.
            $redis  = Redis::connection();
            $redis->publish('update-order-vue', json_encode(['event' => 'hide_exchange_selection',
                'data' => [
                    'order_id'      => $order_id,
                    'market'        => $order->order_market,
                    'order_user_id' => $order->order_user_id,
                    'sell_user_id'  => Auth::id(),
                    'amount'        => $order->order_amount
                ]
            ]));
        }

        return response()->json([]);
    }

    /**
     * Fetching available coins from Wallet to buy
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initializeBuyBox(Request $request)
    {

        $coinID             = $request->input('coin_id');
        $availableBalance   = Wallet::getBalance($coinID, Auth::id());

        return response()->json($availableBalance);
    }

    /**
     * Fetching avilable coins from Wallet to sell
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function initializeSellBox(Request $request)
    {

        $coinID             = $request->input('coin_id');
        $availableToSell   = Wallet::getBalance($coinID, Auth::id());

        return response()->json($availableToSell);
    }

    /**
     * Fetch all orders based on Market and Order Main Coin
     *
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function fetchOrdersByCoins(Request $request) {

        $market = $request->input('market');
        $coin   = $request->input('coin');

        $orders = Order::getOrdersByCoins($market, $coin);

        $totalSellAmount = 0;
        $totalBuyAmount = 0;

        $orders->map(function (Order $order) use (&$totalBuyAmount, &$totalSellAmount) {
            if ($order->order_buysell == 1) {
                $totalBuyAmount = bcadd($totalBuyAmount, $order->order_amount, 9);
            } else if ($order->order_buysell == 2) {
                $totalSellAmount = bcadd($totalSellAmount, $order->order_amount, 9);
            }
        });

        $returnOrders = [
            'total_buy_amount'  => $totalBuyAmount,
            'total_sell_amount' => $totalSellAmount
        ];

        $key            = $coin . '-' . $market;
        $returnOrders[$key]   = [
            'BUY'   => [],
            'SELL'  => []
        ];


        foreach ($orders as $order) {

            if($order->order_buysell == 1) {

                $returnOrders[$key]['BUY'][]    = $order;
            }
            else {

                $returnOrders[$key]['SELL'][]   = $order;
            }
        }

        return response()->json($returnOrders);
    }

    /**
     * Returns chart data based on selected symbol
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData(Request $request)
    {

        $symbol     = $request->input('symbol');
        $coinID     = $request->input('coin_id');

        $marketCap  = new MarketCap;
        $chartData      = $marketCap->getChartData( $symbol );

        if( !count($chartData) ) {

            $transactions    = new Transaction;

            $chartData       = $transactions->getChartData( $coinID );
            $total           = $transactions->getValueTotalByCoin( $coinID );
            $updown          = $transactions->upDownByCoin( $coinID );
        } else {

            $total          = $marketCap->getValueTotalByCoin( $symbol );
            $updown         = $marketCap->upDownByCoin( $symbol );
        }

        $graph  = [];
        $volume = [];

        //Values passing to high chart should be numbers
        foreach ($chartData as $key => $chart) {

            $graph[$key]  = [
                (int)$chart->updated_date * 1000, //Converting unix timestamp to javascript format
                (double)$chart->open_price,
                (double)$chart->high_price,
                (double)$chart->low_price,
                (double)$chart->close_price
                ];

            $volume[$key]   = [
                (int)$chart->updated_date * 1000, //Converting unix timestamp to javascript format
                (double)$chart->average_volume
            ];
        }

        $graphData  = [
            'graph'     => $graph,
            'volume'    => $volume,
            'total'     => $total,
            'updown' => $updown,
            'price' => [
                'usd' => Helper::getCurrentPrice($symbol, 'usd')
            ]
        ];

        return response()->json($graphData);
    }

    /**
     * Update values based on chart zoom dates
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartZoomed(Request $request)
    {
        $marketCap  = new MarketCap;
        $startDate  = date('Y-m-d H:i:s', $request->input('start_date'));
        $endDate    = date('Y-m-d H:i:s', $request->input('end_date'));
        $symbol     = $request->input('symbol');
        $coinID     = $request->input('coin_id');

        $total      = $marketCap->getValueTotalByCoin($symbol, $startDate, $endDate);
        $updown     = $marketCap->upDownByCoin($symbol, $startDate, $endDate);

        if(!$total && !$updown) {

            $transactions    = new Transaction;

            $total      = $transactions->getValueTotalByCoin( $coinID, $startDate, $endDate );
            $updown     = $transactions->upDownByCoin( $coinID, $startDate, $endDate );
        }


        $updatedData    = [
            'total' => $total,
            'updown'=> $updown
        ];

        return response()->json($updatedData);
    }

    /**
     * Fetch all orders to execute by user id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ordersByUser()
    {
        $orders = Order::getOrderByUser();

        return response()->json($orders);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function transactionsByUser(Request $request) {
        $type = (bool) $request->show_sell ? 2 : 1;
        $transactions   = Transaction::getTransactionsByUser(Auth::id(), $type);
        return response()->json($transactions);
    }


    /**
     * Fetch all Order history
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderHistory()
    {
        $order_transactions = Transaction::getTransactionsByCoin('2');
        return response()->json($order_transactions);
    }

    /**
     * Delete an order by orderID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOrder(Request $request)
    {
        $order_id   = $request->input('order_id');

        //Get the corresponding order details.
        $order      = Order::getOrderByOrderID( $order_id );

        if($order->order_buysell == 1) {

            $coin   = $order->order_maincoin_id;
            $value  = doubleval($order->order_total);
        } else {

            $coin   = $order->order_coin_id;
            $value  = doubleval($order->order_amount);
        }

        if($order->gas_fee != 0) {

            //Updating ETH wallet
            Wallet::updateWalletForDeletedOrder(2, $order->gas_fee);
        }

        //Updating wallet
        Wallet::updateWalletForDeletedOrder($coin, $value);

        $orderDelete      = Order::deleteOrderByID( $order_id );

//        if(!$orderDelete) return response()->json( $order );

        // Publishing the activity. Client side listeners will read this.
        $redis  = Redis::connection();
        $redis->publish('update-order-vue', json_encode(['event' => 'orderdeleted', 'data' => [
            'order_id' => $order_id,
            'order_buysell' => $order->order_buysell
        ]]));

        return response()->json( [
            'delete'    => $orderDelete,
            'amount'    => $value,
            'type'      => $order->order_buysell
            ] );
    }

    public function admin(){
        return view('app.vue-dashboard', compact('admin') );
    }


}
