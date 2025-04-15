=============================
Update Guide: Version 2.2
=============================

==========================Important Notice=========================

	Before proceeding with the update, please ensure you:
		1. Backup your "assets" folder inside the root directory.
		2. Backup your database to prevent data loss.

===================================================================


==========================How to Update?==========================

	Step 1: Extract the Update Files
	Extract the update.zip file into your root directory where the old files of version 1.0 are located. Ensure that all files are replaced correctly.

	Step 2: Update the Database
	Import the update.sql file into your existing database of version 2.0 using your database management tool (e.g., phpMyAdmin, MySQL CLI).

==============================================================================================


======================Changelog: Version 2.0======================

Bug Fixes:
[FIX] 500 error in order confirmation process.
[FIX] 500 error on products page while filtering by category.
[FIX] Issues in products inventory.
[FIX] Search feature in mobile screens.
[FIX] 500 server error in seller logs page.
[FIX] 500 server error in products page when no products are added.

Updates & Enhancements:
[UPDATE] Improved Admin Panel UI.
[UPDATE] Enhanced Order Management in Admin Panel.
[UPDATE] Redesigned Order Detail Page in Admin Panel.
[UPDATE] Improved Order Details Page in Seller Panel.
[UPDATE] Refreshed design for Widgets & Insights in Admin Dashboard.
[UPDATE] Date Filter Feature added in Sales & Withdraw Report Chart (Admin Dashboard).
[UPDATE] Date Filter Feature added in Sales Report Chart (My Shop Page - Admin Panel).

New Features:
[ADD] Transaction Report Chart in Admin Dashboard.
[ADD] KYC Verification for Sellers.
[ADD] Order Management in Seller Panel.
[ADD] Login with Google, Facebook, LinkedIn accounts.
[ADD] XML Sitemap Configuration.
[ADD] robots.txt Configuration.
[ADD] New Payment Gateways:
	1. Binance
	2. Aamarpay
	3. SslCommerz
	4. Authorize.net
	5. Mercado Pago
	6. Now Payments
	7. NMI
[ADD] Slug Management for Policy Pages.
[ADD] SEO Content Management for Policy Pages.
[ADD] Form Generator.
[ADD] Automatic System Update.
[ADD] Push Notification System.
[ADD] Global SMS Template.
[ADD] Global Push Notification Template.
[ADD] Copy Shortcodes for Notification Templates.
[ADD] New SMS Gateways & Configurations:
	1. Clickatell
	2. Infobip
	3. MessageBird
	4. Nexmo
	5. SMS Broadcast
	6. Twilio
	7. TextMagic
	8. Custom API Configuration for SMS Gateway
[ADD] Force SSL Configuration for Website.
[ADD] Agree Policy Configuration.
[ADD] Force Secure Password.
[ADD] Multilingual Support Configuration.

Patches & Security Updates:
[PATCH] Latest System Patch.
[PATCH] Latest Security Patch.
[PATCH] Upgraded to Latest Laravel Version (Laravel 11).
[PATCH] Upgraded to Latest PHP Version (PHP 8.3).