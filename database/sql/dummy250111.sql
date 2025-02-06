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

-- Dumping data for table al_konveksi.bahan_baku: ~3 rows (approximately)
REPLACE INTO `bahan_baku` (`id`, `image`, `nama`, `cek_tersedia`, `keterangan`, `created_at`, `updated_at`) VALUES
	('828dfb66-c2a3-4d22-affe-199f5a266247', 'raw_materials/pCSJrEXZkWD104kbxjDngXwRxGM27USjW3A5uKED.jpg', 'Raw Material Two', 1, 'Raw material two', '2024-12-27 06:07:30', '2024-12-27 06:07:30'),
	('878b77f9-13d2-4244-b4a2-17af79f5c478', 'raw_materials/Qe90mpOlABOoNXn310AmW7aPn0oDdj4R81ZiGQGs.jpg', 'Raw Material One', 1, 'raw material one', '2024-12-27 06:07:15', '2024-12-27 06:07:15'),
	('b004bfa4-8a73-418c-ac66-bc3ae5b25496', 'raw_materials/XEfdhjChetnHQFPS5w0LxYEJnk8KEutyO3BkVdZn.jpg', 'Raw Material Three', 1, 'asdasd', '2024-12-27 07:11:09', '2024-12-27 07:11:09');

-- Dumping data for table al_konveksi.banks: ~1 rows (approximately)
REPLACE INTO `banks` (`id`, `nama_bank`, `logo`, `no_rekening`, `nama_rekening`, `keterangan`, `created_at`, `updated_at`) VALUES
	('ced2bf6a-0c3c-4d3e-bc09-833eb816ab73', 'Bank Central Asia (BCA)', 'banks/CXU5EgO2Lylzgoy6adrIBZETTlVBYZZ3fmE2ODoe.png', 9871378961, 'Muhammad Nur Alif', NULL, '2025-01-10 14:36:44', '2025-01-10 14:36:44');

-- Dumping data for table al_konveksi.failed_jobs: ~0 rows (approximately)

-- Dumping data for table al_konveksi.flyers: ~0 rows (approximately)

-- Dumping data for table al_konveksi.flyer_views: ~0 rows (approximately)

-- Dumping data for table al_konveksi.migrations: ~0 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2024_12_23_075457_create_permission_tables', 1),
	(6, '2024_12_23_115821_flyer_table', 1),
	(7, '2024_12_23_115825_flyer_views_table', 1),
	(8, '2024_12_25_032817_raw_materials_table', 1),
	(9, '2024_12_25_032851_suppliers_table', 1),
	(10, '2024_12_25_032908_customers_table', 1),
	(11, '2024_12_25_032918_product_categories_table', 1),
	(12, '2024_12_25_033045_products_table', 1),
	(13, '2024_12_25_033045_stocks_table', 1),
	(14, '2024_12_25_041928_custom_orders_table', 1),
	(15, '2024_12_25_042700_request_productions_table', 1),
	(16, '2024_12_25_070245_purchase_raw_materials', 1),
	(17, '2024_12_25_071220_orders_table', 1),
	(18, '2024_12_25_072111_custom_order_payments_table', 1),
	(19, '2024_12_25_072321_reviews_table', 1),
	(20, '2024_12_25_072336_returs_table', 1),
	(21, '2024_12_25_074150_settings_table', 1),
	(22, '2025_01_08_013502_banks', 1);

-- Dumping data for table al_konveksi.model_has_permissions: ~0 rows (approximately)

-- Dumping data for table al_konveksi.model_has_roles: ~6 rows (approximately)
REPLACE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(3, 'App\\Models\\User', 3),
	(3, 'App\\Models\\User', 4),
	(4, 'App\\Models\\User', 5),
	(4, 'App\\Models\\User', 6);

-- Dumping data for table al_konveksi.password_resets: ~0 rows (approximately)

