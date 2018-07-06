<?php

use App\Models\Coin;
use Illuminate\Database\Seeder;

class DailyWithDrawLimit extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coin_max_limit = [
            1 => 0.05, //BTC
            2 => 0.5, //ETH
            6 => 2, //LTC
            12 => 500, //NEXT
            13 => 250, //ETN
            130 => 1000 //ECA
        ];

        foreach ($coin_max_limit as $coin_id => $limit) {
            $coin = Coin::find($coin_id);
            $coin->coin_max_withdraw = $limit;
            $coin->save();
        }
    }
}
