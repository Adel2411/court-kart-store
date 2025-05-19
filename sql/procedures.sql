-- Clean up: remove procedures if they exist
DROP PROCEDURE IF EXISTS GetOrderDetails;
DROP PROCEDURE IF EXISTS FinalizeOrder;
DROP PROCEDURE IF EXISTS GetCustomerOrderHistory;


DELIMITER $$

-- Procedure 1: Display detailed information about a specific order including total amount
CREATE PROCEDURE GetOrderDetails (
    IN p_order_id INT
)
BEGIN
    SELECT
        o.id AS order_id,
        o.created_at AS order_date,
        o.status,
        u.name AS customer_name,
        u.email AS customer_email,
        p.id AS product_id,
        p.name AS product_name,
        p.image_url,
        oi.quantity,
        oi.price AS unit_price,
        (oi.quantity * oi.price) AS subtotal,
        o.total_price AS total_amount
    FROM
        orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
    WHERE
        o.id = p_order_id;
END$$


-- Procedure 2: Finalize an order by confirming it, emptying the cart, and logging the action
CREATE PROCEDURE FinalizeOrder (
    IN p_order_id INT,
    IN p_user_id INT
)
BEGIN
    DECLARE v_order_exists INT;

    START TRANSACTION;

    SELECT COUNT(*) INTO v_order_exists
    FROM orders
    WHERE id = p_order_id AND user_id = p_user_id AND status = 'pending';

    IF v_order_exists = 1 THEN
        UPDATE orders
        SET status = 'confirmed'
        WHERE id = p_order_id;

        DELETE FROM cart_items
        WHERE user_id = p_user_id;

        INSERT INTO logs (action, user_id, order_id, message)
        VALUES ('CHECKOUT', p_user_id, p_order_id, 'Order finalized and cart emptied');

        COMMIT;
    ELSE
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid or non-pending order for this user';
    END IF;
END$$


-- Procedure 3: Display a customer's order history with item count and product names
CREATE PROCEDURE GetCustomerOrderHistory (
    IN p_user_id INT
)
BEGIN
    SELECT
        o.id AS order_id,
        o.created_at AS order_date,
        o.total_price,
        o.status,
        COUNT(oi.id) AS item_count,
        GROUP_CONCAT(p.name SEPARATOR ', ') AS products
    FROM
        orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
    WHERE
        o.user_id = p_user_id
    GROUP BY
        o.id, o.created_at, o.total_price, o.status
    ORDER BY
        o.created_at DESC;
END$$

DELIMITER ;