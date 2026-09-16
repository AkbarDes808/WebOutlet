-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 14, 2025 at 12:03 PM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `bahans`
--

CREATE TABLE `bahans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_outlet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tepung_roti` int(11) NOT NULL DEFAULT '0',
  `tepung_bumbu` int(11) NOT NULL DEFAULT '0',
  `garam` int(11) NOT NULL DEFAULT '0',
  `bubuk_cabe` int(11) NOT NULL DEFAULT '0',
  `telur` int(11) NOT NULL DEFAULT '0',
  `gula` int(11) NOT NULL DEFAULT '0',
  `ayam` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bahans`
--

INSERT INTO `bahans` (`id`, `nama_outlet`, `tepung_roti`, `tepung_bumbu`, `garam`, `bubuk_cabe`, `telur`, `gula`, `ayam`, `created_at`, `updated_at`) VALUES
(2, 'Outlet 2', 6000, 7500, 800, 950, 110, 1800, 11500, '2025-09-29 20:19:28', '2025-09-29 20:19:28'),
(3, 'Outlet Renon', 9500, 9800, 1400, 1100, 180, 2200, 18000, '2025-09-29 20:19:28', '2025-09-29 20:19:28'),
(4, 'Outlet Kuta', 3200, 4000, 500, 300, 50, 1000, 5500, '2025-09-29 20:19:28', '2025-09-29 20:19:28'),
(5, 'Outlet Sanur', 7100, 6800, 950, 600, 95, 1500, 9800, '2025-09-29 20:19:28', '2025-09-29 20:19:28'),
(6, 'Outlet Ubud', 5500, 6200, 700, 550, 80, 1300, 8500, '2025-09-29 20:19:28', '2025-09-29 20:19:28'),
(11, 'Outlet Teuku Umar', 0, 0, 801, 0, 0, 0, 0, '2025-10-03 05:51:57', '2025-10-03 05:51:57'),
(12, 'Outlet Teuku Umar', 0, 0, 925, 0, 0, 0, 0, '2025-10-03 05:52:23', '2025-10-03 05:52:23'),
(13, 'Outlet 1', 0, 0, 214, 0, 0, 0, 0, '2025-10-03 15:52:11', '2025-10-03 15:52:11'),
(14, 'Outlet 1', 0, 0, 224, 0, 0, 0, 0, '2025-10-03 15:53:04', '2025-10-03 15:53:04'),
(15, 'Outlet 1', 0, 0, 224, 0, 0, 0, 0, '2025-10-03 15:53:07', '2025-10-03 15:53:07'),
(16, 'Outlet 1', 0, 0, 218, 0, 0, 0, 0, '2025-10-03 15:57:17', '2025-10-03 15:57:17'),
(17, 'Outlet 1', 0, 0, 4, 0, 0, 0, 0, '2025-10-03 16:08:15', '2025-10-03 16:08:15'),
(18, 'Outlet 1', 9, 4, 9, 6, 7, 7, 5, '2025-10-03 16:12:27', '2025-10-03 16:12:27'),
(19, 'Outlet 1', 4, 4, 9, 6, 7, 7, 5, '2025-10-04 21:52:51', '2025-10-04 21:52:51'),
(20, 'Outlet 1', 4, 4, 6, 6, 7, 7, 5, '2025-10-04 23:11:37', '2025-10-04 23:11:37');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-admin@example.com|127.0.0.1', 'i:2;', 1765713410),
('laravel-cache-admin@example.com|127.0.0.1:timer', 'i:1765713410;', 1765713410),
('laravel-cache-akbaarrr.26@gmail.com|127.0.0.1', 'i:2;', 1765713726),
('laravel-cache-akbaarrr.26@gmail.com|127.0.0.1:timer', 'i:1765713726;', 1765713726);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marinasi`
--

