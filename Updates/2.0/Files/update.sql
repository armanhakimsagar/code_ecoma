ALTER TABLE `admins` ADD `remember_token` VARCHAR(255) NULL DEFAULT NULL AFTER `password`;

ALTER TABLE `admin_notifications` CHANGE `read_status` `is_read` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `admin_notifications` CHANGE `user_id` `user_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `admin_notifications` CHANGE `seller_id` `seller_id` INT NOT NULL DEFAULT '0';

ALTER TABLE `admin_password_resets` CHANGE `email` `email` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `admin_password_resets` CHANGE `token` `token` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `applied_coupons` CHANGE `user_id` `user_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `applied_coupons` CHANGE `coupon_id` `coupon_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `applied_coupons` CHANGE `order_id` `order_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `applied_coupons` CHANGE `amount` `amount` DECIMAL(28,8) NOT NULL DEFAULT '0';

ALTER TABLE `assign_product_attributes` CHANGE `product_id` `product_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `assign_product_attributes` CHANGE `product_attribute_id` `product_attribute_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `assign_product_attributes` CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `assign_product_attributes` CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `assign_product_attributes` CHANGE `extra_price` `extra_price` DECIMAL(28,8) NOT NULL DEFAULT '0.00';

ALTER TABLE `brands` CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `brands` CHANGE `logo` `logo` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `brands` CHANGE `meta_title` `meta_title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `brands` CHANGE `meta_description` `meta_description` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `brands` CHANGE `meta_keywords` `meta_keywords` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `carts` CHANGE `product_id` `product_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `carts` CHANGE `session_id` `session_id` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `carts` CHANGE `quantity` `quantity` INT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `categories` ADD `position` INT NULL DEFAULT NULL AFTER `parent_id`;
ALTER TABLE `categories` CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `categories` CHANGE `icon` `icon` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `categories` CHANGE `meta_title` `meta_title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `categories` CHANGE `meta_description` `meta_description` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `categories` CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `categories` CHANGE `meta_keywords` `meta_keywords` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `coupons` CHANGE `coupon_name` `coupon_name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `coupons` CHANGE `coupon_code` `coupon_code` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `coupons` CHANGE `discount_type` `discount_type` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=fixed, 2=percent';
ALTER TABLE `coupons` CHANGE `coupon_amount` `coupon_amount` DECIMAL(28,8) NOT NULL DEFAULT '0';
ALTER TABLE `coupons` CHANGE `minimum_spend` `minimum_spend` DECIMAL(28,8) NULL DEFAULT '0.00000000';
ALTER TABLE `coupons` CHANGE `maximum_spend` `maximum_spend` DECIMAL(28,8) NOT NULL DEFAULT '0';

