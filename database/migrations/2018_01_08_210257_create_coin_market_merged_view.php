<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateCoinMarketMergedView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $view_create    = 'CREATE OR REPLACE VIEW `coin_market_merged`  AS  select if(isnull(`m`.`id`),0,`m`.`id`) AS `id`,`c`.`coin_id` AS `coin_id`,`c`.`coin_coin` AS `coin_coin`,`c`.`coin_title` AS `coin_title`, `c`.`wallet_enabled` AS `wallet_enabled`, if(isnull(`m`.`price_btc`),0,`m`.`price_btc`) AS `price_btc`, if(isnull(`m`.`price_usd`),0,`m`.`price_usd`) AS `price_usd`,if(isnull(`m`.`percent_change_1h`),0,`m`.`percent_change_1h`) AS `percent_change_1h`,`m`.`updated_at` AS `last_datetime` from (`coins` `c` left join `market_caps` `m` on((`c`.`coin_coin` = `m`.`symbol`))) where ((`c`.`coin_enabled` = 1) and (`c`.`coin_fiat` = 0))';

        DB::unprepared($view_create);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS coin_market_merged');
    }
}
