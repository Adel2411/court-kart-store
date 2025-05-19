-- Drop existing triggers if they exist
DROP TRIGGER IF EXISTS AfterOrderConfirmed;
DROP TRIGGER IF EXISTS BeforeOrderItemInsert;
DROP TRIGGER IF EXISTS AfterOrderCancelled;
DROP TRIGGER IF EXISTS LogCanceledOrder;


-- 1. Trigger to update stock after order status changes to 'confirmed'
DELIMITER $$

CREATE TRIGGER AfterOrderConfirmed
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    DECLARE v_done INT DEFAULT 0;
    DECLARE v_product_id INT;
    DECLARE v_quantity INT;
    DECLARE cur CURSOR FOR 
        SELECT product_id, quantity FROM order_items WHERE order_id = NEW.id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    IF OLD.status != 'confirmed' AND NEW.status = 'confirmed' THEN
        INSERT INTO logs (action, user_id, order_id, message)
        VALUES ('CHECKOUT', NEW.user_id, NEW.id, CONCAT('Order #', NEW.id, ' confirmed'));

        OPEN cur;
        read_loop: LOOP
            FETCH cur INTO v_product_id, v_quantity;
            IF v_done THEN
                LEAVE read_loop;
            END IF;
            UPDATE products
            SET stock = stock - v_quantity
            WHERE id = v_product_id;
        END LOOP;
        CLOSE cur;
        
        INSERT INTO logs (action, user_id, order_id, message)
        VALUES ('PRODUCT_UPDATE', NEW.user_id, NEW.id, CONCAT('Stock updated for order #', NEW.id));
    END IF;
END$$

DELIMITER ;

-- 2. Trigger to prevent order item insertion if stock is insufficient
DELIMITER $$

CREATE TRIGGER BeforeOrderItemInsert
BEFORE INSERT ON order_items
FOR EACH ROW
BEGIN
    DECLARE available_stock INT;
    DECLARE v_user_id INT;
    
    SELECT stock INTO available_stock
    FROM products
    WHERE id = NEW.product_id;
    
    SELECT user_id INTO v_user_id
    FROM orders
    WHERE id = NEW.order_id;

    IF NEW.quantity > available_stock THEN
        INSERT INTO logs (action, user_id, order_id, message)
        VALUES ('PRODUCT_UPDATE', v_user_id, NEW.order_id, 
                CONCAT('Failed to add product #', NEW.product_id, ' to order #', NEW.order_id, ': Requested ', NEW.quantity, ', Available ', available_stock));
                
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot insert order item: requested quantity exceeds available stock';
    END IF;
END$$

DELIMITER ;

-- 3. Trigger to restore stock when an order is canceled
DELIMITER $$

CREATE TRIGGER AfterOrderCancelled
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    DECLARE v_done INT DEFAULT 0;
    DECLARE v_product_id INT;
    DECLARE v_quantity INT;
    DECLARE cur CURSOR FOR 
        SELECT product_id, quantity FROM order_items WHERE order_id = NEW.id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    IF OLD.status != 'cancelled' AND NEW.status = 'cancelled' THEN
        -- Log the order cancellation
        INSERT INTO logs (action, user_id, order_id, message)
        VALUES ('ORDER_CANCEL', NEW.user_id, NEW.id, CONCAT('Order #', NEW.id, ' canceled'));
        
        OPEN cur;
        read_loop: LOOP
            FETCH cur INTO v_product_id, v_quantity;
            IF v_done THEN
                LEAVE read_loop;
            END IF;
            UPDATE products
            SET stock = stock + v_quantity
            WHERE id = v_product_id;
        END LOOP;
        CLOSE cur;
        
        INSERT INTO logs (action, user_id, order_id, message)
        VALUES ('PRODUCT_UPDATE', NEW.user_id, NEW.id, CONCAT('Stock restored for order #', NEW.id));
    END IF;
END$$

DELIMITER ;

-- 4. Trigger to log canceled orders into the canceled_orders table
DELIMITER $$

CREATE TRIGGER LogCanceledOrder
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.status != 'cancelled' AND NEW.status = 'cancelled' THEN
        -- Only insert if there's no existing record
        INSERT INTO canceled_orders (order_id, reason, canceled_at)
        SELECT NEW.id, 'Order was canceled by user or admin', NOW()
        FROM dual
        WHERE NOT EXISTS (
            SELECT 1 FROM canceled_orders WHERE order_id = NEW.id
        );
        
        INSERT INTO logs (action, user_id, order_id, message)
        VALUES ('ORDER_CANCEL', NEW.user_id, NEW.id, CONCAT('Order #', NEW.id, ' cancellation recorded'));
    END IF;
END$$

DELIMITER ;