ALTER TABLE `coupons_categories` CHANGE `coupon_id` `coupon_id` BIGINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `coupons_categories` CHANGE `category_id` `category_id` BIGINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `coupons_products` CHANGE `coupon_id` `coupon_id` BIGINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `coupons_products` CHANGE `product_id` `product_id` BIGINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `deposits` CHANGE `user_id` `user_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `deposits` CHANGE `order_id` `order_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `deposits` CHANGE `method_code` `method_code` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `deposits` CHANGE `method_currency` `method_currency` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `deposits` CHANGE `final_amo` `final_amount` DECIMAL(28,8) NOT NULL DEFAULT '0.00000000';
ALTER TABLE `deposits` CHANGE `btc_amo` `btc_amount` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `deposits` CHANGE `try` `payment_try` INT NOT NULL DEFAULT '0';
ALTER TABLE `deposits` ADD `is_web` TINYINT(1) NOT NULL DEFAULT '0' AFTER `from_api`;
ALTER TABLE `deposits` ADD `success_url` VARCHAR(255) NULL DEFAULT NULL AFTER `admin_feedback`, ADD `failed_url` VARCHAR(255) NULL DEFAULT NULL AFTER `success_url`, ADD `last_cron` INT NULL DEFAULT '0' AFTER `failed_url`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2025 at 11:09 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecoma`
--

-- --------------------------------------------------------

--
-- Table structure for table `device_tokens`
--

CREATE TABLE `device_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `is_app` tinyint(1) NOT NULL DEFAULT '0',
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `device_tokens`
--
ALTER TABLE `device_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `email_logs` CHANGE `mail_sender` `sender` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `email_logs` CHANGE `email_from` `sent_from` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `email_logs` CHANGE `email_to` `sent_to` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `email_logs` ADD `notification_type` VARCHAR(40) NULL DEFAULT NULL AFTER `updated_at`, ADD `image` VARCHAR(255) NULL DEFAULT NULL AFTER `notification_type`, ADD `user_read` TINYINT NOT NULL DEFAULT '0' AFTER `image`;
ALTER TABLE `email_logs` CHANGE `id` `id` BIGINT UNSIGNED NOT NULL auto_increment FIRST, CHANGE `user_id` `user_id` INT UNSIGNED NULL DEFAULT NULL AFTER `id`, CHANGE `seller_id` `seller_id` INT NULL DEFAULT NULL AFTER `user_id`, CHANGE `sender` `sender` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `seller_id`, CHANGE `sent_from` `sent_from` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `sender`, CHANGE `sent_to` `sent_to` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `sent_from`, CHANGE `subject` `subject` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `sent_to`, CHANGE `message` `message` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `subject`, CHANGE `notification_type` `notification_type` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `message`, CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `notification_type`, CHANGE `user_read` `user_read` TINYINT NOT NULL DEFAULT '0' AFTER `image`;
RENAME TABLE `email_logs` TO `notification_logs`;

DROP TABLE `email_sms_templates`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2025 at 11:15 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecoma`
--

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `push_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shortcodes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  `email_sent_from_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent_from_address` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_status` tinyint(1) NOT NULL DEFAULT '1',
  `sms_sent_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
(1, 'BAL_ADD', 'Balance - Added', 'Your Account has been Credited', '{{site_name}} - Balance Added', '<div>We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account.</div><div><br></div><div>Here are the details of the transaction:</div><div><br></div><div><b>Transaction Number: </b>{{trx}}</div><div><b>Current Balance:</b> {{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>If you have any questions or require further assistance, please don\'t hesitate to contact us. We\'re here to assist you.</div>', 'We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account.', '{{amount}} {{site_currency}} has been successfully added to your account.', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, '{{site_name}} Finance', NULL, 0, NULL, 1, '2021-11-03 12:00:00', '2024-05-25 00:49:44'),
(2, 'BAL_SUB', 'Balance - Subtracted', 'Your Account has been Debited', '{{site_name}} - Balance Subtracted', '<div>We wish to inform you that an amount of {{amount}} {{site_currency}} has been successfully deducted from your account.</div><div><br></div><div>Below are the details of the transaction:</div><div><br></div><div><b>Transaction Number:</b> {{trx}}</div><div><b>Current Balance: </b>{{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>Should you require any further clarification or assistance, please do not hesitate to reach out to us. We are here to assist you in any way we can.</div><div><br></div><div>Thank you for your continued trust in {{site_name}}.</div>', 'We wish to inform you that an amount of {{amount}} {{site_currency}} has been successfully deducted from your account.', '{{amount}} {{site_currency}} debited from your account.', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:17:48'),
(3, 'PAYMENT_COMPLETE', 'Deposit - Automated - Successful', 'Payment Completed Successfully', '{{site_name}} - Payment successful', '<div>We\'re delighted to inform you that your deposit of {{amount}} {{site_currency}} via {{method_name}} has been completed.</div><div><br></div><div>Below, you\'ll find the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge: </b>{{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received:</b> {{method_amount}} {{method_currency}}</div><div><b>Paid via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Your current balance stands at {{post_balance}} {{site_currency}}.</div><div><br></div><div>If you have any questions or need further assistance, feel free to reach out to our support team. We\'re here to assist you in any way we can.</div>', 'We\'re delighted to inform you that your deposit of {{amount}} {{site_currency}} via {{method_name}} has been completed.', 'Payment Completed Successfully', '{\"trx\":\"Transaction number for the payment\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\", \"order_number\":\"Order Number\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 1, '2021-11-03 12:00:00', '2024-05-08 07:20:34'),
(4, 'PAYMENT_APPROVE', 'Payment - Manual - Approved', 'Payment Request Approved', '{{site_name}} - Payment Request Approved', '<div>We are pleased to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been approved.</div><div><br></div><div>Here are the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge: </b>{{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received: </b>{{method_amount}} {{method_currency}}</div><div><b>Paid via: </b>{{method_name}}</div><div><b>Transaction Number: </b>{{trx}}</div><div><br></div><div>Your current balance now stands at {{post_balance}} {{site_currency}}.</div><div><br></div><div>Should you have any questions or require further assistance, please feel free to contact our support team. We\'re here to help.</div>', 'We are pleased to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been approved.', 'Deposit of {{amount}} {{site_currency}} via {{method_name}} has been approved.', '{\"trx\":\"Transaction number for the payment\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\", \"order_number\":\"Order Number\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:19:49'),
(5, 'PAYMENT_REJECT', 'Payment - Manual - Rejected', 'Payment Request Rejected', '{{site_name}} - Payment Request Rejected', '<div>We regret to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.</div><div><br></div><div>Here are the details of the rejected deposit:</div><div><br></div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received:</b> {{method_amount}} {{method_currency}}</div><div><b>Paid via:</b> {{method_name}}</div><div><b>Charge:</b> {{charge}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>If you have any questions or need further clarification, please don\'t hesitate to contact us. We\'re here to assist you.</div><div><br></div><div>Rejection Reason:</div><div>{{rejection_message}}</div><div><br></div><div>Thank you for your understanding.</div>', 'We regret to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', 'Your payment request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"rejection_message\":\"Rejection message by the admin\", \"order_number\":\"Order Number\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:20:13'),
(6, 'PAYMENT_REQUEST', 'Payment - Manual - Requested', 'Payment Request Submitted Successfully', NULL, '<div>We are pleased to confirm that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.</div><div><br></div><div>Below are the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Payable:</b> {{method_amount}} {{method_currency}}</div><div><b>Pay via: </b>{{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Should you have any questions or require further assistance, please feel free to reach out to our support team. We\'re here to assist you.</div>', 'We are pleased to confirm that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.', 'Your deposit request of {{amount}} {{site_currency}} via {{method_name}} submitted successfully.', '{\"trx\":\"Transaction number for the payment\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\", \"order_number\":\"Order Number\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:42'),
(7, 'PASS_RESET_CODE', 'Password - Reset - Code', 'Password Reset', '{{site_name}} Password Reset Code', '<div>We\'ve received a request to reset the password for your account on <b>{{time}}</b>. The request originated from\r\n            the following IP address: <b>{{ip}}</b>, using <b>{{browser}}</b> on <b>{{operating_system}}</b>.\r\n    </div><br>\r\n    <div><span>To proceed with the password reset, please use the following account recovery code</span>: <span><b><font size=\"6\">{{code}}</font></b></span></div><br>\r\n    <div><span>If you did not initiate this password reset request, please disregard this message. Your account security\r\n            remains our top priority, and we advise you to take appropriate action if you suspect any unauthorized\r\n            access to your account.</span></div>', 'To proceed with the password reset, please use the following account recovery code: {{code}}', 'To proceed with the password reset, please use the following account recovery code: {{code}}', '{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, '{{site_name}} Authentication Center', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:24:57'),
(8, 'PASS_RESET_DONE', 'Password - Reset - Confirmation', 'Password Reset Successful', NULL, '<div><div><span>We are writing to inform you that the password reset for your account was successful. This action was completed at {{time}} from the following browser</span>: <span>{{browser}}</span><span>on {{operating_system}}, with the IP address</span>: <span>{{ip}}</span>.</div><br><div><span>Your account security is our utmost priority, and we are committed to ensuring the safety of your information. If you did not initiate this password reset or notice any suspicious activity on your account, please contact our support team immediately for further assistance.</span></div></div>', 'We are writing to inform you that the password reset for your account was successful.', 'We are writing to inform you that the password reset for your account was successful.', '{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, '{{site_name}} Authentication Center', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:24'),
(9, 'ADMIN_SUPPORT_REPLY', 'Support - Reply', 'Re: {{ticket_subject}} - Ticket #{{ticket_id}}', '{{site_name}} - Support Ticket Replied', '<div>\r\n    <div><span>Thank you for reaching out to us regarding your support ticket with the subject</span>:\r\n        <span>\"{{ticket_subject}}\"&nbsp;</span><span>and ticket ID</span>: {{ticket_id}}.</div><br>\r\n    <div><span>We have carefully reviewed your inquiry, and we are pleased to provide you with the following\r\n            response</span><span>:</span></div><br>\r\n    <div>{{reply}}</div><br>\r\n    <div><span>If you have any further questions or need additional assistance, please feel free to reply by clicking on\r\n            the following link</span>: <a href=\"{{link}}\" title=\"\" target=\"_blank\">{{link}}</a><span>. This link will take you to\r\n            the ticket thread where you can provide further information or ask for clarification.</span></div><br>\r\n    <div><span>Thank you for your patience and cooperation as we worked to address your concerns.</span></div>\r\n</div>', 'Thank you for reaching out to us regarding your support ticket with the subject: \"{{ticket_subject}}\" and ticket ID: {{ticket_id}}. We have carefully reviewed your inquiry. To check the response, please go to the following link: {{link}}', 'Re: {{ticket_subject}} - Ticket #{{ticket_id}}', '{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}', 1, '{{site_name}} Support Team', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:26:06'),
(10, 'EVER_CODE', 'Verification - Email', 'Email Verification Code', NULL, '<div>\r\n    <div><span>Thank you for taking the time to verify your email address with us. Your email verification code\r\n            is</span>: <b><font size=\"6\">{{code}}</font></b></div><br>\r\n    <div><span>Please enter this code in the designated field on our platform to complete the verification\r\n            process.</span></div><br>\r\n    <div><span>If you did not request this verification code, please disregard this email. Your account security is our\r\n            top priority, and we advise you to take appropriate measures if you suspect any unauthorized access.</span>\r\n    </div><br>\r\n    <div><span>If you have any questions or encounter any issues during the verification process, please don\'t hesitate\r\n            to contact our support team for assistance.</span></div><br>\r\n    <div><span>Thank you for choosing us.</span></div>\r\n</div>', '---', '---', '{\"code\":\"Email verification code\"}', 1, '{{site_name}} Verification Center', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:12'),
(11, 'SVER_CODE', 'Verification - SMS', 'Verify Your Mobile Number', NULL, '---', 'Your mobile verification code is {{code}}. Please enter this code in the appropriate field to verify your mobile number. If you did not request this code, please ignore this message.', '---', '{\"code\":\"SMS Verification Code\"}', 0, '{{site_name}} Verification Center', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:03'),
(12, 'WITHDRAW_APPROVE', 'Withdraw - Approved', 'Withdrawal Confirmation: Your Request Processed Successfully', '{{site_name}} - Withdrawal Request Approved', '<div>We are writing to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been processed successfully.</div><div><br></div><div>Below are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>You will receive:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><hr><div><br></div><div><b>Details of Processed Payment:</b></div><div>{{admin_details}}</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'We are writing to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been processed successfully.', 'Withdrawal Confirmation: Your Request Processed Successfully', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"admin_details\":\"Details provided by the admin\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:26:37'),
(13, 'WITHDRAW_REJECT', 'Withdraw - Rejected', 'Withdrawal Request Rejected', '{{site_name}} - Withdrawal Request Rejected', '<div>We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.</div><div><br></div><div>Here are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Expected Amount:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><hr><div><br></div><div><b>Refund Details:</b></div><div>{{amount}} {{site_currency}} has been refunded to your account, and your current balance is {{post_balance}} {{site_currency}}.</div><div><br></div><hr><div><br></div><div><b>Reason for Rejection:</b></div><div>{{admin_details}}</div><div><br></div><div>If you have any questions or concerns regarding this rejection or need further assistance, please do not hesitate to contact our support team. We apologize for any inconvenience this may have caused.</div>', 'We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', 'Withdrawal Request Rejected', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:26:55'),
(14, 'WITHDRAW_REQUEST', 'Withdraw - Requested', 'Withdrawal Request Confirmation', '{{site_name}} - Requested for withdrawal', '<div>We are pleased to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.</div><div><br></div><div>Here are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Expected Amount:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Your current balance is {{post_balance}} {{site_currency}}.</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'We are pleased to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.', 'Withdrawal request submitted successfully', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this transaction\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:27:20'),
(15, 'DEFAULT', 'Default Template', '{{subject}}', '{{subject}}', '{{message}}', '{{message}}', '{{message}}', '{\"subject\":\"Subject\",\"message\":\"Message\"}', 1, NULL, NULL, 1, NULL, 1, '2019-09-14 13:14:22', '2024-05-16 01:32:53'),
(16, 'KYC_APPROVE', 'KYC Approved', 'KYC Details has been approved', '{{site_name}} - KYC Approved', '<div><div><span>We are pleased to inform you that your Know Your Customer (KYC) information has been successfully reviewed and approved. This means that you are now eligible to conduct any payout operations within our system.</span></div><br><div><span>Your commitment to completing the KYC process promptly is greatly appreciated, as it helps us ensure the security and integrity of our platform for all users.</span></div><br><div><span>With your KYC verification now complete, you can proceed with confidence to carry out any payout transactions you require. Should you encounter any issues or have any questions along the way, please don\'t hesitate to reach out to our support team. We\'re here to assist you every step of the way.</span></div><br><div><span>Thank you once again for choosing {{site_name}} and for your cooperation in this matter.</span></div></div>', 'We are pleased to inform you that your Know Your Customer (KYC) information has been successfully reviewed and approved. This means that you are now eligible to conduct any payout operations within our system.', 'Your  Know Your Customer (KYC) information has been approved successfully', '[]', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-05-08 07:23:57'),
(17, 'KYC_REJECT', 'KYC Rejected', 'KYC has been rejected', '{{site_name}} - KYC Rejected', '<div><div><span>We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards. As a result, we are unable to approve your KYC submission at this time.</span></div><br><div><span>We understand that this news may be disappointing, and we want to assure you that we take these matters seriously to maintain the security and integrity of our platform.</span></div><br><div><span>Reasons for rejection may include discrepancies or incomplete information in the documentation provided. If you believe there has been a misunderstanding or if you would like further clarification on why your KYC was rejected, please don\'t hesitate to contact our support team.</span></div><br><div><span>We encourage you to review your submitted information and ensure that all details are accurate and up-to-date. Once any necessary adjustments have been made, you are welcome to resubmit your KYC information for review.</span></div><br><div><span>We apologize for any inconvenience this may cause and appreciate your understanding and cooperation in this matter.</span></div><br><div>Rejection Reason:</div><div>{{reason}}</div><div><br></div><div><span>Thank you for your continued support and patience.</span></div></div>', 'We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards. As a result, we are unable to approve your KYC submission at this time. We encourage you to review your submitted information and ensure that all details are accurate and up-to-date. Once any necessary adjustments have been made, you are welcome to resubmit your KYC information for review.', 'Your  Know Your Customer (KYC) information has been rejected', '{\"reason\":\"Rejection Reason\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-05-08 07:24:13'),
(18, 'ORDER_ON_PROCESSING_CONFIRMATION', 'Send Order On Processing Alert', 'Order On Processing Alert', NULL, 'Greetings from {{site_name}}\r\nYour Order is on processing now get ready to to receive your products.\r\n\r\nYour Order ID: {{order_id}} ', 'Greetings from {{site_name}}\r\nYour Order is on processing now get ready to to receive your products.\r\n\r\nYour Order ID: {{order_id}} ', NULL, '{\"site_name\":\"Company Name\", \"order_id\":\"Order ID\"}', 1, NULL, NULL, 1, NULL, 0, '2024-12-19 06:41:06', NULL),
(19, 'ORDER_DISPATCHED_CONFIRMATION', 'Send Order Dispatched Alert', 'Order Dispatched Alert', NULL, 'Greetings from {{site_name}}\r\nYour Order has been dispatched please receive your products.\r\n\r\nYour Order ID: {{order_id}} ', 'Greetings from {{site_name}}\r\nYour Order has been dispatched please receive your products.\r\n\r\nYour Order ID: {{order_id}} ', NULL, '{\"site_name\":\"Company Name\", \"order_id\":\"Order ID\"}', 1, NULL, NULL, 1, NULL, 0, '2024-12-19 06:42:25', NULL),
(20, 'ORDER_DELIVERY_CONFIRMATION', 'Send Order Delivery Confirmation', 'Order Delivery Confirmation', 'Greetings from {{site_name}}\r\nYour order for has been delivered. Thanks for your purchase form us.\r\nOrder Id :{{order_id}}', 'Greetings from {{site_name}}\r\nYour order for has been delivered. Thanks for your purchase form us.\r\nOrder Id :{{order_id}}', 'Greetings from {{site_name}}\r\nYour order for has been delivered. Thanks for your purchase form us.\r\nOrder Id :{{order_id}}', NULL, '{\"site_name\":\"Company Name\", \"order_id\":\"Order Track Number\"}', 1, NULL, NULL, 1, NULL, 0, '2024-12-19 06:43:29', NULL),
(21, 'ORDER_CANCELLATION_CONFIRMATION', 'Send Cancellation Alert', 'Cancellation Alert', NULL, 'Greetings from {{site_name}}. We are sorry say that we couldn\'<span style=\"color: rgb(33, 37, 41); font-size: 1rem;\">t accept your order now.&nbsp; <br>Your order has been canceled.<br><br>Your Order ID: {{order_id}}</span>', 'Greetings from {{site_name}}. \r\n\r\nYour order will be canceled............\r\n\r\nYour Order ID: {{order_id}}', NULL, '{\"site_name\":\"Company Name\", \"order_id\":\"Order ID\"}', 1, NULL, NULL, 1, NULL, 0, '2024-12-19 06:45:33', NULL),
(22, 'ORDER_RETAKE_CONFIRMATION', 'Send Order Retake Confirmation', 'Order Retake Confirmation', NULL, 'Greetings from {{site_name}}\r\nYour Order has been retaken.\r\n<div><span style=\"font-family: &quot;Open Sans&quot;, sans-serif;\">Order ID <b>{{order_id}}&nbsp;</b></span><br></div>', 'Greetings from {{site_name}}\r\nYour Order has been retaken.\r\nOrder ID {{order_id}}  ', NULL, '{\"site_name\":\"Company Name\", \"order_id\":\"Order ID\"}', 1, NULL, NULL, 1, NULL, 0, '2024-12-19 06:52:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `offers` CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `offers` CHANGE `discount_type` `discount_type` TINYINT NOT NULL DEFAULT '1' COMMENT '1=fixed, 2=percent';
ALTER TABLE `offers` CHANGE `amount` `amount` DECIMAL(28,8) NOT NULL DEFAULT '0';
ALTER TABLE `offers` CHANGE `start_date` `start_date` DATE NULL DEFAULT NULL;
ALTER TABLE `offers` CHANGE `end_date` `end_date` DATE NULL DEFAULT NULL;

ALTER TABLE `offers_products` CHANGE `offer_id` `offer_id` BIGINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `offers_products` CHANGE `product_id` `product_id` BIGINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `orders` CHANGE `order_number` `order_number` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `orders` CHANGE `user_id` `user_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `orders` CHANGE `shipping_address` `shipping_address` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `orders` CHANGE `shipping_method_id` `shipping_method_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `orders` CHANGE `shipping_charge` `shipping_charge` DECIMAL(28,8) NOT NULL DEFAULT '0.00';
ALTER TABLE `orders` CHANGE `coupon_code` `coupon_code` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `orders` CHANGE `coupon_amount` `coupon_amount` DECIMAL(28,8) NOT NULL DEFAULT '0';
ALTER TABLE `orders` CHANGE `total_amount` `total_amount` DECIMAL(28,8) NOT NULL DEFAULT '0.00';
ALTER TABLE `orders` CHANGE `order_type` `order_type` TINYINT NOT NULL DEFAULT '1' COMMENT '1 = Order for self, 2 = order for a guest';

ALTER TABLE `order_details` CHANGE `order_id` `order_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `order_details` CHANGE `product_id` `product_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `order_details` CHANGE `quantity` `quantity` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `order_details` CHANGE `base_price` `base_price` DECIMAL(28,8) NOT NULL DEFAULT '0.00';
ALTER TABLE `order_details` CHANGE `total_price` `total_price` DECIMAL(28,8) NOT NULL DEFAULT '0.00';
ALTER TABLE `order_details` CHANGE `details` `details` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `password_resets` CHANGE `email` `email` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `password_resets` CHANGE `token` `token` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2025 at 11:38 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecoma`
--

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `products` CHANGE `brand_id` `brand_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `products` CHANGE `sku` `sku` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `products` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `products` ADD `slug` VARCHAR(255) NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `products` CHANGE `model` `model` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `products` CHANGE `main_image` `main_image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `products` CHANGE `base_price` `base_price` DECIMAL(28,8) NOT NULL DEFAULT '0';
ALTER TABLE `products` CHANGE `meta_title` `meta_title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `products` CHANGE `meta_description` `meta_description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `products` CHANGE `meta_keywords` `meta_keywords` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `products_categories` CHANGE `product_id` `product_id` BIGINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `products_categories` CHANGE `category_id` `category_id` BIGINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `product_attributes` CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `product_attributes` CHANGE `name_for_user` `name_for_user` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `product_attributes` CHANGE `type` `type` TINYINT NOT NULL DEFAULT '1' COMMENT '1 = text, 2 = color, 3= image';

