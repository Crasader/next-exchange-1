<?php

namespace App\Helpers;

use App\Models\Coin;
use App\Models\MarketCap;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\HandleErrorNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class Helper
{

    public static function encryptId($int, $TableSalt = '')
    {
        global $GlobalSalt;
        $HashedChecksum = substr(sha1($TableSalt . $int . $GlobalSalt), 0, 6);
        $hex = dechex($int);
        return self::base64_url_encode($HashedChecksum . $hex);
    }

    public static function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_ ');
    }

    public static function decryptId($string, $TableSalt = '')
    {
        global $GlobalSalt;
        $parts = self::base64_url_decode($string);
        $hex = substr($parts, 6);
        $int = hexdec($hex);
        $part1 = substr($parts, 0, 6);

        return substr(sha1($TableSalt . $int . $GlobalSalt), 0, 6) === $part1 ? $int : false;
    }

    public static function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_ ', '+/='));
    }

    /**
     * Writes the error to laravel log file
     *
     * @param $data
     * @param null $message
     */
    public static function LogError($data, $message = null)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        Log::error("\n" . str_repeat("-", 80) . "\n\n" . $data . "\n\n" . str_repeat("=", 80) . "\n\n\n");

        //Sending mail to bug channel in slack
        Notification::route('mail', 'p1s2o7e0z6z8y4r2@nextexchange.slack.com')
            ->notify(new HandleErrorNotification(
                $message,
                $data
            ));
    }

    /**
     * Returns an Unique id with date
     *
     * @return string
     */
    public static function uniqueID()
    {
        return uniqid(date('YmdHis'));
    }

    public static function convertToFloat($s)
    {
        // convert "," to "."
        $s = str_replace(',', '.', $s);

        // remove everything except numbers and dot "."
        $s = preg_replace("/[^0-9\.]/", "", $s);

        // remove all seperators from first part and keep the end
        $s = str_replace('.', '', substr($s, 0, -3)) . substr($s, -3);

        // return float
        return (float)$s;
    }

    public static function formatCurrency($n, $n_decimals)
    {
        return ((floor($n) == round($n, $n_decimals)) ? number_format($n) : number_format($n, $n_decimals));
    }

    public static function getCryptoPrice($iDate, $iToken)
    {
        if (empty($iDate)) {
            $iDate = Carbon::now()->startOfDay();
        }

        $data = MarketCap::where('created_at', '>', $iDate)
            ->where('created_at', '<=', $iDate->copy()->endOfDay())
            ->where('symbol', '=', $iToken)
            ->avg('price_usd');

        return $data;

    }

    public static function getWalletBalanceRef($iUser)
    {
        // Pick up the refs ID's
        $refs = User::where('referred_by', '=', $iUser)->get();
        $wallet_balance = '0';

        foreach ($refs as $ref) {

            $wallet = Wallet::getBalance(12, $ref->id);
            $balance = bcsub($wallet['amount'], $wallet['inorder'], 9);
            $wallet_balance = bcadd($wallet_balance, $balance, 9);
        }

        return bcdiv(bcmul($wallet_balance, 5, 9), 100, 9); // 5% bonus tokens for the user!
    }

    public static function getCurrentPrice($symbol, $pair)
    {
        static $data;
        if (!$data) {
            $json_data = @file_get_contents(public_path() . '/data.json');
            $data = json_decode($json_data, true);
        }

        $coin_price = [];
        foreach ($data['cmc'] as $value) {
            $coin_price[$value['symbol']]['btc'] = $value['price_btc'];
            $coin_price[$value['symbol']]['usd'] = $value['price_usd'];
        }
        if (empty($coin_price[$symbol]['btc'])) $coin_price[$symbol]['btc'] = '0.000000000';
        if (empty($coin_price[$symbol]['usd'])) $coin_price[$symbol]['usd'] = '0.00';
        if ($coin_price[$symbol]['usd']) $coin_price[$symbol]['usd'] = round($coin_price[$symbol]['usd'], 2);
        return $coin_price[$symbol][$pair];
    }

    public static function get24hVolume($symbol, $type)
    {
        static $data;
        if (!$data) {
            $json_data = @file_get_contents(public_path() . '/data.json');
            $data = json_decode($json_data, true);
        }

        $coin_price = [];
        foreach ($data['cmc'] as $value) {
            $coin_price[$value['symbol']]['percentage'] = $value['percent_change_24h'];
            $coin_price[$value['symbol']]['volume'] = $value['24h_volume_usd'];
        }
        if (empty($coin_price[$symbol]['percentage'])) $coin_price[$symbol]['percentage'] = '0.00';
        if (empty($coin_price[$symbol]['volume'])) $coin_price[$symbol]['volume'] = '0.00';
        if ($coin_price[$symbol]['volume']) $coin_price[$symbol]['volume'] = number_format($coin_price[$symbol]['volume'], 2, ",", ".");
        return $coin_price[$symbol][$type];
    }

    /**
     * Returns CoinID by coin name
     *
     * @param null $coin_name
     * @return int
     */
    public static function getCoin($coin_name = null)
    {
        $coin = Coin::getCoinIDByName($coin_name);
        return !$coin ? 0 : $coin['coin_id'];
    }

    /**
     * Returns walletID by user and coin ID
     *
     * @param int $user_id
     * @param int $coin_id
     * @return int
     */
    public static function getWalletID($user_id = 0, $coin_id = 0)
    {
        $wallet = Wallet::walletIDbyUserAndCoinID($user_id, $coin_id);
        return !$wallet ? 0 : $wallet['id'];
    }
}