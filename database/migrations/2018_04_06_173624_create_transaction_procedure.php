<?php
// OLD Transaction procedure file, new one is committed to database!!


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure  = "CREATE PROCEDURE `order_execute`(IN param_order_id INTEGER, OUT return_data TEXT)
READS SQL DATA

BEGIN

    DECLARE ord_user_id         INT(10);
    DECLARE ord_amount          DECIMAL(20, 9);
    DECLARE amount_executed     INT;
    DECLARE market              VARCHAR(25);
    DECLARE market_id           INT(10);
    DECLARE ip                  VARCHAR(25);
    DECLARE price               DECIMAL(20, 9);
    DECLARE buy_sell            TINYINT;
    DECLARE main_coin           VARCHAR(25);
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

        #Here giving amount - amount_executed, this will help you to use the same procedure if we need to execute
        #an Order, Full/Partial -  manually from admin side in a required situation.
        SET to_execute          = ord_amount - amount_executed;

        CLOSE order_table;

        SearchBlock: BEGIN

            DECLARE match_id                INT;
            DECLARE match_user_id           INT;
            DECLARE match_amount            INT;
            DECLARE match_to_execute        INT;
            DECLARE execute_now             INT;

            DECLARE transaction_id          INT;
########################################################################################################################
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
                    order_user_id <> ord_user_id
                AND
                    (order_amount - executed_amount) > 0
                AND
                    order_market = market
                AND
                    order_price = price
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
########################################################################################################################
                #Locked current match for update.
                SELECT * FROM orders WHERE order_id = match_id FOR UPDATE;

                SET @balance_check   = match_to_execute - to_execute;

                SET execute_now         = to_execute;

                #Checking order is a full match or not
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

                #Updating the amount to_execute variable.
                SET to_execute  = to_execute - execute_now;

                #Updating current order.
                UPDATE orders SET
                    executed_amount = (executed_amount + execute_now),
                    order_executed  = @executed_current,
                    updated_at = executed_date
                WHERE
                    order_id = param_order_id;

                #Updating order match.
                UPDATE orders SET
                    executed_amount = (executed_amount + execute_now),
                    order_executed  = @executed_match,
                    updated_at = executed_date
                WHERE
                    order_id = match_id;
