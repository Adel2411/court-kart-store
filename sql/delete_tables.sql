-- Select the correct database
-- USE `court-kart-store`;

-- Disable foreign key checks to avoid dependency issues
SET FOREIGN_KEY_CHECKS = 0;

-- Drop all tables (order matters due to foreign key dependencies)
DROP TABLE IF EXISTS logs;
DROP TABLE IF EXISTS canceled_orders;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
