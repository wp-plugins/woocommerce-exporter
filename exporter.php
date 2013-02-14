<?php
/*
Plugin Name: WooCommerce - Store Exporter
Plugin URI: http://www.visser.com.au/woocommerce/plugins/exporter/
Description: Export store details out of WooCommerce into a CSV-formatted file.
Version: 1.0.8
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

load_plugin_textdomain( 'woo_ce', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

include_once( 'includes/functions.php' );

include_once( 'includes/common.php' );

$woo_ce = array(
	'filename' => basename( __FILE__ ),
	'dirname' => basename( dirname( __FILE__ ) ),
	'abspath' => dirname( __FILE__ ),
	'relpath' => basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ )
);

$woo_ce['prefix'] = 'woo_ce';
$woo_ce['name'] = __( 'WooCommerce Exporter', 'woo_ce' );
$woo_ce['menu'] = __( 'Store Export', 'woo_ce' );

if( is_admin() ) {

	/* Start of: WordPress Administration */

	function woo_ce_add_settings_link( $links, $file ) {

		static $this_plugin;
		if( !$this_plugin ) $this_plugin = plugin_basename( __FILE__ );
		if( $file == $this_plugin ) {
			$settings_link = sprintf( '<a href="%s">' . __( 'Export', 'woo_ce' ) . '</a>', add_query_arg( 'page', 'woo_ce', 'admin.php' ) );
			array_unshift( $links, $settings_link );
		}
		return $links;

	}
	add_filter( 'plugin_action_links', 'woo_ce_add_settings_link', 10, 2 );

	function woo_ce_admin_init() {

		global $woo_ce, $export;

		include_once( 'includes/formatting.php' );

		$action = woo_get_action();
		switch( $action ) {

			case 'export':
				$export = new stdClass();
				$export->delimiter = $_POST['delimiter'];
				$export->category_separator = $_POST['category_separator'];
				$dataset = array();
				$export->type = $_POST['dataset'];
				if( $export->type == 'products' ) {
					$dataset[] = 'products';
					$export->fields = $_POST['product_fields'];
				}
				if( $export->type == 'categories' )
					$dataset[] = 'categories';
				if( $export->type == 'tags' )
					$dataset[] = 'tags';
				if( $export->type == 'sales' ) {
					$dataset[] = 'orders';
					$export->fields = $_POST['sale_fields'];
				}
				if( $export->type == 'customers' ) {
					$dataset[] = 'customers';
					$export->fields = $_POST['customer_fields'];
				}
				if( $export->type == 'coupons' ) {
					$dataset[] = 'coupons';
					$export->fields = $_POST['coupon_fields'];
				}
				if( $dataset ) {

					if( isset( $_POST['timeout'] ) )
						$timeout = $_POST['timeout'];
					else
						$timeout = 600;

					if( !ini_get( 'safe_mode' ) )
						set_time_limit( $timeout );

					if( isset( $woo_ce['debug'] ) && $woo_ce['debug'] ) {
						woo_ce_export_dataset( $dataset );
					} else {
						woo_ce_generate_csv_header( $export->type );
						woo_ce_export_dataset( $dataset );

						exit();
					}
				}
				break;

		}

	}
	add_action( 'admin_init', 'woo_ce_admin_init' );

	function woo_ce_enqueue_scripts( $hook ) {

		/* Export */
		$page = 'woocommerce_page_woo_ce';
		if( $page == $hook ) {
			wp_enqueue_style( 'woo_ce_styles', plugins_url( '/templates/admin/woo-admin_ce-export.css', __FILE__ ) );
			wp_enqueue_script( 'woo_ce_scripts', plugins_url( '/templates/admin/woo-admin_ce-export.js', __FILE__ ), array( 'jquery' ) );
		}

	}
	add_action( 'admin_enqueue_scripts', 'woo_ce_enqueue_scripts' );

	function woo_ce_html_page() {

		global $wpdb, $woo_ce;

		$title = apply_filters( 'woo_ce_template_header', '' );
		woo_ce_template_header( $title );
		woo_ce_support_donate();
		$action = woo_get_action();
		switch( $action ) {

			case 'export':
				$message = __( 'Chosen WooCommerce details have been exported from your store.', 'woo_ce' );
				$output = '<div class="updated settings-error"><p><strong>' . $message . '</strong></p></div>';
				echo $output;

				woo_ce_manage_form();
				break;

			default:
				woo_ce_manage_form();
				break;

		}
		woo_ce_template_footer();

	}

	function woo_ce_manage_form() {

		global $woo_ce;

		$tab = false;
		if( isset( $_GET['tab'] ) )
			$tab = $_GET['tab'];

		$url = add_query_arg( 'page', 'woo_ce' );

		include_once( 'templates/admin/woo-admin_ce-export.php' );

	}

	/* End of: WordPress Administration */

}
?>