-- Dumping data for table al_konveksi.pelanggan: ~5 rows (approximately)
REPLACE INTO `pelanggan` (`id`, `nama`, `email`, `user_id`, `created_at`, `updated_at`) VALUES
	('2f0067f7-e048-4f3d-bc2a-753f3ceb23d0', 'customer1', 'customer1@gmail.com', 5, '2025-01-10 14:35:12', '2025-01-10 14:35:12'),
	('433f49e4-29df-4f78-83e3-211c8c16a113', 'Mantap Jiwah', 'hallo@gmail.com', NULL, '2024-12-27 06:50:50', '2024-12-27 06:50:50'),
	('5eff0ceb-482f-45f7-b219-a2c2735df2ef', 'Mba Limul', 'limul@hiamalif.com', 6, '2025-01-10 14:38:47', '2025-01-10 14:38:47'),
	('aee0c9c2-31f8-4dd3-b9f6-1dc4a291d577', NULL, 'customer@gmail.com', NULL, '2024-12-27 06:20:42', '2024-12-27 06:20:42'),
	('daef3c31-57e6-4454-aec7-80a52e2c8de1', NULL, 'wkwk@gmail.com', NULL, '2024-12-27 06:49:36', '2024-12-27 06:49:36');

-- Dumping data for table al_konveksi.pembayaran_pesanan_khusus: ~0 rows (approximately)

-- Dumping data for table al_konveksi.pembelian_bahan_baku: ~0 rows (approximately)

-- Dumping data for table al_konveksi.pengajuan_produksi: ~0 rows (approximately)

-- Dumping data for table al_konveksi.pengembalian: ~0 rows (approximately)
REPLACE INTO `pengembalian` (`id`, `pesanan_id`, `user_id`, `pesanan_khusus_id`, `keterangan`, `cek_pesanan_khusus`, `created_at`, `updated_at`) VALUES
	('7733b582-1ebc-4c43-9e31-b933fe34b271', 'cf65562f-b25c-4491-a5ca-69c97e310829', 5, NULL, 'Salah dalam warna dan ukuran', NULL, '2025-01-11 03:45:32', '2025-01-11 03:45:32');

-- Dumping data for table al_konveksi.permissions: ~0 rows (approximately)

-- Dumping data for table al_konveksi.personal_access_tokens: ~0 rows (approximately)

-- Dumping data for table al_konveksi.pesanan: ~5 rows (approximately)
REPLACE INTO `pesanan` (`id`, `user_id`, `code`, `status`, `produk`, `bukti_pembayaran`, `total_harga`, `created_at`, `updated_at`) VALUES
	('09d8ca5d-2b66-425a-84cf-83355813c921', 1, 'ORDER/004/BRC/I/25', '2', '{"68f76269-038a-4cc0-8951-540bc7713818": {"id": "68f76269-038a-4cc0-8951-540bc7713818", "qty": 3}, "bf1c342f-2cc3-43f6-9394-6facc635022b": {"id": "bf1c342f-2cc3-43f6-9394-6facc635022b", "qty": 2}, "f5ac43b3-3594-4c2b-9b6d-cfe81aeef823": {"id": "f5ac43b3-3594-4c2b-9b6d-cfe81aeef823", "qty": 1}}', 'orders/SDqtsoS8yoLsCkJ06x9iXobTZYFnEedcWdLMR3oC.png', 184218, '2025-01-10 14:39:02', '2025-01-10 15:08:53'),
	('37260163-dc67-4ce8-8882-08c2f96a3e55', 5, 'ORDER/003/BRC/I/25', '3', '{"68f76269-038a-4cc0-8951-540bc7713818": {"id": "68f76269-038a-4cc0-8951-540bc7713818", "qty": 2}, "bf1c342f-2cc3-43f6-9394-6facc635022b": {"id": "bf1c342f-2cc3-43f6-9394-6facc635022b", "qty": 1}, "f5ac43b3-3594-4c2b-9b6d-cfe81aeef823": {"id": "f5ac43b3-3594-4c2b-9b6d-cfe81aeef823", "qty": 2}}', 'orders/H4Sflj62vc185IXGAcsSPuBIMD5g24Dfw4HtGycl.png', 192108, '2025-01-10 14:37:31', '2025-01-10 15:09:18'),
	('4604558e-671c-4419-b5c7-540c86162418', 5, 'ORDER/002/BRC/I/25', '2', '{"bf1c342f-2cc3-43f6-9394-6facc635022b": {"id": "bf1c342f-2cc3-43f6-9394-6facc635022b", "qty": 1}, "f0b827eb-7db2-49a6-bdb8-f8703543f4b8": {"id": "f0b827eb-7db2-49a6-bdb8-f8703543f4b8", "qty": 1}}', 'orders/6DGshTlBUVvPL0RblSsCtDJ95lZ5C7mMeJ3l6ipm.webp', 133692, '2025-01-10 14:37:10', '2025-01-10 15:24:06'),
	('cf65562f-b25c-4491-a5ca-69c97e310829', 5, 'ORDER/005/BRC/I/25', '2', '{"68f76269-038a-4cc0-8951-540bc7713818": {"id": "68f76269-038a-4cc0-8951-540bc7713818", "qty": 1}, "bf1c342f-2cc3-43f6-9394-6facc635022b": {"id": "bf1c342f-2cc3-43f6-9394-6facc635022b", "qty": 1}}', 'orders/rJBSayqiIHi4Er1RQRLAL9nKT74rWgNM6gmERd7y.webp', 50221, '2025-01-10 14:40:23', '2025-01-10 15:08:47'),
	('eb679215-4975-4705-a243-b804d34a75e5', 6, 'ORDER/006/BRC/I/25', '2', '{"bf1c342f-2cc3-43f6-9394-6facc635022b": {"id": "bf1c342f-2cc3-43f6-9394-6facc635022b", "qty": 1}, "f0b827eb-7db2-49a6-bdb8-f8703543f4b8": {"id": "f0b827eb-7db2-49a6-bdb8-f8703543f4b8", "qty": 1}, "f5ac43b3-3594-4c2b-9b6d-cfe81aeef823": {"id": "f5ac43b3-3594-4c2b-9b6d-cfe81aeef823", "qty": 1}}', 'orders/PsaT9ma1v6CITiNmCOYj3t2VvHenvifhCQ2ydFaf.png', 191802, '2025-01-10 14:50:16', '2025-01-10 15:08:38');