########################################################################################################################

                SET @executed_total     = execute_now * price;
                SET market_id          := (SELECT coin_id FROM  coins WHERE coin_coin = market);

                ############ WALLET UPDATES & TRANSACTIONS ############

                IF buy_sell = 1 THEN #### BUY ####

                ################################## For ordered user ##################################
                #MAIN COIN
                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                                            coin_id = main_coin_id
                                        AND
                                            user_id = ord_user_id
                                        );

                    #Locking wallet row
                    SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                    UPDATE wallet SET
                        amount = amount - @executed_total,
                        amount_inorder = amount_inorder - @executed_total,
                        updated_at = executed_date
                    WHERE
                        id = @wallet_id;

                #TRANSACTION FOR BUY - ordered user
                    SET @wallet_blalnce = (SELECT amount FROM wallet WHERE id = @wallet_id);

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
                        @wallet_blalnce,
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

                    SET return_data = concat(return_data, '\"transaction_id\": \"',transaction_id, '\"');
                    SET return_data = concat(return_data, ', \"user_id\": \"', ord_user_id, '\"');
                    SET return_data = concat(return_data, ', \"to_user_id\": \"', match_user_id, '\"');
                    SET return_data = concat(return_data, ', \"order_id\": \"', param_order_id, '\"');
                    SET return_data = concat(return_data, ', \"order_amount\": \"', execute_now, '\"');
                    SET return_data = concat(return_data, ', \"order_price\": \"', price, '\"');
                    SET return_data = concat(return_data, ', \"order_market\": \"', market, '\"');
                    SET return_data = concat(return_data, ', \"order_maincoin\": \"', main_coin, '\"');
                    SET return_data = concat(return_data, ', \"order_buysell\": \"', 1, '\"');
                    SET return_data = concat(return_data, ', \"coin_id\": \"', main_coin_id, '\"'); #for BUY => maincoin_id
                    SET return_data = concat(return_data, ', \"update_at\": \"', unix_timestamp(executed_date), '\"');
                    SET return_data = concat(return_data, '}');

                #MARKET
                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                                            `name` = market
                                        AND
                                            user_id = ord_user_id
                                        );

                    #If no existing market coin
                    IF @wallet_id IS NULL THEN

                        #tx_id is the id from transactions table with transaction take place to buy this market
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

                        #Locking wallet row
                        SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                        UPDATE wallet SET
                            amount = amount + execute_now,
                            updated_at = executed_date
                        WHERE
                            id = @wallet_id;

                    END IF;

                ################################## For matched user ##################################
                #MAIN COIN
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

                        #Locking wallet row
                        SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                        UPDATE wallet SET
                            amount = amount + @executed_total,
                            updated_at = executed_date
                        WHERE
                            id = @wallet_id;
                    END IF;

                #TRANSACTION FOR SELL - matched user
                    SET @wallet_blalnce = (SELECT amount FROM wallet WHERE id = @new_wallet_id);
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
                        @wallet_blalnce,
                        1,
                        match_id,
                        param_order_id,
                        executed_date,
                        executed_date
                    );
                    SET transaction_id = LAST_INSERT_ID ();

                    SET return_data = concat(return_data, ', {');
                    SET return_data = concat(return_data, '\"transaction_id\": \"', transaction_id, '\"');
                    SET return_data = concat(return_data, ', \"user_id\": \"', match_user_id, '\"');
                    SET return_data = concat(return_data, ', \"to_user_id\": \"', ord_user_id, '\"');
                    SET return_data = concat(return_data, ', \"order_id\": \"', match_id, '\"');
                    SET return_data = concat(return_data, ', \"order_amount\": \"', execute_now, '\"');
                    SET return_data = concat(return_data, ', \"order_price\": \"', price, '\"');
                    SET return_data = concat(return_data, ', \"order_market\": \"', market, '\"');
                    SET return_data = concat(return_data, ', \"order_maincoin\": \"', main_coin, '\"');
                    SET return_data = concat(return_data, ', \"order_buysell\": \"', 2, '\"');
                    SET return_data = concat(return_data, ', \"coin_id\": \"', market_id, '\"'); #for SELL => market_id
                    SET return_data = concat(return_data, ', \"update_at\": \"', unix_timestamp(executed_date), '\"');
                    SET return_data = concat(return_data, '}');


                    if @new_wallet_id IS NOT NULL THEN

                        #Here we are updating tx_id only for first insertion to wallet
                        UPDATE wallet SET
                            tx_id = transaction_id,
                            updated_at = executed_date
                        WHERE
                            id = @new_wallet_id;
                    END IF;

                #MARKET
                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                                            `name` = market
                                        AND
                                            user_id = match_user_id
                                        );

                    #Locking wallet row
                    SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                    UPDATE wallet SET
                        amount = amount - execute_now,
                        amount_inorder = amount_inorder - execute_now,
                        updated_at = executed_date
                    WHERE
                        id = @wallet_id;

                ELSE  #### SELL ####

                ################################## For ordered user ##################################
                #MAIN COIN
                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                                            coin_id = main_coin_id
                                        AND
                                            user_id = ord_user_id
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
                            ord_user_id,
                            main_coin_id,
                            main_coin,
                            @executed_total,
                            executed_date,
                            executed_date
                        );
                        SET @new_wallet_id  = LAST_INSERT_ID ();
                    ELSE

                        #Locking wallet row
                        SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                        UPDATE wallet SET
                            amount = amount + @executed_total,
                            updated_at = executed_date
                        WHERE
                            id = @wallet_id;

                    END IF;

                #TRANSACTION FOR SELL - ordered user
                    SET @wallet_blalnce = (SELECT amount FROM wallet WHERE id = @new_wallet_id);

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
                        @wallet_blalnce,
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

                    SET return_data = concat(return_data, '\"transaction_id\": \"', transaction_id, '\"');
                    SET return_data = concat(return_data, ', \"user_id\": \"', ord_user_id, '\"');
                    SET return_data = concat(return_data, ', \"to_user_id\": \"', match_user_id, '\"');
                    SET return_data = concat(return_data, ', \"order_id\": \"', param_order_id, '\"');
                    SET return_data = concat(return_data, ', \"order_amount\": \"', execute_now, '\"');
                    SET return_data = concat(return_data, ', \"order_price\": \"', price, '\"');
                    SET return_data = concat(return_data, ', \"order_market\": \"', market, '\"');
                    SET return_data = concat(return_data, ', \"order_maincoin\": \"', main_coin, '\"');
                    SET return_data = concat(return_data, ', \"order_buysell\": \"', 2, '\"');
                    SET return_data = concat(return_data, ', \"coin_id\": \"', market_id, '\"'); #for SELL => market_id
                    SET return_data = concat(return_data, ', \"update_at\": \"', unix_timestamp(executed_date), '\"');
                    SET return_data = concat(return_data, '}');

                    #updating wallet with new transaction id if first insert to wallet.
                    IF @new_wallet_id IS NOT NULL THEN

                        #Here we are updating tx_id only for first insertion to wallet
                        UPDATE wallet SET
                            tx_id = transaction_id,
                            updated_at = executed_date
                        WHERE
                            id = @new_wallet_id;
                    END IF;

                #MARKET
                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                        `name` = market
                        AND
                        user_id = ord_user_id
                    );

                    UPDATE wallet SET
                        amount = amount - execute_now,
                        amount_inorder = amount_inorder - execute_now,
                        updated_at = executed_date
                    WHERE
                        id = @wallet_id;

                ################################## For matched user ##################################
                    #MAIN COIN
                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                        coin_id = main_coin_id
                        AND
                        user_id = match_user_id
                    );

                    #Locking wallet row
                    SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                    UPDATE wallet SET
                        amount = amount - @executed_total,
                        amount_inorder = amount_inorder - @executed_total,
                        updated_at = executed_date
                    WHERE
                        id = @wallet_id;

                #TRANSACTION FOR BUY - matched user
                    SET @wallet_blalnce = (SELECT amount FROM wallet WHERE id = @wallet_id);
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
                        @wallet_blalnce,
                        1,
                        match_id,
                        param_order_id,
                        executed_date,
                        executed_date
                    );
                    SET transaction_id = LAST_INSERT_ID ();
                    SET return_data = concat(return_data, ', {');
                    SET return_data = concat(return_data, '\"transaction_id\": \"', transaction_id, '\"');
                    SET return_data = concat(return_data, ', \"user_id\": \"', match_user_id, '\"');
                    SET return_data = concat(return_data, ', \"to_user_id\": \"', ord_user_id, '\"');
                    SET return_data = concat(return_data, ', \"order_id\": \"', match_id, '\"');
                    SET return_data = concat(return_data, ', \"order_amount\": \"', execute_now, '\"');
                    SET return_data = concat(return_data, ', \"order_price\": \"', price, '\"');
                    SET return_data = concat(return_data, ', \"order_market\": \"', market, '\"');
                    SET return_data = concat(return_data, ', \"order_maincoin\": \"', main_coin, '\"');
                    SET return_data = concat(return_data, ', \"order_buysell\": \"', 1, '\"');
                    SET return_data = concat(return_data, ', \"coin_id\": \"', main_coin_id, '\"'); #for Buy => main_coin_id
                    SET return_data = concat(return_data, ', \"update_at\": \"', unix_timestamp(executed_date), '\"');
                    SET return_data = concat(return_data, '}');

                    #MARKET
                    SET @wallet_id  := (SELECT id FROM wallet WHERE
                        `name` = market
                        AND
                        user_id = match_user_id
                    );

                    #If no existing market coin
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

                        #Locking wallet row
                        SELECT * FROM wallet WHERE id = @wallet_id FOR UPDATE;

                        UPDATE wallet SET
                            amount = amount + execute_now,
                            updated_at = executed_date
                        WHERE
                            id = @wallet_id;
                    END IF;

                END IF;

                #Breaking the loop if nothing to execute.
                IF to_execute = 0 THEN
                    LEAVE find_match_loop;
                END IF;

            END LOOP find_match_loop;

            CLOSE prev_orders;

        END SearchBlock;

    SET return_data = concat('[', return_data, ']');
    COMMIT;

END";

        DB::unprepared('DELETE FROM `migrations` WHERE `migration` =\'2018_03_13_181706_create_transaction_procedure\'');
        DB::unprepared('DROP PROCEDURE IF EXISTS order_execute');
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS order_execute');
    }
}
