-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 12, 2023 at 05:49 AM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `merchant_wosul`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `account_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` int(11) NOT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_balance` decimal(13,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `pos_default` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `slack`, `store_id`, `account_code`, `account_type`, `label`, `initial_balance`, `description`, `pos_default`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'mVE480Oi0jpiwmcJEb0hneC9j', 1, '101', 1, 'Default Sales Account', '0.00', 'Default Sales Account', 1, 1, 1, 1, '2020-11-19 01:12:43', '2021-03-11 13:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `acc_coa`
--

CREATE TABLE `acc_coa` (
  `HeadCode` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `HeadName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `PHeadName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `HeadLevel` int(11) NOT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `IsTransaction` tinyint(1) NOT NULL,
  `IsGL` tinyint(1) NOT NULL,
  `HeadType` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `IsBudget` tinyint(1) NOT NULL,
  `IsDepreciation` tinyint(1) DEFAULT '0',
  `DepreciationRate` decimal(18,2) DEFAULT '0.00',
  `CreateBy` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `CreateDate` datetime NOT NULL,
  `UpdateBy` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `acc_coa`
--

INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES
('20101', 'Accounts Payable', 'Current Liabilities', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 11:19:25', NULL, NULL),
('10202', 'Accounts Receivable', 'Current Assets', 2, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-07-07 12:43:30', NULL, NULL),
('20102', 'Accrued Expense', 'Current Liabilities', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:15:30', NULL, NULL),
('20103', 'Accrued Salaries', 'Current Liabilities', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:15:16', NULL, NULL),
('20107', 'Accumulated Depreciation', 'Current Liabilities', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:23:44', NULL, NULL),
('30202', 'Additional paid in capital', 'Issued Capital', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 09:00:41', NULL, NULL),
('1', 'Assets', 'COA', 0, 1, 0, 0, 'A', 0, 0, '0.00', '1', '2021-06-30 11:04:07', '', '2015-10-15 15:57:55'),
('40213', 'Bank commissions', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:47:46', NULL, NULL),
('1010102', 'Buildings', 'Property plant and equipment', 3, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:34:45', NULL, NULL),
('2010701', 'Buildings accumulated depreciation', 'Accumulated Depreciation', 3, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:25:41', NULL, NULL),
('4021501', 'Buildings depreciation expense', 'Depreciation', 3, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:50:05', NULL, NULL),
('10201', 'Cash & Cash Equivalent', 'Current Assets', 2, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-07-13 13:54:55', NULL, NULL),
('1020101', 'Cash at Bank', 'Cash & Cash Equivalent', 3, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-07-13 13:55:06', NULL, NULL),
('1020102', 'Cash in Hand', 'Cash & Cash Equivalent', 3, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-07-13 13:55:34', NULL, NULL),
('40303', 'Change in currency value gains or losses', 'Non Operational Expenses', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 10:02:17', NULL, NULL),
('40205', 'Commissions and incentives', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:45:26', NULL, NULL),
('1010103', 'Computer and Printer', 'Property plant and equipment', 3, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:34:26', NULL, NULL),
('2010703', 'Computer and printers accumulated depreciation', 'Accumulated Depreciation', 3, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:27:25', NULL, NULL),
('4021503', 'Computers and printers depreciation expense', 'Depreciation', 3, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:50:51', NULL, NULL),
('40101', 'Cost of goods sold', 'Direct Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:41:40', NULL, NULL),
('102', 'Current Assets', 'Assets', 1, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-07-13 13:54:28', NULL, NULL),
('201', 'Current Liabilities', 'Liabilities', 1, 1, 0, 1, 'L', 0, 0, '0.00', '1', '2021-07-07 12:36:43', NULL, NULL),
('102010101', 'Default', 'Cash at Bank', 4, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 11:19:11', NULL, NULL),
('40215', 'Depreciation', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:48:31', NULL, NULL),
('401', 'Direct Cost', 'Expense', 1, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:35:25', NULL, NULL),
('20108', 'End of Services Provision', 'Current Liabilities', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:28:19', NULL, NULL),
('1010201', 'Equipment', 'Equipment', 3, 1, 0, 1, 'A', 0, 0, '0.00', '1', '2021-07-13 13:32:10', NULL, NULL),
('2010702', 'Equipment accumulated depreciation', 'Accumulated Depreciation', 3, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:26:29', NULL, NULL),
('4021502', 'Equipment depreciation expense', 'Depreciation', 3, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:50:23', NULL, NULL),
('3', 'Equity', 'COA', 0, 1, 0, 0, 'L', 0, 0, '0.00', '1', '2015-10-15 15:57:55', '', '2015-10-15 15:57:55'),
('4', 'Expense', 'COA', 0, 1, 1, 0, 'E', 0, 0, '0.00', '1', '2021-07-13 13:46:35', '', '2015-10-15 15:57:55'),
('40209', 'Fees and subscriptions', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:46:41', NULL, NULL),
('101', 'Fixed Assets', 'Assets', 1, 1, 0, 1, 'A', 0, 0, '0.00', '1', '2021-07-07 12:33:09', NULL, NULL),
('30402', 'Foreign currency translation reserve', 'Reserve', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 09:19:21', NULL, NULL),
('20106', 'General organization for social insurance payable', 'Current Liabilities', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:20:30', NULL, NULL),
('40208', 'Government fees', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:46:20', NULL, NULL),
('40212', 'Hospitality and cleanliness', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:47:31', NULL, NULL),
('5', 'Income', 'COA', 0, 1, 0, 0, 'I', 0, 0, '0.00', '1', '2021-06-29 08:56:39', '', '2015-10-15 15:57:55'),
('10301', 'Intangible asset', 'Non Current Assets', 2, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 06:13:01', NULL, NULL),
('40304', 'Interest', 'Non Operational Expenses', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 10:02:35', NULL, NULL),
('10205', 'Inventory', 'Current Assets', 2, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:56:40', NULL, NULL),
('104', 'Investment', 'Assets', 1, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 12:30:43', NULL, NULL),
('302', 'Issued Capital', 'Equity', 1, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 08:59:46', NULL, NULL),
('1010101', 'Lands', 'Property plant and equipment', 3, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:34:35', NULL, NULL),
('2', 'Liabilities', 'COA', 0, 1, 0, 0, 'L', 0, 0, '0.00', '1', '2013-07-04 12:32:07', '', '2015-10-15 19:46:54'),
('20201', 'Long term Loans', 'Non Current Liabilities', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:29:19', NULL, NULL),
('40203', 'Marketing and advertising', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:44:43', NULL, NULL),
('40202', 'Medical insurance and treatment', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:44:11', NULL, NULL),
('103', 'Non Current Assets', 'Assets', 1, 1, 0, 1, 'A', 0, 0, '0.00', '1', '2021-07-07 12:33:55', NULL, NULL),
('202', 'Non Current Liabilities', 'Liabilities', 1, 1, 0, 1, 'L', 0, 0, '0.00', '1', '2021-07-07 12:37:06', NULL, NULL),
('403', 'Non Operational Expenses', 'Expense', 1, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:38:40', NULL, NULL),
('30301', 'Opening Balance', 'Other equity', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 09:01:40', NULL, NULL),
('402', 'Operational Cost', 'Expense', 1, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:36:52', NULL, NULL),
('303', 'Other equity', 'Equity', 1, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 09:01:15', NULL, NULL),
('40214', 'Other expenses', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:47:59', NULL, NULL),
('1020103', 'Petty Cash', 'Cash & Cash Equivalent', 3, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:42:22', NULL, NULL),
('10203', 'Prepaid expenses', 'Current Assets', 2, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:49:54', NULL, NULL),
('1020301', 'Prepaid medical insurance', 'Prepaid expenses', 3, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:50:56', NULL, NULL),
('1020302', 'Prepaid rent', 'Prepaid expenses', 3, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:51:27', NULL, NULL),
('30101', 'Profit and loss', 'Retained Earnings or losses', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 08:59:03', NULL, NULL),
('10101', 'Property plant and equipment', 'Fixed Assets', 2, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:36:28', NULL, NULL),
('20302', 'Purchases VAT', 'VAT', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 11:19:59', NULL, NULL),
('30201', 'Registered capital', 'Issued Capital', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 09:00:13', NULL, NULL),
('40204', 'Rental expenses', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:45:01', NULL, NULL),
('304', 'Reserve', 'Equity', 1, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 09:02:04', NULL, NULL),
('301', 'Retained Earnings or losses', 'Equity', 1, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 08:58:42', NULL, NULL),
('40201', 'Salaries and administrative fees', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:43:54', NULL, NULL),
('40102', 'Salaries and wages', 'Direct Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:42:13', NULL, NULL),
('501', 'Sales', 'Income', 1, 1, 1, 1, 'I', 0, 0, '0.00', '1', '2021-08-23 11:17:57', NULL, NULL),
('40103', 'Sales Commissions', 'Direct Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:42:49', NULL, NULL),
('502', 'Sales Discount', 'Income', 1, 1, 1, 1, 'I', 0, 0, '0.00', '1', '2021-08-23 11:18:01', NULL, NULL),
('503', 'Sales Return', 'Income', 1, 1, 1, 1, 'I', 0, 0, '0.00', '1', '2021-08-23 11:18:06', NULL, NULL),
('20301', 'Sales VAT', 'VAT', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 11:19:52', NULL, NULL),
('40104', 'Shipping and custom fees', 'Direct Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:43:26', NULL, NULL),
('20104', 'Short term loans', 'Current Liabilities', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:17:17', NULL, NULL),
('40207', 'Social insurance expense', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:46:00', NULL, NULL),
('10204', 'Staff advances', 'Current Assets', 2, 1, 1, 1, 'A', 0, 0, '0.00', '1', '2021-08-23 05:56:01', NULL, NULL),
('40211', 'Stationery and prints', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:47:18', NULL, NULL),
('30401', 'Statutory reserve', 'Reserve', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 09:02:45', NULL, NULL),
('40302', 'TAX', 'Non Operational Expenses', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 10:02:01', NULL, NULL),
('40216', 'Transportation expense', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:49:00', NULL, NULL),
('40206', 'Travel Expenses', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:45:43', NULL, NULL),
('20105', 'Unearned revenues', 'Current Liabilities', 2, 1, 1, 1, 'L', 0, 0, '0.00', '1', '2021-08-23 07:17:51', NULL, NULL),
('40210', 'Utilities expenses', 'Operational Cost', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 09:47:01', NULL, NULL),
('203', 'VAT', 'Liabilities', 1, 1, 0, 1, 'L', 0, 0, '0.00', '1', '2021-07-07 12:37:19', NULL, NULL),
('40301', 'Zakat', 'Non Operational Expenses', 2, 1, 1, 1, 'E', 0, 0, '0.00', '1', '2021-08-23 10:01:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `acc_transaction`
--

CREATE TABLE `acc_transaction` (
  `ID` int(11) NOT NULL,
  `VNo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Vtype` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `VDate` date DEFAULT NULL,
  `COAID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Narration` text COLLATE utf8_unicode_ci,
  `Debit` decimal(18,2) DEFAULT NULL,
  `Credit` decimal(18,2) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `receipt_image` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_slack` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_receipt_link` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `StoreID` int(11) NOT NULL,
  `IsPosted` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreateBy` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreateDate` datetime DEFAULT NULL,
  `UpdateBy` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `IsAppove` char(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appsetting`
--

CREATE TABLE `appsetting` (
  `id` int(11) NOT NULL,
  `latitude` varchar(30) DEFAULT NULL,
  `longitude` varchar(30) DEFAULT NULL,
  `acceptablerange` int(11) DEFAULT NULL,
  `googleapi_authkey` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appsetting`
--

INSERT INTO `appsetting` (`id`, `latitude`, `longitude`, `acceptablerange`, `googleapi_authkey`) VALUES
(1, '23.829312399999996', '90.42076019999999', 20, 'Authorization: Key=AAAACc-ZrPQ:APA91bH0tBWMWQOq9l3RBXdZ9O0-g8rUhISTVgRtan_59iOuzbeuSK8bUcbHL9IBJ9B8loKTbNfXgwO1KIi6ZFfXxI0IyHvw0oIO9MOxPeovbQfNlVrye9tfocgtgCtm49Zrd-NM4_VJ');

-- --------------------------------------------------------

--
-- Table structure for table `arabic_text`
--

CREATE TABLE `arabic_text` (
  `id` int(11) NOT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `arabic_text`
--

INSERT INTO `arabic_text` (`id`, `content`) VALUES
(1, 'a:10:{s:3:\"qty\";s:8:\"كمية\";s:11:\"description\";s:6:\"وصف\";s:5:\"price\";s:6:\"سعر\";s:6:\"amount\";s:10:\"لقيمة\";s:9:\"sub_total\";s:27:\"المجموع الفرعي\";s:8:\"discount\";s:6:\"خصم\";s:16:\"overall_discount\";s:21:\"الخصم العام\";s:22:\"subtotal_excluding_tax\";s:50:\"المجموع الفرعي من غير ضريبة\";s:3:\"tax\";s:14:\"الضريبة\";s:10:\"bill_total\";s:29:\"إجمالي الفاتورة\";}');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_history`
--

CREATE TABLE `attendance_history` (
  `atten_his_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `state` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance_history`
--

INSERT INTO `attendance_history` (`atten_his_id`, `uid`, `id`, `state`, `time`) VALUES
(1, 1, 0, '1', '2021-01-28 11:45:00'),
(2, 1, 0, '1', '2021-01-28 19:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `award`
--

CREATE TABLE `award` (
  `award_id` int(11) NOT NULL,
  `award_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aw_description` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `awr_gift_item` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `employee_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `awarded_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `award`
--

INSERT INTO `award` (`award_id`, `award_name`, `aw_description`, `awr_gift_item`, `date`, `employee_id`, `awarded_by`) VALUES
(1, 'Employee of the month', 'best performing employee  ', '1000', '2021-01-28', '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `bank_information`
--

CREATE TABLE `bank_information` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing_counters`
--

CREATE TABLE `billing_counters` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `billing_counter_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `counter_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `billing_counters`
--

INSERT INTO `billing_counters` (`id`, `slack`, `store_id`, `billing_counter_code`, `counter_name`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'FifxVEVnAkkozUaJttThctcmc', 1, 'DEFAULT_COUNTER', 'DEFAULT COUNTER', NULL, 1, 1, NULL, '2021-03-02 10:01:06', '2021-03-02 10:01:06');

-- --------------------------------------------------------

--
-- Table structure for table `bonat_store_counter_points_settings`
--

CREATE TABLE `bonat_store_counter_points_settings` (
  `id` int(11) NOT NULL,
  `slack` varchar(50) NOT NULL,
  `merchant_id` varchar(50) DEFAULT NULL,
  `store_id` varchar(50) DEFAULT NULL,
  `counter_id` varchar(50) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `is_verify` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bonat_user_points_settings`
--

CREATE TABLE `bonat_user_points_settings` (
  `id` int(11) NOT NULL,
  `slack` varchar(50) NOT NULL,
  `bonat_merchant_id` varchar(50) DEFAULT NULL,
  `merchant_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `is_verify` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_registers`
--

CREATE TABLE `business_registers` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `billing_counter_id` int(11) DEFAULT NULL,
  `current_register` tinyint(4) NOT NULL DEFAULT '0',
  `opening_date` datetime NOT NULL,
  `closing_date` datetime DEFAULT NULL,
  `opening_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `closing_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `manual_drawer_opens` decimal(13,2) NOT NULL DEFAULT '0.00',
  `credit_card_slips` decimal(13,2) NOT NULL DEFAULT '0.00',
  `cheques` decimal(13,2) NOT NULL DEFAULT '0.00',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_registers`
--

INSERT INTO `business_registers` (`id`, `slack`, `store_id`, `user_id`, `billing_counter_id`, `current_register`, `opening_date`, `closing_date`, `opening_amount`, `closing_amount`, `manual_drawer_opens`, `credit_card_slips`, `cheques`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'X7H1fHhs7wS9IY47SToTBrkz9', 1, 1, 1, 0, '2021-03-02 10:02:18', '2021-03-10 12:52:25', '0.00', '304092.14', '0.00', '0.00', '0.00', 1, 1, '2021-03-02 10:02:18', '2021-03-15 16:27:47'),
(4, 'S7Q98uNRL45bO4cdacby3Fynv', 7, 14, 4, 1, '2021-03-09 10:56:57', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 14, NULL, '2021-03-09 10:56:57', '2021-03-09 10:56:57');

-- --------------------------------------------------------

--
-- Table structure for table `candidate_basic_info`
--

CREATE TABLE `candidate_basic_info` (
  `can_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alter_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `present_address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parmanent_address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` text COLLATE utf8mb4_unicode_ci,
  `ssn` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_education_info`
--

CREATE TABLE `candidate_education_info` (
  `can_edu_id` int(11) NOT NULL,
  `can_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `degree_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `university_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cgp` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sequencee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_interview`
--

CREATE TABLE `candidate_interview` (
  `can_int_id` int(11) NOT NULL,
  `can_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_adv_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interview_date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interviewer_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interview_marks` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `written_total_marks` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mcq_total_marks` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_marks` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recommandation` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `selection` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_selection`
--

CREATE TABLE `candidate_selection` (
  `can_sel_id` int(11) NOT NULL,
  `can_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pos_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `selection_terms` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_shortlist`
--

CREATE TABLE `candidate_shortlist` (
  `can_short_id` int(11) NOT NULL,
  `can_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_adv_id` int(11) NOT NULL,
  `date_of_shortlist` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interview_date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_workexperience`
--

CREATE TABLE `candidate_workexperience` (
  `can_workexp_id` int(11) NOT NULL,
  `can_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `working_period` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duties` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supervisor` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequencee` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `category_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_ar` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_ar` text COLLATE utf8mb4_unicode_ci,
  `category_applied_on` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'all_stores',
  `category_applicable_stores` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zid_category_id` bigint(255) DEFAULT NULL,
  `zid_parent_category_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combos`
--

CREATE TABLE `combos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `is_discount_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Constant:AMOUNT,PERCENTAGE',
  `discount_value` double(10,2) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-Inactive,1-Active',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combo_groups`
--

CREATE TABLE `combo_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` bigint(20) NOT NULL,
  `parent` bigint(20) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combo_products`
--

CREATE TABLE `combo_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `combo_id` bigint(20) NOT NULL,
  `combo_size_id` bigint(20) NOT NULL,
  `combo_group_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `measurement_id` bigint(20) DEFAULT NULL,
  `quantity` double(10,4) NOT NULL,
  `price` double(10,4) NOT NULL,
  `price_after_discount` double(10,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combo_sizes`
--

CREATE TABLE `combo_sizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `combo_id` bigint(20) NOT NULL,
  `size_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_symbol` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `code`, `dial_code`, `currency_name`, `currency_code`, `currency_symbol`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Afghanistan', 'AF', '+93', 'Afghan afghani', 'AFN', '؋', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(2, 'Aland Islands', 'AX', '+358', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(3, 'Albania', 'AL', '+355', 'Albanian lek', 'ALL', 'L', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(4, 'Algeria', 'DZ', '+213', 'Algerian dinar', 'DZD', 'د.ج', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(5, 'AmericanSamoa', 'AS', '+1684', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(6, 'Andorra', 'AD', '+376', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(7, 'Angola', 'AO', '+244', 'Angolan kwanza', 'AOA', 'Kz', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(8, 'Anguilla', 'AI', '+1264', 'East Caribbean dolla', 'XCD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(9, 'Antarctica', 'AQ', '+672', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(10, 'Antigua and Barbuda', 'AG', '+1268', 'East Caribbean dolla', 'XCD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(11, 'Argentina', 'AR', '+54', 'Argentine peso', 'ARS', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(12, 'Armenia', 'AM', '+374', 'Armenian dram', 'AMD', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(13, 'Aruba', 'AW', '+297', 'Aruban florin', 'AWG', 'ƒ', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(14, 'Australia', 'AU', '+61', 'Australian dollar', 'AUD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(15, 'Austria', 'AT', '+43', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(16, 'Azerbaijan', 'AZ', '+994', 'Azerbaijani manat', 'AZN', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(17, 'Bahamas', 'BS', '+1242', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(18, 'Bahrain', 'BH', '+973', 'Bahraini dinar', 'BHD', '.د.ب', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(19, 'Bangladesh', 'BD', '+880', 'Bangladeshi taka', 'BDT', '৳', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(20, 'Barbados', 'BB', '+1246', 'Barbadian dollar', 'BBD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(21, 'Belarus', 'BY', '+375', 'Belarusian ruble', 'BYR', 'Br', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(22, 'Belgium', 'BE', '+32', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(23, 'Belize', 'BZ', '+501', 'Belize dollar', 'BZD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(24, 'Benin', 'BJ', '+229', 'West African CFA fra', 'XOF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(25, 'Bermuda', 'BM', '+1441', 'Bermudian dollar', 'BMD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(26, 'Bhutan', 'BT', '+975', 'Bhutanese ngultrum', 'BTN', 'Nu.', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(27, 'Bolivia, Plurination', 'BO', '+591', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(28, 'Bosnia and Herzegovi', 'BA', '+387', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(29, 'Botswana', 'BW', '+267', 'Botswana pula', 'BWP', 'P', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(30, 'Brazil', 'BR', '+55', 'Brazilian real', 'BRL', 'R$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(31, 'British Indian Ocean', 'IO', '+246', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(32, 'Brunei Darussalam', 'BN', '+673', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(33, 'Bulgaria', 'BG', '+359', 'Bulgarian lev', 'BGN', 'лв', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(34, 'Burkina Faso', 'BF', '+226', 'West African CFA fra', 'XOF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(35, 'Burundi', 'BI', '+257', 'Burundian franc', 'BIF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(36, 'Cambodia', 'KH', '+855', 'Cambodian riel', 'KHR', '៛', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(37, 'Cameroon', 'CM', '+237', 'Central African CFA ', 'XAF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(38, 'Canada', 'CA', '+1', 'Canadian dollar', 'CAD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(39, 'Cape Verde', 'CV', '+238', 'Cape Verdean escudo', 'CVE', 'Esc or $', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(40, 'Cayman Islands', 'KY', '+ 345', 'Cayman Islands dolla', 'KYD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(41, 'Central African Repu', 'CF', '+236', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(42, 'Chad', 'TD', '+235', 'Central African CFA ', 'XAF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(43, 'Chile', 'CL', '+56', 'Chilean peso', 'CLP', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(44, 'China', 'CN', '+86', 'Chinese yuan', 'CNY', '¥ or 元', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(45, 'Christmas Island', 'CX', '+61', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(46, 'Cocos (Keeling] Isla', 'CC', '+61', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(47, 'Colombia', 'CO', '+57', 'Colombian peso', 'COP', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(48, 'Comoros', 'KM', '+269', 'Comorian franc', 'KMF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(49, 'Congo', 'CG', '+242', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(50, 'Congo, The Democrati', 'CD', '+243', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(51, 'Cook Islands', 'CK', '+682', 'New Zealand dollar', 'NZD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(52, 'Costa Rica', 'CR', '+506', 'Costa Rican colón', 'CRC', '₡', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(53, 'Cote Ivoire', 'CI', '+225', 'West African CFA fra', 'XOF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(54, 'Croatia', 'HR', '+385', 'Croatian kuna', 'HRK', 'kn', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(55, 'Cuba', 'CU', '+53', 'Cuban convertible pe', 'CUC', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(56, 'Cyprus', 'CY', '+357', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(57, 'Czech Republic', 'CZ', '+420', 'Czech koruna', 'CZK', 'Kč', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(58, 'Denmark', 'DK', '+45', 'Danish krone', 'DKK', 'kr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(59, 'Djibouti', 'DJ', '+253', 'Djiboutian franc', 'DJF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(60, 'Dominica', 'DM', '+1767', 'East Caribbean dolla', 'XCD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(61, 'Dominican Republic', 'DO', '+1849', 'Dominican peso', 'DOP', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(62, 'Ecuador', 'EC', '+593', 'United States dollar', 'USD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(63, 'Egypt', 'EG', '+20', 'Egyptian pound', 'EGP', '£ or ج.م', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(64, 'El Salvador', 'SV', '+503', 'United States dollar', 'USD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(65, 'Equatorial Guinea', 'GQ', '+240', 'Central African CFA ', 'XAF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(66, 'Eritrea', 'ER', '+291', 'Eritrean nakfa', 'ERN', 'Nfk', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(67, 'Estonia', 'EE', '+372', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(68, 'Ethiopia', 'ET', '+251', 'Ethiopian birr', 'ETB', 'Br', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(69, 'Falkland Islands (Ma', 'FK', '+500', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(70, 'Faroe Islands', 'FO', '+298', 'Danish krone', 'DKK', 'kr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(71, 'Fiji', 'FJ', '+679', 'Fijian dollar', 'FJD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(72, 'Finland', 'FI', '+358', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(73, 'France', 'FR', '+33', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(74, 'French Guiana', 'GF', '+594', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(75, 'French Polynesia', 'PF', '+689', 'CFP franc', 'XPF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(76, 'Gabon', 'GA', '+241', 'Central African CFA ', 'XAF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(77, 'Gambia', 'GM', '+220', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(78, 'Georgia', 'GE', '+995', 'Georgian lari', 'GEL', 'ლ', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(79, 'Germany', 'DE', '+49', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(80, 'Ghana', 'GH', '+233', 'Ghana cedi', 'GHS', '₵', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(81, 'Gibraltar', 'GI', '+350', 'Gibraltar pound', 'GIP', '£', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(82, 'Greece', 'GR', '+30', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(83, 'Greenland', 'GL', '+299', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(84, 'Grenada', 'GD', '+1473', 'East Caribbean dolla', 'XCD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(85, 'Guadeloupe', 'GP', '+590', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(86, 'Guam', 'GU', '+1671', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(87, 'Guatemala', 'GT', '+502', 'Guatemalan quetzal', 'GTQ', 'Q', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(88, 'Guernsey', 'GG', '+44', 'British pound', 'GBP', '£', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(89, 'Guinea', 'GN', '+224', 'Guinean franc', 'GNF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(90, 'Guinea-Bissau', 'GW', '+245', 'West African CFA fra', 'XOF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(91, 'Guyana', 'GY', '+595', 'Guyanese dollar', 'GYD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(92, 'Haiti', 'HT', '+509', 'Haitian gourde', 'HTG', 'G', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(93, 'Holy See (Vatican Ci', 'VA', '+379', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(94, 'Honduras', 'HN', '+504', 'Honduran lempira', 'HNL', 'L', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(95, 'Hong Kong', 'HK', '+852', 'Hong Kong dollar', 'HKD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(96, 'Hungary', 'HU', '+36', 'Hungarian forint', 'HUF', 'Ft', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(97, 'Iceland', 'IS', '+354', 'Icelandic króna', 'ISK', 'kr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(98, 'India', 'IN', '+91', 'Indian rupee', 'INR', '₹', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(99, 'Indonesia', 'ID', '+62', 'Indonesian rupiah', 'IDR', 'Rp', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(100, 'Iran, Islamic Republ', 'IR', '+98', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(101, 'Iraq', 'IQ', '+964', 'Iraqi dinar', 'IQD', 'ع.د', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(102, 'Ireland', 'IE', '+353', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(103, 'Isle of Man', 'IM', '+44', 'British pound', 'GBP', '£', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(104, 'Israel', 'IL', '+972', 'Israeli new shekel', 'ILS', '₪', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(105, 'Italy', 'IT', '+39', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(106, 'Jamaica', 'JM', '+1876', 'Jamaican dollar', 'JMD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(107, 'Japan', 'JP', '+81', 'Japanese yen', 'JPY', '¥', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(108, 'Jersey', 'JE', '+44', 'British pound', 'GBP', '£', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(109, 'Jordan', 'JO', '+962', 'Jordanian dinar', 'JOD', 'د.ا', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(110, 'Kazakhstan', 'KZ', '+7 7', 'Kazakhstani tenge', 'KZT', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(111, 'Kenya', 'KE', '+254', 'Kenyan shilling', 'KES', 'Sh', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(112, 'Kiribati', 'KI', '+686', 'Australian dollar', 'AUD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(113, 'Korea, Democratic Pe', 'KP', '+850', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(114, 'Korea, Republic of S', 'KR', '+82', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(115, 'Kuwait', 'KW', '+965', 'Kuwaiti dinar', 'KWD', 'د.ك', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(116, 'Kyrgyzstan', 'KG', '+996', 'Kyrgyzstani som', 'KGS', 'лв', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(117, 'Laos', 'LA', '+856', 'Lao kip', 'LAK', '₭', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(118, 'Latvia', 'LV', '+371', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(119, 'Lebanon', 'LB', '+961', 'Lebanese pound', 'LBP', 'ل.ل', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(120, 'Lesotho', 'LS', '+266', 'Lesotho loti', 'LSL', 'L', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(121, 'Liberia', 'LR', '+231', 'Liberian dollar', 'LRD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(122, 'Libyan Arab Jamahiri', 'LY', '+218', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(123, 'Liechtenstein', 'LI', '+423', 'Swiss franc', 'CHF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(124, 'Lithuania', 'LT', '+370', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(125, 'Luxembourg', 'LU', '+352', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(126, 'Macao', 'MO', '+853', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(127, 'Macedonia', 'MK', '+389', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(128, 'Madagascar', 'MG', '+261', 'Malagasy ariary', 'MGA', 'Ar', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(129, 'Malawi', 'MW', '+265', 'Malawian kwacha', 'MWK', 'MK', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(130, 'Malaysia', 'MY', '+60', 'Malaysian ringgit', 'MYR', 'RM', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(131, 'Maldives', 'MV', '+960', 'Maldivian rufiyaa', 'MVR', '.ރ', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(132, 'Mali', 'ML', '+223', 'West African CFA fra', 'XOF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(133, 'Malta', 'MT', '+356', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(134, 'Marshall Islands', 'MH', '+692', 'United States dollar', 'USD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(135, 'Martinique', 'MQ', '+596', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(136, 'Mauritania', 'MR', '+222', 'Mauritanian ouguiya', 'MRO', 'UM', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(137, 'Mauritius', 'MU', '+230', 'Mauritian rupee', 'MUR', '₨', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(138, 'Mayotte', 'YT', '+262', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(139, 'Mexico', 'MX', '+52', 'Mexican peso', 'MXN', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(140, 'Micronesia, Federate', 'FM', '+691', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(141, 'Moldova', 'MD', '+373', 'Moldovan leu', 'MDL', 'L', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(142, 'Monaco', 'MC', '+377', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(143, 'Mongolia', 'MN', '+976', 'Mongolian tögrög', 'MNT', '₮', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(144, 'Montenegro', 'ME', '+382', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(145, 'Montserrat', 'MS', '+1664', 'East Caribbean dolla', 'XCD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(146, 'Morocco', 'MA', '+212', 'Moroccan dirham', 'MAD', 'د.م.', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(147, 'Mozambique', 'MZ', '+258', 'Mozambican metical', 'MZN', 'MT', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(148, 'Myanmar', 'MM', '+95', 'Burmese kyat', 'MMK', 'Ks', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(149, 'Namibia', 'NA', '+264', 'Namibian dollar', 'NAD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(150, 'Nauru', 'NR', '+674', 'Australian dollar', 'AUD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(151, 'Nepal', 'NP', '+977', 'Nepalese rupee', 'NPR', '₨', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(152, 'Netherlands', 'NL', '+31', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(153, 'Netherlands Antilles', 'AN', '+599', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(154, 'New Caledonia', 'NC', '+687', 'CFP franc', 'XPF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(155, 'New Zealand', 'NZ', '+64', 'New Zealand dollar', 'NZD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(156, 'Nicaragua', 'NI', '+505', 'Nicaraguan córdoba', 'NIO', 'C$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(157, 'Niger', 'NE', '+227', 'West African CFA fra', 'XOF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(158, 'Nigeria', 'NG', '+234', 'Nigerian naira', 'NGN', '₦', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(159, 'Niue', 'NU', '+683', 'New Zealand dollar', 'NZD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(160, 'Norfolk Island', 'NF', '+672', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(161, 'Northern Mariana Isl', 'MP', '+1670', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(162, 'Norway', 'NO', '+47', 'Norwegian krone', 'NOK', 'kr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(163, 'Oman', 'OM', '+968', 'Omani rial', 'OMR', 'ر.ع.', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(164, 'Pakistan', 'PK', '+92', 'Pakistani rupee', 'PKR', '₨', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(165, 'Palau', 'PW', '+680', 'Palauan dollar', '', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(166, 'Palestinian Territor', 'PS', '+970', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(167, 'Panama', 'PA', '+507', 'Panamanian balboa', 'PAB', 'B/.', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(168, 'Papua New Guinea', 'PG', '+675', 'Papua New Guinean ki', 'PGK', 'K', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(169, 'Paraguay', 'PY', '+595', 'Paraguayan guaraní', 'PYG', '₲', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(170, 'Peru', 'PE', '+51', 'Peruvian nuevo sol', 'PEN', 'S/.', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(171, 'Philippines', 'PH', '+63', 'Philippine peso', 'PHP', '₱', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(172, 'Pitcairn', 'PN', '+872', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(173, 'Poland', 'PL', '+48', 'Polish z?oty', 'PLN', 'zł', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(174, 'Portugal', 'PT', '+351', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(175, 'Puerto Rico', 'PR', '+1939', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(176, 'Qatar', 'QA', '+974', 'Qatari riyal', 'QAR', 'ر.ق', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(177, 'Romania', 'RO', '+40', 'Romanian leu', 'RON', 'lei', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(178, 'Russia', 'RU', '+7', 'Russian ruble', 'RUB', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(179, 'Rwanda', 'RW', '+250', 'Rwandan franc', 'RWF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(180, 'Reunion', 'RE', '+262', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(181, 'Saint Barthelemy', 'BL', '+590', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(182, 'Saint Helena, Ascens', 'SH', '+290', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(183, 'Saint Kitts and Nevi', 'KN', '+1869', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(184, 'Saint Lucia', 'LC', '+1758', 'East Caribbean dolla', 'XCD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(185, 'Saint Martin', 'MF', '+590', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(186, 'Saint Pierre and Miq', 'PM', '+508', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(187, 'Saint Vincent and th', 'VC', '+1784', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(188, 'Samoa', 'WS', '+685', 'Samoan t?l?', 'WST', 'T', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(189, 'San Marino', 'SM', '+378', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(190, 'Sao Tome and Princip', 'ST', '+239', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(191, 'Saudi Arabia', 'SA', '+966', 'Saudi riyal', 'SAR', 'ر.س', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(192, 'Senegal', 'SN', '+221', 'West African CFA fra', 'XOF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(193, 'Serbia', 'RS', '+381', 'Serbian dinar', 'RSD', 'дин. or din.', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(194, 'Seychelles', 'SC', '+248', 'Seychellois rupee', 'SCR', '₨', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(195, 'Sierra Leone', 'SL', '+232', 'Sierra Leonean leone', 'SLL', 'Le', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(196, 'Singapore', 'SG', '+65', 'Brunei dollar', 'BND', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(197, 'Slovakia', 'SK', '+421', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(198, 'Slovenia', 'SI', '+386', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(199, 'Solomon Islands', 'SB', '+677', 'Solomon Islands doll', 'SBD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(200, 'Somalia', 'SO', '+252', 'Somali shilling', 'SOS', 'Sh', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(201, 'South Africa', 'ZA', '+27', 'South African rand', 'ZAR', 'R', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(202, 'South Georgia and th', 'GS', '+500', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(203, 'Spain', 'ES', '+34', 'Euro', 'EUR', '€', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(204, 'Sri Lanka', 'LK', '+94', 'Sri Lankan rupee', 'LKR', 'Rs or රු', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(205, 'Sudan', 'SD', '+249', 'Sudanese pound', 'SDG', 'ج.س.', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(206, 'Suriname', 'SR', '+597', 'Surinamese dollar', 'SRD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(207, 'Svalbard and Jan May', 'SJ', '+47', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(208, 'Swaziland', 'SZ', '+268', 'Swazi lilangeni', 'SZL', 'L', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(209, 'Sweden', 'SE', '+46', 'Swedish krona', 'SEK', 'kr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(210, 'Switzerland', 'CH', '+41', 'Swiss franc', 'CHF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(211, 'Syrian Arab Republic', 'SY', '+963', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(212, 'Taiwan', 'TW', '+886', 'New Taiwan dollar', 'TWD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(213, 'Tajikistan', 'TJ', '+992', 'Tajikistani somoni', 'TJS', 'ЅМ', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(214, 'Tanzania, United Rep', 'TZ', '+255', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(215, 'Thailand', 'TH', '+66', 'Thai baht', 'THB', '฿', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(216, 'Timor-Leste', 'TL', '+670', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(217, 'Togo', 'TG', '+228', 'West African CFA fra', 'XOF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(218, 'Tokelau', 'TK', '+690', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(219, 'Tonga', 'TO', '+676', 'Tongan pa?anga', 'TOP', 'T$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(220, 'Trinidad and Tobago', 'TT', '+1868', 'Trinidad and Tobago ', 'TTD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(221, 'Tunisia', 'TN', '+216', 'Tunisian dinar', 'TND', 'د.ت', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(222, 'Turkey', 'TR', '+90', 'Turkish lira', 'TRY', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(223, 'Turkmenistan', 'TM', '+993', 'Turkmenistan manat', 'TMT', 'm', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(224, 'Turks and Caicos Isl', 'TC', '+1649', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(225, 'Tuvalu', 'TV', '+688', 'Australian dollar', 'AUD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(226, 'Uganda', 'UG', '+256', 'Ugandan shilling', 'UGX', 'Sh', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(227, 'Ukraine', 'UA', '+380', 'Ukrainian hryvnia', 'UAH', '₴', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(228, 'United Arab Emirates', 'AE', '+971', 'United Arab Emirates', 'AED', 'د.إ', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(229, 'United Kingdom', 'GB', '+44', 'British pound', 'GBP', '£', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(230, 'United States', 'US', '+1', 'United States dollar', 'USD', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(231, 'Uruguay', 'UY', '+598', 'Uruguayan peso', 'UYU', '$', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(232, 'Uzbekistan', 'UZ', '+998', 'Uzbekistani som', 'UZS', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(233, 'Vanuatu', 'VU', '+678', 'Vanuatu vatu', 'VUV', 'Vt', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(234, 'Venezuela, Bolivaria', 'VE', '+58', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(235, 'Vietnam', 'VN', '+84', 'Vietnamese ??ng', 'VND', '₫', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(236, 'Virgin Islands, Brit', 'VG', '+1284', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(237, 'Virgin Islands, U.S.', 'VI', '+1340', '', '', '', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(238, 'Wallis and Futuna', 'WF', '+681', 'CFP franc', 'XPF', 'Fr', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(239, 'Yemen', 'YE', '+967', 'Yemeni rial', 'YER', '﷼', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(240, 'Zambia', 'ZM', '+260', 'Zambian kwacha', 'ZMW', 'ZK', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03'),
(241, 'Zimbabwe', 'ZW', '+263', 'Botswana pula', 'BWP', 'P', 1, '2020-11-19 01:09:03', '2020-11-19 01:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_type` enum('DEFAULT','CUSTOM','WALKIN') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `building_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_seller_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_additional_info`
--

CREATE TABLE `customer_additional_info` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_table`
--

CREATE TABLE `custom_table` (
  `custom_id` int(11) NOT NULL,
  `custom_field` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_data_type` int(11) NOT NULL,
  `custom_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept_id` int(11) NOT NULL,
  `department_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deviceinfo`
--

CREATE TABLE `deviceinfo` (
  `id` int(11) NOT NULL,
  `device_ip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deviceinfo`
--

INSERT INTO `deviceinfo` (`id`, `device_ip`) VALUES
(1, '192.168.1.201');

-- --------------------------------------------------------

--
-- Table structure for table `discount_codes`
--

CREATE TABLE `discount_codes` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percentage` decimal(8,2) DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `discounttype` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_always` int(11) NOT NULL DEFAULT '0',
  `discount_value` decimal(10,2) DEFAULT NULL,
  `limit_on_discount` int(11) NOT NULL DEFAULT '0',
  `discount_start_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discount_end_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discount_applicable_products` text COLLATE utf8mb4_unicode_ci,
  `discount_not_applicable_products` text COLLATE utf8mb4_unicode_ci,
  `discount_applicable_categories` text COLLATE utf8mb4_unicode_ci,
  `discount_applied_on` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `duty_type`
--

CREATE TABLE `duty_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `duty_type`
--

INSERT INTO `duty_type` (`id`, `type_name`) VALUES
(1, 'Full Time'),
(2, 'Part Time'),
(3, 'Contructual'),
(4, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `edfapay_transactions`
--

CREATE TABLE `edfapay_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `order_json` text COLLATE utf8mb4_unicode_ci,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Pending,1-Paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_benifit`
--

CREATE TABLE `employee_benifit` (
  `id` int(11) NOT NULL,
  `bnf_cl_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bnf_cl_code_des` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bnff_acural_date` date NOT NULL,
  `bnf_status` tinyint(4) NOT NULL,
  `employee_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_equipment`
--

CREATE TABLE `employee_equipment` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `issue_date` date NOT NULL,
  `damarage_desc` text COLLATE utf8mb4_unicode_ci,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_history`
--

CREATE TABLE `employee_history` (
  `emp_his_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `pos_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alter_phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `present_address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parmanent_address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` text COLLATE utf8mb4_unicode_ci,
  `degree_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cgp` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passing_year` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_period` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duties` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervisor` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` text COLLATE utf8mb4_unicode_ci,
  `is_admin` int(2) NOT NULL DEFAULT '0',
  `dept_id` int(11) DEFAULT NULL,
  `division_id` int(11) DEFAULT NULL,
  `maiden_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` int(11) DEFAULT NULL,
  `citizenship` int(11) DEFAULT NULL,
  `duty_type` int(11) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `original_hire_date` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `termination_reason` text COLLATE utf8mb4_unicode_ci,
  `voluntary_termination` int(11) DEFAULT NULL,
  `rehire_date` date DEFAULT NULL,
  `rate_type` int(11) DEFAULT NULL,
  `rate` float DEFAULT NULL,
  `pay_frequency` int(11) DEFAULT NULL,
  `pay_frequency_txt` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hourly_rate2` float DEFAULT NULL,
  `hourly_rate3` float DEFAULT NULL,
  `home_department` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_code_desc` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_acc_date` date DEFAULT NULL,
  `class_status` tinyint(4) DEFAULT NULL,
  `is_super_visor` int(11) DEFAULT NULL,
  `super_visor_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervisor_report` text COLLATE utf8mb4_unicode_ci,
  `dob` date DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `marital_status` int(11) DEFAULT NULL,
  `ethnic_group` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eeo_class_gp` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ssn` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_in_state` int(11) DEFAULT NULL,
  `live_in_state` int(11) DEFAULT NULL,
  `home_email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cell_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emerg_contct` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emrg_h_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emrg_w_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emgr_contct_relation` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_em_contct` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_emg_h_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_emg_w_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `latitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceptablerange` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance`
--

CREATE TABLE `employee_performance` (
  `emp_per_id` int(11) NOT NULL,
  `employee_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_star` int(11) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_position`
--

CREATE TABLE `employee_position` (
  `emp_pos_id` int(10) UNSIGNED NOT NULL,
  `employee_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_details` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary_payment`
--

CREATE TABLE `employee_salary_payment` (
  `emp_sal_pay_id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `total_salary` varchar(50) NOT NULL,
  `total_working_minutes` varchar(50) NOT NULL,
  `working_period` varchar(50) NOT NULL,
  `payment_due` varchar(50) NOT NULL,
  `payment_date` varchar(50) NOT NULL,
  `salary_name` varchar(100) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `bank_name` varchar(250) DEFAULT NULL,
  `paid_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary_setup`
--

CREATE TABLE `employee_salary_setup` (
  `e_s_s_id` int(11) UNSIGNED NOT NULL,
  `employee_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sal_type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary_type_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` date DEFAULT NULL,
  `update_date` datetime(6) DEFAULT NULL,
  `update_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gross_salary` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_sal_pay_type`
--

CREATE TABLE `employee_sal_pay_type` (
  `emp_sal_pay_type_id` int(11) UNSIGNED NOT NULL,
  `payment_period` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_attendance`
--

CREATE TABLE `emp_attendance` (
  `att_id` int(10) UNSIGNED NOT NULL,
  `employee_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sign_in` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sign_out` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staytime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `equipment_id` int(11) NOT NULL,
  `equipment_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_assign` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_type`
--

CREATE TABLE `equipment_type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_type`
--

INSERT INTO `equipment_type` (`type_id`, `type_name`) VALUES
(1, 'assest');

-- --------------------------------------------------------

--
-- Table structure for table `expense_information`
--

CREATE TABLE `expense_information` (
  `id` int(11) NOT NULL,
  `expense_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_information`
--

INSERT INTO `expense_information` (`id`, `expense_name`) VALUES
(1, 'Direct Cost'),
(2, 'Operational Cost'),
(3, 'Non Operational Expenses');

-- --------------------------------------------------------

--
-- Table structure for table `expresspay`
--

CREATE TABLE `expresspay` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_to` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'INVOICE,POS',
  `bill_to_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_json` json DEFAULT NULL,
  `request_json` json DEFAULT NULL,
  `response_json` json DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0-Pending,1-Paid',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `gender_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`id`, `gender_name`) VALUES
(1, 'Male'),
(2, 'Female'),
(3, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `grand_loan`
--

CREATE TABLE `grand_loan` (
  `loan_id` int(11) NOT NULL,
  `employee_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_details` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interest_rate` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `installment` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `installment_period` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `repayment_amount` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_approve` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `repayment_start_date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hardwares`
--

CREATE TABLE `hardwares` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_area`
--

CREATE TABLE `income_area` (
  `id` int(11) NOT NULL,
  `income_field` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income_area`
--

INSERT INTO `income_area` (`id`, `income_field`) VALUES
(1, 'Sales'),
(2, 'Sales Discount'),
(3, 'Sales Return');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_counts`
--

CREATE TABLE `inventory_counts` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(30) NOT NULL,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_date` date DEFAULT NULL,
  `status` smallint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_count_items`
--

CREATE TABLE `inventory_count_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `inventory_count_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `original_quantity` decimal(5,2) DEFAULT NULL,
  `entered_quantity` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `invoice_due_date` date DEFAULT NULL,
  `parent_po_id` int(11) DEFAULT NULL,
  `bill_to` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_to_id` int(11) NOT NULL,
  `bill_to_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_to_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_address` text COLLATE utf8mb4_unicode_ci,
  `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_option_id` int(11) DEFAULT NULL,
  `subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_including_tax` decimal(10,2) NOT NULL,
  `total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `shipping_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `packing_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_color_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Default Color Code - #094269',
  `return_invoice_amount` decimal(13,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices_return`
--

CREATE TABLE `invoices_return` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `return_invoice_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `invoice_due_date` date DEFAULT NULL,
  `parent_po_id` int(11) DEFAULT NULL,
  `bill_to` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_to_id` int(11) NOT NULL,
  `bill_to_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_to_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_address` text COLLATE utf8mb4_unicode_ci,
  `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_option_id` int(11) DEFAULT NULL,
  `subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_including_tax` decimal(10,2) NOT NULL,
  `total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `shipping_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `packing_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_color_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Default Color Code - #094269',
  `reason` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `payment_method_slack` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_charges`
--

CREATE TABLE `invoice_charges` (
  `id` int(11) NOT NULL,
  `slack` varchar(50) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_products`
--

CREATE TABLE `invoice_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `show_description_in` int(11) DEFAULT '0',
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_code_id` int(11) DEFAULT NULL,
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount_type` smallint(6) NOT NULL COMMENT '1 - Amount, 2 - Percentage',
  `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_components` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `measurement_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_type` int(11) DEFAULT NULL COMMENT '1-Product 2-Service'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_return_products`
--

CREATE TABLE `invoice_return_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_invoice_id` int(11) NOT NULL,
  `return_invoice_slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_code_id` int(11) DEFAULT NULL,
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_components` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `measurement_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_type` int(11) DEFAULT NULL COMMENT '1-Product 2-Service',
  `is_wastage` tinyint(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_services`
--

CREATE TABLE `invoice_services` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_code_id` int(11) DEFAULT NULL,
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_components` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_advertisement`
--

CREATE TABLE `job_advertisement` (
  `job_adv_id` int(10) UNSIGNED NOT NULL,
  `pos_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adv_circular_date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `circular_dadeline` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adv_file` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `adv_details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keyboard_shortcuts`
--

CREATE TABLE `keyboard_shortcuts` (
  `id` int(10) UNSIGNED NOT NULL,
  `keyboard_constant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyboard_shortcut` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyboard_shortcut_label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `keyboard_shortcuts`
--

INSERT INTO `keyboard_shortcuts` (`id`, `keyboard_constant`, `keyboard_shortcut`, `keyboard_shortcut_label`, `description`, `sort_order`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'CLOSE_ORDER', 'ctrl,shift,m', 'Ctrl+Shift+m', 'Close Order', 0, 1, 1, NULL, '2020-11-19 01:10:49', '2020-11-19 01:10:49'),
(2, 'HOLD_ORDER', 'ctrl,shift,n', 'Ctrl+Shift+n', 'Hold Order', 0, 1, 1, NULL, '2020-11-19 01:10:49', '2020-11-19 01:10:49'),
(3, 'SEND_TO_KITCHEN', 'ctrl,shift,b', 'Ctrl+Shift+b', 'Send to Kitchen', 0, 1, 1, NULL, '2020-11-19 01:10:49', '2020-11-19 01:10:49'),
(4, 'SKIP_CUSTOMER', 'shift,z', 'Shift+z', 'Skip customer selection', 0, 1, 1, NULL, '2020-11-19 01:10:49', '2020-11-19 01:10:49'),
(5, 'PROCEED_CUSTOMER', 'shift,x', 'Shift+x', 'Proceed customer selection', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(6, 'ARROW_LEFT', 'arrowleft', 'Arrow Left', 'Move left through POS products', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(7, 'ARROW_RIGHT', 'arrowright', 'Arrow Right', 'Move right through POS products', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(8, 'CHOOSE_PRODUCT', 'ctrl', 'Control', 'Choose POS product', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(9, 'SCROLL_PAYMENT_METHODS', 'shift,p', 'Shift+p', 'Scroll through payment methods', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(10, 'CHOOSE_PAYMENT_METHOD', 'shift,l', 'Shift+l', 'Choose payment method', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(11, 'SCROLL_BUSINESS_ACCOUNTS', 'shift,o', 'Shift+o', 'Scroll through business accounts', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(12, 'CHOOSE_BUSINESS_ACCOUNT', 'shift,k', 'Shift+k', 'Choose business accounts', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(13, 'SCROLL_ORDER_TYPES', 'shift,i', 'Shift+i', 'Scroll through order types', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(14, 'CHOOSE_ORDER_TYPE', 'shift,j', 'Shift+j', 'Choose order type', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(15, 'SCROLL_RESTAURANT_TABLES', 'shift,u', 'Shift+u', 'Scroll through restaurant tables', 0, 1, 1, NULL, '2020-11-19 01:10:50', '2020-11-19 01:10:50'),
(16, 'CHOOSE_RESTAURANT_TABLE', 'shift,h', 'Shift+h', 'Choose restaurant table', 0, 1, 1, NULL, '2020-11-19 01:10:51', '2020-11-19 01:10:51'),
(17, 'CONTINUE', 'shift,m', 'Shift+m', 'Continue', 0, 1, 1, NULL, '2020-11-19 01:10:51', '2020-11-19 01:10:51'),
(18, 'CANCEL', 'shift,n', 'Shift+n', 'Cancel', 0, 1, 1, NULL, '2020-11-19 01:10:51', '2020-11-19 01:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `phrase` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `english` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `arabic` mediumtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `phrase`, `english`, `arabic`) VALUES
(2, 'login', 'Login', 'تسجيل الدخول'),
(3, 'email', 'Email Address', 'البريد الإلكتروني'),
(4, 'password', 'Password', 'كلمة المرور'),
(5, 'reset', 'Reset', 'إعادة تعيين'),
(6, 'dashboard', 'Dashboard', 'لوحة التحكم'),
(7, 'home', 'Home', 'المنزل'),
(8, 'profile', 'Profile', 'الملف الشخصي'),
(9, 'profile_setting', 'Profile Setting', 'إعدادات الملف الشخصي'),
(10, 'firstname', 'First Name', 'الاسم الأول'),
(11, 'lastname', 'Last Name', 'اسم العائلة'),
(12, 'about', 'About', 'حول'),
(13, 'preview', 'Preview', 'معاينة'),
(14, 'image', 'Image', 'صورة'),
(15, 'save', 'Save', 'حفظ'),
(16, 'upload_successfully', 'Upload Successfully!', 'تم الرفع بنجاح'),
(17, 'user_added_successfully', 'User Added Successfully!', 'تمت إضافة المستخدم بنجاح'),
(18, 'please_try_again', 'Please Try Again...', 'حاول مرة أخرى'),
(19, 'inbox_message', 'Inbox Messages', 'رسالة البريد الوارد'),
(20, 'sent_message', 'Sent Message', 'أرسل رسالة'),
(21, 'message_details', 'Message Details', 'تفاصيل الرسالة'),
(22, 'new_message', 'New Message', 'رسالة جديدة'),
(23, 'receiver_name', 'Receiver Name', 'اسم المستلم'),
(24, 'sender_name', 'Sender Name', 'اسم المرسل'),
(25, 'subject', 'Subject', 'الموضوع'),
(26, 'message', 'Message', 'رسالة'),
(27, 'message_sent', 'Message Sent!', 'تم الارسال'),
(28, 'ip_address', 'IP Address', ''),
(29, 'last_login', 'Last Login', 'آخر تسجيل دخول'),
(30, 'last_logout', 'Last Logout', 'آخر تسجيل خروج'),
(31, 'status', 'Status', 'الحالات'),
(32, 'delete_successfully', 'Delete Successfully!', 'حذف بنجاح'),
(33, 'send', 'Send', 'أرسل'),
(34, 'date', 'Date', 'التاريخ'),
(35, 'action', 'Action', 'عملية'),
(36, 'sl_no', 'SL No.', 'رقم التسلسل'),
(37, 'are_you_sure', 'Are You Sure ? ', 'هل أنت واثق'),
(38, 'application_setting', 'Application Setting', 'اعدادات التطبيق'),
(39, 'application_title', 'Application Title', 'عنوان التطبيق'),
(40, 'address', 'Address', 'العنوان'),
(41, 'phone', 'Phone', 'جوال'),
(42, 'favicon', 'Favicon', 'الأيقونة المفضلة'),
(43, 'logo', 'Logo', 'شعار'),
(44, 'language', 'Language', 'اللغة'),
(45, 'left_to_right', 'Left To Right', 'من اليسار إلى اليمين'),
(46, 'right_to_left', 'Right To Left', 'من اليمين إلى اليسار'),
(47, 'footer_text', 'Footer Text', 'نص التذييل'),
(48, 'site_align', 'Application Alignment', 'محاذاة الموقع'),
(49, 'welcome_back', 'Welcome Back!', 'مرحبا بعودتك'),
(50, 'please_contact_with_admin', 'Please Contact With Admin', 'يرجى التواصل مع المشرف'),
(51, 'incorrect_email_or_password', 'Incorrect Email/Password', 'بريد أو كلمة مرورغير صحيحة'),
(52, 'select_option', 'Select Option', 'حدد الخيار'),
(53, 'ftp_setting', 'Data Synchronize [FTP Setting]', 'إعداد بروتوكول نقل الملفات'),
(54, 'hostname', 'Host Name', 'اسم المضيف'),
(55, 'username', 'User Name', 'اسم المستخدم'),
(56, 'ftp_port', 'FTP Port', 'منفذ بروتوكول نقل الملفات'),
(57, 'ftp_debug', 'FTP Debug', 'تصحيح أخطاء بروتوكول نقل الملفات'),
(58, 'project_root', 'Project Root', 'أصل المشروع'),
(59, 'update_successfully', 'Update Successfully', 'تم التحديث بنجاح'),
(60, 'save_successfully', 'Save Successfully!', 'تم الحفظ بنجاح'),
(61, 'delete_successfully', 'Delete Successfully!', 'حذف بنجاح'),
(62, 'internet_connection', 'Internet Connection', 'اتصال بالإنترنت'),
(63, 'ok', 'Ok', 'نعم'),
(64, 'not_available', 'Not Available', 'غير متاح'),
(65, 'available', 'Available', 'متوفرة'),
(66, 'outgoing_file', 'Outgoing File', 'ملف صادر'),
(67, 'incoming_file', 'Incoming File', 'ملف الدخل'),
(68, 'data_synchronize', 'Data Synchronize', 'مزامنة البيانات'),
(69, 'unable_to_upload_file_please_check_configuration', 'Unable to upload file! please check configuration', 'غير قادر على تحميل الملف يرجى التحقق من التكوين'),
(70, 'please_configure_synchronizer_settings', 'Please configure synchronizer settings', 'يرجى تكوين إعدادات المزامن'),
(71, 'download_successfully', 'Download Successfully', 'تم التنزيل بنجاح'),
(72, 'unable_to_download_file_please_check_configuration', 'Unable to download file! please check configuration', 'غير قادر على تنزيل الملف يرجى التحقق من التكوين'),
(73, 'data_import_first', 'Data Import First', 'استيراد البيانات أولا'),
(74, 'data_import_successfully', 'Data Import Successfully!', 'استيراد البيانات بنجاح'),
(75, 'unable_to_import_data_please_check_config_or_sql_file', 'Unable to import data! please check configuration / SQL file.', 'غير قادر على استيراد البيانات ، يرجى التحقق من التكوين لملف SQL'),
(76, 'download_data_from_server', 'Download Data from Server', 'تنزيل البيانات من الخادم'),
(77, 'data_import_to_database', 'Data Import To Database', 'استيراد البيانات إلى قاعدة البيانات'),
(79, 'data_upload_to_server', 'Data Upload to Server', 'تحميل البيانات إلى الخادم'),
(80, 'please_wait', 'Please Wait...', 'الرجاء الإنتظار'),
(81, 'ooops_something_went_wrong', ' Ooops something went wrong...', 'شيء ما حدث بشكل خاطئ'),
(82, 'module_permission_list', 'Module Permission List', 'قائمة أذونات الوحدة'),
(83, 'user_permission', 'User Permission', 'إذن المستخدم'),
(84, 'add_module_permission', 'Add Module Permission', 'إضافة صلاحية لخاصية '),
(85, 'module_permission_added_successfully', 'Module Permission Added Successfully!', 'تمت إضافة إذن الوحدة بنجاح'),
(86, 'update_module_permission', 'Update Module Permission', ''),
(87, 'download', 'Download', 'تحميل'),
(88, 'module_name', 'Module Name', 'اسم وحدة'),
(89, 'create', 'Create', 'إنشأ'),
(90, 'read', 'Read', 'إقرأ'),
(91, 'update', 'Update', 'تحدث'),
(92, 'delete', 'Delete', 'حذف'),
(93, 'module_list', 'Module List', 'قائمة الوحدة'),
(94, 'add_module', 'Add Module', 'إضافة خاصية'),
(95, 'directory', 'Module Direcotory', 'الدليل'),
(96, 'description', 'Description', 'الوصف'),
(97, 'image_upload_successfully', 'Image Upload Successfully!', 'تم تحميل الصورة بنجاح'),
(98, 'module_added_successfully', 'Module Added Successfully', 'تمت إضافة الوحدة بنجاح'),
(99, 'inactive', 'Inactive', 'غير نشط'),
(100, 'active', 'Active', 'فعال'),
(101, 'user_list', 'User List', 'قائمة المستخدم'),
(102, 'see_all_message', 'See All Messages', 'رؤية كل الرسائل'),
(103, 'setting', 'Setting', 'إعدادات'),
(104, 'logout', 'Logout', 'تسجيل خروج'),
(105, 'admin', 'Admin', 'مسؤول'),
(106, 'add_user', 'Add User', 'إضافة مستخدم'),
(107, 'user', 'User', 'المستخدم'),
(108, 'module', 'Module', 'وحدة'),
(109, 'new', 'New', 'جديد'),
(110, 'inbox', 'Inbox', 'صندوق الوارد'),
(111, 'sent', 'Sent', 'مرسل'),
(112, 'synchronize', 'Synchronize', 'تزامن'),
(113, 'data_synchronizer', 'Data Synchronizer', 'مزامنة البيانات'),
(114, 'module_permission', 'Module Permission', 'إذن الوحدة'),
(115, 'backup_now', 'Backup Now!', 'اعمل نسخة احتياطية الان'),
(116, 'restore_now', 'Restore Now!', 'استعادة الآن'),
(117, 'backup_and_restore', 'Backup and Download', 'النسخ الاحتياطي والاستعادة'),
(118, 'captcha', 'Captcha Word', 'كلمة التحقق'),
(119, 'database_backup', 'Database Backup', 'نسخه الاحتياطيه لقاعدة البيانات'),
(120, 'restore_successfully', 'Restore Successfully', 'تم الاستعادة بنجاح'),
(121, 'backup_successfully', 'Backup Successfully', 'تم النسخ الاحتياطي بنجاح'),
(122, 'filename', 'File Name', 'اسم الملف'),
(123, 'file_information', 'File Information', 'معلومات الملف'),
(124, 'size', 'size', 'المقاس'),
(125, 'backup_date', 'Backup Date', 'تاريخ_النسخ الاحتياطي'),
(126, 'overwrite', 'Overwrite', 'الكتابة فوق'),
(127, 'invalid_file', 'Invalid File!', 'ملف غير صالح'),
(128, 'invalid_module', 'Invalid Module', 'وحدة غير صالحة'),
(129, 'remove_successfully', 'Remove Successfully!', 'تمت الإزالة بنجاح'),
(130, 'install', 'Install', 'تثبيت'),
(131, 'uninstall', 'Uninstall', 'الغاء التثبيت'),
(132, 'tables_are_not_available_in_database', 'Tables are not available in database.sql', ''),
(133, 'no_tables_are_registered_in_config', 'No tables are registerd in config.php', 'لا توجد جداول مسجلة في ملف التكوين'),
(134, 'enquiry', 'Enquiry', 'سؤال'),
(135, 'read_unread', 'Read/Unread', 'قراءة غير مقروءة'),
(136, 'enquiry_information', 'Enquiry Information', 'معلومات الاستفسار'),
(137, 'user_agent', 'User Agent', 'وكيل المستخدم'),
(138, 'checked_by', 'Checked By', 'فحص بواسطة'),
(139, 'new_enquiry', 'New Enquiry', 'استفسار جديد'),
(140, 'crud', 'Crud', ''),
(141, 'view', 'View', 'عرض'),
(142, 'name', 'Name', 'اسم'),
(143, 'add', 'Address', 'إضافة'),
(144, 'ph', 'Phone', ''),
(145, 'cid', 'SL No', ''),
(146, 'view_atn', 'AttendanceView', 'مراقبة الحضور'),
(147, 'mang', 'Employemanagement', ''),
(148, 'designation', 'Position', 'الاستقالات'),
(149, 'test', 'Test', 'اختبار'),
(150, 'sl', 'SL', 'التسلسل'),
(151, 'bdtask', 'BDTASK', 'وصول'),
(152, 'practice', 'Practice', 'تدريب'),
(153, 'branch_name', 'Branch Name', 'أسم الفرع'),
(154, 'chairman_name', 'Chairman', 'اسم رئيس مجلس الإدارة'),
(155, 'b_photo', 'Photo', 'الصورة'),
(156, 'b_address', 'Address', 'العنوان'),
(157, 'position', 'Position', 'الوظيفة'),
(158, 'advertisement', 'Advertisement', 'الإعلانات'),
(159, 'position_name', 'Position', 'اسم الوظيفة'),
(160, 'position_details', 'Details', 'تفاصيل الوظيفة'),
(161, 'circularprocess', 'Recruitment', 'أجراءات سير العمل'),
(162, 'pos_id', 'Position', 'الرقم الوظيفي'),
(163, 'adv_circular_date', 'Publish Date', 'الدورة الزمنية للاعلان'),
(164, 'circular_dadeline', 'Dadeline', 'وقت تسليم سير العمل'),
(165, 'adv_file', 'Documents', 'ملفات الاعلان '),
(166, 'adv_details', 'Details', 'معلومات الاعلان'),
(167, 'attendance', 'Attendance', 'الحضور'),
(168, 'employee', 'Employee', 'الموظف'),
(169, 'emp_id', 'Employee Name', 'معرف'),
(170, 'sign_in', 'Sign In', 'تسجيل الدخول'),
(171, 'sign_out', 'Sign Out', 'تسجيل الخروج'),
(172, 'staytime', 'Stay Time', 'وقت الجلوس'),
(173, 'abc', '1', 'أبجد'),
(174, 'first_name', 'First Name', 'الاسم الأول'),
(175, 'last_name', 'Last Name', 'اسم العائلة'),
(176, 'alter_phone', 'Alternative Phone', 'الرقم البديل '),
(177, 'present_address', 'Present Address', 'العنوان الحالي'),
(178, 'parmanent_address', 'Permanent Address', 'العنوان الثابت'),
(179, 'candidateinfo', 'Candidate Info', 'معلومات المرشح'),
(180, 'add_advertisement', 'Add Advertisement', 'إضافة إعلان '),
(181, 'advertisement_list', 'Manage Advertisement ', 'قائمة الإعلانات'),
(182, 'candidate_basic_info', 'Candidate Information', 'المعلومات الأساسية للمرشح'),
(183, 'can_basicinfo_list', 'Manage Candidate', 'يمكن قائمة المعلومات الأساسية'),
(184, 'add_canbasic_info', 'Add New Candidate', 'إضافة معلومات المرشح'),
(185, 'candidate_education_info', 'Candidate Educational Info', 'معلومات تعليم المرشح'),
(186, 'can_educationinfo_list', 'Candidate Edu Info list', 'يمكن قائمة معلومات التعليم'),
(187, 'add_edu_info', 'Add Educational Info', 'إضافة معلومات التعليم'),
(188, 'can_id', 'Candidate Id', 'الهوية الوطنية للمرشح '),
(189, 'degree_name', 'Obtained Degree', 'الدرجه العلميه'),
(190, 'university_name', 'University', 'اسم الجامعة'),
(191, 'cgp', 'CGPA', ''),
(192, 'comments', 'Comments', 'التعليقات'),
(193, 'signature', 'Signature', 'التوقيع'),
(194, 'candidate_workexperience', 'Candidate Work Experience', 'خبرة عمل المرشح'),
(195, 'can_workexperience_list', 'Work Experience list', 'يمكن أن تعمل قائمة الخبرة'),
(196, 'add_can_experience', 'Add Work Experience', 'إضافة خبرات المرشح '),
(197, 'company_name', 'Company Name', 'اسم الشركة'),
(198, 'working_period', 'Working Period', 'فترة العمل'),
(199, 'duties', 'Duties', 'المهام'),
(200, 'supervisor', 'Supervisor', 'المشرف'),
(201, 'candidate_workexpe', 'Candidate Work Experience', 'الخبرات العملية للمرشح'),
(202, 'candidate_shortlist', 'Candidate Shortlist', 'قائمة المرشحين المختصرة'),
(203, 'shortlist_view', 'Manage Shortlist', 'عرض القائمة مختصرة'),
(204, 'add_shortlist', 'Add Shortlist', 'إضافة قائمة مختصرة'),
(205, 'date_of_shortlist', 'Shortlist Date', 'تاريخ القائمة المختصرة'),
(206, 'interview_date', 'Interview Date', 'موعد المقابلة'),
(207, 'submit', 'Submit', 'تسليم'),
(208, 'candidate_id', 'Your ID', 'هوية المرشح'),
(209, 'job_adv_id', 'Job Position', ''),
(210, 'sequence', 'Sequence', 'التسلسل'),
(211, 'candidate_interview', 'Interview', 'مقابلة المرشح'),
(212, 'interview_list', 'Interview list', 'قائمة المقابلة'),
(213, 'add_interview', 'Interview', 'إضافة مقابلة'),
(214, 'interviewer_id', 'Interviewer', 'معرف المقابلة '),
(215, 'interview_marks', 'Viva Marks', 'علامات المقابلة'),
(216, 'written_total_marks', 'Written Total Marks', 'مجموع العلامات المكتوبة'),
(217, 'mcq_total_marks', 'MCQ Total Marks', ''),
(218, 'recommandation', 'Recommandation', 'التعليقات'),
(219, 'selection', 'Selection', 'اختيار'),
(220, 'details', 'Details', 'التفاصيل'),
(221, 'candidate_selection', 'Candidate Selection', 'اختيار المرشح'),
(222, 'selection_list', 'Selection List', 'قائمة الاختيار'),
(223, 'add_selection', 'Add Selection', 'إضافة اختيار'),
(224, 'employee_id', 'Employee Id', 'هوية الموظف'),
(225, 'position_id', '1', 'معرف الوظيفة'),
(226, 'selection_terms', 'Selection Terms', 'شروط الاختيار'),
(227, 'total_marks', 'Total Marks', 'إجمالي العلامات'),
(228, 'photo', 'Picture', 'صورة'),
(229, 'your_id', 'Your ID', 'رقم الهوية'),
(230, 'change_image', 'Change Photo', 'تغيير الصورة'),
(231, 'picture', 'Photograph', 'صورة'),
(232, 'ad', 'Add', 'إعلان'),
(233, 'write_y_p_info', 'Write Your Persoanal Information', 'اكتب عن نفسك'),
(234, 'emp_position', 'Employee Position', 'قسم الموظف'),
(235, 'add_pos', 'Add Position', 'إضافة نقاط بيع'),
(236, 'list_pos', 'List of Position', ''),
(237, 'emp_salary_stup', 'Employee Salary SetUp', 'اعدادات راتب الموظف'),
(238, 'add_salary_stup', 'Add Salary Setup', 'إضافة اعدادات الراتب'),
(239, 'list_salarystup', 'List of Salary Setup', ''),
(240, 'emp_sal_name', 'Salary Name', ''),
(241, 'emp_sal_type', 'Salary Type', ''),
(242, 'emp_performance', 'Employee Performance', 'تقييم اداء الموظف'),
(243, 'add_performance', 'Add Performance', 'إضافة الأداء'),
(244, 'list_performance', 'List of Performance', 'قائمة الاداء'),
(245, 'note', 'Note', 'ملاحظة'),
(246, 'note_by', 'Note By', 'ملاحظة من قبل'),
(247, 'number_of_star', 'Number of Star', 'التقييم الرقمي '),
(248, 'updated_by', 'Updated By', 'تم التحديث بواسطة'),
(249, 'emp_sal_payment', 'Manage Employee Salary', ''),
(250, 'add_payment', 'Add Payment', 'إضافة الدفع'),
(251, 'list_payment', 'List of payment', 'قائمة المدفوعات'),
(252, 'total_salary', 'Total Salary', 'إجمالي الرواتب'),
(253, 'total_working_minutes', 'Working Hour', 'إجمالي دقائق العمل'),
(254, 'payment_due', 'Payment Type', 'استحقاق الدفع'),
(255, 'payment_date', 'Date', 'تاريخ الدفع'),
(256, 'paid_by', 'Paid By', 'دفع بواسطة'),
(257, 'view_employee_payment', 'Employee Payment List', 'عرض مدفوعات الموظف'),
(258, 'sal_payment_type', 'Salary Payment Type', 'طريقة دفع الرواتب'),
(259, 'add_payment_type', 'Add Payment Type', 'إضافة نوع الدفع'),
(260, 'list_payment_type', 'List of Payment Type', 'قائمة نوع الدفع'),
(261, 'payment_period', 'Payment Period', 'فترة الدفع'),
(262, 'payment_type', 'Payment Type', 'نوع الدفع'),
(263, 'time', 'Punch Time', 'الوقت'),
(264, 'shift', 'Shift', 'المناوبة'),
(265, 'location', 'Location', 'الموقع'),
(266, 'logtype', 'Log Type', ''),
(267, 'branch', 'Location', 'الفرع'),
(268, 'student', 'Students', 'طالب'),
(269, 'csv', 'CSV', 'CSV'),
(270, 'save_successfull', 'Your Data Save Successfully', 'تم الحفظ بنجاح'),
(271, 'successfully_updated', 'Your Data Successfully Updated', 'تم التحديث بنجاح'),
(272, 'atn_form', 'Attendance Form', 'نموذج الحضور'),
(273, 'atn_report', 'Attendance Reports', ''),
(274, 'end_date', 'To', 'تاريخ الانتهاء'),
(275, 'start_date', 'From', 'تاريخ البدء'),
(276, 'done', 'Done', 'منجز'),
(277, 'employee_id_se', 'Write Employee Id or name here ', 'معرف الموظف '),
(278, 'attendance_repor', 'Attendance Report', ''),
(279, 'e_time', 'End Time', 'الساعة الإلكترونية'),
(280, 's_time', 'Start Time', ''),
(281, 'atn_datewiserer', 'Date Wise Report', ''),
(282, 'atn_report_id', 'Date And Id base Report', ''),
(283, 'atn_report_time', 'Date And Time report', ''),
(284, 'payroll', 'Payroll', 'كشف رواتب'),
(285, 'loan', 'Loan', 'السلف'),
(286, 'loan_grand', 'Grant Loan', 'ضمان السلفة'),
(287, 'add_loan', 'Add Loan', 'إضافة قرض'),
(288, 'loan_list', 'List of Loan', 'لائحة السلف'),
(289, 'loan_details', 'Loan Details', 'تفاصيل السلفة'),
(290, 'amount', 'Amount', 'المبلغ'),
(291, 'interest_rate', 'Interest Percentage', 'معدل الفائدة'),
(292, 'installment_period', 'Installment Period', 'فترة القسط'),
(293, 'repayment_amount', 'Repayment Total', 'قيمة التعويض'),
(294, 'date_of_approve', 'Approve Date', 'تاريخ الموافقة'),
(295, 'repayment_start_date', 'Repayment From', 'تاريخ بدء التعويض'),
(296, 'permission_by', 'Permitted By', 'إذن بواسطة'),
(297, 'grand', 'Grant', 'كبير'),
(298, 'installment', 'Installment', 'القسط'),
(299, 'loan_status', 'status', 'حالة السلفة'),
(300, 'installment_period_m', 'Installment Period in Month', 'فترة القسط'),
(301, 'successfully_inserted', 'Your loan Successfully Granted', 'تم إدخاله بنجاح'),
(302, 'loan_installment', 'Loan Installment', 'تقسيط السلفة'),
(303, 'add_installment', 'Add Installment', 'إضافة قسط'),
(304, 'installment_list', 'List of Installment', 'قائمة الأقساط'),
(305, 'loan_id', 'Loan No', 'رقم السلفة'),
(306, 'installment_amount', 'Installment Amount', 'قيمة القسط'),
(307, 'payment', 'Payment', 'دفع'),
(308, 'received_by', 'Receiver', 'استلمت من قبل'),
(309, 'installment_no', 'Install No', 'رقم القسط'),
(310, 'notes', 'Notes', 'ملاحظات'),
(311, 'paid', 'Paid', 'مدفوع'),
(312, 'loan_report', 'Loan Report', 'تقرير السلفة'),
(313, 'e_r_id', 'Enter Your Employee ID', ''),
(314, 'leave', 'Leave', 'الاجازة'),
(315, 'add_leave', 'Add Leave', 'إضافة إجازة'),
(316, 'list_leave', 'List of Leave', 'قائمة الاجازة'),
(317, 'dayname', 'Weekly Leave Day', 'اسم اليوم'),
(318, 'holiday', 'Holiday', 'يوم الاجازة'),
(319, 'list_holiday', 'List of Holidays', 'قائمة العطلة'),
(320, 'no_of_days', 'Number of Days', 'عدد الأيام'),
(321, 'holiday_name', 'Holiday Name', 'اسم العطلة'),
(322, 'set', 'SET', 'اعد'),
(323, 'tax', 'Tax', 'الضريبة'),
(324, 'tax_setup', 'Tax Setup', 'إعداد الضرائب'),
(325, 'add_tax_setup', 'Add Tax Setup', 'إضافة إعداد الضرائب'),
(326, 'list_tax_setup', 'List of Tax setup', ''),
(327, 'tax_collection', 'Tax collection', 'مجموعة الضرائب'),
(328, 'start_amount', 'Start Amount', 'بداية المبلغ'),
(329, 'end_amount', 'End Amount', 'المبلغ النهائي'),
(330, 'rate', 'Tax Rate', 'النسبة'),
(331, 'date_start', 'Date Start', 'تاريخ البدء'),
(332, 'amount_tax', 'Tax Amount', 'مبلغ الضريبة'),
(333, 'collection_by', 'Collection By', 'جمع بواسطة'),
(334, 'date_end', 'Date End', 'تاريخ الانتهاء'),
(335, 'income_net_period', 'Income  Net period', 'صافي الايرادات'),
(336, 'default_amount', 'Default Amount', 'المبلغ الافتراضي'),
(337, 'add_sal_type', 'Add Salary Type', 'إضافة نوع الراتب '),
(338, 'list_sal_type', 'Salary Type List', ''),
(339, 'salary_type_setup', 'Salary Type Setup', 'إنشاء نوع الراتب'),
(340, 'salary_setup', 'Salary SetUp', 'إعداد الراتب'),
(341, 'add_sal_setup', 'Add Salary Setup', 'إضافة إعدادات الرواتب'),
(342, 'list_sal_setup', 'Salary Setup List', ''),
(343, 'salary_type_id', 'Salary Type', 'معرف نوع الراتب'),
(344, 'salary_generate', 'Salary Generate', 'إنشاء الراتب'),
(345, 'add_sal_generate', 'Generate Now', 'إضافة مخصص الرواتب '),
(346, 'list_sal_generate', 'Generated Salary List', ''),
(347, 'gdate', 'Generate Date', ''),
(348, 'start_dates', 'Start Date', 'تواريخ البدء'),
(349, 'generate', 'Generate ', 'انشاء'),
(350, 'successfully_saved_saletup', ' Set up Successfull', 'تم حفظ الاعدادات بنجاح'),
(351, 's_date', 'Start Date', ''),
(352, 'e_date', 'End Date', ''),
(353, 'salary_payable', 'Payable Salary', 'الراتب المستحق للدفع'),
(354, 'tax_manager', 'Tax', 'مدير الضرائب'),
(355, 'generate_by', 'Generate By', 'انشاء بواسطة'),
(356, 'successfully_paid', 'Successfully Paid', 'تم الدفع بنجاح'),
(357, 'direct_empl', ' Employee', 'مباشرة الموظيف'),
(358, 'add_emp_info', 'Add New Employee', 'إضافة معلومات الموظف'),
(359, 'new_empl_pos', 'Add New Employee Position', ''),
(360, 'manage', 'Manage Position', 'تدبير'),
(361, 'ad_advertisement', 'ADD POSITION', 'إعلان الاعلانات'),
(362, 'moduless', 'Modules', 'الموديول'),
(363, 'next', 'Next', 'التالي'),
(364, 'finish', 'Finish', 'ينهي'),
(365, 'request', 'Request', 'طلب'),
(366, 'successfully_saved', 'Your Data Successfully Saved', 'تم الحفظ بنجاح'),
(367, 'sal_type', 'Salary Type', 'نوع الراتب'),
(368, 'sal_name', 'Salary Name', 'مسمى الراتب'),
(369, 'leave_application', 'Leave Application', 'طلب الاجازة'),
(370, 'apply_strt_date', 'Application Start Date', 'تطبيق تاريخ البداية'),
(371, 'apply_end_date', 'Application End date', 'تطبيق تاريخ الانتهاء'),
(372, 'leave_aprv_strt_date', 'Approve Start Date', 'بداية الاجازة'),
(373, 'leave_aprv_end_date', 'Approved End Date', 'نهاية الاجازة '),
(374, 'num_aprv_day', 'Aproved Day', ''),
(375, 'reason', 'Reason', 'السبب'),
(376, 'approve_date', 'Approved Date', 'تاريخ الموافقة '),
(377, 'leave_type', 'Leave Type', 'نوع الاجازة'),
(378, 'apply_hard_copy', 'Application Hard Copy', 'تطبيق نسخة ورقية'),
(379, 'approved_by', 'Approved By', 'تمت الموافقة عليه من قبل'),
(380, 'notice', 'Notice Board', 'إشعار'),
(381, 'noticeboard', 'Notice Board', 'لوح الإعلانات'),
(382, 'notice_descriptiion', 'Description', 'وصف الإشعار'),
(383, 'notice_date', 'Notice Date', 'تاريخ الإشعار'),
(384, 'notice_type', 'Notice Type', 'نوع الإشعار'),
(385, 'notice_by', 'Notice By', 'إشعار من قبل'),
(386, 'notice_attachment', 'Attachment', 'إشعار مرفق'),
(387, 'account_name', 'Account Name', 'اسم الحساب'),
(388, 'account_type', 'Account Type', 'نوع الحساب'),
(389, 'account_id', 'Account Name', 'معرف الحساب'),
(390, 'transaction_description', 'Description', 'وصف العملية'),
(391, 'payment_id', 'Payment', 'معرف الدفع'),
(392, 'create_by_id', 'Created By', 'تم إنشاؤه في'),
(393, 'account', 'Account', 'الحساب'),
(394, 'account_add', 'Add Account', 'إضافة حساب'),
(395, 'account_transaction', 'Transaction', 'عمليات الحساب'),
(396, 'award', 'Award', 'المنح'),
(397, 'new_award', 'New Award', 'مكافئة جديدة'),
(398, 'award_name', 'Award Name', 'اسم المنحه'),
(399, 'aw_description', 'Award Description', 'وصف المنحة'),
(400, 'awr_gift_item', 'Gift Item', 'نوع المنحه المقدمة'),
(401, 'awarded_by', 'Award By', 'منحت _من خلال'),
(402, 'employee_name', 'Employee Name', 'اسم الموظف'),
(403, 'employee_list', 'Atn List', 'قائمة الموظفين'),
(404, 'department', 'Department', 'الادارة'),
(405, 'department_name', 'Department Name ', 'اسم الادارة'),
(406, 'clockout', 'ClockOut', 'على مدار الساعة '),
(407, 'se_account_id', 'Select Account Name', 'اعداد رقم الحساب'),
(408, 'division', 'Division', 'القسم'),
(409, 'add_division', 'Add Division', 'إضافة تقسيم'),
(410, 'update_division', 'Update Division', 'تحديث التقسيم'),
(411, 'division_name', 'Division Name', 'اسم القسم'),
(412, 'division_list', 'Manage Division ', 'قائمة الاقسام'),
(413, 'designation_list', 'Position List', 'قائمة الاستقالات'),
(414, 'manage_designation', 'Manage Position', 'تعيين مدير'),
(415, 'add_designation', 'Add Position', 'إضافة تسمية'),
(416, 'select_division', 'Select Division', 'اختر التقسيم'),
(417, 'select_designation', 'Select Position', 'حدد التعيين'),
(418, 'asset', 'Asset', 'أصول'),
(419, 'asset_type', 'Asset Type', 'نوع الأصول'),
(420, 'add_type', 'Add Type', 'إضافة نوع'),
(421, 'type_list', 'Type List', 'القائمة'),
(422, 'type_name', 'Type Name', 'الاسم'),
(423, 'select_type', 'Select Type', 'اختر نوع'),
(424, 'equipment_name', 'Equipment Name', 'اسم الجهاز'),
(425, 'model', 'Model', 'نموذج'),
(426, 'serial_no', 'Serial No', 'الرقم التسلسلي'),
(427, 'equipment', 'Equipment', 'معدات'),
(428, 'add_equipment', 'Add Equipment', 'إضافة المعدات'),
(429, 'equipment_list', 'Equipment List', 'قائمة المعدات'),
(430, 'type', 'Type', 'نوع'),
(431, 'equipment_maping', 'Equipment Mapping', 'رسم خرائط المعدات'),
(432, 'add_maping', 'Add Mapping', 'إضافة الخرائط'),
(433, 'maping_list', 'Mapping List', ''),
(434, 'update_equipment', 'Update Equipment', 'تحديث المعدات'),
(435, 'select_employee', 'Select Employee', 'اختر الموظف'),
(436, 'select_equipment', 'Select Equipment', 'اختر المعدات'),
(437, 'basic_info', 'Basic Info', 'معلومات أساسية'),
(438, 'middle_name', 'Middle Name', 'الاسم الوسطى'),
(439, 'state', 'Country', 'الحالة'),
(440, 'city', 'City', 'المدينة'),
(441, 'zip_code', 'Zip Code', 'الرمز البريدي'),
(442, 'maiden_name', 'Maiden Name', 'الاسم قبل الزواج'),
(443, 'add_employee', 'Add Employee', 'إضافة موظف'),
(444, 'manage_employee', 'Manage Employee', 'إدارة الموظف'),
(445, 'employee_update_form', 'Employee Update Form', 'استمارة تحديث الموظف'),
(446, 'what_you_search', 'What You Search', 'ماذا تبحث عنه'),
(447, 'search', 'Search', 'بحث'),
(448, 'duty_type', 'Duty Type', 'نوع المهام'),
(449, 'hire_date', 'Hire Date', 'بداية مباشرة العمل'),
(450, 'original_h_date', 'Original Hire Date', 'تاريخ الاجازات الرسمية'),
(451, 'voluntary_termination', 'Voluntary Termination', 'الفصل من العمل'),
(452, 'termination_reason', 'Termination Reason', 'سبب الفصل'),
(453, 'termination_date', 'Termination Date', 'تاريخ الفصل'),
(454, 're_hire_date', 'Re Hire Date', 'تاريخ اعادة التوظيف'),
(455, 'rate_type', 'Rate Type', 'نوع النسبة'),
(456, 'pay_frequency', 'Pay Frequency', 'دفع بشكل متكرر'),
(457, 'pay_frequency_txt', 'Pay Frequency Text', ''),
(458, 'hourly_rate2', 'Hourly rate2', 'معدل الساعة 2'),
(459, 'hourly_rate3', 'Hourly Rate3', 'معدل الساعة 3'),
(460, 'home_department', 'Home Department', 'قسم المنزل'),
(461, 'department_text', 'Department Text', 'نص الادارة'),
(462, 'benifit_class_code', 'Benefit Class code', 'رمز فئة المزايا'),
(463, 'benifit_desc', 'Benefit Description', 'وصف الميزة'),
(464, 'benifit_acc_date', 'Benefit Accrual Date', 'تاريخ حساب المميزات'),
(465, 'benifit_sta', 'Benefit Status', 'حالة الميزة'),
(466, 'super_visor_name', 'Supervisor Name', 'اسم المشرف'),
(467, 'is_super_visor', 'Is Supervisor', ''),
(468, 'supervisor_report', 'Supervisor Report', 'تقرير المشرف'),
(469, 'dob', 'Date of Birth', ''),
(470, 'gender', 'Gender', 'جنس'),
(471, 'marital_stats', 'Marital Status', ''),
(472, 'ethnic_group', 'Ethnic Group', 'مجموعة عرقية'),
(473, 'eeo_class_gp', 'EEO Class', ''),
(474, 'ssn', 'SSN', 'رقم الهوية الوطنية'),
(475, 'work_in_state', 'Work in State', 'العمل داخل المدينة'),
(476, 'live_in_state', 'Live in State', 'العيش في الدولة'),
(477, 'home_email', 'Home Email', 'البريد الإلكتروني للمنزل'),
(478, 'business_email', 'Business Email', 'البريد الالكتروني للعمل'),
(479, 'home_phone', 'Home Phone', 'هاتف المنزل'),
(480, 'business_phone', 'Business Phone', 'رقم العمل '),
(481, 'cell_phone', 'Cell Phone', 'رقم الجوال'),
(482, 'emerg_contct', 'Emergency Contact', 'معلومات الحالات الطارئة'),
(483, 'emerg_home_phone', 'Emergency Home Phone', 'هاتف المنزل للطوارئ'),
(484, 'emrg_w_phone', 'Emergency Work Phone', ''),
(485, 'emer_con_rela', 'Emergency Contact Relation', 'معلومات شخص للطوارئ'),
(486, 'alt_em_contct', 'Alter Emergency Contact', 'الرقم البديل في حالات الطوارئ'),
(487, 'alt_emg_h_phone', 'Alt Emergency Home Phone', 'الرقم البديل للمنزل في حالات الطوارئ'),
(488, 'alt_emg_w_phone', 'Alt Emergency  Work Phone', 'الرقم البديل للعمل في حالات الطوارئ'),
(489, 'reports', 'Reports', 'التقارير'),
(490, 'employee_reports', 'Employee Reports', 'تقارير الموظف'),
(491, 'demographic_report', 'Demographic Report', 'تقرير ديموغرافي'),
(492, 'posting_report', 'Positional Report', 'تقرير التعيين'),
(493, 'custom_report', 'Custom Report', 'تقرير مخصص'),
(494, 'benifit_report', 'Benefit Report', 'تقرير المميزات'),
(495, 'demographic_info', 'Demographical Information', 'المعلومات الديموغرافية'),
(496, 'positional_info', 'Positional Information', 'معلومات الوظيفة'),
(497, 'assets_info', 'Assets Information', 'معلومات الأصول'),
(498, 'custom_field', 'Custom Field', 'حقل مخصص'),
(499, 'custom_value', 'Custom Data', 'قيمة التخصيص'),
(500, 'adhoc_report', 'Adhoc Report', ''),
(501, 'asset_assignment', 'Asset Assignment', 'مخصصات الأصول'),
(502, 'assign_asset', 'Assign Assets', 'تخصيص الأصول'),
(503, 'assign_list', 'Assign List', 'قائمة التخصيص'),
(504, 'update_assign', 'Update Assign', 'تخصيص التحديث'),
(505, 'citizenship', 'Citizenship', 'الجنسية'),
(506, 'class_sta', 'Class status', 'حالة السلم الوظيفي'),
(507, 'class_acc_date', 'Class Accrual date', 'تاريخ السلم الوظيفي'),
(508, 'class_descript', 'Class Description', 'وصف السلم الوظيفي'),
(509, 'class_code', 'Class Code', 'كود السلم الوظيفي'),
(510, 'return_asset', 'Return Assets', 'إرجاع الأصول'),
(511, 'dept_id', 'Department ID', 'معرف الادارة'),
(512, 'parent_id', 'Parent ID', 'معرف الأصل'),
(513, 'equipment_id', 'Equipment ID', 'معرف المعدات'),
(514, 'issue_date', 'Issue Date', 'تاريخ الإصدار'),
(515, 'damarage_desc', 'Damarage Description', 'وصف الضرر'),
(516, 'return_date', 'Return Date', 'تاريخ الإرجاع'),
(517, 'is_assign', 'Is Assign', 'تعيين'),
(518, 'emp_his_id', 'Employee History ID', ''),
(519, 'damarage_descript', 'Damage Description', 'وصف الضرر'),
(520, 'return', 'Return', 'إرجاع'),
(521, 'return_successfull', 'Return Successfull', 'تم الإرجاع بنجاح'),
(522, 'return_list', 'Return List', 'قائمة الإرجاع'),
(523, 'custom_data', 'Custom Data', 'البيانات المخصصة'),
(524, 'passing_year', 'Passing Year', 'مرور عام'),
(525, 'is_admin', 'Is Admin', 'هل هو مسؤول '),
(526, 'zip', 'Zip Code', 'ارشيف'),
(527, 'original_hire_date', 'Original Hire Date', 'تاريخ التوظيف الأساسي'),
(528, 'rehire_date', 'Rehire Date', 'تاريخ إعادة التوظيف'),
(529, 'class_code_desc', 'Class Code Description', 'وصف رمز السلم الوظيفة'),
(530, 'class_status', 'Class Status', 'حالة السلم الوظيفي'),
(531, 'super_visor_id', 'Supervisor ID', 'هوية المشرف'),
(532, 'marital_status', 'Marital Status', 'الحالة الاجتماعية'),
(533, 'emrg_h_phone', 'Emergency Home Phone', ''),
(534, 'emgr_contct_relation', 'Emergency Contact Relation', 'رقم جوال شخص للطوارئ'),
(535, 'id', 'ID', 'رقم الهوية'),
(536, 'type_id', 'Equipment Type', 'نوع الهوية'),
(537, 'custom_id', 'Custom ID', 'معرف مخصص'),
(538, 'custom_data_type', 'Custom Data Type', 'نوع البيانات المخصصة'),
(539, 'role_permission', 'Role Permission', 'إذن الصلاحية'),
(540, 'permission_setup', 'Permission Setup', 'إعداد الإذن'),
(541, 'add_role', 'Add Role', 'إضافة دور'),
(542, 'role_list', 'Role List', 'قائمة الصلاحيات'),
(543, 'user_access_role', 'User Access Role', 'صلاحيات المستخدم'),
(544, 'menu_item_list', 'Menu Item List', 'قائمة عناصر القائمة'),
(545, 'ins_menu_for_application', 'Ins Menu  For Application', 'القائمة الإضافية للتطبيق'),
(546, 'menu_title', 'Menu Title', 'عنوان القائمة'),
(547, 'page_url', 'Page Url', 'رابط الصفحة'),
(548, 'parent_menu', 'Parent Menu', 'قائمة الأصل'),
(549, 'role', 'Role', 'الصلاحية'),
(550, 'role_name', 'Role Name', 'اسم الصلاحية'),
(551, 'single_checkin', 'Single Check In', 'تسجيل دخول واحد'),
(552, 'bulk_checkin', 'Bulk Check In', 'تسجيل دخول جماعي'),
(553, 'manage_attendance', 'Manage Attendance', 'إدارة الحضور'),
(554, 'attendance_list', 'Attendance List', 'قائمة الحضور'),
(555, 'checkin', 'Check In', 'الدخول'),
(556, 'checkout', 'Check Out', 'الخروج'),
(557, 'stay', 'Stay', 'الجلوس'),
(558, 'attendance_report', 'Attendance Report', 'تقرير الحضور'),
(559, 'work_hour', 'Work Hour', 'ساعة العمل'),
(560, 'cancel', 'Cancel', 'إلغاء'),
(561, 'confirm_clock', 'Confirm Checkout', 'تأكيد الوقت'),
(562, 'add_attendance', 'Add Attendance', 'إضافة الحضور'),
(563, 'upload_csv', 'Upload CSV', 'تحميل الملف'),
(564, 'import_attendance', 'Import Attendance', 'حضور الاستيراد'),
(565, 'manage_account', 'Manage Account', 'إدارة الحساب'),
(566, 'add_account', 'Add Account', 'إضافة حساب'),
(567, 'add_new_account', 'Add New Account', 'إضافة حساب جديد'),
(568, 'account_details', 'Account Details', 'تفاصيل الحساب'),
(569, 'manage_transaction', 'Manage Transaction', 'إدارة المعاملات'),
(570, 'add_expence', 'Add Experience', 'إضافة مصاريف'),
(571, 'add_income', 'Add Income', 'إضافة الدخل'),
(572, 'return_now', 'Return Now !!', 'الإرجاع الآن'),
(573, 'manage_award', 'Manage Award', 'إدارة الجائزة'),
(574, 'add_new_award', 'Add New Award', 'إضافة مكافئة جديدة'),
(575, 'personal_information', 'Personal Information', 'معلومات شخصية'),
(576, 'educational_information', 'Educational Information', 'معلومات التعليم'),
(577, 'past_experience', 'Past Experience', 'الخبرة السابقة'),
(578, 'basic_information', 'Basic Information', 'معلومات أساسية'),
(579, 'result', 'Result', 'النتيجة'),
(580, 'institute_name', 'Institute Name', 'اسم المؤسسة'),
(581, 'education', 'Education', 'التعليم'),
(582, 'manage_shortlist', 'Manage Short List', 'إدارة القائمة المختصرة'),
(583, 'manage_interview', 'Manage Interview', 'إدارة المقابلة'),
(584, 'manage_selection', 'Manage Selection', 'إدارة الاختيار'),
(585, 'add_new_dept', 'Add New Department', 'إضافة ديون جديدة'),
(586, 'manage_dept', 'Manage Department', 'إدارة الادارة'),
(587, 'successfully_checkout', 'Checkout Successful !', 'تم الدفع بنجاح'),
(588, 'grant_loan', 'Grant Loan', 'قرض منحة'),
(589, 'successfully_installed', 'Successfully Installed', 'تم التثبيت بنجاح'),
(590, 'total_loan', 'Total Loan', 'إجمالي القروض'),
(591, 'total_amount', 'Total Amount', 'إجمالي المبلغ'),
(592, 'filter', 'Filter', 'منقي'),
(593, 'weekly_holiday', 'Weekly Holiday', 'إجازة أسبوعية'),
(594, 'manage_application', 'Manage Application', 'إدارة الطلب'),
(595, 'add_application', 'Add Application', 'إضافة تطبيق'),
(596, 'manage_holiday', 'Manage Holiday', 'إدارة العطلة'),
(597, 'add_more_holiday', 'Add More Holiday', 'إضافة إجازة'),
(598, 'manage_weekly_holiday', 'Manage Weekly Holiday', 'إدارة العطلة الأسبوعية'),
(599, 'add_weekly_holiday', 'Add Weekly Holiday', 'إضافة عطلة أسبوعية'),
(600, 'manage_granted_loan', 'Manage Granted Loan', 'إدارة القرض الممنوح'),
(601, 'manage_installment', 'Manage Installment', 'إدارة التقسيط'),
(602, 'add_new_notice', 'Add New Notice', 'إضافة إشعار جديد'),
(603, 'manage_notice', 'Manage Notice', 'إدارة الإشعار'),
(604, 'salary_type', 'Salary Benefits', 'نوع الراتب'),
(605, 'manage_salary_generate', 'Manage Salary Generate', 'إدارة الراتب'),
(606, 'generate_now', 'Generate Now', 'انشاء الان '),
(607, 'add_salary_setup', 'Add Salary Setup', 'إضافة إعداد الراتب'),
(608, 'manage_salary_setup', 'Manage Salary Setup', 'إدارة اعدادات الرواتب'),
(609, 'add_salary_type', 'Add Salary Benefits', 'إضافة نوع الراتب'),
(610, 'manage_salary_type', 'Manage Salary Benefits', 'نوع الراتب'),
(611, 'manage_tax_setup', 'Manage Tax Setup', 'إدارة إعداد الضرائب'),
(612, 'setup_tax', 'Setup Tax', 'إنشاء ضريبة'),
(613, 'add_more', 'Add More', 'إضافة المزيد'),
(614, 'tax_rate', 'Tax Rate', 'معدل الضريبة'),
(615, 'no', 'No', 'لا'),
(616, 'setup', 'Setup', 'إنشاء'),
(617, 'biographicalinfo', 'Bio-Graphical Information', 'معلومات السيرة الذاتية'),
(618, 'positional_information', 'Positional Information', 'معلومات الوظيفة'),
(620, 'benifits', 'Benefits', 'المميزات'),
(621, 's_rate', 'Rate', ''),
(622, 'others_leave_application', 'Leave Application', 'انواع إجازات آخرى'),
(623, 'add_leave_type', 'Add Leave Type', 'إضافة نوع إجازة'),
(624, 'others_leave', 'Others Leave', 'يغادر الآخرون'),
(625, 'number_of_leave_days', 'Number of Leave Days', 'عدد أيام الإجازة'),
(626, 'app_date', 'Application Date', 'تاريخ طلب التوظيف'),
(627, 'apply_day', 'Apply Day', 'تطبيق اليوم'),
(628, 'time_zone', 'Time Zone ', 'المنطقة'),
(629, 'accounts', 'Accounts', 'الحسابات'),
(630, 'c_o_a', 'Chart of Account', 'شجرة الحسابات'),
(631, 'debit_voucher', 'Debit Voucher', 'سند المدين'),
(632, 'credit_voucher', 'Credit Voucher', 'سند الدائن'),
(633, 'contra_voucher', 'Contra Voucher', 'قسيمة كونترا'),
(634, 'journal_voucher', 'Journal Voucher', 'دفتر  اليومية'),
(635, 'voucher_approval', 'Voucher Approval', 'الموافقة على السند'),
(636, 'account_report', 'Account Report', 'تقرير الحساب'),
(637, 'voucher_report', 'Voucher Report', 'تقرير السندات'),
(638, 'cash_book', 'Cash Book', 'كتاب النقدية'),
(639, 'bank_book', 'Bank Book', 'تقدير البنك'),
(640, 'general_ledger', 'General Ledger', 'دفتر الأستاذ العام'),
(641, 'trial_balance', 'Trial Balance', 'ميزان المراجعة'),
(642, 'profit_loss', 'Profit Loss', 'ربح خسارة'),
(643, 'cash_flow', 'Cash Flow', 'نقد متدفق'),
(644, 'coa_print', 'Coa Print', 'طباعة الكود'),
(645, 'grant', 'Grant', 'منحة'),
(646, 'confirm', 'Confirm', 'التأكيد'),
(647, 'pay_now', 'Pay Now ??', 'إدفع الآن'),
(648, 'find', 'Find', 'تجد'),
(649, 'gl_head', 'GL Head', ''),
(650, 'acc_code', 'Account Code', 'كود الحساب'),
(651, 'from_date', 'From Date', 'من التاريخ'),
(652, 'to_date', 'To Date', 'حتى تاريخ'),
(653, 'bank_book_voucher', 'Bank Book Voucher', 'السندات البنكية'),
(654, 'bank_book_report_of', 'Bank Book Report Of', 'تقرير البنك المصرفي'),
(655, 'on', 'On', 'على'),
(656, 'to', 'To', 'إلى'),
(657, 'opening_balance', 'Opening Balance', 'الرصيد الإفتتاحي'),
(658, 'balance', 'Balance', 'الرصيد'),
(659, 'credit', 'Credit', 'الدائن'),
(660, 'debit', 'Debit', 'المدين'),
(661, 'head_of_account', 'Head Of Account', 'رئيس الحساب'),
(662, 'voucher_type', 'Voucher Type', 'نوع السندات'),
(663, 'voucher_no', 'Voucher No', 'رقم السند'),
(664, 'transaction_date', 'Transaction Date', 'تاريخ العملية'),
(665, 'cash_book_voucher', 'Cash Book Voucher', 'قسيمة دفتر النقدية'),
(666, 'cash_book_report_on', 'Cash Book Report On', 'تقرير دفتر النقدية على'),
(667, 'particulars', 'Particulars', 'التفاصيل'),
(668, 'amount_in_dollar', 'Amount In Dollar', 'المبلغ بالدولار'),
(669, 'opening_cash_and_equivalent', 'Opening Cash & Equivalent', 'فتح النقدية وما يعادلها'),
(670, 'cash_flow_statement', 'Cash Flow Statement', 'بيان التدفقات النقدية'),
(671, 'code', 'Code', 'الكود'),
(672, 'remark', 'Remark', 'ملاحظة'),
(673, 'debit_account_head', 'Debit Account Head', 'حساب المدين'),
(674, 'cash_in_hand', 'Cash In Hand', 'نقد في اليد'),
(675, 'credit_account_head', 'Credit Account Head', 'رئيس حساب الدائن'),
(676, 'transaction_head', 'Transaction Head', 'بداية العملية'),
(677, 'with_details', 'With Details', 'مع التفاصيل'),
(678, 'no_report', 'No Of Report', 'لا يوجد تقرير'),
(679, 'total', 'Total', 'الإجمالي'),
(680, 'current_balance', 'Current Balance', 'الرصيد الحالي'),
(681, 'pre_balance', 'Pre Balance', 'التوازن المسبق'),
(682, 'trial_balance_with_opening_as_on', 'Trial Balance With Opening ', 'ميزان المراجعة مع الافتتاح حتى'),
(683, 'as_on', 'As On', ''),
(684, 'chairman', 'Chairman', 'الرئيس'),
(685, 'prepared_by', 'Prepared By', 'أعدت بواسطة'),
(686, 'statement_of_comprehensive_income', 'Statement Of Comprehensive Income', 'كشف الحساب الشامل'),
(687, 'from', 'From', 'من عند'),
(688, 'total_expenses', 'Total Expenses', 'إجمالي المصروفات'),
(689, 'total_income', 'Total Income', 'إجمالي الدخل'),
(690, 'authorized_signature', 'Authorize Signature', 'اعتماد التوقيع'),
(691, 'account_official', 'Account Official', 'مسؤول الحساب'),
(692, 'approved', 'Approved', ' تمت الموافقة'),
(693, 'update_credit_voucher', 'Update Credit Voucher', 'تحديث مستند الائتمان'),
(694, 'benefits', 'Benefit', 'المميزات'),
(695, 'class', 'Class', 'السلم الوظيفي'),
(696, 'biographical_info', 'Biographical Info', 'معلومات السيرة الذاتية'),
(697, 'additional_address', 'Additional Address', 'عنوان اضافي'),
(698, 'custom', 'Custom', 'العادة'),
(699, 'can_name', 'Candidate Name', 'يمكن تسمية'),
(700, 'select', 'Select', 'تحديد'),
(701, 'benefit_type', 'Benefit Type', 'نوع المزايا'),
(702, 'salary_benefits_type', 'Benefits Type', 'نوع مزايا الراتب'),
(703, 'addition', 'Addition', 'الاضافات'),
(704, 'basic', 'Basic', 'أساسي'),
(705, 'deduction', 'Deduction', 'الخصومات'),
(706, 'gross_salary', 'Gross Salary', 'الراتب الكلى'),
(707, 'total_loan_amount', 'Total Loan Amount', 'إجمالي قيمة القروض'),
(708, 'loan_no', 'Loan No', 'رقم السلفة'),
(709, 'loan_issue_id', 'Loan Issue Id', 'معرف إصدار السلفة'),
(710, 'repayment', 'Repayment', 'تعويض'),
(711, 'candidate_name', 'Candidate name', 'اسم المرشح'),
(712, 'employee_performance', 'Employee Performance', 'أداء الموظفين'),
(713, 'check_in', 'Check In', 'الدخول'),
(714, 'check_out', 'Check Out', 'الخروج'),
(715, 'datewise_report', 'Date Wise Report', 'تاريخ التقرير '),
(716, 'employee_wise_report', 'Employee Wise Report', 'تقرير الموظف '),
(717, 'date_in_time_report', 'Date & In Time Report', 'التاريخ في تقرير الوقت'),
(718, 'report_view', 'Report View', 'عرض التقرير'),
(719, 'notice_form', 'Notice Form', 'شكل الإشعار'),
(720, 'atn_log', 'Load Device Data', ''),
(721, 'atn_log_datewise', 'Attendance Log', ''),
(722, 'device_connection', 'Device Connection', 'اتصال الجهاز'),
(723, 'user_name', 'User Name', 'اسم المستخدم'),
(724, 'in_time', 'In Time', 'في الوقت'),
(725, 'out_time', 'Out Time', 'وقت الخروج'),
(726, 'worked_hours', 'Worked Hours', 'ساعات عمل'),
(727, 'wasteg_hour', 'Wastage Hours', 'الساعة المهدرة'),
(728, 'net_hour', 'Net Hours', 'الساعة الصافية'),
(729, 'device_information', 'Device Information', 'معلومات الجهاز'),
(730, 'plz_generate_an_ip', 'Please Generate an Ip', ''),
(731, 'device_name', 'Device Name', 'اسم الجهاز'),
(732, 'device_ip', 'Device Ip', 'جهاز IP'),
(733, 'device_user', 'Device User', 'مستخدم الجهاز'),
(734, 'n_b_spendtime', 'N.B : You Spent', ''),
(735, 'hours_out_of_workinghour', 'Hours out of Working hours', 'ساعات خارج ساعة العمل'),
(736, 'total_employee', 'Total Employee', 'إجمالي الموظفين'),
(737, 'present_employee', 'Present Employee', 'الموظف الحالي'),
(738, 'today_worked_hour', 'Today\'s Worked Hours', 'ايام ساعات العمل'),
(739, 'todays_transaction', 'Today\'s Transaction', 'ايام العمليات'),
(740, 'device_model', 'Device Model', 'نوع الجهاز'),
(741, 'download_sample_file', 'Download Sample File', 'تنزيل ملف '),
(742, 'salar_month', 'Salary Month', 'راتب شهر'),
(743, 'bank', 'Bank', 'البنك'),
(744, 'add_bank', 'Add Bank', 'إضافة بنك'),
(745, 'bank_list', 'Bank List', 'قائمة البنوك'),
(746, 'update_bank', 'Update Bank', 'تحديث البنك'),
(747, 'bank_name', 'Bank Name', 'اسم البنك'),
(748, 'account_number', 'Account Number', 'رقم الحساب'),
(749, 'cash_adjustment', 'Cash Adjustment', 'التعديل النقدي'),
(750, 'adjustment_type', 'Adjustment Type', 'نوع التعديل'),
(751, 'bank_adjustment', 'Bank Adjustment', 'تسوية البنك'),
(752, 'expense', 'Expense', 'مصروف'),
(753, 'expense_item', 'Expense Item', 'بند النفقات'),
(754, 'expense_statement', 'Expense Statement', 'بيان المصروفات'),
(755, 'expense_name', 'Expense Name', 'اسم المصاريف'),
(756, 'add_expense', 'Add Expense', 'إضافة مصروف'),
(757, 'print', 'Print', 'طباعة'),
(758, 'income', 'Income', 'الإيرادات'),
(759, 'income_field', 'Income Field', 'مجال الايرادات'),
(760, 'update_income', 'Update Income', 'تحديث الدخل'),
(761, 'income_statement', 'Income Statement', 'قائمة الدخل'),
(762, 'attendence', 'Attendance', 'الحضور'),
(763, 'working_day', 'Working Day', 'يوم عمل'),
(764, 'salary_month', 'Salary Month', 'راتب شهر'),
(765, 'salary_slip', 'Salary Slip', 'وصل الراتب'),
(766, 'head_code', 'Head Code', 'رمز الرئيسي'),
(767, 'particular', 'Particulars', 'معين'),
(768, 'parent_type', 'Parent Type', 'نوع الأصل'),
(769, 'expense_sheet', 'Expense Sheet', 'ورقة المصروفات'),
(770, 'head_name', 'Head Name', 'اسم الرئيس'),
(771, 'income_sheet', 'Income Sheet', 'جدول الايرادات'),
(772, 'recruitment', ' Recruitment', 'التوظيف'),
(773, 'ref_number', 'Reference Number', 'رقم المرجع '),
(774, 'employee_signature', 'Employee Signature', 'توقيع الموظف'),
(775, 'name_of_bank', 'Name Of Bank', 'اسم البنك'),
(776, 'net_salary', 'Net Salary', 'صافي الراتب'),
(777, 'in_word', 'In Word', 'في كلمة'),
(778, 'total_deduction', 'Total Deduction', 'إجمالي الخصم'),
(779, 'total_addition', 'Total Addition', 'مجموع الاضافات'),
(780, 'basic_salary', 'Basic Salary', 'الراتب الاساسي'),
(781, 'earnings', 'Earnings', 'المبلغ المستلم'),
(782, 'salary_date', 'Salary Date', 'تاريخ الراتب'),
(783, 'money_receipt', 'Money Receipt', 'استلام الأموال'),
(784, 'balance_adjustment', 'Balance Adjustment', 'تعديل الرصيد'),
(785, 'parent_head', 'Parent Head', 'رأس الأصل'),
(786, 'child_head', 'Child Head', 'عدد الاطفال'),
(787, 'due_amount', 'Due Amount', 'المبلغ المستحق'),
(788, 'loan_payment', 'Loan Payment', 'دفع السلفة'),
(789, 'todays_notice', 'Today\'s Notice', 'ايام الانذارات'),
(790, 'attend_employee', 'Attend Employee', 'حضور الموظف'),
(791, 'department_wise', 'Department Wise', 'الادارة'),
(792, 'income_expense', 'Income Expense', 'مصاريف الايرادات'),
(793, 'todays_leave', 'Today\'s Leave', 'ايام الاجازات'),
(794, 'leave_day', 'Leave Day', 'يوم اجازه '),
(795, 'leave_finish', 'Leave Finish', 'انتهاء الاجازه'),
(796, 'loan_amount', 'Loan Amount', 'مبلغ السلف'),
(797, 'leave_employee', 'Leave Employee', 'إجازة الموظف'),
(798, 'absent_employee', 'Absent Employee', 'موظف غائب'),
(799, 'worked_hour', 'Worked Hours', 'ساعة عمل'),
(800, 'new_password', 'New Password', 'كلمة المرور الجديدة'),
(801, 'latitude', 'Latitude', 'خط العرض'),
(802, 'longitude', 'Longitude', 'خط الطول'),
(803, 'acceptablerange', 'Acceptable Range', 'نطاق مقبول'),
(804, 'googleapi_authkey', 'Google Api Auth Key', ''),
(805, 'approve', 'Approve', 'يوافق'),
(806, 'decline', 'Decline', 'مرفوض'),
(807, 'attendance_history', 'Attendance History', 'سجل الحضور'),
(808, 'give_attendance', 'Give Attendance', 'إعطاء الحضور'),
(809, 'ledger_history', 'Ledger History', 'سجل دفتر الأستاذ'),
(810, 'request_leave', 'Request Leave', 'طلب إجازة'),
(811, 'my_profile', 'My Profile', 'ملفي'),
(812, 'salary_statement', 'Salary Statement', 'حالة الراتب'),
(813, 'notices', 'Notice', 'إشعارات'),
(814, 'working_hour', 'Working Hour', 'ساعة العمل'),
(815, 'qr_attendance', 'QR Attendance', 'باركود الحضور'),
(816, 'leave_remaining', 'Leave Remaining', 'الاجازه المتبقية'),
(817, 'total_attendance', 'Total Attendance', 'إجمالي الحضور'),
(818, 'day_absent', 'Day Absent', 'يوم الغياب'),
(819, 'day_present', 'Day Present', 'اليوم الحضور'),
(820, 'previous', 'Previous', 'السابق'),
(821, 'network_alert', 'Check Network Connection', 'تنبيه الشبكة'),
(822, 'select_date', 'Select Date', 'حدد التاريخ'),
(823, 'attendance_log', 'Attendance Log', ''),
(824, 'in', 'In', 'في'),
(825, 'out', 'Out', 'خارج'),
(826, 'load_more', 'Load More', 'تحميل المزيد'),
(827, 'data_not_found', 'Data Not Found', 'لم يتم العثور على بيانات'),
(828, 'worked', 'Worked', 'عمل'),
(829, 'wastage', 'Wastage', 'مهدر'),
(830, 'punch_time', 'Punch Time', ''),
(831, 'loading', 'Loading ...', 'جار التحميل'),
(832, 'wrong_info_alert', 'Some Information Was Wrong There', 'تنبيه معلومات خاطئة'),
(833, 'from_to_date_alrt', 'From And To Date Field Are Require', ''),
(834, 'qr_scan', 'QR Scan', 'مسح الباركود'),
(835, 'stop_scan', 'Stop Scan', 'إيقاف المسح الضوئي'),
(836, 'scan_again', 'Scan Again', 'ابحث مره اخرى'),
(837, 'confirm_attendance', 'Confirm Attendance', 'تأكيد الحضور'),
(838, 'scan_alert', 'Your Scan Qr Was Wrong!! Please Scan Again', 'بحث الانذارات'),
(839, 'attn_success_mgs', 'Attendance Successfully Saved', ''),
(840, 'you_r_not_in_office', 'You Are Not In The Office Location', 'انت لست بالمكتب'),
(841, 'out_of_range', 'Out Of Range', 'خارج النطاق'),
(842, 'request_for_leave', 'Request For Leave', 'طلب إجازة'),
(843, 'leave_reason', 'Leave Reason', 'سبب الاجازة'),
(844, 'write_reason', 'Write Reason', 'اكتب السبب'),
(845, 'send_request', 'Send Request', 'أرسل طلب'),
(846, 'leave_his_status', 'Leave History Status', ''),
(847, 'total_tax', 'Total Tax', 'إجمالي الضرائب'),
(848, 'employment_date', 'Employment Date', 'تاريخ التوظيف'),
(849, 'notice_details', 'Notice Details', 'تفاصيل الإشعار'),
(850, 'no_notice_to_show', 'No Notice to Show', 'لا يوجد إشعار لعرضه'),
(851, 'welcome_msg', 'Welcome To HRM', 'رسالة ترحيبية'),
(852, 'enter_your_email', 'Enter Your Email', 'أدخل بريدك الإلكتروني'),
(853, 'enter_your_password', 'Enter Your Password', 'ادخل كلمة المرور الخاصة بك'),
(854, 'cannot_remember_pass', 'Can not Remember Password', 'لا يمكن تذكر مرور'),
(855, 'forgot_password', 'Forgot Password', 'هل نسيت كلمة السر'),
(856, 'email_pass_cannot_empt', 'Email or password can not be empty', 'لا يمكن إفراغ تمرير البريد الإلكتروني'),
(857, 'email_format_was_not_right', 'Email format was not Right!', 'البريد الإلكتروني غير صالح'),
(858, 'email_or_pass_not_matched', 'Email or password not matched!', 'البريد الإلكتروني أو كلمة المرور غير متطابقتين'),
(859, 'reset_your_password', 'Reset Your Password', 'أعد ضبط كلمة السر'),
(860, 'your_remember_password', 'You Remember Password', 'تذكر كلمة المرور الخاصة بك'),
(861, 'back_to_login', 'Back to Login', 'العودة لتسجيل الدخول'),
(862, 'email_fild_can_not_empty', 'Email Field can not be empty', 'حقل البريد الإلكتروني لا يمكن أن يكون فارغ'),
(863, 'email_not_found', 'Email Not Found', 'البريد الإلكتروني غير موجود'),
(864, 'successfully_send_email', 'Successfully Send Email!', 'تم إرسال البريد الإلكتروني بنجاح'),
(865, 'email_is_not_valid', 'Email Is Not Valid', 'البريد الإلكتروني غير صالح'),
(866, 'sorry_email_not_sent', 'Sorry Email Not Sent', 'آسف لم يتم إرسال البريد الإلكتروني'),
(867, 'day_leave', 'Day Leave', 'يوم الاجازة'),
(868, 'search_work_details', 'Search Work Details', 'البحث عن تفاصيل العمل'),
(869, 'times', 'Time', 'الاوقات'),
(870, 'request_not_send', 'Request Not Send', 'طلب عدم الإرسال'),
(871, 'leave_request_success', 'Your Leave Request SuccessFul', 'تم طلب الاجازة بنجاح'),
(872, 'all_field_are_required', 'All Field Are Required', 'كل الحقول مطلوبة'),
(873, 'plz_select_data_properly', 'Please select date properly', 'الرجاء إختيار التاريخ بدقة '),
(874, 'pending', 'Pending', 'معلق'),
(875, 'unpaid', 'Unpaid', 'غير مدفوع '),
(876, 'salary_details', 'Salary Details', 'تفاصيل الراتب'),
(877, 'worked_days', 'Worked Days', 'أيام عمل'),
(878, 'monthly_attendance', 'Monthly Attendance', 'الحضور الشهري'),
(879, 'year', 'Year', 'سنة'),
(880, 'month', 'Month', 'شهر'),
(881, 'missing_attendance', 'Missing Attendance', 'عدم الحضور'),
(882, 'daily_presents', 'Daily Presents', 'الحضور اليومي '),
(883, 'all', 'All', 'الكل'),
(884, 'daily_absent', 'Daily Absent', 'الغياب اليومي'),
(885, 'monthly_presents', 'Monthly Presents', 'هدايا شهرية'),
(886, 'monthly_absent', 'Monthly Absent', 'الغياب الشهري'),
(887, 'leave_report', 'Leave Report', 'تقرير الاجازة'),
(888, 'employee_on_leave', 'Employee On Leave', 'موظف في إجازة'),
(889, 'leave_balance', 'Leave Balance', 'رصيد الاجازات'),
(890, 'without_weekend', 'Without Weekend', 'بدون عطلة نهاية الأسبوع'),
(891, 'new_recruited_employee', 'New Recruited', 'الموظف المعين حديثًا'),
(892, 'todays_present', 'Today\'s Presents', 'ايام الحضور'),
(893, 'todays_absent', 'Today\'s Absents', 'ايام الغياب'),
(894, 'male', 'Male', 'الذكر'),
(895, 'female', 'Female', 'أنثى'),
(896, 'latest_notice', 'Latest Notice', 'أحدث إشعار'),
(897, 'attendance_last_30days', 'Attendance (Last 30 Days)', 'الحضور آخر 30 يومًا'),
(898, 'recruited_current_year', 'Recruited (Current Year)', 'اعداد المتوظفين خلال السنة'),
(899, 'absent_15days', 'Absent (Last 15 Days)', 'تغيب 15 يوما'),
(900, 'loanreceive', 'Loan Received', 'الحصول على سلفة'),
(901, 'current_year', 'Current Year', 'السنة الحالية'),
(902, 'awarded', 'Awarded', 'المنح'),
(903, 'loanpayment', 'Loan Payment', 'دفع السلفة'),
(904, 'login_info', 'Login Info', 'معلومات تسجيل الدخول'),
(905, 'user_email', 'User Email', 'البريد الالكتروني للمستخدم'),
(906, 'update_now', 'Update Now', 'تحديث الان'),
(907, 'notesupdate', 'Note: If you want to update software,you Must have immediate previous version', 'تحديث الملاحظات'),
(908, 'purchase_key', 'Purchase Key', 'مفتاح الشراء'),
(909, 'mobile_app_setting', 'Mobile App Setting(Addons)', 'إعداد تطبيق الجوال'),
(910, 'noupdates', 'No update available', 'لا توجد تحديثات'),
(911, 'finance', 'Finance', 'المالية'),
(912, 'hr', 'HR', 'الموارد البشرية'),
(913, 'Wosul', 'Wosul', 'وصول'),
(914, 'Edit Phrase', 'Edit Phrase', NULL),
(915, 'debit_total', 'Debit Total', 'إجمالي الخصم'),
(916, 'credit_total', 'Credit Total', 'إجمالي الائتمان'),
(917, 'remarks', 'Remarks', 'ملاحظات'),
(918, 'successfully_approved', 'Successfully Approved', 'تمت الموافقة بنجاح'),
(919, 'expenses', 'Expenses', 'نفقات'),
(920, 'transaction_receipt_link', 'Transaction receipt link', 'رابط إيصال المعاملة'),
(921, 'transaction_id', 'Transaction Id', 'رقم المعاملة'),
(922, 'coa', 'coa', 'شجرة الحسابات');
INSERT INTO `language` (`id`, `phrase`, `english`, `arabic`) VALUES
(923, 'Assets', 'Assets', 'الأصول'),
(924, 'Fixed Assets', 'Fixed Assets', 'أصول ثابتة'),
(925, 'Property plant and equipment', 'Property plant and equipment', 'عقارات وألات ومعدات'),
(926, 'Lands', 'Lands', 'الأراضي'),
(927, 'Buildings', 'Buildings', 'المباني'),
(928, 'Computer and Printer', 'Computer and Printer', 'أجهزة مكتبية وطابعات'),
(929, 'Current Assets', 'Current Assets', 'أصول متداولة'),
(930, 'Cash & Cash Equivalent', 'Cash & Cash Equivalent', 'النقد ومايعادله'),
(931, 'Cash at Bank', 'Cash at Bank', 'النقدية بالبنك'),
(932, 'Default', 'Default', ''),
(933, 'Cash in Hand', 'Cash in Hand', 'النقدية بالخزينة'),
(934, 'Petty Cash', 'Petty Cash', 'العهد النقدية'),
(935, 'Accounts Receivable', 'Accounts Receivable', 'المدينون'),
(936, 'Prepaid expenses', 'Prepaid expenses', 'مصروفات مقدمة'),
(937, 'Prepaid medical insurance', 'Prepaid medical insurance', 'تأمين طبي مقدم'),
(938, 'Prepaid rent', 'Prepaid rent', 'إيجار مقدم'),
(939, 'Staff advances', 'Staff advances', 'مدفوعات مقدمة للموظفين'),
(940, 'Inventory', 'Inventory', 'المخزون'),
(941, 'Non Current Assets', 'Non Current Assets', 'أصول غير متداولة'),
(942, 'Intangible Asset', 'Intangible Asset', 'الأصول غيرالملموسة'),
(943, 'Investment', 'Investment', 'الإستثمارات العقارية'),
(944, 'Liabilities', 'Liabilities', 'الألتزامات'),
(945, 'Current Liabilities', 'Current Liabilities', 'الإلتزامات المتداولة'),
(946, 'Accounts Payable', 'Accounts Payable', 'الدائنون'),
(947, 'Accrued Expense', 'Accrued Expense', 'مصروفات مستحقة'),
(948, 'Accrued Salaries', 'Accrued Salaries', 'الرواتب المستحقة'),
(949, 'Short term loans', 'Short term loans', 'قروض قصيرة الأجل'),
(950, 'Unearned revenues', 'Unearned revenues', 'إيرادات غير مكتسبة'),
(951, 'General organization for social insurance payable', 'General organization for social insurance payable', 'مستحقات المؤسسة العامة للتأمينات الإجتماعية'),
(952, 'Accumulated Depreciation', 'Accumulated Depreciation', 'مجمع الإهلاك'),
(953, 'Buildings accumulated depreciation', 'Buildings accumulated depreciation', 'مجمع إهلاك المباني'),
(954, 'Equipment accumulated depreciation', 'Equipment accumulated depreciation', 'مجمع إهلاك المعدات'),
(955, 'Computer and printers accumulated depreciation', 'Computer and printers accumulated depreciation', 'مجمع استهلاك أجهزة مكتبية وطابعات'),
(956, 'End of Services Provision', 'End of Services Provision', 'مخصص مكافأه نهاية الخدمة'),
(957, 'Non Current Liabilities', 'Non Current Liabilities', 'التزامات غير متداولة'),
(958, 'Long term Loans', 'Long term Loans', 'قروض طويلة الأجل'),
(959, 'VAT', 'VAT', 'الضريبة'),
(960, 'Sales VAT', 'Sales VAT', 'ضريبة المبيعات'),
(961, 'Purchases VAT', 'Purchases VAT', 'ضريبة المشتريات'),
(962, 'Equity', 'Equity', 'حقوق الملكية'),
(963, 'Retained Earnings or losses', 'Retained Earnings or losses', 'الأرباح المبقاه والخسائر'),
(964, 'Profit and loss', 'Profit and loss', 'الأرباح والخسائر العاملة'),
(965, 'Issued Capital', 'Issued Capital', 'رأس المال'),
(966, 'Registered capital', 'Registered capital', 'رأس المال المسجل'),
(967, 'Additional paid in capital', 'Additional paid in capital', 'رأس المال الأضافي المدفوع'),
(968, 'Other equity', 'Other equity', 'حقوق الملكية أخرى'),
(969, 'Opening Balance', 'Opening Balance', 'أرصدة إفتتاحية'),
(970, 'Reserve', 'Reserve', 'احتياطيات'),
(971, 'Statutory reserve', 'Statutory reserve', 'احتياطي نظامي'),
(972, 'Foreign currency translation reserve', 'Foreign currency translation reserve', 'احتياطي ترجمة عملات أجنبية'),
(973, 'Expense', 'Expense', 'المصاريف'),
(974, 'Direct Cost', 'Direct Cost', 'التكاليف المباشرة'),
(975, 'Cost of goods sold', 'Cost of goods sold', 'تكلفة البضاعة المباعة'),
(976, 'Salaries and wages', 'Salaries and wages', 'رواتب وأجور'),
(977, 'Sales Commissions', 'Sales Commissions', 'عمولات بيع'),
(978, 'Shipping and custom fees', 'Shipping and custom fees', 'شحن وتلخيص جمركي'),
(979, 'Operational Cost', 'Operational Cost', 'التكاليف التشغيلية'),
(980, 'Salaries and administrative fees', 'Salaries and administrative fees', 'الرواتب والرسوم الإدارية'),
(981, 'Medical insurance and treatment', 'Medical insurance and treatment', 'تأمين طبي'),
(982, 'Marketing and advertising', 'Marketing and advertising', 'مصاريف تسويقية ودعائية'),
(983, 'Rental expenses', 'Rental expenses', 'مصاريف الإيجار'),
(984, 'Commissions and incentives', 'Commissions and incentives', 'عمولات وحوافز'),
(985, 'Travel Expenses', 'Travel Expenses', 'تذاكر سفر'),
(986, 'Social insurance expense', 'Social insurance expense', 'التأمينات الأجتماعية'),
(987, 'Government fees', 'Government fees', 'الرسوم الحكومية'),
(988, 'Fees and subscriptions', 'Fees and subscriptions', 'رسوم وإشتراكات'),
(989, 'Utilities expenses', 'Utilities expenses', 'مصاريف خدمات المكتب'),
(990, 'Stationery and prints', 'Stationery and prints', 'مصاريف مكتبية ومطبوعات'),
(991, 'Hospitality and cleanliness', 'Hospitality and cleanliness', 'مصاريف ضيافة'),
(992, 'Bank commissions', 'Bank commissions', 'عمولات بنكية'),
(993, 'Other expenses', 'Other expenses', 'مصاريف أخرى'),
(994, 'Depreciation', 'Depreciation', 'مصاريف الأهلاك'),
(995, 'Buildings depreciation expense', 'Buildings depreciation expense', 'مصروف إهلاك المباني'),
(996, 'Equipment depreciation expense', 'Equipment depreciation expense', 'مصروف إهلاك المعدات'),
(997, 'Computers and printers depreciation expense', 'Computers and printers depreciation expense', 'مصروف إهلاك أجهزة مكتبية وطابعات'),
(998, 'Transportation expense', 'Transportation expense', 'مصروف نقل ومواصلات'),
(999, 'Non Operational Expenses', 'Non Operational Expenses', 'مصاريف غير تشغيلية'),
(1000, 'Zakat', 'Zakat', 'الزكاة'),
(1001, 'TAX', 'TAX', 'الضرائب'),
(1002, 'Change in currency value gains or losses', 'Change in currency value gains or losses', 'ترجمة عملات أجنبية'),
(1003, 'Interest', 'Interest', 'فوائد'),
(1004, 'Purchases', 'Purchases', 'المشتريات'),
(1005, 'Purchase Discount', 'Purchase Discount', 'خصم المشتريات'),
(1006, 'Purchase Return', 'Purchase Return', 'مردودات المشتريات'),
(1007, 'Income', 'Income', 'الإيرادات'),
(1008, 'Sales', 'Sales', 'المبيعات'),
(1009, 'Sales Discount', 'Sales Discount', 'خصم المبيعات'),
(1010, 'Sales Return', 'Sales Return', 'مردودات المبيعات'),
(1011, 'successfully_login', 'Successfully login', 'تم تسجيل الدخول بنجاح'),
(1012, 'successfully_login', 'Successfully login', 'تم تسجيل الدخول بنجاح'),
(1013, 'no_data_found', 'No Data Found', 'لاتوجد بيانات'),
(1014, 'success', 'Success', 'النجاح');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `language_constant` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `language_constant`, `language_code`, `language`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'EN', 'en', 'English', 1, 1, NULL, '2020-11-19 01:10:14', '2020-11-19 01:10:14'),
(2, 'DE', 'de', 'German', 0, 1, NULL, '2020-11-19 01:10:14', '2020-11-19 01:10:14'),
(3, 'AR', 'ar', 'Arabic', 1, 1, NULL, '2020-11-19 01:10:14', '2020-11-19 01:10:14'),
(4, 'ES', 'es', 'Spanish', 0, 1, NULL, '2020-11-19 01:10:14', '2020-11-19 01:10:14'),
(5, 'FR', 'fr', 'French', 0, 1, NULL, '2020-11-19 01:10:14', '2020-11-19 01:10:14'),
(6, 'IT', 'it', 'Italian', 0, 1, NULL, '2020-11-19 01:10:14', '2020-11-19 01:10:14'),
(7, 'MS', 'ms', 'Malay', 0, 1, NULL, '2020-11-19 01:10:14', '2020-11-19 01:10:14'),
(8, 'NO', 'no', 'Norwegian', 0, 1, NULL, '2020-11-19 01:10:15', '2020-11-19 01:10:15'),
(9, 'SV', 'sv', 'Swedish', 0, 1, NULL, '2020-11-19 01:10:15', '2020-11-19 01:10:15'),
(10, 'TH', 'th', 'Thai', 0, 1, NULL, '2020-11-19 01:10:15', '2020-11-19 01:10:15'),
(11, 'ZH', 'zh', 'Chinese', 0, 1, NULL, '2020-11-19 01:10:15', '2020-11-19 01:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `language_setting`
--

CREATE TABLE `language_setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_culture` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language_setting_phrases`
--

CREATE TABLE `language_setting_phrases` (
  `id` int(10) UNSIGNED NOT NULL,
  `lang_setting_id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_phrase` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_apply`
--

CREATE TABLE `leave_apply` (
  `leave_appl_id` int(11) NOT NULL,
  `employee_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_type_id` int(2) NOT NULL,
  `apply_strt_date` date DEFAULT NULL,
  `apply_end_date` date DEFAULT NULL,
  `apply_day` int(11) NOT NULL,
  `leave_aprv_strt_date` date DEFAULT NULL,
  `leave_aprv_end_date` date DEFAULT NULL,
  `num_aprv_day` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_hard_copy` text COLLATE utf8mb4_unicode_ci,
  `apply_date` date DEFAULT NULL,
  `approve_date` date DEFAULT NULL,
  `approved_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE `leave_type` (
  `leave_type_id` int(2) NOT NULL,
  `leave_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_days` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_installment`
--

CREATE TABLE `loan_installment` (
  `loan_inst_id` int(11) NOT NULL,
  `employee_id` varchar(21) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_id` varchar(21) COLLATE utf8mb4_unicode_ci NOT NULL,
  `installment_amount` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `received_by` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `installment_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `notes` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marital_info`
--

CREATE TABLE `marital_info` (
  `id` int(11) NOT NULL,
  `marital_sta` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marital_info`
--

INSERT INTO `marital_info` (`id`, `marital_sta`) VALUES
(1, 'Single'),
(2, 'Married'),
(3, 'Divorced'),
(4, 'Widowed'),
(5, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `master_account_type`
--

CREATE TABLE `master_account_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_type_constant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_account_type`
--

INSERT INTO `master_account_type` (`id`, `account_type_constant`, `label`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'BASIC', 'Basic (Default)', '', 1, 1, NULL, '2020-11-19 01:09:57', '2020-11-19 01:09:57'),
(2, 'ASSET', 'Asset', '', 1, 1, NULL, '2020-11-19 01:09:57', '2020-11-19 01:09:57'),
(3, 'LIABILITY', 'Liability', '', 1, 1, NULL, '2020-11-19 01:09:57', '2020-11-19 01:09:57'),
(4, 'EQUITY', 'Equity', '', 1, 1, NULL, '2020-11-19 01:09:57', '2020-11-19 01:09:57'),
(5, 'REVENUE', 'Revenue', '', 1, 1, NULL, '2020-11-19 01:09:57', '2020-11-19 01:09:57'),
(6, 'EXPENSE', 'Expense', '', 1, 1, NULL, '2020-11-19 01:09:57', '2020-11-19 01:09:57');

-- --------------------------------------------------------

--
-- Table structure for table `master_billing_type`
--

CREATE TABLE `master_billing_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `billing_type_constant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_billing_type`
--

INSERT INTO `master_billing_type` (`id`, `billing_type_constant`, `label`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'FINE_DINE', 'Fine Dine', '', 1, 1, NULL, '2020-11-19 01:10:38', '2020-11-19 01:10:38'),
(2, 'QUICK_BILL', 'Quick Bill', '', 1, 1, NULL, '2020-11-19 01:10:38', '2020-11-19 01:10:38');

-- --------------------------------------------------------

--
-- Table structure for table `master_date_format`
--

CREATE TABLE `master_date_format` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format_value` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format_label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_date_format`
--

INSERT INTO `master_date_format` (`id`, `key`, `date_format_value`, `date_format_label`, `status`, `created_at`, `updated_at`) VALUES
(1, 'DATE_TIME_FORMAT', 'd-m-Y H:i', '01-12-2020 23:00', 1, '2020-11-19 01:09:08', '2020-11-19 01:09:08'),
(2, 'DATE_TIME_FORMAT', 'j-n-Y H:i', '1-12-2020 23:00', 1, '2020-11-19 01:09:08', '2020-11-19 01:09:08'),
(3, 'DATE_TIME_FORMAT', 'd-m-Y h:i A', '01-12-2020 01:00 PM', 1, '2020-11-19 01:09:08', '2020-11-19 01:09:08'),
(4, 'DATE_TIME_FORMAT', 'j-n-Y h:i A', '1-12-2020 01:00 PM', 1, '2020-11-19 01:09:08', '2020-11-19 01:09:08'),
(5, 'DATE_FORMAT', 'd-m-Y', '01-12-2020', 1, '2020-11-19 01:09:09', '2020-11-19 01:09:09'),
(6, 'DATE_FORMAT', 'j-n-Y', '1-12-2020', 1, '2020-11-19 01:09:09', '2020-11-19 01:09:09'),
(7, 'DATE_FORMAT', 'Y-m-d', '2020-12-01', 1, '2020-11-19 01:09:09', '2020-11-19 01:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `master_invoice_print_type`
--

CREATE TABLE `master_invoice_print_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `print_type_value` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `print_type_label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_invoice_print_type`
--

INSERT INTO `master_invoice_print_type` (`id`, `print_type_value`, `print_type_label`, `status`, `created_at`, `updated_at`) VALUES
(1, 'A4', 'A4 Sheet', 1, '2020-11-19 01:09:10', '2020-11-19 01:09:10'),
(2, 'SMALL', 'Small Sheet', 1, '2020-11-19 01:09:10', '2020-11-19 01:09:10'),
(3, 'SMALL_LITE', 'Small Sheet - Lite', 1, '2020-11-19 01:10:53', '2020-11-19 01:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `master_order_type`
--

CREATE TABLE `master_order_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_type_constant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `restaurant` tinyint(4) NOT NULL DEFAULT '0',
  `icon` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_order_type`
--

INSERT INTO `master_order_type` (`id`, `order_type_constant`, `label`, `description`, `restaurant`, `icon`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'DINEIN', 'Dine In', '', 1, 'fas fa-utensil-spoon', 1, 1, NULL, '2020-11-19 01:10:09', '2020-11-19 01:10:09'),
(2, 'TAKEWAY', 'Take Away', '', 1, 'fas fa-box-open', 1, 1, NULL, '2020-11-19 01:10:10', '2020-11-19 01:10:10'),
(3, 'DELIVERY', 'Delivery', '', 1, 'fas fa-biking', 1, 1, NULL, '2020-11-19 01:10:10', '2020-11-19 01:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `master_status`
--

CREATE TABLE `master_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_constant` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_status`
--

INSERT INTO `master_status` (`id`, `key`, `value`, `value_constant`, `label`, `color`, `status`, `created_at`, `updated_at`) VALUES
(1, 'USER_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(2, 'USER_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(3, 'CUSTOMER_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(4, 'CUSTOMER_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(5, 'ROLE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(6, 'ROLE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(7, 'CATEGORY_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(8, 'CATEGORY_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(9, 'PRODUCT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(10, 'PRODUCT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(11, 'SUPPLIER_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(12, 'SUPPLIER_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(13, 'TAX_CODE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(14, 'TAX_CODE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(15, 'ORDER_STATUS', '0', 'DELETED', 'Deleted', 'label red-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(16, 'ORDER_STATUS', '1', 'CLOSED', 'Closed', 'label green-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(17, 'ORDER_STATUS', '2', 'HOLD', 'Hold', 'label red-label', 1, '2020-11-19 01:08:33', '2020-11-19 01:08:33'),
(18, 'ORDER_PRODUCT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(19, 'ORDER_PRODUCT_STATUS', '2', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(20, 'STORE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(21, 'STORE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(22, 'DISCOUNTCODE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(23, 'DISCOUNTCODE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(24, 'PAYMENT_METHOD_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(25, 'PAYMENT_METHOD_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(26, 'PURCHASE_ORDER_STATUS', '1', 'CREATED', 'Created', 'label green-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(27, 'PURCHASE_ORDER_STATUS', '2', 'APPROVED', 'Approved', 'label blue-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(28, 'PURCHASE_ORDER_STATUS', '3', 'RELEASED_TO_SUPPLIER', 'Released To Supplier', 'label orange-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(29, 'PURCHASE_ORDER_STATUS', '4', 'CLOSED', 'Closed', 'label grey-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(30, 'PURCHASE_ORDER_STATUS', '0', 'CANCELLED', 'Cancelled', 'label red-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(31, 'PURCHASE_ORDER_PRODUCT_ST', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(32, 'PURCHASE_ORDER_PRODUCT_ST', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(33, 'MAIL_SETTING_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(34, 'MAIL_SETTING_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(35, 'MASTER_DATE_FORMAT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(36, 'MASTER_DATE_FORMAT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:35', '2020-11-19 01:08:35'),
(37, 'MASTER_INVOICE_PRINT_TYPE', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:35', '2020-11-19 01:08:35'),
(38, 'MASTER_INVOICE_PRINT_TYPE', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:35', '2020-11-19 01:08:35'),
(39, 'INVOICE_STATUS', '0', 'CANCELLED', 'Cancelled', 'label red-label', 1, '2020-11-19 01:09:23', '2020-11-19 01:09:23'),
(40, 'INVOICE_STATUS', '1', 'NEW', 'New', 'label blue-label', 1, '2020-11-19 01:09:24', '2020-11-19 01:09:24'),
(41, 'INVOICE_STATUS', '2', 'SENT', 'Sent', 'label green-label', 1, '2020-11-19 01:09:24', '2020-11-19 01:09:24'),
(42, 'INVOICE_STATUS', '3', 'PAID', 'Paid', 'label green-label', 1, '2020-11-19 01:09:24', '2020-11-19 01:09:24'),
(43, 'INVOICE_STATUS', '4', 'OVERDUE', 'Overdue', 'label orange-label', 1, '2020-11-19 01:09:26', '2020-11-19 01:09:26'),
(44, 'INVOICE_STATUS', '5', 'VOID', 'Void', 'label grey-label', 1, '2020-11-19 01:09:26', '2020-11-19 01:09:26'),
(45, 'INVOICE_STATUS', '6', 'WRITEOFF', 'Write Off', 'label grey-label', 1, '2020-11-19 01:09:27', '2020-11-19 01:09:27'),
(46, 'INVOICE_PRODUCT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:09:27', '2020-11-19 01:09:27'),
(47, 'INVOICE_PRODUCT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:09:28', '2020-11-19 01:09:28'),
(48, 'QUOTATION_STATUS', '0', 'CANCELLED', 'Cancelled', 'label red-label', 1, '2020-11-19 01:09:34', '2020-11-19 01:09:34'),
(49, 'QUOTATION_STATUS', '1', 'PENDING', 'Pending', 'label blue-label', 1, '2020-11-19 01:09:34', '2020-11-19 01:09:34'),
(50, 'QUOTATION_STATUS', '2', 'ACCEPTED', 'Accepted', 'label green-label', 1, '2020-11-19 01:09:34', '2020-11-19 01:09:34'),
(51, 'QUOTATION_STATUS', '3', 'DECLINED', 'Declined', 'label red-label', 1, '2020-11-19 01:09:34', '2020-11-19 01:09:34'),
(52, 'QUOTATION_STATUS', '4', 'EXPIRED', 'Expired', 'label red-label', 1, '2020-11-19 01:09:35', '2020-11-19 01:09:35'),
(53, 'QUOTATION_PRODUCT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:09:35', '2020-11-19 01:09:35'),
(54, 'QUOTATION_PRODUCT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:09:35', '2020-11-19 01:09:35'),
(55, 'ORDER_STATUS', '3', 'PAYMENT_PENDING', 'Payment Pending', 'label orange-label', 1, '2020-11-19 01:09:47', '2020-11-19 01:09:47'),
(56, 'ORDER_STATUS', '4', 'PAYMENT_FAILED', 'Payment Failed', 'label red-label', 1, '2020-11-19 01:09:47', '2020-11-19 01:09:47'),
(57, 'MASTER_TRANSACTION_TYPE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:09:54', '2020-11-19 01:09:54'),
(58, 'MASTER_TRANSACTION_TYPE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:09:54', '2020-11-19 01:09:54'),
(59, 'MASTER_ACCOUNT_TYPE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:09:57', '2020-11-19 01:09:57'),
(60, 'MASTER_ACCOUNT_TYPE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:09:57', '2020-11-19 01:09:57'),
(61, 'ACCOUNT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:00', '2020-11-19 01:10:00'),
(62, 'ACCOUNT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:00', '2020-11-19 01:10:00'),
(63, 'MASTER_TAX_OPTION_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:02', '2020-11-19 01:10:02'),
(64, 'MASTER_TAX_OPTION_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:03', '2020-11-19 01:10:03'),
(65, 'ORDER_STATUS', '5', 'IN_KITCHEN', 'In Kitchen', 'label blue-label', 1, '2020-11-19 01:10:06', '2020-11-19 01:10:06'),
(66, 'ORDER_KITCHEN_STATUS', '0', 'CONFIRMED', 'Order Confirmed', 'label yellow-label', 1, '2020-11-19 01:10:07', '2020-11-19 01:10:07'),
(67, 'ORDER_KITCHEN_STATUS', '1', 'STARTED_PREPARING', 'Started Preparing', 'label blue-label', 1, '2020-11-19 01:10:07', '2020-11-19 01:10:07'),
(68, 'ORDER_KITCHEN_STATUS', '2', 'ORDER_READY', 'Ready to Serve', 'label green-label', 1, '2020-11-19 01:10:07', '2020-11-19 01:10:07'),
(69, 'MASTER_ORDER_TYPE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:09', '2020-11-19 01:10:09'),
(70, 'MASTER_ORDER_TYPE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:09', '2020-11-19 01:10:09'),
(71, 'RESTAURANT_TABLE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:12', '2020-11-19 01:10:12'),
(72, 'RESTAURANT_TABLE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:12', '2020-11-19 01:10:12'),
(73, 'LANGUAGE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:13', '2020-11-19 01:10:13'),
(74, 'LANGUAGE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:13', '2020-11-19 01:10:13'),
(75, 'STOCK_TRANSFER_STATUS', '0', 'PENDING', 'Pending', 'label yellow-label', 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(76, 'STOCK_TRANSFER_STATUS', '1', 'INITIATED', 'Initiated', 'label blue-label', 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(77, 'STOCK_TRANSFER_STATUS', '2', 'VERIFIED', 'Verified', 'label green-label', 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(78, 'STOCK_TRANSFER_PRODUCT_STATUS', '0', 'PENDING', 'Pending', 'label yellow-label', 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(79, 'STOCK_TRANSFER_PRODUCT_STATUS', '1', 'ACCEPTED', 'Accepted', 'label green-label', 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(80, 'STOCK_TRANSFER_PRODUCT_STATUS', '2', 'REJECTED', 'Rejected', 'label red-label', 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(81, 'STOCK_RETURN_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:28', '2020-11-19 01:10:28'),
(82, 'STOCK_RETURN_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:28', '2020-11-19 01:10:28'),
(83, 'STOCK_RETURN_PRODUCT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:28', '2020-11-19 01:10:28'),
(84, 'STOCK_RETURN_PRODUCT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:28', '2020-11-19 01:10:28'),
(85, 'NOTIFICATION_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:31', '2020-11-19 01:10:31'),
(86, 'NOTIFICATION_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:31', '2020-11-19 01:10:31'),
(87, 'MASTER_BILLING_TYPE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:37', '2020-11-19 01:10:37'),
(88, 'MASTER_BILLING_TYPE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:38', '2020-11-19 01:10:38'),
(89, 'SMS_SETTING_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:39', '2020-11-19 01:10:39'),
(90, 'SMS_SETTING_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:39', '2020-11-19 01:10:39'),
(91, 'SMS_TEMPLATE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:42', '2020-11-19 01:10:42'),
(92, 'SMS_TEMPLATE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:42', '2020-11-19 01:10:42'),
(93, 'BILLING_COUNTER_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:44', '2020-11-19 01:10:44'),
(94, 'BILLING_COUNTER_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:44', '2020-11-19 01:10:44'),
(95, 'KEYBOARD_SHORTCUT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:51', '2020-11-19 01:10:51'),
(96, 'KEYBOARD_SHORTCUT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:51', '2020-11-19 01:10:51'),
(97, 'PRODUCT_IMAGE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:10:55', '2020-11-19 01:10:55'),
(98, 'PRODUCT_IMAGE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:10:55', '2020-11-19 01:10:55'),
(99, 'MEASUREMENT_UNIT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(100, 'MEASUREMENT_UNIT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(101, 'MAIN_CATEGORY_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(102, 'MAIN_CATEGORY_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(103, 'MEASUREMENT_CATEGORY_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(104, 'MEASUREMENT_CATEGORY_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(105, 'MEASUREMENT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(106, 'MEASUREMENT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(107, 'MERCHANT_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(108, 'MERCHANT_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(109, 'SUBSCRIPTION_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(110, 'SUBSCRIPTION_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(111, 'BRAND_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(112, 'BRAND_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(113, 'MODIFIER_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(114, 'MODIFIER_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(115, 'MODIFIER_OPTION_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(116, 'MODIFIER_OPTION_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(125, 'SYNC_ZID_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(126, 'SYNC_ZID_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 01:08:32', '2020-11-19 01:08:32'),
(127, 'UPS_SETTING_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2021-08-25 09:48:47', '2021-08-25 09:48:47'),
(128, 'UPS_SETTING_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2021-08-25 09:48:47', '2021-08-25 09:48:47'),
(129, 'BUPS_SETTING_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2021-09-14 12:58:13', '2021-09-14 12:58:13'),
(130, 'BUPS_SETTING_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2021-09-14 12:58:13', '2021-09-14 12:58:13'),
(131, 'BONAT_VERIFY_SETTING_STATUS', '1', 'Verified', 'Verified', 'label green-label', 1, '2021-09-14 12:59:31', '2021-09-14 12:59:31'),
(132, 'BONAT_VERIFY_SETTING_STATUS', '0', 'NotVerified', 'NotVerified', 'label red-label', 1, '2021-09-14 12:59:31', '2021-09-14 12:59:31'),
(133, 'RETURN_STATUS', '1', 'RETURNED', 'Returned', 'label green-label', 1, '2020-11-18 19:38:32', '2020-11-18 19:38:32'),
(134, 'RETURN_STATUS', '0', 'ONHOLD', 'On Hold', 'label red-label', 1, '2020-11-18 19:38:32', '2020-11-18 19:38:32'),
(135, 'QUANTITY_PURCHASE_STATUS', '0', 'CANCELLED', 'Cancelled', 'label red-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(136, 'QUANTITY_PURCHASE_STATUS', '1', 'CREATED', 'Created', 'label green-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(137, 'QUANTITY_PURCHASE_STATUS', '2', 'APPROVED', 'Approved', 'label blue-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(138, 'QUANTITY_PURCHASE_STATUS', '3', 'RELEASED_TO_SUPPLIER', 'Released To Supplier', 'label orange-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(139, 'QUANTITY_PURCHASE_STATUS', '4', 'CLOSED', 'Closed', 'label grey-label', 1, '2020-11-19 01:08:34', '2020-11-19 01:08:34'),
(140, 'INVOICE_STATUS', '7', 'PARTIAL_PAY', 'Partial Pay', 'label orange-label', 1, '2022-02-17 07:52:25', '2022-02-17 07:52:25'),
(141, 'INVOICE_STATUS', '8', 'RETURN', 'Return', 'label yellow-label', 1, '2022-02-17 07:52:41', '2022-02-17 07:52:41'),
(142, 'TAX_NAME_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2022-07-22 14:32:35', '2022-07-22 14:32:35'),
(143, 'TAX_NAME_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2022-07-22 14:32:35', '2022-07-22 14:32:35'),
(144, 'INVENTORY_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2020-11-19 03:38:33', '2020-11-19 03:38:33'),
(145, 'INVENTORY_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2020-11-19 03:38:33', '2020-11-19 03:38:33'),
(146, 'PRICE_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2022-11-06 08:51:30', '2022-11-06 08:51:30'),
(147, 'PRICE_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2022-11-06 08:51:30', '2022-11-06 08:51:30'),
(148, 'COMBO_STATUS', '1', 'ACTIVE', 'Active', 'label green-label', 1, '2022-11-08 11:03:50', '2022-11-08 11:03:50'),
(149, 'COMBO_STATUS', '0', 'INACTIVE', 'Inactive', 'label red-label', 1, '2022-11-08 11:03:50', '2022-11-08 11:03:50'),
(150, 'ORDER_STATUS', '6', 'RETURN', 'Return', 'label yellow-label', 1, '2022-11-08 11:03:50', '2022-11-08 11:03:50'),
(151, 'EXPRESSPAY_STATUS', '1', 'PAID', 'Paid', 'label green-label', 1, '2023-02-13 02:45:32', '2023-02-13 02:45:32'),
(152, 'EXPRESSPAY_STATUS', '0', 'PENDING', 'Pending', 'label red-label', 1, '2023-02-13 02:45:32', '2023-02-13 02:45:32'),
(153, 'ORDER_STATUS', '7', 'PARTIAL_PAYMENT', 'Partial Payment', 'label orange-label', 1, '2023-04-12 03:18:41', '2023-04-12 03:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `master_tax_option`
--

CREATE TABLE `master_tax_option` (
  `id` int(10) UNSIGNED NOT NULL,
  `tax_option_constant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_count` int(11) NOT NULL DEFAULT '1',
  `component_1` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `component_2` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `component_3` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_tax_option`
--

INSERT INTO `master_tax_option` (`id`, `tax_option_constant`, `label`, `component_count`, `component_1`, `component_2`, `component_3`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'DEFAULT_TAX', 'DEFAULT TAX', 1, 'TAX', '', '', '', 1, 1, NULL, '2020-11-19 01:10:03', '2020-11-19 01:10:03'),
(2, 'CGST_SGST', 'CGST + SGST', 2, 'CGST', 'SGST', '', '', 1, 1, NULL, '2020-11-19 01:10:03', '2020-11-19 01:10:03'),
(3, 'IGST', 'IGST', 1, 'IGST', '', '', '', 1, 1, NULL, '2020-11-19 01:10:03', '2020-11-19 01:10:03'),
(4, 'VAT', 'VAT', 1, 'VAT', '', '', '', 1, 1, NULL, '2020-11-19 01:10:08', '2020-11-19 01:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `master_transaction_type`
--

CREATE TABLE `master_transaction_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_type_constant` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_transaction_type`
--

INSERT INTO `master_transaction_type` (`id`, `transaction_type_constant`, `label`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'INCOME', 'Income/Credit', '', 1, 1, NULL, '2020-11-19 01:09:54', '2020-11-19 01:09:54'),
(2, 'EXPENSE', 'Expense/Debit', '', 1, 1, NULL, '2020-11-19 01:09:54', '2020-11-19 01:09:54');

-- --------------------------------------------------------

--
-- Table structure for table `measurements`
--

CREATE TABLE `measurements` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `measurement_category_id` int(11) DEFAULT NULL,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `measurement_categories`
--

CREATE TABLE `measurement_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `measurement_conversions`
--

CREATE TABLE `measurement_conversions` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_measurement_id` int(11) NOT NULL,
  `to_measurement_id` int(11) NOT NULL,
  `value` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) DEFAULT NULL,
  `icon` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_restaurant_menu` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `type`, `menu_key`, `label`, `route`, `parent`, `sort_order`, `icon`, `image`, `is_restaurant_menu`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MAIN_MENU', 'MM_DASHBOARD', 'Dashboard', '', 0, 1, 'fas fa-chart-line', 'dashbord.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:10:46'),
(2, 'MAIN_MENU', 'MM_ORDERS', 'Sales & Orders', '', 0, 4, 'fas fa-shopping-cart', 'sales.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:10:08'),
(3, 'MAIN_MENU', 'MM_USER', 'Customer', '', 0, 9, 'fas fa-user-astronaut', 'customer.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:10:08'),
(4, 'MAIN_MENU', 'MM_SUPPLIER', 'Supplier', '', 0, 8, 'fas fa-truck', 'supplier.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:10:08'),
(5, 'MAIN_MENU', 'MM_TAX_AND_DISCOUNT', 'Tax & Discount', '', 0, 6, 'fas fa-percentage', 'tax_and_discount.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:10:08'),
(6, 'MAIN_MENU', 'MM_STOCK', 'Inventory', '', 0, 2, 'fas fa-cubes', 'inventory.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:10:23'),
(7, 'MAIN_MENU', 'MM_REPORT', 'Reports', '', 0, 12, 'fas fa-chart-pie', 'report.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:10:34'),
(8, 'MAIN_MENU', 'MM_SETTINGS', 'Settings', '', 0, 13, 'fas fa-cog', 'setting.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:10:34'),
(9, 'SUB_MENU', 'SM_POS_ORDERS', 'Point of Sale', 'orders', 2, 4, NULL, 'sales_and_orders/pointofsale.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:09:49'),
(10, 'SUB_MENU', 'SM_QUANTITY_PURCHASE', 'Quantity Purchase', 'quantity_purchases', 185, 2, NULL, 'purchasing/purchase_order.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(11, 'SUB_MENU', 'SM_USERS', 'Users', 'users', 8, 1, NULL, 'setting_user.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(12, 'SUB_MENU', 'SM_CUSTOMERS', 'Customers', 'customers', 3, 2, NULL, 'customer/customer.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(13, 'SUB_MENU', 'SM_ROLES', 'Roles', 'roles', 8, 3, NULL, 'customer/role.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(14, 'SUB_MENU', 'SM_SUPPLIERS', 'Suppliers', 'suppliers', 4, 1, NULL, 'supplier/supplier.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(15, 'SUB_MENU', 'SM_TAXCODES', 'Tax', 'tax_codes', 5, 1, NULL, 'tax_and_discount_code/discount_code.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(16, 'SUB_MENU', 'SM_DISCOUNTCODES', 'Discount', 'discount_codes', 5, 2, NULL, 'tax_and_discount_code/tax_code.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(17, 'SUB_MENU', 'SM_PRODUCTS', 'Products', 'products', 6, 1, NULL, 'inventory/product.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(18, 'SUB_MENU', 'SM_CATEGORY', 'Categories', 'categories', 6, 2, NULL, 'inventory/category.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(19, 'SUB_MENU', 'SM_STORE', 'Stores', 'stores', 8, 1, NULL, 'setting_store.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(20, 'SUB_MENU', 'SM_PAYMENT_METHOD', 'Payment Methods', 'payment_methods', 8, 2, NULL, 'setting_payment.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(21, 'SUB_MENU', 'SM_IMPORT_DATA', 'Import Data', 'import_data', 160, 1, NULL, 'import_data/import_data.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:10:34'),
(22, 'SUB_MENU', 'SM_UPDATE_DATA', 'Upload & Update Data', 'update_data', 160, 2, NULL, 'import_data/upload_update_data.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:10:34'),
(23, 'SUB_MENU', 'SM_EMAIL_SETTING', 'Email Settings', 'email_setting', 8, 3, NULL, 'setting_email.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:10:39'),
(24, 'SUB_MENU', 'SM_APP_SETTING', 'App Settings', 'app_setting', 8, 7, NULL, 'setting_app.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:11:03'),
(25, 'ACTIONS', 'A_ADD_USER', 'Add User', '', 11, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(26, 'ACTIONS', 'A_EDIT_USER', 'Edit User', '', 11, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(27, 'ACTIONS', 'A_DETAIL_USER', 'View User Detail', '', 11, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(28, 'ACTIONS', 'A_ADD_ROLE', 'Add Role', '', 13, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:19', '2020-11-19 01:08:19'),
(29, 'ACTIONS', 'A_EDIT_ROLE', 'Edit Role', '', 13, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:19', '2020-11-19 01:08:19'),
(30, 'ACTIONS', 'A_DETAIL_ROLE', 'View Role Detail', '', 13, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:19', '2020-11-19 01:08:19'),
(31, 'ACTIONS', 'A_ADD_CUSTOMER', 'Add Customer', '', 12, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:19', '2020-11-19 01:08:19'),
(32, 'ACTIONS', 'A_EDIT_CUSTOMER', 'Edit Customer', '', 12, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:19', '2020-11-19 01:08:19'),
(33, 'ACTIONS', 'A_DETAIL_CUSTOMER', 'View Customer Detail', '', 12, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:19', '2020-11-19 01:08:19'),
(34, 'ACTIONS', 'A_ADD_ORDER', 'Add Order', '', 9, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(35, 'ACTIONS', 'A_EDIT_ORDER', 'Edit Order', '', 9, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(36, 'ACTIONS', 'A_DETAIL_ORDER', 'View Order Details', '', 9, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(37, 'ACTIONS', 'A_DELETE_ORDER', 'Delete Order', '', 9, 4, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(38, 'ACTIONS', 'A_ADD_PRODUCT', 'Add Product', '', 17, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(39, 'ACTIONS', 'A_EDIT_PRODUCT', 'Edit Product', '', 17, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(40, 'ACTIONS', 'A_DETAIL_PRODUCT', 'View Product Detail', '', 17, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(41, 'SUB_MENU', 'SM_PRODUCT_LABEL', 'Product Label', 'generate_barcode', 6, 5, NULL, 'inventory/product_label.png', 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:10:52'),
(42, 'ACTIONS', 'A_ADD_CATEGORY', 'Add Category', '', 18, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(43, 'ACTIONS', 'A_EDIT_CATEGORY', 'Edit Category', '', 18, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(44, 'ACTIONS', 'A_DETAIL_CATEGORY', 'View Category Detail', '', 18, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(45, 'ACTIONS', 'A_ADD_TAXCODE', 'Add Tax Code', '', 15, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(46, 'ACTIONS', 'A_EDIT_TAXCODE', 'Edit Tax Code', '', 15, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(47, 'ACTIONS', 'A_DETAIL_TAXCODE', 'View Tax Code Detail', '', 15, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(48, 'ACTIONS', 'A_ADD_DISCOUNTCODE', 'Add Discount Code', '', 16, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:20', '2020-11-19 01:08:20'),
(49, 'ACTIONS', 'A_EDIT_DISCOUNTCODE', 'Edit Discount Code', '', 16, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(50, 'ACTIONS', 'A_DETAIL_DISCOUNTCODE', 'View Discount Code Detail', '', 16, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(51, 'ACTIONS', 'A_ADD_SUPPLIER', 'Add Supplier', '', 14, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(52, 'ACTIONS', 'A_EDIT_SUPPLIER', 'Edit Supplier', '', 14, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(53, 'ACTIONS', 'A_DETAIL_SUPPLIER', 'View Supplier Detail', '', 14, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(54, 'ACTIONS', 'A_ADD_STORE', 'Add Store', '', 19, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(55, 'ACTIONS', 'A_EDIT_STORE', 'Edit Store', '', 19, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(56, 'ACTIONS', 'A_DETAIL_STORE', 'View Store Detail', '', 19, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(57, 'ACTIONS', 'A_ADD_PAYMENT_METHOD', 'Add Payment Method', '', 20, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(58, 'ACTIONS', 'A_EDIT_PAYMENT_METHOD', 'Edit Payment Method', '', 20, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(59, 'ACTIONS', 'A_DETAIL_PAYMENT_METHOD', 'View Payment Method Detail', '', 20, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(60, 'ACTIONS', 'A_UPLOAD_USER', 'Upload Users', '', 21, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(61, 'ACTIONS', 'A_UPLOAD_STORE', 'Upload Store', '', 21, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(62, 'ACTIONS', 'A_UPLOAD_SUPPLIER', 'Upload Supplier', '', 21, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(63, 'ACTIONS', 'A_UPLOAD_CATEGORY', 'Upload Category', '', 21, 4, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(64, 'ACTIONS', 'A_UPLOAD_PRODUCT', 'Upload Product', '', 21, 5, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(65, 'ACTIONS', 'A_UPDATE_USER', 'Update Users', '', 22, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(66, 'ACTIONS', 'A_UPDATE_STORE', 'Update Store', '', 22, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(67, 'ACTIONS', 'A_UPDATE_SUPPLIER', 'Update Supplier', '', 22, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(68, 'ACTIONS', 'A_UPDATE_CATEGORY', 'Update Category', '', 22, 4, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(69, 'ACTIONS', 'A_UPDATE_PRODUCT', 'Update Product', '', 22, 5, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(70, 'ACTIONS', 'A_ADD_QUANTITY_PURCHASE', 'Add Quantity Purchase', '', 10, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(71, 'ACTIONS', 'A_EDIT_QUANTITY_PURCHASE', 'Edit Quantity Purchase ', '', 10, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:22', '2020-11-19 01:08:22'),
(72, 'ACTIONS', 'A_DETAIL_QUANTITY_PURCHASE', 'View Quantity \nPurchase Detail', '', 10, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:22', '2020-11-19 01:08:22'),
(73, 'ACTIONS', 'A_EDIT_STATUS_QUANTITY_PURCHASE', 'Change Quantity Purchase Status', '', 10, 4, NULL, NULL, 0, 1, '2020-11-19 01:08:22', '2020-11-19 01:09:50'),
(74, 'ACTIONS', 'A_EDIT_EMAIL_SETTING', 'Edit Email Setting', '', 23, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:22', '2020-11-19 01:08:22'),
(75, 'ACTIONS', 'A_EDIT_APP_SETTING', 'Edit App Setting', '', 24, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:22', '2020-11-19 01:08:22'),
(76, 'SUB_MENU', 'SM_INVOICES', 'Invoices', 'invoices', 2, 3, NULL, 'sales_and_orders/invoice.png', 0, 1, '2020-11-19 01:09:20', '2020-11-19 01:09:20'),
(77, 'ACTIONS', 'A_ADD_INVOICE', 'Add Invoice', '', 76, 1, NULL, NULL, 0, 1, '2020-11-19 01:09:20', '2020-11-19 01:09:20'),
(78, 'ACTIONS', 'A_EDIT_INVOICE', 'Edit Invoice', '', 76, 2, NULL, NULL, 0, 1, '2020-11-19 01:09:21', '2020-11-19 01:09:21'),
(79, 'ACTIONS', 'A_DETAIL_INVOICE', 'View Invoice Details', '', 76, 3, NULL, NULL, 0, 1, '2020-11-19 01:09:21', '2020-11-19 01:09:21'),
(80, 'ACTIONS', 'A_DELETE_INVOICE', 'Delete Invoice', '', 76, 4, NULL, NULL, 0, 1, '2020-11-19 01:09:22', '2020-11-19 01:09:22'),
(81, 'ACTIONS', 'A_EDIT_STATUS_INVOICE', 'Change Invoice Status', '', 76, 5, NULL, NULL, 0, 1, '2020-11-19 01:09:22', '2020-11-19 01:09:22'),
(82, 'ACTIONS', 'A_MAKE_PAYMENT_INVOICE', 'Add Invoice Payment', '', 76, 6, NULL, NULL, 0, 1, '2020-11-19 01:09:23', '2020-11-19 01:09:23'),
(83, 'SUB_MENU', 'SM_QUOTATIONS', 'Quotations', 'quotations', 2, 4, NULL, 'sales_and_orders/quotation.png', 0, 1, '2020-11-19 01:09:33', '2020-11-19 01:09:33'),
(84, 'ACTIONS', 'A_ADD_QUOTATION', 'Add Quotation', '', 83, 1, NULL, NULL, 0, 1, '2020-11-19 01:09:33', '2020-11-19 01:09:33'),
(85, 'ACTIONS', 'A_EDIT_QUOTATION', 'Edit Quotation', '', 83, 2, NULL, NULL, 0, 1, '2020-11-19 01:09:34', '2020-11-19 01:09:34'),
(86, 'ACTIONS', 'A_DETAIL_QUOTATION', 'View Quotation Details', '', 83, 3, NULL, NULL, 0, 1, '2020-11-19 01:09:34', '2020-11-19 01:09:34'),
(87, 'ACTIONS', 'A_DELETE_QUOTATION', 'Delete Quotation', '', 83, 4, NULL, NULL, 0, 1, '2020-11-19 01:09:34', '2020-11-19 01:09:34'),
(88, 'ACTIONS', 'A_EDIT_STATUS_QUOTATION', 'Change Quotation Status', '', 83, 5, NULL, NULL, 0, 1, '2020-11-19 01:09:34', '2020-11-19 01:09:34'),
(89, 'MAIN_MENU', 'MM_ACCOUNT', 'Business Account', '', 0, 5, 'fas fa-money-check-alt', 'business.png', 0, 1, '2020-11-19 01:09:48', '2020-11-19 01:10:08'),
(90, 'SUB_MENU', 'SM_ACCOUNTS', 'Accounts', 'accounts', 89, 1, NULL, 'business_account/account.png', 0, 1, '2020-11-19 01:09:49', '2020-11-19 01:09:49'),
(91, 'SUB_MENU', 'SM_TRANSACTIONS', 'Transactions', 'transactions', 89, 2, NULL, 'business_account/transaction.png', 0, 1, '2020-11-19 01:09:49', '2020-11-19 01:09:49'),
(92, 'ACTIONS', 'A_ADD_ACCOUNT', 'Add Account', '', 90, 1, NULL, NULL, 0, 1, '2020-11-19 01:09:49', '2020-11-19 01:09:49'),
(93, 'ACTIONS', 'A_EDIT_ACCOUNT', 'Edit Account', '', 90, 2, NULL, NULL, 0, 1, '2020-11-19 01:09:49', '2020-11-19 01:09:49'),
(94, 'ACTIONS', 'A_DETAIL_ACCOUNT', 'View Account Detail', '', 90, 3, NULL, NULL, 0, 1, '2020-11-19 01:09:49', '2020-11-19 01:09:49'),
(95, 'ACTIONS', 'A_ADD_TRANSACTION', 'Add Transaction', '', 91, 1, NULL, NULL, 0, 1, '2020-11-19 01:09:49', '2020-11-19 01:09:49'),
(96, 'ACTIONS', 'A_EDIT_TRANSACTION', 'Edit Transaction', '', 91, 2, NULL, NULL, 0, 1, '2020-11-19 01:09:50', '2020-11-19 01:09:50'),
(97, 'ACTIONS', 'A_DETAIL_TRANSACTION', 'View Transaction Detail', '', 91, 3, NULL, NULL, 0, 1, '2020-11-19 01:09:50', '2020-11-19 01:09:50'),
(98, 'ACTIONS', 'A_DELETE_TRANSACTION', 'Delete Transaction', '', 91, 4, NULL, NULL, 0, 1, '2020-11-19 01:09:50', '2020-11-19 01:09:50'),
(99, 'ACTIONS', 'A_DELETE_QUANTITY_PURCHASE', 'Delete Quantity Purchase', '', 10, 5, NULL, NULL, 0, 1, '2020-11-19 01:09:50', '2020-11-19 01:09:50'),
(100, 'MAIN_MENU', 'MM_RESTAURANT', 'Restaurant', '', 0, 7, 'fas fa-utensils', 'restaurant.png', 1, 1, '2020-11-19 01:10:07', '2020-11-19 01:10:58'),
(101, 'SUB_MENU', 'SM_RESTAURANT_KITCHEN', 'Kitchen View', 'kitchen', 100, 1, NULL, 'restaurant/kitchen_view.png', 0, 1, '2020-11-19 01:10:07', '2020-11-19 01:10:07'),
(102, 'SUB_MENU', 'SM_RESTAURANT_TABLES', 'Tables', 'tables', 100, 3, NULL, 'restaurant/table_view.png', 0, 1, '2020-11-19 01:10:07', '2020-11-19 01:10:58'),
(103, 'ACTIONS', 'A_ADD_RESTAURANT_TABLE', 'Add Table', '', 102, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:07', '2020-11-19 01:10:07'),
(104, 'ACTIONS', 'A_EDIT_RESTAURANT_TABLE', 'Edit Table', '', 102, 2, NULL, NULL, 0, 1, '2020-11-19 01:10:07', '2020-11-19 01:10:07'),
(105, 'ACTIONS', 'A_DETAIL_RESTAURANT_TABLE', 'View Table Detail', '', 102, 3, NULL, NULL, 0, 1, '2020-11-19 01:10:07', '2020-11-19 01:10:07'),
(106, 'ACTIONS', 'A_CHANGE_KITCHEN_ORDER_STATUS', 'Change Kitchen Order Status', '', 101, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:07', '2020-11-19 01:10:07'),
(107, 'SUB_MENU', 'SM_TARGET', 'Monthly Targets', 'targets', 89, 3, NULL, 'business_account/target.png', 0, 1, '2020-11-19 01:10:16', '2020-11-19 01:10:16'),
(108, 'ACTIONS', 'A_ADD_TARGET', 'Add Target', '', 107, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:16', '2020-11-19 01:10:16'),
(109, 'ACTIONS', 'A_EDIT_TARGET', 'Edit Target', '', 107, 2, NULL, NULL, 0, 1, '2020-11-19 01:10:17', '2020-11-19 01:10:17'),
(110, 'ACTIONS', 'A_DETAIL_TARGET', 'View Target Detail', '', 107, 3, NULL, NULL, 0, 1, '2020-11-19 01:10:17', '2020-11-19 01:10:17'),
(111, 'ACTIONS', 'A_DELETE_TARGET', 'Delete Target', '', 107, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:17', '2020-11-19 01:10:17'),
(112, 'SUB_MENU', 'SM_STOCK_TRANSFER', 'Stock Transfer', 'stock_transfers', 6, 3, NULL, 'inventory/stock_transfer.png', 0, 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(113, 'ACTIONS', 'A_ADD_STOCK_TRANSFER', 'Add New Stock Transfer', '', 112, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(114, 'ACTIONS', 'A_EDIT_STOCK_TRANSFER', 'Edit Stock Transfer', '', 112, 2, NULL, NULL, 0, 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(115, 'ACTIONS', 'A_DETAIL_STOCK_TRANSFER', 'View Stock Transfer Detail', '', 112, 3, NULL, NULL, 0, 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(116, 'ACTIONS', 'A_DELETE_STOCK_TRANSFER', 'Delete Stock Transfer', '', 112, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(117, 'ACTIONS', 'A_VERIFY_STOCK_TRANSFER', 'Verify Stock Transfer Request', '', 112, 5, NULL, NULL, 0, 1, '2020-11-19 01:10:21', '2020-11-19 01:10:21'),
(118, 'ACTIONS', 'A_VIEW_ORDER_LISTING', 'View Order Listing', '', 9, 5, NULL, NULL, 0, 1, '2020-11-19 01:10:23', '2020-11-19 01:10:23'),
(119, 'ACTIONS', 'A_VIEW_INVOICE_LISTING', 'View Invoice Listing', '', 76, 7, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(120, 'ACTIONS', 'A_VIEW_QUANTITY_PURCHASE_LISTING', 'View Quantity Purchase Listing', '', 10, 6, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(121, 'ACTIONS', 'A_VIEW_QUOTATION_LISTING', 'View Quotation Listing', '', 83, 6, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(122, 'ACTIONS', 'A_VIEW_ACCOUNT_LISTING', 'View Account Listing', '', 90, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(123, 'ACTIONS', 'A_VIEW_TRANSACTION_LISTING', 'View Transaction Listing', '', 91, 5, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(124, 'ACTIONS', 'A_VIEW_TARGET_LISTING', 'View Target Listing', '', 107, 5, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(125, 'ACTIONS', 'A_VIEW_USER_LISTING', 'View User Listing', '', 11, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(126, 'ACTIONS', 'A_VIEW_CUSTOMER_LISTING', 'View Customer Listing', '', 12, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(127, 'ACTIONS', 'A_VIEW_ROLE_LISTING', 'View Role Listing', '', 13, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(128, 'ACTIONS', 'A_VIEW_SUPPLIER_LISTING', 'View Supplier Listing', '', 14, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:24', '2020-11-19 01:10:24'),
(129, 'ACTIONS', 'A_VIEW_TAXCODE_LISTING', 'View Tax Code Listing', '', 15, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:25', '2020-11-19 01:10:25'),
(130, 'ACTIONS', 'A_VIEW_DISCOUNTCODE_LISTING', 'View Discount Code Listing', '', 16, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:25', '2020-11-19 01:10:25'),
(131, 'ACTIONS', 'A_VIEW_PRODUCT_LISTING', 'View Product Listing', '', 17, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:25', '2020-11-19 01:10:52'),
(132, 'ACTIONS', 'A_VIEW_CATEGORY_LISTING', 'View Category Listing', '', 18, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:25', '2020-11-19 01:10:25'),
(133, 'ACTIONS', 'A_VIEW_STOCK_TRANSFER_LISTING', 'View Stock Transfer Listing', '', 112, 6, NULL, NULL, 0, 1, '2020-11-19 01:10:25', '2020-11-19 01:10:25'),
(134, 'ACTIONS', 'A_VIEW_KITCHEN_ORDER_LISTING', 'View Kitchen Order Listing', '', 101, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:25', '2020-11-19 01:10:25'),
(135, 'ACTIONS', 'A_VIEW_RESTAURANT_TABLE_LISTING', 'View Table Listing', '', 102, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:25', '2020-11-19 01:10:25'),
(136, 'ACTIONS', 'A_VIEW_STORE_LISTING', 'View Store Listing', '', 19, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:25', '2020-11-19 01:10:25'),
(137, 'ACTIONS', 'A_VIEW_PAYMENT_METHOD_LISTING', 'View Payment Method Listing', '', 20, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:25', '2020-11-19 01:10:25'),
(138, 'SUB_MENU', 'SM_RETURNS', 'Stock Return', 'stock_returns', 6, 4, NULL, 'inventory/stock_return.png', 0, 1, '2020-11-19 01:10:26', '2020-11-19 01:10:26'),
(139, 'ACTIONS', 'A_ADD_STOCK_RETURN', 'Add New Stock Return', '', 138, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:26', '2020-11-19 01:10:26'),
(140, 'ACTIONS', 'A_EDIT_STOCK_RETURN', 'Edit Stock Return', '', 138, 2, NULL, NULL, 0, 1, '2020-11-19 01:10:26', '2020-11-19 01:10:26'),
(141, 'ACTIONS', 'A_DETAIL_STOCK_RETURN', 'View Stock Return Detail', '', 138, 3, NULL, NULL, 0, 1, '2020-11-19 01:10:27', '2020-11-19 01:10:27'),
(142, 'ACTIONS', 'A_DELETE_STOCK_RETURN', 'Delete Stock Return', '', 138, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:27', '2020-11-19 01:10:27'),
(143, 'ACTIONS', 'A_VIEW_STOCK_RETURN_LISTING', 'View Stock Return Listing', '', 138, 5, NULL, NULL, 0, 1, '2020-11-19 01:10:27', '2020-11-19 01:10:27'),
(144, 'MAIN_MENU', 'MM_NOTIFICATION', 'Notification', '', 0, 11, 'fas fa-bell', 'notification.png', 0, 1, '2020-11-19 01:10:30', '2020-11-19 01:10:30'),
(145, 'SUB_MENU', 'SM_NOTIFICATIONS', 'Notifications', 'notifications', 144, 1, NULL, 'notification/notification.png', 0, 1, '2020-11-19 01:10:30', '2020-11-19 01:10:30'),
(146, 'ACTIONS', 'A_ADD_NOTIFICATION', 'Add New Notification', '', 145, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:30', '2020-11-19 01:10:30'),
(147, 'ACTIONS', 'A_DETAIL_NOTIFICATION', 'View Notification', '', 145, 2, NULL, NULL, 0, 1, '2020-11-19 01:10:31', '2020-11-19 01:10:31'),
(148, 'ACTIONS', 'A_DELETE_NOTIFICATION', 'Delete Notification', '', 145, 3, NULL, NULL, 0, 1, '2020-11-19 01:10:31', '2020-11-19 01:10:31'),
(149, 'ACTIONS', 'A_VIEW_NOTIFICATION_LISTING', 'View Notification Listing', '', 145, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:31', '2020-11-19 01:10:31'),
(150, 'SUB_MENU', 'SM_BUSINESS_REGISTERS', 'Business Registers', 'business_registers', 89, 4, NULL, 'business_account/business_registration.png', 0, 1, '2020-11-19 01:10:32', '2020-11-19 01:10:32'),
(151, 'ACTIONS', 'A_VIEW_BUSINESS_REGISTER_LISTING', 'View Business Register Listing', '', 150, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:32', '2020-11-19 01:10:32'),
(152, 'ACTIONS', 'A_DETAIL_BUSINESS_REGISTER', 'View Business Register Detail', '', 150, 2, NULL, NULL, 0, 1, '2020-11-19 01:10:32', '2020-11-19 01:10:32'),
(153, 'ACTIONS', 'A_DELETE_BUSINESS_REGISTER', 'Delete Business Register', '', 150, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:32', '2020-11-19 01:10:32'),
(154, 'SUB_MENU', 'SM_DOWNLOAD_REPORT', 'Download Reports', 'download_reports', 7, 1, NULL, 'reports/download.png', 0, 1, '2020-11-19 01:10:34', '2020-11-19 01:10:34'),
(155, 'SUB_MENU', 'SM_BEST_SELLER', 'Best Seller Report', 'best_seller_report', 7, 2, NULL, 'reports/best_seller.png', 0, 1, '2020-11-19 01:10:34', '2020-11-19 01:10:34'),
(156, 'SUB_MENU', 'SM_DAY_WISE_SALE', 'Day Wise Sale Report', 'day_wise_sale_report', 7, 3, NULL, 'reports/day_wise_sale.png', 0, 1, '2020-11-19 01:10:34', '2020-11-19 01:10:34'),
(157, 'SUB_MENU', 'SM_CATEGORY_REPORT', 'Category Report', 'catgeory_report', 7, 4, NULL, 'reports/category.png', 0, 1, '2020-11-19 01:10:34', '2020-11-19 01:10:34'),
(158, 'SUB_MENU', 'SM_PRODUCT_QUANTITY_ALERT', 'Stock Quantity Alert', 'product_quantity_alert', 7, 5, NULL, 'reports/quantity_alert.png', 0, 1, '2020-11-19 01:10:34', '2020-11-19 01:10:34'),
(159, 'SUB_MENU', 'SM_STORE_STOCK_CHART', 'Store Stock Chart', 'store_stock_chart', 7, 6, NULL, 'reports/stock_chart.png', 0, 1, '2020-11-19 01:10:34', '2020-11-19 01:10:34'),
(160, 'MAIN_MENU', 'MM_IMPORT', 'Import Data', '', 0, 10, 'fas fa-cloud-download-alt', 'import.png', 0, 1, '2020-11-19 01:10:34', '2020-11-19 01:10:34'),
(161, 'SUB_MENU', 'SM_SMS_SETTING', 'SMS Settings', 'sms_setting', 8, 4, NULL, 'setting_sms.png', 0, 1, '2020-11-19 01:10:39', '2020-11-19 01:10:39'),
(162, 'ACTIONS', 'A_EDIT_SMS_SETTING', 'Edit SMS Setting', '', 161, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:39', '2020-11-19 01:10:39'),
(163, 'SUB_MENU', 'SM_SMS_TEMPLATE', 'SMS Templates', 'sms_templates', 8, 5, NULL, 'setting_sms_template.png', 0, 1, '2020-11-19 01:10:42', '2020-11-19 01:10:42'),
(164, 'ACTIONS', 'A_VIEW_SMS_TEMPLATE_LISTING', 'View SMS Template Listing', '', 163, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:42', '2020-11-19 01:10:42'),
(165, 'ACTIONS', 'A_EDIT_SMS_TEMPLATE', 'Edit SMS Template', '', 163, 2, NULL, NULL, 0, 1, '2020-11-19 01:10:42', '2020-11-19 01:10:42'),
(166, 'ACTIONS', 'A_DETAIL_SMS_TEMPLATE', 'View SMS Template', '', 163, 3, NULL, NULL, 0, 1, '2020-11-19 01:10:42', '2020-11-19 01:10:42'),
(167, 'ACTIONS', 'A_SHARE_INVOICE_SMS', 'Send Invoice SMS from Order Detail Page', '', 9, 6, NULL, NULL, 0, 1, '2020-11-19 01:10:42', '2020-11-19 01:10:42'),
(168, 'SUB_MENU', 'SM_BILLING_COUNTERS', 'Billing Counters', 'billing_counters', 89, 5, NULL, 'business_account/billing_counter.png\r\n', 0, 1, '2020-11-19 01:10:44', '2020-11-19 01:10:44'),
(169, 'ACTIONS', 'A_ADD_BILLING_COUNTER', 'Add Billing Counter', '', 168, 1, NULL, NULL, 0, 1, '2020-11-19 01:10:44', '2020-11-19 01:10:44'),
(170, 'ACTIONS', 'A_EDIT_BILLING_COUNTER', 'Edit Billing Counter', '', 168, 2, NULL, NULL, 0, 1, '2020-11-19 01:10:44', '2020-11-19 01:10:44'),
(171, 'ACTIONS', 'A_DETAIL_BILLING_COUNTER', 'View Billing Counter Detail', '', 168, 3, NULL, NULL, 0, 1, '2020-11-19 01:10:44', '2020-11-19 01:10:44'),
(172, 'ACTIONS', 'A_VIEW_BILLING_COUNTER_LISTING', 'View Billing Counter Listing', '', 168, 4, NULL, NULL, 0, 1, '2020-11-19 01:10:44', '2020-11-19 01:10:44'),
(173, 'SUB_MENU', 'SM_MASTER_DASHBOARD', 'Master Dashboard', 'dashboard', 1, 1, NULL, 'dashboard/master_dashboard.png', 0, 1, '2020-11-19 01:10:46', '2020-11-19 01:10:46'),
(174, 'SUB_MENU', 'SM_BILLING_COUNTER_DASHBOARD', 'Billing Counter Dashboard', 'billing_counter_dashboard', 1, 2, NULL, 'dashboard/billing_counter.png', 0, 1, '2020-11-19 01:10:46', '2020-11-19 01:10:46'),
(175, 'ACTIONS', 'A_CREATE_INVOICE_FROM_QP', 'Create Invoice from Quantity Purchase', '', 10, 7, NULL, NULL, 0, 1, '2020-11-19 01:10:52', '2020-11-19 01:10:52'),
(176, 'ACTIONS', 'A_UPLOAD_INGREDIENT', 'Upload Ingredient', '', 21, 6, NULL, NULL, 0, 1, '2020-11-19 01:10:58', '2020-11-19 01:10:58'),
(177, 'ACTIONS', 'A_UPDATE_INGREDIENT', 'Update Ingredient', '', 22, 6, NULL, NULL, 0, 1, '2020-11-19 01:10:58', '2020-11-19 01:10:58'),
(178, 'SUB_MENU', 'SM_RESTAURANT_WAITER', 'Waiter View', 'waiter', 100, 2, NULL, 'restaurant/waiter_view.png', 0, 1, '2020-11-19 01:10:58', '2020-11-19 01:10:58'),
(185, 'MAIN_MENU', 'MM_PURCHASE', 'Purchasing', '', 0, 3, 'fas fa-shopping-cart', 'purchase.png', 0, 1, '2020-11-19 01:08:17', '2020-11-19 01:10:08'),
(187, 'ACTIONS', 'A_SEND_TO_KITCHEN', 'Send to Kitchen', '', 9, 7, NULL, NULL, 0, 1, '2020-11-19 01:10:42', '2020-11-19 01:10:42'),
(188, 'ACTIONS', 'A_VIEW_BRAND', 'View Brand Listing', '', 192, 4, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(189, 'ACTIONS', 'A_DETAIL_BRAND', 'View Brand', '', 192, 3, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(190, 'ACTIONS', 'A_EDIT_BRAND', 'Edit Brand', '', 192, 2, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(191, 'ACTIONS', 'A_ADD_BRAND', 'Add Brand', '', 192, 1, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(192, 'SUB_MENU', 'SM_BRAND', 'Brands', 'brands', 6, 6, NULL, 'setting_brand.png', 1, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(193, 'MAIN_MENU', 'MM_HR', 'HRM', '', 0, 16, 'fas fa-id-card', 'hr.png', 0, 1, '2021-01-12 08:22:34', '2021-01-12 08:22:34'),
(194, 'SUB_MENU', 'SM_MEASUREMENT', 'Measurements', 'measurements', 6, 6, NULL, 'setting_measurment.png', 1, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(195, 'ACTIONS', 'A_ADD_MEASUREMENT', 'Add Measurement', '', 194, 1, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(196, 'ACTIONS', 'A_EDIT_MEASUREMENT', 'Edit Measurement', '', 194, 2, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(197, 'ACTIONS', 'A_DETAIL_MEASUREMENT', 'View Measurement', '', 194, 3, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(198, 'ACTIONS', 'A_VIEW_MEASUREMENT', 'View Measurement Listing', '', 194, 4, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(199, 'SUB_MENU', 'SM_MEASUREMENT_CATEGORY', 'Measurement Categories', 'measurement_categories', 6, 6, NULL, 'setting_measurment.png', 1, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(200, 'ACTIONS', 'A_VIEW_MEASUREMENT_CATEGORY', 'View Measurement Category Listing', '', 199, 4, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(201, 'ACTIONS', 'A_DETAIL_MEASUREMENT_CATEGORY', 'View Measurement Category', '', 199, 3, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(202, 'ACTIONS', 'A_EDIT_MEASUREMENT_CATEGORY', 'Edit Measurement Category', '', 199, 2, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(203, 'ACTIONS', 'A_ADD_MEASUREMENT_CATEGORY', 'Add Measurement Category', '', 199, 1, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(204, 'SUB_MENU', 'SM_LANGUAGE', 'Language', 'lang.list', 8, 8, NULL, 'language.png', 0, 1, '2020-11-19 01:08:18', '2020-11-19 01:08:18'),
(205, 'ACTIONS', 'A_EDIT_LANGUAGE', 'Edit Language', '', 204, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(206, 'ACTIONS', 'A_ADD_LANGUAGE', 'Add Language', '', 204, 2, NULL, NULL, 0, 1, '2021-03-31 01:08:21', '2021-03-31 01:08:21'),
(207, 'ACTIONS', 'A_DETAIL_LANGUAGE', 'View Language Detail', '', 204, 3, NULL, NULL, 0, 1, '2021-03-31 01:08:21', '2021-03-31 01:08:21'),
(208, 'ACTIONS', 'A_LISTING_LANGUAGE', 'View Language Listing', '', 204, 4, NULL, NULL, 0, 1, '2021-03-31 01:08:21', '2021-03-31 01:08:21'),
(209, 'ACTIONS', 'A_PHRASE_LANGUAGE', 'Add Language Phrase', '', 204, 1, NULL, NULL, 0, 1, '2021-03-31 01:08:21', '2021-03-31 01:08:21'),
(210, 'ACTIONS', 'A_ADD_PHRASE_LANGUAGE', 'Add Language Phrase', '', 204, 1, NULL, NULL, 0, 1, '2021-03-31 01:08:21', '2021-03-31 01:08:21'),
(211, 'ACTIONS', 'A_EDIT_PHRASE_LANGUAGE', 'Edit Language Phrase', '', 204, 1, NULL, NULL, 0, 1, '2021-03-31 01:08:21', '2021-03-31 01:08:21'),
(212, 'SUB_MENU', 'SM_RETURN_ORDERS', 'Return Orders', 'return_orders', 2, 5, NULL, 'sales_and_orders/return.png', 0, 1, '2021-04-05 08:20:53', '2021-04-05 08:20:53'),
(213, 'SUB_MENU', 'SM_MODIFIER', 'Modifiers', 'modifiers', 6, 7, NULL, 'setting_measurment.png', 1, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(214, 'ACTIONS', 'A_VIEW_MODIFIER', 'View Modifier', '', 213, 4, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(215, 'ACTIONS', 'A_DETAIL_MODIFIER', 'View Modifier Detail', '', 213, 3, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(216, 'ACTIONS', 'A_EDIT_MODIFIER', 'Edit Modifier', '', 213, 2, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(217, 'ACTIONS', 'A_ADD_MODIFIER', 'Add Modifier', '', 213, 1, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(218, 'SUB_MENU', 'SM_MODIFIER_OPTION', 'Modifier Options', 'modifier_options', 6, 7, NULL, 'setting_measurment.png', 1, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(219, 'ACTIONS', 'A_ADD_MODIFIER_OPTION', 'Add Modifier Option', '', 218, 1, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(220, 'ACTIONS', 'A_EDIT_MODIFIER_OPTION', 'Edit Modifier Option', '', 218, 2, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(221, 'ACTIONS', 'A_DETAIL_MODIFIER_OPTION', 'View Modifier Option Detail', '', 218, 3, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(222, 'ACTIONS', 'A_VIEW_MODIFIER_OPTION', 'View Modifier Option', '', 218, 4, NULL, NULL, 0, 1, '2020-11-19 01:11:03', '2020-11-19 01:11:03'),
(249, 'ACTIONS', 'A_VIEW_QUANTITY_PURCHASE_LISTINGS', 'View Return Order Details', '', 213, 1, NULL, NULL, 0, 1, '2020-11-19 06:38:20', '2020-06-02 06:38:20'),
(250, 'ACTIONS', 'A_VIEW_RETURN_ORDER_LISTING', 'View Order Listing', '', 213, 2, NULL, NULL, 0, 1, '2020-11-19 06:40:23', '2020-06-02 06:40:23'),
(252, 'ACTIONS', 'A_SYNC_ZID_CATEGORY', 'Sync Zid Categories', '', 251, 4, NULL, NULL, 0, 1, '2020-11-19 06:41:03', '2020-11-19 06:41:03'),
(253, 'ACTIONS', 'A_SYNC_ZID_PRODUCT', 'Sync Zid Products', '', 251, 4, NULL, NULL, 0, 1, '2020-11-19 06:41:03', '2020-11-19 06:41:03'),
(254, 'MAIN_MENU', 'MM_LOYALITY', 'Loyality', NULL, 0, 14, NULL, 'loyality.png', 0, 1, '2021-08-25 09:46:36', '2021-08-25 09:46:36'),
(255, 'SUB_MENU', 'SM_USERPOINTS', 'User Points Settings', 'user_points_settings', 254, 1, NULL, 'abkhas.png', 0, 1, '2021-08-25 09:47:38', '2021-08-25 09:47:38'),
(256, 'ACTIONS', 'A_EDIT_USERPOINTS_SETTING', 'Edit User Points Settings', '', 255, 1, NULL, '', 0, 1, '2021-08-25 09:48:09', '2021-08-25 09:48:09'),
(257, 'SUB_MENU', 'SM_HR', 'HR', '', 193, 1, NULL, 'hr.png', 0, 1, '2021-11-05 09:35:47', '2021-11-05 09:35:47'),
(258, 'SUB_MENU', 'SM_FINANCE', 'Finance', '', 193, 1, NULL, 'hr.png', 0, 1, '2021-11-05 09:37:05', '2021-11-05 09:37:05'),
(259, 'SUB_MENU', 'SM_ADMIN', 'Admin', '', 193, 1, NULL, 'hr.png', 0, 1, '2021-11-05 09:37:48', '2021-11-05 09:37:48'),
(260, 'SUB_MENU', 'SM_PURCHASE_ORDER', 'Purchase Orders', 'purchase_orders', 185, 2, NULL, 'purchasing/purchase_order.png', 0, 1, '2020-11-18 19:38:18', '2020-11-18 19:38:18'),
(261, 'ACTIONS', 'A_ADD_PURCHASE_ORDER', 'Add Purchase Order', '', 260, 1, NULL, NULL, 0, 1, '2020-11-19 01:08:21', '2020-11-19 01:08:21'),
(262, 'ACTIONS', 'A_EDIT_PURCHASE_ORDER', 'Edit Purchase Order ', '', 260, 2, NULL, NULL, 0, 1, '2020-11-19 01:08:22', '2020-11-19 01:08:22'),
(263, 'ACTIONS', 'A_DETAIL_PURCHASE_ORDER', 'View Purchase Order Detail', '', 260, 3, NULL, NULL, 0, 1, '2020-11-19 01:08:22', '2020-11-19 01:08:22'),
(264, 'ACTIONS', 'A_EDIT_STATUS_PURCHASE_ORDER', 'Change Purchase Order Status', '', 260, 4, NULL, NULL, 0, 1, '2020-11-19 01:08:22', '2020-11-19 01:09:50'),
(265, 'ACTIONS', 'A_DELETE_PURCHASE_ORDER', 'Delete Purchase Order', '', 260, 5, NULL, NULL, 0, 1, '2020-11-19 01:09:50', '2020-11-19 01:09:50'),
(266, 'ACTIONS', 'A_VIEW_PURCHASE_ORDER_LISTING', 'View Purchase Order Listing', '', 260, 6, NULL, NULL, 0, 1, '2020-11-19 01:09:50', '2020-11-19 01:09:50'),
(267, 'ACTIONS', 'A_CREATE_INVOICE_FROM_PO', 'Create Invoice from Purchase Order', '', 260, 7, NULL, NULL, 0, 1, '2020-11-19 01:09:50', '2020-11-19 01:09:50'),
(268, 'SUB_MENU', 'SM_EMP', 'Employee', '', 193, 1, NULL, 'hr.png', 0, 1, '2021-12-10 11:24:10', '2021-12-10 11:24:10'),
(269, 'SUB_MENU', 'SM_RETURN_INVOICE', 'Return Invoice', 'invoice_return', 2, 6, NULL, 'sales_and_orders/return_invoice.png', 0, 1, '2022-02-17 07:52:55', '2022-02-17 07:52:55'),
(270, 'MAIN_MENU', 'MM_ZID', 'Zid', '', 8, 15, NULL, 'setting_measurment.png', 0, 1, '2022-03-18 06:30:00', '2022-03-18 06:30:00'),
(271, 'SUB_MENU', 'SM_ZID_STORE', 'Stores', 'zid_store', 270, 4, NULL, 'sales_and_orders/pointofsale.png', 0, 1, '2022-03-18 06:30:00', '2022-03-18 06:30:00'),
(272, 'SUB_MENU', 'SM_ZID_ACTION', 'Action', 'zid_action', 270, 4, NULL, 'sales_and_orders/pointofsale.png', 0, 1, '2022-03-18 06:30:00', '2022-03-18 06:30:00'),
(273, 'SUB_MENU', 'SM_QUANTITY_ADJUSTMENT', 'Quantity Adjustment', 'quantity_adjustments', 6, 6, NULL, 'setting_measurment.png', 1, 1, NULL, NULL),
(274, 'ACTIONS', 'A_ADD_QUANTITY_ADJUSTMENT', 'Add Quantity Adjustment', '', 273, 6, NULL, NULL, 0, 1, NULL, NULL),
(275, 'ACTIONS', 'A_EDIT_QUANTITY_ADJUSTMENT', 'Edit Quantity Adjustment', '', 273, 6, NULL, NULL, 0, 1, NULL, NULL),
(276, 'ACTIONS', 'A_VIEW_QUANTITY_ADJUSTMENT', 'View Quantity Adjustment', '', 273, 6, NULL, NULL, 0, 1, NULL, NULL),
(277, 'SUB_MENU', 'SM_DAMAGE_REPORT', 'Damage Report', 'damage_reports', 7, 5, NULL, 'reports/category.png', 0, 1, NULL, NULL),
(278, 'SUB_MENU', 'SM_TAXNAMES', 'Tax Name Master', 'tax_names', 5, 2, NULL, 'tax_and_discount_code/discount_code.png', 0, 1, '2022-07-22 14:32:35', '2022-07-22 14:32:35'),
(279, 'ACTIONS', 'A_ADD_TAXNAME', 'Add Tax Name', NULL, 278, 1, NULL, NULL, 0, 1, '2022-07-22 14:32:35', '2022-07-22 14:32:35'),
(280, 'ACTIONS', 'A_EDIT_TAXNAME', 'Edit Tax Name', NULL, 278, 2, NULL, NULL, 0, 1, '2022-07-22 14:32:35', '2022-07-22 14:32:35'),
(281, 'ACTIONS', 'A_DETAIL_TAXNAME', 'View Tax Name Detail', NULL, 278, 3, NULL, NULL, 0, 1, '2022-07-22 14:32:35', '2022-07-22 14:32:35'),
(282, 'ACTIONS', 'A_VIEW_TAXNAME_LISTING', 'View Tax Name Listing', NULL, 278, 3, NULL, NULL, 0, 1, '2022-07-22 14:32:35', '2022-07-22 14:32:35'),
(283, 'ACTIONS', 'A_AUTOFILL_CLOSE_REGISTER', 'Autofill Close Register', '', 150, 3, NULL, NULL, 0, 1, '2020-11-19 03:40:32', '2020-11-19 03:40:32'),
(284, 'SUB_MENU', 'SM_TAX_RETURN_REPORT', 'Tax Return Report', 'tax_return_report', 7, 8, NULL, 'reports/category.png', 0, 1, '2022-07-22 14:33:00', '2022-07-22 14:33:00'),
(285, 'SUB_MENU', 'SM_INVENTORY', 'Inventory Report', 'inventory_report', 7, 7, NULL, 'reports/inventory.png', 0, 1, '2020-11-19 03:40:34', '2020-11-19 03:40:34'),
(286, 'SUB_MENU', 'SM_INVENTORY_COUNT', 'Inventory Count', 'inventory-count', 6, 8, NULL, 'inventory/inventory_count.png', 0, 1, '2020-11-19 06:11:03', '2020-11-19 06:11:03'),
(287, 'SUB_MENU', 'SM_PAYMENT', 'Payment Report', 'payment_report', 7, 8, NULL, 'reports/payment.png', 0, 1, '2022-07-05 03:40:34', '2020-07-05 03:40:34'),
(288, 'SUB_MENU', 'SM_PRICE', 'Prices', 'prices', 6, 1, '', 'inventory/prices.png', 0, 1, NULL, NULL),
(289, 'ACTIONS', 'A_ADD_PRICE', 'Add Price', '', 288, 1, '', '', 0, 1, NULL, NULL),
(290, 'ACTIONS', 'A_EDIT_PRICE', 'Edit Price', '', 288, 2, '', '', 0, 1, NULL, NULL),
(291, 'ACTIONS', 'A_DETAIL_PRICE', 'View Price Detail', '', 288, 3, '', '', 0, 1, NULL, NULL),
(292, 'ACTIONS', 'A_VIEW_PRICE_LISTING', 'View Price Listing', '', 288, 4, '', '', 0, 1, NULL, NULL),
(293, 'SUB_MENU', 'SM_COMBO', 'Combos', 'combos', 6, 1, '', 'inventory/product.png', 0, 1, NULL, NULL),
(294, 'ACTIONS', 'A_ADD_COMBO_GROUP', 'Add Combo Group', '', 293, 1, '', '', 0, 1, NULL, NULL),
(295, 'ACTIONS', 'A_EDIT_COMBO_GROUP', 'Edit Combo Group', '', 293, 2, '', '', 0, 1, NULL, NULL),
(296, 'ACTIONS', 'A_VIEW_COMBO_GROUP_LISTING', 'View Combo Group Listing', '', 293, 3, '', '', 0, 1, NULL, NULL),
(297, 'ACTIONS', 'A_ADD_COMBO', 'Add Combo', '', 293, 4, '', '', 0, 1, NULL, NULL),
(298, 'ACTIONS', 'A_EDIT_COMBO', 'Edit Combo', '', 293, 5, '', '', 0, 1, NULL, NULL),
(299, 'ACTIONS', 'A_VIEW_COMBO_LISTING', 'View Combo Listing', '', 293, 7, '', '', 0, 1, NULL, NULL),
(300, 'ACTIONS', 'A_HOLD_ORDER', 'Can Hold Order', '', 9, 8, '', '', 0, 1, NULL, NULL),
(301, 'ACTIONS', 'A_GIFT_ORDER', 'Can Gift Order', '', 9, 9, '', '', 0, 1, NULL, NULL),
(302, 'MAIN_MENU', 'MM_MARKETPLACE', 'Marketplace', '', 0, 18, '', 'marketplace.png', 0, 1, NULL, NULL),
(303, 'SUB_MENU', 'SM_QOYOD', 'Qoyod', 'qoyod', 302, 1, '', 'marketplaces/qoyod.png', 0, 1, NULL, NULL),
(304, 'ACTIONS', 'A_ADD_QOYOD', 'Add Qoyod', '', 303, 1, '', '', 0, 1, NULL, NULL),
(305, 'ACTIONS', 'A_EDIT_QOYOD', 'Edit Qoyod', '', 303, 2, '', '', 0, 1, NULL, NULL),
(306, 'ACTIONS', 'A_SYNC_QOYOD_BUSINESS_ACCOUNT', 'Sync Business Account', '', 303, 3, '', '', 0, 1, NULL, NULL),
(307, 'SUB_MENU', 'SM_EXPRESSPAY', 'Expresspay', 'expresspay_setting', 8, 1, '', 'setting_user.png', 0, 1, '2023-04-12 03:18:40', NULL),
(308, 'ACTIONS', 'A_VIEW_EXPRESSPAY_TRANSACTION', 'View Expresspay Transactions', '', 76, 9, '', '', 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `datetime` datetime NOT NULL,
  `sender_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=unseen, 1=seen, 2=delete',
  `receiver_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=unseen, 1=seen, 2=delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2022_08_21_102425_add_platform_type_to_stores_table', 1),
(2, '2022_08_22_140635_create_mobile_cashiers_table', 1),
(3, '2022_08_23_104132_change_response_data_datatype_in_mobile_cashiers', 1),
(4, '2022_08_25_135008_add_column_is_cashier_to_users', 1),
(5, '2022_08_30_093142_change_data_size_of_modifier_option_price', 1),
(6, '2022_09_05_131634_add_price_type_to_products_table', 1),
(7, '2022_09_05_140036_modify_columns_on_products_table', 1),
(8, '2022_09_05_140530_add_default_value_for_category_applied_on_column_in_category', 1),
(9, '2022_09_05_141717_modify_quantity_on_quantity_history_table', 1),
(10, '2022_09_06_134633_change_admin_account_password', 1),
(11, '2022_09_06_142858_modify_discount_codes_table', 1),
(12, '2022_09_14_143904_allow_null_for_product_code_in_stock_transfer_products', 1),
(13, '2022_09_15_140616_add_gift_in_order_product', 1),
(14, '2022_09_16_093748_create_damage_report_menu', 1),
(15, '2022_09_22_131945_add_invoice_color_in_stores_table', 2),
(16, '2022_10_02_173824_create_prices_table', 2),
(17, '2022_10_02_174356_create_product_prices_table', 2),
(18, '2022_10_02_201818_insert_price_status_in_master_status', 2),
(19, '2022_10_05_150524_add_column_price_code_to_prices', 2),
(20, '2022_10_05_211537_change_column_size_in_product_prices_table', 2),
(21, '2022_10_06_160521_add_column_price_id_in_stores_table', 2),
(22, '2022_10_07_142548_add_column_is_price_enabled_to_stores', 2),
(23, '2022_10_07_151020_insert_prices_submenu_to_menus', 2),
(24, '2022_10_09_130214_create_modifier_option_ingredients_table', 2),
(25, '2022_10_19_115943_correcting_invoice_listing_menu_key', 2),
(26, '2022_10_18_171139_create_combo_groups_table', 3),
(27, '2022_10_19_133607_create_combos_table', 3),
(28, '2022_10_19_133638_create_combo_sizes_table', 3),
(29, '2022_10_19_133647_create_combo_products_table', 3),
(30, '2022_10_24_174006_add_column_has_combo_to_orders_table', 3),
(31, '2022_10_24_174243_add_column_combo_id_to_order_products_table', 3),
(32, '2022_10_27_134149_create_edfapay_transactions_table', 3),
(33, '2022_10_30_103247_change_order_in_menus_table', 3),
(34, '2022_10_31_135745_add_column_price_after_discount_in_combo_products', 3),
(35, '2022_11_01_122723_insert_combo_menu_to_menus', 3),
(36, '2022_11_01_130639_insert_combo_status_in_master_status', 3),
(37, '2022_11_01_140258_change_sort_order_for_main_menus_in_menus', 3),
(38, '2022_11_01_185749_add_column_combo_cart_id_to_order_products', 3),
(39, '2022_11_03_115812_insert_return_status_on_master_status_table', 3),
(40, '2022_11_04_135347_change_payment_option_nullable_on_orders_table', 3),
(41, '2022_11_04_135532_change_payment_option_nullable_on_order_return_table', 3),
(42, '2022_11_08_141545_change_discount_value_data_type_on_discount_codes_table', 4),
(43, '2022_11_10_125204_add_column_returning_register_id_to_order_return_table', 4),
(44, '2022_11_17_115600_create_customer_additional_info_table', 5),
(45, '2022_11_27_131552_create_order_duplication_logs_table', 5),
(46, '2022_11_30_135335_insert_gift_and_hold_permition_to_menus', 5),
(47, '2022_12_04_105921_add_combo_fields_to_order_return_product_table', 5),
(48, '2022_12_21_134242_add_column_action_in_quantity_adjustments', 5),
(49, '2022_12_29_131548_insert_into_payment_methods', 5),
(50, '2022_10_20_123327_insert_market_place_and_qoyod_menus_to_menu', 6),
(51, '2022_10_21_072316_create_qoyod_accounts_table', 6),
(52, '2022_10_31_122010_create_qoyod_categories_table', 6),
(53, '2022_10_31_142656_create_qoyod_mesurment_units_table', 6),
(54, '2022_10_31_150149_create_qoyod_users_accounts_table', 6),
(55, '2022_11_02_093333_create_qoyod_products_table', 6),
(56, '2022_11_08_155057_create_qoyod_vendors_table', 6),
(57, '2022_11_10_081717_create_qoyod_customers_table', 6),
(58, '2022_12_05_125101_remove_quotation_number_unique_from_quotations_table', 6),
(59, '2022_12_20_093957_add_qoyod_key_column_to_setting_app_table', 6),
(60, '2022_12_20_094421_drop_qoyod_accounts_table', 6),
(61, '2022_12_21_124950_remove_store_id_from_qoyod_users_accounts_table', 6),
(62, '2022_12_21_160113_create_qoyod_inventory_table', 6),
(63, '2022_12_26_151924_remove_store_id_from_qoyod_categories_table', 6),
(64, '2022_12_27_093437_remove_store_id_from_qoyod_mesurment_units_table', 6),
(65, '2022_12_27_094713_remove_store_id_from_qoyod_vendors_table', 6),
(66, '2022_12_27_094754_remove_store_id_from_qoyod_customers_table', 6),
(67, '2022_12_29_083134_remove_store_id_from_qoyod_products_table', 6),
(68, '2023_01_16_090459_insert_expresspay_into_payment_methods', 6),
(69, '2023_01_16_101449_create_expresspay_table', 6),
(70, '2023_01_24_074250_insert_expresspay_status_in_master_status', 6),
(71, '2023_01_31_145318_add_column_request_json_to_expresspay', 6),
(72, '2023_02_12_114807_change_qoyod_menu_order_in_menus', 6),
(73, '2023_02_11_074504_insert_nearpay_into_payment_methods', 7),
(74, '2023_02_11_074638_add_column_nearpay_json_to_orders_table', 7),
(75, '2023_02_13_205348_insert_expresspay_setting_in_setting_app', 7),
(76, '2023_02_13_212322_insert_expresspay_setting_submenu_in_menus', 7),
(77, '2023_02_16_142128_add_converted_invoice_slack_on_quotation_table', 7),
(78, '2023_02_17_100234_add_contact_column_to_expresspay_table', 7),
(79, '2023_02_22_100603_insert_expresspay_action_into_menus', 7),
(80, '2023_02_22_144543_add_column_invoice_link_to_expresspay', 7),
(81, '2023_02_22_154015_insert_expresspay_permission_for_user_role_menus', 7),
(82, '2023_02_24_121731_add_return_bill_to_id_on_transaction_table', 7),
(83, '2023_02_28_195745_insert_partial_payment_to_master_status_table', 7),
(84, '2023_03_14_102310_create_menu_for_tax_return_report', 7),
(85, '2023_03_22_080521_make_customer_phone_email_optional_in_order_return', 7),
(86, '2023_03_22_140010_insert_discount_type_column_in_invoice_products_table', 7),
(87, '2023_03_28_111838_add_deleted_at_to_customers', 7),
(88, '2023_04_05_095734_add_payment_details_on_invoices_return_table', 7),
(89, '2023_04_06_103933_insert_expresspay_sms_template_in_setting_app', 7);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_cashiers`
--

CREATE TABLE `mobile_cashiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CASHIER' COMMENT 'CASHIER',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `store_id` tinyint(4) NOT NULL,
  `device_id` tinyint(4) NOT NULL,
  `response_data` text COLLATE utf8mb4_unicode_ci,
  `device_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'IPAD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modifiers`
--

CREATE TABLE `modifiers` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_multiple` int(11) NOT NULL COMMENT '0-false,1-True',
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modifier_options`
--

CREATE TABLE `modifier_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `modifier_id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modifier_option_ingredients`
--

CREATE TABLE `modifier_option_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `modifier_option_id` bigint(20) UNSIGNED NOT NULL,
  `ingredient_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `measurement_id` bigint(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text,
  `image` varchar(255) NOT NULL,
  `directory` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `name`, `description`, `image`, `directory`, `status`) VALUES
(39, 'attendance Details ', 'Simple attendance processing System', 'application/modules/attendance/assets/images/thumbnail.jpg', 'attendance', 1),
(40, 'Employee circulation processing System', 'Simple circulation processing System', 'application/modules/circularprocess/assets/images/thumbnail.jpg', 'circularprocess', 1),
(41, 'Employee Details ', 'Simple hrm processing System', 'application/modules/employee/assets/images/thumbnail.jpg', 'employee', 1),
(42, 'Leave Details ', 'Simple leave processing System', 'application/modules/leave/assets/images/thumbnail.jpg', 'leave', 1),
(43, 'Loan Details ', 'Simple loan processing System', 'application/modules/loan/assets/images/thumbnail.jpg', 'loan', 1),
(44, 'TAX Details ', 'Simple tax processing System', 'application/modules/tax/assets/images/thumbnail.jpg', 'tax', 1),
(46, 'Payroll Details ', 'Simple payroll processing System', 'application/modules/payroll/assets/images/thumbnail.jpg', 'payroll', 1),
(48, 'Account', 'Account information', 'application/modules/account/assets/images/thumbnail.jpg', 'account', 1),
(49, 'Notice Details ', 'Simple Notice', 'application/modules/noticeboard/assets/images/thumbnail.jpg', 'noticeboard', 1),
(50, 'Award Details ', 'Simple Award', 'application/modules/award/assets/images/thumbnail.jpg', 'award', 1),
(52, 'asset Details ', 'Simple asset', 'application/modules/asset/assets/images/thumbnail.jpg', 'asset', 1);

-- --------------------------------------------------------

--
-- Table structure for table `module_permission`
--

CREATE TABLE `module_permission` (
  `id` int(11) NOT NULL,
  `fk_module_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `create` tinyint(1) DEFAULT NULL,
  `read` tinyint(1) DEFAULT NULL,
  `update` tinyint(1) DEFAULT NULL,
  `delete` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscription`
--

CREATE TABLE `newsletter_subscription` (
  `id` int(11) NOT NULL,
  `slack` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notice_board`
--

CREATE TABLE `notice_board` (
  `notice_id` int(11) NOT NULL,
  `notice_descriptiion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_date` date NOT NULL,
  `notice_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notice_attachment` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `slack`, `user_id`, `notification_text`, `read`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'dzkO7oX9t3sZSs89BYjrVYjXk', 8, 'TEST2', 0, 1, 2, NULL, '2021-01-20 15:36:41', '2021-01-20 15:36:41'),
(3, '4BisWKMHyZqLVrMtV78sC1RRt', 11, 'sds', 0, 1, 1, NULL, '2021-03-15 07:34:06', '2021-03-15 07:34:06'),
(4, 'UTVaNDuhdh6LZ3Euhyq8Zx59w', 14, 'sds', 0, 1, 1, NULL, '2021-03-15 07:34:06', '2021-03-15 07:34:06');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `order_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'to know from which device order has been created',
  `customer_id` int(11) NOT NULL,
  `customer_phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `counter_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `counter_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL,
  `store_level_discount_code_id` int(11) DEFAULT NULL,
  `store_level_discount_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_level_total_discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `store_level_total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `product_level_total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `store_level_total_tobacco_tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `store_level_total_tobacco_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `store_level_tax_code_id` int(11) DEFAULT NULL,
  `store_level_tax_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_level_total_tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `store_level_total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `store_level_total_tax_components` text COLLATE utf8mb4_unicode_ci,
  `product_level_total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `purchase_amount_subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `sale_amount_subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_discount_before_additional_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_amount_before_additional_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `additional_discount_percentage` decimal(8,2) DEFAULT '0.00',
  `additional_discount_amount` decimal(13,2) DEFAULT '0.00',
  `total_discount_amount` decimal(13,2) DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount_rounded` decimal(13,0) NOT NULL DEFAULT '0',
  `payment_method_id` int(11) DEFAULT NULL,
  `payment_method_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_account_id` int(11) DEFAULT NULL,
  `order_type_id` int(11) DEFAULT NULL,
  `order_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_mode` int(11) NOT NULL DEFAULT '0',
  `bill_type_id` int(11) DEFAULT NULL,
  `bill_type` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `table_number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waiter_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `kitchen_status` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_type` int(11) DEFAULT NULL,
  `payment_option` int(11) DEFAULT NULL,
  `cash_amount` decimal(10,2) DEFAULT NULL,
  `change_amount` decimal(10,2) DEFAULT NULL,
  `card_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_amount` decimal(10,2) DEFAULT NULL,
  `value_date` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_order_amount` decimal(13,2) DEFAULT '0.00',
  `bonat_discount` tinyint(4) NOT NULL DEFAULT '0',
  `reference_number` bigint(11) DEFAULT NULL,
  `has_combo` tinyint(4) DEFAULT NULL,
  `nearpay_json` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_damage`
--

CREATE TABLE `order_damage` (
  `id` int(11) NOT NULL,
  `product` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `branch_reference` varchar(255) NOT NULL,
  `order_type` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `order_reference` int(11) NOT NULL,
  `time` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(5,0) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `order_slack` text NOT NULL,
  `tax_amount` decimal(25,0) NOT NULL DEFAULT '0',
  `discount_amount` decimal(25,0) NOT NULL DEFAULT '0',
  `product_code` decimal(25,0) NOT NULL DEFAULT '0',
  `return_order_id` int(11) DEFAULT '0',
  `order_id` int(11) DEFAULT '0',
  `product_id` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `product_slack` varchar(255) DEFAULT '0',
  `purchase_amount_excluding_tax` decimal(5,2) DEFAULT '0.00',
  `sale_amount_excluding_tax` decimal(5,2) DEFAULT '0.00',
  `discount_code_id` int(11) DEFAULT '0',
  `discount_code` varchar(255) DEFAULT '0',
  `discount_percentage` decimal(5,2) DEFAULT '0.00',
  `tax_code_id` int(11) DEFAULT '0',
  `tax_code` int(11) DEFAULT '0',
  `tax_percentage` decimal(5,2) DEFAULT '0.00',
  `tax_components` decimal(5,2) DEFAULT '0.00',
  `sub_total_purchase_price_excluding_tax` decimal(5,2) DEFAULT '0.00',
  `total_after_discount` decimal(5,2) DEFAULT '0.00',
  `total_amount` decimal(5,2) DEFAULT '0.00',
  `is_ready_to_serve` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `created_by` int(11) DEFAULT '0',
  `is_wastage` int(11) DEFAULT '0',
  `order_product_modifier_options` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_duplication_logs`
--

CREATE TABLE `order_duplication_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_created_at` datetime NOT NULL,
  `order_updated_at` datetime NOT NULL,
  `order_data` json NOT NULL,
  `transaction_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `purchase_amount_excluding_tax` decimal(13,4) NOT NULL,
  `sale_amount_excluding_tax` decimal(13,4) NOT NULL,
  `discount_code_id` int(11) DEFAULT NULL,
  `discount_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `additional_discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_code_id` int(11) DEFAULT NULL,
  `tax_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_components` text COLLATE utf8mb4_unicode_ci,
  `tobacco_tax_components` text COLLATE utf8mb4_unicode_ci,
  `sub_total_purchase_price_excluding_tax` decimal(13,4) NOT NULL,
  `sub_total_sale_price_excluding_tax` decimal(13,4) NOT NULL,
  `discount_amount` decimal(13,4) NOT NULL,
  `total_after_discount` decimal(13,4) NOT NULL,
  `tax_amount` decimal(13,4) NOT NULL,
  `total_amount` decimal(13,4) NOT NULL,
  `is_ready_to_serve` tinyint(4) NOT NULL DEFAULT '0',
  `is_gifted` tinyint(1) DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `modifier_option_id` int(11) DEFAULT NULL,
  `modifier_option_amount` decimal(10,2) DEFAULT NULL,
  `total_modifier_option_amount` decimal(10,2) DEFAULT NULL,
  `total_modifier_amount` double(10,2) DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bonat_discount` tinyint(4) NOT NULL DEFAULT '0',
  `bonat_discount_price` decimal(13,4) NOT NULL,
  `bonat_coupon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `combo_id` int(11) DEFAULT NULL,
  `combo_cart_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_modifier_options`
--

CREATE TABLE `order_product_modifier_options` (
  `id` int(11) UNSIGNED NOT NULL,
  `slack` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `modifier_option_id` int(11) NOT NULL,
  `modifier_option_price` decimal(10,4) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_return`
--

CREATE TABLE `order_return` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `return_order_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_slack` varchar(30) DEFAULT NULL,
  `order_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL,
  `returning_register_id` bigint(20) DEFAULT NULL,
  `store_level_discount_code_id` int(11) DEFAULT NULL,
  `store_level_discount_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_level_total_discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `store_level_total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `product_level_total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `store_level_tax_code_id` int(11) DEFAULT NULL,
  `store_level_tax_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_level_total_tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `store_level_total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `store_level_total_tax_components` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `product_level_total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `purchase_amount_subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `sale_amount_subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_discount_before_additional_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_amount_before_additional_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `additional_discount_percentage` decimal(8,2) DEFAULT '0.00',
  `additional_discount_amount` decimal(13,2) DEFAULT '0.00',
  `total_discount_amount` decimal(13,2) DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount_rounded` decimal(13,0) NOT NULL DEFAULT '0',
  `payment_method_id` int(11) DEFAULT NULL,
  `payment_method_slack` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_account_id` int(11) DEFAULT NULL,
  `order_type_id` int(11) DEFAULT NULL,
  `order_type` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_mode` int(11) NOT NULL DEFAULT '0',
  `bill_type_id` int(11) DEFAULT NULL,
  `bill_type` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `table_number` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waiter_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `kitchen_status` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_type` int(11) DEFAULT NULL,
  `payment_option` int(11) DEFAULT NULL,
  `cash_amount` decimal(10,2) DEFAULT NULL,
  `change_amount` decimal(10,2) DEFAULT NULL,
  `card_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_amount` decimal(10,2) DEFAULT NULL,
  `value_date` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `reference_number` int(11) DEFAULT '0',
  `return_type` varchar(255) DEFAULT 'Return'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_return_product`
--

CREATE TABLE `order_return_product` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_slack` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `purchase_amount_excluding_tax` decimal(13,2) NOT NULL,
  `sale_amount_excluding_tax` decimal(13,2) NOT NULL,
  `discount_code_id` int(11) DEFAULT NULL,
  `discount_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_code_id` int(11) DEFAULT NULL,
  `tax_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_components` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tobacco_tax_components` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sub_total_purchase_price_excluding_tax` decimal(13,2) NOT NULL,
  `sub_total_sale_price_excluding_tax` decimal(13,2) NOT NULL,
  `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(13,2) NOT NULL,
  `is_ready_to_serve` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `return_type` varchar(10) DEFAULT 'Return',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_wastage` tinyint(3) DEFAULT NULL,
  `time` text,
  `reason` text,
  `branch` varchar(255) DEFAULT '0',
  `branch_reference` varchar(255) DEFAULT '0',
  `added_by` varchar(255) DEFAULT '0',
  `order_reference` varchar(255) DEFAULT '0',
  `order_id` int(11) DEFAULT '0',
  `order_slack` varchar(255) DEFAULT '',
  `order_product_modifier_options` text,
  `combo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `combo_cart_id` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_constant` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_1` text COLLATE utf8mb4_unicode_ci,
  `key_2` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `slack`, `payment_constant`, `label`, `key_1`, `key_2`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'wdgH8KPXATLB0VbrShlP42sLP', 'STRIPE', 'Stc Pay', '', '', 'Stripe Payment', 1, 1, 1, '2020-11-18 19:39:47', '2021-03-10 07:16:51'),
(2, 'QSjEAQ36CrtjiBrIQDjYSvcLp', 'PAYPAL', 'Mada', '', '', 'Paypal Payment', 1, 1, 1, '2020-11-18 19:39:48', '2021-03-10 07:16:32'),
(3, 'udOpKjuUTStMScHqEFWXKlslH', 'VISA', 'Visa', '', '', NULL, 1, 1, NULL, '2021-03-10 07:16:19', '2021-03-10 07:16:19'),
(4, 'iviIG0x0pwJzHhvWLMCVsW3Ke', 'CASH', 'Cash', '', '', 'Cash', 1, 1, 1, '2021-03-13 01:57:04', '2021-03-14 02:41:58'),
(5, 'wdgH8KPXATLB0VbrShlP42sLJ', 'SUREPAY', 'Sure Pay', NULL, NULL, 'Sure Pay', 1, NULL, NULL, '2023-01-10 11:11:47', NULL),
(6, 'wdgHgKPXATLB0VbrShlP42sLG', 'EXPRESSPAY', 'Express Pay', NULL, NULL, 'Express Pay', 1, NULL, NULL, '2023-02-13 02:45:32', NULL),
(7, 'wdgH8nPXATLB0VbrShlP42nLJ', 'NEARPAY', 'Near Pay', NULL, NULL, 'Near Pay', 1, NULL, NULL, '2023-04-12 03:18:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_holiday`
--

CREATE TABLE `payroll_holiday` (
  `payrl_holi_id` int(11) UNSIGNED NOT NULL,
  `holiday_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_of_days` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_tax_collection`
--

CREATE TABLE `payroll_tax_collection` (
  `tax_coll_id` int(11) UNSIGNED NOT NULL,
  `employee_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_start` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_tax` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `collection_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_end` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `income_net_period` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_tax_setup`
--

CREATE TABLE `payroll_tax_setup` (
  `tax_setup_id` int(11) UNSIGNED NOT NULL,
  `start_amount` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_amount` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay_frequency`
--

CREATE TABLE `pay_frequency` (
  `id` int(11) NOT NULL,
  `frequency_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pay_frequency`
--

INSERT INTO `pay_frequency` (`id`, `frequency_name`) VALUES
(1, 'Weekly'),
(2, 'Biweekly'),
(3, 'Annual'),
(4, 'Monthly');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `pos_id` int(11) NOT NULL,
  `position_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`pos_id`, `position_name`, `position_details`) VALUES
(1, 'Manager', '');

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` bigint(20) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-Inactive,1-Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_ar` text COLLATE utf8mb4_unicode_ci,
  `show_description_in` int(11) DEFAULT '0',
  `main_category_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `tax_code_id` int(11) NOT NULL,
  `tobacco_tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `is_tobacco_tax` tinyint(4) NOT NULL DEFAULT '0',
  `discount_code_id` int(11) DEFAULT NULL,
  `quantity` decimal(8,2) DEFAULT NULL,
  `alert_quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `purchase_amount_excluding_tax` decimal(13,2) NOT NULL,
  `sale_amount_excluding_tax` decimal(13,4) DEFAULT NULL,
  `sale_amount_including_tax` decimal(13,2) DEFAULT NULL,
  `price_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'fixed',
  `is_ingredient` tinyint(4) NOT NULL DEFAULT '0',
  `is_ingredient_price` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `inventory_type` int(11) DEFAULT NULL,
  `shows_in` int(11) DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `measurement_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_thumb_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_border_color` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_manufacturer_date` date DEFAULT NULL,
  `product_expiry_date` date DEFAULT NULL,
  `is_taxable` tinyint(3) DEFAULT NULL,
  `modifier_id` int(11) DEFAULT NULL,
  `sales_price_including_boolean_and_price` decimal(13,2) DEFAULT NULL,
  `zid_product_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Product Id assigned by ZID Platform',
  `zid_parent_product_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_applied_on` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'all_stores',
  `product_applicable_stores` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_barcode_details`
--

CREATE TABLE `product_barcode_details` (
  `id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `barcode_no` varchar(255) COLLATE utf8mb4_german2_ci NOT NULL,
  `product_slack` varchar(30) COLLATE utf8mb4_german2_ci NOT NULL,
  `is_ingredient` tinyint(1) NOT NULL DEFAULT '0',
  `quantity` decimal(15,2) NOT NULL,
  `show_barcode_value` tinyint(1) NOT NULL DEFAULT '0',
  `show_item_name` tinyint(1) NOT NULL DEFAULT '0',
  `show_item_price_with_vat` tinyint(1) NOT NULL DEFAULT '0',
  `show_store_name` tinyint(1) NOT NULL DEFAULT '0',
  `store_id` int(10) UNSIGNED NOT NULL,
  `show_manufacturing_date` tinyint(1) NOT NULL DEFAULT '0',
  `manufacturing_date` date DEFAULT NULL,
  `show_expiry_date` tinyint(1) NOT NULL DEFAULT '0',
  `expiry_date` date DEFAULT NULL,
  `show_notes` tinyint(1) NOT NULL,
  `notes` text COLLATE utf8mb4_german2_ci,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_german2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `filename` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ingredients`
--

CREATE TABLE `product_ingredients` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `ingredient_product_id` int(11) NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `measurement_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_modifiers`
--

CREATE TABLE `product_modifiers` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `modifier_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_prices`
--

CREATE TABLE `product_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `price_id` bigint(20) NOT NULL,
  `sale_amount_excluding_tax` decimal(10,4) NOT NULL,
  `sale_amount_including_tax` decimal(10,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `po_number` int(11) NOT NULL,
  `po_reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_due_date` date DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_address` text COLLATE utf8mb4_unicode_ci,
  `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_option_id` int(11) DEFAULT NULL,
  `subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `shipping_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `packing_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `terms` text COLLATE utf8mb4_unicode_ci,
  `update_stock` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `business_account_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_type` int(11) DEFAULT NULL,
  `discount_rate` decimal(10,2) DEFAULT NULL,
  `transaction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_products`
--

CREATE TABLE `purchase_order_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_code_id` int(11) NOT NULL DEFAULT '0',
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_components` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `stock_update` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qoyod_categories`
--

CREATE TABLE `qoyod_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `qoyod_category_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qoyod_customers`
--

CREATE TABLE `qoyod_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `qoyod_customer_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qoyod_inventory`
--

CREATE TABLE `qoyod_inventory` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` int(10) UNSIGNED NOT NULL,
  `qoyod_inventory_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qoyod_mesurment_units`
--

CREATE TABLE `qoyod_mesurment_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` int(10) UNSIGNED NOT NULL,
  `qoyod_unit_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qoyod_products`
--

CREATE TABLE `qoyod_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `qoyod_product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qoyod_users_accounts`
--

CREATE TABLE `qoyod_users_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `qoyod_account_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qoyod_vendors`
--

CREATE TABLE `qoyod_vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `qoyod_vendor_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quantity_adjustments`
--

CREATE TABLE `quantity_adjustments` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `reference` varchar(255) DEFAULT '',
  `store_id` int(11) DEFAULT NULL,
  `action` varchar(191) NOT NULL DEFAULT 'DECREMENT' COMMENT 'values: INCREMENT,DECREMENT',
  `reason` varchar(255) DEFAULT NULL COMMENT 'content',
  `status` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `slack` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='newTable';

-- --------------------------------------------------------

--
-- Table structure for table `quantity_adjustment_items`
--

CREATE TABLE `quantity_adjustment_items` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `quantity_adjustment_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='newTable';

-- --------------------------------------------------------

--
-- Table structure for table `quantity_history`
--

CREATE TABLE `quantity_history` (
  `id` int(11) NOT NULL,
  `slack` varchar(55) NOT NULL,
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `type` varchar(55) NOT NULL COMMENT 'PRODUCT,PURCHASE_ORDER,QUANTITY_PURCHASE,QUANTITY_ADJUSTMENT,STOCK_TRANSFER',
  `action` varchar(55) NOT NULL COMMENT 'INCREMENT,DECREMENT',
  `quantity` int(11) DEFAULT NULL,
  `table_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quantity_purchases`
--

CREATE TABLE `quantity_purchases` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `po_number` int(11) NOT NULL,
  `po_reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_due_date` date DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_address` text COLLATE utf8mb4_unicode_ci,
  `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_option_id` int(11) DEFAULT NULL,
  `subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `shipping_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `packing_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `terms` text COLLATE utf8mb4_unicode_ci,
  `update_stock` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `business_account_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_type` int(11) DEFAULT NULL,
  `discount_rate` decimal(10,2) DEFAULT NULL,
  `transaction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quantity_purchase_products`
--

CREATE TABLE `quantity_purchase_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_components` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `stock_update` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `store_id` int(11) NOT NULL,
  `quotation_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quotation_reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_date` date DEFAULT NULL,
  `quotation_due_date` date DEFAULT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci,
  `bill_to` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_to_id` int(11) NOT NULL,
  `bill_to_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_to_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_address` text COLLATE utf8mb4_unicode_ci,
  `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_option_id` int(11) DEFAULT NULL,
  `subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_including_tax` decimal(10,2) NOT NULL,
  `total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `shipping_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `packing_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `converted_invoice_slack` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_products`
--

CREATE TABLE `quotation_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `show_description_in` int(11) DEFAULT '0',
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `measurement_id` int(11) DEFAULT NULL,
  `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_code_id` int(11) DEFAULT NULL,
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_components` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_type` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rate_type`
--

CREATE TABLE `rate_type` (
  `id` int(11) NOT NULL,
  `r_type_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_tables`
--

CREATE TABLE `restaurant_tables` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `table_number` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_of_occupants` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurant_tables`
--

INSERT INTO `restaurant_tables` (`id`, `slack`, `store_id`, `table_number`, `no_of_occupants`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'vkNSo7Su83R6xFEZsqeWk8lMQ', 1, 'Table 1', 5, 1, 1, 1, '2020-11-18 20:20:05', '2021-03-07 06:36:43'),
(2, 'VOvOWNSVa7VRQzESFO3GqV1QF', 2, 'Table No 1', 5, 1, 2, NULL, '2020-11-18 22:55:53', '2020-11-18 22:55:53'),
(3, '16qt641cglZQAFBsi7TgQahb6', 2, 'T5', 5, 1, 2, NULL, '2020-12-09 19:34:11', '2020-12-09 19:34:11'),
(4, 'VRbIXLUuIloXCAG4e2HtT7lFG', 2, 'Table-3', 4, 1, 2, NULL, '2021-01-13 06:39:56', '2021-01-13 06:39:56'),
(5, 'TxTkNMjYIQvRwZEVEPbJRneAD', 1, '2', 4, 1, 1, NULL, '2021-03-07 02:50:00', '2021-03-07 02:50:00'),
(6, 'WBAger4nnTGUaXhK34R0GZiNC', 1, 'Table3', 1, 1, 1, NULL, '2021-03-11 00:38:45', '2021-03-11 00:38:45'),
(7, 'QWHhJD0cEourNcrPqcWoqq7AR', 1, '5', 3, 1, 1, NULL, '2021-03-14 03:56:28', '2021-03-14 03:56:28'),
(8, 'TvnLHa13co17duf0ulI27kTaa', 1, 'Table90', 12, 1, 1, NULL, '2021-03-14 04:21:17', '2021-03-14 04:21:17');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slack`, `role_code`, `label`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '3xakUWNxGkjXf48Me3ps5cE0i', 'AD', 'Administrator', 1, 1, NULL, NULL, NULL),
(2, '3xakUWNxGkjXf48Me3ps5cE0x', 'MR', 'Merchant', 1, 1, 1, NULL, '2021-03-30 04:31:03'),
(3, 'cK7KRQmOeM', 'EMP', 'Employee', 1, 1, NULL, '2021-12-06 21:48:41', '2021-12-06 21:48:41');

-- --------------------------------------------------------

--
-- Table structure for table `role_menus`
--

CREATE TABLE `role_menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_menus`
--

INSERT INTO `role_menus` (`id`, `role_id`, `menu_id`, `created_by`, `created_at`, `updated_at`) VALUES
(466, 3, 1, 1, '2021-12-06 21:48:41', '2021-12-06 21:48:41'),
(467, 3, 173, 1, '2021-12-06 21:48:41', '2021-12-06 21:48:41'),
(468, 3, 193, 1, '2021-12-06 21:48:41', '2021-12-06 21:48:41'),
(469, 3, 256, 1, '2021-12-06 21:48:41', '2021-12-06 21:48:41'),
(479, 1, 1, NULL, NULL, NULL),
(480, 1, 2, NULL, NULL, NULL),
(481, 1, 3, NULL, NULL, NULL),
(482, 1, 4, NULL, NULL, NULL),
(483, 1, 5, NULL, NULL, NULL),
(484, 1, 6, NULL, NULL, NULL),
(485, 1, 7, NULL, NULL, NULL),
(486, 1, 8, NULL, NULL, NULL),
(487, 1, 9, NULL, NULL, NULL),
(488, 1, 10, NULL, NULL, NULL),
(489, 1, 11, NULL, NULL, NULL),
(490, 1, 12, NULL, NULL, NULL),
(491, 1, 13, NULL, NULL, NULL),
(492, 1, 14, NULL, NULL, NULL),
(493, 1, 15, NULL, NULL, NULL),
(494, 1, 16, NULL, NULL, NULL),
(495, 1, 17, NULL, NULL, NULL),
(496, 1, 18, NULL, NULL, NULL),
(497, 1, 19, NULL, NULL, NULL),
(498, 1, 20, NULL, NULL, NULL),
(499, 1, 21, NULL, NULL, NULL),
(500, 1, 22, NULL, NULL, NULL),
(501, 1, 23, NULL, NULL, NULL),
(502, 1, 24, NULL, NULL, NULL),
(503, 1, 25, NULL, NULL, NULL),
(504, 1, 26, NULL, NULL, NULL),
(505, 1, 27, NULL, NULL, NULL),
(506, 1, 28, NULL, NULL, NULL),
(507, 1, 29, NULL, NULL, NULL),
(508, 1, 30, NULL, NULL, NULL),
(509, 1, 31, NULL, NULL, NULL),
(510, 1, 32, NULL, NULL, NULL),
(511, 1, 33, NULL, NULL, NULL),
(512, 1, 34, NULL, NULL, NULL),
(513, 1, 35, NULL, NULL, NULL),
(514, 1, 36, NULL, NULL, NULL),
(515, 1, 37, NULL, NULL, NULL),
(516, 1, 38, NULL, NULL, NULL),
(517, 1, 39, NULL, NULL, NULL),
(518, 1, 40, NULL, NULL, NULL),
(519, 1, 41, NULL, NULL, NULL),
(520, 1, 42, NULL, NULL, NULL),
(521, 1, 43, NULL, NULL, NULL),
(522, 1, 44, NULL, NULL, NULL),
(523, 1, 45, NULL, NULL, NULL),
(524, 1, 46, NULL, NULL, NULL),
(525, 1, 47, NULL, NULL, NULL),
(526, 1, 48, NULL, NULL, NULL),
(527, 1, 49, NULL, NULL, NULL),
(528, 1, 50, NULL, NULL, NULL),
(529, 1, 51, NULL, NULL, NULL),
(530, 1, 52, NULL, NULL, NULL),
(531, 1, 53, NULL, NULL, NULL),
(532, 1, 54, NULL, NULL, NULL),
(533, 1, 55, NULL, NULL, NULL),
(534, 1, 56, NULL, NULL, NULL),
(535, 1, 57, NULL, NULL, NULL),
(536, 1, 58, NULL, NULL, NULL),
(537, 1, 59, NULL, NULL, NULL),
(538, 1, 60, NULL, NULL, NULL),
(539, 1, 61, NULL, NULL, NULL),
(540, 1, 62, NULL, NULL, NULL),
(541, 1, 63, NULL, NULL, NULL),
(542, 1, 64, NULL, NULL, NULL),
(543, 1, 65, NULL, NULL, NULL),
(544, 1, 66, NULL, NULL, NULL),
(545, 1, 67, NULL, NULL, NULL),
(546, 1, 68, NULL, NULL, NULL),
(547, 1, 69, NULL, NULL, NULL),
(548, 1, 70, NULL, NULL, NULL),
(549, 1, 71, NULL, NULL, NULL),
(550, 1, 72, NULL, NULL, NULL),
(551, 1, 73, NULL, NULL, NULL),
(552, 1, 74, NULL, NULL, NULL),
(553, 1, 75, NULL, NULL, NULL),
(554, 1, 76, NULL, NULL, NULL),
(555, 1, 77, NULL, NULL, NULL),
(556, 1, 78, NULL, NULL, NULL),
(557, 1, 79, NULL, NULL, NULL),
(558, 1, 80, NULL, NULL, NULL),
(559, 1, 81, NULL, NULL, NULL),
(560, 1, 82, NULL, NULL, NULL),
(561, 1, 83, NULL, NULL, NULL),
(562, 1, 84, NULL, NULL, NULL),
(563, 1, 85, NULL, NULL, NULL),
(564, 1, 86, NULL, NULL, NULL),
(565, 1, 87, NULL, NULL, NULL),
(566, 1, 88, NULL, NULL, NULL),
(567, 1, 89, NULL, NULL, NULL),
(568, 1, 90, NULL, NULL, NULL),
(569, 1, 91, NULL, NULL, NULL),
(570, 1, 92, NULL, NULL, NULL),
(571, 1, 93, NULL, NULL, NULL),
(572, 1, 94, NULL, NULL, NULL),
(573, 1, 95, NULL, NULL, NULL),
(574, 1, 96, NULL, NULL, NULL),
(575, 1, 97, NULL, NULL, NULL),
(576, 1, 98, NULL, NULL, NULL),
(577, 1, 99, NULL, NULL, NULL),
(578, 1, 100, NULL, NULL, NULL),
(579, 1, 101, NULL, NULL, NULL),
(580, 1, 102, NULL, NULL, NULL),
(581, 1, 103, NULL, NULL, NULL),
(582, 1, 104, NULL, NULL, NULL),
(583, 1, 105, NULL, NULL, NULL),
(584, 1, 106, NULL, NULL, NULL),
(585, 1, 107, NULL, NULL, NULL),
(586, 1, 108, NULL, NULL, NULL),
(587, 1, 109, NULL, NULL, NULL),
(588, 1, 110, NULL, NULL, NULL),
(589, 1, 111, NULL, NULL, NULL),
(590, 1, 112, NULL, NULL, NULL),
(591, 1, 113, NULL, NULL, NULL),
(592, 1, 114, NULL, NULL, NULL),
(593, 1, 115, NULL, NULL, NULL),
(594, 1, 116, NULL, NULL, NULL),
(595, 1, 117, NULL, NULL, NULL),
(596, 1, 118, NULL, NULL, NULL),
(597, 1, 119, NULL, NULL, NULL),
(598, 1, 120, NULL, NULL, NULL),
(599, 1, 121, NULL, NULL, NULL),
(600, 1, 122, NULL, NULL, NULL),
(601, 1, 123, NULL, NULL, NULL),
(602, 1, 124, NULL, NULL, NULL),
(603, 1, 125, NULL, NULL, NULL),
(604, 1, 126, NULL, NULL, NULL),
(605, 1, 127, NULL, NULL, NULL),
(606, 1, 128, NULL, NULL, NULL),
(607, 1, 129, NULL, NULL, NULL),
(608, 1, 130, NULL, NULL, NULL),
(609, 1, 131, NULL, NULL, NULL),
(610, 1, 132, NULL, NULL, NULL),
(611, 1, 133, NULL, NULL, NULL),
(612, 1, 134, NULL, NULL, NULL),
(613, 1, 135, NULL, NULL, NULL),
(614, 1, 136, NULL, NULL, NULL),
(615, 1, 137, NULL, NULL, NULL),
(616, 1, 138, NULL, NULL, NULL),
(617, 1, 139, NULL, NULL, NULL),
(618, 1, 140, NULL, NULL, NULL),
(619, 1, 141, NULL, NULL, NULL),
(620, 1, 142, NULL, NULL, NULL),
(621, 1, 143, NULL, NULL, NULL),
(622, 1, 144, NULL, NULL, NULL),
(623, 1, 145, NULL, NULL, NULL),
(624, 1, 146, NULL, NULL, NULL),
(625, 1, 147, NULL, NULL, NULL),
(626, 1, 148, NULL, NULL, NULL),
(627, 1, 149, NULL, NULL, NULL),
(628, 1, 150, NULL, NULL, NULL),
(629, 1, 151, NULL, NULL, NULL),
(630, 1, 152, NULL, NULL, NULL),
(631, 1, 153, NULL, NULL, NULL),
(632, 1, 154, NULL, NULL, NULL),
(633, 1, 155, NULL, NULL, NULL),
(634, 1, 156, NULL, NULL, NULL),
(635, 1, 157, NULL, NULL, NULL),
(636, 1, 158, NULL, NULL, NULL),
(637, 1, 159, NULL, NULL, NULL),
(638, 1, 160, NULL, NULL, NULL),
(639, 1, 161, NULL, NULL, NULL),
(640, 1, 162, NULL, NULL, NULL),
(641, 1, 163, NULL, NULL, NULL),
(642, 1, 164, NULL, NULL, NULL),
(643, 1, 165, NULL, NULL, NULL),
(644, 1, 166, NULL, NULL, NULL),
(645, 1, 167, NULL, NULL, NULL),
(646, 1, 168, NULL, NULL, NULL),
(647, 1, 169, NULL, NULL, NULL),
(648, 1, 170, NULL, NULL, NULL),
(649, 1, 171, NULL, NULL, NULL),
(650, 1, 172, NULL, NULL, NULL),
(651, 1, 173, NULL, NULL, NULL),
(652, 1, 174, NULL, NULL, NULL),
(653, 1, 175, NULL, NULL, NULL),
(654, 1, 176, NULL, NULL, NULL),
(655, 1, 177, NULL, NULL, NULL),
(656, 1, 178, NULL, NULL, NULL),
(657, 1, 185, NULL, NULL, NULL),
(658, 1, 187, NULL, NULL, NULL),
(659, 1, 188, NULL, NULL, NULL),
(660, 1, 189, NULL, NULL, NULL),
(661, 1, 190, NULL, NULL, NULL),
(662, 1, 191, NULL, NULL, NULL),
(663, 1, 192, NULL, NULL, NULL),
(664, 1, 193, NULL, NULL, NULL),
(665, 1, 194, NULL, NULL, NULL),
(666, 1, 195, NULL, NULL, NULL),
(667, 1, 196, NULL, NULL, NULL),
(668, 1, 197, NULL, NULL, NULL),
(669, 1, 198, NULL, NULL, NULL),
(670, 1, 199, NULL, NULL, NULL),
(671, 1, 200, NULL, NULL, NULL),
(672, 1, 201, NULL, NULL, NULL),
(673, 1, 202, NULL, NULL, NULL),
(674, 1, 203, NULL, NULL, NULL),
(675, 1, 204, NULL, NULL, NULL),
(676, 1, 205, NULL, NULL, NULL),
(677, 1, 206, NULL, NULL, NULL),
(678, 1, 207, NULL, NULL, NULL),
(679, 1, 208, NULL, NULL, NULL),
(680, 1, 209, NULL, NULL, NULL),
(681, 1, 210, NULL, NULL, NULL),
(682, 1, 211, NULL, NULL, NULL),
(683, 1, 212, NULL, NULL, NULL),
(684, 1, 213, NULL, NULL, NULL),
(685, 1, 214, NULL, NULL, NULL),
(686, 1, 215, NULL, NULL, NULL),
(687, 1, 216, NULL, NULL, NULL),
(688, 1, 217, NULL, NULL, NULL),
(689, 1, 218, NULL, NULL, NULL),
(690, 1, 219, NULL, NULL, NULL),
(691, 1, 220, NULL, NULL, NULL),
(692, 1, 221, NULL, NULL, NULL),
(693, 1, 222, NULL, NULL, NULL),
(694, 1, 249, NULL, NULL, NULL),
(695, 1, 250, NULL, NULL, NULL),
(696, 1, 252, NULL, NULL, NULL),
(697, 1, 253, NULL, NULL, NULL),
(698, 1, 254, NULL, NULL, NULL),
(699, 1, 255, NULL, NULL, NULL),
(700, 1, 256, NULL, NULL, NULL),
(701, 1, 257, NULL, NULL, NULL),
(702, 1, 258, NULL, NULL, NULL),
(703, 1, 259, NULL, NULL, NULL),
(704, 1, 260, NULL, NULL, NULL),
(705, 1, 261, NULL, NULL, NULL),
(706, 1, 262, NULL, NULL, NULL),
(707, 1, 263, NULL, NULL, NULL),
(708, 1, 264, NULL, NULL, NULL),
(709, 1, 265, NULL, NULL, NULL),
(710, 1, 266, NULL, NULL, NULL),
(711, 1, 267, NULL, NULL, NULL),
(712, 1, 268, NULL, NULL, NULL),
(713, 1, 269, NULL, NULL, NULL),
(714, 1, 270, NULL, NULL, NULL),
(715, 1, 271, NULL, NULL, NULL),
(716, 1, 272, NULL, NULL, NULL),
(717, 1, 273, NULL, NULL, NULL),
(718, 1, 274, NULL, NULL, NULL),
(719, 1, 275, NULL, NULL, NULL),
(720, 1, 276, NULL, NULL, NULL),
(721, 1, 277, NULL, NULL, NULL),
(722, 1, 278, NULL, NULL, NULL),
(723, 1, 279, NULL, NULL, NULL),
(724, 1, 280, NULL, NULL, NULL),
(725, 1, 281, NULL, NULL, NULL),
(726, 1, 282, NULL, NULL, NULL),
(727, 1, 283, NULL, NULL, NULL),
(728, 1, 284, NULL, NULL, NULL),
(729, 1, 285, NULL, NULL, NULL),
(730, 1, 286, NULL, NULL, NULL),
(731, 1, 287, NULL, NULL, NULL),
(732, 1, 289, NULL, NULL, NULL),
(733, 1, 291, NULL, NULL, NULL),
(734, 1, 290, NULL, NULL, NULL),
(735, 1, 292, NULL, NULL, NULL),
(736, 1, 288, NULL, NULL, NULL),
(737, 1, 297, NULL, NULL, NULL),
(738, 1, 294, NULL, NULL, NULL),
(739, 1, 298, NULL, NULL, NULL),
(740, 1, 295, NULL, NULL, NULL),
(741, 1, 296, NULL, NULL, NULL),
(742, 1, 299, NULL, NULL, NULL),
(743, 1, 293, NULL, NULL, NULL),
(744, 1, 301, NULL, NULL, NULL),
(745, 1, 300, NULL, NULL, NULL),
(746, 1, 304, NULL, NULL, NULL),
(747, 1, 305, NULL, NULL, NULL),
(748, 1, 306, NULL, NULL, NULL),
(749, 1, 302, NULL, NULL, NULL),
(750, 1, 303, NULL, NULL, NULL),
(751, 1, 308, NULL, NULL, NULL),
(752, 1, 307, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `salary_setup_header`
--

CREATE TABLE `salary_setup_header` (
  `s_s_h_id` int(11) UNSIGNED NOT NULL,
  `employee_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary_payable` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `absent_deduct` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_manager` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_setup_header`
--

INSERT INTO `salary_setup_header` (`s_s_h_id`, `employee_id`, `salary_payable`, `absent_deduct`, `tax_manager`, `status`) VALUES
(1, '34', NULL, '0', '0', ''),
(2, '34', NULL, '0', '0', ''),
(3, '34', NULL, '0', '0', ''),
(4, '1', NULL, '0', '0', ''),
(5, '34', NULL, '0', '0', ''),
(6, '34', NULL, '0', '0', ''),
(7, '34', NULL, '0', '0', ''),
(8, '34', NULL, '0', '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `salary_sheet_generate`
--

CREATE TABLE `salary_sheet_generate` (
  `ssg_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gdate` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_date` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generate_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_sheet_generate`
--

INSERT INTO `salary_sheet_generate` (`ssg_id`, `name`, `gdate`, `start_date`, `end_date`, `generate_by`) VALUES
(1, 'March 2020', '2020-03-18', '2020-3-1', '2020-3-31', ''),
(2, 'January 2020', '2020-03-23', '2020-1-1', '2020-1-31', ''),
(3, 'February 2020', '2020-03-23', '2020-2-1', '2020-2-29', ''),
(6, 'August 2020', '2020-03-23', '2020-8-1', '2020-8-31', '');

-- --------------------------------------------------------

--
-- Table structure for table `salary_type`
--

CREATE TABLE `salary_type` (
  `salary_type_id` int(10) UNSIGNED NOT NULL,
  `sal_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_sal_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_amount` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_type`
--

INSERT INTO `salary_type` (`salary_type_id`, `sal_name`, `emp_sal_type`, `default_amount`, `status`) VALUES
(1, 'Health', '1', '', ''),
(2, 'House Rent', '1', '', ''),
(3, 'PF', '0', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `sec_menu_item`
--

CREATE TABLE `sec_menu_item` (
  `menu_id` int(11) NOT NULL,
  `menu_title` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_menu` int(11) DEFAULT NULL,
  `is_report` tinyint(1) DEFAULT NULL,
  `createby` int(11) NOT NULL,
  `createdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sec_menu_item`
--

INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES
(134, 'asset_type', 'type_form', 'asset', 0, 0, 2, '2018-10-04 00:00:00'),
(137, 'equipment', 'equipment_form', 'asset', NULL, 0, 2, '2018-10-04 00:00:00'),
(142, 'asset_assignment', 'maping_form', 'asset', NULL, 0, 2, '2018-10-04 00:00:00'),
(143, 'return', '', 'asset', NULL, 0, 2, '2018-10-04 00:00:00'),
(144, 'return_asset', 'asset_return_form', 'asset', 143, 0, 2, '2018-10-04 00:00:00'),
(145, 'return_list', 'return_list', 'asset', 143, 0, 2, '2018-10-04 00:00:00'),
(147, 'attendance', '', 'attendance', 0, 0, 2, '2018-10-04 00:00:00'),
(148, 'atn_form', 'atnview', 'attendance', 147, 0, 2, '2018-10-04 00:00:00'),
(149, 'new_award', 'award_form', 'award', 0, 0, 2, '2018-10-04 00:00:00'),
(150, 'candidate_basic_info', '', 'recruitment', 0, 0, 2, '2018-10-04 00:00:00'),
(151, 'add_canbasic_info', 'canInfo_form', 'recruitment', 150, 0, 2, '2018-10-04 00:00:00'),
(152, 'can_basicinfo_list', 'canInfoview', 'recruitment', 150, 0, 2, '2018-10-04 00:00:00'),
(153, 'candidate_shortlist', 'shortlist_form', 'recruitment', 0, 0, 2, '2018-10-04 00:00:00'),
(154, 'candidate_interview', 'interview_form', 'recruitment', 0, 0, 2, '2018-10-04 00:00:00'),
(155, 'candidate_selection', 'selection_form', 'recruitment', 0, 0, 2, '2018-10-04 00:00:00'),
(156, 'department', 'dept_form', 'department', 0, 0, 2, '2018-10-04 00:00:00'),
(157, 'division', '', 'department', 0, 0, 2, '2018-10-04 00:00:00'),
(158, 'add_division', 'division_form', 'department', 157, 0, 2, '2018-10-04 00:00:00'),
(159, 'division_list', 'division_list', 'department', 157, 0, 2, '2018-10-04 00:00:00'),
(161, 'position', 'position_form', 'employee', 0, 0, 2, '2018-10-04 00:00:00'),
(162, 'direct_empl', '', 'employee', 0, 0, 2, '2018-10-04 00:00:00'),
(163, 'add_employee', 'employ_form', 'employee', 162, 0, 2, '2018-10-04 00:00:00'),
(164, 'manage_employee', 'employee_view', 'employee', 162, 0, 2, '2018-10-04 00:00:00'),
(165, 'emp_performance', 'emp_performance_form', 'employee', 0, 0, 2, '2018-10-04 00:00:00'),
(167, 'weekly_holiday', 'weeklyform', 'leave', 0, 0, 2, '2018-10-08 00:00:00'),
(168, 'holiday', 'holiday_form', 'leave', 0, 0, 2, '2018-10-08 00:00:00'),
(169, 'others_leave_application', '', 'leave', NULL, 0, 2, '2018-10-08 00:00:00'),
(170, 'loan_grand', 'grandloan_form', 'loan', 0, 0, 2, '2018-10-08 00:00:00'),
(171, 'loan_installment', 'installment_form', 'loan', 0, 0, 2, '2018-10-08 00:00:00'),
(172, 'loan_report', 'ln_report', 'loan', 0, 0, 2, '2018-10-08 00:00:00'),
(173, 'notice', 'notice_form', 'noticeboard', 0, 0, 2, '2018-10-08 00:00:00'),
(174, 'salary_type_setup', 'emp_salarysetup_form', 'payroll', NULL, 0, 2, '2018-10-08 00:00:00'),
(175, 'salary_setup', 'salarysetup_form', 'payroll', 0, 0, 2, '2018-10-08 00:00:00'),
(176, 'salary_generate', 'salary_generate_form', 'payroll', 0, 0, 2, '2018-10-08 00:00:00'),
(177, 'employee_reports', '', 'reports', 0, 0, 2, '2018-10-09 00:00:00'),
(178, 'demographic_report', 'demographic_list', 'reports', 177, 0, 2, '2018-10-09 00:00:00'),
(179, 'posting_report', 'positional_list', 'reports', 177, 0, 2, '2018-10-09 00:00:00'),
(180, 'asset', 'assets_list', 'reports', 177, 0, 2, '2018-10-09 00:00:00'),
(181, 'benifit_report', 'benifit_list', 'reports', 177, 0, 2, '2018-10-09 00:00:00'),
(182, 'custom_report', 'custom_list', 'reports', 177, 0, 2, '2018-10-09 00:00:00'),
(183, 'adhoc_report', 'adhoc_form', 'reports', 0, 0, 2, '2018-10-09 00:00:00'),
(186, 'add_leave_type', 'leave_type_form', 'leave', 169, 0, 2, '2018-10-16 00:00:00'),
(187, 'leave_application', 'other_leave_application_form', 'leave', 169, 0, 2, '2018-10-16 00:00:00'),
(188, 'c_o_a', 'treeview', 'accounts', NULL, 0, 2, '2018-10-18 00:00:00'),
(189, 'balance_adjustment', 'balance_adjustment', 'accounts', 0, 0, 2, '2019-12-14 00:00:00'),
(190, 'cash_adjustment', 'cash_adjustment', 'accounts', 0, 0, 2, '2019-12-14 00:00:00'),
(191, 'bank_adjustment', 'bank_adjustment', 'accounts', 0, 0, 2, '2019-12-14 00:00:00'),
(192, 'payment_type', 'payment_type', 'accounts', 0, 0, 2, '2019-12-14 00:00:00'),
(193, 'debit_voucher', 'debit_voucher', 'accounts', 0, 0, 2, '2018-10-18 00:00:00'),
(194, 'credit_voucher', 'credit_voucher', 'accounts', 0, 0, 2, '2018-10-18 00:00:00'),
(195, 'contra_voucher', 'contra_voucher', 'accounts', 0, 0, 2, '2018-10-18 00:00:00'),
(196, 'journal_voucher', 'journal_voucher', 'accounts', 0, 0, 2, '2018-10-18 00:00:00'),
(197, 'voucher_approval', 'voucher_approve', 'accounts', 0, 0, 2, '2018-10-18 00:00:00'),
(198, 'account_report', '', 'accounts', 0, 0, 2, '2018-10-18 00:00:00'),
(199, 'voucher_report', 'coa', 'accounts', 194, 0, 2, '2018-10-18 00:00:00'),
(200, 'cash_book', 'cash_book', 'accounts', 194, 0, 2, '2018-10-18 00:00:00'),
(201, 'bank_book', 'bank_book', 'accounts', 194, 0, 2, '2018-10-18 00:00:00'),
(202, 'general_ledger', 'general_ledger', 'accounts', 194, 0, 2, '2018-10-18 00:00:00'),
(203, 'trial_balance', 'trial_balance', 'accounts', 194, 0, 2, '2018-10-18 00:00:00'),
(204, 'add_bank', 'add_bank', 'bank', 0, 0, 2, '2019-12-14 00:00:00'),
(205, 'bank_list', 'bank_list', 'bank', 0, 0, 2, '2019-12-14 00:00:00'),
(206, 'profit_loss', 'profit_loss_report', 'accounts', 194, 0, 2, '2018-10-18 00:00:00'),
(207, 'cash_flow', 'cash_flow_report', 'accounts', 194, 0, 2, '2018-10-18 00:00:00'),
(208, 'coa_print', 'coa_print', 'accounts', 194, 0, 2, '2018-10-18 00:00:00'),
(211, 'atn_log_datewise', 'attendance_log_datewise', 'attendance', 147, 0, 2, '2019-12-14 00:00:00'),
(212, 'device_connection', 'device_connect_form', 'attendance', 0, 0, 2, '2019-12-14 00:00:00'),
(213, 'expense_item', 'add_expense', 'expense', 0, 0, 2, '2019-12-14 00:00:00'),
(214, 'expense_sheet', 'expense_sheet', 'expense', 0, 0, 2, '2019-12-14 00:00:00'),
(215, 'expense_statement', 'expense_statement_form', 'expense', 0, 0, 2, '2019-12-14 00:00:00'),
(216, 'income_field', 'add_income', 'income', 0, 0, 2, '2019-12-14 00:00:00'),
(217, 'income_sheet', 'income_sheet', 'income', 0, 0, 2, '2019-12-14 00:00:00'),
(218, 'income_statement', 'income_statement_form', 'income', 0, 0, 2, '2019-12-14 00:00:00'),
(219, 'emp_sal_payment', 'paymentview', 'payroll', 0, 0, 2, '2019-12-14 00:00:00'),
(220, 'Main Menu', 'dashboard/home', 'MainMenu', NULL, NULL, 1, '2021-04-01 19:13:34'),
(221, 'finance', NULL, 'finance', 0, 0, 2, '2021-04-06 11:40:22'),
(222, 'hr', NULL, 'hr', 0, 0, 2, '2021-04-06 11:40:22'),
(225, 'accounts', NULL, 'accounts', 0, 0, 2, '2021-04-06 15:00:08'),
(226, 'asset', NULL, 'asset', NULL, NULL, 2, '2021-04-06 16:14:31'),
(227, 'bank', NULL, 'bank', NULL, NULL, 2, '2021-04-06 16:23:44'),
(228, 'expense', NULL, 'expense', 0, 0, 2, '2021-04-06 16:29:35'),
(229, 'income', NULL, 'income', 0, 0, 2, '2021-04-06 16:32:57'),
(230, 'employee', NULL, 'employee', 0, 0, 2, '2021-04-06 16:49:25'),
(231, 'loan', NULL, 'loan', 0, 0, 2, '2021-04-06 16:53:48'),
(233, 'leave', NULL, 'leave', 0, 0, 2, '2021-04-06 16:55:45'),
(234, 'noticeboard', NULL, 'noticeboard', 0, 0, 2, '2021-04-06 16:59:33'),
(235, 'payroll', NULL, 'payroll', 0, 0, 2, '2021-04-06 17:06:00'),
(236, 'recruitment', NULL, 'recruitment', 0, 0, 2, '2021-04-06 17:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `sec_role_permission`
--

CREATE TABLE `sec_role_permission` (
  `id` bigint(20) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `can_access` tinyint(1) NOT NULL,
  `can_create` tinyint(1) NOT NULL,
  `can_edit` tinyint(1) NOT NULL,
  `can_delete` tinyint(1) NOT NULL,
  `createby` int(11) NOT NULL,
  `createdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sec_role_permission`
--

INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES
(1, 1, 188, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(2, 1, 189, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(3, 1, 190, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(4, 1, 191, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(5, 1, 192, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(6, 1, 193, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(7, 1, 194, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(8, 1, 195, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(9, 1, 196, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(10, 1, 197, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(11, 1, 198, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(12, 1, 199, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(13, 1, 200, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(14, 1, 201, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(15, 1, 202, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(16, 1, 203, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(17, 1, 206, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(18, 1, 207, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(19, 1, 208, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(20, 1, 134, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(21, 1, 137, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(22, 1, 142, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(23, 1, 143, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(24, 1, 144, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(25, 1, 145, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(26, 1, 147, 1, 1, 1, 1, 2, '2021-02-15 06:10:14'),
(27, 1, 148, 1, 1, 1, 1, 2, '2021-02-15 06:10:14'),
(28, 1, 211, 1, 1, 1, 1, 2, '2021-02-15 06:10:14'),
(29, 1, 212, 1, 1, 1, 1, 2, '2021-02-15 06:10:14'),
(30, 1, 149, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(31, 1, 204, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(32, 1, 205, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(33, 1, 156, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(34, 1, 157, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(35, 1, 158, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(36, 1, 159, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(37, 1, 161, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(38, 1, 162, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(39, 1, 163, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(40, 1, 164, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(41, 1, 165, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(42, 1, 213, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(43, 1, 214, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(44, 1, 215, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(45, 1, 216, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(46, 1, 217, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(47, 1, 218, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(48, 1, 167, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(49, 1, 168, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(50, 1, 169, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(51, 1, 186, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(52, 1, 187, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(53, 1, 170, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(54, 1, 171, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(55, 1, 172, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(56, 1, 173, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(57, 1, 174, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(58, 1, 175, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(59, 1, 176, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(60, 1, 219, 1, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(61, 1, 150, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(62, 1, 151, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(63, 1, 152, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(64, 1, 153, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(65, 1, 154, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(66, 1, 155, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(67, 1, 177, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(68, 1, 178, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(69, 1, 179, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(70, 1, 180, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(71, 1, 181, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(72, 1, 182, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(73, 1, 183, 0, 0, 0, 0, 2, '2021-02-15 06:10:14'),
(220, 4, 188, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(221, 4, 189, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(222, 4, 190, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(223, 4, 191, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(224, 4, 192, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(225, 4, 193, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(226, 4, 194, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(227, 4, 195, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(228, 4, 196, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(229, 4, 197, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(230, 4, 198, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(231, 4, 199, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(232, 4, 200, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(233, 4, 201, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(234, 4, 202, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(235, 4, 203, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(236, 4, 206, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(237, 4, 207, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(238, 4, 208, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(239, 4, 134, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(240, 4, 137, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(241, 4, 142, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(242, 4, 143, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(243, 4, 144, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(244, 4, 145, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(245, 4, 147, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(246, 4, 148, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(247, 4, 211, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(248, 4, 212, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(249, 4, 149, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(250, 4, 204, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(251, 4, 205, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(252, 4, 156, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(253, 4, 157, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(254, 4, 158, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(255, 4, 159, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(256, 4, 161, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(257, 4, 162, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(258, 4, 163, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(259, 4, 164, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(260, 4, 165, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(261, 4, 213, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(262, 4, 214, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(263, 4, 215, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(264, 4, 216, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(265, 4, 217, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(266, 4, 218, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(267, 4, 167, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(268, 4, 168, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(269, 4, 169, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(270, 4, 186, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(271, 4, 187, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(272, 4, 170, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(273, 4, 171, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(274, 4, 172, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(275, 4, 173, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(276, 4, 174, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(277, 4, 175, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(278, 4, 176, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(279, 4, 219, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(280, 4, 150, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(281, 4, 151, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(282, 4, 152, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(283, 4, 153, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(284, 4, 154, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(285, 4, 155, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(286, 4, 177, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(287, 4, 178, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(288, 4, 179, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(289, 4, 180, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(290, 4, 181, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(291, 4, 182, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(292, 4, 183, 1, 1, 1, 1, 2, '2021-02-15 06:14:56'),
(754, 3, 188, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(755, 3, 189, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(756, 3, 190, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(757, 3, 191, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(758, 3, 192, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(759, 3, 193, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(760, 3, 194, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(761, 3, 195, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(762, 3, 196, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(763, 3, 197, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(764, 3, 198, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(765, 3, 199, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(766, 3, 200, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(767, 3, 201, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(768, 3, 202, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(769, 3, 203, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(770, 3, 206, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(771, 3, 207, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(772, 3, 208, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(773, 3, 225, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(774, 3, 134, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(775, 3, 137, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(776, 3, 142, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(777, 3, 143, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(778, 3, 144, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(779, 3, 145, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(780, 3, 226, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(781, 3, 147, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(782, 3, 148, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(783, 3, 211, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(784, 3, 212, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(785, 3, 149, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(786, 3, 204, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(787, 3, 205, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(788, 3, 227, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(789, 3, 156, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(790, 3, 157, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(791, 3, 158, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(792, 3, 159, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(793, 3, 161, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(794, 3, 162, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(795, 3, 163, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(796, 3, 164, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(797, 3, 165, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(798, 3, 213, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(799, 3, 214, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(800, 3, 215, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(801, 3, 228, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(802, 3, 221, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(803, 3, 222, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(804, 3, 216, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(805, 3, 217, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(806, 3, 218, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(807, 3, 229, 1, 1, 1, 1, 2, '2021-04-06 11:03:40'),
(808, 3, 167, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(809, 3, 168, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(810, 3, 169, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(811, 3, 186, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(812, 3, 187, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(813, 3, 170, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(814, 3, 171, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(815, 3, 172, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(816, 3, 220, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(817, 3, 173, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(818, 3, 174, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(819, 3, 175, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(820, 3, 176, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(821, 3, 219, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(822, 3, 150, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(823, 3, 151, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(824, 3, 152, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(825, 3, 153, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(826, 3, 154, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(827, 3, 155, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(828, 3, 177, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(829, 3, 178, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(830, 3, 179, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(831, 3, 180, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(832, 3, 181, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(833, 3, 182, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(834, 3, 183, 0, 0, 0, 0, 2, '2021-04-06 11:03:40'),
(1002, 2, 188, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1003, 2, 189, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1004, 2, 190, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1005, 2, 191, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1006, 2, 192, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1007, 2, 193, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1008, 2, 194, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1009, 2, 195, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1010, 2, 196, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1011, 2, 197, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1012, 2, 198, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1013, 2, 199, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1014, 2, 200, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1015, 2, 201, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1016, 2, 202, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1017, 2, 203, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1018, 2, 206, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1019, 2, 207, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1020, 2, 208, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1021, 2, 225, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1022, 2, 134, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1023, 2, 137, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1024, 2, 142, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1025, 2, 143, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1026, 2, 144, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1027, 2, 145, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1028, 2, 226, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1029, 2, 147, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1030, 2, 148, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1031, 2, 211, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1032, 2, 212, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1033, 2, 149, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1034, 2, 204, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1035, 2, 205, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1036, 2, 227, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1037, 2, 156, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1038, 2, 157, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1039, 2, 158, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1040, 2, 159, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1041, 2, 161, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1042, 2, 162, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1043, 2, 163, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1044, 2, 164, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1045, 2, 165, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1046, 2, 230, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1047, 2, 213, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1048, 2, 214, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1049, 2, 215, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1050, 2, 228, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1051, 2, 221, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1052, 2, 222, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1053, 2, 216, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1054, 2, 217, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1055, 2, 218, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1056, 2, 229, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1057, 2, 167, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1058, 2, 168, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1059, 2, 169, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1060, 2, 186, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1061, 2, 187, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1062, 2, 233, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1063, 2, 170, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1064, 2, 171, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1065, 2, 172, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1066, 2, 231, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1067, 2, 220, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1068, 2, 173, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1069, 2, 234, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1070, 2, 174, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1071, 2, 175, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1072, 2, 176, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1073, 2, 219, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1074, 2, 235, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1075, 2, 150, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1076, 2, 151, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1077, 2, 152, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1078, 2, 153, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1079, 2, 154, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1080, 2, 155, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1081, 2, 236, 1, 1, 1, 1, 2, '2021-04-06 11:37:45'),
(1082, 2, 177, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1083, 2, 178, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1084, 2, 179, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1085, 2, 180, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1086, 2, 181, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1087, 2, 182, 0, 0, 0, 0, 2, '2021-04-06 11:37:45'),
(1088, 2, 183, 0, 0, 0, 0, 2, '2021-04-06 11:37:45');

-- --------------------------------------------------------

--
-- Table structure for table `sec_role_tbl`
--

CREATE TABLE `sec_role_tbl` (
  `role_id` int(11) NOT NULL,
  `role_name` text NOT NULL,
  `role_description` text NOT NULL,
  `create_by` int(11) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `role_status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sec_role_tbl`
--

INSERT INTO `sec_role_tbl` (`role_id`, `role_name`, `role_description`, `create_by`, `date_time`, `role_status`) VALUES
(1, 'Employee', 'All employee get default this role', 2, '2020-04-04 11:22:31', 1),
(2, 'Hr', 'Hr Role', 2, '2021-02-15 06:12:06', 1),
(3, 'Finance', 'Finance Role', 2, '2021-02-15 06:13:29', 1),
(4, 'Admin', 'Admin Role', 2, '2021-02-15 06:14:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sec_user_access_tbl`
--

CREATE TABLE `sec_user_access_tbl` (
  `role_acc_id` int(11) NOT NULL,
  `fk_role_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sec_user_access_tbl`
--

INSERT INTO `sec_user_access_tbl` (`role_acc_id`, `fk_role_id`, `fk_user_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('uVru0ipeGcM6MfE5dvbaxQdrpad47xSH3qDcQ2wX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36', 'YToxNjp7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9kZW1vLndvc3VsLnRlc3QvZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo2OiJfdG9rZW4iO3M6NDA6IjVXTUp2YlhiNm52ckVrNjd4alE2UnpON01qbktxYUhoNEFnUnpOeXciO3M6ODoiZnVsbG5hbWUiO3M6MTM6IkFkbWluaXN0cmF0b3IiO3M6OToiZmlyc3RuYW1lIjtOO3M6MTM6InByb2ZpbGVfaW1hZ2UiO047czo1OiJzbGFjayI7czoyNToiSngzcEN5S1Y1S0xnQUl5ZzFtNlJzMm15WSI7czo3OiJ1c2VyX2lkIjtpOjE7czo0OiJyb2xlIjtpOjE7czoxMjoiaW5pdGlhbF9saW5rIjtzOjMyOiJodHRwOi8vZGVtby53b3N1bC50ZXN0L2Rhc2hib2FyZCI7czoxMjoiYWNjZXNzX3Rva2VuIjtzOjIzMzoiZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBjM01pT2lKcWQzUmZkRzlyWlc0aUxDSnpkV0lpT25zaWRYTmxjbDlwWkNJNk1Td2lkWE5sY2w5emJHRmpheUk2SWtwNE0zQkRlVXRXTlV0TVowRkplV2N4YlRaU2N6SnRlVmtpZlN3aWFXRjBJam94TmpFM05qRXlOemd6TENKbGVIQWlPakUyTVRjMk9Ua3hPRE45LjdiMERCU080U1BlaElFRmt5dVlvak44WjF5WFFRcFdWV3lMS2Z4TEVaYzgiO3M6MTM6ImN1cnJlbmN5X2NvZGUiO3M6MzoiU0FSIjtzOjEzOiJjdXJyZW5jeV9uYW1lIjtzOjExOiJTYXVkaSByaXlhbCI7czoxNDoic3RvcmVfdGF4X2NvZGUiO2k6MTtzOjg6InN0b3JlX2lkIjtpOjE7czoxMDoic3RvcmVfbG9nbyI7czowOiIiO30=', 1617612795);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `address` text,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `favicon` varchar(100) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `timezone` varchar(150) NOT NULL,
  `site_align` varchar(50) DEFAULT NULL,
  `footer_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `title`, `address`, `email`, `phone`, `logo`, `favicon`, `language`, `timezone`, `site_align`, `footer_text`) VALUES
(1, 'Bdtask Ltds', '4 th Floor  Mannan Plaza ,Khilkhet,Dhaka-1229', 'bdtask@gmail.com', '0123456789', 'assets/img/icons/2017-07-22/HRM.png', 'assets/img/icons/2017-04-03/m.png', 'english', 'Africa/Casablanca', 'LTR', '2020Ã‚Â©Copyright');

-- --------------------------------------------------------

--
-- Table structure for table `setting_app`
--

CREATE TABLE `setting_app` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_date_time_format` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_date_format` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_print_logo` text COLLATE utf8mb4_unicode_ci,
  `company_logo` text COLLATE utf8mb4_unicode_ci,
  `navbar_logo` text COLLATE utf8mb4_unicode_ci,
  `favicon` text COLLATE utf8mb4_unicode_ci,
  `qoyod_api_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qoyod_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Inactive,1-Active',
  `qoyod_last_sync_time` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_setting_updated_at` datetime DEFAULT NULL,
  `expresspay_merchant_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expresspay_password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expresspay_sms_template` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting_app`
--

INSERT INTO `setting_app` (`id`, `company_name`, `app_date_time_format`, `app_date_format`, `invoice_print_logo`, `company_logo`, `navbar_logo`, `favicon`, `qoyod_api_key`, `qoyod_status`, `qoyod_last_sync_time`, `updated_by`, `created_at`, `updated_at`, `tax_setting_updated_at`, `expresspay_merchant_key`, `expresspay_password`, `expresspay_sms_template`) VALUES
(1, 'WOSUL', 'j-n-Y h:i A', 'j-n-Y', 'logo_invoice_print.png', 'logo_company.png', 'logo_navbar.png', 'favicon.png', NULL, 0, NULL, 1, '2021-03-10 06:58:30', '2021-03-15 11:04:11', NULL, NULL, NULL, 'Thank you for shopping!  Invoice number: [INVOICE_NUMBER] Amount: [AMOUNT]SAR, Link to view your bill: [INVOICE_LINK] Payment link: [PAYMENT_LINK]');

-- --------------------------------------------------------

--
-- Table structure for table `setting_mail`
--

CREATE TABLE `setting_mail` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encryption` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_email_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting_sms_gateways`
--

CREATE TABLE `setting_sms_gateways` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `twilio_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_gateway_settings`
--

CREATE TABLE `sms_gateway_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_gateway_settings`
--

INSERT INTO `sms_gateway_settings` (`id`, `slack`, `api_key`, `user_name`, `sender_name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(6, 'PDsOdx8sXg7YiQnom5lsedfxJ', '.', '.', '.', 0, 1, 1, '2021-03-15 05:52:33', '2021-03-15 11:16:06');

-- --------------------------------------------------------

--
-- Table structure for table `sms_templates`
--

CREATE TABLE `sms_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `available_variables` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_templates`
--

INSERT INTO `sms_templates` (`id`, `slack`, `template_key`, `message`, `available_variables`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '5lke23QT5WRgKZekoaGo7ql8g', 'POS_SALE_BILL_MESSAGE', 'Thank you for shopping. Order {order_number}. Order amount {currency_code} {order_amount}. Link to view your ebill {public_order_link}', '{order_number}, {order_amount}, {currency_code}, {payment_method}, {customer_name}, {customer_email}, {customer_phone}, {order_date}, {public_order_link},{pos_order_receipt_url}', 'This SMS will be sent to the customer while you close an order. Given the customer has a valid phone number updated.', 1, 1, 1, '2020-11-18 19:40:42', '2021-03-14 01:56:22');

-- --------------------------------------------------------

--
-- Table structure for table `stock_returns`
--

CREATE TABLE `stock_returns` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `return_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_date` date DEFAULT NULL,
  `bill_to` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_to_id` int(11) NOT NULL,
  `bill_to_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_to_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_address` text COLLATE utf8mb4_unicode_ci,
  `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_option_id` int(11) DEFAULT NULL,
  `subtotal_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `shipping_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `packing_charge` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_order_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `update_stock` tinyint(4) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_returns`
--

INSERT INTO `stock_returns` (`id`, `slack`, `store_id`, `return_number`, `return_date`, `bill_to`, `bill_to_id`, `bill_to_code`, `bill_to_name`, `bill_to_email`, `bill_to_contact`, `bill_to_address`, `currency_name`, `currency_code`, `tax_option_id`, `subtotal_excluding_tax`, `total_discount_amount`, `total_after_discount`, `total_tax_amount`, `shipping_charge`, `packing_charge`, `total_order_amount`, `update_stock`, `notes`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'kiKp6cUwnxZjK9RTcGzy5DJ9n', 2, '101', '2020-12-03', 'SUPPLIER', 2, 'SUP102', 'Supplier 1', 'supplier@wosul.com', '9898363557', ',', 'Saudi riyal', 'SAR', 4, '500.00', '0.00', '500.00', '75.00', '5.00', '10.00', '590.00', 0, NULL, 1, 2, NULL, '2020-12-02 19:11:37', '2020-12-02 19:11:37'),
(2, '8phqb3eS2JiIingNGRiBKKig3', 2, '102', '2020-12-08', 'SUPPLIER', 2, 'SUP102', 'Supplier 1', 'supplier@wosul.com', '9898363557', ',', 'Saudi riyal', 'SAR', 4, '50.00', '0.00', '50.00', '7.50', '5.00', '10.00', '72.50', 0, 'asdasd', 1, 2, NULL, '2020-12-08 07:32:08', '2020-12-08 07:32:08');

-- --------------------------------------------------------

--
-- Table structure for table `stock_return_products`
--

CREATE TABLE `stock_return_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_return_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `subtotal_amount_excluding_tax` decimal(13,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_code_id` int(11) NOT NULL DEFAULT '0',
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `total_after_discount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `tax_components` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `measurement_id` int(11) DEFAULT NULL,
  `stock_update` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer`
--

CREATE TABLE `stock_transfer` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `stock_transfer_reference` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_store_id` int(11) NOT NULL,
  `from_store_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_store_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_store_id` int(11) NOT NULL,
  `to_store_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_store_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_products`
--

CREATE TABLE `stock_transfer_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_transfer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL DEFAULT '0.00',
  `inward_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'MERGE, NEW',
  `accepted_quantity` decimal(8,2) DEFAULT NULL,
  `destination_product_id` int(11) DEFAULT NULL,
  `destination_product_slack` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_product_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_product_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_number` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tobacco_tax_val` float NOT NULL DEFAULT '0',
  `tax_code_id` int(11) DEFAULT NULL,
  `discount_code_id` int(11) DEFAULT NULL,
  `store_order_return_number` int(11) NOT NULL DEFAULT '0',
  `store_invoice_return_number` int(11) NOT NULL DEFAULT '0',
  `store_order_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `address` text COLLATE utf8mb4_unicode_ci,
  `building_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_seller_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `pincode` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SMALL',
  `currency_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
  `restaurant_mode` int(11) NOT NULL DEFAULT '0',
  `restaurant_waiter_role_id` int(11) DEFAULT NULL,
  `restaurant_billing_type_id` int(11) DEFAULT NULL,
  `enable_customer_popup` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bank_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iban_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_holder_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_invoice_policy_information` text COLLATE utf8mb4_unicode_ci,
  `invoice_policy_information` text COLLATE utf8mb4_unicode_ci,
  `store_invoice_color` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_logo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_policy_information` text COLLATE utf8mb4_unicode_ci,
  `quotation_policy_information` text COLLATE utf8mb4_unicode_ci,
  `zid_store_api_token` text COLLATE utf8mb4_unicode_ci,
  `zid_store_id` int(11) DEFAULT NULL,
  `store_opening_time` text COLLATE utf8mb4_unicode_ci,
  `store_closing_time` text COLLATE utf8mb4_unicode_ci,
  `is_store_closing_next_day` tinyint(1) NOT NULL DEFAULT '0',
  `tax_registration_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idle_time_status` tinyint(3) NOT NULL DEFAULT '0',
  `idle_time` int(11) DEFAULT NULL,
  `platform_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ONLINE' COMMENT 'ONLINE,OFFLINE',
  `platform_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IPAD,ANDROID',
  `price_id` bigint(20) DEFAULT NULL,
  `is_price_enabled` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `slack`, `store_code`, `name`, `tax_number`, `vat_number`, `tobacco_tax_val`, `tax_code_id`, `discount_code_id`, `store_order_return_number`, `store_invoice_return_number`, `store_order_number`, `address`, `building_number`, `street_name`, `district`, `city`, `other_seller_id`, `country_id`, `pincode`, `primary_contact`, `secondary_contact`, `primary_email`, `secondary_email`, `invoice_type`, `currency_name`, `currency_code`, `restaurant_mode`, `restaurant_waiter_role_id`, `restaurant_billing_type_id`, `enable_customer_popup`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `bank_name`, `iban_number`, `account_holder_name`, `pos_invoice_policy_information`, `invoice_policy_information`, `store_invoice_color`, `store_logo`, `purchase_policy_information`, `quotation_policy_information`, `zid_store_api_token`, `zid_store_id`, `store_opening_time`, `store_closing_time`, `is_store_closing_next_day`, `tax_registration_name`, `idle_time_status`, `idle_time`, `platform_mode`, `platform_type`, `price_id`, `is_price_enabled`) VALUES
(1, 'VIkw2q8VQx3kq0mD8MbXZkYm8', 'DEFAULT_STORE', 'DEFAULT STORE', NULL, NULL, 0, 1, NULL, 5, 0, '35', 'SA', NULL, NULL, NULL, NULL, NULL, 191, NULL, NULL, NULL, NULL, NULL, 'A4', 'Saudi riyal', 'SAR', 1, 0, 1, 1, 1, 1, 1, '2020-11-18 20:28:42', '2021-03-15 11:18:07', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '00:00', '23:59', 0, NULL, 0, NULL, 'ONLINE', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `supplier_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `building_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_seller_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `synchronizer_setting`
--

CREATE TABLE `synchronizer_setting` (
  `id` int(11) NOT NULL,
  `hostname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `port` varchar(10) NOT NULL,
  `debug` varchar(10) NOT NULL,
  `project_root` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `targets`
--

CREATE TABLE `targets` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `month` date NOT NULL,
  `income` decimal(13,2) NOT NULL DEFAULT '999999.00',
  `expense` decimal(13,2) NOT NULL DEFAULT '99999.00',
  `sales` decimal(13,2) NOT NULL DEFAULT '999999.00',
  `net_profit` decimal(13,2) NOT NULL DEFAULT '999999.00',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_codes`
--

CREATE TABLE `tax_codes` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `label` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_tax_return` tinyint(1) DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_codes`
--

INSERT INTO `tax_codes` (`id`, `slack`, `store_id`, `label`, `tax_code`, `total_tax_percentage`, `description`, `is_tax_return`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'p5GojGJiIh3sGGAUf8SMYW6Qv', 1, 'VAT', 'DEFAULT_TAX', '15.00', NULL, 0, 1, 1, NULL, '2021-03-02 04:51:17', '2021-03-02 04:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `tax_code_type`
--

CREATE TABLE `tax_code_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `tax_code_id` int(11) NOT NULL,
  `tax_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_name_id` int(11) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_code_type`
--

INSERT INTO `tax_code_type` (`id`, `tax_code_id`, `tax_type`, `tax_percentage`, `tax_name_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'VAT', '15.00', 2, 1, '2021-03-02 04:51:17', '2021-03-02 04:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `tax_names`
--

CREATE TABLE `tax_names` (
  `id` int(11) NOT NULL,
  `tax_name` varchar(100) NOT NULL,
  `percentage` decimal(6,2) NOT NULL,
  `is_visible` tinyint(4) NOT NULL DEFAULT '1',
  `is_default` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tax_names`
--

INSERT INTO `tax_names` (`id`, `tax_name`, `percentage`, `is_visible`, `is_default`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'No Tax', '0.00', 0, 1, '2022-07-22 14:32:35', 1, '2022-07-22 14:32:35', 1),
(2, 'VAT', '15.00', 1, 1, '2022-07-22 14:32:35', 1, '2022-07-22 14:32:35', 1),
(3, 'Zero Tax', '0.00', 1, 1, '2022-07-22 14:32:35', 1, '2022-07-22 14:32:35', 1),
(4, 'Exempt Tax', '0.00', 1, 1, '2022-07-22 14:32:35', 1, '2022-07-22 14:32:35', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `transaction_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` int(11) NOT NULL,
  `transaction_type` int(11) NOT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'POS_ORDER, INVOICE, CUSTOMER, SUPPLIER',
  `bill_to_id` int(11) DEFAULT NULL,
  `bill_to_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_contact` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_address` text COLLATE utf8mb4_unicode_ci,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(13,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `pg_transaction_id` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pg_transaction_status` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `return_bill_to_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login_code` int(8) DEFAULT NULL,
  `init_password` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_reset_token` text COLLATE utf8mb4_unicode_ci,
  `password_reset_max_tries` int(11) NOT NULL DEFAULT '0',
  `password_reset_last_tried_on` datetime DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` text COLLATE utf8mb4_unicode_ci,
  `role_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
  `is_master` tinyint(3) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_cashier` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-false,1-true'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `slack`, `user_code`, `fullname`, `email`, `password`, `login_code`, `init_password`, `password_reset_token`, `password_reset_max_tries`, `password_reset_last_tried_on`, `phone`, `profile_image`, `role_id`, `store_id`, `language_id`, `status`, `is_admin`, `is_master`, `created_by`, `updated_by`, `created_at`, `updated_at`, `is_cashier`) VALUES
(1, 'Jx3pCyKV5KLgAIyg1m6Rs2myY', '100', 'Administrator', 'admin@wosul.sa', '$2y$10$DRTO/InN0Dt5uDrZS2o6SOsQZ7F8h5ElyA2h4dpbZd6r9XsRhVE3.', NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '2020-11-18 14:17:55', '2022-09-21 11:33:09', 0),
(2, 'Jx3pCyKV5KLgAIyg1m6Rs2myM', '101', 'Merchant', 'merchant@wosul.sa', '$2y$10$qu.kaZrS.5auRlEdWyUZvOKY9V7qwyfpjjmJPaS25c2nWDmV7KpWe', NULL, NULL, NULL, 0, NULL, '', NULL, 2, 1, 1, 1, 1, 0, 1, 1, '2020-11-18 14:17:55', '2021-03-23 02:19:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_access_tokens`
--

CREATE TABLE `user_access_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` text COLLATE utf8mb4_unicode_ci,
  `access_token` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_access_tokens`
--

INSERT INTO `user_access_tokens` (`id`, `user_id`, `session_id`, `access_token`, `created_at`, `updated_at`) VALUES
(5, 1, 'Pc0WbYTZ0z8r1sn85glxkFxsnThPMqi9GeDujcD7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0Njc4ODc4LCJleHAiOjE2MTQ3NjUyNzh9.QkUJpYWmLft9BuzPChKDh8NnIBVzgeSeiQodfDnKWpY', '2021-03-02 04:24:38', '2021-03-02 04:24:38'),
(7, 1, 'vXIl9qaYnawK7cZRGhENETk5RAr3231nwLlV9QPb', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0Njc5Mzc2LCJleHAiOjE2MTQ3NjU3NzZ9.q6VsNw811nROD3U3wtdgfqQ1AAFdgsLcExICMTaouuY', '2021-03-02 04:32:56', '2021-03-02 04:32:56'),
(10, 1, '5CzZcWDZJ6GmbKO1dsYpoksVAoihg9hL2D21PrPt', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NjgzMzk4LCJleHAiOjE2MTQ3Njk3OTh9.pOyP6vo_ly05yOvbKumzaMVZ51TgyJA5oOgxkiEE_uM', '2021-03-02 05:39:58', '2021-03-02 05:39:58'),
(11, 1, 'bBBbvdCqGGwUH7jvLA8Oh26iEWEf22cRtoaO9RoS', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0Njg1MTIxLCJleHAiOjE2MTQ3NzE1MjF9.wMz8Lw12PA9KqbsoJ2F2ksTD9l-whhhDSfOpojmyl94', '2021-03-02 06:08:41', '2021-03-02 06:08:41'),
(12, 1, 'Od2VH6FPwCmxtRrNUkIuKaOgkNL8vaCO6d9DBB57', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzUxNjAxLCJleHAiOjE2MTQ4MzgwMDF9.iPiREM8_2RjiqetecxPUb1c0uDpwVg7O3Pp7Z3bOr5g', '2021-03-03 00:36:41', '2021-03-03 00:36:41'),
(13, 1, 'uiCMKnuvwACBt9KzpYBLagjTulXBWWGU9bPgWeBu', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzU0NjkzLCJleHAiOjE2MTQ4NDEwOTN9.YszWQiwfDWsypSuI3Nm8x0ZzVDsVjhlVl8YZ-5_eGlA', '2021-03-03 01:28:13', '2021-03-03 01:28:13'),
(14, 1, 'uiCMKnuvwACBt9KzpYBLagjTulXBWWGU9bPgWeBu', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzU3MjAzLCJleHAiOjE2MTQ4NDM2MDN9.NRpy6Ib9j6DFqwmHsVdaQIg3t19LPkI7jvR_gSVXTXU', '2021-03-03 02:10:03', '2021-03-03 02:10:03'),
(15, 1, 'uiCMKnuvwACBt9KzpYBLagjTulXBWWGU9bPgWeBu', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzU3MjkxLCJleHAiOjE2MTQ4NDM2OTF9.BLiRVWY1AsiW0ljKeU4qmiVHkladdbkkOkRTX5P5pho', '2021-03-03 02:11:31', '2021-03-03 02:11:31'),
(16, 1, 'uiCMKnuvwACBt9KzpYBLagjTulXBWWGU9bPgWeBu', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzU3MzAxLCJleHAiOjE2MTQ4NDM3MDF9.dry_DsEe5vuYkmYVxKOo-yx4id6Qlz9PxZgWpgI2S9w', '2021-03-03 02:11:41', '2021-03-03 02:11:41'),
(17, 1, 'uiCMKnuvwACBt9KzpYBLagjTulXBWWGU9bPgWeBu', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzU3MzA5LCJleHAiOjE2MTQ4NDM3MDl9.b0YBce9knmDROdnDywh10_Zmqn8f1f6d1TmIs179G24', '2021-03-03 02:11:49', '2021-03-03 02:11:49'),
(19, 1, 'uiCMKnuvwACBt9KzpYBLagjTulXBWWGU9bPgWeBu', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzYxNTMzLCJleHAiOjE2MTQ4NDc5MzN9.50QXMmjRjlUVa_0vhNa6r-0LHYBjF6lptqOgWhWBnyc', '2021-03-03 03:22:14', '2021-03-03 03:22:14'),
(21, 1, 'iZ8uro1tZ3hz2ozorA0c8H9E0NYjhMIo5ScRNUEb', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzY0Mzg4LCJleHAiOjE2MTQ4NTA3ODh9.uV5JE6cD66JiD0UrxhP_dJsjpA-F7iEmJwnEK5aixHI', '2021-03-03 04:09:49', '2021-03-03 04:09:49'),
(22, 1, 'uiCMKnuvwACBt9KzpYBLagjTulXBWWGU9bPgWeBu', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzY5NTg0LCJleHAiOjE2MTQ4NTU5ODR9.XM8fX8EvFKYMwdvsIE_QgdypLkwHXomD7687ZQITFzk', '2021-03-03 05:36:24', '2021-03-03 05:36:24'),
(33, 1, 'B8xqCoyByY8xWvYvLV0peGrtBp7tSLJFDHlaYXtn', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0NzgxODM0LCJleHAiOjE2MTQ4NjgyMzR9.aJh-g6JDHWc-V5Vgh1ztW4oPY7txYgJA5C5P1v8inIU', '2021-03-03 09:00:34', '2021-03-03 09:00:34'),
(34, 1, 'B8xqCoyByY8xWvYvLV0peGrtBp7tSLJFDHlaYXtn', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0Nzg0MzI5LCJleHAiOjE2MTQ4NzA3Mjl9.DNfgjcuJ6UlwzUUmY5mAITkvOgw7kwkix2dnvYzYS5s', '2021-03-03 09:42:09', '2021-03-03 09:42:09'),
(35, 1, 'B8xqCoyByY8xWvYvLV0peGrtBp7tSLJFDHlaYXtn', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0Nzg0NzUyLCJleHAiOjE2MTQ4NzExNTJ9.BpmBJUqglVNcxkRw8ACwYC89jaziLJ3UgpcNQOPImUQ', '2021-03-03 09:49:12', '2021-03-03 09:49:12'),
(36, 1, 'B8xqCoyByY8xWvYvLV0peGrtBp7tSLJFDHlaYXtn', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0Nzg0OTM2LCJleHAiOjE2MTQ4NzEzMzZ9.A3Iuq0yn0pA5iPn2a90thlavG7_I_W-UKpB6CN9kggc', '2021-03-03 09:52:16', '2021-03-03 09:52:16'),
(37, 1, '7uG65ZFdbxKO2tO3z6bMOPbXZMoqZSwLQSuOH9O8', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0Nzg4ODE4LCJleHAiOjE2MTQ4NzUyMTh9.BOteqSX8DkjIMRL9iQegf00F46dSBiQiem76vEgRhQw', '2021-03-03 10:56:59', '2021-03-03 10:56:59'),
(38, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODM5MTc1LCJleHAiOjE2MTQ5MjU1NzV9.k5J95_GIQn39dsOJR99Y6X2Rdf9M1XP20heUjFX1n-I', '2021-03-04 00:56:15', '2021-03-04 00:56:15'),
(39, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODM5NDAxLCJleHAiOjE2MTQ5MjU4MDF9.SyBFkrwxnDCL_okKJ81wGZQp3kc4_1x-hN0e3jbocXk', '2021-03-04 01:00:01', '2021-03-04 01:00:01'),
(40, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODM5NDUzLCJleHAiOjE2MTQ5MjU4NTN9.fRIyl_-DJTjIGzDegjcxjM6b1eEFptTDjSthBZdSwMc', '2021-03-04 01:00:54', '2021-03-04 01:00:54'),
(41, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODM5NTQ2LCJleHAiOjE2MTQ5MjU5NDZ9.iXFlHnFYETH1gukRBvkVk8LHWS_r3A7msqJGyNpk4m4', '2021-03-04 01:02:26', '2021-03-04 01:02:26'),
(42, 1, 'MtmFxh03eRqb7NOgWDdOAE0WiwVsll7EPFqof7Hz', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODM5NTU4LCJleHAiOjE2MTQ5MjU5NTh9.1JYcRXFxE_A273pxTlWdi3elSd87NSYfzKctIZGVOzc', '2021-03-04 01:02:38', '2021-03-04 01:02:38'),
(43, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODM5OTQ2LCJleHAiOjE2MTQ5MjYzNDZ9.bYtbASuUYbO24dMJeR5Apiq2OxXqugjS1eUsfi1cd2g', '2021-03-04 01:09:06', '2021-03-04 01:09:06'),
(44, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQwMzc3LCJleHAiOjE2MTQ5MjY3Nzd9.A3sV3DxUJSAe9GmZ63iUk_quE5Fgrt5pvvNHcwf--I4', '2021-03-04 01:16:17', '2021-03-04 01:16:17'),
(45, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQwNTM5LCJleHAiOjE2MTQ5MjY5Mzl9.n93lgm2wSCP_Ued_7041HVGzbgM_SqnpVpBFmtYm4kk', '2021-03-04 01:18:59', '2021-03-04 01:18:59'),
(46, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQwNjM0LCJleHAiOjE2MTQ5MjcwMzR9.EvBt1xSrcCjm8SpmrNL6HveL7Nels31egLErC09Cu1I', '2021-03-04 01:20:34', '2021-03-04 01:20:34'),
(47, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQwNzE5LCJleHAiOjE2MTQ5MjcxMTl9.iIV6Wm0lOzAsKhDdo9PH8C4WRyUlHHQqrt333YU1Xfg', '2021-03-04 01:21:59', '2021-03-04 01:21:59'),
(48, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQwNzY2LCJleHAiOjE2MTQ5MjcxNjZ9.JdTFna4xKYoKrSsBRIVLVynY-qh-aLEh3rmZUjSIYMI', '2021-03-04 01:22:46', '2021-03-04 01:22:46'),
(49, 1, 'ake7g4syogXzlaCizjKaPujkZDsTAEnC2qj51ACX', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ0Nzk2LCJleHAiOjE2MTQ5MzExOTZ9.3cjJZWesi-QdZ3iJFtLBtcNxrmdDMAErODRiSPYro7s', '2021-03-04 02:29:57', '2021-03-04 02:29:57'),
(50, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ0Nzk5LCJleHAiOjE2MTQ5MzExOTl9.NrLdks-IdqxdjtpjN_Yl__v2A4kauuGKMGS5rYK8ig0', '2021-03-04 02:29:59', '2021-03-04 02:29:59'),
(51, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ1ODc4LCJleHAiOjE2MTQ5MzIyNzh9.pbuHd5FssqzEPxQjj1bGwmDWWfyjeJCbHKIONkXbWLk', '2021-03-04 02:47:58', '2021-03-04 02:47:58'),
(52, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ2MTA3LCJleHAiOjE2MTQ5MzI1MDd9.QyYhHJLLWKzH08QCyBvkxaLcmFoct-HPjU7eeM0tj7A', '2021-03-04 02:51:47', '2021-03-04 02:51:47'),
(53, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ3NDkzLCJleHAiOjE2MTQ5MzM4OTN9.LFKgs0rrxdIqpPxyQ813mPOI31LAHfdtG4q3sjDRt5M', '2021-03-04 03:14:53', '2021-03-04 03:14:53'),
(54, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ3ODM0LCJleHAiOjE2MTQ5MzQyMzR9.vA0VUveXl24wme-UJ46F9J0pQz80lvFuo5KF6tTe4Ng', '2021-03-04 03:20:35', '2021-03-04 03:20:35'),
(55, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ4OTM2LCJleHAiOjE2MTQ5MzUzMzZ9.fq5IXeDd6EKLfOAEUf_ImHvoXB7rFTIq_fkp6srR6d0', '2021-03-04 03:38:56', '2021-03-04 03:38:56'),
(56, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ5NTE2LCJleHAiOjE2MTQ5MzU5MTZ9.jzi7mi9HoAYLgp3VOLN81tLGG7B-0TpCmBoQyTw0_tQ', '2021-03-04 03:48:36', '2021-03-04 03:48:36'),
(57, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ5NjE2LCJleHAiOjE2MTQ5MzYwMTZ9.zS61sOf16mOi99H6Bl_PGuhXKJJ_56PJkkkGZHL3Fmw', '2021-03-04 03:50:17', '2021-03-04 03:50:17'),
(58, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODQ5Nzg2LCJleHAiOjE2MTQ5MzYxODZ9.3F7Lb6o2wFtcM2_qukHKutFbyKIgnGAc75n2igsrYn8', '2021-03-04 03:53:07', '2021-03-04 03:53:07'),
(59, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODU2Mjg0LCJleHAiOjE2MTQ5NDI2ODR9.EhLkLxrImY8K0B5QCLWPz8eq_CFEUCXQ7yEtUU3R2Ew', '2021-03-04 05:41:24', '2021-03-04 05:41:24'),
(60, 1, 'hHAHPHDMcRR0s0RVtTC7v8GSBjexjDaKqwauIPre', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODU3Mjg2LCJleHAiOjE2MTQ5NDM2ODZ9.lET4Se-6onTi4uyPSRpm1XhOBcnn6YdoUsBN0i7sxKo', '2021-03-04 05:58:07', '2021-03-04 05:58:07'),
(61, 1, 'hHAHPHDMcRR0s0RVtTC7v8GSBjexjDaKqwauIPre', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODU3NTY4LCJleHAiOjE2MTQ5NDM5Njh9.Gq6UZYOzlWS0adaePOyLw3PMTYg5rLQrgtI45WG4Jd4', '2021-03-04 06:02:48', '2021-03-04 06:02:48'),
(62, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODU4NTQ2LCJleHAiOjE2MTQ5NDQ5NDZ9.8zdXfAcuIo-4G1ZjzJsRmaiU_k9OGvoLkDCZhAkWy0E', '2021-03-04 06:19:06', '2021-03-04 06:19:06'),
(63, 1, 'cIptlxgNcFc0RHRGNWYIMHon2rZjXJo218IdEXT3', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODU5ODYyLCJleHAiOjE2MTQ5NDYyNjJ9.u40TdRV3isSn6-6ED22FLB1erRuxE4P1hfd0CtGPs9Q', '2021-03-04 06:41:02', '2021-03-04 06:41:02'),
(64, 1, 'eTlh4UcIlqgTyzSUildkYFLNGViqmnt4k0lNPHEn', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODYwNDE4LCJleHAiOjE2MTQ5NDY4MTh9.X6qafu2L1CIbJ33q1OOPx_eL5y8nT365vi5R7mwJxO8', '2021-03-04 06:50:18', '2021-03-04 06:50:18'),
(65, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODY2ODI1LCJleHAiOjE2MTQ5NTMyMjV9.zckZkm8RSD5obZ9XLRPkJy2KU32V90zq619KSZgRzho', '2021-03-04 08:37:05', '2021-03-04 08:37:05'),
(66, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODY2ODQ4LCJleHAiOjE2MTQ5NTMyNDh9.2LClqZLxq9Lvw2x5yfm0LAnxhX6gtdaCZQLWsoKMsl4', '2021-03-04 08:37:28', '2021-03-04 08:37:28'),
(67, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODY2ODY1LCJleHAiOjE2MTQ5NTMyNjV9.URnpDtrGV6EtcTP0E56q5H-yClaLvQrFTcfzBGIe1xw', '2021-03-04 08:37:45', '2021-03-04 08:37:45'),
(68, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODY3Mjk5LCJleHAiOjE2MTQ5NTM2OTl9.Mrps6zWCfMSJtSyVqGbzwpBep2z3I2Y4TGzer6OLF-A', '2021-03-04 08:44:59', '2021-03-04 08:44:59'),
(69, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODY3NDE0LCJleHAiOjE2MTQ5NTM4MTR9.YV2YFtoEp4qjlfUjls3MTeE_pll9-dErMb1_-PBHGWs', '2021-03-04 08:46:54', '2021-03-04 08:46:54'),
(70, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODY3NDM4LCJleHAiOjE2MTQ5NTM4Mzh9.HQX8VIUJdnGN8sbETpI1FCzE_sJ4AKR8_ZsKPhFqAII', '2021-03-04 08:47:19', '2021-03-04 08:47:19'),
(71, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODY3NDUyLCJleHAiOjE2MTQ5NTM4NTJ9.3hMbczQnfNBOxV1cQqgsPRKiDClrLGnvepKznN6vSBk', '2021-03-04 08:47:32', '2021-03-04 08:47:32'),
(72, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODcwNDcyLCJleHAiOjE2MTQ5NTY4NzJ9.KDGw4qFU_QuHDo7566Fa8Q0cDzfmUB88B1fCZx-WejE', '2021-03-04 09:37:52', '2021-03-04 09:37:52'),
(73, 1, 'xCXX33N7xnAXHtJY1Yl8QTQqhAcZZMP9KksGmHK7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0ODcwNTI1LCJleHAiOjE2MTQ5NTY5MjV9.w6kpYvKq1Nmyt3flXlhdCccq6QK-YqT9XwlNn8xI8kM', '2021-03-04 09:38:45', '2021-03-04 09:38:45'),
(75, 1, 'RT08IE32f79XgqvSmKnOlRZek4ki1vhVKG0deezc', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0OTIzMTU5LCJleHAiOjE2MTUwMDk1NTl9.9P7NK6F9YKIdpvt7ukqKwm1nP_WkC3I4gJ3iux3R708', '2021-03-05 00:15:59', '2021-03-05 00:15:59'),
(76, 1, '2v3f3OHCyMIQMVuFTVUaWr87z9QYgjkQ7A28aTm5', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0OTIzNzg3LCJleHAiOjE2MTUwMTAxODd9.rD3xyiOqtVi-_eA5KTmS7WA9rBA-kQJhTw194mm19Z8', '2021-03-05 00:26:27', '2021-03-05 00:26:27'),
(77, 1, 'YFPMeDJmv265IRjygU7Z3fmVR76LOi77wW7LiBPv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE0OTM2OTAwLCJleHAiOjE2MTUwMjMzMDB9.GesyJox82OOMXRybMhmmdXu0Z3X-rX2-4NqYN0FO9dM', '2021-03-05 04:05:00', '2021-03-05 04:05:00'),
(78, 1, 'JxGTzTW4CVEt6waFv2mDsQI9GyxL9qOdzWlVt3t7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MDQzNzY1LCJleHAiOjE2MTUxMzAxNjV9.fm_8pSwZiAeD7KPssu3Q5VOf5Tn_oCrQnL443WTxdkM', '2021-03-06 09:46:05', '2021-03-06 09:46:05'),
(80, 1, '7MPFIKcOKiRmZRtdpLB7uA2j7HrMes2oH0g1jXCX', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MDk2NzgyLCJleHAiOjE2MTUxODMxODJ9.OC-7EP91yl_117f8Y5NIcK78h-cbPs0kHhJrQlp3ggY', '2021-03-07 00:29:43', '2021-03-07 00:29:43'),
(81, 1, 'Pxcl5Ag34cBwaHYoaTdRMu3RYKpYVx2ExCjplt7L', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MDk3NzgzLCJleHAiOjE2MTUxODQxODN9.kY-QwO-NF8cZLY4nkAODJqXCC5CklgP341dC3RBT3oM', '2021-03-07 00:46:23', '2021-03-07 00:46:23'),
(83, 1, 'Heh7Ahej8P0LBJUMunNgeTDjS5EYco8IsV0ZVFxa', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MTAzNTA0LCJleHAiOjE2MTUxODk5MDR9.UuoOZKJPlmVL8Z9Ov1AdUnpkSbeEqjP4pkf7EZ5A_8g', '2021-03-07 02:21:44', '2021-03-07 02:21:44'),
(84, 1, 'y3YIKJCLg4n9UKdNiqELVhm2hcleFJQtz1RVXX0T', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MTAzODEzLCJleHAiOjE2MTUxOTAyMTN9.h2UQixti0LP9scSBnmdSBt-DzxMJBB4XBZOakFMDTJk', '2021-03-07 02:26:54', '2021-03-07 02:26:54'),
(85, 1, 'jkGfguBkX80OLceLP98Bxw9qjjSAvpA0DIGlynz9', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MTA3NTkwLCJleHAiOjE2MTUxOTM5OTB9._5P_BOyraIZRK114wkQkSTUQFmeZDotv0NiRD-ic1DU', '2021-03-07 03:29:50', '2021-03-07 03:29:50'),
(91, 1, 'WHffsLCky1sOhR3OWM0uCy7lq7EWZdH5AvZ7ipIz', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MTEwNTg2LCJleHAiOjE2MTUxOTY5ODZ9.Rj1QHvmAELSrHRcogBjQ5nR9M7CBCelcrLD30Mzp5oo', '2021-03-07 04:19:46', '2021-03-07 04:19:46'),
(94, 1, 'u0ALyIQYfRkWQCCK16ELTMFHaOHfiGre9JZZ1ZoT', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MTE2ODYxLCJleHAiOjE2MTUyMDMyNjF9.wgz9oE4r-6E2i8ovRMbNTpWbSPcP_Lj1scK3Hd7egCw', '2021-03-07 06:04:22', '2021-03-07 06:04:22'),
(95, 13, 'YdoV3WFfoO6YslIgBVTzHSF8C1DoG5UUTjmwyLSW', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MTMsInVzZXJfc2xhY2siOiJDMGZkaDdQcW5acW9GREJFb0prUndUZGlMIn0sImlhdCI6MTYxNTExNzc1MiwiZXhwIjoxNjE1MjA0MTUyfQ.Ot4Rq_YFn-LfLQ2fkiNPztKyiTuQUHScf1mgmDAMLys', '2021-03-07 06:19:12', '2021-03-07 06:19:12'),
(96, 1, 'Li099Ke3Ja5cqidV0luYgTvcmtb0KbNEsAwZKOdF', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MTE4NjIzLCJleHAiOjE2MTUyMDUwMjN9.V60BhPEwOmxdoQyvFcoDhyuXmFSJCtZP2SZzKLawq0I', '2021-03-07 06:33:43', '2021-03-07 06:33:43'),
(98, 1, 'uG8T50JONU9cQDuuaVlExp6IQ3tzYWm47s3Cc0Kl', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MTIxMjYwLCJleHAiOjE2MTUyMDc2NjB9.vqhrcbEHCRziXYVnh5FrKwklKo_p-jS-R3xHxuuTw3s', '2021-03-07 07:17:41', '2021-03-07 07:17:41'),
(99, 1, 'xOSMmEDtRg3M1Pf8lJrU5BXwbCl4EC2DF91n9CnB', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MTIyNzA5LCJleHAiOjE2MTUyMDkxMDl9.jWMdEA54vA_iUSS8xnqgFzGGPPKBe68PBigKP-zKnBY', '2021-03-07 07:41:50', '2021-03-07 07:41:50'),
(119, 1, '8qyObcf8ASrjHiQeIsheHktkkFcSXc9MsbGjYsWF', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MjkyNzk5LCJleHAiOjE2MTUzNzkxOTl9.SUaFidW3fY2-fkRmVMn0s0O1Wg51WihZo4QMQ6nONls', '2021-03-09 06:56:40', '2021-03-09 06:56:40'),
(120, 1, '8l7boW5CIW71eKczn1VKaGsPLdpZFX46qmrvjGDO', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MjkzODQ3LCJleHAiOjE2MTUzODAyNDd9.jnURYv3GQGm0KWy8OotMZlG7mgQsXKqwP_fue9UNg20', '2021-03-09 07:14:07', '2021-03-09 07:14:07'),
(121, 1, '4k4pYShbutdSpR8ssR3tcpQlcTBmlqYqxBNqQoR5', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MjkzOTAwLCJleHAiOjE2MTUzODAzMDB9.BYSbwvSy2Py-uK5UINquRa3VJqul8obTrl03A_8t_TI', '2021-03-09 07:15:00', '2021-03-09 07:15:00'),
(122, 1, 'DEvlSgZnOQwHXK8RczOOs95mzFRJU7efuKkLIzfE', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mjk0MjU2LCJleHAiOjE2MTUzODA2NTZ9.UXwwJouGrpXpBeVR37B2sj6ZDpsGrXdUkL_Jfxv0CZQ', '2021-03-09 07:20:56', '2021-03-09 07:20:56'),
(126, 1, 'beu6xt5GtxHUJShZkjZoydVg5IRMz0sVeT45WvRB', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mjk0NTQ2LCJleHAiOjE2MTUzODA5NDZ9.yib_MBujllhO9dXtF7-tyiDcC6ZIfFfBtdFguoiQHAM', '2021-03-09 07:25:46', '2021-03-09 07:25:46'),
(127, 1, 'OEDZRvl2lHOah7ShyxZIKBFR0YbVuKjT7CZ8A8Jr', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mjk0ODM5LCJleHAiOjE2MTUzODEyMzl9.ej49AoORW9vXS-hxcvZQoyhjns8BPgpwx_AcifuAz5U', '2021-03-09 07:30:39', '2021-03-09 07:30:39'),
(128, 1, 'L28WId9QTyJMczS4iynOxwPmT0GbHIweXjobDMSJ', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mjk5MTQxLCJleHAiOjE2MTUzODU1NDF9.KsMQ2KxAqmCiurlYJsWZAdTqoKmkAMPuwBZ5qyRRAXw', '2021-03-09 08:42:21', '2021-03-09 08:42:21'),
(129, 1, 'uvoQuPlEEjqz6xGvXwHiTTkfY5p7ubYPbSmBmsD2', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzAwNzczLCJleHAiOjE2MTUzODcxNzN9.qjaU8vmwlbae1WTb9cu7G933Nr23bKQALxDV3D6xS4g', '2021-03-09 09:09:33', '2021-03-09 09:09:33'),
(130, 1, 'GCRpA4S0UWbl4xb97ZQgkv7nCNNT43DKR7ztW1Jf', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzA1MDQxLCJleHAiOjE2MTUzOTE0NDF9.lHSJtWYqKAuBAxp_SQgb9-ipDUPVAbEnHgvWrCAOlDw', '2021-03-09 10:20:42', '2021-03-09 10:20:42'),
(131, 1, 'rfkto4jv3oARGsfhkz4i01W8dAkRhElziWuj2MXV', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzEwNTc0LCJleHAiOjE2MTUzOTY5NzR9._2Q7VVEVpHhV7bD-QJs44Q1B6IB3HQxltP7bW1nQwv8', '2021-03-09 11:52:54', '2021-03-09 11:52:54'),
(133, 1, 'CjO4oAiTLhM2Ypd9d9Te3tCqrPjzxbjP1ZSonPh5', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzUyNDg2LCJleHAiOjE2MTU0Mzg4ODZ9.KgZpzurwJSQxRGOS0-2IWsx1j54sSvBngJ8IljleiO4', '2021-03-09 23:31:26', '2021-03-09 23:31:26'),
(138, 1, 'eFxhPxZ3Fr5ugGzI6UtjviYenNma2UDowhwERLTw', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzYyOTUxLCJleHAiOjE2MTU0NDkzNTF9.Ss2h9pvP71LYeraO5QsohjvxpiefbBQC-meWLVUfYwE', '2021-03-10 02:25:51', '2021-03-10 02:25:51'),
(139, 1, 'gQ4r16dIGVHNCXNKMvMEMsVtZRzWOsov9q7Cexa8', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzYzMzgyLCJleHAiOjE2MTU0NDk3ODJ9.Bt543QOoxGy3lveR7RpMmUjUu-VmJoRSc9EVTW7jkmQ', '2021-03-10 02:33:02', '2021-03-10 02:33:02'),
(140, 1, 'mhIJp7xh4b4qKkTpLJKuMgIjibP6Z4FH07AL1fub', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzYzNjg2LCJleHAiOjE2MTU0NTAwODZ9.c_cSPg_EK4BEO6Gir-mJMHH4J896rUxeSnqYcIihLxo', '2021-03-10 02:38:06', '2021-03-10 02:38:06'),
(144, 1, 'xLAMXrZODyx8at5O7owpQB56h6lNXAKmwBIJfT4I', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzY0Nzk5LCJleHAiOjE2MTU0NTExOTl9.KPWMvOOQrdk78FGwJ73Ixk7wLWqxkWlMO0a1BK6fBEU', '2021-03-10 02:56:39', '2021-03-10 02:56:39'),
(145, 1, '0EUn5BtIdy0jSXo87SDRfRS4lMNiU5HaaZKJJJON', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzY2MjY1LCJleHAiOjE2MTU0NTI2NjV9.Ue5BVr0g6oc1RHDHoKttdU7SXKdb8kh9tcf5TxnTxa4', '2021-03-10 03:21:05', '2021-03-10 03:21:05'),
(152, 1, 'Lp5qt1cIaZV6H3000COtYZvnVUl6PlIegtFQGnqI', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzczNzUzLCJleHAiOjE2MTU0NjAxNTN9.YpAc9b6cwZvCIC-d79r_JqAhBXAuKzNOYjNXHvLfMDU', '2021-03-10 05:25:53', '2021-03-10 05:25:53'),
(154, 1, 'VR1NlsD3VEgwTrVWM8xF91fXT6ghGs2zgmQhNUNz', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mzc3MzA0LCJleHAiOjE2MTU0NjM3MDR9.1RjQEouJRwJVCyd2eAd5xxK9wfPrVmUjihdSF6JWfuY', '2021-03-10 06:25:04', '2021-03-10 06:25:04'),
(155, 1, 'zM3rtGaoAS1BMKjEM6H40xaoiAK5WbZ7e9uVdjto', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mzc5MzkwLCJleHAiOjE2MTU0NjU3OTB9.NS2wn-kHoKz2_QI-b159Ba0OEuwzo6MGf1AqcGXWSxU', '2021-03-10 06:59:50', '2021-03-10 06:59:50'),
(156, 1, 'ms7iSOy25jBQ5K0WoMgMEaVXHWIetwyDczyZ5tX4', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzgwNjUxLCJleHAiOjE2MTU0NjcwNTF9.BqTqp-eXlwJU1q73jcFsnpeZrvtW_bKunWVwfYWWgOQ', '2021-03-10 07:20:51', '2021-03-10 07:20:51'),
(162, 1, 'Imgnd9aOjYrGe2tD5ReyUR4Xp9FEMeoIDhBOsX5t', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzgxNjMxLCJleHAiOjE2MTU0NjgwMzF9.DNHqyD02ZhUYDXc1sgQtTG129-Ku-hy2svPfLHqvXJg', '2021-03-10 07:37:11', '2021-03-10 07:37:11'),
(169, 1, '3ZYh2MbNTxh6d3fc6aFyON6sAltnd8qVQZ32fPqz', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzgyODk0LCJleHAiOjE2MTU0NjkyOTR9.M135RSjChnYRhynlRVDQMDSuhoKD0PPdYGsI3CBzYA8', '2021-03-10 07:58:14', '2021-03-10 07:58:14'),
(172, 1, 'qZI6hbRiNiUt6DWRUOIR2jOEHAs978qtYcd2TJTl', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mzg0MTAxLCJleHAiOjE2MTU0NzA1MDF9.SZ4VRh0W3J-7-VRk0kmM3Kw66ShIoC4YanqF8xZng-I', '2021-03-10 08:18:21', '2021-03-10 08:18:21'),
(178, 1, 'xGJUSX7GjXwaxqEV61bsk21aZZGle4lkeSxMRR8F', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mzg1ODMzLCJleHAiOjE2MTU0NzIyMzN9.8KR0DLGk4NMjjPGdki2xdNkLi0WVo2rQbtc8mqGWS7U', '2021-03-10 08:47:13', '2021-03-10 08:47:13'),
(179, 1, 'SkbV1uYDauTOjGFS2PKvlrMbXLmLYmygkdljSCyI', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mzg1ODczLCJleHAiOjE2MTU0NzIyNzN9.6WX0X2gjXxIHQSEb01d87A9OsvzSS7pZPfuPWIHixK0', '2021-03-10 08:47:53', '2021-03-10 08:47:53'),
(186, 1, 'm3hqcDqDoeLJN6ZMKCubOzIaAEghF6g8uZkq64iz', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Mzg5ODAzLCJleHAiOjE2MTU0NzYyMDN9.929Q6pBopDA7pNM6i4POK8COFd3lLRiwkEIXDi8RnOQ', '2021-03-10 09:53:23', '2021-03-10 09:53:23'),
(187, 1, 'tCXlMGwsvx94QlHkuc9kGLNGe5kewAM1b7TYyXHz', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzkwMjU1LCJleHAiOjE2MTU0NzY2NTV9.xf-F80oP9-WribMb1wl6a6XodJ8WbKCveEsp-P1esjA', '2021-03-10 10:00:55', '2021-03-10 10:00:55'),
(188, 1, 'usBrkEpLINjd2JAE0xVKI2MmTx1ygatOrnxcpr8p', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzkwMzc1LCJleHAiOjE2MTU0NzY3NzV9.4tq3inA8rfzGW3byvYwVGZTF1YE2-rPB-gQjsJYm3a0', '2021-03-10 10:02:55', '2021-03-10 10:02:55'),
(189, 1, 'cHlhi55u60D1BmQvNm4cifCYiEjLk5ZpfMf4czts', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1MzkzNzU2LCJleHAiOjE2MTU0ODAxNTZ9.af2maYJhG3EPxsulBOFFEm-quYUsbNiA80RykjfG37M', '2021-03-10 10:59:16', '2021-03-10 10:59:16'),
(190, 1, 'kRga20MhrMT32TeI6C042CyaHLefMbnW0Qa3mdVY', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDM1OTI1LCJleHAiOjE2MTU1MjIzMjV9.83qjYpwmofmprFE7vuMuUJfD7P_aXvp17iisTQAI6tI', '2021-03-10 22:42:05', '2021-03-10 22:42:05'),
(192, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ1ODIwLCJleHAiOjE2MTU1MzIyMjB9.ZyZH6RdlTsS5xOeVCnP7w9kk5Id_q0BhRGmCijwMai0', '2021-03-11 01:27:00', '2021-03-11 01:27:00'),
(193, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ2MTY0LCJleHAiOjE2MTU1MzI1NjR9.BsxgS0TkeoigHjJjyDiRGFVr0hlB35EBat19za8jklE', '2021-03-11 01:32:45', '2021-03-11 01:32:45'),
(194, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ2NjMwLCJleHAiOjE2MTU1MzMwMzB9.N2TJwkB45igdAwEY5h2apQCmChF967tG3rfFk9odFn0', '2021-03-11 01:40:30', '2021-03-11 01:40:30'),
(195, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ2NzU5LCJleHAiOjE2MTU1MzMxNTl9.AEDxeAgQJa-yE5_sseY7mArx6sllJ1flOJiYlFrZAH0', '2021-03-11 01:42:39', '2021-03-11 01:42:39'),
(196, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ4MTQzLCJleHAiOjE2MTU1MzQ1NDN9.qAfhb4DC8KzMYtm1ghx28e-7Vm9KOxdCH4CAZmNOMRo', '2021-03-11 02:05:43', '2021-03-11 02:05:43'),
(197, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ4MjE2LCJleHAiOjE2MTU1MzQ2MTZ9.TqEy0a7BYN_UgP7kCxHi2xe5As_vsASI8jKYACFF4PI', '2021-03-11 02:06:56', '2021-03-11 02:06:56'),
(198, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ4NDE5LCJleHAiOjE2MTU1MzQ4MTl9.H2hjQ0uuhNfFvCCjS3RHnnnNzzx1JEDpol2W4sa2_TU', '2021-03-11 02:10:19', '2021-03-11 02:10:19'),
(199, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ4NTI2LCJleHAiOjE2MTU1MzQ5MjZ9.obDqh5dH6kuMscKhLJyKP4qWXLYr2UhLxMq34Jy2_tw', '2021-03-11 02:12:06', '2021-03-11 02:12:06'),
(200, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ4NjQ2LCJleHAiOjE2MTU1MzUwNDZ9.SbQk1oSHc9WBq3ES68oe5TJ661Lb6JtGGLkyhHMoJDA', '2021-03-11 02:14:06', '2021-03-11 02:14:06'),
(201, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ4NzkwLCJleHAiOjE2MTU1MzUxOTB9.9YjPK2678aSvVDd1iVjnuE_sOPT3sRBxCTmbU5WmJcQ', '2021-03-11 02:16:30', '2021-03-11 02:16:30'),
(202, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ5MzAxLCJleHAiOjE2MTU1MzU3MDF9.pheuA03vJfetX482ZFU0HN54W4Dz25vFztNz3UW3iwg', '2021-03-11 02:25:01', '2021-03-11 02:25:01'),
(203, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ5MzQ4LCJleHAiOjE2MTU1MzU3NDh9.V0F6pfY_TkgaE_hhypw1GkPmfvQP8OuM1-IN8PHddh8', '2021-03-11 02:25:48', '2021-03-11 02:25:48'),
(204, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ5NTUyLCJleHAiOjE2MTU1MzU5NTJ9.iidtHXfKaCcNs-0HGRqH5LSd8a60DP_Ty1CcQwEpqns', '2021-03-11 02:29:12', '2021-03-11 02:29:12'),
(205, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDQ5NTY3LCJleHAiOjE2MTU1MzU5Njd9.xz3wanuIR4jkSmflK9JBUR8cRCp9fJbefDQU8QQGZ2U', '2021-03-11 02:29:27', '2021-03-11 02:29:27'),
(206, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDUwMTA5LCJleHAiOjE2MTU1MzY1MDl9.ARgxA0evg5DtkG4XWy6tvM2KFXTmdlS4S5m7kXyhjWw', '2021-03-11 02:38:29', '2021-03-11 02:38:29'),
(213, 1, 'A1eBbqz9hX21S4AwAaUeH9e1cvYWHexqEc8VWfIr', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDUwOTYxLCJleHAiOjE2MTU1MzczNjF9.k4HFHp4-1PDPjpdsLwhCc3NK761MbU6H4W2I_Z_Ucxo', '2021-03-11 02:52:41', '2021-03-11 02:52:41'),
(216, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDU1MjAxLCJleHAiOjE2MTU1NDE2MDF9.dSkecrFK8NC4C9M2inCY8eDt9spUPYRjR-oMkfS3VxI', '2021-03-11 04:03:21', '2021-03-11 04:03:21'),
(217, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDU1MzExLCJleHAiOjE2MTU1NDE3MTF9.6tibik_YFPIZ9jWLJClA-kkmaWP8FBFMYfqYhwEXwC4', '2021-03-11 04:05:11', '2021-03-11 04:05:11'),
(218, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDU1MzQ1LCJleHAiOjE2MTU1NDE3NDV9.cDBkoHjdFQ1nidbNIh7j9Oc8HPyt-GSARadRTbFbkyA', '2021-03-11 04:05:45', '2021-03-11 04:05:45'),
(221, 1, 'K9gLaVW8pHrG23qr8TlGWX79JvhqXRP33ro8ZJUt', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDU5MTk3LCJleHAiOjE2MTU1NDU1OTd9.xRWVxkRkH14JoieK7uHC64u5z6FBtOVOQrqCUOa82ss', '2021-03-11 05:09:57', '2021-03-11 05:09:57'),
(222, 1, 'JL3zeP6NnGlAU5szeiRWP4sQ5hrDPxzeWwXDAoI6', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDYwMzk2LCJleHAiOjE2MTU1NDY3OTZ9.F3afCjUrPDJ5O2g3C2Qe4LFLFcb7Dqhci6v5_wvYasI', '2021-03-11 05:29:56', '2021-03-11 05:29:56'),
(225, 1, 'OxsKe5mPwcLjD1L0C8NBFx0dIzVfbaCKTi6DVpuv', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDYxNTE5LCJleHAiOjE2MTU1NDc5MTl9.If1PUZN_rWc2fl-k_XsBVuRiKqpnsZAkBj2bksOeBmQ', '2021-03-11 05:48:39', '2021-03-11 05:48:39'),
(227, 1, '8Cq1nJUvi3mIldDVglgfldByZ6dT0oS4rjuXdLRf', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDYzNjAwLCJleHAiOjE2MTU1NTAwMDB9.RsNau7-ywTxUQR-LbaygTSi32h0SmaBbs4x4jupKeUs', '2021-03-11 06:23:20', '2021-03-11 06:23:20'),
(228, 1, '7pGsTgcoX5qeGUJVqBUiQQXZnwkeMV4A6LUjvAbc', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDY0NzcyLCJleHAiOjE2MTU1NTExNzJ9.cVGHuDUbmSnzVlf5WBttMxh2DH1hYKXFi0ZjqtL2U78', '2021-03-11 06:42:52', '2021-03-11 06:42:52'),
(229, 1, 'iMR2F4JlGVvnY3W1L43AjJqx1NnOTo0ZDR3snjBy', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDY5NDcyLCJleHAiOjE2MTU1NTU4NzJ9.mJ0ljpWB-JHYZ66GrOkivFk4pYaL_vFEIFVK0jQQJ_s', '2021-03-11 08:01:12', '2021-03-11 08:01:12'),
(230, 1, 'LUwkUQaVsfBC1CxR2U0fPHWu2vxnF0fp1t6Uzy0b', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDY5OTk1LCJleHAiOjE2MTU1NTYzOTV9.WDj-nqp1Ysr2hVp3ztqOVkngKakDMpZ7Vh-M0mauLgI', '2021-03-11 08:09:55', '2021-03-11 08:09:55'),
(231, 1, 'LUwkUQaVsfBC1CxR2U0fPHWu2vxnF0fp1t6Uzy0b', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDcwMTg2LCJleHAiOjE2MTU1NTY1ODZ9.v7NGAcwKrzXZLS--VrJ7KQVZHdgFij5UC9qm7kjrDKo', '2021-03-11 08:13:06', '2021-03-11 08:13:06'),
(232, 1, 'aPkOQxnwWYX6TJ8vii2zwXs7jvI3xWFuvHJT3O8v', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDczMTI0LCJleHAiOjE2MTU1NTk1MjR9.P6vYcnwiLLCrcQA6Rn_r0qb68WVKpP4_LJiKIEjMGBA', '2021-03-11 09:02:04', '2021-03-11 09:02:04'),
(234, 1, 'y59B66nqosE57CZpaq2T30jUmLR2CP0Twf0tLB6e', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDc1Njg0LCJleHAiOjE2MTU1NjIwODR9.biZ7VwCVk5kwZrmbAMX6krgznIX1cN5LAYpM_IirDEY', '2021-03-11 09:44:44', '2021-03-11 09:44:44'),
(235, 1, 'vuwPWJsAXApmvMwmnUwF6NgPpc2wcFTObPRbMzfV', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDc4NzY5LCJleHAiOjE2MTU1NjUxNjl9.FbOwBCtF0xm11n7cJEt1FXGUq04K8Wt_oAzBkDnYGCs', '2021-03-11 10:36:09', '2021-03-11 10:36:09'),
(236, 1, 'j9EKKkPBp0aHgLEMtR61PW98KB5Pw2WYpVZeK1f4', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDgxNjk4LCJleHAiOjE2MTU1NjgwOTh9.eEZF6xqiefcsl4Cou3W4xaPS8cSBIpuglhlwreBLE_E', '2021-03-11 11:24:58', '2021-03-11 11:24:58'),
(237, 1, '6pPrPZqFfjWzl0wFwzPCB0ZkSogENbdlFKHQNPTk', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NDkxOTA5LCJleHAiOjE2MTU1NzgzMDl9.BclQHlq71EOJFIDuAzWl-1espyIXGBZAz_AiQ09xvtw', '2021-03-11 14:15:09', '2021-03-11 14:15:09'),
(238, 1, 'UUXwlyDpM6j9pDwu2cqasbfEHsnOWHOz81BvHgWy', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NTI1MjY1LCJleHAiOjE2MTU2MTE2NjV9.146_J4OLXOQMGY6O7C0Lu7bPjrFTo5_xKRrNNtqQ06w', '2021-03-11 23:31:05', '2021-03-11 23:31:05'),
(239, 1, 'nGAAQ9WhVKQAG4bVIG0TkuKzNleWwOCGJNNsfmfW', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NTMwNTI5LCJleHAiOjE2MTU2MTY5Mjl9.jDYw-U5ssVp0t6WlKv98hNf7t60cGYpK6_l98o1JBzU', '2021-03-12 00:58:49', '2021-03-12 00:58:49'),
(240, 1, 'TizTFzaG25IGbgKLGeP08OZoqzWAzSOXNuDp2C5v', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NTQ1ODUwLCJleHAiOjE2MTU2MzIyNTB9.2CVT2kUheGFINXEwQg2XXVJpuJjvGy5_L8mS8yes-Eg', '2021-03-12 05:14:11', '2021-03-12 05:14:11'),
(241, 1, 'sGN1ABavoRupJupvK6Sqaq9Q2VZIKRmdhmo8YOSh', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NTQ4MTQ1LCJleHAiOjE2MTU2MzQ1NDV9.G4HNjamezMyerOFqYhuHkUijKqwaxZGj-vhoQPs5UHA', '2021-03-12 05:52:25', '2021-03-12 05:52:25'),
(242, 1, 'sU5tm7YZYaN5cpB69ZQbSPuYQadpxEDYAgOHrlZ9', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NTQ4NzIyLCJleHAiOjE2MTU2MzUxMjJ9.9WmkzneerZEWvKDbbURf-ZTE88-wD--0V73DEqvJdms', '2021-03-12 06:02:02', '2021-03-12 06:02:02'),
(243, 1, 'ka4hhlcHE4kM6TpYsVfZ8XIWf7ZPVFiinK7DgNhw', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NTQ5NzAyLCJleHAiOjE2MTU2MzYxMDJ9.wjQz0NnNIbI00k7ooDTaQ3ftBTDor2yftvgUUxfrDG4', '2021-03-12 06:18:22', '2021-03-12 06:18:22'),
(244, 1, 'XlXvRUoS4PqIQeS1Zmc82WFa9KUIQRJaAHR6kgDI', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NTU5MTAyLCJleHAiOjE2MTU2NDU1MDJ9.JrJFL39sy_gUI4rvQ6_mV_4fY7VvTFDfU5ImuhgrTLc', '2021-03-12 08:55:02', '2021-03-12 08:55:02'),
(245, 1, '1FFFnCr3gCjqqruMC5JxQpXjorsVFghdtuy0NE40', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NjA4NDc5LCJleHAiOjE2MTU2OTQ4Nzl9.-hXwy6ExmWPxHSXJ8SDRJ0W9Mnt-YV2g-M_iZl9nTuQ', '2021-03-12 22:37:59', '2021-03-12 22:37:59'),
(247, 1, 'Q381ov7tfaRYazMa9Fa5NKxHvPUIJs8ADiwdAbc2', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NjEwMDI4LCJleHAiOjE2MTU2OTY0Mjh9.HnM538BKSpWfTF3QWSDwP_gpEDyNPUaS37RQujITjJ0', '2021-03-12 23:03:48', '2021-03-12 23:03:48'),
(248, 1, 'Q1vhMLFD5Y05BF7qtMYqmIcMOtWiBSvoboO9vTYr', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NjEyMzMxLCJleHAiOjE2MTU2OTg3MzF9.ZPzliwHMELZGGKUmGu5ygJyjpOLPyYy7IIahEYVUlds', '2021-03-12 23:42:11', '2021-03-12 23:42:11'),
(249, 1, '1Hk34V42rNXlz0mSHKS2FkKhvyYPmtFOeAW5ecjE', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NjEzMzgzLCJleHAiOjE2MTU2OTk3ODN9.N1m4kRkCAsNfx6vE7UFXBMsQe2j3GKAYuQtF-kB_eN4', '2021-03-12 23:59:44', '2021-03-12 23:59:44'),
(250, 1, 'mW6msOmZUaTwfeNfIgILHcrrPD6PqSY4uaTbyjvL', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NjIwMzc3LCJleHAiOjE2MTU3MDY3Nzd9.86j0VseJ0SD_25heCwiS_lAhh0JSiqeRVGGjMPnRF7I', '2021-03-13 01:56:17', '2021-03-13 01:56:17'),
(251, 1, 'KOnaaIkibo4OhYgpgdFU5q4oBYNbihyUgLMdHbYd', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NjMyNDUwLCJleHAiOjE2MTU3MTg4NTB9.M5p3SBE_PiGrMnPAFIwRjOZxmzTSuTIq8gaF7mFsFXQ', '2021-03-13 05:17:30', '2021-03-13 05:17:30'),
(253, 1, 'um6IBQzURoZE2CcTO0LWc6XXPbMIQEQAzhFOLEZ6', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzA1MjA3LCJleHAiOjE2MTU3OTE2MDd9.HTWQbgj5_mUCUzd2whKndDd5YT_AzTtS_d-A59ZNqDY', '2021-03-14 01:30:07', '2021-03-14 01:30:07'),
(254, 1, 'uovMLHfhIF42L131wgaFxlu7EmBpnLzY0PUdUETi', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzA4NDM0LCJleHAiOjE2MTU3OTQ4MzR9.IkWfplXsBb4z3oCFu49c9hk_ZJLXUGOS9E_SUmduNxw', '2021-03-14 02:23:54', '2021-03-14 02:23:54'),
(256, 1, 'csBXUBnkZBXBJcDycUDkE1QR8qmv4nD6F5Medk1z', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzEyOTY4LCJleHAiOjE2MTU3OTkzNjh9.LXIif4enWRe5ncAM6E9bvYRZDlrqg_0GFQ83Gg96u1w', '2021-03-14 03:39:28', '2021-03-14 03:39:28'),
(257, 1, 'Dak9b9rItYFmLOn9JBWD9zyxmj5KmM0MfQ90IryO', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzEzNjIwLCJleHAiOjE2MTU4MDAwMjB9.IFp8H1X-CpV-KUfdB0LEr0PX2fJe6uX26s2UI35xl6c', '2021-03-14 03:50:20', '2021-03-14 03:50:20'),
(259, 1, 'RgVYuZRjf74j3OQX4MGVejDZsXPT6PqUxFuEPMp7', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzIyOTY2LCJleHAiOjE2MTU4MDkzNjZ9.odwoDCUKjgIZiKmETpotwtylxiigL3vTvgsSEbPoeWw', '2021-03-14 06:26:06', '2021-03-14 06:26:06');
INSERT INTO `user_access_tokens` (`id`, `user_id`, `session_id`, `access_token`, `created_at`, `updated_at`) VALUES
(260, 1, 'bBqLQtlaCjFh0ap2t6B3dXYQSpyO9DkPkhVHkVZi', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzIzNTIxLCJleHAiOjE2MTU4MDk5MjF9.N-D5hg2jKl22P412yNqMb8Ex7__zUXSbKCrw5b0lrfE', '2021-03-14 06:35:21', '2021-03-14 06:35:21'),
(262, 1, '6ooI3rTdt28Zx4y8wXTibYIojeO2yeRu3EyH4wvK', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzI0NDUxLCJleHAiOjE2MTU4MTA4NTF9.TslzmRzT1YMQQNy2a2F_n-pSPWJG8Nu6cXdnPWgPEJs', '2021-03-14 06:50:51', '2021-03-14 06:50:51'),
(263, 1, 'a1G3b6Jshu2Vut8pqwfcHLbPAl7qfDT5XW9vmRX8', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzM2MDA3LCJleHAiOjE2MTU4MjI0MDd9.kwiaxLgAXvChVQSQrZ-PcAOZ8p1PzmL3KWPV6L8Z9DA', '2021-03-14 10:03:27', '2021-03-14 10:03:27'),
(264, 1, 'h9OEs8klSVGDjBtWGPv57JBTHWkW2NVVQqR1G3h2', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzg3MjYwLCJleHAiOjE2MTU4NzM2NjB9.7jFKkdrW7grafzLZRP3X4mS-2WEWcbsR0AJjw7Ombv4', '2021-03-15 00:17:40', '2021-03-15 00:17:40'),
(266, 1, 'PnbBTwZswbPIRNBaYRdezwqimk5Lu23mPkBOO5wW', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzg5MTU5LCJleHAiOjE2MTU4NzU1NTl9.PMrz8kRQ47I13z7gWVIqIFw8Gw3CYxNALYCPu06OlGs', '2021-03-15 00:49:19', '2021-03-15 00:49:19'),
(268, 1, 'tHDoVWMOyTqOVBjjni5QEHNsbX8RfUsXGRIfKFnK', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzkxMzg4LCJleHAiOjE2MTU4Nzc3ODh9.LIRJjG2z5VuPdZ387ci0Wpv0eSjqfurIeV-Hb4tibZ8', '2021-03-15 01:26:28', '2021-03-15 01:26:28'),
(269, 1, '7xuskUVL88AMxxuKuWpoxZJxq5oYIpJZjqDxJhpi', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1NzkyMzcyLCJleHAiOjE2MTU4Nzg3NzJ9.sN_IeCkPM4xuIOVHo-3_e8_P2g-EsTiA8m6HDhejxbg', '2021-03-15 01:42:52', '2021-03-15 01:42:52'),
(270, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk0NjY0LCJleHAiOjE2MTU4ODEwNjR9.aNW2KiE1SuLoccVeYcrHcypjHtF4u_RjLUW71z4KoMQ', '2021-03-15 02:21:04', '2021-03-15 02:21:04'),
(271, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk0ODM3LCJleHAiOjE2MTU4ODEyMzd9.dg5ALvwGAGGlZpN0kxuF3WeFWzbtkoscBfwmYDrkRdw', '2021-03-15 02:23:57', '2021-03-15 02:23:57'),
(272, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk0ODk1LCJleHAiOjE2MTU4ODEyOTV9.niyfGRhQ1Y6QvwusoVPUkP5EFPCf2CymFzeV3-CL214', '2021-03-15 02:24:55', '2021-03-15 02:24:55'),
(273, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk0OTAyLCJleHAiOjE2MTU4ODEzMDJ9.FyWCCpEJ6wJCwyku7-Wg7kC7cNoezCbFEqNo2eufnu4', '2021-03-15 02:25:03', '2021-03-15 02:25:03'),
(274, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1MDc1LCJleHAiOjE2MTU4ODE0NzV9.bLRz96NMCCDKkRXtafN7z_GcNCErq_zq70GzWMaUgcI', '2021-03-15 02:27:55', '2021-03-15 02:27:55'),
(275, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1MjQ0LCJleHAiOjE2MTU4ODE2NDR9.y4aUZW11FvATXhB4Vwi4gjAhuskaxJetVmf-Jn7aIRI', '2021-03-15 02:30:44', '2021-03-15 02:30:44'),
(276, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1NDkyLCJleHAiOjE2MTU4ODE4OTJ9.FI9_iX3347BfiLFVGDRSZJWJVNkoFR2nlUxjZt9cAOM', '2021-03-15 02:34:52', '2021-03-15 02:34:52'),
(277, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1NTQ1LCJleHAiOjE2MTU4ODE5NDV9.h65EGNgLrrki7ew711wQ4eGIR0QtnbqR9gnCNZQDROc', '2021-03-15 02:35:45', '2021-03-15 02:35:45'),
(278, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1NTg2LCJleHAiOjE2MTU4ODE5ODZ9.QdRpCLPqUV6bBpvCXU5T2SgDkitYyn6EdCn9MB-i73A', '2021-03-15 02:36:26', '2021-03-15 02:36:26'),
(279, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1NjYxLCJleHAiOjE2MTU4ODIwNjF9.T9790F-JWjHCxiAaBjwn0vE0yaXBFf3uRxxj6N2YJ3Y', '2021-03-15 02:37:41', '2021-03-15 02:37:41'),
(280, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1NzI0LCJleHAiOjE2MTU4ODIxMjR9.VeMU5oL_Tp1TW6DrrlEdcu1ZzVSsWwjbCVpgh8K3f3U', '2021-03-15 02:38:44', '2021-03-15 02:38:44'),
(281, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1ODc3LCJleHAiOjE2MTU4ODIyNzd9.bWbX5K8iBPR9pnbD05Crg9puTPH8IOOYhQPk9S-y-Ug', '2021-03-15 02:41:17', '2021-03-15 02:41:17'),
(282, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1OTM1LCJleHAiOjE2MTU4ODIzMzV9.am9xjTj5yHwVtsXQbTz5pe-UH1RNWMjNatsC21AdzMA', '2021-03-15 02:42:15', '2021-03-15 02:42:15'),
(283, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk1OTgwLCJleHAiOjE2MTU4ODIzODB9.-etz9kBUviY30ge8M8d9T2LcdcG7mh6RvfQTMwFJHV4', '2021-03-15 02:43:00', '2021-03-15 02:43:00'),
(284, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk2MDM0LCJleHAiOjE2MTU4ODI0MzR9.8pTKvZMGbmhep9UZWvubxNQvf37y_6burCRLuXXXkZc', '2021-03-15 02:43:54', '2021-03-15 02:43:54'),
(285, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk2MDY3LCJleHAiOjE2MTU4ODI0Njd9.9t92Nh72ueswv1aKWU1hc7rO6AqrNZzj7N_oGV-ueiI', '2021-03-15 02:44:27', '2021-03-15 02:44:27'),
(286, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk2MTAyLCJleHAiOjE2MTU4ODI1MDJ9.bCJYbZGM6-Ls_dmGvUYXcZxa8IdhOKakq0s3pptoxZY', '2021-03-15 02:45:02', '2021-03-15 02:45:02'),
(287, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk2MjAzLCJleHAiOjE2MTU4ODI2MDN9.sKXkvB9W8ZqUMNvmZ009LG1DGG9qyOHrBDMkYtK2o8A', '2021-03-15 02:46:43', '2021-03-15 02:46:43'),
(288, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk2NjI4LCJleHAiOjE2MTU4ODMwMjh9.qVesXQbSLB8pMxf0lSuxoa8HV6hEn6xeWSpy2vevuqw', '2021-03-15 02:53:48', '2021-03-15 02:53:48'),
(289, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk2NjcwLCJleHAiOjE2MTU4ODMwNzB9.tsyYKsGdXKMvuII-qsM7pOxPyiKNCoVLnyjUikotU-s', '2021-03-15 02:54:30', '2021-03-15 02:54:30'),
(290, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk2OTg0LCJleHAiOjE2MTU4ODMzODR9.slj-_E1dhdffWsevPfl8bIljTeMHmfv49gwJj15gu28', '2021-03-15 02:59:44', '2021-03-15 02:59:44'),
(291, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3MTIzLCJleHAiOjE2MTU4ODM1MjN9.pnx4Kiwxk7wiVgzCa6LfxJDwltaRqfJfuptae0NmTAM', '2021-03-15 03:02:03', '2021-03-15 03:02:03'),
(292, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3MTY4LCJleHAiOjE2MTU4ODM1Njh9._2ebi2nZ6kqMwXTJwLXSJit3IQlFrmA_fw2tU0gp2tw', '2021-03-15 03:02:48', '2021-03-15 03:02:48'),
(293, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3MjA3LCJleHAiOjE2MTU4ODM2MDd9.dRzUXzHcIJzkbP-tcrgEuqPyaUN623mFZk8FxIyRb-U', '2021-03-15 03:03:27', '2021-03-15 03:03:27'),
(294, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3MjY1LCJleHAiOjE2MTU4ODM2NjV9.lCV5C1XX0FI_7MuXF8KjljTWOPW9wc8cKZMRrA_mEj0', '2021-03-15 03:04:25', '2021-03-15 03:04:25'),
(295, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3MzIzLCJleHAiOjE2MTU4ODM3MjN9.xsBBu7Q1sqh7wCbahmWerGV-dt-R-ihKUT-SD4PoycM', '2021-03-15 03:05:23', '2021-03-15 03:05:23'),
(296, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3Mzk5LCJleHAiOjE2MTU4ODM3OTl9.i0ZJPegI0ZDUVDN9SfSSw2H7-ASs4Cb9x-oZDuUwaHU', '2021-03-15 03:06:39', '2021-03-15 03:06:39'),
(297, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3NDc5LCJleHAiOjE2MTU4ODM4Nzl9.1q4nN4ga60QiyCMaypV1Kt2n0E68LmcokQbq4FQPqEA', '2021-03-15 03:07:59', '2021-03-15 03:07:59'),
(298, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3NTI0LCJleHAiOjE2MTU4ODM5MjR9.nJImOeLhHQ6m4yeOREDEOEmgWAs60UdNSA8FokMdgZc', '2021-03-15 03:08:44', '2021-03-15 03:08:44'),
(299, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3NTY4LCJleHAiOjE2MTU4ODM5Njh9.lOIL4ECqnmXzbnWmJLbiKDu0xhiBLv7itKMMJl5IdEs', '2021-03-15 03:09:28', '2021-03-15 03:09:28'),
(300, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3NjA5LCJleHAiOjE2MTU4ODQwMDl9.GNw49q7oWCbifMCWHngVyFkmaaJ8tYcSWKlEpw8KFQA', '2021-03-15 03:10:09', '2021-03-15 03:10:09'),
(301, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3Njg1LCJleHAiOjE2MTU4ODQwODV9.M0vGA6prGuUHzx6sHZJXu6AnR-gvmnW3OwrgQ2fdWbs', '2021-03-15 03:11:25', '2021-03-15 03:11:25'),
(302, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3NzE2LCJleHAiOjE2MTU4ODQxMTZ9.AvH0G0s59HtqIRHAqNe68HtKGRSo9qSfOUm77AoWfcg', '2021-03-15 03:11:56', '2021-03-15 03:11:56'),
(303, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk3ODI2LCJleHAiOjE2MTU4ODQyMjZ9.juZwAMyRiAtOZuLGZuVnCOWiujuc4qxHnUML65sP69U', '2021-03-15 03:13:46', '2021-03-15 03:13:46'),
(304, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk4MDMyLCJleHAiOjE2MTU4ODQ0MzJ9.STmwlEAR9lgSA7BfGVG5WFI0TwEkJ03WR95T6gSosJI', '2021-03-15 03:17:12', '2021-03-15 03:17:12'),
(305, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk4MDkwLCJleHAiOjE2MTU4ODQ0OTB9.VhiHo-zxYRykn76CySVxvWY2GuYNJhIYHRLfsCipGvg', '2021-03-15 03:18:10', '2021-03-15 03:18:10'),
(306, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk4MTYxLCJleHAiOjE2MTU4ODQ1NjF9.FLsNwbNB5vAW2tM5WzPegFfLHVP-an9ryXURD9aXfQ0', '2021-03-15 03:19:21', '2021-03-15 03:19:21'),
(308, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk4MjcxLCJleHAiOjE2MTU4ODQ2NzF9.9_kbKmrgaR_xUtgqFfDdVa53iThfBj4HxJOYRnTEB5I', '2021-03-15 03:21:11', '2021-03-15 03:21:11'),
(309, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk4NjA2LCJleHAiOjE2MTU4ODUwMDZ9.BOQQEFUCzLyWkM2SVXty43CJ4Dgiz_1IjjNTsbk7OPM', '2021-03-15 03:26:46', '2021-03-15 03:26:46'),
(310, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk4Njg2LCJleHAiOjE2MTU4ODUwODZ9.kIxI7MfH3GPBI7HodpCsh5fPyn0q8qDUGU-qPIqjE6w', '2021-03-15 03:28:06', '2021-03-15 03:28:06'),
(311, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk4OTEzLCJleHAiOjE2MTU4ODUzMTN9.wSdWhX3yrT4kCagm9hPNFVg8cbCR1K1EbzGSWWW1o2c', '2021-03-15 03:31:53', '2021-03-15 03:31:53'),
(312, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk5MDIyLCJleHAiOjE2MTU4ODU0MjJ9.2ZG9jgBCYmnZdeRM_XoYJfQX0GXcOohOssu73mwZoyU', '2021-03-15 03:33:42', '2021-03-15 03:33:42'),
(313, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk5MTQzLCJleHAiOjE2MTU4ODU1NDN9.8tU8fyAcQf09gmkUp1YXaXgs9ywTPoO3inoD7liGyGM', '2021-03-15 03:35:43', '2021-03-15 03:35:43'),
(314, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk5NDI3LCJleHAiOjE2MTU4ODU4Mjd9.kvrK6cQxjrTF-8n105lHr10mlq9JFaUD-WKz5dvJCIc', '2021-03-15 03:40:27', '2021-03-15 03:40:27'),
(315, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1Nzk5ODM5LCJleHAiOjE2MTU4ODYyMzl9.4Ut3kwtbYTIXQyCF3qmrlSs__Mg572e_LiY4LRfq1P0', '2021-03-15 03:47:19', '2021-03-15 03:47:19'),
(316, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwMTA5LCJleHAiOjE2MTU4ODY1MDl9.V1UpL2BmnrwrbURkFLc8NU4EEwNMMuQykJcP4p3MReE', '2021-03-15 03:51:49', '2021-03-15 03:51:49'),
(317, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwMTczLCJleHAiOjE2MTU4ODY1NzN9.fNUr2lOuJIyQj7xsofKosp5d6DYSBZ_CJEv1yKzISC8', '2021-03-15 03:52:53', '2021-03-15 03:52:53'),
(318, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwNDIyLCJleHAiOjE2MTU4ODY4MjJ9.lyxiOfRZpXW9sUxf10wORL042BhW1aHN9aoT1oA7aq0', '2021-03-15 03:57:03', '2021-03-15 03:57:03'),
(319, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwNDc0LCJleHAiOjE2MTU4ODY4NzR9._XnOR6F-VjIQ2NgAzA8d-5KIpIELtKMWNcVsKXtw8K0', '2021-03-15 03:57:54', '2021-03-15 03:57:54'),
(320, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwNTQ5LCJleHAiOjE2MTU4ODY5NDl9.hncIx4VQLDFhLcDLeyph9brvXdeQnvJSWujjWgFHS3g', '2021-03-15 03:59:09', '2021-03-15 03:59:09'),
(321, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwNjMzLCJleHAiOjE2MTU4ODcwMzN9.GWTKpngcAK2fCPzSqWS4i1yCMN14dpAmAyhlIshs534', '2021-03-15 04:00:34', '2021-03-15 04:00:34'),
(322, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwNjU2LCJleHAiOjE2MTU4ODcwNTZ9.0FdFJTSkz1g3RGw6T2Vt_8s9tzYFPXcTebDusHj52Mc', '2021-03-15 04:00:56', '2021-03-15 04:00:56'),
(323, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwODI3LCJleHAiOjE2MTU4ODcyMjd9.YsvvnLZ_63orixefC_xAN_YmWSVoJugPfmm7Ay5nsmc', '2021-03-15 04:03:47', '2021-03-15 04:03:47'),
(324, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwODgyLCJleHAiOjE2MTU4ODcyODJ9.-T9aNAjaC6rpY5puO-TMstc8h_DgDANFgtQS0mzPe18', '2021-03-15 04:04:42', '2021-03-15 04:04:42'),
(325, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAwOTg2LCJleHAiOjE2MTU4ODczODZ9.hh0W9ZFnsu_gSHE4b4JWa-8l2Nq0fVIBzIEgiYqAvqw', '2021-03-15 04:06:26', '2021-03-15 04:06:26'),
(326, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxMDE2LCJleHAiOjE2MTU4ODc0MTZ9.gsdyIwHKouaXvPaYl3PMkc1AmcWWJGe97fqp5BAFf0U', '2021-03-15 04:06:56', '2021-03-15 04:06:56'),
(327, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxMDcxLCJleHAiOjE2MTU4ODc0NzF9.3Qbi6F8VUBlne4qUdEZSE4_IjoSwFwt086I2P6klpc4', '2021-03-15 04:07:51', '2021-03-15 04:07:51'),
(328, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxMDg1LCJleHAiOjE2MTU4ODc0ODV9.KDaSkgA8tBKlKuvqYdExwybfbd6d39rGBKrQNNcsz2U', '2021-03-15 04:08:05', '2021-03-15 04:08:05'),
(329, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxMzkzLCJleHAiOjE2MTU4ODc3OTN9.TVOBdFggGbr6WPrNuF63l4hswyADwXwRFa2u6GVqS9k', '2021-03-15 04:13:13', '2021-03-15 04:13:13'),
(330, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxNDUyLCJleHAiOjE2MTU4ODc4NTJ9.sSIIs8nMZPM_vzEwlvjfjp3feCyULq44fPSr1Vdk0Jg', '2021-03-15 04:14:12', '2021-03-15 04:14:12'),
(331, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxNTE1LCJleHAiOjE2MTU4ODc5MTV9.luo7dq-BrxalpO3w60i_xHseZWvFtC2WlHook9UCCPE', '2021-03-15 04:15:15', '2021-03-15 04:15:15'),
(332, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxNTY0LCJleHAiOjE2MTU4ODc5NjR9.hrUwGtmmhQJGlkku75ku1JZdOPJDh9iIpUQwt-gELEk', '2021-03-15 04:16:04', '2021-03-15 04:16:04'),
(333, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxNjQyLCJleHAiOjE2MTU4ODgwNDJ9.SfFqFSVKllWC7vPg1EZIAusY6DCqVpPu2SA32Lq0XFw', '2021-03-15 04:17:22', '2021-03-15 04:17:22'),
(334, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxNjY1LCJleHAiOjE2MTU4ODgwNjV9.q16bd_XIz7rsTiez7i-jIhqHGEv13CbFW2hDv8UScKI', '2021-03-15 04:17:45', '2021-03-15 04:17:45'),
(335, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxNzUzLCJleHAiOjE2MTU4ODgxNTN9.7E9RcdsCMUKO1_lKX5bIpkn8HQ0fnUeBLQzqyKe4mpo', '2021-03-15 04:19:13', '2021-03-15 04:19:13'),
(336, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxODg0LCJleHAiOjE2MTU4ODgyODR9.1GbOsGf7Q3ST_XNrFUGNEx38Fwo3iiYE6Yxh2Bu5wvc', '2021-03-15 04:21:24', '2021-03-15 04:21:24'),
(337, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxOTQyLCJleHAiOjE2MTU4ODgzNDJ9._S2jmZiBQquXz5plLoLCC-rIlrIrvJJDfiHph75NSts', '2021-03-15 04:22:22', '2021-03-15 04:22:22'),
(338, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAxOTk3LCJleHAiOjE2MTU4ODgzOTd9.wZQytuCuLTzu-s3thKmoYlQtCKBftGEwsa0tYlcKfok', '2021-03-15 04:23:17', '2021-03-15 04:23:17'),
(339, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyMDY0LCJleHAiOjE2MTU4ODg0NjR9.PEdSnR9FCVzp_eoLWC3ACr0gbHpA84D3cpEkiw-8v7o', '2021-03-15 04:24:24', '2021-03-15 04:24:24'),
(340, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyMTA2LCJleHAiOjE2MTU4ODg1MDZ9.dly9TkUcLmka1zIXpKRWK7duy1rPGbNrYnwY4PK1ogI', '2021-03-15 04:25:06', '2021-03-15 04:25:06'),
(341, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyMjE3LCJleHAiOjE2MTU4ODg2MTd9.Wt_Kxj2wtG-SHRMemTYRbFA2KQBUo0_pF1nwqKB2EWo', '2021-03-15 04:26:57', '2021-03-15 04:26:57'),
(342, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyMzE0LCJleHAiOjE2MTU4ODg3MTR9.xy2Z6gFbTOjXvovvDGzukVoKH4KV6pxkhXovtwZ-s2c', '2021-03-15 04:28:34', '2021-03-15 04:28:34'),
(343, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyMzcxLCJleHAiOjE2MTU4ODg3NzF9.E1Cn0pZ_Wi3pH_-QBicZ4kOS0Epbv6yzAW-2NODEcLg', '2021-03-15 04:29:31', '2021-03-15 04:29:31'),
(344, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyNDE4LCJleHAiOjE2MTU4ODg4MTh9.EYq9o1TnMZ4-klrNt-e6-cXC_6Y3OIMhd363EP4KX4o', '2021-03-15 04:30:18', '2021-03-15 04:30:18'),
(345, 1, 'erkQWi8ZhtVpgwFuAZCGnzbxwlULVfHODNyxybzS', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyNTI3LCJleHAiOjE2MTU4ODg5Mjd9.c7bWwIzWPPlW-XMmYA7ESaPBcS9Z8peE03CUX12gurg', '2021-03-15 04:32:07', '2021-03-15 04:32:07'),
(346, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyNjk2LCJleHAiOjE2MTU4ODkwOTZ9.W2ig2niksP-yQSQGz6EMfJTDo0oCzvWUZM17CrApkvA', '2021-03-15 04:34:56', '2021-03-15 04:34:56'),
(347, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyNzMyLCJleHAiOjE2MTU4ODkxMzJ9.Vdcvpgh5K5h4cPRGyKQjb66lAsJ30KXqOSTvTUFkN7U', '2021-03-15 04:35:32', '2021-03-15 04:35:32'),
(348, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAyODE5LCJleHAiOjE2MTU4ODkyMTl9.mDuXz1a538K-dEFGYpchuA2VxuS7doCHiM8bPjUMcdA', '2021-03-15 04:36:59', '2021-03-15 04:36:59'),
(349, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAzMDUwLCJleHAiOjE2MTU4ODk0NTB9.0Ju5K_Qkp-bAwGLm5SYAuzpLwpqE6AyoG3TThpiuU0s', '2021-03-15 04:40:50', '2021-03-15 04:40:50'),
(350, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAzMDk4LCJleHAiOjE2MTU4ODk0OTh9.QHch_JOMT4jsSSvIJjjyzPyoVG92j9FJbhm8iUccS34', '2021-03-15 04:41:38', '2021-03-15 04:41:38'),
(352, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAzMTQ4LCJleHAiOjE2MTU4ODk1NDh9.DTzNrvHp_PmrBdpUOoxMF3ofPIKHrzeMK6tNQN7OU_Y', '2021-03-15 04:42:28', '2021-03-15 04:42:28'),
(353, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAzMTU4LCJleHAiOjE2MTU4ODk1NTh9.1J7ZgWoHi3pgIEi3UP3LOckVTjBeMrufXCW2vwKB4Fo', '2021-03-15 04:42:38', '2021-03-15 04:42:38'),
(354, 1, 'JE1Ib6vxNtN2h8aqmN5Nr6JXIhpbxafeMQTFlZOE', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAzNTU0LCJleHAiOjE2MTU4ODk5NTR9._IfYllUv2IY1twAHS5kwJxZSrb691X4f5sfuHw2skw4', '2021-03-15 04:49:14', '2021-03-15 04:49:14'),
(356, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODAzODQ0LCJleHAiOjE2MTU4OTAyNDR9.Pi2oq9i7zYOBZocWk07-aOTptcNj-U_Y9oiL2__jLhE', '2021-03-15 04:54:04', '2021-03-15 04:54:04'),
(357, 11, 'qJh4OcwE2gcKtNDxcydnDP4z79inprQOsYVCWtu1', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MTEsInVzZXJfc2xhY2siOiJuMVVmbWlZdWlFM0R6eXZicFhYMVEyMndHIn0sImlhdCI6MTYxNTgwNTUwNSwiZXhwIjoxNjE1ODkxOTA1fQ.05hPRGDIjRBx6yyEod7EChNd6eRWZr5reSCoJEiiVaw', '2021-03-15 05:21:45', '2021-03-15 05:21:45'),
(358, 1, 'edwWa7yWyMg0AO6aHjtswZ75dCiJYosJw0GEuLxc', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA2NDQzLCJleHAiOjE2MTU4OTI4NDN9.b-oc3MBF6CzJxY9Qmh3ZREdlfjgHsAUlpV355pKbJe8', '2021-03-15 05:37:23', '2021-03-15 05:37:23'),
(359, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA3MjQzLCJleHAiOjE2MTU4OTM2NDN9.Dq7z5zbXYetL8N9qAAej6scsSi9RPv2jY-ptRwM6UX8', '2021-03-15 05:50:43', '2021-03-15 05:50:43'),
(360, 1, 'K9qpZfjEhcw6MnZHsmcu9gT0DNE9Spkur4LF7y3L', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA3MjQ3LCJleHAiOjE2MTU4OTM2NDd9.P-UsktB07lq3v1-TGF0FguGwQlsU9oXEoi5V_GoIky8', '2021-03-15 05:50:47', '2021-03-15 05:50:47'),
(361, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA4NTA5LCJleHAiOjE2MTU4OTQ5MDl9.wy-Cj3NKtr753ehkt8SCztq7eqGtLOZ93-Y9VHqjVe8', '2021-03-15 06:11:49', '2021-03-15 06:11:49'),
(362, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA4NTIxLCJleHAiOjE2MTU4OTQ5MjF9.2NSumCO2YAosrPohzYBr3nqf0heN5AJ-t2_N1J6e3I8', '2021-03-15 06:12:02', '2021-03-15 06:12:02'),
(363, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA4NTU4LCJleHAiOjE2MTU4OTQ5NTh9.DNOxMPaNFbYnrZ2l19xc7R0sm6BbFpXK5iXPGZMQXaU', '2021-03-15 06:12:38', '2021-03-15 06:12:38'),
(364, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA4NjE1LCJleHAiOjE2MTU4OTUwMTV9.qSusamXasXQ7MBXwpinqCpbP5aWXUtEAKw4OCq9m2ks', '2021-03-15 06:13:35', '2021-03-15 06:13:35'),
(365, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA4NjI0LCJleHAiOjE2MTU4OTUwMjR9.MmbMqdrxCOg5XtF5-pDLM_Ur9Uc7OKZYnW8mbR1hjTQ', '2021-03-15 06:13:44', '2021-03-15 06:13:44'),
(366, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA4NjcyLCJleHAiOjE2MTU4OTUwNzJ9.dzIfuK6lzJz3t5h4o_fcc6eHDKmRtEximt4OXgbHPjw', '2021-03-15 06:14:32', '2021-03-15 06:14:32'),
(367, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA4NzU3LCJleHAiOjE2MTU4OTUxNTd9.-bxNOXaAs5YOUqhnxS-hILzijlvmBKO1maAOk3BHEJg', '2021-03-15 06:15:57', '2021-03-15 06:15:57'),
(368, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA5NDcyLCJleHAiOjE2MTU4OTU4NzJ9.xVsYuN07w6Ex-ERIH2PIyslfYPp74mp9Qc3VQLuWU3Q', '2021-03-15 06:27:52', '2021-03-15 06:27:52'),
(369, 1, '8UiIbYR4NfsaKXpwTlEIM5NUFy2J8mqLzHbKv8ej', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA5NTM4LCJleHAiOjE2MTU4OTU5Mzh9.qWUcZGUgscrT1nSr4xU1CYbJPAcxvxvFmrG69_HHoqo', '2021-03-15 06:28:58', '2021-03-15 06:28:58'),
(370, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA5NTQ3LCJleHAiOjE2MTU4OTU5NDd9.ruA8-yz3VssFnuABdT8aSvnjJsyI3c_Pc0iNXLBivWw', '2021-03-15 06:29:07', '2021-03-15 06:29:07'),
(371, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA5NzA1LCJleHAiOjE2MTU4OTYxMDV9.GgrtkkSEBZFNbCatQWPp2w7O7UPtzMmky64Yj4VlLWc', '2021-03-15 06:31:46', '2021-03-15 06:31:46'),
(372, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA5NzU1LCJleHAiOjE2MTU4OTYxNTV9.cgJYOJX6CyxoBGTH4GbMi9Pp5UATGy8PjMcfy90yBGY', '2021-03-15 06:32:35', '2021-03-15 06:32:35'),
(374, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA5ODQ5LCJleHAiOjE2MTU4OTYyNDl9.nQoCjwypfzBL1eql38yoWxToXNkMt-b8FVy5zCbVlOQ', '2021-03-15 06:34:09', '2021-03-15 06:34:09'),
(375, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODA5OTY1LCJleHAiOjE2MTU4OTYzNjV9.K5ydxMBmoAAuaa8KJMaj6Vl8MIoLRnc69VypZFKTnwc', '2021-03-15 06:36:05', '2021-03-15 06:36:05'),
(376, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwMDY5LCJleHAiOjE2MTU4OTY0Njl9.oNBopb0QhFCOANlVPI3u2Byi0LZ2ugwd1b48mofe184', '2021-03-15 06:37:49', '2021-03-15 06:37:49'),
(377, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwMTQ4LCJleHAiOjE2MTU4OTY1NDh9.qF5On08voUbiGcxgPJCI5R8nZ8R2O_BwZY9Bm1LAYD8', '2021-03-15 06:39:08', '2021-03-15 06:39:08'),
(378, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwMjAyLCJleHAiOjE2MTU4OTY2MDJ9.cNU3m3gqkl0F0OCSgJjt77ms5x9r0_38SMlD8swGuDg', '2021-03-15 06:40:03', '2021-03-15 06:40:03'),
(379, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwMjcxLCJleHAiOjE2MTU4OTY2NzF9.mdYDT2GCZNvo0KJyuxNRJwB0Hsk0-O_xoNpeJBki_Dc', '2021-03-15 06:41:11', '2021-03-15 06:41:11'),
(380, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwMzQyLCJleHAiOjE2MTU4OTY3NDJ9.8xyjTQGd59-jo55VUdw7EWKaMwuFnGqJdWJ_UUENnIY', '2021-03-15 06:42:22', '2021-03-15 06:42:22'),
(381, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwNDAyLCJleHAiOjE2MTU4OTY4MDJ9.Y2N0z0jorNMM0RSAmUH50QlmuqfwhqDsQjpqFObN8do', '2021-03-15 06:43:22', '2021-03-15 06:43:22'),
(382, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwNDU1LCJleHAiOjE2MTU4OTY4NTV9.VGcoZjwxNpf-GjZptSveYxs0ZzccbvVSS-U6ua5K-mU', '2021-03-15 06:44:15', '2021-03-15 06:44:15'),
(383, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwNTQxLCJleHAiOjE2MTU4OTY5NDF9.vDXJ2vIjITCOf6MjNDxnWU-ztzlhyMYNr0VTYx2EHoQ', '2021-03-15 06:45:41', '2021-03-15 06:45:41'),
(384, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwNjcyLCJleHAiOjE2MTU4OTcwNzJ9.Oz8axWbboBG2arQCvOl_pNSp0txC185jTAxhiareJMc', '2021-03-15 06:47:52', '2021-03-15 06:47:52'),
(385, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwNzMyLCJleHAiOjE2MTU4OTcxMzJ9.S2Z46FGIKETb7ExeTKw-ShKyOqwxyHwmyn5LgshPA5Y', '2021-03-15 06:48:52', '2021-03-15 06:48:52'),
(386, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwODA1LCJleHAiOjE2MTU4OTcyMDV9.OZWB3Ma_J2W1wJz2Th4LvPGl1GqvdKJkabPOdJbkzTs', '2021-03-15 06:50:05', '2021-03-15 06:50:05'),
(387, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwOTA3LCJleHAiOjE2MTU4OTczMDd9.GtuHOVwPfX3Q_MjOHnz2nYmC5N_1ALbFQMUlxv6W-Ao', '2021-03-15 06:51:47', '2021-03-15 06:51:47'),
(388, 1, 'AI1m2V0zm3Owh2CVV7egcEwRf2NZXy3Bczesd0Kk', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwOTY3LCJleHAiOjE2MTU4OTczNjd9.BNm1vsGHmT4CD2gasCN55Lk1bpbO8H-W8zVPz_3WonU', '2021-03-15 06:52:47', '2021-03-15 06:52:47'),
(389, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEwOTk4LCJleHAiOjE2MTU4OTczOTh9.tLYU4zJ3H1jXs95ozGHlt1efQMlIHneUQQwiYvSZm_U', '2021-03-15 06:53:18', '2021-03-15 06:53:18'),
(390, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODExMTg5LCJleHAiOjE2MTU4OTc1ODl9.cF9aJyTfg4ukmiZwUVKrGgq6s6S1nNLqyYozIYGZzrM', '2021-03-15 06:56:29', '2021-03-15 06:56:29'),
(391, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEyMzA1LCJleHAiOjE2MTU4OTg3MDV9.k_WGaJodS-Cm7WsqRsDwaueFLEfXfAWBhIXbvCw037g', '2021-03-15 07:15:05', '2021-03-15 07:15:05'),
(393, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODEzNzg2LCJleHAiOjE2MTU5MDAxODZ9.68cQyazKcPvURqBbUVQJS8jTI5JIpys6R-qYnnktisQ', '2021-03-15 07:39:46', '2021-03-15 07:39:46'),
(394, 1, 'SfSTYQY8eEPTXhoYhlx8FAGzjYyeCq7LSbFTumyr', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE0MTcyLCJleHAiOjE2MTU5MDA1NzJ9.CZV_phQu1x2Sg2gDZ2PNuuTKzMfKDXUDUFTft6FAtMY', '2021-03-15 07:46:12', '2021-03-15 07:46:12'),
(395, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE1Mjg2LCJleHAiOjE2MTU5MDE2ODZ9.XW0YUpODRORWKugYx0GIzN2HQlfv7SXX0EGdw7OTjEo', '2021-03-15 08:04:46', '2021-03-15 08:04:46'),
(398, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE3NTIzLCJleHAiOjE2MTU5MDM5MjN9.22MTVaEtRzO2B9FS5Lr-GEYk6JSJD5GyFg02KHDC2NU', '2021-03-15 08:42:03', '2021-03-15 08:42:03'),
(399, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE3NTg1LCJleHAiOjE2MTU5MDM5ODV9.LK29TMjq-bHGiB4leL4ANUwD_8v6lz6GA_eVmaYfllY', '2021-03-15 08:43:05', '2021-03-15 08:43:05'),
(400, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE3Njg4LCJleHAiOjE2MTU5MDQwODh9.ma0SmnxERur5OmNf7apCiNTjnb1QN82aVidU35m0frM', '2021-03-15 08:44:48', '2021-03-15 08:44:48'),
(401, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE3Nzc5LCJleHAiOjE2MTU5MDQxNzl9.zbEPQPaD4Nqbh1Ak1oA5JlfEIDhPc5MoajLY285v5mQ', '2021-03-15 08:46:19', '2021-03-15 08:46:19'),
(402, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE3ODM2LCJleHAiOjE2MTU5MDQyMzZ9.SWCEkly8PUwj7-BAV3qDyHYAIarwBmtCseghxpVfZ-I', '2021-03-15 08:47:16', '2021-03-15 08:47:16'),
(403, 1, 'iHVCFRwtNk7r6tBV7BAEHMbD8G8tMiFlOlEvmM3Y', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE4NTI0LCJleHAiOjE2MTU5MDQ5MjR9.NgPtjYRE7cw2Xq-JF3DPnnPMk_6GxAU5bp71oQGZRus', '2021-03-15 08:58:44', '2021-03-15 08:58:44'),
(404, 1, 'RNlg0bpCehlruIA8YVBjs7JFLRkd3vsOhn1Ey5RK', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE4NjU5LCJleHAiOjE2MTU5MDUwNTl9.a2ybjSH7YH0sBz11yDWwWLQw_D7c7bxVgKYYYnoNveA', '2021-03-15 09:00:59', '2021-03-15 09:00:59'),
(405, 1, 'dXLyqPVkffzk8LvvtdkBlv7W3eYn7aUh0uRmP1tR', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODE4ODczLCJleHAiOjE2MTU5MDUyNzN9.27N8f_cHRN2dN8iuXIZlggSBEXtivRZqW_LqAJLcvo4', '2021-03-15 09:04:33', '2021-03-15 09:04:33'),
(406, 1, 'vKPX1kMElQebJPhSKuiDIR4DNN89a4PgZqic6jSg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODIyOTczLCJleHAiOjE2MTU5MDkzNzN9.PZzccGPcbCCHIcHRw0C47alUe2en4dj9AKvYDXrT5Bo', '2021-03-15 10:12:53', '2021-03-15 10:12:53'),
(408, 1, 'mzlRYKg9DnMKaPJeGezdaUg3DFsGjTWx7Lq5P4xZ', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE1ODI0MzEyLCJleHAiOjE2MTU5MTA3MTJ9.vFmjz5N2-SMzEbx3kjkfKnRYhM0DB-2C7sFg0Kr5ODU', '2021-03-15 10:35:14', '2021-03-15 10:35:14'),
(422, 19, 'rd6S3hlvoP3EfU7jFlpZguGL9GqinqrJHg7QZ6kP', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MTksInVzZXJfc2xhY2siOiJXSU9aQVVjOW1jN2ZLY1gySnU4NnVlaVlNIn0sImlhdCI6MTYxNjE1OTQzOCwiZXhwIjoxNjE2MjQ1ODM4fQ.6339nHPyarnCHyeoB2A0tDyAKbQh3WAudoCdE7ntrbY', '2021-03-19 05:10:38', '2021-03-19 05:10:38'),
(423, 19, 'NI53aZizHYST1UMvorxB5feMmB4UR0dKbWrjhw3k', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MTksInVzZXJfc2xhY2siOiJXSU9aQVVjOW1jN2ZLY1gySnU4NnVlaVlNIn0sImlhdCI6MTYxNjE2MDE4OSwiZXhwIjoxNjE2MjQ2NTg5fQ.cROan_MMis3JMrqm79rVrdrjOXgw2C9e6TNPRbnqYxw', '2021-03-19 05:23:09', '2021-03-19 05:23:09'),
(425, 2, '2YZo7aN4qm2zN4bmT0sL8DwAZsjCjvj5tjLV5xXZ', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MiwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteU0ifSwiaWF0IjoxNjE2MTYxMDM4LCJleHAiOjE2MTYyNDc0Mzh9._a5snu3EXi30qCgYqiQ5fEmrGujTpv_Q_96pd8_sf50', '2021-03-19 05:37:18', '2021-03-19 05:37:18'),
(426, 1, 'l9pq8nQT67YgigtcojqOBYvyQydwUmigWqBASvSB', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE2NDgxMTMyLCJleHAiOjE2MTY1Njc1MzJ9.E_VxeC_-E1At-G61S-3d459sYK-eMla5ynZb71B_93I', '2021-03-22 22:32:13', '2021-03-22 22:32:13'),
(427, 2, 'abfBGVSwvCAo2lqW7fOE3m74IkPaZUkP02eGdnSe', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MiwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteU0ifSwiaWF0IjoxNjE2NDgxMTkxLCJleHAiOjE2MTY1Njc1OTF9.xlgMGRTKFbxP8huKk_KCI7iM7LBRgBr6-iK2k-LAf1w', '2021-03-22 22:33:11', '2021-03-22 22:33:11'),
(435, 2, 'l2OvwRn3p6UV53ot0bMZ8KZebDBDjR97dObQ3Kgc', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MiwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteU0ifSwiaWF0IjoxNjE2NDk1MTYxLCJleHAiOjE2MTY1ODE1NjF9.xmeZ2GUbY3ftDV0xNk_Uq5c0Q4ly8HZQMNmlv9aq0o0', '2021-03-23 02:26:01', '2021-03-23 02:26:01'),
(436, 1, '70AMkBf7Acq8ELoQJXqhly1ZDrygD0121kgGN3NT', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE3MDk3OTQzLCJleHAiOjE2MTcxODQzNDN9.fMWJCWP8geaQDGDZ_R8VitYFtC05WvTYa8f8lv3Titk', '2021-03-30 01:52:25', '2021-03-30 01:52:25'),
(438, 2, 'XUlQbZpJhdiPW7b4nfjpgPv7NRS4NzTInjdikqAr', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MiwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteU0ifSwiaWF0IjoxNjE3MDk4NDAzLCJleHAiOjE2MTcxODQ4MDN9.QpE0nGDvtVRaSvZwzVtlyetJFqD_zquu9Yr9Pu5LhQg', '2021-03-30 02:00:03', '2021-03-30 02:00:03'),
(443, 3, '6wnD9QcxtjJQwr2C8lqwncPSuPAl1B8f0yDOpHgj', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MywidXNlcl9zbGFjayI6IlBqUG4xcEpRbHBIWlM2cGZIeXo1QU8zS2IifSwiaWF0IjoxNjE3MTA3NTQwLCJleHAiOjE2MTcxOTM5NDB9.fNRCHDL1j1fen9IRVfd7GM-_jfQYeddpCSF_8xc4BIM', '2021-03-30 04:32:20', '2021-03-30 04:32:20'),
(446, 1, 'uVru0ipeGcM6MfE5dvbaxQdrpad47xSH3qDcQ2wX', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJqd3RfdG9rZW4iLCJzdWIiOnsidXNlcl9pZCI6MSwidXNlcl9zbGFjayI6Ikp4M3BDeUtWNUtMZ0FJeWcxbTZSczJteVkifSwiaWF0IjoxNjE3NjEyNzgzLCJleHAiOjE2MTc2OTkxODN9.7b0DBSO4SPehIEFkyuYojN8Z1yXQQpWVWyLKfxLEZc8', '2021-04-05 00:53:05', '2021-04-05 00:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_menus`
--

CREATE TABLE `user_menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_menus`
--

INSERT INTO `user_menus` (`id`, `user_id`, `menu_id`, `created_by`, `created_at`, `updated_at`) VALUES
(469, 2, 273, NULL, NULL, NULL),
(472, 2, 274, NULL, NULL, NULL),
(475, 2, 275, NULL, NULL, NULL),
(478, 2, 276, NULL, NULL, NULL),
(481, 2, 277, NULL, NULL, NULL),
(488, 1, 1, NULL, NULL, NULL),
(489, 1, 2, NULL, NULL, NULL),
(490, 1, 3, NULL, NULL, NULL),
(491, 1, 4, NULL, NULL, NULL),
(492, 1, 5, NULL, NULL, NULL),
(493, 1, 6, NULL, NULL, NULL),
(494, 1, 7, NULL, NULL, NULL),
(495, 1, 8, NULL, NULL, NULL),
(496, 1, 9, NULL, NULL, NULL),
(497, 1, 10, NULL, NULL, NULL),
(498, 1, 11, NULL, NULL, NULL),
(499, 1, 12, NULL, NULL, NULL),
(500, 1, 13, NULL, NULL, NULL),
(501, 1, 14, NULL, NULL, NULL),
(502, 1, 15, NULL, NULL, NULL),
(503, 1, 16, NULL, NULL, NULL),
(504, 1, 17, NULL, NULL, NULL),
(505, 1, 18, NULL, NULL, NULL),
(506, 1, 19, NULL, NULL, NULL),
(507, 1, 20, NULL, NULL, NULL),
(508, 1, 21, NULL, NULL, NULL),
(509, 1, 22, NULL, NULL, NULL),
(510, 1, 23, NULL, NULL, NULL),
(511, 1, 24, NULL, NULL, NULL),
(512, 1, 25, NULL, NULL, NULL),
(513, 1, 26, NULL, NULL, NULL),
(514, 1, 27, NULL, NULL, NULL),
(515, 1, 28, NULL, NULL, NULL),
(516, 1, 29, NULL, NULL, NULL),
(517, 1, 30, NULL, NULL, NULL),
(518, 1, 31, NULL, NULL, NULL),
(519, 1, 32, NULL, NULL, NULL),
(520, 1, 33, NULL, NULL, NULL),
(521, 1, 34, NULL, NULL, NULL),
(522, 1, 35, NULL, NULL, NULL),
(523, 1, 36, NULL, NULL, NULL),
(524, 1, 37, NULL, NULL, NULL),
(525, 1, 38, NULL, NULL, NULL),
(526, 1, 39, NULL, NULL, NULL),
(527, 1, 40, NULL, NULL, NULL),
(528, 1, 41, NULL, NULL, NULL),
(529, 1, 42, NULL, NULL, NULL),
(530, 1, 43, NULL, NULL, NULL),
(531, 1, 44, NULL, NULL, NULL),
(532, 1, 45, NULL, NULL, NULL),
(533, 1, 46, NULL, NULL, NULL),
(534, 1, 47, NULL, NULL, NULL),
(535, 1, 48, NULL, NULL, NULL),
(536, 1, 49, NULL, NULL, NULL),
(537, 1, 50, NULL, NULL, NULL),
(538, 1, 51, NULL, NULL, NULL),
(539, 1, 52, NULL, NULL, NULL),
(540, 1, 53, NULL, NULL, NULL),
(541, 1, 54, NULL, NULL, NULL),
(542, 1, 55, NULL, NULL, NULL),
(543, 1, 56, NULL, NULL, NULL),
(544, 1, 57, NULL, NULL, NULL),
(545, 1, 58, NULL, NULL, NULL),
(546, 1, 59, NULL, NULL, NULL),
(547, 1, 60, NULL, NULL, NULL),
(548, 1, 61, NULL, NULL, NULL),
(549, 1, 62, NULL, NULL, NULL),
(550, 1, 63, NULL, NULL, NULL),
(551, 1, 64, NULL, NULL, NULL),
(552, 1, 65, NULL, NULL, NULL),
(553, 1, 66, NULL, NULL, NULL),
(554, 1, 67, NULL, NULL, NULL),
(555, 1, 68, NULL, NULL, NULL),
(556, 1, 69, NULL, NULL, NULL),
(557, 1, 70, NULL, NULL, NULL),
(558, 1, 71, NULL, NULL, NULL),
(559, 1, 72, NULL, NULL, NULL),
(560, 1, 73, NULL, NULL, NULL),
(561, 1, 74, NULL, NULL, NULL),
(562, 1, 75, NULL, NULL, NULL),
(563, 1, 76, NULL, NULL, NULL),
(564, 1, 77, NULL, NULL, NULL),
(565, 1, 78, NULL, NULL, NULL),
(566, 1, 79, NULL, NULL, NULL),
(567, 1, 80, NULL, NULL, NULL),
(568, 1, 81, NULL, NULL, NULL),
(569, 1, 82, NULL, NULL, NULL),
(570, 1, 83, NULL, NULL, NULL),
(571, 1, 84, NULL, NULL, NULL),
(572, 1, 85, NULL, NULL, NULL),
(573, 1, 86, NULL, NULL, NULL),
(574, 1, 87, NULL, NULL, NULL),
(575, 1, 88, NULL, NULL, NULL),
(576, 1, 89, NULL, NULL, NULL),
(577, 1, 90, NULL, NULL, NULL),
(578, 1, 91, NULL, NULL, NULL),
(579, 1, 92, NULL, NULL, NULL),
(580, 1, 93, NULL, NULL, NULL),
(581, 1, 94, NULL, NULL, NULL),
(582, 1, 95, NULL, NULL, NULL),
(583, 1, 96, NULL, NULL, NULL),
(584, 1, 97, NULL, NULL, NULL),
(585, 1, 98, NULL, NULL, NULL),
(586, 1, 99, NULL, NULL, NULL),
(587, 1, 100, NULL, NULL, NULL),
(588, 1, 101, NULL, NULL, NULL),
(589, 1, 102, NULL, NULL, NULL),
(590, 1, 103, NULL, NULL, NULL),
(591, 1, 104, NULL, NULL, NULL),
(592, 1, 105, NULL, NULL, NULL),
(593, 1, 106, NULL, NULL, NULL),
(594, 1, 107, NULL, NULL, NULL),
(595, 1, 108, NULL, NULL, NULL),
(596, 1, 109, NULL, NULL, NULL),
(597, 1, 110, NULL, NULL, NULL),
(598, 1, 111, NULL, NULL, NULL),
(599, 1, 112, NULL, NULL, NULL),
(600, 1, 113, NULL, NULL, NULL),
(601, 1, 114, NULL, NULL, NULL),
(602, 1, 115, NULL, NULL, NULL),
(603, 1, 116, NULL, NULL, NULL),
(604, 1, 117, NULL, NULL, NULL),
(605, 1, 118, NULL, NULL, NULL),
(606, 1, 119, NULL, NULL, NULL),
(607, 1, 120, NULL, NULL, NULL),
(608, 1, 121, NULL, NULL, NULL),
(609, 1, 122, NULL, NULL, NULL),
(610, 1, 123, NULL, NULL, NULL),
(611, 1, 124, NULL, NULL, NULL),
(612, 1, 125, NULL, NULL, NULL),
(613, 1, 126, NULL, NULL, NULL),
(614, 1, 127, NULL, NULL, NULL),
(615, 1, 128, NULL, NULL, NULL),
(616, 1, 129, NULL, NULL, NULL),
(617, 1, 130, NULL, NULL, NULL),
(618, 1, 131, NULL, NULL, NULL),
(619, 1, 132, NULL, NULL, NULL),
(620, 1, 133, NULL, NULL, NULL),
(621, 1, 134, NULL, NULL, NULL),
(622, 1, 135, NULL, NULL, NULL),
(623, 1, 136, NULL, NULL, NULL),
(624, 1, 137, NULL, NULL, NULL),
(625, 1, 138, NULL, NULL, NULL),
(626, 1, 139, NULL, NULL, NULL),
(627, 1, 140, NULL, NULL, NULL),
(628, 1, 141, NULL, NULL, NULL),
(629, 1, 142, NULL, NULL, NULL),
(630, 1, 143, NULL, NULL, NULL),
(631, 1, 144, NULL, NULL, NULL),
(632, 1, 145, NULL, NULL, NULL),
(633, 1, 146, NULL, NULL, NULL),
(634, 1, 147, NULL, NULL, NULL),
(635, 1, 148, NULL, NULL, NULL),
(636, 1, 149, NULL, NULL, NULL),
(637, 1, 150, NULL, NULL, NULL),
(638, 1, 151, NULL, NULL, NULL),
(639, 1, 152, NULL, NULL, NULL),
(640, 1, 153, NULL, NULL, NULL),
(641, 1, 154, NULL, NULL, NULL),
(642, 1, 155, NULL, NULL, NULL),
(643, 1, 156, NULL, NULL, NULL),
(644, 1, 157, NULL, NULL, NULL),
(645, 1, 158, NULL, NULL, NULL),
(646, 1, 159, NULL, NULL, NULL),
(647, 1, 160, NULL, NULL, NULL),
(648, 1, 161, NULL, NULL, NULL),
(649, 1, 162, NULL, NULL, NULL),
(650, 1, 163, NULL, NULL, NULL),
(651, 1, 164, NULL, NULL, NULL),
(652, 1, 165, NULL, NULL, NULL),
(653, 1, 166, NULL, NULL, NULL),
(654, 1, 167, NULL, NULL, NULL),
(655, 1, 168, NULL, NULL, NULL),
(656, 1, 169, NULL, NULL, NULL),
(657, 1, 170, NULL, NULL, NULL),
(658, 1, 171, NULL, NULL, NULL),
(659, 1, 172, NULL, NULL, NULL),
(660, 1, 173, NULL, NULL, NULL),
(661, 1, 174, NULL, NULL, NULL),
(662, 1, 175, NULL, NULL, NULL),
(663, 1, 176, NULL, NULL, NULL),
(664, 1, 177, NULL, NULL, NULL),
(665, 1, 178, NULL, NULL, NULL),
(666, 1, 185, NULL, NULL, NULL),
(667, 1, 187, NULL, NULL, NULL),
(668, 1, 188, NULL, NULL, NULL),
(669, 1, 189, NULL, NULL, NULL),
(670, 1, 190, NULL, NULL, NULL),
(671, 1, 191, NULL, NULL, NULL),
(672, 1, 192, NULL, NULL, NULL),
(673, 1, 193, NULL, NULL, NULL),
(674, 1, 194, NULL, NULL, NULL),
(675, 1, 195, NULL, NULL, NULL),
(676, 1, 196, NULL, NULL, NULL),
(677, 1, 197, NULL, NULL, NULL),
(678, 1, 198, NULL, NULL, NULL),
(679, 1, 199, NULL, NULL, NULL),
(680, 1, 200, NULL, NULL, NULL),
(681, 1, 201, NULL, NULL, NULL),
(682, 1, 202, NULL, NULL, NULL),
(683, 1, 203, NULL, NULL, NULL),
(684, 1, 204, NULL, NULL, NULL),
(685, 1, 205, NULL, NULL, NULL),
(686, 1, 206, NULL, NULL, NULL),
(687, 1, 207, NULL, NULL, NULL),
(688, 1, 208, NULL, NULL, NULL),
(689, 1, 209, NULL, NULL, NULL),
(690, 1, 210, NULL, NULL, NULL),
(691, 1, 211, NULL, NULL, NULL),
(692, 1, 212, NULL, NULL, NULL),
(693, 1, 213, NULL, NULL, NULL),
(694, 1, 214, NULL, NULL, NULL),
(695, 1, 215, NULL, NULL, NULL),
(696, 1, 216, NULL, NULL, NULL),
(697, 1, 217, NULL, NULL, NULL),
(698, 1, 218, NULL, NULL, NULL),
(699, 1, 219, NULL, NULL, NULL),
(700, 1, 220, NULL, NULL, NULL),
(701, 1, 221, NULL, NULL, NULL),
(702, 1, 222, NULL, NULL, NULL),
(703, 1, 249, NULL, NULL, NULL),
(704, 1, 250, NULL, NULL, NULL),
(705, 1, 252, NULL, NULL, NULL),
(706, 1, 253, NULL, NULL, NULL),
(707, 1, 254, NULL, NULL, NULL),
(708, 1, 255, NULL, NULL, NULL),
(709, 1, 256, NULL, NULL, NULL),
(710, 1, 257, NULL, NULL, NULL),
(711, 1, 258, NULL, NULL, NULL),
(712, 1, 259, NULL, NULL, NULL),
(713, 1, 260, NULL, NULL, NULL),
(714, 1, 261, NULL, NULL, NULL),
(715, 1, 262, NULL, NULL, NULL),
(716, 1, 263, NULL, NULL, NULL),
(717, 1, 264, NULL, NULL, NULL),
(718, 1, 265, NULL, NULL, NULL),
(719, 1, 266, NULL, NULL, NULL),
(720, 1, 267, NULL, NULL, NULL),
(721, 1, 268, NULL, NULL, NULL),
(722, 1, 269, NULL, NULL, NULL),
(723, 1, 270, NULL, NULL, NULL),
(724, 1, 271, NULL, NULL, NULL),
(725, 1, 272, NULL, NULL, NULL),
(726, 1, 273, NULL, NULL, NULL),
(727, 1, 274, NULL, NULL, NULL),
(728, 1, 275, NULL, NULL, NULL),
(729, 1, 276, NULL, NULL, NULL),
(730, 1, 277, NULL, NULL, NULL),
(731, 1, 278, NULL, NULL, NULL),
(732, 1, 279, NULL, NULL, NULL),
(733, 1, 280, NULL, NULL, NULL),
(734, 1, 281, NULL, NULL, NULL),
(735, 1, 282, NULL, NULL, NULL),
(736, 1, 283, NULL, NULL, NULL),
(737, 1, 284, NULL, NULL, NULL),
(738, 1, 285, NULL, NULL, NULL),
(739, 1, 286, NULL, NULL, NULL),
(740, 1, 287, NULL, NULL, NULL),
(741, 1, 289, NULL, NULL, NULL),
(742, 1, 291, NULL, NULL, NULL),
(743, 1, 290, NULL, NULL, NULL),
(744, 1, 292, NULL, NULL, NULL),
(745, 1, 288, NULL, NULL, NULL),
(746, 1, 297, NULL, NULL, NULL),
(747, 1, 294, NULL, NULL, NULL),
(748, 1, 298, NULL, NULL, NULL),
(749, 1, 295, NULL, NULL, NULL),
(750, 1, 296, NULL, NULL, NULL),
(751, 1, 299, NULL, NULL, NULL),
(752, 1, 293, NULL, NULL, NULL),
(753, 1, 301, NULL, NULL, NULL),
(754, 1, 300, NULL, NULL, NULL),
(755, 1, 304, NULL, NULL, NULL),
(756, 1, 305, NULL, NULL, NULL),
(757, 1, 306, NULL, NULL, NULL),
(758, 1, 302, NULL, NULL, NULL),
(759, 1, 303, NULL, NULL, NULL),
(760, 1, 308, NULL, NULL, NULL),
(761, 1, 307, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_points_settings`
--

CREATE TABLE `user_points_settings` (
  `id` int(11) NOT NULL,
  `slack` varchar(50) NOT NULL,
  `token_id` varchar(50) DEFAULT NULL,
  `secret_key` varchar(50) DEFAULT NULL,
  `merchant_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_stores`
--

CREATE TABLE `user_stores` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_stores`
--

INSERT INTO `user_stores` (`id`, `user_id`, `store_id`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 2, 1, NULL, NULL, NULL),
(4, 1, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `weekly_holiday`
--

CREATE TABLE `weekly_holiday` (
  `wk_id` int(11) NOT NULL,
  `dayname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `weekly_holiday`
--

INSERT INTO `weekly_holiday` (`wk_id`, `dayname`) VALUES
(1, 'Friday,Wednesday');

-- --------------------------------------------------------

--
-- Table structure for table `zid_action`
--

CREATE TABLE `zid_action` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `endpoint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zid_action`
--

INSERT INTO `zid_action` (`id`, `key`, `title`, `action`, `endpoint`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'synczid_product', 'Synz Products & Categories', 'It will import all the products & categories from Zid into Wosul ERP ', '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `zid_activity`
--

CREATE TABLE `zid_activity` (
  `id` int(10) UNSIGNED NOT NULL,
  `zid_action_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zid_store`
--

CREATE TABLE `zid_store` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `zid_store_id` varchar(20) DEFAULT NULL,
  `authorization` text NOT NULL,
  `access_token` text NOT NULL,
  `expires_in` varchar(50) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounts_slack_unique` (`slack`),
  ADD UNIQUE KEY `accounts_account_code_unique` (`account_code`),
  ADD KEY `accounts_store_id_account_type_status_index` (`store_id`,`account_type`,`status`);

--
-- Indexes for table `acc_coa`
--
ALTER TABLE `acc_coa`
  ADD PRIMARY KEY (`HeadName`);

--
-- Indexes for table `acc_transaction`
--
ALTER TABLE `acc_transaction`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `appsetting`
--
ALTER TABLE `appsetting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arabic_text`
--
ALTER TABLE `arabic_text`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_history`
--
ALTER TABLE `attendance_history`
  ADD PRIMARY KEY (`atten_his_id`);

--
-- Indexes for table `award`
--
ALTER TABLE `award`
  ADD PRIMARY KEY (`award_id`);

--
-- Indexes for table `bank_information`
--
ALTER TABLE `bank_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing_counters`
--
ALTER TABLE `billing_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `billing_counters_slack_unique` (`slack`),
  ADD KEY `billing_counters_store_id_billing_counter_code_status_index` (`store_id`,`billing_counter_code`,`status`);

--
-- Indexes for table `bonat_store_counter_points_settings`
--
ALTER TABLE `bonat_store_counter_points_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bonat_user_points_settings`
--
ALTER TABLE `bonat_user_points_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `measurement_units_slack_unique` (`slack`),
  ADD KEY `measurement_units_unit_code_status_index` (`status`);

--
-- Indexes for table `business_registers`
--
ALTER TABLE `business_registers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `business_registers_slack_unique` (`slack`),
  ADD KEY `business_register_indexes` (`user_id`,`store_id`),
  ADD KEY `business_registers_billing_counter_id_index` (`billing_counter_id`);

--
-- Indexes for table `candidate_basic_info`
--
ALTER TABLE `candidate_basic_info`
  ADD PRIMARY KEY (`can_id`);

--
-- Indexes for table `candidate_education_info`
--
ALTER TABLE `candidate_education_info`
  ADD PRIMARY KEY (`can_edu_id`);

--
-- Indexes for table `candidate_interview`
--
ALTER TABLE `candidate_interview`
  ADD PRIMARY KEY (`can_int_id`);

--
-- Indexes for table `candidate_selection`
--
ALTER TABLE `candidate_selection`
  ADD PRIMARY KEY (`can_sel_id`);

--
-- Indexes for table `candidate_shortlist`
--
ALTER TABLE `candidate_shortlist`
  ADD PRIMARY KEY (`can_short_id`);

--
-- Indexes for table `candidate_workexperience`
--
ALTER TABLE `candidate_workexperience`
  ADD PRIMARY KEY (`can_workexp_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_slack_unique` (`slack`),
  ADD KEY `category_status_store_id_category_code_index` (`status`,`store_id`,`category_code`);

--
-- Indexes for table `combos`
--
ALTER TABLE `combos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combos_slack_unique` (`slack`);

--
-- Indexes for table `combo_groups`
--
ALTER TABLE `combo_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combo_groups_slack_unique` (`slack`);

--
-- Indexes for table `combo_products`
--
ALTER TABLE `combo_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combo_products_slack_unique` (`slack`);

--
-- Indexes for table `combo_sizes`
--
ALTER TABLE `combo_sizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combo_sizes_slack_unique` (`slack`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_status_index` (`status`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_slack_unique` (`slack`),
  ADD KEY `customers_email_phone_status_index` (`email`,`phone`,`status`);

--
-- Indexes for table `customer_additional_info`
--
ALTER TABLE `customer_additional_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_additional_info_slack_unique` (`slack`);

--
-- Indexes for table `custom_table`
--
ALTER TABLE `custom_table`
  ADD PRIMARY KEY (`custom_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `deviceinfo`
--
ALTER TABLE `deviceinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discount_codes_slack_unique` (`slack`),
  ADD KEY `discount_codes_status_store_id_discount_code_index` (`status`,`store_id`,`discount_code`);

--
-- Indexes for table `duty_type`
--
ALTER TABLE `duty_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edfapay_transactions`
--
ALTER TABLE `edfapay_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_benifit`
--
ALTER TABLE `employee_benifit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_equipment`
--
ALTER TABLE `employee_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_history`
--
ALTER TABLE `employee_history`
  ADD PRIMARY KEY (`emp_his_id`);

--
-- Indexes for table `employee_performance`
--
ALTER TABLE `employee_performance`
  ADD PRIMARY KEY (`emp_per_id`);

--
-- Indexes for table `employee_salary_payment`
--
ALTER TABLE `employee_salary_payment`
  ADD PRIMARY KEY (`emp_sal_pay_id`);

--
-- Indexes for table `employee_salary_setup`
--
ALTER TABLE `employee_salary_setup`
  ADD PRIMARY KEY (`e_s_s_id`);

--
-- Indexes for table `emp_attendance`
--
ALTER TABLE `emp_attendance`
  ADD PRIMARY KEY (`att_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`equipment_id`);

--
-- Indexes for table `equipment_type`
--
ALTER TABLE `equipment_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `expense_information`
--
ALTER TABLE `expense_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expresspay`
--
ALTER TABLE `expresspay`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grand_loan`
--
ALTER TABLE `grand_loan`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `hardwares`
--
ALTER TABLE `hardwares`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_area`
--
ALTER TABLE `income_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_counts`
--
ALTER TABLE `inventory_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_count_items`
--
ALTER TABLE `inventory_count_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_slack_unique` (`slack`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoice_indexes` (`store_id`,`invoice_reference`,`bill_to`,`bill_to_id`,`status`);

--
-- Indexes for table `invoices_return`
--
ALTER TABLE `invoices_return`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_slack_unique` (`slack`),
  ADD KEY `invoice_indexes` (`store_id`,`invoice_reference`,`bill_to`,`bill_to_id`,`status`);

--
-- Indexes for table `invoice_charges`
--
ALTER TABLE `invoice_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_products`
--
ALTER TABLE `invoice_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_products_slack_unique` (`slack`),
  ADD KEY `invoice_products_invoice_id_status_index` (`invoice_id`,`status`);

--
-- Indexes for table `invoice_return_products`
--
ALTER TABLE `invoice_return_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_products_slack_unique` (`slack`),
  ADD KEY `invoice_return_products_invoice_id_status_index` (`return_invoice_id`,`status`);

--
-- Indexes for table `invoice_services`
--
ALTER TABLE `invoice_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_products_slack_unique` (`slack`),
  ADD KEY `invoice_products_invoice_id_status_index` (`invoice_id`,`status`);

--
-- Indexes for table `keyboard_shortcuts`
--
ALTER TABLE `keyboard_shortcuts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `keyboard_shortcuts_keyboard_constant_unique` (`keyboard_constant`),
  ADD KEY `keyboard_shortcuts_status_keyboard_constant_index` (`status`,`keyboard_constant`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_language_constant_unique` (`language_constant`),
  ADD UNIQUE KEY `languages_language_code_unique` (`language_code`),
  ADD KEY `language_tables_indexes` (`status`);

--
-- Indexes for table `language_setting`
--
ALTER TABLE `language_setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_setting_code_unique` (`lang_code`) USING BTREE,
  ADD KEY `language_setting_tables_indexes` (`status`) USING BTREE;

--
-- Indexes for table `language_setting_phrases`
--
ALTER TABLE `language_setting_phrases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_apply`
--
ALTER TABLE `leave_apply`
  ADD PRIMARY KEY (`leave_appl_id`);

--
-- Indexes for table `leave_type`
--
ALTER TABLE `leave_type`
  ADD PRIMARY KEY (`leave_type_id`);

--
-- Indexes for table `loan_installment`
--
ALTER TABLE `loan_installment`
  ADD PRIMARY KEY (`loan_inst_id`);

--
-- Indexes for table `marital_info`
--
ALTER TABLE `marital_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_account_type`
--
ALTER TABLE `master_account_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_account_type_account_type_constant_unique` (`account_type_constant`),
  ADD KEY `master_account_type_status_index` (`status`);

--
-- Indexes for table `master_billing_type`
--
ALTER TABLE `master_billing_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_billing_type_billing_type_constant_unique` (`billing_type_constant`),
  ADD KEY `master_billing_type_status_index` (`status`);

--
-- Indexes for table `master_date_format`
--
ALTER TABLE `master_date_format`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_invoice_print_type`
--
ALTER TABLE `master_invoice_print_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_order_type`
--
ALTER TABLE `master_order_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_order_type_order_type_constant_unique` (`order_type_constant`),
  ADD KEY `master_order_type_order_type_constant_status_index` (`order_type_constant`,`status`);

--
-- Indexes for table `master_status`
--
ALTER TABLE `master_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_status_key_value_value_constant_status_index` (`key`,`value`,`value_constant`,`status`);

--
-- Indexes for table `master_tax_option`
--
ALTER TABLE `master_tax_option`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_tax_option_tax_option_constant_unique` (`tax_option_constant`),
  ADD KEY `master_tax_option_status_index` (`status`);

--
-- Indexes for table `master_transaction_type`
--
ALTER TABLE `master_transaction_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_transaction_type_transaction_type_constant_unique` (`transaction_type_constant`),
  ADD KEY `master_transaction_type_status_index` (`status`);

--
-- Indexes for table `measurements`
--
ALTER TABLE `measurements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `measurement_units_slack_unique` (`slack`),
  ADD KEY `measurement_units_unit_code_status_index` (`status`);

--
-- Indexes for table `measurement_categories`
--
ALTER TABLE `measurement_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `measurement_units_slack_unique` (`slack`),
  ADD KEY `measurement_units_unit_code_status_index` (`status`);

--
-- Indexes for table `measurement_conversions`
--
ALTER TABLE `measurement_conversions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `measurement_units_slack_unique` (`slack`),
  ADD KEY `measurement_units_unit_code_status_index` (`status`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_menu_key_unique` (`menu_key`),
  ADD KEY `menus_type_menu_key_parent_status_index` (`type`,`menu_key`,`parent`,`status`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_cashiers`
--
ALTER TABLE `mobile_cashiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modifiers`
--
ALTER TABLE `modifiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modifier_slack_unique` (`slack`),
  ADD KEY `modifier_unit_status_index` (`status`);

--
-- Indexes for table `modifier_options`
--
ALTER TABLE `modifier_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modifier_options_slack_unique` (`slack`),
  ADD KEY `modifier_options_code_status_index` (`status`);

--
-- Indexes for table `modifier_option_ingredients`
--
ALTER TABLE `modifier_option_ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_permission`
--
ALTER TABLE `module_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_module_id` (`fk_module_id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `newsletter_subscription`
--
ALTER TABLE `newsletter_subscription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_board`
--
ALTER TABLE `notice_board`
  ADD PRIMARY KEY (`notice_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notifications_slack_unique` (`slack`),
  ADD KEY `notification_indexes` (`user_id`,`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_slack_unique` (`slack`),
  ADD KEY `orders_customer_id_store_id_status_index` (`customer_id`,`store_id`,`status`),
  ADD KEY `orders_kitchen_status_index` (`kitchen_status`),
  ADD KEY `orders_register_id_index` (`register_id`),
  ADD KEY `orders_payment_method_id_index` (`payment_method_id`);

--
-- Indexes for table `order_damage`
--
ALTER TABLE `order_damage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_duplication_logs`
--
ALTER TABLE `order_duplication_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_products_slack_unique` (`slack`),
  ADD KEY `order_products_order_id_product_id_product_code_status_index` (`order_id`,`product_id`,`status`) USING BTREE;

--
-- Indexes for table `order_product_modifier_options`
--
ALTER TABLE `order_product_modifier_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_return`
--
ALTER TABLE `order_return`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slack` (`slack`);

--
-- Indexes for table `order_return_product`
--
ALTER TABLE `order_return_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slack` (`slack`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_slack_unique` (`slack`),
  ADD KEY `payment_methods_status_index` (`status`);

--
-- Indexes for table `payroll_holiday`
--
ALTER TABLE `payroll_holiday`
  ADD PRIMARY KEY (`payrl_holi_id`);

--
-- Indexes for table `payroll_tax_collection`
--
ALTER TABLE `payroll_tax_collection`
  ADD PRIMARY KEY (`tax_coll_id`);

--
-- Indexes for table `payroll_tax_setup`
--
ALTER TABLE `payroll_tax_setup`
  ADD PRIMARY KEY (`tax_setup_id`);

--
-- Indexes for table `pay_frequency`
--
ALTER TABLE `pay_frequency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`pos_id`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slack_unique` (`slack`),
  ADD KEY `products_status_store_id_product_code_index` (`status`,`store_id`) USING BTREE;

--
-- Indexes for table `product_barcode_details`
--
ALTER TABLE `product_barcode_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD UNIQUE KEY `product_slack` (`product_slack`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_images_slack_unique` (`slack`),
  ADD KEY `product_images_product_id_index` (`product_id`);

--
-- Indexes for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_ingredients_slack_unique` (`slack`),
  ADD KEY `product_ingredients_index` (`product_id`,`ingredient_product_id`,`measurement_id`);

--
-- Indexes for table `product_modifiers`
--
ALTER TABLE `product_modifiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_prices`
--
ALTER TABLE `product_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_slack_unique` (`slack`),
  ADD KEY `purchase_orders_store_id_po_number_supplier_id_status_index` (`store_id`,`po_number`,`supplier_id`,`status`);

--
-- Indexes for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_order_products_slack_unique` (`slack`),
  ADD KEY `purchase_order_products_purchase_order_id_status_index` (`purchase_order_id`,`status`);

--
-- Indexes for table `qoyod_categories`
--
ALTER TABLE `qoyod_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qoyod_customers`
--
ALTER TABLE `qoyod_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qoyod_inventory`
--
ALTER TABLE `qoyod_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qoyod_mesurment_units`
--
ALTER TABLE `qoyod_mesurment_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qoyod_products`
--
ALTER TABLE `qoyod_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qoyod_users_accounts`
--
ALTER TABLE `qoyod_users_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qoyod_vendors`
--
ALTER TABLE `qoyod_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_codes_slack_unique` (`slack`);

--
-- Indexes for table `quantity_adjustments`
--
ALTER TABLE `quantity_adjustments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quantity_adjustment_items`
--
ALTER TABLE `quantity_adjustment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quantity_adjustment_id` (`quantity_adjustment_id`);

--
-- Indexes for table `quantity_history`
--
ALTER TABLE `quantity_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quantity_purchases`
--
ALTER TABLE `quantity_purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_slack_unique` (`slack`),
  ADD KEY `purchase_orders_store_id_po_number_supplier_id_status_index` (`store_id`,`po_number`,`supplier_id`,`status`);

--
-- Indexes for table `quantity_purchase_products`
--
ALTER TABLE `quantity_purchase_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_order_products_slack_unique` (`slack`),
  ADD KEY `purchase_order_products_purchase_order_id_status_index` (`purchase_order_id`,`status`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotations_slack_unique` (`slack`),
  ADD KEY `quotation_indexes` (`store_id`,`quotation_number`,`quotation_reference`,`bill_to`,`bill_to_id`,`status`);

--
-- Indexes for table `quotation_products`
--
ALTER TABLE `quotation_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotation_products_slack_unique` (`slack`),
  ADD KEY `quotation_products_quotation_id_status_index` (`quotation_id`,`status`);

--
-- Indexes for table `rate_type`
--
ALTER TABLE `rate_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_tables_slack_unique` (`slack`),
  ADD KEY `restaurant_tables_indexes` (`store_id`,`status`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slack_unique` (`slack`),
  ADD UNIQUE KEY `roles_role_code_unique` (`role_code`),
  ADD KEY `roles_status_index` (`status`);

--
-- Indexes for table `role_menus`
--
ALTER TABLE `role_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_menus_role_id_menu_id_index` (`role_id`,`menu_id`);

--
-- Indexes for table `salary_setup_header`
--
ALTER TABLE `salary_setup_header`
  ADD PRIMARY KEY (`s_s_h_id`);

--
-- Indexes for table `salary_sheet_generate`
--
ALTER TABLE `salary_sheet_generate`
  ADD PRIMARY KEY (`ssg_id`);

--
-- Indexes for table `salary_type`
--
ALTER TABLE `salary_type`
  ADD PRIMARY KEY (`salary_type_id`);

--
-- Indexes for table `sec_menu_item`
--
ALTER TABLE `sec_menu_item`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `sec_role_permission`
--
ALTER TABLE `sec_role_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sec_role_tbl`
--
ALTER TABLE `sec_role_tbl`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `sec_user_access_tbl`
--
ALTER TABLE `sec_user_access_tbl`
  ADD PRIMARY KEY (`role_acc_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `sessions_id_unique` (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting_app`
--
ALTER TABLE `setting_app`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setting_app_company_name_app_date_format_index` (`company_name`(191),`app_date_format`);

--
-- Indexes for table `setting_mail`
--
ALTER TABLE `setting_mail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_mail_slack_unique` (`slack`),
  ADD KEY `setting_mail_type_status_index` (`type`,`status`);

--
-- Indexes for table `setting_sms_gateways`
--
ALTER TABLE `setting_sms_gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_sms_gateways_slack_unique` (`slack`),
  ADD KEY `setting_sms_gateways_account_id_token_twilio_number_index` (`account_id`,`token`,`twilio_number`);

--
-- Indexes for table `sms_gateway_settings`
--
ALTER TABLE `sms_gateway_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sms_gateway_settings_slack_unique` (`slack`),
  ADD UNIQUE KEY `sms_gateway_settings_api_key_unique` (`api_key`);

--
-- Indexes for table `sms_templates`
--
ALTER TABLE `sms_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sms_templates_slack_unique` (`slack`),
  ADD UNIQUE KEY `sms_templates_template_key_unique` (`template_key`),
  ADD KEY `sms_templates_status_index` (`status`);

--
-- Indexes for table `stock_returns`
--
ALTER TABLE `stock_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_returns_slack_unique` (`slack`),
  ADD UNIQUE KEY `stock_returns_return_number_unique` (`return_number`),
  ADD KEY `return_indexes` (`store_id`,`bill_to`,`bill_to_id`,`status`);

--
-- Indexes for table `stock_return_products`
--
ALTER TABLE `stock_return_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_return_products_slack_unique` (`slack`),
  ADD KEY `stock_return_products_stock_return_id_status_index` (`stock_return_id`,`status`);

--
-- Indexes for table `stock_transfer`
--
ALTER TABLE `stock_transfer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_transfer_slack_unique` (`slack`),
  ADD UNIQUE KEY `stock_transfer_stock_transfer_reference_unique` (`stock_transfer_reference`),
  ADD KEY `stock_transfer_store_id_from_store_id_to_store_id_status_index` (`store_id`,`from_store_id`,`to_store_id`,`status`);

--
-- Indexes for table `stock_transfer_products`
--
ALTER TABLE `stock_transfer_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_transfer_products_slack_unique` (`slack`),
  ADD KEY `stock_transfer_product_indexes` (`stock_transfer_id`,`product_id`,`destination_product_id`,`status`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stores_slack_unique` (`slack`),
  ADD UNIQUE KEY `stores_store_code_unique` (`store_code`),
  ADD KEY `stores_status_index` (`status`),
  ADD KEY `stores_restaurant_mode_index` (`restaurant_mode`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_slack_unique` (`slack`),
  ADD KEY `suppliers_status_store_id_supplier_code_index` (`status`,`store_id`,`supplier_code`);

--
-- Indexes for table `synchronizer_setting`
--
ALTER TABLE `synchronizer_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `targets`
--
ALTER TABLE `targets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `targets_slack_unique` (`slack`),
  ADD KEY `targets_store_id_month_index` (`store_id`,`month`);

--
-- Indexes for table `tax_codes`
--
ALTER TABLE `tax_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tax_codes_slack_unique` (`slack`),
  ADD KEY `tax_codes_status_store_id_tax_code_index` (`status`,`store_id`,`tax_code`);

--
-- Indexes for table `tax_code_type`
--
ALTER TABLE `tax_code_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_code_type_tax_code_id_index` (`tax_code_id`);

--
-- Indexes for table `tax_names`
--
ALTER TABLE `tax_names`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_slack_unique` (`slack`),
  ADD UNIQUE KEY `transactions_transaction_code_unique` (`transaction_code`),
  ADD KEY `transaction_indexes` (`store_id`,`account_id`,`transaction_type`,`bill_to`,`bill_to_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_slack_unique` (`slack`),
  ADD UNIQUE KEY `users_user_code_unique` (`user_code`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_status_index` (`status`),
  ADD KEY `users_language_id_index` (`language_id`);

--
-- Indexes for table `user_access_tokens`
--
ALTER TABLE `user_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `user_menus`
--
ALTER TABLE `user_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_menus_user_id_menu_id_index` (`user_id`,`menu_id`);

--
-- Indexes for table `user_points_settings`
--
ALTER TABLE `user_points_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_stores`
--
ALTER TABLE `user_stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_stores_user_id_store_id_index` (`user_id`,`store_id`);

--
-- Indexes for table `weekly_holiday`
--
ALTER TABLE `weekly_holiday`
  ADD PRIMARY KEY (`wk_id`);

--
-- Indexes for table `zid_action`
--
ALTER TABLE `zid_action`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zid_activity`
--
ALTER TABLE `zid_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zid_store`
--
ALTER TABLE `zid_store`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `acc_transaction`
--
ALTER TABLE `acc_transaction`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appsetting`
--
ALTER TABLE `appsetting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance_history`
--
ALTER TABLE `attendance_history`
  MODIFY `atten_his_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `award`
--
ALTER TABLE `award`
  MODIFY `award_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_information`
--
ALTER TABLE `bank_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing_counters`
--
ALTER TABLE `billing_counters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bonat_store_counter_points_settings`
--
ALTER TABLE `bonat_store_counter_points_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bonat_user_points_settings`
--
ALTER TABLE `bonat_user_points_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_registers`
--
ALTER TABLE `business_registers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `candidate_education_info`
--
ALTER TABLE `candidate_education_info`
  MODIFY `can_edu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_interview`
--
ALTER TABLE `candidate_interview`
  MODIFY `can_int_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_selection`
--
ALTER TABLE `candidate_selection`
  MODIFY `can_sel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_shortlist`
--
ALTER TABLE `candidate_shortlist`
  MODIFY `can_short_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_workexperience`
--
ALTER TABLE `candidate_workexperience`
  MODIFY `can_workexp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `combos`
--
ALTER TABLE `combos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `combo_groups`
--
ALTER TABLE `combo_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `combo_products`
--
ALTER TABLE `combo_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `combo_sizes`
--
ALTER TABLE `combo_sizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_additional_info`
--
ALTER TABLE `customer_additional_info`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_table`
--
ALTER TABLE `custom_table`
  MODIFY `custom_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deviceinfo`
--
ALTER TABLE `deviceinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discount_codes`
--
ALTER TABLE `discount_codes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `duty_type`
--
ALTER TABLE `duty_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `edfapay_transactions`
--
ALTER TABLE `edfapay_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_benifit`
--
ALTER TABLE `employee_benifit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_equipment`
--
ALTER TABLE `employee_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_history`
--
ALTER TABLE `employee_history`
  MODIFY `emp_his_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_performance`
--
ALTER TABLE `employee_performance`
  MODIFY `emp_per_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_salary_payment`
--
ALTER TABLE `employee_salary_payment`
  MODIFY `emp_sal_pay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_salary_setup`
--
ALTER TABLE `employee_salary_setup`
  MODIFY `e_s_s_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_attendance`
--
ALTER TABLE `emp_attendance`
  MODIFY `att_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `equipment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment_type`
--
ALTER TABLE `equipment_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expense_information`
--
ALTER TABLE `expense_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expresspay`
--
ALTER TABLE `expresspay`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gender`
--
ALTER TABLE `gender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grand_loan`
--
ALTER TABLE `grand_loan`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hardwares`
--
ALTER TABLE `hardwares`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_area`
--
ALTER TABLE `income_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_counts`
--
ALTER TABLE `inventory_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_count_items`
--
ALTER TABLE `inventory_count_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices_return`
--
ALTER TABLE `invoices_return`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_charges`
--
ALTER TABLE `invoice_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_products`
--
ALTER TABLE `invoice_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_return_products`
--
ALTER TABLE `invoice_return_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_services`
--
ALTER TABLE `invoice_services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keyboard_shortcuts`
--
ALTER TABLE `keyboard_shortcuts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1015;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `language_setting`
--
ALTER TABLE `language_setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language_setting_phrases`
--
ALTER TABLE `language_setting_phrases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_apply`
--
ALTER TABLE `leave_apply`
  MODIFY `leave_appl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_type`
--
ALTER TABLE `leave_type`
  MODIFY `leave_type_id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_installment`
--
ALTER TABLE `loan_installment`
  MODIFY `loan_inst_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marital_info`
--
ALTER TABLE `marital_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `master_account_type`
--
ALTER TABLE `master_account_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `master_billing_type`
--
ALTER TABLE `master_billing_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `master_date_format`
--
ALTER TABLE `master_date_format`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `master_invoice_print_type`
--
ALTER TABLE `master_invoice_print_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_order_type`
--
ALTER TABLE `master_order_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_status`
--
ALTER TABLE `master_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `master_tax_option`
--
ALTER TABLE `master_tax_option`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_transaction_type`
--
ALTER TABLE `master_transaction_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `measurements`
--
ALTER TABLE `measurements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `measurement_categories`
--
ALTER TABLE `measurement_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `measurement_conversions`
--
ALTER TABLE `measurement_conversions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `mobile_cashiers`
--
ALTER TABLE `mobile_cashiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modifiers`
--
ALTER TABLE `modifiers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modifier_options`
--
ALTER TABLE `modifier_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modifier_option_ingredients`
--
ALTER TABLE `modifier_option_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `module_permission`
--
ALTER TABLE `module_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsletter_subscription`
--
ALTER TABLE `newsletter_subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notice_board`
--
ALTER TABLE `notice_board`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_damage`
--
ALTER TABLE `order_damage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_duplication_logs`
--
ALTER TABLE `order_duplication_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_modifier_options`
--
ALTER TABLE `order_product_modifier_options`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_return`
--
ALTER TABLE `order_return`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_return_product`
--
ALTER TABLE `order_return_product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payroll_holiday`
--
ALTER TABLE `payroll_holiday`
  MODIFY `payrl_holi_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_tax_collection`
--
ALTER TABLE `payroll_tax_collection`
  MODIFY `tax_coll_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_tax_setup`
--
ALTER TABLE `payroll_tax_setup`
  MODIFY `tax_setup_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pay_frequency`
--
ALTER TABLE `pay_frequency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `pos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_barcode_details`
--
ALTER TABLE `product_barcode_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_modifiers`
--
ALTER TABLE `product_modifiers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_prices`
--
ALTER TABLE `product_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qoyod_categories`
--
ALTER TABLE `qoyod_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qoyod_customers`
--
ALTER TABLE `qoyod_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qoyod_inventory`
--
ALTER TABLE `qoyod_inventory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qoyod_mesurment_units`
--
ALTER TABLE `qoyod_mesurment_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qoyod_products`
--
ALTER TABLE `qoyod_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qoyod_users_accounts`
--
ALTER TABLE `qoyod_users_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qoyod_vendors`
--
ALTER TABLE `qoyod_vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quantity_adjustments`
--
ALTER TABLE `quantity_adjustments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `quantity_adjustment_items`
--
ALTER TABLE `quantity_adjustment_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `quantity_history`
--
ALTER TABLE `quantity_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quantity_purchases`
--
ALTER TABLE `quantity_purchases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quantity_purchase_products`
--
ALTER TABLE `quantity_purchase_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_products`
--
ALTER TABLE `quotation_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rate_type`
--
ALTER TABLE `rate_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_menus`
--
ALTER TABLE `role_menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=753;

--
-- AUTO_INCREMENT for table `salary_setup_header`
--
ALTER TABLE `salary_setup_header`
  MODIFY `s_s_h_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `salary_sheet_generate`
--
ALTER TABLE `salary_sheet_generate`
  MODIFY `ssg_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `salary_type`
--
ALTER TABLE `salary_type`
  MODIFY `salary_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sec_menu_item`
--
ALTER TABLE `sec_menu_item`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT for table `sec_role_permission`
--
ALTER TABLE `sec_role_permission`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1089;

--
-- AUTO_INCREMENT for table `sec_role_tbl`
--
ALTER TABLE `sec_role_tbl`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sec_user_access_tbl`
--
ALTER TABLE `sec_user_access_tbl`
  MODIFY `role_acc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `setting_app`
--
ALTER TABLE `setting_app`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `setting_mail`
--
ALTER TABLE `setting_mail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting_sms_gateways`
--
ALTER TABLE `setting_sms_gateways`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_gateway_settings`
--
ALTER TABLE `sms_gateway_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sms_templates`
--
ALTER TABLE `sms_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_returns`
--
ALTER TABLE `stock_returns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_return_products`
--
ALTER TABLE `stock_return_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer`
--
ALTER TABLE `stock_transfer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_products`
--
ALTER TABLE `stock_transfer_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `synchronizer_setting`
--
ALTER TABLE `synchronizer_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `targets`
--
ALTER TABLE `targets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_codes`
--
ALTER TABLE `tax_codes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_code_type`
--
ALTER TABLE `tax_code_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_names`
--
ALTER TABLE `tax_names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_access_tokens`
--
ALTER TABLE `user_access_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=447;

--
-- AUTO_INCREMENT for table `user_menus`
--
ALTER TABLE `user_menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=762;

--
-- AUTO_INCREMENT for table `user_points_settings`
--
ALTER TABLE `user_points_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_stores`
--
ALTER TABLE `user_stores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `weekly_holiday`
--
ALTER TABLE `weekly_holiday`
  MODIFY `wk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zid_action`
--
ALTER TABLE `zid_action`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zid_activity`
--
ALTER TABLE `zid_activity`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zid_store`
--
ALTER TABLE `zid_store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_barcode_details`
--
ALTER TABLE `product_barcode_details`
  ADD CONSTRAINT `product_barcode_details_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`);

--
-- Constraints for table `quantity_adjustment_items`
--
ALTER TABLE `quantity_adjustment_items`
  ADD CONSTRAINT `quantity_adjustment_items_ibfk_1` FOREIGN KEY (`quantity_adjustment_id`) REFERENCES `quantity_adjustments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