-- Dumping data for table al_konveksi.pesanan_khusus: ~0 rows (approximately)

-- Dumping data for table al_konveksi.produk: ~4 rows (approximately)
REPLACE INTO `produk` (`id`, `produk_kategori_id`, `image`, `nama`, `slug`, `keterangan`, `harga`, `created_at`, `updated_at`) VALUES
	('68f76269-038a-4cc0-8951-540bc7713818', 1, 'products/lAOHfDAOrPaCCEaAzzqpyOXT45K6gaUSzVJKG5yA.jpg', 'Gamis Two', 'gamis-two', '', 23122, '2024-12-27 07:09:38', '2024-12-27 07:09:38'),
	('bf1c342f-2cc3-43f6-9394-6facc635022b', 2, 'products/KDsGHqEioe0lvhoo6DEE9hhwB48uk9ouJYGTiQfW.jpg', 'Jersey Two', 'jersey-two', '', 22122, '2024-12-27 07:08:47', '2024-12-27 07:08:47'),
	('f0b827eb-7db2-49a6-bdb8-f8703543f4b8', 2, 'products/REXvMjXSxHaKtcvCNldBiE3rYopL6Z0kMmAchLbD.jpg', 'Jersey One', 'jersey-one', '', 98321, '2024-12-27 07:08:25', '2024-12-27 07:08:25'),
	('f5ac43b3-3594-4c2b-9b6d-cfe81aeef823', 1, 'products/BgAuRCHcHkmoTecRIN1HlMoXlJvA99gVlouUlRNs.jpg', 'Gamis One', 'gamis-one', '', 52352, '2024-12-27 07:09:14', '2024-12-27 07:09:14');

-- Dumping data for table al_konveksi.produk_kategori: ~2 rows (approximately)
REPLACE INTO `produk_kategori` (`id`, `nama`, `created_at`, `updated_at`) VALUES
	(1, 'Gamis', '2024-12-27 07:07:02', '2024-12-27 07:07:02'),
	(2, 'Jersey', '2024-12-27 07:07:50', '2024-12-27 07:07:50');