CREATE TABLE `marinasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daging_ayam` int(11) NOT NULL DEFAULT '0',
  `saus_teriyaki` int(11) NOT NULL DEFAULT '0',
  `bawang_putih` int(11) NOT NULL DEFAULT '0',
  `lada` int(11) NOT NULL DEFAULT '0',
  `garam` int(11) NOT NULL DEFAULT '0',
  `ketumbar` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marinasi`
--

INSERT INTO `marinasi` (`id`, `daging_ayam`, `saus_teriyaki`, `bawang_putih`, `lada`, `garam`, `ketumbar`, `created_at`, `updated_at`) VALUES
(1, 3, 6, 6, 4, 7, 6, '2025-10-03 04:06:04', '2025-10-03 04:06:04');

-- --------------------------------------------------------

--
-- Table structure for table `marinasis`
--

CREATE TABLE `marinasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_11_09_133213_add_is_admin_to_users_table', 2),
(5, '2025_09_30_040926_create_bahans_table', 3),
(6, '2025_09_30_041404_create_bahans_table', 4),
(7, '2025_10_03_114558_create_marinasi_table', 5),
(8, '2025_10_03_115107_create_marinasis_table', 6),
(9, '2025_10_04_012418_modify_role_column_in_users_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('tJJ75evX2vJW0SDa36mQaxPfZ572Czl8NiSLTgQI', 30, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.1 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVmNpazVuTU5FVjN3UFZzRnVTSHZHZGo5THE2TFluZkZyT0VQRnd0aiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozMDt9', 1765713732);

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `KD_DOK` int(11) DEFAULT NULL,
  `KD_TPS` varchar(512) DEFAULT NULL,
  `NM_PENGANGKUT` varchar(512) DEFAULT NULL,
  `NO_FLIGHT` varchar(512) DEFAULT NULL,
  `CALL_SIGN` varchar(512) DEFAULT NULL,
  `TGL_TIBA` date DEFAULT NULL,
  `KD_GUDANG` varchar(512) DEFAULT NULL,
  `REF_NUMBER` varchar(512) DEFAULT NULL,
  `NO_BLAWB` varchar(512) DEFAULT NULL,
  `TGL_BLAWB` int(11) DEFAULT NULL,
  `NO_MASTER_BLAWB` varchar(512) DEFAULT NULL,
  `TGL_MASTER_BLAWB` varchar(512) DEFAULT NULL,
  `ID_CONSIGNEE` bigint(11) DEFAULT NULL,
  `CONSIGNEE` varchar(512) DEFAULT NULL,
  `BRUTO` double DEFAULT NULL,
  `NO_BC31` varchar(512) DEFAULT NULL,
  `TGL_BC31` int(11) DEFAULT NULL,
  `NO_POS_BC31` varchar(512) DEFAULT NULL,
  `CONT_ASAL` varchar(512) DEFAULT NULL,
  `SERI_KEMAS` varchar(512) DEFAULT NULL,
  `KD_EMAS` varchar(512) DEFAULT NULL,
  `JML_KEMAS` varchar(512) DEFAULT NULL,
  `KD_TIMBUN` varchar(512) DEFAULT NULL,
  `KD_DOK_INOUT` int(11) DEFAULT NULL,
  `NO_DOK_INOUT` varchar(512) NOT NULL,
  `TGL_DOK_INOUT` int(11) DEFAULT NULL,
  `WK_INOUT` bigint(11) DEFAULT NULL,
  `KD_SAR_ANGKUT_INOUT` int(11) DEFAULT NULL,
  `NO_POL` varchar(512) DEFAULT NULL,
  `PEL_MUAT` varchar(512) DEFAULT NULL,
  `PEL_TRANSIT` varchar(512) DEFAULT NULL,
  `PEL_BONGKAR` varchar(512) DEFAULT NULL,
  `GUDANG_TUJUAN` varchar(512) DEFAULT NULL,
  `KODE_KANTOR` int(11) DEFAULT NULL,
  `NO_DAFTAR_PABEAN` varchar(512) DEFAULT NULL,
  `TGL_DAFTER_PABEAN` int(11) DEFAULT NULL,
  `NO_SEGEL_BC` varchar(512) DEFAULT NULL,
  `TGL_SEGEL_BC` varchar(512) DEFAULT NULL,
  `NO_IJIN_TPS` varchar(512) DEFAULT NULL,
  `TGL_IJIN_TPS` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`KD_DOK`, `KD_TPS`, `NM_PENGANGKUT`, `NO_FLIGHT`, `CALL_SIGN`, `TGL_TIBA`, `KD_GUDANG`, `REF_NUMBER`, `NO_BLAWB`, `TGL_BLAWB`, `NO_MASTER_BLAWB`, `TGL_MASTER_BLAWB`, `ID_CONSIGNEE`, `CONSIGNEE`, `BRUTO`, `NO_BC31`, `TGL_BC31`, `NO_POS_BC31`, `CONT_ASAL`, `SERI_KEMAS`, `KD_EMAS`, `JML_KEMAS`, `KD_TIMBUN`, `KD_DOK_INOUT`, `NO_DOK_INOUT`, `TGL_DOK_INOUT`, `WK_INOUT`, `KD_SAR_ANGKUT_INOUT`, `NO_POL`, `PEL_MUAT`, `PEL_TRANSIT`, `PEL_BONGKAR`, `GUDANG_TUJUAN`, `KODE_KANTOR`, `NO_DAFTAR_PABEAN`, `TGL_DAFTER_PABEAN`, `NO_SEGEL_BC`, `TGL_SEGEL_BC`, `NO_IJIN_TPS`, `TGL_IJIN_TPS`) VALUES
