=== WooCommerce - Store Exporter ===

Contributors: visser
Donate link: http://www.visser.com.au/#donations
Tags: e-commerce, woocommerce, shop, cart, ecommerce, export, csv, customers, products, sales, coupons
Requires at least: 2.9.2
Tested up to: 3.9.1
Stable tag: 1.5.5

== Description ==

Export store details out of WooCommerce into a CSV-formatted file.

Features include:

* Export Products (*)
* Export Products by Product Category
* Export Products by Product Status
* Export Products by Type including Variations
* Export Categories
* Export Tags
* Export Orders (**)
* Export Orders by Order Status (**)
* Export Orders by Order Date (**)
* Export Orders by Customers (**)
* Export Customers (**)
* Export Coupons (**)
* Toggle and save export fields
* Works with WordPress Multisite
* Supports external CRON commands
* Supports scheduled exports

(*) Compatible with Product Importer Deluxe, All in One SEO Pack, Advanced Google Product Feed, Product Addons, Sequential Order Number Pro, WooCommerce Checkout Manager, Cost of Goods, Per-Product Shipping, Local Pickups Plus and more.
(**) Requries the Pro upgrade to enable additional store export functionality.

For more information visit: http://www.visser.com.au/woocommerce/

== Installation ==

1. Upload the folder 'woocommerce-exporter' to the '/wp-content/plugins/' directory

2. Activate 'WooCommerce - Exporter' through the 'Plugins' menu in WordPress

That's it!

== Usage ==

1. Open WooCommerce > Store Export from the WordPress Administration

2. Select the Export tab on the Store Exporter screen

3. Select which data type and WooCommerce details you would like to export and click Export

4. Download archived copies of previous exports from the Archives tab

Done!

== Support ==

If you have any problems, questions or suggestions please join the members discussion on our WooCommerce dedicated forum.

http://www.visser.com.au/woocommerce/forums/

== Screenshots ==

1. The overview screen for Store Exporter.
2. Select the data fields to be included in the export, selections are remembered for next export.
3. Each dataset (e.g. Products, Orders, etc.) include filter options to filter by date, status, type, customer and more.
4. A range of export options can be adjusted to suit different languages and file formatting requirements.
5. Export a list of WooCommerce Product Categories into a CSV file.
6. Export a list of WooCommerce Product Tags into a CSV file.
7. Download achived copies of previous exports

== Changelog ==

= 1.5.5 =
* Fixed: Export error prompt displaying due to WordPress transient

= 1.5.4 =
* Changed: Removed WooCommere Plugins dashboard widget
* Added: CSS class to Custom Product Fields
* Fixed: Broken export checks that may affect export options

= 1.5.3 =
* Added: Support for exporting Local Pickup Plus fields in Orders
* Fixed: Memory leak in woo_ce_expand_state_name
* Fixed: Memory leak in woo_ce_expand_country_name
* Changed: Removed duplicate Order Items: Type field
* Added: Disabled Custom Order Fields widget to Export screen
* Changed: Using WP_Query instead of get_posts for bulk export
* Changed: Cross-Sells and Up-Sells get their own formatting functions
* Changed: Moved export function into common space for CRON and scheduled exports
* Added: Toggle visibility of each export types fields within Export Options

= 1.5.2 =
* Added: Option for Up-Sells to export Product SKU instead of Product ID
* Added: Option for Cross-Sells to export Product SKU instead of Product ID
* Changed: Toggle visibility of dataset relevant export options
* Changed: Moved Field delimiter option to Settings tab
* Changed: Moved Category separator option to Settings tab
* Changed: Moved Add BOM character option to Settings tab
* Changed: Moved Character encoding option to Settings tab
* Changed: Moved Field escape formatting option to Settings tab
* Changed: Moved Order Item Formatting option to Export Options widget
* Changed: Combined Volume offset and Limit volume
* Added: Skip Overview screen option to Overview screen

