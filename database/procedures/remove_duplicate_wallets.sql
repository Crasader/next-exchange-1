DROP PROCEDURE IF EXISTS remove_duplicate_wallets;

DELIMITER $$

CREATE PROCEDURE `remove_duplicate_wallets`(IN param_user_id INTEGER, IN param_coin_id INTEGER)
READS SQL DATA

  BEGIN

    SET @initial_wallet := (SELECT id FROM  wallet WHERE user_id = param_user_id AND  coin_id = param_coin_id ORDER BY id LIMIT 1);

    SELECT @sum_amount := sum(amount), @sum_inorder := sum(amount_inorder) FROM wallet WHERE user_id = param_user_id AND  coin_id = param_coin_id;

    UPDATE wallet SET amount = @sum_amount, amount_inorder = @sum_inorder WHERE id = @initial_wallet;

    DELETE FROM wallet WHERE id > @initial_wallet;

  END $$

DELIMITER ;