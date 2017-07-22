-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2017 at 09:02 AM
-- Server version: 5.6.33
-- PHP Version: 5.6.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jisaidie`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(10) UNSIGNED NOT NULL,
  `apikeyable_id` int(10) UNSIGNED DEFAULT NULL,
  `apikeyable_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_ip_address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `apikeyable_id`, `apikeyable_type`, `key`, `last_ip_address`, `last_used_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, NULL, 'dcae6a94f50c04358768fec139d55420ea17c3db', '127.0.0.1', '2017-07-22 15:58:03', '2017-07-22 08:22:53', '2017-07-22 12:58:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_number` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `net_salary` double DEFAULT '0',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_checkoff` tinyint(4) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL,
  `activation_code` text COLLATE utf8mb4_unicode_ci,
  `organization_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `middle_name`, `surname`, `mobile_number`, `employee_number`, `id_number`, `net_salary`, `email`, `is_checkoff`, `status`, `activation_code`, `organization_id`, `created_at`, `updated_at`) VALUES
(1, 'John', 'Mwenda', 'Rimberia', '254723383856', NULL, '25025368', 0, '', 0, 1, '', NULL, '2017-07-22 11:27:25', '2017-07-22 12:58:03');

-- --------------------------------------------------------

--
-- Table structure for table `customer_devices`
--

CREATE TABLE `customer_devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_devices`
--

