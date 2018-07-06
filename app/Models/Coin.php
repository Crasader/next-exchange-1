<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Coin extends Model
{
    public $primaryKey  = 'coin_id';
    protected $guarded = [];
    protected  $table='coins';


    /**
     * Get the list of coin id to created the wallet as default
     *
     * @return array
     */
    public static function defaultWallets( ) {

        $coins  = Coin::where('default_wallet',1)
            ->get(['coin_id']);

        if(! count($coins)) return [];

        $defaultWallet  = [];

        foreach($coins as $coin) {

            $defaultWallet[]    = $coin->coin_id;
        }

        return $defaultWallet;
    }

    public static function getDailyWithdrawLimit( $coin_id ) {

        $coin   = Coin::where('coin_id', $coin_id)
            ->first(['coin_max_withdraw']);

        return $coin->coin_max_withdraw;
    }

    /**
     * Returns the price in BTC and USD for Transaction Coins
     *
     * @return mixed
     */
	public static function getCoinsBySymbol(){
        $coinSymbols    = Config::get('app.transaction_coins');
        $coins = Coin::select('coin_id', 'coin_coin')
                    ->whereIn('coin_coin', $coinSymbols)
                    ->where('coin_enabled', 1)
                    ->get();
        return $coins;
    }

    public static function getNameOrTypeByID( $coin_id ) {

        $coin   = Coin::select(
            'coin_coin',
            'coin_erc'
        )->where('coin_id', $coin_id)
            ->first();

        return $coin['coin_erc'] == 20 ? 'erc20' : strtolower($coin['coin_coin']);
    }

    public static function getContractAddress( $coin_id ) {

        $coin   = Coin::select(
            'coin_address'
        )->where('coin_id', $coin_id)
            ->first();

        return $coin['coin_address'];
    }

    /**
     * Get Coin ID and Symbol based on enable stats and fiat
     *
     * @param int $coinEnabled
     * @return mixed
     */
    public static function getIDAndSymbol($coinEnabled = 1)
    {
        $coins  = Coin::select('coin_id', 'coin_coin AS symbol', 'coin_title', 'wallet_enabled')
                    ->where('coin_enabled', $coinEnabled)
                    ->get();

        return $coins;
    }

    /**
     * Returns all digital currencies and if user have balance in wallet returns wallet balance along with currency
     *
     * @return mixed
     */
    public static function digitalCurrencies()
    {
        $user_id    = Auth::id();

        $digital_currencies = Coin::select(
            'coins.coin_id AS coin_id',
            'coins.coin_coin AS coin_coin',
             DB::raw('IF(wallet.user_id = ' . $user_id . ', (amount - amount_inorder), 0) AS current_balance')
        )
            ->leftJoin('wallet', function($q) use ($user_id) {

                $q->on('coins.coin_id', '=', 'wallet.coin_id')
                    ->where('wallet.user_id', $user_id);
            })
            ->where('coins.coin_enabled', 1)
            ->get();

        return $digital_currencies;
    }

    /**
     * Get the coin Id from table with coin name
     */
    public static function getCoinIDByName( $name ) {

        return Coin::select('coin_id')->where('coin_coin', $name)->first();
    }

    /**
     * Returns and array contains the coin id of Family coins which are grouped in the same market (ETH, BTC, NEM)
     *
     * @return array
     */
    public static function getFamilyCoins( $coin_id ) {

        $getMarket  = Coin::where('coin_id', $coin_id)
            ->first(['coin_market']);

        if(! $getMarket) return [];
        if(trim($getMarket->coin_market) == '') return [];

        $Coins   = Coin::where('coin_market', $getMarket->coin_market)
            ->orderBy('coin_id', 'asc')
            ->get(['coin_id']);

        if(!$Coins) return [];

        $Family   = [];

        //Creating single dimensional array of coins
        foreach ($Coins as $coin) {

            $Family[]    = $coin['coin_id'];
        }

        return $Family;
    }

    /**
     * Determine whether coin coin exists and it's not fiat,
     * creates wallet if there no wallet and return false
     *
     *
     * @param $coin_id
     * @param $user_id
     * @return bool
     */
    public static function isNotFiatAndWalletExists( $coin_id, $user_id ) {

        $coin   = Coin::find($coin_id);

        if(! $coin) return false;

        if($coin->coin_fiat == 0) return true;

        $wallet     = Wallet::where('user_id', $user_id)
            ->where('coin_id', $coin_id)
            ->first();

        if(!$wallet) {

            $wallet     = new Wallet;

            $wallet->tx_id          = Helper::uniqueID();
            $wallet->user_id        = $user_id;
            $wallet->coin_id        = $coin_id;
            $wallet->name           = self::getNameByID( $coin_id );
            $wallet->amount         = 0.0;
            $wallet->save();

            return false;
        }

        return true;
    }

    /**
     * Returns the coin name by coin_id
     *
     * @param $coin_id
     * @return array
     */
    public static function getNameByID($coin_id)
    {

        $coin = Coin::select(
            'coin_coin'
        )->where('coin_id', $coin_id)
            ->first();

        return $coin['coin_coin'];
    }

    public static function getCoinDetailsByName( $name ) {

        return Coin::select('*')->where('coin_coin', $name)->first();
    }

}
