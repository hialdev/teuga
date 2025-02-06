-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table al_konveksi.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '4854b9c2-bde8-4e5e-a6d6-442f4c8513cb',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `input_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `value` text COLLATE utf8mb4_unicode_ci,
  `is_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table al_konveksi.settings: ~0 rows (approximately)
REPLACE INTO `settings` (`id`, `group`, `group_key`, `name`, `key`, `description`, `input_type`, `value`, `is_urgent`, `created_at`, `updated_at`) VALUES
	('0dce868d-08f8-4c65-8904-3636ae2af53d', 'Site', 'site', 'Logo', 'logo', 'Logo untuk website', 'image', 'settings/zueml9h4JGBWjyL4dOz9jo6P7dIGQbRGWZ6kVQiR.jpg', 1, '2024-12-25 21:05:21', '2024-12-25 21:05:31'),
	('1626205a-3961-4cb4-a1aa-ad3ab5720eeb', 'Site', 'site', 'Favicon', 'favicon', 'Upload gambar dengan rasio 1:1', 'image', 'settings/aaZ8I0TOvMRSHSpDXKVbNs7vpCq55bcEslyF9ueT.jpg', 1, '2024-12-25 21:05:54', '2024-12-25 21:06:02'),
	('239f1c78-7d44-42c2-8f10-f678396c5e9d', 'Theme', 'theme', 'Button Border Color', 'btn-border', 'Nilai Default \'#926e38\'', 'text', '#212121', 1, '2024-12-26 05:17:30', '2024-12-26 05:21:52'),
	('40aa2835-2e30-451f-bcb8-6fc4f5a29559', 'Theme', 'theme', 'Background Subtle', 'bg-subtle', 'Nilai default \'#926e380c\'', 'text', NULL, 1, '2024-12-26 05:16:10', '2024-12-26 05:16:10'),
	('6d6de589-6acd-437b-b86a-7f8074ae667d', 'Theme', 'theme', 'Button Hover Background', 'btn-hover-bg', 'Nilai default \'#a47d42\'', 'text', NULL, 1, '2024-12-26 05:18:02', '2024-12-26 05:18:02'),
	('8e5d7803-d425-4e13-a645-dea51a9362f9', 'Theme', 'theme', 'Button Background', 'btn-bg', 'Nilai Default #926e38', 'text', '#212121', 1, '2024-12-26 05:16:58', '2024-12-26 05:21:42'),
	('9212cb81-6357-4382-ad09-2f9c6b0b9e2b', 'Site', 'site', 'Dashboard - Paragraf', 'dashboard-paragraf', 'Paragraf pada header dashboard', 'text', 'Lihat etalase kita untuk mengetahui product yang ready stock, atau buat pesanan khusus (Custom Order) sesuai kemauan kamu!', 1, '2024-12-25 21:07:17', '2024-12-25 21:09:24'),
	('930d264f-66b0-4b7e-b0fa-8cecac392c7d', 'Theme', 'theme', 'Primary RGBA', 'primary-rgba', 'Nilai default \'163, 118, 78\'', 'text', NULL, 1, '2024-12-26 05:15:33', '2024-12-26 05:15:33'),
	('97de4ca9-788f-445e-8c92-3bcf62374fef', 'Site', 'site', 'Dashboard - Button Link', 'dashboard-button-link', 'Link untuk tombol pada header dashboard', 'text', '/custom-order/offer', 1, '2024-12-25 21:08:00', '2024-12-25 21:09:30'),
	('b2786502-1224-46fd-9062-2de265aa07aa', 'Site', 'site', 'Dashboard - Button Text', 'dashboard-button-text', 'Text untuk tombol pada header dashboard', 'text', 'Custom Order', 1, '2024-12-25 21:07:36', '2024-12-25 21:09:36'),
	('cbe5cd7b-c416-4818-aa54-907d75415104', 'Theme', 'theme', 'Button Hover Border', 'btn-hover-border', 'Nilai default \'#a47d42\'', 'text', NULL, 1, '2024-12-26 05:18:28', '2024-12-26 05:18:28'),
	('cf966a94-8ba8-47b6-89d7-f8eac3636e54', 'Site', 'site', 'Dashboard - Greeting', 'dashboard-greeting', 'Title ucapan pada header dashboard', 'text', 'Masukan nilai PPN, contoh 11 untuk PPN 11%', 1, '2024-12-25 21:06:51', '2024-12-25 21:09:55'),
	('e2786502-1344-46fd-7062-2de265ba15aa', 'Site', 'site', 'PPN %', 'ppn', 'Nilai PPN, contoh 11 untuk PPN 11%', 'number', '11', 1, '2024-12-25 21:07:36', '2024-12-25 21:09:36'),
	('ed186bd9-6cad-471e-97c6-6c8660c51375', 'Theme', 'theme', 'Primary Color', 'primary-color', 'Nilai default #926e38', 'text', '#212121', 1, '2024-12-26 05:15:02', '2024-12-26 05:24:26');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
