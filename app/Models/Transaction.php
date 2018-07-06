<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{

    protected $table    = 'transactions';

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'transaction_user_id', 'transaction_txid', 'transaction_rxid', 'transaction_addr', 'transaction_amount', 'transaction_market', 'transaction_fee', 'transaction_cost', 'transaction_ip', 'transaction_price', 'transaction_buysell', 'transaction_maincoin_amount', 'transaction_maincoin', 'transaction_maincoin_wallet_id', 'transaction_maincoin_wallet_balance', 'transaction_confirmations', 'transaction_status'
    ];

    /**
     * Getting % difference of coins which are not avilable in market_cap
     *
     * @param string $coin
     * @param null $startDate
     * @param null $endDate
     * @return float|string
     */
    public static function upDownByCoin($coin = '2', $startDate = null, $endDate = null)
    {
        try{

            $updownF = Transaction::where('transaction_maincoin', $coin);

            if(!is_null($startDate)) {
                $updownF->whereBetween('updated_at', [$startDate, $endDate] );
            }
            $updownF->orderBy('transaction_id', 'ASC');
            $updownF->select('transaction_amount');

            $firstPrice     = $updownF->first();

            $updownL = Transaction::where('transaction_maincoin', $coin);

            if(!is_null($startDate)) {
                $updownL->whereBetween('updated_at', [$startDate, $endDate] );
            }
            $updownL->orderBy('transaction_id', 'DESC');
            $updownL->select('transaction_amount');

            $lastPrice     = $updownL->first();

        } catch (Exception $e) {

            return $e->getMessage();
        }

        $firstPrice         =  $firstPrice ? $firstPrice->transaction_amount : 0;
        $lastPrice          = $lastPrice ? $lastPrice->transaction_amount : 0;

        $totalPercentage    = (($firstPrice - $lastPrice) / ($firstPrice == 0 ? 1 : $firstPrice) ) * 100;

        return round($totalPercentage, 2);
    }

    /**
     * Fetches user transactions by type
     *
     * @param $user_id
     * @param null $type
     * @return \Illuminate\Support\Collection
     */
    public static function getTransactionsByUser($user_id, $type = null) {

        $transactions   = Transaction::select(

            'transaction_amount AS order_amount',
            'market_name AS order_market',
            'maincoin_name AS order_maincoin',
            'transaction_price AS order_price',
            'transaction_maincoin_amount AS order_total',
            'transaction_buysell AS order_buysell',
            'updated_at AS update_at'
        )
            ->where('transaction_user_id', $user_id)
            ->orderBy('created_at', 'desc');

        if ($type) {
            $transactions = $transactions->where('transaction_buysell', $type);
        }

        return $transactions->get();
    }

    public static function getTransactionsByCoin($coin_id)
    {

        $transactions = Transaction::select(
            'transaction_amount AS order_amount',
            'market_name AS order_market',
            'maincoin_name AS order_maincoin',
            'transaction_price AS order_price',
            'transaction_maincoin_amount AS order_total',
            'transaction_buysell AS order_buysell',
            'updated_at AS update_at'
        )
            ->where('transaction_maincoin', $coin_id)
            ->where('transaction_buysell', '<>', 0)
            ->orderBy('market_name')
            ->orderBy('maincoin_name')
            ->get();

        return ($transactions && count($transactions)) ? $transactions->toArray() : [];
    }

    /**
     * Getting weight average for market data
     *
     * @return mixed
     */
    public static function getWeightAverage()
    {
        $weight_avg     = [];
        $result = Transaction::select(
            'maincoin_name',
            DB::raw('(SUM(transaction_maincoin_amount * transaction_amount) / SUM(transaction_maincoin_amount)) AS weight_avg')
        )
        ->where(
            'updated_at', '>', DB::raw('CURDATE() - INTERVAL 1 DAY')
        )
        ->groupBy('transaction_maincoin')
        ->get();

        if($result && count($result)) {

            foreach ( $result as $value) {

                $weight_avg[$value->maincoin_name]  = $value->weight_avg;
            }
        }

        return $weight_avg;
    }

    /**
     * Retrieves the sum of daily transaction amount by user, coin ID and type of transaction
     *
     * @param $user_id
     * @param $coin_id
     * @param $type
     * @return float
     */
    public static function getDailyTransactionAmount( $user_id, $coin_id, $type ) {

        $dailyTransaction    = Transaction::where('transaction_user_id', $user_id)
            ->where('transaction_maincoin', $coin_id)
            ->where('transaction_type', $type)
            ->sum('transaction_amount');

        return $dailyTransaction ? $dailyTransaction : 0.00;
    }

    /**
     * Getting Chart for the coins which are not available in market_cap
     *
     * @param $coin_id
     * @return mixed
     */
    public function getChartData($coin_id)
    {

        $data = $this->select(
            DB::raw("UNIX_TIMESTAMP(updated_at) AS updated_date"),
            DB::raw("MIN(transaction_price) AS low_price"),
            DB::raw("MAX(transaction_price) AS high_price"),
            DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(CAST(transaction_price AS CHAR) ORDER BY updated_at), ',', 1 ) AS open_price"),
            DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(CAST(transaction_price AS CHAR) ORDER BY updated_at DESC), ',', 1 ) AS close_price"),
            DB::raw("AVG(transaction_maincoin_amount) AS average_volume")
        )
            ->where("transaction_maincoin", $coin_id)
            ->groupBy(
                DB::raw("date(updated_at)")
            )
            ->orderBy("updated_at")
            ->get();

        return $data;
    }

    /**
     * Getting total amount for coins which are not available in market_cap
     *
     * @param null $coin
     * @param null $startDate
     * @param null $endDate
     * @return string
     */
    public function getValueTotalByCoin($coin = null, $startDate = null, $endDate = null)
    {

        try {

            $total = $this->where('transaction_maincoin', $coin);

            if (!is_null($startDate)) {
                $total->whereBetween('updated_at', [$startDate, $endDate]);
            }

            $totalCoinValue = $total->sum('transaction_amount');

        } catch (Exception $e) {

            return $e->getMessage();
        }

        return $totalCoinValue;
    }
}