(8, 'IBL1', 'QG', '0', ' ', '2024-04-02', 'BT20', 'IBL24TPS103', '0', 20240402, NULL, NULL, 840446712301000, 'PT INDO BERJAYA LOGISTIK', 21.1, NULL, 20240402, NULL, 'BP 8098 Y', '1', 'PK', '1', NULL, 55, 'IE1CSN5001CGK', 20240402, 20240402140000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240402, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', ' ', '2024-04-02', 'BT20', 'IBL24TPS103', '0', 20240402, NULL, NULL, 840446712301000, 'PT INDO BERJAYA LOGISTIK', 12.65, NULL, 20240402, NULL, 'BP 8098 Y', '1', 'PK', '1', NULL, 55, 'IE1CSN5002CGK', 20240402, 20240402140000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240402, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', ' ', '2024-04-02', 'BT20', 'IBL24TPS103', '0', 20240402, NULL, NULL, 840446712301000, 'PT INDO BERJAYA LOGISTIK', 7.8, NULL, 20240402, NULL, 'BP 8098 Y', '1', 'PK', '1', NULL, 55, 'IE1CSN5003CGK', 20240402, 20240402140000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240402, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', ' ', '2024-04-02', 'BT20', 'IBL24TPS103', '0', 20240402, NULL, NULL, 840446712301000, 'PT INDO BERJAYA LOGISTIK', 12.1, NULL, 20240402, NULL, 'BP 8098 Y', '1', 'PK', '1', NULL, 55, 'IE1CSN5004CGK', 20240402, 20240402140000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240402, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', ' ', '2024-04-02', 'BT20', 'IBL24TPS103', '0', 20240402, NULL, NULL, 840446712301000, 'PT INDO BERJAYA LOGISTIK', 10.3, NULL, 20240402, NULL, 'BP 8098 Y', '1', 'PK', '1', NULL, 55, 'IE1CSN5005CGK', 20240402, 20240402140000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240402, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-04-03', 'BT20', 'IBL24TPS104', '1', 20240403, NULL, NULL, 840446712301001, 'PT INDO BERJAYA LOGISTIK', 10.5, NULL, 20240403, NULL, 'BP 8098 Z', '2', 'PK', '3', NULL, 55, 'IE1CSN5006CGK', 20240403, 20240403120000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240403, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-04-03', 'BT20', 'IBL24TPS105', '2', 20240403, NULL, NULL, 840446712301002, 'PT INDO BERJAYA LOGISTIK', 15.2, NULL, 20240403, NULL, 'BP 8098 Z', '3', 'PK', '4', NULL, 55, 'IE1CSN5007CGK', 20240403, 20240403130000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240403, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-04-04', 'BT20', 'IBL24TPS106', '3', 20240404, NULL, NULL, 840446712301003, 'PT INDO BERJAYA LOGISTIK', 8.7, NULL, 20240404, NULL, 'BP 8098 Z', '4', 'PK', '5', NULL, 55, 'IE1CSN5008CGK', 20240404, 20240404100000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240404, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-04-04', 'BT20', 'IBL24TPS107', '4', 20240404, NULL, NULL, 840446712301004, 'PT INDO BERJAYA LOGISTIK', 12.3, NULL, 20240404, NULL, 'BP 8098 Z', '5', 'PK', '6', NULL, 55, 'IE1CSN5009CGK', 20240404, 20240404110000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240404, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-04-05', 'BT20', 'IBL24TPS108', '5', 20240405, NULL, NULL, 840446712301005, 'PT INDO BERJAYA LOGISTIK', 9.6, NULL, 20240405, NULL, 'BP 8098 Z', '6', 'PK', '7', NULL, 55, 'IE1CSN5010CGK', 20240405, 20240405100000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240405, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-04-05', 'BT20', 'IBL24TPS109', '6', 20240405, NULL, NULL, 840446712301006, 'PT INDO BERJAYA LOGISTIK', 11.4, NULL, 20240405, NULL, 'BP 8098 Z', '7', 'PK', '8', NULL, 55, 'IE1CSN5011CGK', 20240405, 20240405120000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240405, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-04-06', 'BT20', 'IBL24TPS110', '7', 20240406, NULL, NULL, 840446712301007, 'PT INDO BERJAYA LOGISTIK', 8.8, NULL, 20240406, NULL, 'BP 8098 Z', '8', 'PK', '9', NULL, 55, 'IE1CSN5012CGK', 20240406, 20240406100000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240406, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-04-07', 'BT20', 'IBL24TPS201', '9', 20240407, NULL, NULL, 840446712301009, 'PT INDO BERJAYA LOGISTIK', 10.2, NULL, 20240407, NULL, 'BP 8098 Z', '10', 'PK', '12', NULL, 55, 'IE1CSN5014CGK', 20240407, 20240407120000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240407, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-05-01', 'BT20', 'IBL24TPS301', '10', 20240501, NULL, NULL, 840446712301010, 'PT INDO BERJAYA LOGISTIK', 12.1, NULL, 20240501, NULL, 'BP 8098 Z', '11', 'PK', '13', NULL, 55, 'IE1CSN5015CGK', 20240501, 20240501130000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240501, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-06-10', 'BT20', 'IBL24TPS401', '11', 20240610, NULL, NULL, 840446712301011, 'PT INDO BERJAYA LOGISTIK', 15.4, NULL, 20240610, NULL, 'BP 8098 Z', '12', 'PK', '14', NULL, 55, 'IE1CSN5016CGK', 20240610, 20240610140000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240610, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-07-15', 'BT20', 'IBL24TPS501', '12', 20240715, NULL, NULL, 840446712301012, 'PT INDO BERJAYA LOGISTIK', 11.9, NULL, 20240715, NULL, 'BP 8098 Z', '13', 'PK', '15', NULL, 55, 'IE1CSN5017CGK', 20240715, 20240715120000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240715, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-08-20', 'BT20', 'IBL24TPS601', '13', 20240820, NULL, NULL, 840446712301013, 'PT INDO BERJAYA LOGISTIK', 9.7, NULL, 20240820, NULL, 'BP 8098 Z', '14', 'PK', '16', NULL, 55, 'IE1CSN5018CGK', 20240820, 20240820110000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240820, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-09-25', 'BT20', 'IBL24TPS701', '14', 20240925, NULL, NULL, 840446712301014, 'PT INDO BERJAYA LOGISTIK', 14.3, NULL, 20240925, NULL, 'BP 8098 Z', '15', 'PK', '17', NULL, 55, 'IE1CSN5019CGK', 20240925, 20240925120000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20240925, NULL, NULL, NULL, NULL),
(8, 'IBL1', 'QG', '0', NULL, '2024-10-10', 'BT20', 'IBL24TPS801', '15', 20241010, NULL, NULL, 840446712301015, 'PT INDO BERJAYA LOGISTIK', 10.8, NULL, 20241010, NULL, 'BP 8098 Z', '16', 'PK', '18', NULL, 55, 'IE1CSN5020CGK', 20241010, 20241010130000, 9, 'BP 8184 DM', 'IDBTH', NULL, 'IDCGK', NULL, 20400, NULL, 20241010, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','SPV','outlet') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'outlet',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(23, 'Supervisor', 'spv@example.com', NULL, '$2y$12$OhluoVyooMjNpQxFAMwPq.NkvNMa1r8f8j2wgKIAkhghDaAWt5YKa', 'SPV', NULL, '2025-10-03 17:25:20', '2025-10-03 17:25:20'),
(24, 'Outlet 1', 'outlet1@example.com', NULL, '$2y$12$7AwtorFWry2rPIha1YG1rOJrWnTAUUv1AMGq1YUihqAowPDEIye3a', 'outlet', NULL, '2025-10-03 17:25:21', '2025-10-03 17:25:21'),
(25, 'Outlet 2', 'outlet2@example.com', NULL, '$2y$12$CGzi7Tsx0QBtoTQNTgwzfufi5seLzI5plcvTMDSb2o0yYzGEBlBBK', 'outlet', NULL, '2025-10-03 17:25:21', '2025-10-03 17:25:21'),
(26, 'Outlet 3', 'outlet3@example.com', NULL, '$2y$12$5ohxGHCvziy.5WzJUUWrJOdmOOCeNK7r6AZ44hol.kx1lA3h7UHo6', 'outlet', NULL, '2025-10-03 17:25:21', '2025-10-03 17:25:21'),
(27, 'Outlet 4', 'outlet4@example.com', NULL, '$2y$12$5X4XzM3hQdeF4aMU9LqguuXSRQXylMMV1rVIHIa14IHrAjlk3VPGa', 'outlet', NULL, '2025-10-03 17:25:22', '2025-10-03 17:25:22'),
(28, 'Outlet 5', 'outlet5@example.com', NULL, '$2y$12$QkGpb85nWQsHdG.fDGFoFeWJDy9uvQRugkNlYr0FPCZiZ8.XCtXqi', 'outlet', NULL, '2025-10-03 17:25:22', '2025-10-03 17:25:22'),
(30, 'Admin User', 'admin@gmail.com', NULL, '$2y$12$qhM8lEzduGeo03Y7bxAwTur8R.3gSFqPtxo8w38q02YOpdun32052', 'outlet', NULL, '2025-12-14 04:02:04', '2025-12-14 04:02:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bahans`
--
ALTER TABLE `bahans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marinasi`
--
ALTER TABLE `marinasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marinasis`
--
ALTER TABLE `marinasis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`NO_DOK_INOUT`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bahans`
--
ALTER TABLE `bahans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marinasi`
--
ALTER TABLE `marinasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `marinasis`
--
ALTER TABLE `marinasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
