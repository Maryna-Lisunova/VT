-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Май 22 2025 г., 01:23
-- Версия сервера: 8.4.4
-- Версия PHP: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Создание базы данных
CREATE DATABASE IF NOT EXISTS `calendar_base`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;
USE `calendar_base`;

-- ======================================================
-- Таблица: users
-- ======================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ======================================================
-- Таблица: genres
-- ======================================================
DROP TABLE IF EXISTS `genres`;
CREATE TABLE `genres` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ======================================================
-- Таблица: works (картины, книги, фильмы)
-- ======================================================
DROP TABLE IF EXISTS `works`;
CREATE TABLE `works` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,           -- Автор записи (FK к users)
  `genre_id` INT NOT NULL,          -- Жанр работы (FK к genres)
  `calendar_date` DATE NOT NULL,    -- Дата записи в календаре
  `title` VARCHAR(255) NOT NULL,    -- Заголовок или название работы
  `content_type` ENUM('text', 'image', 'youtube') NOT NULL,  -- Тип содержимого
  `content` TEXT NOT NULL,          -- Само содержимое (текст, URL, описание)
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_genre_id` (`genre_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ======================================================
-- Демонстрационные данные
-- ======================================================

-- Пользователи
INSERT INTO `users` (`username`, `email`, `password_hash`) VALUES
  ('alice', 'alice@example.com', 'hashed_password1'),
  ('bob', 'bob@example.com', 'hashed_password2');

-- Жанры
INSERT INTO `genres` (`name`) VALUES
  ('Кино'),
  ('Литература'),
  ('Изобразительное искусство');

-- Записи календаря: картины, книги, фильмы
INSERT INTO `works` (`user_id`, `genre_id`, `calendar_date`, `title`, `content_type`, `content`) VALUES
  (1, 1, '2025-12-01', 'Christmas pudding', 'image', 'c:\WebData\_site1\apps\my_project\Public\images\pudding.jpg');
  (2, 2, '2025-12-02', 'Grinch', 'youtube', 'https://www.youtube.com/embed/nytpYtLtHpE'),
  (3, 3, '2025-05-23', 'Christmas story', 'text', 'Christmas is a tonic for our souls. It moves us to think of others rather than of ourselves. It directs our thoughts to giving.'),
  

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