ALTER TABLE `product_images` CHANGE `product_id` `product_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `product_images` CHANGE `assign_product_attribute_id` `assign_product_attribute_id` INT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `product_images` CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `product_reviews` CHANGE `user_id` `user_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `product_reviews` CHANGE `product_id` `product_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `product_reviews` CHANGE `review` `review` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `product_reviews` CHANGE `rating` `rating` INT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `product_stocks` CHANGE `product_id` `product_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `product_stocks` CHANGE `attributes` `attributes` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `product_stocks` CHANGE `sku` `sku` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `product_stocks` CHANGE `quantity` `quantity` INT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `sellers` ADD `dial_code` VARCHAR(40) NULL DEFAULT NULL AFTER `email`;
ALTER TABLE `sellers` ADD `country_name` VARCHAR(255) NULL DEFAULT NULL AFTER `password`;

ALTER TABLE `sellers` ADD `city` VARCHAR(255) NULL DEFAULT NULL AFTER `country_code`, ADD `state` VARCHAR(255) NULL DEFAULT NULL AFTER `city`, ADD `zip` VARCHAR(255) NULL DEFAULT NULL AFTER `state`;
ALTER TABLE `sellers` ADD `profile_complete` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `featured`;

ALTER TABLE `user_logins` ADD `seller_id` INT NULL DEFAULT NULL AFTER `user_id`;
INSERT INTO user_logins (seller_id, user_ip, city, country, country_code, longitude, latitude, browser, os, created_at, updated_at)
SELECT id, seller_ip, city, country, country_code, longitude, latitude, browser, os,created_at, updated_at FROM seller_logins; 

DROP TABLE seller_logins;

ALTER TABLE `sell_logs` CHANGE `seller_id` `seller_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `sell_logs` CHANGE `product_id` `product_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `sell_logs` CHANGE `order_id` `order_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `sell_logs` CHANGE `qty` `qty` INT NOT NULL DEFAULT '0';
ALTER TABLE `sell_logs` CHANGE `product_price` `product_price` DECIMAL(28,8) NOT NULL DEFAULT '0';
ALTER TABLE `sell_logs` ADD `product_commission` DECIMAL(5.2) NOT NULL DEFAULT '0' AFTER `product_price`;
ALTER TABLE `sell_logs` CHANGE `product_commission` `product_commission` DECIMAL(5,2) NOT NULL DEFAULT '0';
ALTER TABLE `sell_logs` CHANGE `after_commission` `after_commission` DECIMAL(28,8) NOT NULL DEFAULT '0';

ALTER TABLE `shipping_methods` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `shipping_methods` CHANGE `charge` `charge` DECIMAL(28,8) NOT NULL DEFAULT '0.00';
ALTER TABLE `shipping_methods` CHANGE `shipping_time` `shipping_time` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'in days';

ALTER TABLE `shops` CHANGE `seller_id` `seller_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `shops` CHANGE `phone` `phone` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `stock_logs` CHANGE `stock_id` `stock_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `stock_logs` CHANGE `quantity` `quantity` INT NOT NULL DEFAULT '0';
ALTER TABLE `stock_logs` CHANGE `type` `type` TINYINT NOT NULL DEFAULT '1' COMMENT '1= Updated by admin, 2= Sell';

ALTER TABLE `subscribers` CHANGE `email` `email` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `support_attachments` CHANGE `support_message_id` `support_message_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `support_attachments` CHANGE `attachment` `attachment` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `support_messages` CHANGE `supportticket_id` `support_ticket_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `support_messages` CHANGE `message` `message` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `transactions` ADD `remark` VARCHAR(40) NULL DEFAULT NULL AFTER `details`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2025 at 12:31 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecoma`
--

-- --------------------------------------------------------

--
-- Table structure for table `update_logs`
--

CREATE TABLE `update_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `update_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `update_logs`
--
ALTER TABLE `update_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `update_logs`
--
ALTER TABLE `update_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `users` ADD `dial_code` VARCHAR(40) NULL DEFAULT NULL AFTER `email`;
ALTER TABLE `users` ADD `country_name` VARCHAR(255) NULL DEFAULT NULL AFTER `password`;
ALTER TABLE `users` ADD `city` VARCHAR(255) NULL DEFAULT NULL AFTER `country_code`, ADD `state` VARCHAR(255) NULL DEFAULT NULL AFTER `city`, ADD `zip` VARCHAR(40) NULL DEFAULT NULL AFTER `state`;
ALTER TABLE `users` ADD `profile_complete` TINYINT(1) NOT NULL DEFAULT '0' AFTER `sv`;
ALTER TABLE `users` ADD `ban_reason` VARCHAR(255) NULL DEFAULT NULL AFTER `tsc`;
ALTER TABLE `users` ADD `provider` VARCHAR(255) NULL DEFAULT NULL AFTER `remember_token`, ADD `provider_id` VARCHAR(255) NULL DEFAULT NULL AFTER `provider`;

ALTER TABLE `user_logins` CHANGE `user_id` `user_id` INT UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `wishlists` CHANGE `user_id` `user_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wishlists` CHANGE `product_id` `product_id` INT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `withdrawals` CHANGE `method_id` `method_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `withdrawals` CHANGE `seller_id` `seller_id` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `withdrawals` CHANGE `currency` `currency` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `withdrawals` CHANGE `trx` `trx` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `withdraw_methods` ADD `form_id` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `withdraw_methods` DROP `delay`;
ALTER TABLE `withdraw_methods` DROP `user_data`;

ALTER TABLE `frontends` CHANGE `data_keys` `data_keys` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `frontends` CHANGE `data_values` `data_values` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2025 at 12:49 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecoma`
--

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `frontends` ADD `tempname` VARCHAR(40) NULL DEFAULT NULL AFTER `data_values`, ADD `slug` VARCHAR(255) NULL DEFAULT NULL AFTER `tempname`, ADD `seo_content` LONGTEXT NULL DEFAULT NULL AFTER `slug`;

UPDATE frontends
SET tempname = (
    SELECT active_template
    FROM general_settings
    LIMIT 1
);


