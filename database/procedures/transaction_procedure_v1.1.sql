DELIMITER $$

DROP PROCEDURE IF EXISTS order_execute
$$


CREATE PROCEDURE `order_execute`(IN param_order_id INTEGER, OUT return_data TEXT)
    READS SQL DATA
BEGIN

    DECLARE ord_user_id         INT(10);
    DECLARE ord_amount          DECIMAL(20, 9);
    DECLARE amount_executed     DECIMAL(20, 9);
    DECLARE market              VARCHAR(25);
    DECLARE market_id           INT(10);
    DECLARE ip                  VARCHAR(25);
    DECLARE price               DECIMAL(20, 9);
    DECLARE buy_sell            TINYINT;
    DECLARE main_coin           VARCHAR(10);
    DECLARE main_coin_id        INT;
    DECLARE executed_date       DATETIME;

    DECLARE to_execute          DECIMAL(20, 9);

    DECLARE order_table CURSOR FOR
        SELECT
            order_user_id,
            order_amount,
            executed_amount,
            order_market,
            order_ip,
            order_price,
            order_buysell,
            order_maincoin,
            order_maincoin_id
        FROM
            orders
        WHERE
            order_id = param_order_id;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
    DECLARE EXIT HANDLER FOR SQLWARNING ROLLBACK;

    START TRANSACTION;

        SET return_data = NULL;
        SET executed_date = now();

        SELECT * FROM orders WHERE order_id = param_order_id FOR UPDATE;

        OPEN order_table;

        FETCH order_table INTO
            ord_user_id,
            ord_amount,
            amount_executed,
            market,
            ip,
            price,
            buy_sell,
            main_coin,
            main_coin_id;

	SET to_execute          = ord_amount - amount_executed;

        CLOSE order_table;

        SearchBlock: BEGIN

            DECLARE match_id                INT;
            DECLARE match_user_id           INT;
            DECLARE match_amount            DECIMAL(20, 9);
            DECLARE match_to_execute        DECIMAL(20, 9);
            DECLARE execute_now             DECIMAL(20, 9);

            DECLARE transaction_id          INT;
            DECLARE done INT DEFAULT FALSE;
            DECLARE prev_orders CURSOR FOR
                SELECT
                    order_id,
                    order_user_id,
                    order_amount,
                    (order_amount - executed_amount)
                FROM
                    orders
                WHERE
                    (order_amount - executed_amount) > 0
                AND
                    order_market = market
                AND
                    (order_buysell = 1 AND order_price >= price OR order_buysell = 2 AND order_price <= price)
                AND
                    order_maincoin_id = main_coin_id
                AND
                    order_buysell <> buy_sell
                AND
                    order_executed = 0
                ORDER BY
                    order_id;

            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

            OPEN prev_orders;

            find_match_loop: LOOP

                FETCH prev_orders INTO
                    match_id,
                    match_user_id,
                    match_amount,
                    match_to_execute;

                IF done THEN
                    LEAVE find_match_loop;
                END IF;

                SELECT * FROM orders WHERE order_id = match_id FOR UPDATE;

                SELECT CONCAT("Matched ", match_id) as ShowStatus;

                SET @balance_check   = match_to_execute - to_execute;

                SET execute_now         = to_execute;

		IF @balance_check = 0 THEN

                    SET @executed_current   = 1;
                    SET @executed_match     = 1;
                ELSE

                    IF @balance_check > 0 THEN

                        SET @executed_current   = 1;
                        SET @executed_match     = 0;
                    ELSE

                        SET execute_now         = match_to_execute;
                        SET @executed_current   = 0;
                        SET @executed_match     = 1;
                    END IF;
                    SET @is_executed    = 0;
                END IF;

		SET to_execute  = to_execute - execute_now;

		UPDATE orders SET
                    executed_amount = (executed_amount + execute_now),
                    order_executed  = @executed_current,
                    updated_at = executed_date
                WHERE
                    order_id = param_order_id;

		UPDATE orders SET
                    executed_amount = (executed_amount + execute_now),
                    order_executed  = @executed_match,
                    updated_at = executed_date
                WHERE
                    order_id = match_id;

                SET @executed_total     = round(execute_now * price, 9);
                SET market_id          := (SELECT coin_id FROM  coins WHERE coin_coin = market);

                SELECT CONCAT("Orders updated ", match_id) as ShowStatus;

                IF buy_sell = 1 THEN
		    SET @wallet_id  := (SELECT id FROM wallet WHERE
                                            coin_id = main_coin_id
                                        AND
                                            user_id = ord_user_id
                                        );

		    SELECT CONCAT("Buy/Sell ", buy_sell, "; Wallet ID = ", @wallet_id) as ShowStatus;

                    UPDATE wallet SET
                        amount = amount - @executed_total,
                        amount_inorder = amount_inorder - @executed_total,
                        updated_at = executed_date
                    WHERE
                        id = @wallet_id;

		    SET @wallet_balance = (SELECT amount FROM wallet WHERE id = @wallet_id);

                    INSERT INTO transactions (
                        transaction_user_id,
                        transaction_txid,
                        transaction_rxid,
                        transaction_addr,
                        transaction_amount,
                        transaction_market,
                        market_name,
                        transaction_ip,
                        transaction_price,
                        transaction_buysell,
                        transaction_maincoin_amount,
                        transaction_maincoin,
                        maincoin_name,
                        transaction_maincoin_wallet_id,
                        transaction_maincoin_wallet_balance,
                        transaction_status,
                        order_id,
                        order_matched,
                        created_at,
                        updated_at
                    ) VALUES (
                        ord_user_id,
                        md5(concat(param_order_id,'&',match_id)),
                        md5(concat(param_order_id,'&',match_id)),
                        md5(ip),
                        execute_now,
                        market_id,
                        market,
                        ip,
                        price,
                        1,
                        @executed_total,
                        main_coin_id,
                        main_coin,
                        @wallet_id,
                        @wallet_balance,
                        1,
                        param_order_id,
                        match_id,
                        executed_date,
                        executed_date
                    );
                    SET transaction_id = LAST_INSERT_ID ();

                    IF return_data IS NOT NULL THEN
                        SET return_data = concat(return_data, ', {');
                    ELSE
                        SET return_data = '{';
                    END IF;

                    SET return_data = concat(return_data, '"transaction_id": "',transaction_id, '"');
                    SET return_data = concat(return_data, ', "user_id": "', ord_user_id, '"');
                    SET return_data = concat(return_data, ', "to_user_id": "', match_user_id, '"');
                    SET return_data = concat(return_data, ', "order_id": "', param_order_id, '"');
                    SET return_data = concat(return_data, ', "order_amount": "', execute_now, '"');
                    SET return_data = concat(return_data, ', "order_price": "', price, '"');
                    SET return_data = concat(return_data, ', "order_market": "', market, '"');
                    SET return_data = concat(return_data, ', "order_maincoin": "', main_coin, '"');
                    SET return_data = concat(return_data, ', "order_buysell": "', 1, '"');
                    SET return_data = concat(return_data, ', "coin_id": "', main_coin_id, '"');
                    SET return_data = concat(return_data, ', "update_at": "', unix_timestamp(executed_date), '"');
                    SET return_data = concat(return_data, '}');

		    SELECT CONCAT("Return data is ", return_data) as ShowStatus;

		    SET @wallet_id  := (SELECT id FROM wallet WHERE
                                            `name` = market
                                        AND
                                            user_id = ord_user_id
                                        );

		    IF @wallet_id IS NULL THEN

			INSERT INTO wallet (
                            tx_id,
                            user_id,
                            coin_id,
                            `name`,
                            amount,
                            created_at,
                            updated_at
                        ) VALUES (
                            transaction_id,
                            ord_user_id,
                            market_id,
                            market,
                            execute_now,
                            executed_date,
                            executed_date
                            );
                    ELSE

                    SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                        UPDATE wallet SET
                            amount = amount + execute_now,
                            updated_at = executed_date
                        WHERE
                            id = @wallet_id;

                    END IF;

				    SET @wallet_id  := (SELECT id FROM wallet WHERE
                                            coin_id = main_coin_id
                                        AND
                                            user_id = match_user_id
                                        );
                    SET @new_wallet_id  = @wallet_id;

                    IF @wallet_id IS NULL THEN

                        INSERT INTO wallet (
                            tx_id,
                            user_id,
                            coin_id,
                            `name`,
                            amount,
                            created_at,
                            updated_at
                        ) VALUES (
                            '',
                            match_user_id,
                            main_coin_id,
                            main_coin,
                            @executed_total,
                            executed_date,
                            executed_date
                        );
                        SET @new_wallet_id = LAST_INSERT_ID ();
                    ELSE

                     SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                        UPDATE wallet SET
                            amount = amount + @executed_total,
                            updated_at = executed_date
                        WHERE
                            id = @wallet_id;
                    END IF;

		    SET @wallet_balance = (SELECT amount FROM wallet WHERE id = @new_wallet_id);
                    INSERT INTO transactions (
                        transaction_user_id,
                        transaction_txid,
                        transaction_rxid,
                        transaction_addr,
                        transaction_amount,
                        transaction_market,
                        market_name,
                        transaction_ip,
                        transaction_price,
                        transaction_buysell,
                        transaction_maincoin_amount,
                        transaction_maincoin,
                        maincoin_name,
                        transaction_maincoin_wallet_id,
                        transaction_maincoin_wallet_balance,
                        transaction_status,
                        order_id,
                        order_matched,
                        created_at,
                        updated_at
                    ) VALUES (
                        match_user_id,
                        md5(concat(match_id, '&', param_order_id)),
                        md5(concat(match_id, '&', param_order_id)),
                        md5(ip),
                        execute_now,
                        market_id,
                        market,
                        ip,
                        price,
                        2,
                        @executed_total,
                        main_coin_id,
                        main_coin,
                        @new_wallet_id,
                        @wallet_balance,
                        1,
                        match_id,
                        param_order_id,
                        executed_date,
                        executed_date
                    );
                    SET transaction_id = LAST_INSERT_ID ();

                    SET return_data = concat(return_data, ', {');
                    SET return_data = concat(return_data, '"transaction_id": "', transaction_id, '"');
                    SET return_data = concat(return_data, ', "user_id": "', match_user_id, '"');
                    SET return_data = concat(return_data, ', "to_user_id": "', ord_user_id, '"');
                    SET return_data = concat(return_data, ', "order_id": "', match_id, '"');
                    SET return_data = concat(return_data, ', "order_amount": "', execute_now, '"');
                    SET return_data = concat(return_data, ', "order_price": "', price, '"');
                    SET return_data = concat(return_data, ', "order_market": "', market, '"');
                    SET return_data = concat(return_data, ', "order_maincoin": "', main_coin, '"');
                    SET return_data = concat(return_data, ', "order_buysell": "', 2, '"');
                    SET return_data = concat(return_data, ', "coin_id": "', market_id, '"');
                    SET return_data = concat(return_data, ', "update_at": "', unix_timestamp(executed_date), '"');
                    SET return_data = concat(return_data, '}');

		    SELECT CONCAT("Return data is ", return_data) as ShowStatus;


                    if @new_wallet_id IS NOT NULL THEN

			UPDATE wallet SET
                            tx_id = transaction_id,
                            updated_at = executed_date
                        WHERE
                            id = @new_wallet_id;
                    END IF;

                                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                                            `name` = market
                                        AND
                                            user_id = match_user_id
                                        );

                             SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                    UPDATE wallet SET
                        amount = amount - execute_now,
                        amount_inorder = amount_inorder - execute_now,
                        updated_at = executed_date
                    WHERE
                        id = @wallet_id;

                ELSE
				    SET @wallet_id  := (SELECT id FROM wallet WHERE
                                            coin_id = main_coin_id
                                        AND
                                            user_id = ord_user_id
                                        );
                    SET @new_wallet_id  = @wallet_id;

		    SELECT CONCAT("Buy/Sell ", buy_sell, "; Wallet ID = ", @wallet_id) as ShowStatus;

                    IF @wallet_id IS NULL THEN

                        INSERT INTO wallet (
                            tx_id,
                            user_id,
                            coin_id,
                            `name`,
                            amount,
                            created_at,
                            updated_at
                        ) VALUES (
                            '',
                            ord_user_id,
                            main_coin_id,
                            main_coin,
                            @executed_total,
                            executed_date,
                            executed_date
                        );
                        SET @new_wallet_id  = LAST_INSERT_ID ();
                    ELSE

			SELECT CONCAT("Updating ", @wallet_id, " with ", @executed_total, " on ", executed_date) as ShowStatus;

                        SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                        UPDATE wallet SET
                            amount = amount + @executed_total,
                            updated_at = executed_date
                        WHERE
                            id = @wallet_id;

			SELECT CONCAT("Updated ", @wallet_id, " with ", @executed_total, " on ", executed_date) as ShowStatus;

                    END IF;

                    SELECT CONCAT("Selecting ", @new_wallet_id) as ShowStatus;

		    SET @wallet_balance = (SELECT amount FROM wallet WHERE id = @new_wallet_id);

		    SELECT CONCAT("Wallet Balance ", @wallet_balance) as ShowStatus;

                    INSERT INTO transactions (
                        transaction_user_id,
                        transaction_txid,
                        transaction_rxid,
                        transaction_addr,
                        transaction_amount,
                        transaction_market,
                        market_name,
                        transaction_ip,
                        transaction_price,
                        transaction_buysell,
                        transaction_maincoin_amount,
                        transaction_maincoin,
                        maincoin_name,
                        transaction_maincoin_wallet_id,
                        transaction_maincoin_wallet_balance,
                        transaction_status,
                        order_id,
                        order_matched,
                        created_at,
                        updated_at
                    ) VALUES (
                        ord_user_id,
                        md5(concat(param_order_id, '&', match_id)),
                        md5(concat(param_order_id, '&', match_id)),
                        md5(ip),
                        execute_now,
                        market_id,
                        market,
                        ip,
                        price,
                        2,
                        @executed_total,
                        main_coin_id,
                        main_coin,
                        @new_wallet_id,
                        @wallet_balance,
                        1,
                        param_order_id,
                        match_id,
                        executed_date,
                        executed_date
                    );
                    SET transaction_id = LAST_INSERT_ID ();

                    IF return_data IS NOT NULL THEN

                        SET return_data = concat(return_data, ', {');
                    ELSE

                        SET return_data = '{';
                    END IF;

                    SET return_data = concat(return_data, '"transaction_id": "', transaction_id, '"');
                    SET return_data = concat(return_data, ', "user_id": "', ord_user_id, '"');
                    SET return_data = concat(return_data, ', "to_user_id": "', match_user_id, '"');
                    SET return_data = concat(return_data, ', "order_id": "', param_order_id, '"');
                    SET return_data = concat(return_data, ', "order_amount": "', execute_now, '"');
                    SET return_data = concat(return_data, ', "order_price": "', price, '"');
                    SET return_data = concat(return_data, ', "order_market": "', market, '"');
                    SET return_data = concat(return_data, ', "order_maincoin": "', main_coin, '"');
                    SET return_data = concat(return_data, ', "order_buysell": "', 2, '"');
                    SET return_data = concat(return_data, ', "coin_id": "', market_id, '"');
                    SET return_data = concat(return_data, ', "update_at": "', unix_timestamp(executed_date), '"');
                    SET return_data = concat(return_data, '}');

		    SELECT CONCAT("Return data is ", return_data) as ShowStatus;

                    IF @new_wallet_id IS NOT NULL THEN

			UPDATE wallet SET
                            tx_id = transaction_id,
                            updated_at = executed_date
                        WHERE
                            id = @new_wallet_id;
                    END IF;

		    SELECT CONCAT("New wallet update done ", return_data) as ShowStatus;

                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                        `name` = market
                        AND
                        user_id = ord_user_id
                    );

		    SELECT CONCAT("Found  ", @wallet_id, " from ", market) as ShowStatus;
		    SELECT CONCAT("Update with  ", execute_now) as ShowStatus;
		    SELECT CONCAT("Result  ", 12.0 - execute_now) as ShowStatus;

                    SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                    UPDATE wallet SET
                        amount = amount - execute_now,
                        amount_inorder = amount_inorder - execute_now,
                        updated_at = executed_date
                    WHERE
                        id = @wallet_id;

		    SELECT CONCAT("First wallet update done ", return_data) as ShowStatus;

                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                        coin_id = main_coin_id
                        AND
                        user_id = match_user_id
                    );

                    UPDATE wallet SET
                        amount = amount - @executed_total,
                        amount_inorder = amount_inorder - @executed_total,
                        updated_at = executed_date
                    WHERE
                        id = @wallet_id;

		    SELECT CONCAT("Wallet updates done ", return_data) as ShowStatus;

                    SET @wallet_balance = (SELECT amount FROM wallet WHERE id = @wallet_id);
                    INSERT INTO transactions (
                        transaction_user_id,
                        transaction_txid,
                        transaction_rxid,
                        transaction_addr,
                        transaction_amount,
                        transaction_market,
                        market_name,
                        transaction_ip,
                        transaction_price,
                        transaction_buysell,
                        transaction_maincoin_amount,
                        transaction_maincoin,
                        maincoin_name,
                        transaction_maincoin_wallet_id,
                        transaction_maincoin_wallet_balance,
                        transaction_status,
                        order_id,
                        order_matched,
                        created_at,
                        updated_at
                    ) VALUES (
                        match_user_id,
                        md5(concat(match_id, '&', param_order_id)),
                        md5(concat(match_id, '&', param_order_id)),
                        md5(ip),
                        execute_now,
                        market_id,
                        market,
                        ip,
                        price,
                        1,
                        @executed_total,
                        main_coin_id,
                        main_coin,
                        @wallet_id,
                        @wallet_balance,
                        1,
                        match_id,
                        param_order_id,
                        executed_date,
                        executed_date
                    );
                    SET transaction_id = LAST_INSERT_ID ();
                    SET return_data = concat(return_data, ', {');
                    SET return_data = concat(return_data, '"transaction_id": "', transaction_id, '"');
                    SET return_data = concat(return_data, ', "user_id": "', match_user_id, '"');
                    SET return_data = concat(return_data, ', "to_user_id": "', ord_user_id, '"');
                    SET return_data = concat(return_data, ', "order_id": "', match_id, '"');
                    SET return_data = concat(return_data, ', "order_amount": "', execute_now, '"');
                    SET return_data = concat(return_data, ', "order_price": "', price, '"');
                    SET return_data = concat(return_data, ', "order_market": "', market, '"');
                    SET return_data = concat(return_data, ', "order_maincoin": "', main_coin, '"');
                    SET return_data = concat(return_data, ', "order_buysell": "', 1, '"');
                    SET return_data = concat(return_data, ', "coin_id": "', main_coin_id, '"');
                    SET return_data = concat(return_data, ', "update_at": "', unix_timestamp(executed_date), '"');
                    SET return_data = concat(return_data, '}');

		    SELECT CONCAT("Return data is ", return_data) as ShowStatus;

                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                        `name` = market
                        AND
                        user_id = match_user_id
                    );

                    IF @wallet_id IS NULL THEN

                        INSERT INTO wallet (
                            tx_id,
                            user_id,
                            coin_id,
                            `name`,
                            amount,
                            created_at,
                            updated_at
                        ) VALUES (
                            transaction_id,
                            match_user_id,
                            market_id,
                            market,
                            execute_now,
                            executed_date,
                            executed_date
                        );
                    ELSE

                    SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                        UPDATE wallet SET
                            amount = amount + execute_now,
                            updated_at = executed_date
                        WHERE
                            id = @wallet_id;
                    END IF;

                END IF;

              SELECT CONCAT("At end ", match_id) as ShowStatus;

              #Breaking the loop if nothing to execute.
                IF to_execute = 0 THEN
                    LEAVE find_match_loop;
                END IF;

            END LOOP find_match_loop;

            CLOSE prev_orders;

        END SearchBlock;

    SET return_data = concat('[', return_data, ']');
    COMMIT;

END
$$

DELIMITER ;