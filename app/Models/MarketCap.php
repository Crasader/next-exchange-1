<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MarketCap extends Model
{
    protected $table = 'market_caps';

	protected $fillable = [
		'cmc_id', 'name', 'symbol', 'rank', 'price_usd', 'price_btc', '24h_volume_usd', 'market_cap_usd', 'available_supply', 'total_supply', 'max_supply', 'percent_change_1h', 'percent_change_24h', 'percent_change_7d', 'last_updated'
	];

    /**
     * Returns the initial data for Orderbook Chart
     *
     * @param $symbol
     * @return mixed
     */
	public function getChartData( $symbol )
    {
        $data  = $this->select(
            "last_updated AS updated_date",
            DB::raw("MIN(price_usd) AS low_price"),
            DB::raw("MAX(price_usd) AS high_price"),
            DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(CAST(price_usd AS CHAR) ORDER BY updated_at), ',', 1 ) AS open_price"),
            DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(CAST(price_usd AS CHAR) ORDER BY updated_at DESC), ',', 1 ) AS close_price"),
            DB::raw("AVG(24h_volume_usd) AS average_volume")
        )
            ->where("symbol", $symbol)
            ->groupBy(
                DB::raw("date(updated_at)")
            )
            ->orderBy("updated_at")
            ->get();

        return $data;
    }

    /**
     * Get the 24 hour volume total of each symbol
     *
     * @param string $coin
     * @param null $startDate
     * @param null $endDate
     * @return int
     */
    public function getValueTotalByCoin($coin = 'ETH', $startDate = null, $endDate = null)
    {

        try{

            $total = $this->where('symbol', $coin);

            if(!is_null($startDate)) {
                $total->whereBetween('last_updated', [strtotime($startDate), strtotime($endDate)] );
            }

            $totalCoinValue = $total->sum('24h_volume_usd');

        } catch (Exception $e) {

            return $e->getMessage();
        }


        return $totalCoinValue;
    }

    /**
     * Calculate the % updown of Coins
     *
     * @param string $coin
     * @param null $startDate
     * @param null $endDate
     * @return string
     */
    public function upDownByCoin($coin = 'ETH', $startDate = null, $endDate = null)
    {
        try{

            $updown = $this->where('symbol', $coin);

            if(!is_null($startDate)) {
                $updown->whereBetween('last_updated', [strtotime($startDate), strtotime($endDate)] );
            }

            $totalPercentage    = $updown->sum('percent_change_24h');

        } catch (Exception $e) {

            return $e->getMessage();
        }

        return $totalPercentage;
    }
}
