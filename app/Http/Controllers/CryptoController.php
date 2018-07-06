<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\Blockchain\Ethereum\Types\BlockNumber;
use App\Models\Addresses;
use App\Models\Coin;
use App\Models\Fee;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawToken;
use App\Notifications\SuccessfulTransaction;
use App\Notifications\WithdrawConfirmEmail;
use App\Services\ETNService;
use App\Traits\WalletTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Blockchain\EthereumController;
use App\Http\Controllers\Blockchain\Ethereum\EthereumClient;
use App\Http\Controllers\Blockchain\Ethereum\Types\Address as Address;
use App\Http\Controllers\Blockchain\Ethereum\Types\Ether as Ether;
use App\Services\Blockchainservice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class CryptoController extends Controller
{
    use WalletTrait;

    /**
     * Generating the new Coin address
     *
     * @param null $coin_id
     * @return null|String
     */
    public function getAddressNew( $coin_id = null ){
        if(is_null( $coin_id )) return $coin_id;
        return Addresses::generateAddressByUserID( Auth::id(), $coin_id );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(Request $request) { // ToDo: Add order and sync local wallet

        $input      = $request->all();

        $coin_id    = $input['coinid'];
        $to         = $input['toaddress'];
        $amount     = $input['amount'];
        $show_data  = $input['show_data'];

        $coin       = Coin::getNameOrTypeByID( $coin_id );

        $coin_name  = Coin::getNameByID( $coin_id );

        $exist_address  = null;

        //Getting the ETH related coins from .env file
        $Family          = Coin::getFamilyCoins( $coin_id );

        $token = $coin_id . ':' . $to . ':' . $amount . ':' . $show_data;

        $encode_token = base64_encode($token);

        $encode_token = substr($encode_token, 0, strlen($encode_token) - 2);

        //If BTC, ETH or XEM - taking that address and pk, else take address with coin id
        if(in_array($coin_id, $Family) && in_array($Family[0], [1, 2, 9])) {

            $exist_address = Addresses::where('address_coin', $Family[0])
                ->where('address_user_id', Auth::id() )
                ->orderBy('address_coin')
                ->first(['address_address', 'pk']);
        } else {

            $exist_address = Addresses::getAddressByUserID(auth()->user()->id, $coin_id);
        }

        if(!$exist_address) {

            return response()->json([
                'status'    => false,
                'message'   => 'Your do not have ' . $coin . ' address in our database'
            ]);
        }

        $pk     = $exist_address->pk;
        $from   = $exist_address->address_address;

        if($from == $to) {

            Helper::LogError([
                'from'      => $from,
                'to'        => $to,
                'status'    => false,
                'message'   => 'Withdraw to same address not permitted.',
            ], 'Same from and to address error');

            return response()->json([

                'status'    => false,
                'message'   => 'Withdraw to same address not permitted.'
            ]);
        }

        switch($amount) {

            case '':
            case '0':

                return response()->json([
                    'status'    => false,
                    'message'   => 'Please enter a valid amount to withdraw '
                ]);
        }

        $withdraw_limit  = Coin::getDailyWithdrawLimit( $coin_id );
        //Setting withdraw limit by coin_id, if -1 => no limit for withdraw
        if($withdraw_limit != -1) {

            if( $amount > $withdraw_limit ) {

                return response()->json([
                    'status'    => false,
                    'message'   => 'You can\'t withdraw an amount greater than ' . $withdraw_limit
                ]);
            }
        }

        $wallet         = Wallet::getBalance( $coin_id, Auth::id() );
        $wallet_balance = bcsub( $wallet['amount'], $wallet['inorder'],9 );

        //Checking sufficient Balance is available
        if( $wallet_balance < $amount ) {

            return response()->json([
                'status'    => false,
                'message'   => 'Insufficient Balance in your wallet'
            ]);
        }

        if($withdraw_limit != -1) {

            $daily_transaction_amount = Transaction::getDailyTransactionAmount(Auth::id(), $coin_id, 2);
            if ($daily_transaction_amount >= $withdraw_limit) {

                return response()->json([
                    'status' => false,
                    'message' => 'You already withdraw ' . $daily_transaction_amount . ' of ' . $withdraw_limit . ' ' . $coin
                ]);
            }
        }

        $blockchain_service = new Blockchainservice;
        $blockchain_service->setCoin( $coin_id );
        //Get Gas Fees
        $gasFee         = $blockchain_service->getFee( $amount );

        if(! $gasFee['status']) {

           Helper::LogError(json_encode($gasFee), 'Gas fee error from CryptoController, Line No: 146');

            return response()->json([
                'status'    => false,
                'message'   => "You can't withdraw " . $coin_name . " this time, Please try again later"
            ]);
        }

        $withdraw_fee_percentage    = Fee::getFee('withdraw');
        $withdraw_fee               = bcmul($amount, bcdiv($withdraw_fee_percentage, 100, 9), 9);

        if($coin_id == 12) {

            $total_fee      = $gasFee['data'] . ' ETH';
            $credit_to_user = $amount;

        } elseif( $coin == 'erc20') {

            $total_fee      = $gasFee['data'] . ' ETH & ' . $withdraw_fee . ' ' . $coin;
            $credit_to_user = bcsub($amount, $withdraw_fee, 9);

        } else {

            //Adding 1 first then subtracting after getting the sum, Because the decimal numbers like .00001 is not getting exact precision while adding with another fractional number.
            $total_fee                  = bcsub(bcadd(1 + $gasFee['data'], $withdraw_fee, 9), 1, 9);
            $credit_to_user             = bcsub($amount, $total_fee, 9);
        }

        WithdrawToken::create([
            'token' => $encode_token,
            'user_id' => Auth::id()
        ]);


        if($show_data == 1) {

            if( $credit_to_user <= 0) {
                return response()->json([
                    'status'        => 'show_data',
                    'amount'        => $amount,
                    'total_fee'     => $total_fee,
                    'credit_amount' => '',
                    'message'       => 'Withdraw fees is higher than or equal to your transaction amount. Please enter a higher amount to withdraw'
                ]);
            } else {
                // Withdraw confirm email notify the user
                Auth::user()->notify( new WithdrawConfirmEmail(
                    $amount,
                    $to,
                    'withdraw',
                    $coin,
                    $encode_token
                ));

                return response()->json([
                    'status'        => 'show_data',
                    'amount'        => $amount,
                    'total_fee'     => $total_fee,
                    'credit_amount' => $credit_to_user <= 0 ? '' : $credit_to_user,
                    'message'       => 'Please check your email to confirm withdraw'
                ]);
            }
        }

        // Withdraw confirm email notify the user
        Auth::user()->notify( new WithdrawConfirmEmail(
            $amount,
            $to,
            'withdraw',
            $coin,
            $encode_token
        ));
                
        return response()->json([
            'status'     => false,
            'message'    => "Please check your email to confirm withdraw"
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdrawConfirm($info)
    {

        $tokenExists = WithdrawToken::query()->where('token', $info)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$tokenExists) return response()->json([
            'status' => false,
            'message' => 'Withdraw token is invalid'
        ]);

        $decode_info = base64_decode($info . '==');

        $infos = explode(":", $decode_info);

        if(!is_array($infos)){
            return response()->json([
                'status'     => false,
                'message'    => "Your withdraw not confirmed"
            ]);
        }

        $coin_id    = $infos[0];
        $to         = $infos[1];
        $amount     = $infos[2];
        $show_data  = 0;

        $coin       = Coin::getNameOrTypeByID( $coin_id );

        $coin_name  = Coin::getNameByID( $coin_id );

        $exist_address  = null;

        //Getting the ETH related coins from .env file
        $Family          = Coin::getFamilyCoins( $coin_id );

        //If BTC, ETH or XEM - taking that address and pk, else take address with coin id
        if(in_array($coin_id, $Family) && in_array($Family[0], [1, 2, 9])) {

            $exist_address = Addresses::where('address_coin', $Family[0])
                ->where('address_user_id', Auth::id() )
                ->orderBy('address_coin')
                ->first(['address_address', 'pk']);
        } else {

            $exist_address = Addresses::getAddressByUserID(auth()->user()->id, $coin_id);
        }

        if(!$exist_address) {

            return response()->json([
                'status'    => false,
                'message'   => 'Your do not have ' . $coin . ' address in our database'
            ]);
        }
        
        $pk     = $exist_address->pk;
        $from   = $exist_address->address_address;

        $blockchain_service = new Blockchainservice;
        $blockchain_service->setCoin( $coin_id );

        //Get Gas Fees
        $gasFee         = $blockchain_service->getFee( $amount );

        if(! $gasFee['status']) {

           Helper::LogError(json_encode($gasFee), 'Gas fee error from CryptoController, Line No: 146');

            return response()->json([
                'status'    => false,
                'message'   => "You can't withdraw " . $coin_name . " this time, Please try again later"
            ]);
        }

        $withdraw_fee_percentage    = Fee::getFee('withdraw');
        $withdraw_fee               = bcmul($amount, bcdiv($withdraw_fee_percentage, 100, 9), 9);

        if($coin_id == 12) {

            $total_fee      = $gasFee['data'] . ' ETH';
            $credit_to_user = $amount;

        } elseif( $coin == 'erc20') {

            $total_fee      = $gasFee['data'] . ' ETH & ' . $withdraw_fee . ' ' . $coin;
            $credit_to_user = bcsub($amount, $withdraw_fee, 9);

        } else {

            //Adding 1 first then subtracting after getting the sum, Because the decimal numbers like .00001 is not getting exact precision while adding with another fractional number.
            $total_fee                  = bcsub(bcadd(1 + $gasFee['data'], $withdraw_fee, 9), 1, 9);
            $credit_to_user             = bcsub($amount, $total_fee, 9);
        }


        $amount_after_fees  = bcsub($amount, $withdraw_fee, 9);

        // TODO: Check if fee's are applied to fee Account??
        $transfer_result    = $blockchain_service->transfer($coin, $pk, $from, $to, $amount_after_fees);

        //Checking transfer function executed in block chain part or not
        //If executed will check the result is success or not
        if(is_array($transfer_result)) {
    
            if(array_key_exists('message', $transfer_result) ) return response()->json([
                'status'    => false,
                'message'   => $transfer_result['message']
            ]);

            try {

                $data   = $this->withDrawFromWallet( Auth::id(), $coin_id, $amount, $transfer_result['data']['txid'] ); // When succesfull it is affecting the amount in wallet

                /**
                 * Updates wallet if address and coin are equals
                 */
                $this->updateRecipientWalletIfExists($to, $coin_name, $amount);

                if($data) {

                    WithdrawToken::query()->where('token', $info)
                        ->where('user_id', Auth::id())
                        ->delete();

                    // Email notify the user
                    Auth::user()->notify( new SuccessfulTransaction(
                        $amount,
                        $transfer_result['data']['txid'],
                        'withdraw',
                        $coin
                    ));
                
                    $this->checkBeneficiary($to, $coin_id, $coin, $amount_after_fees, $transfer_result['data']['txid']);

                    $withDrawGas    =  $blockchain_service->getFee( $withdraw_fee );

                    $this->executeFees( $blockchain_service, 0, $withdraw_fee, $withDrawGas, $from, $coin_id, 2 );

                    return response()->json([
                        'status'     => true,
                        'message'    => $amount_after_fees . ' '. $coin .' transferred to address ' . $to
                    ]);
                }
            }catch (\Exception $exception) {

                $result_error = [
                    'status'        => false,
                    'coin'          => $coin,
                    'error'         => $exception->getMessage(),
                    'blockchain'    => $transfer_result,
                    'message'       => 'It seems some issues in this transaction. Please contact administrator'
                ];
                Helper::LogError(json_encode($result_error), 'CryptoController Wallet and Transaction Update, Line No: 200' );

                return $result_error;
            }
        }

        return response()->json([
            'status'     => false,
            'message'    => "Can't perform the transaction at this moment pls try again later"
        ]);
    }

    /**
     * Check the Beneficiary address exists in our DB, if exists writing the transaction to our table
     *
     * @param $to
     * @param $coin_id
     * @param $coin
     * @param $amount
     * @param $rx_id
     * @return mixed
     */
    public function checkBeneficiary( $to, $coin_id, $coin, $amount, $rx_id ) {

        $address    = Addresses::where('address_address', $to)
            ->first(['address_id', 'address_user_id']);

        if(! count($address)) return false;

        try {

            $wallet     = Wallet::where('address_id', $address->address_id)
                ->where('coin_id', $coin_id)
                ->first(['id', 'amount']);
      
            if(!count($wallet)) {

                $error  = [

                    'SQL'           => Wallet::where('address_id', $address->address_id)->where('coin_id', $coin_id)->toSql(),
                    'address'       => $address,
                    'to_address'    => $to,
                    'coin_id'       => $coin_id,
                    'coin_name'     => $coin
                ];

                Helper::LogError( $error, 'CryptoController Beneficiary wallet not exists, Line No: 259' );
                return false;
            }

            $wallet_balance = bcadd($wallet[0]->amount, $amount, 9);

            $user   = User::find($address[0]->address_user_id);

            if($coin == 'erc20') {

                $coin = Coin::getNameByID( $coin_id );
            }

            $user->notify( new SuccessfulTransaction(
                $amount,
                $rx_id,
                'deposit',
                $coin
            ));

            return $this->generateTransaction( $address->address_user_id, $wallet->id, $coin_id, $amount, $wallet_balance, $rx_id['txid'], 1, 1 );
        } catch (\Exception $exception) {

            $error  = [

                'Message' => $exception->getMessage(),
                'Trace'    => $exception->getTrace()
            ];

            Helper::LogError( $error, 'CryptoController Check Beneficiary error, Line No: 250' );
        }

        return false;
    }

    /**
     * This will update same currency wallet
     * so there is no need for syncing
     *
     * @param $address
     * @param $coinName
     * @param $amount
     */
    public function updateRecipientWalletIfExists ($address, $coinName, $amount)
    {
        $wallet = Wallet::query()->whereHas('address', function ($has) use ($address) {
            $has->where('address_address', $address);
        })
            ->where('name', $coinName)
            ->first();

        if ($wallet) {
            $wallet->amount = bcadd($wallet->amount, $amount ,9);
            $wallet->save();
        }
    }
}
