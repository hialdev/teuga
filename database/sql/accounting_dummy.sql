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

-- Dumping data for table teuga_accounting.accounts: ~41 rows (approximately)
REPLACE INTO `accounts` (`id`, `master_account_id`, `parent_account_id`, `code`, `account_name`, `description`, `is_logical`, `created_at`, `updated_at`) VALUES
	('00d0bbbf-6b19-42c0-8162-ce7ea179d2be', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', NULL, '105', 'PPN Masukan', 'Pencatatan PPN untuk semua Pemasukan yang dikenai PPN seperti Permintaan Client (Piutang)', 1, '2025-02-24 06:45:22', '2025-02-24 06:45:56'),
	('0428dba0-90fc-4ac3-aeb9-b6a8e17de714', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', 'ed2b3072-d039-493d-9bed-42abb045eabf', '101', 'Bank Nasional Indonesia (BNI)', NULL, 0, '2025-02-23 14:20:08', '2025-02-23 14:20:08'),
	('0ba1d611-1541-4a4a-bd82-66f472eefbde', '4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', NULL, '101', 'Beban Logistik', NULL, 1, '2025-02-12 05:27:11', '2025-02-12 05:27:11'),
	('20bebd95-0ca4-458c-bf26-a985f9d1e8af', '7c2f3c19-563d-47a4-bd64-08098d25ba1e', '7bb7912c-4722-491a-809d-558e5aa1c132', '101', 'Client Test Spesial', 'Akun Pendapatan untuk Client Test Spesial', 0, '2025-02-23 11:48:13', '2025-02-23 11:48:13'),
	('23012110-2213-4027-93d8-729c16be0171', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', '677163b9-295a-4cac-8045-fe8694392fd8', '101', 'CV Mandiri Perkasa', 'Akun Piutang untuk CV Mandiri Perkasa', 0, '2025-02-17 15:40:56', '2025-02-17 15:40:56'),
	('260c3335-3453-497b-ac79-357ff555931b', '4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', NULL, '100', 'Beban Pokok Penjualan (COGS)', NULL, 1, '2025-02-12 05:26:54', '2025-02-12 05:26:54'),
	('2e7a9f45-e814-4be6-8648-46a05d95a9d5', 'ed1848ca-f2da-4826-93db-0ded5986bd67', '536b8ddd-398b-436e-8a9c-8330c5e283df', '100', 'PT Ekspedisi Nusantara', 'Akun Hutang Logistik untuk PT Ekspedisi Nusantara', 0, '2025-02-17 17:36:43', '2025-02-17 17:36:43'),
	('2f6f9fd0-72dd-4bb7-8299-b728f799123c', '4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', NULL, '102', 'Beban Operasional', NULL, 1, '2025-02-12 05:27:29', '2025-02-12 05:27:29'),
	('334484b6-5fd8-450c-8851-5cd64ac7713f', 'de3c04bc-4474-4e52-8c3c-e2b0089605ae', NULL, '102', 'Dividen', NULL, 1, '2025-02-12 05:25:42', '2025-02-12 05:25:42'),
	('39236268-6f39-4085-992d-2fa51559559e', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', '677163b9-295a-4cac-8045-fe8694392fd8', '102', 'Client Test Spesial', 'Akun Piutang untuk Client Test Spesial', 0, '2025-02-23 11:48:13', '2025-02-23 11:48:13'),
	('3d305aa5-6862-4570-879b-a7cc4a191fd1', 'de3c04bc-4474-4e52-8c3c-e2b0089605ae', NULL, '105', 'Surplus Revaluasi Aset', 'Pertambahan nilai yang terjadi saat Revaluasi Aset', 1, '2025-02-24 08:27:07', '2025-02-24 08:27:07'),
	('536b8ddd-398b-436e-8a9c-8330c5e283df', 'ed1848ca-f2da-4826-93db-0ded5986bd67', NULL, '101', 'Hutang Pengangkutan (Logistik)', 'Akun Hutang Pengangkutan (Logistik)', 1, '2025-02-17 17:32:36', '2025-02-17 17:32:36'),
	('542ef3dc-64c5-4822-a99a-ffac578524b8', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', '677163b9-295a-4cac-8045-fe8694392fd8', '100', 'PT Bangun Jaya Abadi', 'Akun Piutang untuk PT Bangun Jaya Abadi', 0, '2025-02-17 15:19:25', '2025-02-17 15:19:25'),
	('571f5a37-7214-4c8f-8c51-46e690c2b407', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', '6c2e68c1-7d6a-485f-a56a-3436dc13e311', '101', 'Kendaraan', NULL, 0, '2025-02-28 07:49:04', '2025-02-28 07:49:04'),
	('5e851af8-3a16-4a99-a061-3783f85a9664', 'ed1848ca-f2da-4826-93db-0ded5986bd67', NULL, '104', 'Hutang Pajak', 'Pajak yang Harus Dibayarkan', 1, '2025-03-01 08:44:52', '2025-03-01 08:44:52'),
	('63d1c105-1406-46b2-a628-00e7ea6e2c43', 'ed1848ca-f2da-4826-93db-0ded5986bd67', 'ae4230dd-34bd-48df-bfc0-0702e8119fd9', '100', 'PT. Konstruksi Nusantara', 'Akun Hutang untuk PT. Konstruksi Nusantara', 0, '2025-02-17 16:56:25', '2025-02-17 16:56:25'),
	('64f31390-abaa-4030-95bd-c1dd66b5eace', 'ed1848ca-f2da-4826-93db-0ded5986bd67', '536b8ddd-398b-436e-8a9c-8330c5e283df', '101', 'Logistic Special', 'Akun Hutang Logistik untuk Logistic Special', 0, '2025-03-01 07:37:26', '2025-03-01 07:37:26'),
	('677163b9-295a-4cac-8045-fe8694392fd8', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', NULL, '103', 'Piutang (Receivable)', NULL, 1, '2025-02-12 05:21:48', '2025-02-12 05:21:48'),
	('6c2e68c1-7d6a-485f-a56a-3436dc13e311', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', NULL, '102', 'Aset Tetap (Fixed Asset)', NULL, 1, '2025-02-12 05:20:47', '2025-02-12 05:20:47'),
	('755c83cb-470f-4164-99a5-81e8780eed98', '4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', '260c3335-3453-497b-ac79-357ff555931b', '102', 'PT Nur Alif', 'Akun COGS/HPP untuk PT Nur Alif', 0, '2025-03-06 01:49:14', '2025-03-06 01:49:14'),
	('76dd71f4-e1df-484f-82dc-547cd8adbd5f', 'de3c04bc-4474-4e52-8c3c-e2b0089605ae', NULL, '100', 'Laba Ditahan', NULL, 1, '2025-02-12 05:25:10', '2025-02-12 05:25:10'),
	('79204556-ec2f-420f-9d0e-8a261b84060e', 'de3c04bc-4474-4e52-8c3c-e2b0089605ae', NULL, '101', 'Modal Pemilik', NULL, 1, '2025-02-12 05:25:23', '2025-02-12 05:25:23'),
	('7bb7912c-4722-491a-809d-558e5aa1c132', '7c2f3c19-563d-47a4-bd64-08098d25ba1e', 'cfac6288-52c2-41c3-b4e7-0a234ed2815d', '100', 'CV Mandiri Perkasa', 'Akun Pendapatan untuk CV Mandiri Perkasa', 0, '2025-02-17 15:40:56', '2025-02-17 15:40:56'),
	('807f10ab-cf0b-4cb0-a8cb-0c104fdf398f', 'ed1848ca-f2da-4826-93db-0ded5986bd67', '2e7a9f45-e814-4be6-8648-46a05d95a9d5', '100', 'PT. Beton Jaya Abadi', 'Akun Hutang untuk PT. Beton Jaya Abadi', 0, '2025-02-19 09:42:04', '2025-02-19 09:42:04'),
	('82a91758-742b-4e1b-a019-a5ffb623acff', 'de3c04bc-4474-4e52-8c3c-e2b0089605ae', NULL, '103', 'Ekuitas Temporer / Pengkoreksian', 'Segala sub akun yang mengarah ke akun ini tidak akan dihitung dalam Laporan Perubahan Ekuitas (Changes in Equity)', 1, '2025-02-12 05:26:02', '2025-02-14 06:57:19'),
	('8b63388a-8d4d-450a-9036-25a9e41162ee', 'ed1848ca-f2da-4826-93db-0ded5986bd67', '63d1c105-1406-46b2-a628-00e7ea6e2c43', '100', 'PT. Konstruksi Nusantara', 'Akun Hutang untuk PT. Konstruksi Nusantara', 0, '2025-02-17 17:06:58', '2025-02-17 17:06:58'),
	('94c1b8e5-0219-4e46-8623-02ecae7db895', '4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', '0ba1d611-1541-4a4a-bd82-66f472eefbde', '100', 'PT Ekspedisi Nusantara', 'Akun Beban Logistik untuk PT Ekspedisi Nusantara', 0, '2025-02-17 17:36:42', '2025-02-17 17:36:42'),
	('95bdf281-2c34-4e82-a38d-0966861f1d6f', 'de3c04bc-4474-4e52-8c3c-e2b0089605ae', NULL, '104', 'Laba Tahun Berjalan', NULL, 1, '2025-02-14 07:52:41', '2025-02-14 07:52:41'),
	('9bf98959-a373-4776-9a57-20d9797a3a7e', '7c2f3c19-563d-47a4-bd64-08098d25ba1e', '7bb7912c-4722-491a-809d-558e5aa1c132', '100', 'CV Mandiri Perkasa', 'Akun Pendapatan untuk CV Mandiri Perkasa', 0, '2025-02-17 17:49:36', '2025-02-17 17:49:36'),
	('9eab978e-436f-47e4-b46d-abf8e3585fa8', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', NULL, '100', 'Kas', NULL, 1, '2025-02-12 05:06:23', '2025-02-12 05:15:42'),
	('9f460b51-00f4-458f-aa50-536b101eb875', 'ed1848ca-f2da-4826-93db-0ded5986bd67', NULL, '102', 'PPN Keluaran', 'Mencatat semua PPN dari Pembelian / Pengeluaran', 1, '2025-02-24 06:44:32', '2025-02-24 06:44:32'),
	('a0c55971-f021-4dff-a0ca-058162bc08cf', 'ed1848ca-f2da-4826-93db-0ded5986bd67', NULL, '103', 'PPh 23 Terutang', 'Mencatat PPh 23 Terutang dari Pemesanan Jasa Angkut / Logistik', 1, '2025-02-24 06:47:33', '2025-02-24 06:47:33'),
	('a84cc8b8-0889-4086-9a82-58e13570832e', 'de3c04bc-4474-4e52-8c3c-e2b0089605ae', '82a91758-742b-4e1b-a019-a5ffb623acff', '100', 'Penyesuaian / Koreksi Saldo', NULL, 1, '2025-02-14 07:30:55', '2025-02-14 07:30:55'),
	('ae4230dd-34bd-48df-bfc0-0702e8119fd9', 'ed1848ca-f2da-4826-93db-0ded5986bd67', NULL, '100', 'Hutang (Payable)', NULL, 1, '2025-02-12 05:24:47', '2025-02-12 05:24:47'),
	('b4a779f5-d0d7-4a59-b60b-34d13ae0edc4', 'de3c04bc-4474-4e52-8c3c-e2b0089605ae', '82a91758-742b-4e1b-a019-a5ffb623acff', '101', 'Cadangan Modal Khusus', NULL, 1, '2025-02-14 07:38:11', '2025-02-14 07:38:11'),
	('bdae4175-a046-4cec-b299-10395a4a25fc', 'ed1848ca-f2da-4826-93db-0ded5986bd67', '2e7a9f45-e814-4be6-8648-46a05d95a9d5', '102', 'PT Nur Alif', 'Akun Hutang untuk PT Nur Alif', 0, '2025-03-06 01:49:14', '2025-03-06 01:49:14'),
	('c38b9714-ad9d-40c1-b6e4-92febf1e0f3c', 'ed1848ca-f2da-4826-93db-0ded5986bd67', '2e7a9f45-e814-4be6-8648-46a05d95a9d5', '101', 'PT. Konstruksi Nusantara', 'Akun Hutang untuk PT. Konstruksi Nusantara', 0, '2025-02-24 02:59:05', '2025-02-24 02:59:05'),
	('c74641f1-d23a-4df8-9487-d55cf7562fc4', '7c2f3c19-563d-47a4-bd64-08098d25ba1e', '7bb7912c-4722-491a-809d-558e5aa1c132', '102', 'PT Bangun Jaya Abadi', 'Akun Pendapatan untuk PT Bangun Jaya Abadi', 0, '2025-03-06 01:43:56', '2025-03-06 01:43:56'),
	('cfac6288-52c2-41c3-b4e7-0a234ed2815d', '7c2f3c19-563d-47a4-bd64-08098d25ba1e', 'e86d811f-0176-49e8-b019-213115922411', '100', 'PT Bangun Jaya Abadi', 'Akun Pendapatan untuk PT Bangun Jaya Abadi', 0, '2025-02-17 15:19:25', '2025-02-17 15:19:25'),
	('d0b62525-1e74-44fd-9a0e-13f1694b64d6', '4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', '260c3335-3453-497b-ac79-357ff555931b', '101', 'PT. Beton Jaya Abadi', 'Akun COGS/HPP untuk PT. Beton Jaya Abadi', 0, '2025-02-19 09:42:04', '2025-02-19 09:42:04'),
	('d985f325-b1fc-4149-be53-b3e17ea0d7d1', '4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', '0ba1d611-1541-4a4a-bd82-66f472eefbde', '101', 'Logistic Special', 'Akun Beban Logistik untuk Logistic Special', 0, '2025-03-01 07:37:26', '2025-03-01 07:37:26'),
	('dee006b5-0fa6-450d-af76-8249c22369b1', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', 'ed2b3072-d039-493d-9bed-42abb045eabf', '100', 'Bank Central Asia (BCA)', NULL, 0, '2025-02-23 14:19:20', '2025-02-23 14:19:20'),
	('e86d811f-0176-49e8-b019-213115922411', '7c2f3c19-563d-47a4-bd64-08098d25ba1e', NULL, '100', 'Pendapatan Penjualan', NULL, 1, '2025-02-12 05:26:27', '2025-02-12 05:26:27'),
	('eb345412-b2b8-43b5-865b-1bdc6f740649', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', NULL, '104', 'Akumulasi Penyusutan', NULL, 1, '2025-02-12 05:22:08', '2025-02-12 05:28:05'),
	('ed2b3072-d039-493d-9bed-42abb045eabf', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', NULL, '101', 'Bank', NULL, 1, '2025-02-12 05:06:40', '2025-02-12 05:16:54'),
	('edabf990-1389-4642-ba1c-9ae2248496be', '4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', '260c3335-3453-497b-ac79-357ff555931b', '100', 'PT. Konstruksi Nusantara', 'Akun COGS/HPP untuk PT. Konstruksi Nusantara', 0, '2025-02-17 16:56:25', '2025-02-17 16:56:25'),
	('ee1333a2-245c-4be0-9972-10da7cb3a07f', '4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', NULL, '103', 'Beban Penyusutan', NULL, 1, '2025-02-12 05:27:48', '2025-02-12 05:27:48'),
	('ee972f9c-d546-4b76-b0b2-9831807fb029', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', '6c2e68c1-7d6a-485f-a56a-3436dc13e311', '100', 'Bangunan Permanen', NULL, 0, '2025-02-28 04:14:03', '2025-02-28 04:16:17'),
	('f5a45e28-1c48-440f-9ccc-c95da50b6d5d', '34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', NULL, '106', 'PPN Dibayar di Muka (Aset Pajak)', 'PPN Dibayar di Muka (Aset Pajak) hanya muncul saat PPN Masukan lebih besar daripada PPN Keluaran', 1, '2025-02-24 07:02:07', '2025-02-24 07:02:07');

-- Dumping data for table teuga_accounting.master_accounts: ~5 rows (approximately)
REPLACE INTO `master_accounts` (`id`, `code`, `type`, `created_at`, `updated_at`) VALUES
	('34b24c62-78c9-476b-ac72-3eb2fe1a4bb2', 'AST', 'assets', '2025-02-12 05:02:55', '2025-02-12 05:02:55'),
	('4f7e3d6f-f6e2-41ad-9c6a-cf9e6bd9dccc', 'XPN', 'expenses', '2025-02-12 05:03:32', '2025-02-12 05:03:32'),
	('7c2f3c19-563d-47a4-bd64-08098d25ba1e', 'RVN', 'revenue', '2025-02-12 05:03:22', '2025-02-12 05:03:22'),
	('de3c04bc-4474-4e52-8c3c-e2b0089605ae', 'EQT', 'equity', '2025-02-12 05:03:15', '2025-02-12 05:03:15'),
	('ed1848ca-f2da-4826-93db-0ded5986bd67', 'LBL', 'liabilities', '2025-02-12 05:03:07', '2025-02-12 05:03:07');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
