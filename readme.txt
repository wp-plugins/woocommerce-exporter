=== WooCommerce - Store Exporter ===

Contributors: visser
Donate link: http://www.visser.com.au/#donations
Tags: e-commerce, woocommerce, shop, cart, ecommerce, export, csv
Requires at least: 2.9.2
Tested up to: 3.7
Stable tag: 1.2.9

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

(*) Compatible with Product Importer Deluxe, All in One SEO Pack, Advanced Google Product Feed and more.
(**) Requries the Pro upgrade to enable additional store export functionality.

For more information visit: http://www.visser.com.au/woocommerce/

== Installation ==

1. Upload the folder 'woocommerce-exporter' to the '/wp-content/plugins/' directory
2. Activate 'WooCommerce - Exporter' through the 'Plugins' menu in WordPress

That's it!

== Usage ==

1. Open WooCommerce > Store Export from the WordPress Administration
2. Select which WooCommerce details you would like to export and click Export

Done!

== Support ==

If you have any problems, questions or suggestions please join the members discussion on my WooCommerce dedicated forum.

http://www.visser.com.au/woocommerce/forums/

== Screenshots ==

1. Select the data type you wish to export, default is Products.
2. Select the data fields to be included in the export, selections are remembered for next export.
3. Filter the exported data by date, status, customer and more.
4. A range of global export options are included.

== Changelog ==

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