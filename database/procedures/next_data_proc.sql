DROP PROCEDURE IF EXISTS `next_data`;


DELIMITER $$

CREATE PROCEDURE `next_data`(OUT next_values TEXT)
READS SQL DATA

BEGIN

    DECLARE change_price        DOUBLE;
    DECLARE market_price        DOUBLE;
    DECLARE is_wallet_enabled   DOUBLE;
    DECLARE down_price          DOUBLE;
    DECLARE up_price            DOUBLE;
    DECLARE high_price          DOUBLE;
    DECLARE low_price           DOUBLE;

    (SELECT
        MAX(transaction_price),
        MIN(transaction_price)
    INTO
        @max_amount,
        @low_amount
    FROM  `transactions`
    WHERE  `transaction_maincoin` = 12
                                       AND updated_at  >= CURDATE( ) - INTERVAL 1 DAY
    GROUP BY transaction_maincoin);

    SET high_price = IF (@max_amount IS NULL, 0, @max_amount);
    SET low_price  = IF (@low_amount IS NULL, 0, @low_amount);

    SET @weight_avg := (SELECT SUM( transaction_maincoin_amount * transaction_amount ) / SUM( transaction_maincoin_amount )
                      FROM  `transactions`
                      WHERE  `transaction_maincoin` =  12
                             AND updated_at  > CURDATE( ) - INTERVAL 1 DAY
    );
    SET @weight_avg     = IF (@weight_avg IS NULL, 0, @weight_avg);
    SET @set_price      = @weight_avg / 1000;

    SET up_price    = @weight_avg + @set_price;
    SET down_price  = @weight_avg - @set_price;

    SET @last_price := (SELECT transaction_price
                       FROM  `transactions`
                       WHERE  `transaction_maincoin` =  12
                              AND updated_at  >= CURDATE( ) - INTERVAL 1 DAY
                       ORDER BY transaction_id DESC
                       LIMIT 0 , 1);

    SET @last_price = IF (@last_price IS NULL, 0, @last_price);

    SET @first_price := (SELECT transaction_price
                        FROM  `transactions`
                        WHERE  `transaction_maincoin` =  12
                               AND updated_at  >= CURDATE( ) - INTERVAL 1 DAY
                        ORDER BY transaction_id ASC
                        LIMIT 0 , 1);

    SET @first_price = IF (@first_price IS NULL, 0, @first_price);

    SET change_price = 0;
    IF @last_price != 0 THEN
        SET change_price  = (@last_price + @first_price) / @last_price;
    END IF ;

    SET @buy_avg    := (
                        SELECT AVG(transaction_price)
                        FROM `transactions`
                        WHERE `transaction_maincoin` = 12
                            AND `transaction_buysell` = 1
                            AND updated_at  >= CURDATE( ) - INTERVAL 1 DAY
    );

    SET @sell_avg    := (
                        SELECT AVG(transaction_price)
                        FROM `transactions`
                        WHERE `transaction_maincoin` = 12
                              AND `transaction_buysell` = 2
                              AND updated_at  >= CURDATE( ) - INTERVAL 1 DAY
    );

    SET market_price    = (@buy_avg + @sell_avg) / 2;

    SET market_price    = IF (@market_price IS NULL, 0, @market_price);

    SET is_wallet_enabled := (SELECT `wallet_enabled` FROM `coins` WHERE `coin_id` = 12);


    SET next_values = "{";
    SET next_values = concat(next_values, "\"coin_id\":", "\"", 12, "\"");
    SET next_values = concat(next_values,  ", \"symbol\":","\"NEXT\"");
    SET next_values = concat(next_values,  ", \"coin_title\":","\"Next\"");
    SET next_values = concat(next_values,  ", \"change\":", "\"", ROUND( change_price, 9 ), "\"");
    SET next_values = concat(next_values,  ", \"price\":", "\"", ROUND( market_price, 9 ), "\"");
    SET next_values = concat(next_values,  ", \"wallet_enabled\":", "\"", is_wallet_enabled, "\"");
    SET next_values = concat(next_values,  ", \"sell\":", "\"", ROUND( down_price, 9 ), "\"");
    SET next_values = concat(next_values,  ", \"buy\":", "\"", ROUND( up_price, 9 ), "\"");
    SET next_values = concat(next_values,  ", \"high\":", "\"", ROUND( high_price, 9 ), "\"");
    SET next_values = concat(next_values,  ", \"low\":", "\"", ROUND( low_price, 9 ), "\"");
    SET next_values = concat(next_values, "}");

END $$

DELIMITER ;