INSERT INTO `customer_devices` (`id`, `device_id`, `customer_id_number`, `created_at`, `updated_at`) VALUES
(4, '555557', '25025368', '2017-07-22 11:34:51', '2017-07-22 11:34:51');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_01_01_193651_create_roles_permissions_tables', 2),
(8, '2017_07_21_221608_create_settings_table', 3),
(9, '2016_09_13_042808_create_api_keys_table', 4),
(10, '2017_07_22_114918_create_tests_table', 5),
(11, '2017_07_22_115949_create_services_table', 6),
(12, '2017_07_22_122535_create_service_commands_table', 7),
(13, '2017_07_22_124452_create_organizations_table', 8),
(14, '2017_07_22_125350_create_customers_table', 9),
(15, '2017_07_22_130000_create_customer_devices_table', 9),
(16, '2017_07_22_140512_create_transactions_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `product`, `description`, `created_at`, `updated_at`) VALUES
(1, 'CreateCustomer', 'Customer', 'Create a new customer', '2017-07-22 09:06:31', '2017-07-22 09:06:31'),
(2, 'UpdateCustomerProfile', 'Customer', 'Updating details of an existing customer', '2017-07-22 09:07:02', '2017-07-22 09:07:02'),
(3, 'ApplyLoan', 'Loan', 'Create New Loan application', '2017-07-22 10:52:39', '2017-07-22 10:52:39'),
(4, 'UpdateCustomerActivationCode', 'Customer', 'Update or add a new customer activation code', '2017-07-22 12:00:24', '2017-07-22 12:00:24'),
(5, 'ActivateCustomer', 'Customer', 'Activate a customer', '2017-07-22 12:42:53', '2017-07-22 12:42:53');

-- --------------------------------------------------------

--
-- Table structure for table `service_commands`
--

CREATE TABLE `service_commands` (
  `id` int(10) UNSIGNED NOT NULL,
  `processing_function` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `level` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_commands`
--

INSERT INTO `service_commands` (`id`, `processing_function`, `service_id`, `level`, `description`, `created_at`, `updated_at`) VALUES
(1, 'create_customer_profile', 1, 0, 'Create a new customer profile', '2017-07-22 09:31:02', '2017-07-22 09:31:02'),
(2, 'send_notification', 1, 1, 'Notify parties of this new profile created', '2017-07-22 09:32:27', '2017-07-22 09:32:27'),
(3, 'update_customer_profile', 2, 0, 'Update a customers profile', '2017-07-22 09:32:57', '2017-07-22 09:32:57'),
(4, 'update_activation_code', 4, 0, 'Update or add new customer activation code', '2017-07-22 12:01:17', '2017-07-22 12:02:01'),
(5, 'send_notification', 4, 1, 'send notification', '2017-07-22 12:01:47', '2017-07-22 12:01:47'),
(6, 'activate_customer', 5, 0, 'Activate customer profile', '2017-07-22 12:43:31', '2017-07-22 12:43:31');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `setting_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_name`, `setting_value`, `setting_description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Jisaidie', 'The site name', '2017-07-21 19:17:50', '2017-07-21 19:19:10'),
(2, 'prsp_sender_id', 'digi_universe', 'The signature used to send out messages', '2017-07-21 19:19:39', '2017-07-21 19:20:33'),
(3, 'prsp_username', 'Jisaidie', 'The user name for using prsp services', '2017-07-21 19:21:07', '2017-07-21 19:21:07'),
(4, 'prsp_api_key', 'kjsafgjhdfgj8723rjsfglt73i6r2893', 'The PRSP key', '2017-07-21 19:21:35', '2017-07-21 19:21:35'),
(5, 'nco_processing_fee', '10', 'The processing fee for non-check-off loans', '2017-07-21 19:22:12', '2017-07-21 19:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `service_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(8,0) NOT NULL DEFAULT '0',
  `charges` decimal(8,0) NOT NULL DEFAULT '0',
  `profile` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `service_id`, `request`, `response`, `status`, `amount`, `charges`, `profile`, `created_at`, `updated_at`) VALUES
(1, '1', '{"action":"CreateCustomer","product":"Customer","request":{"first_name":"John","last_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:10:28', '2017-07-22 11:10:28'),
(2, '1', '{"action":"CreateCustomer","request":{"first_name":"John","last_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:12:56', '2017-07-22 11:12:56'),
(3, '1', '{"action":"CreateCustomer","request":{"first_name":"John","last_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:13:26', '2017-07-22 11:13:26'),
(4, '1', '{"action":"CreateCustomer","request":{"first_name":"John","last_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:14:24', '2017-07-22 11:14:24'),
(5, '1', '{"action":"CreateCustomer","request":{"first_name":"John","last_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:14:53', '2017-07-22 11:14:53'),
(6, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:15:43', '2017-07-22 11:15:43'),
(7, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:17:36', '2017-07-22 11:17:36'),
(8, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:27:25', '2017-07-22 11:27:25'),
(9, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:28:46', '2017-07-22 11:28:46'),
(10, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:29:20', '2017-07-22 11:29:20'),
(11, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:29:32', '2017-07-22 11:29:32'),
(12, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:31:10', '2017-07-22 11:31:10'),
(13, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:31:31', '2017-07-22 11:31:31'),
(14, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:32:06', '2017-07-22 11:32:06'),
(15, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:33:05', '2017-07-22 11:33:05'),
(16, '2', '{"action":"UpdateCustomerProfile","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557,"id_number":"25025368"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:34:51', '2017-07-22 11:34:51'),
(17, '2', '{"action":"UpdateCustomerProfile","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","device_id":555557,"id_number":"25025368"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 11:35:14', '2017-07-22 11:35:14'),
(18, '4', '{"action":"UpdateCustomerActivationCode","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:10:54', '2017-07-22 12:10:54'),
(19, '4', '{"action":"UpdateCustomerActivationCode","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:11:47', '2017-07-22 12:11:47'),
(20, '4', '{"action":"UpdateCustomerActivationCode","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:12:04', '2017-07-22 12:12:04'),
(21, '4', '{"action":"UpdateCustomerActivationCode","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:13:41', '2017-07-22 12:13:41'),
(22, '4', '{"action":"UpdateCustomerActivationCode","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:14:44', '2017-07-22 12:14:44'),
(23, '4', '{"action":"UpdateCustomerActivationCode","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:18:51', '2017-07-22 12:18:51'),
(24, '4', '{"action":"UpdateCustomerActivationCode","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","activation_code":"555556y7","id_number":"25025368"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:23:13', '2017-07-22 12:23:13'),
(25, '4', '{"action":"UpdateCustomerActivationCode","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","activation_code":"555556y7","id_number":"25025368"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:24:10', '2017-07-22 12:24:10'),
(26, '4', '{"action":"UpdateCustomerActivationCode","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","activation_code":"555556y7","id_number":"25025368"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:24:42', '2017-07-22 12:24:42'),
(27, '4', '{"action":"UpdateCustomerActivationCode","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","activation_code":"555556y7","id_number":"25025368"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:25:40', '2017-07-22 12:25:40'),
(28, '5', '{"action":"ActivateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","activation_code":"555556y7","id_number":"25025368"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:58:03', '2017-07-22 12:58:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Njui Josphat', 'admin@admin.com', '$2y$10$Jo.b95MSSPIYrQ6bbd3K4eyaYd1H5g1zI9tY.K9ooxzkDO3jRu1AW', 'PqAiYqKBTj6I4egTRrrLUpOIgOTvuvjIuOMIWkrh8gpuRvZLJFcokJlWpNTF', '2017-07-21 18:14:42', '2017-07-21 18:14:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_keys_apikeyable_id_apikeyable_type_index` (`apikeyable_id`,`apikeyable_type`),
  ADD KEY `api_keys_key_index` (`key`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_organization_id_foreign` (`organization_id`);

--
-- Indexes for table `customer_devices`
--
ALTER TABLE `customer_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`role_id`,`user_id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_commands`
--
ALTER TABLE `service_commands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_commands_service_id_foreign` (`service_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customer_devices`
--
ALTER TABLE `customer_devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `service_commands`
--
ALTER TABLE `service_commands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_commands`
--
ALTER TABLE `service_commands`
  ADD CONSTRAINT `service_commands_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