-- Dumping data for table al_konveksi.roles: ~4 rows (approximately)
REPLACE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'developer', 'web', '2025-01-10 14:30:30', '2025-01-10 14:30:30'),
	(2, 'admin', 'web', '2025-01-10 14:30:30', '2025-01-10 14:30:30'),
	(3, 'employee', 'web', '2025-01-10 14:30:30', '2025-01-10 14:30:30'),
	(4, 'pelanggan', 'web', '2025-01-10 14:30:30', '2025-01-10 14:30:30');

-- Dumping data for table al_konveksi.role_has_permissions: ~0 rows (approximately)

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

-- Dumping data for table al_konveksi.stok: ~4 rows (approximately)
REPLACE INTO `stok` (`id`, `produk_id`, `stok`, `created_at`, `updated_at`) VALUES
	('12e67fda-74bd-43af-9a46-a96b1f476033', 'bf1c342f-2cc3-43f6-9394-6facc635022b', 200, '2024-12-27 07:08:47', '2024-12-27 07:08:47'),
	('20d208ee-a1a1-4dd4-8a74-1887adcaf7c9', 'f0b827eb-7db2-49a6-bdb8-f8703543f4b8', 500, '2024-12-27 07:08:25', '2024-12-27 07:08:25'),
	('a483c27e-6de2-42e4-a810-684c48d9a9bf', 'f5ac43b3-3594-4c2b-9b6d-cfe81aeef823', 350, '2024-12-27 07:09:14', '2024-12-27 07:09:14'),
	('f2af2a74-2355-41bd-94f6-fc3ed9b6dfa5', '68f76269-038a-4cc0-8951-540bc7713818', 222, '2024-12-27 07:09:38', '2024-12-27 07:09:38');

-- Dumping data for table al_konveksi.suppliers: ~4 rows (approximately)
REPLACE INTO `suppliers` (`id`, `nama`, `email`, `keterangan`, `created_at`, `updated_at`) VALUES
	('1ecfd3de-dc25-4ed1-8d33-ffcc124f1bbe', 'PT Underwear Invisible', 'look@underwear.id', 'Supplier bahan pakaian dalam', '2024-12-27 07:00:03', '2024-12-27 07:00:03'),
	('c36780fe-4464-47cc-b11a-12f6e32b43b3', 'PT AL Sport International', 'contact@alsport.com', 'Supplier bahan baju jersey', '2024-12-27 06:59:19', '2024-12-27 06:59:19'),
	('ec11d038-0fd2-4fda-8bf4-61c6b0ccd335', 'PT Serba Ada', 'hi@serbaada.com', 'Supplier bahan bahan terbesar di indonesia', '2024-12-27 07:00:54', '2024-12-27 07:00:54'),
	('f6d75c8f-6dca-41fe-8266-181a95790f3d', 'Aleeyah Collection', 'inc@aleeyah.com', 'Supplier bahan bahan gamis', '2024-12-27 06:58:35', '2024-12-27 06:58:35');

