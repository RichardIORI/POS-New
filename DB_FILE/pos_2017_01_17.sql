ALTER TABLE `order_items` CHANGE COLUMN `is_waimai` `is_takeout` ENUM('N','Y') NOT NULL DEFAULT 'N';

ALTER TABLE `orders` MODIFY COLUMN `tax_amount` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `subtotal` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `total` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `card_val` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `cash_val` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `tip` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `paid` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `change` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `fix_discount` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `percent_discount` float DEFAULT 0;
ALTER TABLE `orders` MODIFY COLUMN `discount_value` float DEFAULT 0;
ALTER TABLE `order_items` MODIFY COLUMN `extras_amount` float DEFAULT 0;


ALTER TABLE `order_items` ADD COLUMN `comb_id` int DEFAULT 0;