DROP TABLE `gateways`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2025 at 12:59 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecoma`
--

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `code` int DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `gateway_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supported_currencies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `crypto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `image`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, 101, 'Paypal', 'Paypal', '663a38d7b455d1715091671.png', 1, '{\"paypal_email\":{\"title\":\"PayPal Email\",\"global\":true,\"value\":\"sb-owud61543012@business.example.com\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:21:11'),
(2, 0, 102, 'Perfect Money', 'PerfectMoney', '663a3920e30a31715091744.png', 1, '{\"passphrase\":{\"title\":\"ALTERNATE PASSPHRASE\",\"global\":true,\"value\":\"hR26aw02Q1eEeUPSIfuwNypXX\"},\"wallet_id\":{\"title\":\"PM Wallet\",\"global\":false,\"value\":\"\"}}', '{\"USD\":\"$\",\"EUR\":\"\\u20ac\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:22:24'),
(3, 0, 103, 'Stripe Hosted', 'Stripe', '663a39861cb9d1715091846.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:24:06'),
(4, 0, 104, 'Skrill', 'Skrill', '663a39494c4a91715091785.png', 1, '{\"pay_to_email\":{\"title\":\"Skrill Email\",\"global\":true,\"value\":\"merchant@skrill.com\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"---\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"MAD\":\"MAD\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PLN\":\"PLN\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"SAR\":\"SAR\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TND\":\"TND\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\",\"COP\":\"COP\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:23:05'),
(5, 0, 105, 'PayTM', 'Paytm', '663a390f601191715091727.png', 1, '{\"MID\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"DIY12386817555501617\"},\"merchant_key\":{\"title\":\"Merchant Key\",\"global\":true,\"value\":\"bKMfNxPPf_QdZppa\"},\"WEBSITE\":{\"title\":\"Paytm Website\",\"global\":true,\"value\":\"DIYtestingweb\"},\"INDUSTRY_TYPE_ID\":{\"title\":\"Industry Type\",\"global\":true,\"value\":\"Retail\"},\"CHANNEL_ID\":{\"title\":\"CHANNEL ID\",\"global\":true,\"value\":\"WEB\"},\"transaction_url\":{\"title\":\"Transaction URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/oltp-web\\/processTransaction\"},\"transaction_status_url\":{\"title\":\"Transaction STATUS URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/paytmchecksum\\/paytmCallback.jsp\"}}', '{\"AUD\":\"AUD\",\"ARS\":\"ARS\",\"BDT\":\"BDT\",\"BRL\":\"BRL\",\"BGN\":\"BGN\",\"CAD\":\"CAD\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"HRK\":\"HRK\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"GEL\":\"GEL\",\"GHS\":\"GHS\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"MAD\":\"MAD\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"NGN\":\"NGN\",\"NOK\":\"NOK\",\"PKR\":\"PKR\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"ZAR\":\"ZAR\",\"KRW\":\"KRW\",\"LKR\":\"LKR\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"UGX\":\"UGX\",\"UAH\":\"UAH\",\"AED\":\"AED\",\"GBP\":\"GBP\",\"USD\":\"USD\",\"VND\":\"VND\",\"XOF\":\"XOF\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:22:07'),
(6, 0, 106, 'Payeer', 'Payeer', '663a38c9e2e931715091657.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"866989763\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"7575\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}', 0, '{\"status\":{\"title\": \"Status URL\",\"value\":\"ipn.Payeer\"}}', NULL, '2019-09-14 13:14:22', '2024-05-07 08:20:57'),
(7, 0, 107, 'PayStack', 'Paystack', '663a38fc814e91715091708.png', 1, '{\"public_key\":{\"title\":\"Public key\",\"global\":true,\"value\":\"pk_test_cd330608eb47970889bca397ced55c1dd5ad3783\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"sk_test_8a0b1f199362d7acc9c390bff72c4e81f74e2ac3\"}}', '{\"USD\":\"USD\",\"NGN\":\"NGN\"}', 0, '{\"callback\":{\"title\": \"Callback URL\",\"value\":\"ipn.Paystack\"},\"webhook\":{\"title\": \"Webhook URL\",\"value\":\"ipn.Paystack\"}}\r\n', NULL, '2019-09-14 13:14:22', '2024-05-07 08:21:48'),
(9, 0, 109, 'Flutterwave', 'Flutterwave', '663a36c2c34d61715091138.png', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------------\"},\"encryption_key\":{\"title\":\"Encryption Key\",\"global\":true,\"value\":\"------------------\"}}', '{\"BIF\":\"BIF\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CVE\":\"CVE\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"GHS\":\"GHS\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"KES\":\"KES\",\"LRD\":\"LRD\",\"MWK\":\"MWK\",\"MZN\":\"MZN\",\"NGN\":\"NGN\",\"RWF\":\"RWF\",\"SLL\":\"SLL\",\"STD\":\"STD\",\"TZS\":\"TZS\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"XAF\":\"XAF\",\"XOF\":\"XOF\",\"ZMK\":\"ZMK\",\"ZMW\":\"ZMW\",\"ZWD\":\"ZWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:12:18'),
(10, 0, 110, 'RazorPay', 'Razorpay', '663a393a527831715091770.png', 1, '{\"key_id\":{\"title\":\"Key Id\",\"global\":true,\"value\":\"rzp_test_kiOtejPbRZU90E\"},\"key_secret\":{\"title\":\"Key Secret \",\"global\":true,\"value\":\"osRDebzEqbsE1kbyQJ4y0re7\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:22:50'),
(11, 0, 111, 'Stripe Storefront', 'StripeJs', '663a3995417171715091861.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:24:21'),
(12, 0, 112, 'Instamojo', 'Instamojo', '663a384d54a111715091533.png', 1, '{\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_2241633c3bc44a3de84a3b33969\"},\"auth_token\":{\"title\":\"Auth Token\",\"global\":true,\"value\":\"test_279f083f7bebefd35217feef22d\"},\"salt\":{\"title\":\"Salt\",\"global\":true,\"value\":\"19d38908eeff4f58b2ddda2c6d86ca25\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:18:53'),
(13, 0, 501, 'Blockchain', 'Blockchain', '663a35efd0c311715090927.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"55529946-05ca-48ff-8710-f279d86b1cc5\"},\"xpub_code\":{\"title\":\"XPUB CODE\",\"global\":true,\"value\":\"xpub6CKQ3xxWyBoFAF83izZCSFUorptEU9AF8TezhtWeMU5oefjX3sFSBw62Lr9iHXPkXmDQJJiHZeTRtD9Vzt8grAYRhvbz4nEvBu3QKELVzFK\"}}', '{\"BTC\":\"BTC\"}', 1, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:08:47'),
(15, 0, 503, 'CoinPayments', 'Coinpayments', '663a36a8d8e1d1715091112.png', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"---------------------\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"---------------------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"---------------------\"}}', '{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}', 1, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:11:52'),
(16, 0, 504, 'CoinPayments Fiat', 'CoinpaymentsFiat', '663a36b7b841a1715091127.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"6515561\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:12:07'),
(17, 0, 505, 'Coingate', 'Coingate', '663a368e753381715091086.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"6354mwVCEw5kHzRJ6thbGo-N\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:11:26'),
(18, 0, 506, 'Coinbase Commerce', 'CoinbaseCommerce', '663a367e46ae51715091070.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"c47cd7df-d8e8-424b-a20a\"},\"secret\":{\"title\":\"Webhook Shared Secret\",\"global\":true,\"value\":\"55871878-2c32-4f64-ab66\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"JPY\":\"JPY\",\"GBP\":\"GBP\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CNY\":\"CNY\",\"SEK\":\"SEK\",\"NZD\":\"NZD\",\"MXN\":\"MXN\",\"SGD\":\"SGD\",\"HKD\":\"HKD\",\"NOK\":\"NOK\",\"KRW\":\"KRW\",\"TRY\":\"TRY\",\"RUB\":\"RUB\",\"INR\":\"INR\",\"BRL\":\"BRL\",\"ZAR\":\"ZAR\",\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CDF\":\"CDF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NPR\":\"NPR\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}\r\n\r\n', 0, '{\"endpoint\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.CoinbaseCommerce\"}}', NULL, '2019-09-14 13:14:22', '2024-05-07 08:11:10'),
(24, 0, 113, 'Paypal Express', 'PaypalSdk', '663a38ed101a61715091693.png', 1, '{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"Ae0-tixtSV7DvLwIh3Bmu7JvHrjh5EfGdXr_cEklKAVjjezRZ747BxKILiBdzlKKyp-W8W_T7CKH1Ken\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"EOhbvHZgFNO21soQJT1L9Q00M3rK6PIEsdiTgXRBt2gtGtxwRer5JvKnVUGNU5oE63fFnjnYY7hq3HBA\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:21:33'),
(25, 0, 114, 'Stripe Checkout', 'StripeV3', '663a39afb519f1715091887.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"whsec_lUmit1gtxwKTveLnSe88xCSDdnPOt8g5\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, '{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}', NULL, '2019-09-14 13:14:22', '2024-05-07 08:24:47'),
(27, 0, 115, 'Mollie', 'Mollie', '663a387ec69371715091582.png', 1, '{\"mollie_email\":{\"title\":\"Mollie Email \",\"global\":true,\"value\":\"vi@gmail.com\"},\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_cucfwKTWfft9s337qsVfn5CC4vNkrn\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:19:42'),
(30, 0, 116, 'Cashmaal', 'Cashmaal', '663a361b16bd11715090971.png', 1, '{\"web_id\":{\"title\":\"Web Id\",\"global\":true,\"value\":\"3748\"},\"ipn_key\":{\"title\":\"IPN Key\",\"global\":true,\"value\":\"546254628759524554647987\"}}', '{\"PKR\":\"PKR\",\"USD\":\"USD\"}', 0, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.Cashmaal\"}}', NULL, NULL, '2024-05-07 08:09:31'),
(36, 0, 119, 'Mercado Pago', 'MercadoPago', '663a386c714a91715091564.png', 1, '{\"access_token\":{\"title\":\"Access Token\",\"global\":true,\"value\":\"APP_USR-7924565816849832-082312-21941521997fab717db925cf1ea2c190-1071840315\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-05-07 08:19:24'),
(37, 0, 120, 'Authorize.net', 'Authorize', '663a35b9ca5991715090873.png', 1, '{\"login_id\":{\"title\":\"Login ID\",\"global\":true,\"value\":\"59e4P9DBcZv\"},\"transaction_key\":{\"title\":\"Transaction Key\",\"global\":true,\"value\":\"47x47TJyLw2E7DbR\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-05-07 08:07:53'),
(46, 0, 121, 'NMI', 'NMI', '663a3897754cf1715091607.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"2F822Rw39fx762MaV7Yy86jXGTC7sCDy\"}}', '{\"AED\":\"AED\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"RUB\":\"RUB\",\"SEC\":\"SEC\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, NULL, '2024-05-07 08:20:07'),
(50, 0, 507, 'BTCPay', 'BTCPay', '663a35cd25a8d1715090893.png', 1, '{\"store_id\":{\"title\":\"Store Id\",\"global\":true,\"value\":\"HsqFVTXSeUFJu7caoYZc3CTnP8g5LErVdHhEXPVTheHf\"},\"api_key\":{\"title\":\"Api Key\",\"global\":true,\"value\":\"4436bd706f99efae69305e7c4eff4780de1335ce\"},\"server_name\":{\"title\":\"Server Name\",\"global\":true,\"value\":\"https:\\/\\/testnet.demo.btcpayserver.org\"},\"secret_code\":{\"title\":\"Secret Code\",\"global\":true,\"value\":\"SUCdqPn9CDkY7RmJHfpQVHP2Lf2\"}}', '{\"BTC\":\"Bitcoin\",\"LTC\":\"Litecoin\"}', 1, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.BTCPay\"}}', NULL, NULL, '2024-05-07 08:08:13'),
(51, 0, 508, 'Now payments hosted', 'NowPaymentsHosted', '663a38b8d57a81715091640.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"--------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------------\"}}', '{\"BTG\":\"BTG\",\"ETH\":\"ETH\",\"XMR\":\"XMR\",\"ZEC\":\"ZEC\",\"XVG\":\"XVG\",\"ADA\":\"ADA\",\"LTC\":\"LTC\",\"BCH\":\"BCH\",\"QTUM\":\"QTUM\",\"DASH\":\"DASH\",\"XLM\":\"XLM\",\"XRP\":\"XRP\",\"XEM\":\"XEM\",\"DGB\":\"DGB\",\"LSK\":\"LSK\",\"DOGE\":\"DOGE\",\"TRX\":\"TRX\",\"KMD\":\"KMD\",\"REP\":\"REP\",\"BAT\":\"BAT\",\"ARK\":\"ARK\",\"WAVES\":\"WAVES\",\"BNB\":\"BNB\",\"XZC\":\"XZC\",\"NANO\":\"NANO\",\"TUSD\":\"TUSD\",\"VET\":\"VET\",\"ZEN\":\"ZEN\",\"GRS\":\"GRS\",\"FUN\":\"FUN\",\"NEO\":\"NEO\",\"GAS\":\"GAS\",\"PAX\":\"PAX\",\"USDC\":\"USDC\",\"ONT\":\"ONT\",\"XTZ\":\"XTZ\",\"LINK\":\"LINK\",\"RVN\":\"RVN\",\"BNBMAINNET\":\"BNBMAINNET\",\"ZIL\":\"ZIL\",\"BCD\":\"BCD\",\"USDT\":\"USDT\",\"USDTERC20\":\"USDTERC20\",\"CRO\":\"CRO\",\"DAI\":\"DAI\",\"HT\":\"HT\",\"WABI\":\"WABI\",\"BUSD\":\"BUSD\",\"ALGO\":\"ALGO\",\"USDTTRC20\":\"USDTTRC20\",\"GT\":\"GT\",\"STPT\":\"STPT\",\"AVA\":\"AVA\",\"SXP\":\"SXP\",\"UNI\":\"UNI\",\"OKB\":\"OKB\",\"BTC\":\"BTC\"}', 1, '', NULL, NULL, '2024-05-07 08:20:40'),
(52, 0, 509, 'Now payments checkout', 'NowPaymentsCheckout', '663a38a59d2541715091621.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"---------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 1, '', NULL, NULL, '2024-05-07 08:20:21'),
(53, 0, 122, '2Checkout', 'TwoCheckout', '663a39b8e64b91715091896.png', 1, '{\"merchant_code\":{\"title\":\"Merchant Code\",\"global\":true,\"value\":\"253248016872\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"eQM)ID@&vG84u!O*g[p+\"}}', '{\"AFN\": \"AFN\",\"ALL\": \"ALL\",\"DZD\": \"DZD\",\"ARS\": \"ARS\",\"AUD\": \"AUD\",\"AZN\": \"AZN\",\"BSD\": \"BSD\",\"BDT\": \"BDT\",\"BBD\": \"BBD\",\"BZD\": \"BZD\",\"BMD\": \"BMD\",\"BOB\": \"BOB\",\"BWP\": \"BWP\",\"BRL\": \"BRL\",\"GBP\": \"GBP\",\"BND\": \"BND\",\"BGN\": \"BGN\",\"CAD\": \"CAD\",\"CLP\": \"CLP\",\"CNY\": \"CNY\",\"COP\": \"COP\",\"CRC\": \"CRC\",\"HRK\": \"HRK\",\"CZK\": \"CZK\",\"DKK\": \"DKK\",\"DOP\": \"DOP\",\"XCD\": \"XCD\",\"EGP\": \"EGP\",\"EUR\": \"EUR\",\"FJD\": \"FJD\",\"GTQ\": \"GTQ\",\"HKD\": \"HKD\",\"HNL\": \"HNL\",\"HUF\": \"HUF\",\"INR\": \"INR\",\"IDR\": \"IDR\",\"ILS\": \"ILS\",\"JMD\": \"JMD\",\"JPY\": \"JPY\",\"KZT\": \"KZT\",\"KES\": \"KES\",\"LAK\": \"LAK\",\"MMK\": \"MMK\",\"LBP\": \"LBP\",\"LRD\": \"LRD\",\"MOP\": \"MOP\",\"MYR\": \"MYR\",\"MVR\": \"MVR\",\"MRO\": \"MRO\",\"MUR\": \"MUR\",\"MXN\": \"MXN\",\"MAD\": \"MAD\",\"NPR\": \"NPR\",\"TWD\": \"TWD\",\"NZD\": \"NZD\",\"NIO\": \"NIO\",\"NOK\": \"NOK\",\"PKR\": \"PKR\",\"PGK\": \"PGK\",\"PEN\": \"PEN\",\"PHP\": \"PHP\",\"PLN\": \"PLN\",\"QAR\": \"QAR\",\"RON\": \"RON\",\"RUB\": \"RUB\",\"WST\": \"WST\",\"SAR\": \"SAR\",\"SCR\": \"SCR\",\"SGD\": \"SGD\",\"SBD\": \"SBD\",\"ZAR\": \"ZAR\",\"KRW\": \"KRW\",\"LKR\": \"LKR\",\"SEK\": \"SEK\",\"CHF\": \"CHF\",\"SYP\": \"SYP\",\"THB\": \"THB\",\"TOP\": \"TOP\",\"TTD\": \"TTD\",\"TRY\": \"TRY\",\"UAH\": \"UAH\",\"AED\": \"AED\",\"USD\": \"USD\",\"VUV\": \"VUV\",\"VND\": \"VND\",\"XOF\": \"XOF\",\"YER\": \"YER\"}', 0, '{\"approved_url\":{\"title\": \"Approved URL\",\"value\":\"ipn.TwoCheckout\"}}', NULL, NULL, '2024-05-07 08:24:56'),
(54, 0, 123, 'Checkout', 'Checkout', '663a3628733351715090984.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------\"},\"public_key\":{\"title\":\"PUBLIC KEY\",\"global\":true,\"value\":\"------\"},\"processing_channel_id\":{\"title\":\"PROCESSING CHANNEL\",\"global\":true,\"value\":\"------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"AUD\":\"AUD\",\"CAN\":\"CAN\",\"CHF\":\"CHF\",\"SGD\":\"SGD\",\"JPY\":\"JPY\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-05-07 08:09:44'),
(55, 1, 1000, 'Bank Transfer', 'bank_transfer', '663b50fc3b21c1715163388.png', 1, '[]', '[]', 0, NULL, '<div style=\"border-left: 3px solid #b5b0b0;\r\n    padding: 12px;\r\n    font-style: italic;\r\n    margin: 30px 0px;\r\n    background: #f9f9f9;\r\n    border-radius: 3px;\"><p style=\"\r\n    margin-bottom: 10px;\r\n    font-weight: bold;\r\n    font-size: 17px;\r\n\">Send the amount to the below bank information</p><p style=\"\r\n    margin-bottom: 0;\r\n\">\r\n</p><p style=\"\r\nmargin-bottom: 0;\r\n\"><span style=\"font-weight:500\">Bank Name:</span> Bank of America</p>\r\n<p style=\"\r\nmargin-bottom: 0;\r\n\"><span style=\"font-weight:500\">Branch:</span> New York</p>\r\n<p style=\"\r\nmargin-bottom: 0;\r\n\"><span style=\"font-weight:500\">Routing:</span> 1234</p>\r\n<p style=\"\r\n    margin-bottom: 0;\r\n\"><span style=\"font-weight:500\">Account Number:</span> 997855xxxxxxxx6985</p></div>', '2024-03-13 23:11:21', '2024-12-31 23:47:05'),
(56, 0, 510, 'Binance', 'Binance', '663a35db4fd621715090907.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"tsu3tjiq0oqfbtmlbevoeraxhfbp3brejnm9txhjxcp4to29ujvakvfl1ibsn3ja\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"jzngq4t04ltw8d4iqpi7admfl8tvnpehxnmi34id1zvfaenbwwvsvw7llw3zdko8\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"231129033\"}}', '{\"BTC\":\"Bitcoin\",\"USD\":\"USD\",\"BNB\":\"BNB\"}', 1, '{\"cron\":{\"title\": \"Cron Job URL\",\"value\":\"ipn.Binance\"}}', NULL, NULL, '2024-05-07 08:08:27'),
(57, 0, 124, 'SslCommerz', 'SslCommerz', '663a397a70c571715091834.png', 1, '{\"store_id\":{\"title\":\"Store ID\",\"global\":true,\"value\":\"---------\"},\"store_password\":{\"title\":\"Store Password\",\"global\":true,\"value\":\"----------\"}}', '{\"BDT\":\"BDT\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"SGD\":\"SGD\",\"INR\":\"INR\",\"MYR\":\"MYR\"}', 0, NULL, NULL, NULL, '2024-05-07 08:23:54'),
(58, 0, 125, 'Aamarpay', 'Aamarpay', '663a34d5d1dfc1715090645.png', 1, '{\"store_id\":{\"title\":\"Store ID\",\"global\":true,\"value\":\"---------\"},\"signature_key\":{\"title\":\"Signature Key\",\"global\":true,\"value\":\"----------\"}}', '{\"BDT\":\"BDT\"}', 0, NULL, NULL, NULL, '2024-05-07 08:04:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `gateway_currencies` DROP `image`;
ALTER TABLE `general_settings` CHANGE `sitename` `site_name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `general_settings` ADD `email_from_name` VARCHAR(255) NULL DEFAULT NULL AFTER `email_from`;
ALTER TABLE `general_settings` ADD `sms_template` TEXT NULL DEFAULT NULL AFTER `email_template`, ADD `sms_from` VARCHAR(255) NULL DEFAULT NULL AFTER `sms_template`, ADD `push_title` VARCHAR(255) NULL DEFAULT NULL AFTER `sms_from`, ADD `push_template` VARCHAR(255) NULL DEFAULT NULL AFTER `push_title`;
ALTER TABLE `general_settings` DROP `sms_api`;
ALTER TABLE `general_settings` ADD `firebase_config` TEXT NULL DEFAULT NULL AFTER `sms_config`;
ALTER TABLE `general_settings` ADD `global_shortcodes` TEXT NULL DEFAULT NULL AFTER `firebase_config`;
ALTER TABLE `general_settings` ADD `pn` TINYINT(1) NOT NULL DEFAULT '0' AFTER `sn`;
ALTER TABLE `general_settings` ADD `maintenance_mode` TINYINT NOT NULL DEFAULT '0' AFTER `force_ssl`;
ALTER TABLE `general_settings` ADD `multi_language` TINYINT(1) NOT NULL DEFAULT '1' AFTER `agree`;
ALTER TABLE `general_settings` ADD `socialite_credentials` TEXT NULL DEFAULT NULL AFTER `active_template`,  ADD `system_customized` TINYINT(1) NOT NULL DEFAULT '0' AFTER `socialite_credentials`, ADD `paginate_number` INT NOT NULL DEFAULT '0' AFTER `system_customized`;
ALTER TABLE `general_settings` ADD `currency_format` TINYINT NOT NULL DEFAULT '1' COMMENT '1=>Both 2=>Text Only 3=>Symbol Only' AFTER `paginate_number`;

