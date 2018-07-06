<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateMarketDataProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure  = 'CREATE PROCEDURE `market_data`(IN symbol VARCHAR (10))
READS SQL DATA

BEGIN

  DECLARE m_id TEXT;
  DECLARE c_coin_id INT;
  DECLARE c_coin_coin TEXT;
  DECLARE c_coin_title TEXT;
  DECLARE c_wallet_enabled INT;
  DECLARE c_price_btc DOUBLE;
  DECLARE c_price_usd DOUBLE;
  DECLARE m_percent_change_1h DOUBLE;
  DECLARE m_last_datetime DATETIME;

  DECLARE market_price DOUBLE;
  DECLARE max_trans_price DOUBLE;
  DECLARE max_market_price DOUBLE;
  DECLARE min_trans_price DOUBLE;
  DECLARE min_market_price DOUBLE;

  DECLARE high_price DOUBLE;
  DECLARE low_price DOUBLE;

  DECLARE last_price DOUBLE;
  DECLARE first_price DOUBLE;

  DECLARE weight_avg DOUBLE;
  DECLARE set_price DOUBLE;
  DECLARE up_price DOUBLE;
  DECLARE down_price DOUBLE;
  DECLARE change_price DOUBLE;

  DECLARE temp_result TEXT;
  DECLARE return_result TEXT;

  DECLARE done INT DEFAULT FALSE;

  DECLARE coin_market_cursor CURSOR FOR
        SELECT * FROM
          `coin_market_merged`
        WHERE
          `id` IN (
              SELECT MAX(`id`)
              FROM
              `coin_market_merged`
              GROUP BY `coin_coin`
          );

     DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

  OPEN coin_market_cursor;

  SET temp_result = "";
  SET return_result = "";

     CM_loop: LOOP

       FETCH `coin_market_cursor` INTO m_id, c_coin_id, c_coin_coin, c_coin_title, c_wallet_enabled, c_price_btc, c_price_usd, m_percent_change_1h, m_last_datetime;

        IF done THEN
        	LEAVE CM_loop;
        END IF;

      IF symbol = "USD" THEN

        SET market_price = c_price_usd;
      ELSE

        SET market_price = c_price_btc;
      END IF;

      SET max_trans_price := (SELECT MAX(transaction_amount)
        FROM  `transactions`
        WHERE  `transaction_maincoin` = c_coin_coin
        AND updated_at  >= CURDATE( ) - INTERVAL 1 DAY
        GROUP BY transaction_maincoin);

     SET high_price = IF (max_trans_price IS NULL, 0, max_trans_price);

       IF max_trans_price IS NULL THEN

          IF symbol = "USD" THEN

            SET max_market_price := (SELECT MAX(price_usd) FROM `market_caps`
				    WHERE `symbol` = c_coin_coin
            AND `updated_at`  >= CURDATE( )- INTERVAL 1 DAY
            GROUP BY c_coin_coin);
          ELSE

            SET max_market_price := (SELECT MAX(price_btc) FROM `market_caps`
				    WHERE `symbol` = c_coin_coin
            AND `updated_at`  >= CURDATE( )- INTERVAL 1 DAY
            GROUP BY c_coin_coin);
          END IF;

         SET high_price = IF (max_market_price IS NULL, 0, max_market_price);

       END IF;

       SET min_trans_price := (SELECT CAST(Min(transaction_amount) AS DECIMAL(16,12))
                         FROM transactions
                         WHERE  `transaction_maincoin` = c_coin_id AND updated_at >= CURDATE( )- INTERVAL 1 DAY
                         group by `transaction_maincoin`);

       SET low_price = IF (min_trans_price IS NULL, 0, min_trans_price);

       IF min_trans_price IS NULL THEN

        IF symbol = "USD" THEN

          SET min_market_price  := (SELECT MIN(price_usd)
                                    FROM `market_caps`
                                    WHERE  `symbol` = c_coin_coin
                                           AND updated_at  >= CURDATE( )- INTERVAL 1 DAY
                                    GROUP BY c_coin_coin);
        ELSE

           SET min_market_price  := (SELECT MIN(price_btc)
                                    FROM `market_caps`
                                    WHERE  `symbol` = c_coin_coin
                                           AND updated_at  >= CURDATE( )- INTERVAL 1 DAY
                                    GROUP BY c_coin_coin);
        END IF;
         SET low_price = IF (min_market_price IS NULL, 0, min_market_price);

       END IF;

       SET last_price := (SELECT transaction_amount
                          FROM  `transactions`
                          WHERE  `transaction_maincoin` =  c_coin_coin
                                 AND updated_at  >= CURDATE( ) - INTERVAL 1
                          DAY
                          ORDER BY transaction_id DESC
                          LIMIT 0 , 1);

       SET last_price = IF (last_price IS NULL, 0, last_price);

       SET first_price := (SELECT transaction_amount
                           FROM  `transactions`
                           WHERE  `transaction_maincoin` =  c_coin_coin
                                  AND updated_at  >= CURDATE( ) - INTERVAL 1
                           DAY
                           ORDER BY transaction_id ASC
                           LIMIT 0 , 1);

       SET first_price = IF (first_price IS NULL, 0, first_price);

       SET weight_avg = (SELECT SUM( transaction_maincoin_amount * transaction_amount ) / SUM( transaction_maincoin_amount )
                         FROM  `transactions`
                         WHERE  `transaction_maincoin` =  c_coin_coin
                                AND updated_at  > CURDATE( ) - INTERVAL 1 DAY);

       IF weight_avg IS NULL THEN

          SET set_price = (market_price / 100);

         IF c_coin_coin = "BTC" AND symbol <> "USD" THEN

             SET up_price   = c_price_btc;
             SET down_price = c_price_btc;

         ELSE

            IF symbol = "USD" THEN

               SET up_price     = ROUND( (c_price_usd + set_price), 9);
               SET down_price   = ROUND( (c_price_usd - set_price), 9);
            ELSE
               SET up_price     = ROUND( (c_price_btc + set_price), 9);
               SET down_price   = ROUND( (c_price_btc - set_price), 9);
            END IF;

         END IF;

       ELSE

        SET set_price   = weight_avg / 1000;
        SET up_price    = ROUND( (weight_avg + set_price), 9);
        SET down_price  = ROUND( (weight_avg - set_price), 9);

       END IF;

      IF (last_price = 0) OR (first_price = 0) THEN

        SET change_price  = m_percent_change_1h;

      ELSE

        SET change_price  = (last_price + first_price) / last_price;

      END IF;


      SET temp_result = concat(temp_result, "\"coin_id\":", "\"", c_coin_id, "\"");
      SET temp_result = concat(temp_result,  ", \"symbol\":","\"", c_coin_coin, "\"");
      SET temp_result = concat(temp_result,  ", \"coin_title\":","\"", c_coin_title, "\"");
      SET temp_result = concat(temp_result,  ", \"change\":", "\"", ROUND( change_price, 2 ), "\"");
      SET temp_result = concat(temp_result,  ", \"price\":", "\"", market_price, "\"");
      SET temp_result = concat(temp_result,  ", \"wallet_enabled\":", "\"", c_wallet_enabled, "\"");
      SET temp_result = concat(temp_result,  ", \"sell\":", "\"", down_price, "\"");
      SET temp_result = concat(temp_result,  ", \"buy\":", "\"", up_price, "\"");
      SET temp_result = concat(temp_result,  ", \"high\":", "\"", high_price, "\"");
      SET temp_result = concat(temp_result,  ", \"low\":", "\"", low_price, "\"");

      IF return_result = "" THEN

        SET return_result = CONCAT("{", temp_result, "}");

      ELSE

        SET return_result = CONCAT(return_result, CONCAT(", {", temp_result, "}"));

      END IF;

      SET temp_result = "";

     END LOOP;

  SET return_result = concat("[", return_result, "]");

  CLOSE coin_market_cursor;

  SELECT return_result;

END';

        DB::unprepared('DROP PROCEDURE IF EXISTS market_data');
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS market_data');
    }
}
