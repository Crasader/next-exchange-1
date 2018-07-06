<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Services\Blockchainservice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

use function Sodium\add;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Addresses extends Model
{
    //use SoftDeletes;

    protected $table = 'addresses';

    protected $primaryKey   = 'address_id';

    protected $fillable = ['address_user_id', 'address_address', 'address_coin', 'address_name', 'address_type', 'pk', 'salt', 'IV'];

    protected $hidden = ['salt', 'pk'];

    public static function getAddressByUserID($user_id, $coin_id)
    {
        // Check if coin_id is a family coin
        $Family = Coin::getFamilyCoins($coin_id);

        \Debugbar::info($Family[0]);

        if ($Family[0]) $coin_id = $Family[0];

        $result = Addresses::select('address_address', 'pk')
            ->where('address_coin', $coin_id)
            ->where('address_user_id', $user_id)
            ->first();

        return $result != null ? $result : false;
    }

    /**
     * Returns the block chain address by coin id for current user, if address exits will return that address else
     * Generate new address from Block chain, save to address table and return that address
     *
     * @param $user_id
     * @param $coin_id
     * @return String
     */
    public static function generateAddressByUserID( $user_id, $coin_id )
    {
        $coin   = Coin::getNameByID( $coin_id );

        // Get the market where the coin belongs
        $getMarket  = Coin::where('coin_id', $coin_id)
            ->orderBy('coin_id', 'asc')
            ->first(['coin_market']);

        $coinToGenerateAddress   = $coin_id;

        // Get the coins which are grouped as a family coins (ERC20 for ETH, etc...)
        $Family = Coin::getFamilyCoins($coin_id);

        //If BTC, ETH or XEM - setting that id for generating address
        if(in_array($coin_id, $Family) && in_array($Family[0], [1, 2, 9])) {
            $coinToGenerateAddress  = $Family[0];
        }

        //Searching existing address for the coin_id, if exists return that address
        $address    = Addresses::where( 'address_user_id', $user_id )
            ->where( 'address_coin', $coinToGenerateAddress )
            ->first();

        if ($address) {
            $wallet = $wallet     = Wallet::where('user_id', $user_id)
                ->where('coin_id', $coin_id)
                ->first();

            if (!$wallet) {
                self::createWallet($address->address_id, $user_id, $coin_id, $coin);
            }
            return $address;
        }
        // Releasing from memory
        unset($address);

        if (in_array($coin_id, [10, 11])) {
            /** FIAT COIN */
            // Generate a unique 12 char address for FIAT ACCOUNTS
            $address = [];
            $address['address'] = $coin_id . rand(10, 99) . str_pad($user_id, 8, '0', STR_PAD_LEFT);
            $address['pk'] = \Uuid::generate()->string;
        } else {
            /** DIGITAL BLOCKCHAIN ACCOUNT */
            /** @var  $blockChain */
            // If there is no address a new address from BlockChain is needed and write to Addresses table with coin and user ID
            $blockChain = new Blockchainservice;
            $blockChain->setCoin($coinToGenerateAddress);
            $address = $blockChain->createAddress();
        }

        if (!$address) return null;

        if (!$address['address']) return null;

        //Writing to table and returns the address
        $generated_address = Addresses::create([
            'address_user_id'   => $user_id,
            'address_address'   => $address['address'],
            'address_coin'      => $coinToGenerateAddress,
            'address_name'      => 'Wallet',
            'address_type'      => $coinToGenerateAddress == 2 ? 'ETH' : $coin,
            'pk'                => $address['pk']
        ]);

        if($coinToGenerateAddress != $coin_id) {
            $generated_coin = Coin::getNameByID( $coinToGenerateAddress );
            self::createWallet($generated_address->address_id, $user_id, $coinToGenerateAddress, $generated_coin);
        }

        self::createWallet($generated_address->address_id, $user_id, $coin_id, $coin);
        return $generated_address;
    }

    /**
     * Creating an empty wallet
     *
     * @param $address_id
     * @param $user_id
     * @param $coin_id
     * @param $coin_name
     * @return bool
     */
    private static function createWallet($address_id, $user_id, $coin_id, $coin_name) {

        $wallet     = Wallet::where('coin_id', $coin_id)
            ->where('user_id', $user_id)
            ->first();

        if(! $wallet) {
            $wallet     = new Wallet;
            $wallet->tx_id          = Helper::uniqueID();
            $wallet->address_id     = $address_id;
            $wallet->user_id        = $user_id;
            $wallet->coin_id        = $coin_id;
            $wallet->name           = $coin_name;
            $wallet->amount         = 0.0;
            $wallet->save();
        } else {
            $wallet->address_id     = $address_id;
            $wallet->save();
        }

        return true;
    }

    /**
     * Search for a payment id in address table
     *
     * @param $address_payment_id
     * @return bool
     */
    public static function searchPaymentID($address_payment_id)
    {

        $exists = Addresses::where('address_payment_id', $address_payment_id)->first();

        if ($exists) {
            return true;
        }

        return false;
    }

}