ALTER TABLE `general_settings` CHANGE `sys_version` `available_version` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
    `general_settings` CHANGE `id` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
    CHANGE `cur_text` `cur_text` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'currency text' AFTER `site_name`,
    CHANGE `cur_sym` `cur_sym` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'currency symbol' AFTER `cur_text`,
    CHANGE `email_from` `email_from` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `cur_sym`,
    CHANGE `email_from_name` `email_from_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `email_from`,
    CHANGE `email_template` `email_template` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `email_from_name`,
    CHANGE `sms_template` `sms_template` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `email_template`,
    CHANGE `sms_from` `sms_from` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `sms_template`,
    CHANGE `push_title` `push_title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `sms_from`,
    CHANGE `push_template` `push_template` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `push_title`,
    CHANGE `base_color` `base_color` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `push_template`,
    CHANGE `mail_config` `mail_config` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'email configuration' AFTER `base_color`,
    CHANGE `sms_config` `sms_config` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `mail_config`,
    CHANGE `firebase_config` `firebase_config` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `sms_config`,
    CHANGE `global_shortcodes` `global_shortcodes` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `firebase_config`,
    CHANGE `ev` `ev` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'email verification, 0 - dont check, 1 - check' AFTER `global_shortcodes`,
    CHANGE `en` `en` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'email notification, 0 - dont send, 1 - send' AFTER `ev`,
    CHANGE `sv` `sv` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'sms verication, 0 - dont check, 1 - check' AFTER `en`,
    CHANGE `sn` `sn` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'sms notification, 0 - dont send, 1 - send' AFTER `sv`,
    CHANGE `pn` `pn` TINYINT(1) NOT NULL DEFAULT '0' AFTER `sn`,
    CHANGE `force_ssl` `force_ssl` TINYINT(1) NOT NULL DEFAULT '0' AFTER `pn`,
    CHANGE `maintenance_mode` `maintenance_mode` TINYINT NOT NULL DEFAULT '0' AFTER `force_ssl`,
    CHANGE `secure_password` `secure_password` TINYINT(1) NOT NULL DEFAULT '0' AFTER `maintenance_mode`,
    CHANGE `agree` `agree` TINYINT(1) NOT NULL DEFAULT '0' AFTER `secure_password`,
    CHANGE `multi_language` `multi_language` TINYINT(1) NOT NULL DEFAULT '1' AFTER `agree`,
    CHANGE `cod` `cod` TINYINT(1) NOT NULL DEFAULT '1' AFTER `multi_language`,
    CHANGE `registration` `registration` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0: Off , 1: On' AFTER `cod`,
    CHANGE `socialite_credentials` `socialite_credentials` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `active_template`,
    CHANGE `system_customized` `system_customized` TINYINT(1) NOT NULL DEFAULT '0' AFTER `available_version`,
    CHANGE `paginate_number` `paginate_number` INT NOT NULL DEFAULT '0' AFTER `system_customized`,
    CHANGE `currency_format` `currency_format` TINYINT NOT NULL DEFAULT '1' COMMENT '1=>Both 2=>Text Only 3=>Symbol Only' AFTER `paginate_number`,
    CHANGE `product_commission` `product_commission` DECIMAL(5, 2) NOT NULL DEFAULT '0.00' AFTER `currency_format`,
    CHANGE `seller_withdraw_limit` `seller_withdraw_limit` DECIMAL(18, 8) NOT NULL DEFAULT '0.00000000' AFTER `product_commission`;

UPDATE `extensions` SET `support` = 'fb_com.png' WHERE `extensions`.`act` = 'fb-comment';

ALTER TABLE `general_settings` ADD `country_list` LONGTEXT NULL DEFAULT NULL AFTER `socialite_credentials`;

