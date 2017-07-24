-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 24, 2017 at 12:02 AM
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
(1, NULL, NULL, 'dcae6a94f50c04358768fec139d55420ea17c3db', '127.0.0.1', '2017-07-23 22:21:41', '2017-07-22 08:22:53', '2017-07-23 19:21:41', NULL);

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
  `net_salary` decimal(10,0) DEFAULT '0',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_checkoff` tinyint(4) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL,
  `activation_code` text COLLATE utf8mb4_unicode_ci,
  `organization_id` int(10) UNSIGNED DEFAULT NULL,
  `withholding_balance` decimal(10,0) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `middle_name`, `surname`, `mobile_number`, `employee_number`, `id_number`, `net_salary`, `email`, `is_checkoff`, `status`, `activation_code`, `organization_id`, `withholding_balance`, `created_at`, `updated_at`) VALUES
(1, 'John', 'Mwenda', 'Rimberia', '254723383856', NULL, '25025368', '0', '', 0, 1, '', NULL, '4470', '2017-07-22 11:27:25', '2017-07-23 16:16:13'),
(2, 'Jane', 'Doe', 'Rimberia', '254723383456', NULL, '25025369', '0', '', 0, 1, '', NULL, '800', '2017-07-23 11:40:25', '2017-07-23 15:54:41');

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
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount_requested` decimal(8,2) NOT NULL,
  `amount_processed` decimal(8,0) NOT NULL DEFAULT '0',
  `daily_interest` double NOT NULL,
  `fees` double NOT NULL,
  `total` double NOT NULL,
  `transaction_ref` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid` double NOT NULL,
  `invoiced` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `date_disbursed` timestamp NULL DEFAULT NULL,
  `deleted` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `customer_id`, `amount_requested`, `amount_processed`, `daily_interest`, `fees`, `total`, `transaction_ref`, `paid`, `invoiced`, `status`, `date_disbursed`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 1, '2000.00', '0', 0, 0, 2000, '', 2000, '0', 205, '2017-07-22 21:31:19', 0, '2017-07-22 18:31:19', '2017-07-23 10:54:31'),
(2, 1, '2000.00', '2000', 0, 10, 2010, '', 2010, '0', 205, '2017-07-23 16:15:11', 0, '2017-07-22 18:33:56', '2017-07-23 16:16:13'),
(3, 1, '2000.00', '2000', 0, 10, 2010, '', 2010, '0', 205, '2017-07-23 16:07:05', 0, '2017-07-22 18:47:42', '2017-07-23 16:08:04'),
(4, 1, '2000.00', '2000', 0, 10, 2010, '', 2010, '0', 205, '2017-07-23 15:53:37', 0, '2017-07-22 19:04:21', '2017-07-23 16:08:04'),
(5, 2, '500.00', '500', 0, 10, 510, '', 510, '0', 205, '2017-07-23 14:22:44', 0, '2017-07-23 13:29:17', '2017-07-23 15:40:50'),
(19, 1, '1500.00', '0', 0, 0, 0, '', 0, '0', 200, NULL, 0, '2017-07-23 19:21:42', '2017-07-23 19:21:42');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `subject`, `message`, `recipient`, `type`, `status`, `attempts`, `service_id`, `created_at`, `updated_at`) VALUES
(1, 'Loan application received', 'Hi [first_name], Your loan application has been received and is being processed.', 'test@example.com', 'email', 'pending', 0, 6, '2017-07-23 19:01:05', '2017-07-23 19:01:05'),
(2, 'Loan application received', 'Hi, A new loan application has been received and awaits your action. <br>\r\n<h3>Details</h3>\r\n<strong>Name:</strong>[customer_name]<br>\r\n<strong>Mobile Number:</strong>[mobile_number]<br>\r\n<strong>Amount:</strong>[amount]<br>', 'test@example.com', 'email', 'pending', 0, 6, '2017-07-23 19:01:05', '2017-07-23 19:01:05'),
(3, 'Loan application received', 'Hi [first_name], Your loan application has been received and is being processed.', '254723383856', 'sms', 'pending', 0, 6, '2017-07-23 19:12:52', '2017-07-23 19:12:52'),
(4, 'Loan application received', 'Hi, A new loan application has been received and awaits your action. <br>\r\n<h3>Details</h3>\r\n<strong>Name:</strong>1500<br>\r\n<strong>Mobile Number:</strong>254723383856<br>\r\n<strong>Amount:</strong>1500<br>', 'test@example.com', 'email', 'pending', 0, 6, '2017-07-23 19:12:52', '2017-07-23 19:12:52'),
(5, 'Loan application received', 'Hi [first_name], Your loan application has been received and is being processed.', '254723383856', 'sms', 'pending', 0, 6, '2017-07-23 19:15:38', '2017-07-23 19:15:38'),
(6, 'Loan application received', 'Hi, A new loan application has been received and awaits your action. <br>\r\n<h3>Details</h3>\r\n<strong>Name:</strong>John<br>\r\n<strong>Mobile Number:</strong>254723383856<br>\r\n<strong>Amount:</strong>1500<br>', 'test@example.com', 'email', 'pending', 0, 6, '2017-07-23 19:15:38', '2017-07-23 19:15:38'),
(7, 'Loan application received', 'Hi [first_name], Your loan application has been received and is being processed.', '254723383856', 'sms', 'pending', 0, 6, '2017-07-23 19:18:10', '2017-07-23 19:18:10'),
(8, 'Loan application received', 'Hi, A new loan application has been received and awaits your action. <br>\r\n<h3>Details</h3>\r\n<strong>Name:</strong>John Mwenda Rimberia<br>\r\n<strong>Mobile Number:</strong>254723383856<br>\r\n<strong>Amount:</strong>1500<br>', 'test@example.com', 'email', 'pending', 0, 6, '2017-07-23 19:18:10', '2017-07-23 19:18:10'),
(9, 'Loan application received', 'Hi John, Your loan application has been received and is being processed.', '254723383856', 'sms', 'pending', 0, 6, '2017-07-23 19:21:42', '2017-07-23 19:21:42'),
(10, 'Loan application received', 'Hi, A new loan application has been received and awaits your action. <br>\r\n<h3>Details</h3>\r\n<strong>Name:</strong>John Mwenda Rimberia<br>\r\n<strong>Mobile Number:</strong>254723383856<br>\r\n<strong>Amount:</strong>1500<br>', 'test@example.com', 'email', 'pending', 0, 6, '2017-07-23 19:21:42', '2017-07-23 19:21:42');

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
(16, '2017_07_22_140512_create_transactions_table', 10),
(17, '2017_07_22_170147_create_screens_table', 11),
(18, '2017_07_22_195736_create_loans_table', 12),
(19, '2017_07_23_180643_create_payments_table', 13),
(20, '2017_07_23_200357_create_response_templates_table', 14),
(21, '2017_07_23_210604_create_messages_table', 15);

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `customer_id`, `amount`, `currency`, `reference`, `gateway`, `loan_id`, `created_at`, `updated_at`) VALUES
(1, NULL, '200.00', 'KES', 'RTY56KYE', 'mpesa', NULL, '2017-07-23 15:49:43', '2017-07-23 15:49:43'),
(2, NULL, '200.00', 'KES', 'RTY56KYE', 'mpesa', NULL, '2017-07-23 15:54:42', '2017-07-23 15:54:42'),
(3, NULL, '200.00', 'KES', 'RTY56KYE', 'mpesa', 4, '2017-07-23 15:58:44', '2017-07-23 15:58:44'),
(4, 1, '200.00', 'KES', 'RTY56KYE', 'mpesa', 4, '2017-07-23 16:01:56', '2017-07-23 16:01:56'),
(5, 1, '2010.00', 'KES', 'RTY56MYE', 'mpesa', 3, '2017-07-23 16:08:04', '2017-07-23 16:08:04'),
(6, 1, '1610.00', 'KES', 'RTY56MYE', 'mpesa', 4, '2017-07-23 16:08:04', '2017-07-23 16:08:04'),
(7, 1, '4200.00', 'KES', 'RTY56MYE', 'mpesa', NULL, '2017-07-23 16:08:04', '2017-07-23 16:08:04'),
(8, 1, '2010.00', 'KES', 'RTY56GYE', 'mpesa', 2, '2017-07-23 16:16:13', '2017-07-23 16:16:13');

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
-- Table structure for table `response_templates`
--

CREATE TABLE `response_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` int(11) NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `response_templates`
--

INSERT INTO `response_templates` (`id`, `name`, `subject`, `message`, `type`, `service_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Loan application received', 'Loan application received', 'Hi [first_name], Your loan application has been received and is being processed.', 'sms', 6, 'Text sent to customer when loan application is received', 1, '2017-07-23 17:28:25', '2017-07-23 19:04:25'),
(2, 'Loan application received - [mobile_number]', 'Loan application received', 'Hi, A new loan application has been received and awaits your action. <br>\r\n<h3>Details</h3>\r\n<strong>Name:</strong>[customer_name]<br>\r\n<strong>Mobile Number:</strong>[mobile_number]<br>\r\n<strong>Amount:</strong>[amount]<br>', 'email', 6, 'Alert admin of a new loan application', 1, '2017-07-23 17:31:48', '2017-07-23 19:21:10');

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
-- Table structure for table `screens`
--

