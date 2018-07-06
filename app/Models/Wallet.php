<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Services\Blockchainservice;
use App\Traits\SyncWalletBalanceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class Wallet extends Model
{

    use SyncWalletBalanceTrait;

	protected $table = 'wallet';

    protected $fillable = [
        'tx_id', 'user_id', 'coin_id', 'name', 'amount', 'synced_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public static function createdDefaultWallets( $default_wallets, $user_id ) {

        /**
         * Checking any of the default wallets created already.
         *
         * Some cases because of node issues wallets may not be created
         * To handle this situation this checking is added
         */
        $walletCreated      = Wallet::where('user_id', $user_id)
            ->whereIn('coin_id', $default_wallets)
            ->get(['coin_id']);

        if(! count( $walletCreated )) return [];

        $wallet_created = [];
        foreach($walletCreated as $created) {

            $wallet_created[] = $created->coin_id;
        }

        return $wallet_created;
    }

    /**
     * Get wallet and address details of user's active wallet coins
     *
     * @param $user_id
     * @return array
     */
   public static function getWalletWithAddress( $user_id ) {

       $enabled_coins           = [];
       $btcAddress              = Addresses::getAddressByUserID( $user_id, 1 );
       $ethAddress              = Addresses::getAddressByUserID( $user_id, 2 );
       $xemAddress              = Addresses::getAddressByUserID( $user_id, 9 );
       $usdAddress = Addresses::getAddressByUserID($user_id, 10);
       $eurAddress = Addresses::getAddressByUserID($user_id, 11);

       // Generate the address if the wallet exist, but no address is there
       if (empty($usdAddress)) $usdAddress = Addresses::generateAddressByUserID($user_id, 10);
       if (empty($eurAddress)) $eurAddress = Addresses::generateAddressByUserID($user_id, 11);

       $wallets  = Wallet::leftJoin('addresses', function ( $join ) {
           $join->on('wallet.coin_id', 'addresses.address_coin');
           $join->on('wallet.user_id', 'addresses.address_user_id');
       })
           ->join('coins', 'coins.coin_id', '=', 'wallet.coin_id')
           ->where('wallet.wallet_enabled', 1)
           ->where('wallet.user_id', $user_id)
           ->orderBy('coins.coin_id')
           ->get([
               'wallet.id',
               'wallet.user_id AS user_id',
               'wallet.id AS wallet_id',
               'wallet.coin_id AS coin_id',
               'coins.wallet_enabled AS is_enabled',
               'amount',
               'amount_inorder',
               'address_address',
               'coin_coin',
               'coin_max_withdraw',
               'coin_erc',
               'coin_fiat',
               'coin_market',
               'coin_address'
           ]);

       //Terminating here if no rows exists based on our conditions
       if( !$wallets || !count($wallets) ) return response()->json( $enabled_coins );

       //Creating the coin details array
       foreach( $wallets as $wallet ) {

           if($wallet->coin_fiat == 0) {
               $available_balance   = bcsub($wallet->amount, $wallet->amount_inorder, 9);
           } else {

               $available_balance   = bcsub($wallet->amount, $wallet->amount_inorder, 2);
           }

           $wallet_address  = [
               'coin_id'            => $wallet->coin_id,
               'symbol'             => $wallet->coin_coin,
               'amount'             => $wallet->amount,
               'amount_inorder'     => $wallet->amount_inorder,
               'available'          => $available_balance,
               'payment_amount'     => null,
               'payment_address'    => null,
               'show_withdraw'      => false,
               'show_deposit'       => false,
               'show_data'          => 1,
               'total_fee'          => 0,
               'credit_amount'      => 0,
               'class'              => '',
               'daily_limit'        => $wallet->coin_max_withdraw,
               'btn_disabled'       => false,
               'error'              => '',
               'maintenance_mode'   => false
           ];

           switch ( $wallet->coin_market ) {

               case 'BTC': $wallet_address['address']   = !$btcAddress ? null : $btcAddress->address_address; break;
               case 'ETH': $wallet_address['address']   = !$ethAddress ? null : $ethAddress->address_address; break;
               case 'XEM': $wallet_address['address']   = !$xemAddress ? null : $xemAddress->address_address; break;
               case 'USD':
                   $wallet_address['address'] = !$usdAddress ? null : $usdAddress->address_address;
                   break;
               case 'EUR':
                   $wallet_address['address'] = !$eurAddress ? null : $eurAddress->address_address;
                   break;
               default:    $wallet_address['address']   = $wallet->address_address; break;
           }

           if($wallet->coin_id == 13) {
               $wallet_address['show_details']  = false;
           }

           if($wallet->is_enabled == 0) {
               $wallet_address['maintenance_mode']  = true;
           } else {

               if( $wallet->coin_fiat == 0 ) {
                   $contract_address    = $wallet->coin_market == 'ETH' && $wallet->coin_coin != 'ETH' ? $wallet->coin_address : null;

                   static::runSyncWalletBalance(
                       $wallet->id,
                       $wallet_address['address'],
                       $contract_address,
                       $wallet->coin_coin
                   );

               }
           }

           $enabled_coins[$wallet->coin_market][$wallet->coin_id]   = $wallet_address;
       }

       return $enabled_coins;
   }

    /**
     * Returns walletID by userID and coinID
     *
     * @param $user_id
     * @param $coin_id
     * @return mixed
     */
    public static function walletIDbyUserAndCoinID( $user_id, $coin_id ) {

        return Wallet::select('id')
            ->where('user_id', $user_id)
            ->where('coin_id', $coin_id)
            ->first();
    }

    /**
     * Fetch Wallet balance based on coin and user
     *
     * @param $coin_id
     * @param $user_id
     * @return array
     */
    public static function getBalance( $coin_id, $user_id )
    {

        try {
            $available  = Wallet::select(['amount', 'amount_inorder'])
                ->where('coin_id', $coin_id)
                ->where('user_id', $user_id)
                ->first();

            if($available)
            {
                return [
                    'amount'    => $available->amount,
                    'inorder'   => $available->amount_inorder
                ];
            }

            return [
                'amount'    => 0,
                'inorder'   => 0
            ];
        } catch (Exception $e)  {

            return $e->getMessage();
        }
    }

    /**
     * Update the wallet balance when order is deleting.
     *
     * @param $coin
     * @param $amount_in_order
     */
    public static function updateWalletForDeletedOrder( $coin, $amount_in_order) {

        Wallet::where('coin_id' , $coin)
            ->where('user_id', Auth::id())
            ->update(['amount_inorder' => DB::raw("amount_inorder - {$amount_in_order}")]);

    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id');
    }

    public function address()
    {
        return $this->belongsTo(Addresses::class, 'address_id');
    }

    /**
     * Returns all Currencies with wallet balance if wallet balance is not zero
     *
     * @return mixed
     */
    public static function currencyCoins() {

        $currencies  = Coin::select(
                'coins.coin_id AS coin_id',
                'coins.coin_coin AS coin_coin',
                DB::raw('(amount - amount_inorder) AS current_balance')
            )
            ->join('wallet', 'coins.coin_id', '=', 'wallet.coin_id')
            ->where('wallet.user_id', Auth::id())
            ->where('coins.coin_enabled', 1)
            ->where(DB::raw('(amount - amount_inorder)'), '>', 0)
            ->get();

        return $currencies;
    }


}