UPDATE `general_settings` SET `country_list` = '{\n  \"AF\": {\n    \"country\": \"Afghanistan\",\n    \"dial_code\": \"93\"\n  },\n  \"AX\": {\n    \"country\": \"Aland Islands\",\n    \"dial_code\": \"358\"\n  },\n  \"AL\": {\n    \"country\": \"Albania\",\n    \"dial_code\": \"355\"\n  },\n  \"DZ\": {\n    \"country\": \"Algeria\",\n    \"dial_code\": \"213\"\n  },\n  \"AS\": {\n    \"country\": \"AmericanSamoa\",\n    \"dial_code\": \"1684\"\n  },\n  \"AD\": {\n    \"country\": \"Andorra\",\n    \"dial_code\": \"376\"\n  },\n  \"AO\": {\n    \"country\": \"Angola\",\n    \"dial_code\": \"244\"\n  },\n  \"AI\": {\n    \"country\": \"Anguilla\",\n    \"dial_code\": \"1264\"\n  },\n  \"AQ\": {\n    \"country\": \"Antarctica\",\n    \"dial_code\": \"672\"\n  },\n  \"AG\": {\n    \"country\": \"Antigua and Barbuda\",\n    \"dial_code\": \"1268\"\n  },\n  \"AR\": {\n    \"country\": \"Argentina\",\n    \"dial_code\": \"54\"\n  },\n  \"AM\": {\n    \"country\": \"Armenia\",\n    \"dial_code\": \"374\"\n  },\n  \"AW\": {\n    \"country\": \"Aruba\",\n    \"dial_code\": \"297\"\n  },\n  \"AU\": {\n    \"country\": \"Australia\",\n    \"dial_code\": \"61\"\n  },\n  \"AT\": {\n    \"country\": \"Austria\",\n    \"dial_code\": \"43\"\n  },\n  \"AZ\": {\n    \"country\": \"Azerbaijan\",\n    \"dial_code\": \"994\"\n  },\n  \"BS\": {\n    \"country\": \"Bahamas\",\n    \"dial_code\": \"1242\"\n  },\n  \"BH\": {\n    \"country\": \"Bahrain\",\n    \"dial_code\": \"973\"\n  },\n  \"BD\": {\n    \"country\": \"Bangladesh\",\n    \"dial_code\": \"880\"\n  },\n  \"BB\": {\n    \"country\": \"Barbados\",\n    \"dial_code\": \"1246\"\n  },\n  \"BY\": {\n    \"country\": \"Belarus\",\n    \"dial_code\": \"375\"\n  },\n  \"BE\": {\n    \"country\": \"Belgium\",\n    \"dial_code\": \"32\"\n  },\n  \"BZ\": {\n    \"country\": \"Belize\",\n    \"dial_code\": \"501\"\n  },\n  \"BJ\": {\n    \"country\": \"Benin\",\n    \"dial_code\": \"229\"\n  },\n  \"BM\": {\n    \"country\": \"Bermuda\",\n    \"dial_code\": \"1441\"\n  },\n  \"BT\": {\n    \"country\": \"Bhutan\",\n    \"dial_code\": \"975\"\n  },\n  \"BO\": {\n    \"country\": \"Plurinational State of Bolivia\",\n    \"dial_code\": \"591\"\n  },\n  \"BA\": {\n    \"country\": \"Bosnia and Herzegovina\",\n    \"dial_code\": \"387\"\n  },\n  \"BW\": {\n    \"country\": \"Botswana\",\n    \"dial_code\": \"267\"\n  },\n  \"BR\": {\n    \"country\": \"Brazil\",\n    \"dial_code\": \"55\"\n  },\n  \"IO\": {\n    \"country\": \"British Indian Ocean Territory\",\n    \"dial_code\": \"246\"\n  },\n  \"BN\": {\n    \"country\": \"Brunei Darussalam\",\n    \"dial_code\": \"673\"\n  },\n  \"BG\": {\n    \"country\": \"Bulgaria\",\n    \"dial_code\": \"359\"\n  },\n  \"BF\": {\n    \"country\": \"Burkina Faso\",\n    \"dial_code\": \"226\"\n  },\n  \"BI\": {\n    \"country\": \"Burundi\",\n    \"dial_code\": \"257\"\n  },\n  \"KH\": {\n    \"country\": \"Cambodia\",\n    \"dial_code\": \"855\"\n  },\n  \"CM\": {\n    \"country\": \"Cameroon\",\n    \"dial_code\": \"237\"\n  },\n  \"CA\": {\n    \"country\": \"Canada\",\n    \"dial_code\": \"1\"\n  },\n  \"CV\": {\n    \"country\": \"Cape Verde\",\n    \"dial_code\": \"238\"\n  },\n  \"KY\": {\n    \"country\": \"Cayman Islands\",\n    \"dial_code\": \" 345\"\n  },\n  \"CF\": {\n    \"country\": \"Central African Republic\",\n    \"dial_code\": \"236\"\n  },\n  \"TD\": {\n    \"country\": \"Chad\",\n    \"dial_code\": \"235\"\n  },\n  \"CL\": {\n    \"country\": \"Chile\",\n    \"dial_code\": \"56\"\n  },\n  \"CN\": {\n    \"country\": \"China\",\n    \"dial_code\": \"86\"\n  },\n  \"CX\": {\n    \"country\": \"Christmas Island\",\n    \"dial_code\": \"61\"\n  },\n  \"CC\": {\n    \"country\": \"Cocos (Keeling) Islands\",\n    \"dial_code\": \"61\"\n  },\n  \"CO\": {\n    \"country\": \"Colombia\",\n    \"dial_code\": \"57\"\n  },\n  \"KM\": {\n    \"country\": \"Comoros\",\n    \"dial_code\": \"269\"\n  },\n  \"CG\": {\n    \"country\": \"Congo\",\n    \"dial_code\": \"242\"\n  },\n  \"CD\": {\n    \"country\": \"The Democratic Republic of the Congo\",\n    \"dial_code\": \"243\"\n  },\n  \"CK\": {\n    \"country\": \"Cook Islands\",\n    \"dial_code\": \"682\"\n  },\n  \"CR\": {\n    \"country\": \"Costa Rica\",\n    \"dial_code\": \"506\"\n  },\n  \"CI\": {\n    \"country\": \"Cote d\'Ivoire\",\n    \"dial_code\": \"225\"\n  },\n  \"HR\": {\n    \"country\": \"Croatia\",\n    \"dial_code\": \"385\"\n  },\n  \"CU\": {\n    \"country\": \"Cuba\",\n    \"dial_code\": \"53\"\n  },\n  \"CY\": {\n    \"country\": \"Cyprus\",\n    \"dial_code\": \"357\"\n  },\n  \"CZ\": {\n    \"country\": \"Czech Republic\",\n    \"dial_code\": \"420\"\n  },\n  \"DK\": {\n    \"country\": \"Denmark\",\n    \"dial_code\": \"45\"\n  },\n  \"DJ\": {\n    \"country\": \"Djibouti\",\n    \"dial_code\": \"253\"\n  },\n  \"DM\": {\n    \"country\": \"Dominica\",\n    \"dial_code\": \"1767\"\n  },\n  \"DO\": {\n    \"country\": \"Dominican Republic\",\n    \"dial_code\": \"1849\"\n  },\n  \"EC\": {\n    \"country\": \"Ecuador\",\n    \"dial_code\": \"593\"\n  },\n  \"EG\": {\n    \"country\": \"Egypt\",\n    \"dial_code\": \"20\"\n  },\n  \"SV\": {\n    \"country\": \"El Salvador\",\n    \"dial_code\": \"503\"\n  },\n  \"GQ\": {\n    \"country\": \"Equatorial Guinea\",\n    \"dial_code\": \"240\"\n  },\n  \"ER\": {\n    \"country\": \"Eritrea\",\n    \"dial_code\": \"291\"\n  },\n  \"EE\": {\n    \"country\": \"Estonia\",\n    \"dial_code\": \"372\"\n  },\n  \"ET\": {\n    \"country\": \"Ethiopia\",\n    \"dial_code\": \"251\"\n  },\n  \"FK\": {\n    \"country\": \"Falkland Islands (Malvinas)\",\n    \"dial_code\": \"500\"\n  },\n  \"FO\": {\n    \"country\": \"Faroe Islands\",\n    \"dial_code\": \"298\"\n  },\n  \"FJ\": {\n    \"country\": \"Fiji\",\n    \"dial_code\": \"679\"\n  },\n  \"FI\": {\n    \"country\": \"Finland\",\n    \"dial_code\": \"358\"\n  },\n  \"FR\": {\n    \"country\": \"France\",\n    \"dial_code\": \"33\"\n  },\n  \"GF\": {\n    \"country\": \"French Guiana\",\n    \"dial_code\": \"594\"\n  },\n  \"PF\": {\n    \"country\": \"French Polynesia\",\n    \"dial_code\": \"689\"\n  },\n  \"GA\": {\n    \"country\": \"Gabon\",\n    \"dial_code\": \"241\"\n  },\n  \"GM\": {\n    \"country\": \"Gambia\",\n    \"dial_code\": \"220\"\n  },\n  \"GE\": {\n    \"country\": \"Georgia\",\n    \"dial_code\": \"995\"\n  },\n  \"DE\": {\n    \"country\": \"Germany\",\n    \"dial_code\": \"49\"\n  },\n  \"GH\": {\n    \"country\": \"Ghana\",\n    \"dial_code\": \"233\"\n  },\n  \"GI\": {\n    \"country\": \"Gibraltar\",\n    \"dial_code\": \"350\"\n  },\n  \"GR\": {\n    \"country\": \"Greece\",\n    \"dial_code\": \"30\"\n  },\n  \"GL\": {\n    \"country\": \"Greenland\",\n    \"dial_code\": \"299\"\n  },\n  \"GD\": {\n    \"country\": \"Grenada\",\n    \"dial_code\": \"1473\"\n  },\n  \"GP\": {\n    \"country\": \"Guadeloupe\",\n    \"dial_code\": \"590\"\n  },\n  \"GU\": {\n    \"country\": \"Guam\",\n    \"dial_code\": \"1671\"\n  },\n  \"GT\": {\n    \"country\": \"Guatemala\",\n    \"dial_code\": \"502\"\n  },\n  \"GG\": {\n    \"country\": \"Guernsey\",\n    \"dial_code\": \"44\"\n  },\n  \"GN\": {\n    \"country\": \"Guinea\",\n    \"dial_code\": \"224\"\n  },\n  \"GW\": {\n    \"country\": \"Guinea-Bissau\",\n    \"dial_code\": \"245\"\n  },\n  \"GY\": {\n    \"country\": \"Guyana\",\n    \"dial_code\": \"595\"\n  },\n  \"HT\": {\n    \"country\": \"Haiti\",\n    \"dial_code\": \"509\"\n  },\n  \"VA\": {\n    \"country\": \"Holy See (Vatican City State)\",\n    \"dial_code\": \"379\"\n  },\n  \"HN\": {\n    \"country\": \"Honduras\",\n    \"dial_code\": \"504\"\n  },\n  \"HK\": {\n    \"country\": \"Hong Kong\",\n    \"dial_code\": \"852\"\n  },\n  \"HU\": {\n    \"country\": \"Hungary\",\n    \"dial_code\": \"36\"\n  },\n  \"IS\": {\n    \"country\": \"Iceland\",\n    \"dial_code\": \"354\"\n  },\n  \"IN\": {\n    \"country\": \"India\",\n    \"dial_code\": \"91\"\n  },\n  \"ID\": {\n    \"country\": \"Indonesia\",\n    \"dial_code\": \"62\"\n  },\n  \"IR\": {\n    \"country\": \"Iran - Islamic Republic of Persian Gulf\",\n    \"dial_code\": \"98\"\n  },\n  \"IQ\": {\n    \"country\": \"Iraq\",\n    \"dial_code\": \"964\"\n  },\n  \"IE\": {\n    \"country\": \"Ireland\",\n    \"dial_code\": \"353\"\n  },\n  \"IM\": {\n    \"country\": \"Isle of Man\",\n    \"dial_code\": \"44\"\n  },\n  \"IL\": {\n    \"country\": \"Israel\",\n    \"dial_code\": \"972\"\n  },\n  \"IT\": {\n    \"country\": \"Italy\",\n    \"dial_code\": \"39\"\n  },\n  \"JM\": {\n    \"country\": \"Jamaica\",\n    \"dial_code\": \"1876\"\n  },\n  \"JP\": {\n    \"country\": \"Japan\",\n    \"dial_code\": \"81\"\n  },\n  \"JE\": {\n    \"country\": \"Jersey\",\n    \"dial_code\": \"44\"\n  },\n  \"JO\": {\n    \"country\": \"Jordan\",\n    \"dial_code\": \"962\"\n  },\n  \"KZ\": {\n    \"country\": \"Kazakhstan\",\n    \"dial_code\": \"77\"\n  },\n  \"KE\": {\n    \"country\": \"Kenya\",\n    \"dial_code\": \"254\"\n  },\n  \"KI\": {\n    \"country\": \"Kiribati\",\n    \"dial_code\": \"686\"\n  },\n  \"KP\": {\n    \"country\": \"Democratic People\'s Republic of Korea\",\n    \"dial_code\": \"850\"\n  },\n  \"KR\": {\n    \"country\": \"Republic of South Korea\",\n    \"dial_code\": \"82\"\n  },\n  \"KW\": {\n    \"country\": \"Kuwait\",\n    \"dial_code\": \"965\"\n  },\n  \"KG\": {\n    \"country\": \"Kyrgyzstan\",\n    \"dial_code\": \"996\"\n  },\n  \"LA\": {\n    \"country\": \"Laos\",\n    \"dial_code\": \"856\"\n  },\n  \"LV\": {\n    \"country\": \"Latvia\",\n    \"dial_code\": \"371\"\n  },\n  \"LB\": {\n    \"country\": \"Lebanon\",\n    \"dial_code\": \"961\"\n  },\n  \"LS\": {\n    \"country\": \"Lesotho\",\n    \"dial_code\": \"266\"\n  },\n  \"LR\": {\n    \"country\": \"Liberia\",\n    \"dial_code\": \"231\"\n  },\n  \"LY\": {\n    \"country\": \"Libyan Arab Jamahiriya\",\n    \"dial_code\": \"218\"\n  },\n  \"LI\": {\n    \"country\": \"Liechtenstein\",\n    \"dial_code\": \"423\"\n  },\n  \"LT\": {\n    \"country\": \"Lithuania\",\n    \"dial_code\": \"370\"\n  },\n  \"LU\": {\n    \"country\": \"Luxembourg\",\n    \"dial_code\": \"352\"\n  },\n  \"MO\": {\n    \"country\": \"Macao\",\n    \"dial_code\": \"853\"\n  },\n  \"MK\": {\n    \"country\": \"Macedonia\",\n    \"dial_code\": \"389\"\n  },\n  \"MG\": {\n    \"country\": \"Madagascar\",\n    \"dial_code\": \"261\"\n  },\n  \"MW\": {\n    \"country\": \"Malawi\",\n    \"dial_code\": \"265\"\n  },\n  \"MY\": {\n    \"country\": \"Malaysia\",\n    \"dial_code\": \"60\"\n  },\n  \"MV\": {\n    \"country\": \"Maldives\",\n    \"dial_code\": \"960\"\n  },\n  \"ML\": {\n    \"country\": \"Mali\",\n    \"dial_code\": \"223\"\n  },\n  \"MT\": {\n    \"country\": \"Malta\",\n    \"dial_code\": \"356\"\n  },\n  \"MH\": {\n    \"country\": \"Marshall Islands\",\n    \"dial_code\": \"692\"\n  },\n  \"MQ\": {\n    \"country\": \"Martinique\",\n    \"dial_code\": \"596\"\n  },\n  \"MR\": {\n    \"country\": \"Mauritania\",\n    \"dial_code\": \"222\"\n  },\n  \"MU\": {\n    \"country\": \"Mauritius\",\n    \"dial_code\": \"230\"\n  },\n  \"YT\": {\n    \"country\": \"Mayotte\",\n    \"dial_code\": \"262\"\n  },\n  \"MX\": {\n    \"country\": \"Mexico\",\n    \"dial_code\": \"52\"\n  },\n  \"FM\": {\n    \"country\": \"Federated States of Micronesia\",\n    \"dial_code\": \"691\"\n  },\n  \"MD\": {\n    \"country\": \"Moldova\",\n    \"dial_code\": \"373\"\n  },\n  \"MC\": {\n    \"country\": \"Monaco\",\n    \"dial_code\": \"377\"\n  },\n  \"MN\": {\n    \"country\": \"Mongolia\",\n    \"dial_code\": \"976\"\n  },\n  \"ME\": {\n    \"country\": \"Montenegro\",\n    \"dial_code\": \"382\"\n  },\n  \"MS\": {\n    \"country\": \"Montserrat\",\n    \"dial_code\": \"1664\"\n  },\n  \"MA\": {\n    \"country\": \"Morocco\",\n    \"dial_code\": \"212\"\n  },\n  \"MZ\": {\n    \"country\": \"Mozambique\",\n    \"dial_code\": \"258\"\n  },\n  \"MM\": {\n    \"country\": \"Myanmar\",\n    \"dial_code\": \"95\"\n  },\n  \"NA\": {\n    \"country\": \"Namibia\",\n    \"dial_code\": \"264\"\n  },\n  \"NR\": {\n    \"country\": \"Nauru\",\n    \"dial_code\": \"674\"\n  },\n  \"NP\": {\n    \"country\": \"Nepal\",\n    \"dial_code\": \"977\"\n  },\n  \"NL\": {\n    \"country\": \"Netherlands\",\n    \"dial_code\": \"31\"\n  },\n  \"AN\": {\n    \"country\": \"Netherlands Antilles\",\n    \"dial_code\": \"599\"\n  },\n  \"NC\": {\n    \"country\": \"New Caledonia\",\n    \"dial_code\": \"687\"\n  },\n  \"NZ\": {\n    \"country\": \"New Zealand\",\n    \"dial_code\": \"64\"\n  },\n  \"NI\": {\n    \"country\": \"Nicaragua\",\n    \"dial_code\": \"505\"\n  },\n  \"NE\": {\n    \"country\": \"Niger\",\n    \"dial_code\": \"227\"\n  },\n  \"NG\": {\n    \"country\": \"Nigeria\",\n    \"dial_code\": \"234\"\n  },\n  \"NU\": {\n    \"country\": \"Niue\",\n    \"dial_code\": \"683\"\n  },\n  \"NF\": {\n    \"country\": \"Norfolk Island\",\n    \"dial_code\": \"672\"\n  },\n  \"MP\": {\n    \"country\": \"Northern Mariana Islands\",\n    \"dial_code\": \"1670\"\n  },\n  \"NO\": {\n    \"country\": \"Norway\",\n    \"dial_code\": \"47\"\n  },\n  \"OM\": {\n    \"country\": \"Oman\",\n    \"dial_code\": \"968\"\n  },\n  \"PK\": {\n    \"country\": \"Pakistan\",\n    \"dial_code\": \"92\"\n  },\n  \"PW\": {\n    \"country\": \"Palau\",\n    \"dial_code\": \"680\"\n  },\n  \"PS\": {\n    \"country\": \"Palestinian Territory\",\n    \"dial_code\": \"970\"\n  },\n  \"PA\": {\n    \"country\": \"Panama\",\n    \"dial_code\": \"507\"\n  },\n  \"PG\": {\n    \"country\": \"Papua New Guinea\",\n    \"dial_code\": \"675\"\n  },\n  \"PY\": {\n    \"country\": \"Paraguay\",\n    \"dial_code\": \"595\"\n  },\n  \"PE\": {\n    \"country\": \"Peru\",\n    \"dial_code\": \"51\"\n  },\n  \"PH\": {\n    \"country\": \"Philippines\",\n    \"dial_code\": \"63\"\n  },\n  \"PN\": {\n    \"country\": \"Pitcairn\",\n    \"dial_code\": \"872\"\n  },\n  \"PL\": {\n    \"country\": \"Poland\",\n    \"dial_code\": \"48\"\n  },\n  \"PT\": {\n    \"country\": \"Portugal\",\n    \"dial_code\": \"351\"\n  },\n  \"PR\": {\n    \"country\": \"Puerto Rico\",\n    \"dial_code\": \"1939\"\n  },\n  \"QA\": {\n    \"country\": \"Qatar\",\n    \"dial_code\": \"974\"\n  },\n  \"RO\": {\n    \"country\": \"Romania\",\n    \"dial_code\": \"40\"\n  },\n  \"RU\": {\n    \"country\": \"Russia\",\n    \"dial_code\": \"7\"\n  },\n  \"RW\": {\n    \"country\": \"Rwanda\",\n    \"dial_code\": \"250\"\n  },\n  \"RE\": {\n    \"country\": \"Reunion\",\n    \"dial_code\": \"262\"\n  },\n  \"BL\": {\n    \"country\": \"Saint Barthelemy\",\n    \"dial_code\": \"590\"\n  },\n  \"SH\": {\n    \"country\": \"Saint Helena\",\n    \"dial_code\": \"290\"\n  },\n  \"KN\": {\n    \"country\": \"Saint Kitts and Nevis\",\n    \"dial_code\": \"1869\"\n  },\n  \"LC\": {\n    \"country\": \"Saint Lucia\",\n    \"dial_code\": \"1758\"\n  },\n  \"MF\": {\n    \"country\": \"Saint Martin\",\n    \"dial_code\": \"590\"\n  },\n  \"PM\": {\n    \"country\": \"Saint Pierre and Miquelon\",\n    \"dial_code\": \"508\"\n  },\n  \"VC\": {\n    \"country\": \"Saint Vincent and the Grenadines\",\n    \"dial_code\": \"1784\"\n  },\n  \"WS\": {\n    \"country\": \"Samoa\",\n    \"dial_code\": \"685\"\n  },\n  \"SM\": {\n    \"country\": \"San Marino\",\n    \"dial_code\": \"378\"\n  },\n  \"ST\": {\n    \"country\": \"Sao Tome and Principe\",\n    \"dial_code\": \"239\"\n  },\n  \"SA\": {\n    \"country\": \"Saudi Arabia\",\n    \"dial_code\": \"966\"\n  },\n  \"SN\": {\n    \"country\": \"Senegal\",\n    \"dial_code\": \"221\"\n  },\n  \"RS\": {\n    \"country\": \"Serbia\",\n    \"dial_code\": \"381\"\n  },\n  \"SC\": {\n    \"country\": \"Seychelles\",\n    \"dial_code\": \"248\"\n  },\n  \"SL\": {\n    \"country\": \"Sierra Leone\",\n    \"dial_code\": \"232\"\n  },\n  \"SG\": {\n    \"country\": \"Singapore\",\n    \"dial_code\": \"65\"\n  },\n  \"SK\": {\n    \"country\": \"Slovakia\",\n    \"dial_code\": \"421\"\n  },\n  \"SI\": {\n    \"country\": \"Slovenia\",\n    \"dial_code\": \"386\"\n  },\n  \"SB\": {\n    \"country\": \"Solomon Islands\",\n    \"dial_code\": \"677\"\n  },\n  \"SO\": {\n    \"country\": \"Somalia\",\n    \"dial_code\": \"252\"\n  },\n  \"ZA\": {\n    \"country\": \"South Africa\",\n    \"dial_code\": \"27\"\n  },\n  \"SS\": {\n    \"country\": \"South Sudan\",\n    \"dial_code\": \"211\"\n  },\n  \"GS\": {\n    \"country\": \"South Georgia and the South Sandwich Islands\",\n    \"dial_code\": \"500\"\n  },\n  \"ES\": {\n    \"country\": \"Spain\",\n    \"dial_code\": \"34\"\n  },\n  \"LK\": {\n    \"country\": \"Sri Lanka\",\n    \"dial_code\": \"94\"\n  },\n  \"SD\": {\n    \"country\": \"Sudan\",\n    \"dial_code\": \"249\"\n  },\n  \"SR\": {\n    \"country\": \"Suricountry\",\n    \"dial_code\": \"597\"\n  },\n  \"SJ\": {\n    \"country\": \"Svalbard and Jan Mayen\",\n    \"dial_code\": \"47\"\n  },\n  \"SZ\": {\n    \"country\": \"Swaziland\",\n    \"dial_code\": \"268\"\n  },\n  \"SE\": {\n    \"country\": \"Sweden\",\n    \"dial_code\": \"46\"\n  },\n  \"CH\": {\n    \"country\": \"Switzerland\",\n    \"dial_code\": \"41\"\n  },\n  \"SY\": {\n    \"country\": \"Syrian Arab Republic\",\n    \"dial_code\": \"963\"\n  },\n  \"TW\": {\n    \"country\": \"Taiwan\",\n    \"dial_code\": \"886\"\n  },\n  \"TJ\": {\n    \"country\": \"Tajikistan\",\n    \"dial_code\": \"992\"\n  },\n  \"TZ\": {\n    \"country\": \"Tanzania\",\n    \"dial_code\": \"255\"\n  },\n  \"TH\": {\n    \"country\": \"Thailand\",\n    \"dial_code\": \"66\"\n  },\n  \"TL\": {\n    \"country\": \"Timor-Leste\",\n    \"dial_code\": \"670\"\n  },\n  \"TG\": {\n    \"country\": \"Togo\",\n    \"dial_code\": \"228\"\n  },\n  \"TK\": {\n    \"country\": \"Tokelau\",\n    \"dial_code\": \"690\"\n  },\n  \"TO\": {\n    \"country\": \"Tonga\",\n    \"dial_code\": \"676\"\n  },\n  \"TT\": {\n    \"country\": \"Trinidad and Tobago\",\n    \"dial_code\": \"1868\"\n  },\n  \"TN\": {\n    \"country\": \"Tunisia\",\n    \"dial_code\": \"216\"\n  },\n  \"TR\": {\n    \"country\": \"Turkey\",\n    \"dial_code\": \"90\"\n  },\n  \"TM\": {\n    \"country\": \"Turkmenistan\",\n    \"dial_code\": \"993\"\n  },\n  \"TC\": {\n    \"country\": \"Turks and Caicos Islands\",\n    \"dial_code\": \"1649\"\n  },\n  \"TV\": {\n    \"country\": \"Tuvalu\",\n    \"dial_code\": \"688\"\n  },\n  \"UG\": {\n    \"country\": \"Uganda\",\n    \"dial_code\": \"256\"\n  },\n  \"UA\": {\n    \"country\": \"Ukraine\",\n    \"dial_code\": \"380\"\n  },\n  \"AE\": {\n    \"country\": \"United Arab Emirates\",\n    \"dial_code\": \"971\"\n  },\n  \"GB\": {\n    \"country\": \"United Kingdom\",\n    \"dial_code\": \"44\"\n  },\n  \"US\": {\n    \"country\": \"United States\",\n    \"dial_code\": \"1\"\n  },\n  \"UY\": {\n    \"country\": \"Uruguay\",\n    \"dial_code\": \"598\"\n  },\n  \"UZ\": {\n    \"country\": \"Uzbekistan\",\n    \"dial_code\": \"998\"\n  },\n  \"VU\": {\n    \"country\": \"Vanuatu\",\n    \"dial_code\": \"678\"\n  },\n  \"VE\": {\n    \"country\": \"Venezuela\",\n    \"dial_code\": \"58\"\n  },\n  \"VN\": {\n    \"country\": \"Vietnam\",\n    \"dial_code\": \"84\"\n  },\n  \"VG\": {\n    \"country\": \"British Virgin Islands\",\n    \"dial_code\": \"1284\"\n  },\n  \"VI\": {\n    \"country\": \"U.S. Virgin Islands\",\n    \"dial_code\": \"1340\"\n  },\n  \"WF\": {\n    \"country\": \"Wallis and Futuna\",\n    \"dial_code\": \"681\"\n  },\n  \"YE\": {\n    \"country\": \"Yemen\",\n    \"dial_code\": \"967\"\n  },\n  \"ZM\": {\n    \"country\": \"Zambia\",\n    \"dial_code\": \"260\"\n  },\n  \"ZW\": {\n    \"country\": \"Zimbabwe\",\n    \"dial_code\": \"263\"\n  }\n}' WHERE `general_settings`.`id` = 1;