= 1.5.1 =
* Fixed: CSV File not being displayed on Media screen
* Added: Download Type support to Products export
* Fixed: File Download support for WooCommerce 2.0+
* Changed: Legacy support for File Download export support in pre-WooCommerce 2.0
* Changed: An empty weight/height/width/length will make the dimension unit empty
* Added: Setttings tab for managing global export settings
* Added: Custom export filename support with variables: %store_name%, %dataset%, %date%, %time%
* Changed: Moved Date Format option to Settings tab
* Changed: Moved Max unique Order items option to Settings tab
* Changed: Moved Enable Archives options to Settings tab
* Changed: Removed Manage Custom Product Fields link from Export Options
* Changed: Moved Script Timeout option to Settings tab

= 1.5 =
* Added: Menu Order to Products export
* Changed: Comment Status to Enable Reviews in Products export

= 1.4.9 =
* Added: Order Items: Category and Order Items: Tag to Orders export
* Added: Clicking an export type from the opening screen will open that export tab

= 1.4.8 =
* Changed: Dropped $wpsc_ce global
* Added: Using Plugin constants
* Changed: Moved debug log to WordPress transient
* Added: Disabled Custom Product Fields dialog
* Changed: Removed duplicate Sale Price from Product export
* Fixed: Empty Parent SKU and Product SKU for Product Variations
* Fixed: Fill default Stock Status for Products
* Fixed: Set Product Type to Simple Product by default
* Added: Error notice after blank screen on export
* Fixed: Product Categories empty for Variations in Product export

= 1.4.7 =
* Fixed: Multi-site support resolved
* Changed: Permanently delete failed exports

= 1.4.6 =
* Fixed: Blank screen on export in some instances
* Changed: Removed legacy progress bar
* Changed: Removed legacy Javascript in export screen
* Added: Admin notice confirming deleted archive file
* Changed: Removed bold headings from admin notices
* Added: Error notice to explain blank CSV
* Changed: Renamed "Delete temporary CSV after download" to "Enable Archives"
* Changed: Removed woo_ce_unload_export_global()
* Fixed: Delete WordPress Media on failed export
* Added: Link to Usage document when an error is encountered "Need help?"
* Changed: Using 'export' capability check for Store Export menu
* Changed: Using 'update_plugins' capability check for Jigoshop Plugins Dashboard widget (thanks Marcus!)

= 1.4.5 =
* Added: Custom Product fields
* Added: Memory optimisations for get_posts()
* Changed: Standard admin notices

= 1.4.4 =
* Changed: Default Date Format to d/m/Y

= 1.4.3 =
* Fixed: Export Orders by User Role
* Added: Formatting of User Role

= 1.4.2 =
* Added: Product Published and Product Modified dates to Products export
* Added: Date formatting independant of WordPress > Settings > General

= 1.4.1 =
* Fixed: Default file encoding can trigger PHP warning
* Added: File encoding support for Categories and Tags
* Added: Product Tags sorting export support
* Added: Category sorting export support
* Added: Separate files for each dataset

= 1.4 =
* Added: File encoding for datasets
* Changed: Default file encoding to UTF-8
* Added: Product sorting and ordering
* Changed: Ordering of Export Options

= 1.3.9 =
* Added: Payment Gateway ID to Orders export
* Added: Shipping Method ID to Orders export
* Added: Shipping Cost to Orders export
* Added: Checkout IP Address to Orders export
* Added: User Role to Orders export
* Changed: Removed encoding changes to Description and Excerpt

= 1.3.8 =
* Fixed: PHP 4 notices for File Encoding dropdown
* Fixed: Translation string on Export screen
* Added: WordPress get_posts() optimisation
* Fixed: Ignore Variant Products without Base Products (ala 'phantom Posts')

= 1.3.7 =
* Added: Additional Category column support
* Added: Additional Tag column support
* Fixed: HTML entities now print in plain-text

= 1.3.6 =
* Fixed: PHP error for missing function within Store Exporter Deluxe

= 1.3.5 =
* Fixed: Admin icon on Store Exporter screen
* Fixed: PHP error on Store Exporter screen without Products
* Changed: Moved CSV File dialog on Media screen to template file

