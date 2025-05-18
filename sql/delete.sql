-- Disable foreign key checks for truncating tables
SET FOREIGN_KEY_CHECKS = 0;

-- Truncate all tables (remove all data but keep structure)
TRUNCATE TABLE logs;
TRUNCATE TABLE canceled_orders;
TRUNCATE TABLE order_items;
TRUNCATE TABLE orders;
TRUNCATE TABLE cart_items;
TRUNCATE TABLE products;
TRUNCATE TABLE users;

-- Verify all tables are empty
-- SELECT * FROM logs;
-- SELECT * FROM canceled_orders;
-- SELECT * FROM order_items;
-- SELECT * FROM orders;
-- SELECT * FROM cart_items;
-- SELECT * FROM products;
-- SELECT * FROM users;


-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Now drop all tables in reverse order of dependencies
-- First drop tables that reference other tables
DROP TABLE IF EXISTS logs;
DROP TABLE IF EXISTS canceled_orders;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;

-- Verify all tables are gone
SELECT CONCAT('Tables remaining: ', COUNT(*)) AS status 
FROM information_schema.tables 
WHERE table_schema = DATABASE();