UPDATE users SET dial_code = ( SELECT JSON_UNQUOTE(JSON_EXTRACT(country_list, CONCAT('$."', users.country_code, '".dial_code'))) FROM general_settings WHERE JSON_CONTAINS_PATH(country_list, 'one', CONCAT('$."', users.country_code, '"')) );

UPDATE sellers SET dial_code = ( SELECT JSON_UNQUOTE(JSON_EXTRACT(country_list, CONCAT('$."', sellers.country_code, '".dial_code'))) FROM general_settings WHERE JSON_CONTAINS_PATH(country_list, 'one', CONCAT('$."', sellers.country_code, '"')) );

UPDATE users SET mobile = SUBSTRING(mobile, CHAR_LENGTH(dial_code) + 1);
UPDATE sellers SET mobile = SUBSTRING(mobile, CHAR_LENGTH(dial_code) + 1);

ALTER TABLE `general_settings` DROP `country_list`;

UPDATE frontends
SET slug = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    JSON_UNQUOTE(JSON_EXTRACT(data_values, '$.title')), ' ', '-'), ',', ''), '.', ''),':',''),'?',''))
WHERE data_keys = 'policy_pages.element';

UPDATE frontends
SET slug = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    JSON_UNQUOTE(JSON_EXTRACT(data_values, '$.pageTitle')), ' ', '-'), ',', ''), '.', ''),':',''),'?',''))
WHERE data_keys = 'pages.element';


