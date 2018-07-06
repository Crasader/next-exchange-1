<?php
/**
 * Trait to handle wallet functions
 */

namespace App\Traits;


use App\Helpers\Helper;
use App\Models\Addresses;
use App\Models\Coin;
use App\Models\CoinFeeAddresses;
use App\Models\CoinFeeTransaction;
use App\Models\CoinFeeWallet;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Blockchainservice;
use Illuminate\Support\Facades\Log;

trait WalletTrait
{
    use CaptureIpTrait;

    /**
     * Deposit the amount to wallet with user and coin id
     * Updates if coin exists in wallet otherwise insert new row
     *
     * @param $user_id
     * @param $coin_id
     * @param $amount
     * @param bool $fromBlockChain
     * @param string $type = crypto / fiat
     * @param int $payment_id
     * @param int $status
     * @return array | bool
     */
    public function depositToWallet( $user_id, $coin_id, $amount, $fromBlockChain = false, $type = 'crypto', $payment_id = null, $status = 0) {

        \Debugbar::notice('Deposit to Wallet started');

        $wallet_id      = 0;
        $wallet_balance = 0;

        $wallet         = new Wallet;

        $wallet_coin    = $wallet->where('user_id', $user_id)
            ->where('coin_id', $coin_id)
            ->first();

        //Check coin exists in wallet
        if($wallet_coin) {

            $wallet_id      = $wallet_coin->id;

            if( $status == 1 ) {

                $wallet_coin->amount = bcadd($wallet_coin->amount, $amount, 9);
            }

            $wallet_balance = $wallet_coin->amount;

            if( $type == 'fiat' ) {
                // Coin is FIAT
                \Debugbar::notice('Coin is FIAT');
                \Log::channel('transactions')->info('Start processing fiat coin');
                \Log::channel('transactions')->debug($wallet_coin);
                \Debugbar::info($wallet_coin);
                //$wallet_coin->save();
                return $this->generateTransaction( $user_id, $wallet_id, $coin_id, $amount, $wallet_balance, $payment_id, $status, 1 );
            }

            //If not fiat coins
            $wallet_balance = bcadd($wallet_coin->amount, $amount, 9);

            if($fromBlockChain) {
                //Updating from BlockChain, then just update the amount from block chain
                $wallet_coin->amount    = bcadd(0, $amount, 9);
                $wallet_balance         = $amount;
            } else {
                //Updating from user transfer
                $wallet_coin->amount    = bcadd($wallet_coin->amount, $amount, 9);
            }
            $wallet_coin->save();
        } else {
            //If no wallet exists inserting new row
            $wallet->tx_id          = Helper::uniqueID();
            $wallet->user_id        = $user_id;
            $wallet->coin_id        = $coin_id;
            $wallet->name           = Coin::getNameByID( $coin_id );
            $wallet->amount         = $type == 'fiat' ? 0 : number_format($amount, 9);
            $wallet->amount_inorder = number_format(0, 9);
            $wallet->save();

            $wallet_id              = $wallet->id;
            $wallet_balance         = $wallet->amount;
        }
        return $this->generateTransaction( $user_id, $wallet_id, $coin_id, $amount, $wallet_balance, $payment_id, 0, 1 );
    }

    /**
     * Creates transaction entry for deposit and withdraw
     *
     * @param $user_id
     * @param $wallet_id
     * @param $coin_id
     * @param $amount
     * @param $wallet_balance
     * @param $rx_id
     * @param int $status
     * @param int $type
     * @return array
     */
    private function generateTransaction( $user_id, $wallet_id, $coin_id, $amount, $wallet_balance, $rx_id = null, $status = 0, $type = 0) {

        \Debugbar::notice('Saving transaction in database');
        \Log::channel('transactions')->info('Saving transaction in database');

        try {
            $transaction    = new Transaction;

            $transaction->transaction_user_id                   = $user_id;
            $transaction->transaction_txid = md5(Helper::uniqueID());
            $transaction->transaction_rxid = is_null($rx_id) ? md5(Helper::uniqueID()) : $rx_id;
            $transaction->transaction_addr                      = md5($wallet_id);
            $transaction->transaction_amount                    = bcadd($amount, 0,9);
            $transaction->transaction_fee                       = bcadd(0, 0, 9);
            $transaction->transaction_cost                      = bcadd(0, 0,9);
            $transaction->transaction_ip                        = $this->getClientIp();
            $transaction->transaction_price                     = bcadd(0, 0,9);
            $transaction->transaction_buysell                   = 0; // 0 = Deposit, 1 = Buy, 2 = Sell
            $transaction->transaction_maincoin                  = $coin_id;
            $transaction->maincoin_name                         = Coin::getNameByID( $coin_id );
            $transaction->transaction_maincoin_wallet_id        = $wallet_id;
            $transaction->transaction_maincoin_amount           = bcadd($amount, 0,9);
            $transaction->transaction_maincoin_wallet_balance   = $wallet_balance;
            $transaction->transaction_status                    = $status;
            $transaction->transaction_type                      = $type;

            //$transaction->save();
            \Debugbar::info($transaction);
            \Log::channel('transactions')->debug($transaction);

            if ( $transaction->save() ) {
                return [
                    'wallet_id'         => $wallet_id,
                    'transaction_id'    => $transaction->transaction_id
                    ];
            }
        } catch (\Exception $e) {
            Helper::LogError(json_encode($e->getCode() . ':' . $e->getMessage()));
        }
        return false;
    }

