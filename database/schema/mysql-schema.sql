/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `lang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_username_unique` (`username`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `deposits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deposits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `payment_method_id` bigint unsigned NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `converted_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_percent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `structured_data` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '{"crypto":{"transaction_hash":"b7f3e2a1c9d84f6e0a5b3c7d9e1f4a8b2c6d5e7f9a0b1c2d3e4f5a6b7c8","wallet_address":"TN1Z3YwRk9sQH2LJ8a6Xx4EwQb5F3M7P9C","currency":"USDT","network":"TRC20"},"bank_transfer":{"bank_name":"Chase Bank","account_holder":"John Doe","account_number":"1234567890","routing_number":"021000021 (nullable)","swift":"CHASUS33 (nullable)"},"digital_wallet":{"payment_id":"PAY-84739201","identity_id":"johndoe@email.com \\/ johndoe123"}}',
  `auto_res_dump` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Dump of response from third party payment provider',
  `status` enum('pending','completed','failed','partial_payment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deposits_user_id_foreign` (`user_id`),
  KEY `deposits_payment_method_id_foreign` (`payment_method_id`),
  CONSTRAINT `deposits_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE,
  CONSTRAINT `deposits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `etf_holding_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etf_holding_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `etf_holding_id` bigint unsigned NOT NULL,
  `ticker` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shares` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `price_at_action` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_usd` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_amount_percent` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` enum('buy','sell') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `etf_holding_histories_user_id_foreign` (`user_id`),
  KEY `etf_holding_histories_etf_holding_id_foreign` (`etf_holding_id`),
  CONSTRAINT `etf_holding_histories_etf_holding_id_foreign` FOREIGN KEY (`etf_holding_id`) REFERENCES `etf_holdings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `etf_holding_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `etf_holdings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etf_holdings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ticker` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shares` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `average_price` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `pnl` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `pnl_percent` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `etf_holdings_user_id_foreign` (`user_id`),
  CONSTRAINT `etf_holdings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `forex_trading_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forex_trading_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `symbol` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode` enum('live','demo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'live',
  `type` enum('Buy','Sell') COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` enum('Market','Limit','Stop') COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` decimal(18,4) NOT NULL,
  `price` decimal(18,8) NOT NULL,
  `stop_loss` decimal(18,8) DEFAULT NULL,
  `take_profit` decimal(18,8) DEFAULT NULL,
  `status` enum('pending','filled','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forex_trading_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `forex_trading_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `forex_trading_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forex_trading_positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `symbol` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode` enum('live','demo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'live',
  `side` enum('Buy','Sell') COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` decimal(18,4) NOT NULL,
  `entry_price` decimal(18,8) NOT NULL,
  `current_price` decimal(18,8) NOT NULL,
  `stop_loss` decimal(18,8) DEFAULT NULL,
  `take_profit` decimal(18,8) DEFAULT NULL,
  `margin` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `unrealized_pnl` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `status` enum('open','closed','liquidated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forex_trading_positions_user_id_foreign` (`user_id`),
  CONSTRAINT `forex_trading_positions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `futures_trading_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `futures_trading_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('limit','market') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticker` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `side` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leverage` int NOT NULL DEFAULT '1',
  `take_profit` decimal(20,8) DEFAULT NULL,
  `stop_loss` decimal(20,8) DEFAULT NULL,
  `locked_margin` decimal(20,8) NOT NULL DEFAULT '0.00000000',
  `status` enum('pending','filled','canceled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `futures_trading_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `futures_trading_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `futures_trading_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `futures_trading_positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ticker` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `side` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `take_profit` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stop_loss` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `margin` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leverage` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unrealized_pnl` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `realized_pnl` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `futures_trading_positions_user_id_foreign` (`user_id`),
  CONSTRAINT `futures_trading_positions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `investment_earnings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `investment_earnings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `investment_id` bigint unsigned NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interest` enum('stocks_and_etfs','crypto_assets','real_estate','fixed_income','commodities','businesses_and_startups','art_and_collectibles','gaming_and_esports','cash_and_savings') COLLATE utf8mb4_unicode_ci NOT NULL,
  `risk_profile` enum('conservative','balanced','growth') COLLATE utf8mb4_unicode_ci NOT NULL,
  `investment_goal` enum('short_term','medium_term','long_term') COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `investment_earnings_user_id_foreign` (`user_id`),
  KEY `investment_earnings_investment_id_foreign` (`investment_id`),
  CONSTRAINT `investment_earnings_investment_id_foreign` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `investment_earnings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `investment_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `investment_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `interests` json NOT NULL COMMENT '["stocks_and_etfs","crypto_assets","real_estate","fixed_income","commodities","businesses_and_startups","art_and_collectibles","gaming_and_esports","cash_and_savings"]',
  `risk_profile` enum('conservative','balanced','growth') COLLATE utf8mb4_unicode_ci NOT NULL,
  `investment_goal` enum('short_term','medium_term','long_term') COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int unsigned NOT NULL,
  `duration_type` enum('hours','days','weeks','months','years') COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_investment` decimal(18,2) NOT NULL,
  `max_investment` decimal(18,2) NOT NULL,
  `return_percent` decimal(10,2) NOT NULL,
  `return_interval` enum('hourly','daily','weekly','monthly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `compounding` tinyint(1) NOT NULL DEFAULT '0',
  `capital_returned` tinyint(1) NOT NULL DEFAULT '1',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `investment_plans_is_enabled_is_featured_index` (`is_enabled`,`is_featured`),
  KEY `investment_plans_risk_profile_index` (`risk_profile`),
  KEY `investment_plans_investment_goal_index` (`investment_goal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `investments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `investments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `investment_plan_id` bigint unsigned NOT NULL,
  `capital_invested` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `compounding_capital` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_reinvest` tinyint(1) NOT NULL DEFAULT '0',
  `roi_earned` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `next_roi_at` bigint unsigned DEFAULT NULL,
  `expires_at` bigint unsigned NOT NULL,
  `total_cycles` bigint unsigned NOT NULL,
  `cycle_count` bigint unsigned NOT NULL,
  `status` enum('active','completed','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `investments_user_id_foreign` (`user_id`),
  KEY `investments_investment_plan_id_foreign` (`investment_plan_id`),
  KEY `investments_status_index` (`status`),
  CONSTRAINT `investments_investment_plan_id_foreign` FOREIGN KEY (`investment_plan_id`) REFERENCES `investment_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `investments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kycs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kycs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_front` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_back` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selfie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kycs_user_id_foreign` (`user_id`),
  CONSTRAINT `kycs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `margin_trading_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `margin_trading_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('limit','market') COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_mode` enum('normal','borrow','repay') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `ticker` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `side` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` decimal(28,8) NOT NULL,
  `price` decimal(28,8) NOT NULL,
  `leverage` decimal(8,2) NOT NULL DEFAULT '5.00',
  `locked_margin` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `take_profit` decimal(28,8) DEFAULT NULL,
  `stop_loss` decimal(28,8) DEFAULT NULL,
  `status` enum('pending','filled','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `timestamp` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `margin_trading_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `margin_trading_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `margin_trading_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `margin_trading_positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ticker` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `side` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` decimal(28,8) NOT NULL,
  `entry_price` decimal(28,8) NOT NULL,
  `current_price` decimal(28,8) NOT NULL,
  `take_profit` decimal(28,8) DEFAULT NULL,
  `stop_loss` decimal(28,8) DEFAULT NULL,
  `margin` decimal(28,8) NOT NULL,
  `leverage` decimal(8,2) NOT NULL,
  `unrealized_pnl` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `realized_pnl` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `timestamp` bigint unsigned NOT NULL,
  `status` enum('open','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `margin_trading_positions_user_id_foreign` (`user_id`),
  CONSTRAINT `margin_trading_positions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_wildcard` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` text COLLATE utf8mb4_unicode_ci,
  `type` enum('user','admin','frontend') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'frontend',
  `parent_id` bigint unsigned DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_parent_id_foreign` (`parent_id`),
  KEY `menu_items_type_parent_id_sort_order_index` (`type`,`parent_id`,`sort_order`),
  CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notification_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_messages_user_id_foreign` (`user_id`),
  CONSTRAINT `notification_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `onboardings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `onboardings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `risk_profile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'conservative, balanced, growth',
  `investment_goal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'short_term, medium_term, long_term',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `onboardings_user_id_foreign` (`user_id`),
  CONSTRAINT `onboardings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('crypto','card','bank_transfer','digital_wallet') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'digital_wallet',
  `class` enum('manual','automatic') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `payment_information` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('enabled','disabled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enabled',
  `pay` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `stock_holding_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_holding_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `stock_holding_id` bigint unsigned NOT NULL,
  `ticker` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shares` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `price_at_action` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_usd` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_amount_percent` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` enum('buy','sell') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_holding_histories_user_id_foreign` (`user_id`),
  KEY `stock_holding_histories_stock_holding_id_foreign` (`stock_holding_id`),
  CONSTRAINT `stock_holding_histories_stock_holding_id_foreign` FOREIGN KEY (`stock_holding_id`) REFERENCES `stock_holdings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_holding_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `stock_holdings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_holdings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ticker` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shares` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `average_price` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `pnl` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `pnl_percent` decimal(20,10) NOT NULL DEFAULT '0.0000000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_holdings_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_holdings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ticker` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_at_purchase` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'current price at purchase',
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'amount in website currency',
  `amount_usd` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'amount in usd',
  `shares` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'number of shares',
  `fee_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'fee amount in website currency',
  `fee_amount_percent` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_cost` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_at_sale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchased_at` bigint unsigned NOT NULL COMMENT 'unix timestamp',
  `sold_at` bigint unsigned DEFAULT NULL,
  `status` enum('purchased','sold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'purchased',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_user_id_foreign` (`user_id`),
  CONSTRAINT `stocks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trading_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trading_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `account_type` enum('spot','futures','margin','forex') COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_status` enum('active','inactive','suspended','closed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `borrowed` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode` enum('live','demo') COLLATE utf8mb4_unicode_ci NOT NULL,
  `equity` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `level` enum('micro','mini','standard','pro','vip') COLLATE utf8mb4_unicode_ci NOT NULL,
  `margin_call` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '100',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trading_accounts_user_id_foreign` (`user_id`),
  CONSTRAINT `trading_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `converted_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `converted_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_balance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','banned','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referral_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer_id` bigint unsigned DEFAULT NULL,
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_referrer_id_foreign` (`referrer_id`),
  CONSTRAINT `users_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `withdrawal_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdrawal_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('crypto','bank_transfer','digital_wallet') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'crypto',
  `class` enum('manual','automatic') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `payment_information` longtext COLLATE utf8mb4_unicode_ci,
  `status` enum('enabled','disabled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enabled',
  `pay` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `withdrawals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdrawals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `withdrawal_method_id` bigint unsigned NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `converted_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_percent` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_payable` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_hash` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `structured_data` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '{"crypto":{"transaction_hash":"b7f3e2a1c9d84f6e0a5b3c7d9e1f4a8b2c6d5e7f9a0b1c2d3e4f5a6b7c8","wallet_address":"TN1Z3YwRk9sQH2LJ8a6Xx4EwQb5F3M7P9C","currency":"USDT","network":"TRC20"},"bank_transfer":{"bank_name":"Chase Bank","account_holder":"John Doe","account_number":"1234567890","routing_number":"021000021 (nullable)","swift":"CHASUS33 (nullable)"},"digital_wallet":{"payment_id":"PAY-84739201","identity_id":"johndoe@email.com \\/ johndoe123"}}',
  `auto_res_dump` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Dump of response from third party payment provider',
  `status` enum('pending','completed','failed','partial_payment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `withdrawals_user_id_foreign` (`user_id`),
  KEY `withdrawals_withdrawal_method_id_foreign` (`withdrawal_method_id`),
  CONSTRAINT `withdrawals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `withdrawals_withdrawal_method_id_foreign` FOREIGN KEY (`withdrawal_method_id`) REFERENCES `withdrawal_methods` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2026_01_24_155335_create_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2026_01_25_110215_create_transactions_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2026_01_25_142151_create_notification_messages_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2026_01_25_154925_create_onboardings_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2026_01_25_172222_create_menu_items_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_01_25_200518_create_kycs_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_01_26_100416_add_lang_to_users_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_01_26_124259_create_payment_methods_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2026_01_26_185536_create_deposits_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2026_01_27_090842_add_pay_to_deposits_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2026_01_27_113402_create_investment_plans_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2026_01_27_173853_add_to_menu_items_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2026_01_27_235044_create_investments_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2026_01_28_094322_create_investment_earnings_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2026_02_01_204823_create_stock_holdings_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2026_02_01_204847_create_stock_holding_histories_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2026_02_02_143241_create_etf_holdings_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2026_02_02_143255_create_etf_holding_histories_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2026_02_08_070436_create_trading_accounts_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2026_02_09_123319_create_futures_trading_positions_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2026_02_09_123347_create_futures_trading_orders_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2026_02_09_132432_add_tp_sl_to_futures_trading_orders_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2026_02_09_132831_add_locked_margin_to_futures_trading_orders_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2026_02_09_133614_add_leverage_to_futures_trading_orders_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2026_02_09_173000_create_margin_trading_orders_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2026_02_09_173001_create_margin_trading_positions_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2026_02_09_174000_add_tp_sl_to_margin_trading_positions_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2026_02_09_181000_add_borrowed_to_trading_accounts_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2026_02_09_181001_add_order_mode_to_margin_trading_orders_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2026_02_09_190000_create_forex_trading_orders_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2026_02_09_190001_create_forex_trading_positions_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2026_02_09_192000_add_mode_to_forex_tables',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2026_02_09_174825_modify_cache_value_to_longtext',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2026_02_10_101812_create_withdrawal_methods_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2026_02_10_115832_create_withdrawals_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2026_02_19_103956_create_admins_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2026_02_19_105536_add_lang_to_admins_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2026_02_20_065551_add_status_to_users_table',31);