UPDATE `users` SET `country_name` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.country')), ''), 'null'),
    `state` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.state')), ''), 'null'),
    `city` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.city')), ''), 'null'),
    `zip` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.zip')), ''), 'null');
UPDATE `users` SET `address` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.address')), ''), 'null');

UPDATE `sellers` SET `country_name` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.country')), ''), 'null'),
    `state` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.state')), ''), 'null'),
    `city` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.city')), ''), 'null'),
    `zip` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.zip')), ''), 'null');
UPDATE `sellers` SET `address` = NULLIF(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(`address`, '$.address')), ''), 'null');


UPDATE `general_settings` SET `available_version` = '2.0' WHERE `general_settings`.`id` = 1;

UPDATE products SET slug = CONCAT(LOWER(REPLACE(name, ' ', '-')), '-', id);

UPDATE frontends
SET data_values = JSON_SET(
    JSON_REMOVE(data_values, '$.pageTitle'),
    '$.title', JSON_EXTRACT(data_values, '$.pageTitle')
)
WHERE JSON_EXTRACT(data_values, '$.pageTitle') IS NOT NULL AND data_keys = 'pages.element';


UPDATE `general_settings` SET `email_from_name` = '{{site_name}}' WHERE `general_settings`.`id` = 1;
UPDATE `general_settings` SET `sms_template` = 'hi {{fullname}} ({{username}}), {{message}}' WHERE `general_settings`.`id` = 1;
UPDATE `general_settings` SET `sms_from` = '{{site_name}}' WHERE `general_settings`.`id` = 1;
UPDATE `general_settings` SET `push_title` = '{{site_name}}' WHERE `general_settings`.`id` = 1;
UPDATE `general_settings` SET `push_template` = 'hi {{fullname}} ({{username}}), {{message}}' WHERE `general_settings`.`id` = 1;
UPDATE `general_settings` SET `global_shortcodes` = '{\n \"site_name\":\"Name of your site\",\n \"site_currency\":\"Currency of your site\",\n \"currency_symbol\":\"Symbol of currency\"\n}' WHERE `general_settings`.`id` = 1;
UPDATE `general_settings` SET `socialite_credentials` = '{\"google\":{\"client_id\":\"------------\",\"client_secret\":\"-------------\",\"status\":0},\"facebook\":{\"client_id\":\"------\",\"client_secret\":\"------\",\"status\":0},\"linkedin\":{\"client_id\":\"-----\",\"client_secret\":\"-----\",\"status\":0}}' WHERE `general_settings`.`id` = 1;

INSERT INTO `frontends` (`data_keys`, `data_values`, `tempname`, `slug`, `seo_content`, `created_at`, `updated_at`) VALUES
('maintenance.data', '{\"description\":\"<div class=\\\"mb-5\\\" style=\\\"color: rgb(111, 111, 111); font-family: Nunito, sans-serif; margin-bottom: 3rem !important;\\\"><h2 style=\\\"font-family: Poppins, sans-serif; text-align: center;\\\"><span style=\\\"color: var(--bs-body-color); text-align: var(--bs-body-text-align);\\\"><font size=\\\"6\\\">We\'re just tuning up a few things.<\\/font><\\/span><\\/h2><h3 class=\\\"mb-3\\\" style=\\\"text-align: center; font-weight: 600; line-height: 1.3; font-size: 24px; font-family: Exo, sans-serif; color: rgb(54, 54, 54);\\\"><p style=\\\"font-family: Poppins, sans-serif; text-align: start;\\\">We apologize for the inconvenience but Front is currently undergoing planned maintenance. Thanks for your patience.<\\/p><\\/h3><\\/div>\",\"image\":\"67737b6c900aa1735621484.png\"}', NULL, NULL, NULL, '2020-07-04 23:42:52', '2025-02-17 00:26:11');

ALTER TABLE `general_settings` ADD `product_auto_approval` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `seller_withdraw_limit`;

ALTER TABLE `sellers` ADD `kyc_data` TEXT NULL DEFAULT NULL AFTER `status`, ADD `kyc_rejection_reason` VARCHAR(255) NULL DEFAULT NULL AFTER `kyc_data`, ADD `kv` TINYINT(1) NOT NULL DEFAULT '0' AFTER `kyc_rejection_reason`;


CREATE TABLE sub_orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED DEFAULT 0,
    seller_id INT UNSIGNED DEFAULT 0,
    order_number VARCHAR(40) DEFAULT NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



ALTER TABLE `sub_orders` ADD `is_refunded` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `status`;

-- update sub_orders 
INSERT INTO sub_orders (order_id, seller_id, order_number, total_amount, status, is_refunded, created_at, updated_at)
SELECT 
    o.id AS order_id,
    od.seller_id,
    o.order_number,
    SUM(od.total_price) AS total_amount,
    CASE 
        WHEN o.status = 3 THEN 1
        WHEN o.status = 0 THEN 0
        WHEN o.status = 1 THEN 2
        WHEN o.status = 2 THEN 1
        WHEN o.status = 2 THEN 1
        ELSE o.status
    END AS status,
    0 AS is_refunded,
    o.created_at,
    o.updated_at
FROM order_details od
JOIN orders o ON o.id = od.order_id
GROUP BY od.seller_id, o.id, o.order_number, o.status, o.created_at, o.updated_at;
-- sub_orders insertion end


ALTER TABLE `order_details` ADD `sub_order_id` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `order_id`;

-- update order_details 
UPDATE order_details od
JOIN sub_orders so 
    ON so.order_id = od.order_id 
    AND so.seller_id = od.seller_id
SET od.sub_order_id = so.id;

-- order_details update end

UPDATE orders 
SET status = CASE 
    WHEN status = 0 THEN 0
    WHEN status = 3 THEN 1
    WHEN status = 1 THEN 2
    WHEN status = 2 THEN 4
    WHEN status = 4 THEN 9
    ELSE status -- Retaining other status values in case more exist
END;

-- after insert complete then drop the seller id from order_details table
ALTER TABLE `order_details` DROP `seller_id`;
ALTER TABLE `order_details` DROP `order_id`;

ALTER TABLE `carts` ADD `seller_id` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `user_id`;

ALTER TABLE `order_details` ADD `attributes` TEXT NULL DEFAULT NULL AFTER `details`;
ALTER TABLE `order_details` CHANGE `attributes` `product_attributes` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `stock_logs` CHANGE `type` `type` TINYINT NOT NULL DEFAULT '1' COMMENT '1= Updated by admin, 2= Sell, 3 = Update by seller, 4 = Adjustment';


INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES (NULL, 'ORDER_ITEM_CANCELED', 'Order Item Canceled', 'Some Order Item Canceled', NULL, 'Some of your order items has been canceled by the product seller.\r\n\r\nOrder Number: {{order_number}}\r\nProducts: {{products}}', 'Some of your order items has been canceled by the product seller.\r\n\r\nOrder Number: {{order_number}}\r\nProducts: {{products}}', NULL, '{\"order_number\":\"Order Number\", \"products\":\"Product (Qty)\"}', '1', NULL, NULL, '1', NULL, '0', '2024-12-19 12:52:04', NULL);

ALTER TABLE `orders` ADD `is_refunded` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `status`;

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES (NULL, 'ORDER_READY_FOR_DELIVER', 'Order Ready for Deliver', 'Order Ready for Deliver Confirmation', 'Greetings from {{site_name}}\r\nYour order for has been ready for deliver.\r\nOrder Id :{{order_id}}', 'Greetings from {{site_name}}\r\nYour order for has been ready for deliver. \r\nOrder Id :{{order_id}}', 'Greetings from {{site_name}}\r\nYour order for has been ready for deliver. \r\nOrder Id :{{order_id}}', NULL, '{\"site_name\":\"Company Name\", \"order_id\":\"Order Track Number\"}', '1', NULL, NULL, '1', NULL, '0', '2024-12-19 12:43:29', NULL);

ALTER TABLE `sell_logs` CHANGE `seller_id` `seller_id` INT UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `orders` CHANGE `status` `status` TINYINT NOT NULL DEFAULT '0' COMMENT '0 = pending, 1 = delivered, 2=processing, 3=ready to deliver, 4 = dispatched, 9= canceled_by_admin';

INSERT INTO `extensions` (`id`, `act`, `name`, `description`, `image`, `script`, `shortcode`, `support`, `status`, `created_at`, `updated_at`) VALUES
(2, 'google-recaptcha2', 'Google Recaptcha 2', 'Key location is shown bellow', 'recaptcha3.png', '\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>', '{\"site_key\":{\"title\":\"Site Key\",\"value\":\"6LdPC88fAAAAADQlUf_DV6Hrvgm-pZuLJFSLDOWV\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"6LdPC88fAAAAAG5SVaRYDnV2NpCrptLg2XLYKRKB\"}}', 'recaptcha.png', 0, '2019-10-18 23:16:05', '2022-12-11 01:49:43');

INSERT INTO `frontends` (`data_keys`, `data_values`, `tempname`, `slug`, `seo_content`, `created_at`, `updated_at`) VALUES 
('register_disable.content', '{\"has_image\":\"1\",\"heading\":\"Registration Currently Disabled\",\"subheading\":\"Sign-ups are temporarily unavailable. Please check back later.",\"button_name\":\"Go to Home\",\"button_url\":\"products",\"image\":\"67723f47a885a1735540551.png\"}', 'basic', '', NULL, '2024-12-30 00:35:32', '2024-12-30 00:39:20');

TRUNCATE TABLE `carts`;

ALTER TABLE `languages` CHANGE `icon` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
UPDATE `users` SET `profile_complete` = 1;