-- Dumping data for table al_konveksi.ulasan: ~0 rows (approximately)
REPLACE INTO `ulasan` (`id`, `pesanan_id`, `produk_id`, `user_id`, `pesanan_khusus_id`, `rating`, `keterangan`, `cek_pesanan_khusus`, `created_at`, `updated_at`) VALUES
	('32c4e433-28c6-4c97-a50f-02fa0d99e28a', 'eb679215-4975-4705-a243-b804d34a75e5', 'bf1c342f-2cc3-43f6-9394-6facc635022b', 6, NULL, 5, 'Bagus semuanya, puas', NULL, '2025-01-11 02:55:17', '2025-01-11 02:55:17'),
	('4722d929-04dd-4a71-b56a-fb9f97ba89ad', '4604558e-671c-4419-b5c7-540c86162418', 'bf1c342f-2cc3-43f6-9394-6facc635022b', 5, NULL, 5, 'Bagus sesuai ekspetasi', NULL, '2025-01-11 03:47:08', '2025-01-11 03:47:08'),
	('6107c126-98d1-4504-92c0-fb76f3cd050a', '09d8ca5d-2b66-425a-84cf-83355813c921', 'bf1c342f-2cc3-43f6-9394-6facc635022b', 1, NULL, 4, 'Sangat bagus saya suka sekali', NULL, '2025-01-10 19:24:26', '2025-01-10 19:24:26'),
	('768e74a0-4728-4208-80c7-bf5039069827', '4604558e-671c-4419-b5c7-540c86162418', 'f0b827eb-7db2-49a6-bdb8-f8703543f4b8', 5, NULL, 5, 'Bagus sesuai ekspetasi', NULL, '2025-01-11 03:47:08', '2025-01-11 03:47:08'),
	('82e90577-c096-4e98-9470-8a02cd6cfd16', 'eb679215-4975-4705-a243-b804d34a75e5', 'f5ac43b3-3594-4c2b-9b6d-cfe81aeef823', 6, NULL, 5, 'Bagus semuanya, puas', NULL, '2025-01-11 02:55:17', '2025-01-11 02:55:17'),
	('b177d419-4476-4d96-9aaa-6fdbb81c5004', '09d8ca5d-2b66-425a-84cf-83355813c921', 'f5ac43b3-3594-4c2b-9b6d-cfe81aeef823', 1, NULL, 5, 'Sangat bagus saya suka sekali', NULL, '2025-01-10 19:24:26', '2025-01-10 19:24:26'),
	('cba33860-92bc-47f8-8413-290bc16591bd', '09d8ca5d-2b66-425a-84cf-83355813c921', '68f76269-038a-4cc0-8951-540bc7713818', 1, NULL, 5, 'Sangat bagus saya suka sekali', NULL, '2025-01-10 19:24:26', '2025-01-10 19:24:26'),
	('ef35b34a-e016-4947-adcf-b3fc38f32251', 'eb679215-4975-4705-a243-b804d34a75e5', 'f0b827eb-7db2-49a6-bdb8-f8703543f4b8', 6, NULL, 4, 'Bagus semuanya, puas', NULL, '2025-01-11 02:55:17', '2025-01-11 02:55:17');

-- Dumping data for table al_konveksi.users: ~6 rows (approximately)
REPLACE INTO `users` (`id`, `image`, `name`, `email`, `phone`, `position`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'AL Developer', 'al@hiamalif.com', NULL, NULL, NULL, '$2y$10$GTn.6HirjG02FJHDjlyU2uTbucjfC7bbI8PT0s9oYPExnIltBLD62', NULL, '2025-01-10 14:30:30', '2025-01-10 14:30:30'),
	(2, NULL, 'Admin User', 'admin@example.com', NULL, NULL, NULL, '$2y$10$v8eATzUkoRFIQuBOp4RUiuIiIxExPupeq.U33pyIfKk5/GJmGYKVa', NULL, '2025-01-10 14:30:30', '2025-01-10 14:30:30'),
	(3, NULL, 'Employee User 1', 'employee1@example.com', NULL, NULL, NULL, '$2y$10$rk.V8x9vV0WOolfW7enwtexm7ahr9qVChcMZkHBxNRHT44BILU5jG', NULL, '2025-01-10 14:30:30', '2025-01-10 14:30:30'),
	(4, NULL, 'Employee User 2', 'employee2@example.com', NULL, NULL, NULL, '$2y$10$HeeYURV9jiKBztqtbqP/WOz1ft.fAk1ZpaFClIBz4SPbb/Ja7ULLq', NULL, '2025-01-10 14:30:30', '2025-01-10 14:30:30'),
	(5, NULL, 'customer1', 'customer1@gmail.com', NULL, NULL, NULL, '$2y$10$3LE6gg61qPk.rCWdm6BmveOc5FA.VPs6ef5zBkyBEqq056qKpVzkG', NULL, '2025-01-10 14:35:12', '2025-01-10 14:35:12'),
	(6, NULL, 'Mba Limul', 'limul@hiamalif.com', NULL, NULL, NULL, '$2y$10$QEAKk9lB8I4XVdzEBqRpVua50xu8NsDiS342rSEuDK3TnlTL.wmvS', NULL, '2025-01-10 14:38:47', '2025-01-10 14:38:47');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