    /**
     * Update wallet balance/transaction_id after deposit
     *
     * @param $wallet_id
     * @param $amount
     * @param bool $change_tx_status
     * @param null $tx_id
     * @return bool
     */
    public function updateWallet($wallet_id, $amount, $change_tx_status = false, $tx_id = null)
    {

        $wallet = Wallet::find($wallet_id);

        if (!$wallet) return false;

        $wallet->amount = bcadd($wallet->amount, $amount, 9);

        if (!is_null($tx_id)) {
            $wallet->tx_id = $tx_id;
        }

        if ($wallet->save()) {

            \Log::channel('transactions')->info('Wallet has been updated');
            \Log::channel('transactions')->debug($wallet);

            if (!$change_tx_status) return $wallet->id;

            $transaction_id = !is_null($tx_id) ? $tx_id : $wallet->tx_id;

            if ($amount == 0) return true;

            \Log::channel('transactions')->info('Transaction has been updated');
            \Log::channel('transactions')->debug($transaction_id);

            // Transaction ID is not ID but txID
            return $this->updateTransactionStatus($transaction_id, $wallet->amount);
        }
    }

    /**
     * Update the transaction status after wallet update if required
     *
     * @param $transaction_id
     * @param $wallet_amount
     * @return bool
     */
    private function updateTransactionStatus( $transaction_id, $wallet_amount ) {
        if (is_numeric($transaction_id)) $transaction = Transaction::find($transaction_id);
        else $transaction = Transaction::where('transaction_rxid', '=', $transaction_id)->first();

        $transaction->transaction_maincoin_wallet_balance   = $wallet_amount;
        $transaction->transaction_confirmations             = 1;
        $transaction->transaction_status                    = 1;

        if ($transaction->save()) {
            \Log::channel('transactions')->info('Transaction finished!');
            return $transaction->transaction_maincoin_wallet_id;
        }
        return false;
    }

    /**
     * Withdraws the amount from wallet with user and coin id
     *
     * @param $user_id
     * @param $coin_id
     * @param $amount
     * @param null $rx_id
     * @return array | bool
     */
    public function withDrawFromWallet($user_id, $coin_id, $amount, $rx_id = null)
    {

        $wallet = Wallet::where('coin_id', $coin_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$wallet) return false;

        $wallet->amount = bcsub($wallet->amount, $amount, 9);
        $wallet->save();

        return $this->generateTransaction($user_id, $wallet->id, $coin_id, $amount, $wallet->amount, $rx_id, 1, 2);
    }

    /**
     * Executes the fees transactions and wallet updates
     *
     * @param $block_chain
     * @param $order_id
     * @param $fee_amount
     * @param $gas_fee
     * @param $from_address
     * @param $coin_id
     * @param int $type
     * @return bool
     */
    public function executeFees( $block_chain, $order_id, $fee_amount, $gas_fee, $from_address, $coin_id, $type = 3 ) {

        $feeCollector   = env('FEE_COLLECTOR', 2);
        $feeAddress     = Addresses::getAddressByUserID( $feeCollector, $coin_id );

        try{

            $transfer       = $block_chain->transfer($from_address->pk, $from_address->address_address, $feeAddress->address_address, $fee_amount);

            if($transfer) {

                //Saving to transaction table;
                $transaction    = new Transaction;

                $transaction->transaction_user_id       = $feeCollector;
                $transaction->transaction_txid          = Helper::uniqueID();
                $transaction->transaction_rxid          = $transfer['data'];
                $transaction->transaction_addr          = $from_address->address_address; //From which address fees is transferred
                $transaction->transaction_amount        = $fee_amount;
                $transaction->is_fees                   = 1;
                $transaction->gas_fee_amount            = $gas_fee;
                $transaction->transaction_confirmations = 0;
                $transaction->transaction_status        = 1;
                $transaction->transaction_type          = $type;
                $transaction->order_id                  = $order_id;
                $transaction->save();

                $actual_amount  = bcsub($fee_amount, $gas_fee, 9);

                $wallet         = Wallet::where('user_id', $feeCollector)
                    ->where('coin_id', $coin_id)
                    ->first();

                $wallet->amount = bcadd($wallet->amount, $actual_amount);

                if($wallet->save()) {

                    $update_confirmation    = Transaction::find($transaction->transaction_id);

                    $update_confirmation->transaction_confirmations = 1;
                    $update_confirmation->save();
                }
            }

            return true;
        } catch (\Exception $exception) {

            Log::error(json_encode([

                'error_code'    => $exception->getCode(),
                'message'       => $exception->getMessage(),
                'line_no'       => $exception->getLine()
            ]));
        }

    }
}