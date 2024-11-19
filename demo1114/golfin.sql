-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: golf_inventory
-- ------------------------------------------------------
-- Server version 8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

-- Create the `users` table
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data into the `users` table
INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'john_doe', '$2y$10$4wX9C2vnA7M7bKHFL5O9FO5xZlNQ0Ej7NmGHvNm5y.UV1gp15Ou0m', '2024-11-18 00:00:00'); -- Example user, password: "password123"

-- Create the `golf_items` table
DROP TABLE IF EXISTS `golf_items`;

CREATE TABLE `golf_items` (
  `item_id` INT NOT NULL AUTO_INCREMENT,
  `item_name` VARCHAR(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manufacturer` VARCHAR(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` DECIMAL(10,2) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data into the `golf_items` table
INSERT INTO `golf_items` (`item_id`, `item_name`, `manufacturer`, `price`) VALUES
(1, 'Pro V1 Golf Ball', 'Titleist', 50.00),
(2, 'Mavrik Driver', 'Callaway', 399.99),
(3, 'Spider Putter', 'TaylorMade', 279.99);

--
-- Dump completed on 2024-11-18
--