= 1.3.4 =
* Added: Total incl. GST
* Added: Total excl. GST
* Added: Purchase Time
* Changed: Moved woo_ce_count_object() to formatting.php
* Added: Commenting to each function
* Fixed: PHP 4 support for missing mb_list_encodings()

= 1.3.3 =
* Added: New Product filter 'woo_ce_product_item'

= 1.3.2 =
* Fixed: Order Notes on Orders export

= 1.3.1 =
* Added: Link to submit additional fields

= 1.3 =
* Changed: Using manage_woocommerce instead of manage_options for permission check
* Changed: Removed woo_is_admin_valid_icon
* Changed: Using default WooCommerce icons

= 1.2.9 =
* Fixed: Urgent fix for duplicate formatting function

= 1.2.8 =
* Added: Product ID support
* Added: Post Parent ID support
* Added: Export Product variation support
* Added: Product Attribute support
* Added: Filter Products export by Type
* Added: Sale Price Dates From/To support
* Added: Virtual and Downloadable Product support
* Added: Remove archived export
* Added: Count and filter of archived exports
* Fixed: Hide User ID 0 (guest) from Orders

= 1.2.7 =
* Added: jQuery Chosen support to Orders Customer dropdown
* Fixed: Incorrect counts on some Export types

= 1.2.6 =
* Added: Product Type support
* Added: Native jQuery UI support
* Fixed: Various small bugs

= 1.2.5 =
* Added: Featured Image support

= 1.2.3 =
* Fixed: Tags export
* Added: Export Products by Product Tag filter
* Added: Notice for empty export files
* Changed: UI changes to Filter dialogs

= 1.2.2 =
* Changed: Free version can see Order, Coupon and Customer export options
* Added: Plugin screenshots

= 1.2.1 =
* Added: Support for BOM
* Added: Escape field formatting option
* Added: New line support
* Added: Payment Status (number) option

= 1.2 =
* Fixed: Surplus cell separator at end of lines
* Added: Remember field selections

= 1.1.1 =
* Added: Expiry Date support to Coupons
* Added: Individual Use to Coupons
* Added: Apply before tax to Coupons
* Added: Exclude sale items to Coupons
* Added: Expiry Date to Coupons
* Added: Minimum Amount to Coupons
* Added: Exclude Product ID's to Coupons
* Added: Product Categories to Coupons
* Added: Exclude Product Categories to Coupons
* Added: Usage Limit to Coupons
* Fixed: Customers count causing memory error
* Added: Formatting of 'on' and 'off' values
* Changed: Memory overrides

= 1.1.0 =
* Added: Save option for delimiter
* Added: Save option for category separator
* Added: Save options for limit volume
* Added: Save options for offset
* Added: Save options for timeout

= 1.0.9 =
* Fixed: Export buttons not adjusting Export Dataset
* Added: Select All options to Export
* Added: Partial export support
* Changed: Integration with Exporter Deluxe

= 1.0.8 =
* Added: Integration with Exporter Deluxe

= 1.0.7 =
* Fixed: Excerpt/Product Short description

= 1.0.6 =
* Changed: Options engine
* Changed: Moved styles to admin_enqueue_scripts
* Added: Coupons support

= 1.0.5 =
* Fixed: Template header bug
* Added: Tabbed viewing on the Exporter screen
* Added: Export Orders
* Added: Product columns
* Added: Order columns
* Added: Category heirachy support (up to 3 levels deep)
* Fixed: Foreign character support
* Changed: More efficient Tag generation
* Fixed: Link error on Export within Plugin screen

= 1.0.4 =
* Added: Duplicate e-mail address filtering
* Changed: Updated readme.txt

= 1.0.3 =
* Added: Support for Customers

= 1.0.2 =
* Changed: Migrated to WordPress Extend

= 1.0.1 =
* Fixed: Dashboard widget not loading

= 1.0 =
* Added: First working release of the Plugin

== Disclaimer ==

It is not responsible for any harm or wrong doing this Plugin may cause. Users are fully responsible for their own use. This Plugin is to be used WITHOUT warranty.