CREATE TABLE `screens` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `screens`
--

INSERT INTO `screens` (`id`, `title`, `message`, `icon`, `order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Welcome', 'Welcome to jijenge.', 'images/OEueXQXY1feIjAPD5wJPdD6ryAPTavjfUSQpzi71.png', 1, 1, '2017-07-22 14:40:38', '2017-07-22 16:05:50');

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
(4, 'UpdateCustomerActivationCode', 'Customer', 'Update or add a new customer activation code', '2017-07-22 12:00:24', '2017-07-22 12:00:24'),
(5, 'ActivateCustomer', 'Customer', 'Activate a customer', '2017-07-22 12:42:53', '2017-07-22 12:42:53'),
(6, 'ApplyLoan', 'Loan', 'Apply for a new loan', '2017-07-22 13:46:43', '2017-07-22 13:46:43'),
(7, 'FetchScreens', 'Screen', 'Mobile app screens', '2017-07-22 13:54:32', '2017-07-22 13:54:32'),
(8, 'ApproveLoan', 'Loan', 'approve a loan', '2017-07-22 19:41:47', '2017-07-22 19:41:47'),
(9, 'DisburseLoan', 'Loan', 'Sends funds to client', '2017-07-22 19:43:43', '2017-07-22 19:43:43'),
(10, 'RejectLoanApplication', 'Loan', 'Reject Loan application', '2017-07-22 19:44:24', '2017-07-22 19:44:24'),
(11, 'RepayLoan', 'Loan', 'Repay a loan', '2017-07-23 10:02:13', '2017-07-23 10:02:13');

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
(6, 'activate_customer', 5, 0, 'Activate customer profile', '2017-07-22 12:43:31', '2017-07-22 12:43:31'),
(7, 'fetch_screens', 7, 0, 'fetch screens', '2017-07-22 13:56:51', '2017-07-22 17:52:56'),
(8, 'create_loan', 6, 0, 'Create a new loan application', '2017-07-22 16:11:16', '2017-07-22 17:53:06'),
(9, 'send_notification', 6, 1, 'Notify parties of a new loan application', '2017-07-22 16:11:48', '2017-07-22 17:53:17'),
(10, 'approve_loan_application', 8, 0, 'Approve a loan', '2017-07-22 19:42:38', '2017-07-22 19:42:38'),
(11, 'send_notification', 8, 1, 'Send notification to parties', '2017-07-22 19:43:00', '2017-07-22 19:43:00'),
(12, 'reject_loan_application', 10, 0, 'Reject a loan', '2017-07-22 19:46:34', '2017-07-22 19:46:34'),
(13, 'send_notification', 10, 1, 'Send notification about this action', '2017-07-22 19:47:13', '2017-07-22 19:47:13'),
(15, 'send_funds', 9, 0, 'Send funds to customer', '2017-07-22 20:10:06', '2017-07-23 11:12:01'),
(16, 'send_notification', 9, 2, 'Send notification to parties', '2017-07-22 20:10:35', '2017-07-23 11:11:51'),
(17, 'offset_loan', 11, 0, 'Offset a loan with a given amount', '2017-07-23 10:03:16', '2017-07-23 10:03:16'),
(18, 'send_notification', 11, 1, 'send notification to customer about loan repayment', '2017-07-23 10:03:49', '2017-07-23 10:03:49');

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
(5, 'nco_processing_fee', '10', 'The processing fee for non-check-off loans', '2017-07-21 19:22:12', '2017-07-21 19:22:12'),
(6, 'minimum_loan', '500', 'Minimum loan applicable', '2017-07-22 17:30:27', '2017-07-22 17:30:27'),
(7, 'maximum_loan', '50000', 'Maximum loan applicable', '2017-07-22 17:30:57', '2017-07-22 17:30:57'),
(8, 'co_processing_fee', '8', 'loan fee for check off loans', '2017-07-22 19:59:51', '2017-07-22 19:59:51'),
(9, 'new_loan_application_recipients', 'test@example.com', 'Who should receive new loan applications alert on email', '2017-07-23 17:35:11', '2017-07-23 17:35:11');

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
(28, '5', '{"action":"ActivateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383856","activation_code":"555556y7","id_number":"25025368"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 12:58:03', '2017-07-22 12:58:03'),
(29, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 14:42:49', '2017-07-22 14:42:49'),
(30, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 14:56:04', '2017-07-22 14:56:04'),
(31, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 14:56:25', '2017-07-22 14:56:25'),
(32, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 14:56:51', '2017-07-22 14:56:51'),
(33, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 14:57:08', '2017-07-22 14:57:08'),
(34, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 14:57:54', '2017-07-22 14:57:54'),
(35, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:32:27', '2017-07-22 17:32:27'),
(36, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:33:38', '2017-07-22 17:33:38'),
(37, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:34:35', '2017-07-22 17:34:35'),
(38, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:42:54', '2017-07-22 17:42:54'),
(39, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:43:21', '2017-07-22 17:43:21'),
(40, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:43:44', '2017-07-22 17:43:44'),
(41, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:50:11', '2017-07-22 17:50:11'),
(42, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:50:56', '2017-07-22 17:50:56'),
(43, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:52:06', '2017-07-22 17:52:06'),
(44, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:52:26', '2017-07-22 17:52:26'),
(45, '7', '{"action":"FetchScreens","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:53:22', '2017-07-22 17:53:22'),
(46, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:53:44', '2017-07-22 17:53:44'),
(47, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:54:08', '2017-07-22 17:54:08'),
(48, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:55:01', '2017-07-22 17:55:01'),
(49, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:55:49', '2017-07-22 17:55:49'),
(50, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 17:56:59', '2017-07-22 17:56:59'),
(51, '6', '{"action":"ApplyLoan","request":null}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:13:05', '2017-07-22 18:13:05'),
(52, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:13:39', '2017-07-22 18:13:39'),
(53, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:14:00', '2017-07-22 18:14:00'),
(54, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:14:26', '2017-07-22 18:14:26'),
(55, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:15:14', '2017-07-22 18:15:14'),
(56, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:16:00', '2017-07-22 18:16:00'),
(57, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:18:28', '2017-07-22 18:18:28'),
(58, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:19:23', '2017-07-22 18:19:23'),
(59, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:20:40', '2017-07-22 18:20:40'),
(60, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:21:34', '2017-07-22 18:21:34'),
(61, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:22:17', '2017-07-22 18:22:17'),
(62, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:22:40', '2017-07-22 18:22:40'),
(63, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:22:58', '2017-07-22 18:22:58'),
(64, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:23:14', '2017-07-22 18:23:14'),
(65, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:23:38', '2017-07-22 18:23:38'),
(66, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:29:25', '2017-07-22 18:29:25'),
(67, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:30:06', '2017-07-22 18:30:06'),
(68, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:30:51', '2017-07-22 18:30:51'),
(69, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:31:19', '2017-07-22 18:31:19'),
(70, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:33:56', '2017-07-22 18:33:56'),
(71, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:47:42', '2017-07-22 18:47:42'),
(72, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:50:14', '2017-07-22 18:50:14'),
(73, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:51:31', '2017-07-22 18:51:31'),
(74, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:52:06', '2017-07-22 18:52:06'),
(75, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:56:41', '2017-07-22 18:56:41'),
(76, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:58:18', '2017-07-22 18:58:18'),
(77, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:58:55', '2017-07-22 18:58:55'),
(78, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 18:59:51', '2017-07-22 18:59:51'),
(79, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:00:03', '2017-07-22 19:00:03'),
(80, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:00:45', '2017-07-22 19:00:45'),
(81, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:01:06', '2017-07-22 19:01:06'),
(82, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:01:24', '2017-07-22 19:01:24'),
(83, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:01:38', '2017-07-22 19:01:38'),
(84, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:02:30', '2017-07-22 19:02:30'),
(85, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:03:34', '2017-07-22 19:03:34'),
(86, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:03:51', '2017-07-22 19:03:51'),
(87, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:04:21', '2017-07-22 19:04:21'),
(88, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:04:52', '2017-07-22 19:04:52'),
(89, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:05:31', '2017-07-22 19:05:31'),
(90, '6', '{"action":"ApplyLoan","request":{"amount":"2000","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-22 19:12:13', '2017-07-22 19:12:13'),
(91, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:12:52', '2017-07-23 10:12:52'),
(92, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:37:45', '2017-07-23 10:37:45'),
(93, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:38:12', '2017-07-23 10:38:12'),
(94, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:41:32', '2017-07-23 10:41:32'),
(95, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:42:24', '2017-07-23 10:42:24'),
(96, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:42:38', '2017-07-23 10:42:38'),
(97, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:47:49', '2017-07-23 10:47:49'),
(98, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:49:56', '2017-07-23 10:49:56'),
(99, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:51:15', '2017-07-23 10:51:15'),
(100, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:51:31', '2017-07-23 10:51:31'),
(101, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:51:51', '2017-07-23 10:51:51'),
(102, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:52:31', '2017-07-23 10:52:31'),
(103, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:53:34', '2017-07-23 10:53:34'),
(104, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:53:51', '2017-07-23 10:53:51'),
(105, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:54:04', '2017-07-23 10:54:04'),
(106, '11', '{"action":"RepayLoan","request":{"amount":"1201","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:54:31', '2017-07-23 10:54:31'),
(107, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:55:18', '2017-07-23 10:55:18'),
(108, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 10:57:55', '2017-07-23 10:57:55'),
(109, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:13:53', '2017-07-23 11:13:53'),
(110, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:20:24', '2017-07-23 11:20:24'),
(111, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:23:04', '2017-07-23 11:23:04'),
(112, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:23:43', '2017-07-23 11:23:43'),
(113, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:24:34', '2017-07-23 11:24:34'),
(114, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:25:23', '2017-07-23 11:25:23'),
(115, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:25:53', '2017-07-23 11:25:53'),
(116, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:27:43', '2017-07-23 11:27:43'),
(117, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:28:51', '2017-07-23 11:28:51'),
(118, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:29:49', '2017-07-23 11:29:49'),
(119, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:30:08', '2017-07-23 11:30:08'),
(120, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:30:38', '2017-07-23 11:30:38'),
(121, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:31:00', '2017-07-23 11:31:00'),
(122, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:31:15', '2017-07-23 11:31:15'),
(123, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:31:24', '2017-07-23 11:31:24'),
(124, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:32:20', '2017-07-23 11:32:20'),
(125, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:33:15', '2017-07-23 11:33:15'),
(126, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:33:16', '2017-07-23 11:33:16'),
(127, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:33:45', '2017-07-23 11:33:45'),
(128, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:34:33', '2017-07-23 11:34:33'),
(129, '1', '{"action":"CreateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383456","device_id":555557}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:40:25', '2017-07-23 11:40:25'),
(130, '4', '{"action":"UpdateCustomerActivationCode","request":{"first_name":"Jane","middle_name":"Doe","surname":"Rimberia","email ":"test2@gmail.com","mobile_number":"254723383456","activation_code":"555556y7","id_number":"25025369"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:41:39', '2017-07-23 11:41:39'),
(131, '2', '{"action":"UpdateCustomerProfile","request":{"first_name":"Jane","middle_name":"Doe","surname":"Rimberia","email ":"test2@gmail.com","mobile_number":"254723383456","activation_code":"555556y7","id_number":"25025369"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:43:23', '2017-07-23 11:43:23'),
(132, '2', '{"action":"UpdateCustomerProfile","request":{"first_name":"Jane","middle_name":"Doe","surname":"Rimberia","email ":"test2@gmail.com","mobile_number":"254723383456","activation_code":"555556y7","id_number":"25025369"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:44:19', '2017-07-23 11:44:19'),
(133, '5', '{"action":"ActivateCustomer","request":{"first_name":"John","middle_name":"Mwenda","surname":"Rimberia","email ":"test@gmail.com","mobile_number":"254723383456","activation_code":"555556y7","id_number":"25025368"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:44:54', '2017-07-23 11:44:54'),
(134, '6', '{"action":"ApplyLoan","request":{"amount":"100","mobile_number":"254723383456"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:45:39', '2017-07-23 11:45:39'),
(135, '6', '{"action":"ApplyLoan","request":{"amount":"100","mobile_number":"254723383456"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:46:31', '2017-07-23 11:46:31'),
(136, '6', '{"action":"ApplyLoan","request":{"amount":"100","mobile_number":"254723383456"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:47:26', '2017-07-23 11:47:26'),
(137, '6', '{"action":"ApplyLoan","request":{"amount":"500","mobile_number":"254723383456"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 11:47:46', '2017-07-23 11:47:46'),
(138, '6', '{"action":"ApplyLoan","request":{"amount":"500","mobile_number":"254723383456"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:29:17', '2017-07-23 13:29:17'),
(139, '6', '{"action":"ApplyLoan","request":{"amount":"500","mobile_number":"254723383456"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:29:29', '2017-07-23 13:29:29'),
(140, '8', '{"action":"ApproveLoan","request":{"loan_id":"500"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:32:18', '2017-07-23 13:32:18'),
(141, '8', '{"action":"ApproveLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:32:48', '2017-07-23 13:32:48'),
(142, '8', '{"action":"ApproveLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:33:42', '2017-07-23 13:33:42'),
(143, '9', '{"action":"DisburseLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:34:28', '2017-07-23 13:34:28'),
(144, '9', '{"action":"DisburseLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:35:03', '2017-07-23 13:35:03'),
(145, '9', '{"action":"DisburseLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:46:48', '2017-07-23 13:46:48'),
(146, '9', '{"action":"DisburseLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:47:06', '2017-07-23 13:47:06'),
(147, '9', '{"action":"DisburseLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:47:49', '2017-07-23 13:47:49'),
(148, '9', '{"action":"DisburseLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:47:55', '2017-07-23 13:47:55'),
(149, '8', '{"action":"ApproveLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:49:04', '2017-07-23 13:49:04'),
(150, '8', '{"action":"ApproveLoan","request":{"loan_id":"5","amount_approved":500}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:49:52', '2017-07-23 13:49:52'),
(151, '8', '{"action":"ApproveLoan","request":{"loan_id":"5","amount_approved":500}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:50:49', '2017-07-23 13:50:49'),
(152, '8', '{"action":"ApproveLoan","request":{"loan_id":"5","amount_approved":500}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 13:51:12', '2017-07-23 13:51:12'),
(153, '8', '{"action":"ApproveLoan","request":{"loan_id":"5","amount_approved":500}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 14:16:33', '2017-07-23 14:16:33'),
(154, '9', '{"action":"DisburseLoan","request":{"loan_id":"5","amount_approved":500}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 14:17:05', '2017-07-23 14:17:05'),
(155, '9', '{"action":"DisburseLoan","request":{"loan_id":"5","amount_approved":500}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 14:17:10', '2017-07-23 14:17:10'),
(156, '9', '{"action":"DisburseLoan","request":{"loan_id":"5","amount_approved":500}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 14:17:15', '2017-07-23 14:17:15'),
(157, '8', '{"action":"ApproveLoan","request":{"loan_id":"5","amount_approved":500}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 14:21:27', '2017-07-23 14:21:27'),
(158, '9', '{"action":"DisburseLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 14:22:44', '2017-07-23 14:22:44'),
(159, '9', '{"action":"DisburseLoan","request":{"loan_id":"5"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 14:22:48', '2017-07-23 14:22:48'),
(160, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383456"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:37:24', '2017-07-23 15:37:24'),
(161, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383456","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:40:07', '2017-07-23 15:40:07'),
(162, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383456","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:40:50', '2017-07-23 15:40:50'),
(163, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383456","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:43:06', '2017-07-23 15:43:06'),
(164, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383456","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:49:13', '2017-07-23 15:49:13'),
(165, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383456","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:49:43', '2017-07-23 15:49:43'),
(166, '8', '{"action":"ApproveLoan","request":{"loan_id":"4","amount_approved":2000}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:53:03', '2017-07-23 15:53:03'),
(167, '8', '{"action":"ApproveLoan","request":{"loan_id":"4","amount_approved":2000}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:53:11', '2017-07-23 15:53:11'),
(168, '9', '{"action":"DisburseLoan","request":{"loan_id":"4"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:53:37', '2017-07-23 15:53:37'),
(169, '9', '{"action":"DisburseLoan","request":{"loan_id":"4"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:53:40', '2017-07-23 15:53:40'),
(170, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383456","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:54:41', '2017-07-23 15:54:41'),
(171, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 15:58:44', '2017-07-23 15:58:44'),
(172, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:01:56', '2017-07-23 16:01:56'),
(173, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:05:48', '2017-07-23 16:05:48'),
(174, '8', '{"action":"ApproveLoan","request":{"loan_id":"3","amount_approved":2000}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:06:47', '2017-07-23 16:06:47'),
(175, '9', '{"action":"DisburseLoan","request":{"loan_id":"3"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:07:04', '2017-07-23 16:07:04'),
(176, '11', '{"action":"RepayLoan","request":{"amount":"200","mobile_number":"254723383856","reference":"RTY56KYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:07:35', '2017-07-23 16:07:35'),
(177, '11', '{"action":"RepayLoan","request":{"amount":"4200","mobile_number":"254723383856","reference":"RTY56MYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:08:04', '2017-07-23 16:08:04'),
(178, '8', '{"action":"ApproveLoan","request":{"loan_id":"2","amount_approved":2000}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:14:49', '2017-07-23 16:14:49'),
(179, '9', '{"action":"DisburseLoan","request":{"loan_id":"2"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:15:11', '2017-07-23 16:15:11'),
(180, '9', '{"action":"DisburseLoan","request":{"loan_id":"2"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:15:15', '2017-07-23 16:15:15'),
(181, '11', '{"action":"RepayLoan","request":{"amount":"2300","mobile_number":"254723383856","reference":"RTY56MYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:15:59', '2017-07-23 16:15:59'),
(182, '11', '{"action":"RepayLoan","request":{"amount":"2300","mobile_number":"254723383856","reference":"RTY56GYE","gateway":"mpesa"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 16:16:12', '2017-07-23 16:16:12'),
(183, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:18:13', '2017-07-23 18:18:13'),
(184, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:39:00', '2017-07-23 18:39:00'),
(185, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:41:34', '2017-07-23 18:41:34'),
(186, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:44:28', '2017-07-23 18:44:28'),
(187, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:50:20', '2017-07-23 18:50:20'),
(188, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:50:52', '2017-07-23 18:50:52'),
(189, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:51:16', '2017-07-23 18:51:16'),
(190, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:53:26', '2017-07-23 18:53:26'),
(191, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:55:42', '2017-07-23 18:55:42'),
(192, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 18:57:47', '2017-07-23 18:57:47'),
(193, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 19:01:05', '2017-07-23 19:01:05'),
(194, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 19:12:52', '2017-07-23 19:12:52'),
(195, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 19:15:38', '2017-07-23 19:15:38'),
(196, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 19:18:10', '2017-07-23 19:18:10'),
(197, '6', '{"action":"ApplyLoan","request":{"amount":"1500","mobile_number":"254723383856"}}', NULL, 'pending', '0', '0', NULL, '2017-07-23 19:21:41', '2017-07-23 19:21:41');

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
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `response_templates`
--
ALTER TABLE `response_templates`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `screens`
--
ALTER TABLE `screens`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `customer_devices`
--
ALTER TABLE `customer_devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `response_templates`
--
ALTER TABLE `response_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `screens`
--
ALTER TABLE `screens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `service_commands`
--
ALTER TABLE `service_commands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;
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
