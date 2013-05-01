<?php
/*
Plugin Name: WooCommerce - Store Exporter
Plugin URI: http://www.visser.com.au/woocommerce/plugins/exporter/
Description: Export store details out of WooCommerce into a CSV-formatted file.
Version: 1.1.0
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

load_plugin_textdomain( 'woo_ce', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

include_once( 'includes/functions.php' );
include_once( 'includes/functions-alternatives.php' );
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

	function woo_ce_enqueue_scripts( $hook ) {

		/* Export */
		$page = 'woocommerce_page_woo_ce';
		if( $page == $hook ) {
			/* Date Picker */
			wp_enqueue_script( 'jquery-ui-datepicker', plugins_url( '/js/ui-datepicker.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ) );
			wp_enqueue_style( 'jquery-ui-datepicker', plugins_url( '/templates/admin/jquery-ui-datepicker.css', __FILE__ ) );

			/* Common */
			wp_enqueue_style( 'woo_ce_styles', plugins_url( '/templates/admin/woo-admin_ce-export.css', __FILE__ ) );
			wp_enqueue_script( 'woo_ce_scripts', plugins_url( '/templates/admin/woo-admin_ce-export.js', __FILE__ ), array( 'jquery' ) );
		}

	}
	add_action( 'admin_enqueue_scripts', 'woo_ce_enqueue_scripts' );

	function woo_ce_admin_init() {

		global $woo_ce, $export;

		include_once( 'includes/formatting.php' );

		$action = woo_get_action();
		switch( $action ) {

			case 'export':
				$export = new stdClass();
				$export->delimiter = $_POST['delimiter'];
				if( $export->delimiter <> woo_ce_get_option( 'delimiter' ) )
					woo_ce_update_option( 'delimiter', $export->delimiter );
				$export->category_separator = $_POST['category_separator'];
				if( $export->category_separator <> woo_ce_get_option( 'category_separator' ) )
					woo_ce_update_option( 'category_separator', $export->category_separator );
				$export->limit_volume = -1;
				if( !empty( $_POST['limit_volume'] ) ) {
					$export->limit_volume = $_POST['limit_volume'];
					if( $export->limit_volume <> woo_ce_get_option( 'limit_volume' ) )
						woo_ce_update_option( 'limit_volume', $export->limit_volume );
				}
				$export->offset = 0;
				if( !empty( $_POST['offset'] ) ) {
					$export->offset = (int)$_POST['offset'];
					if( $export->offset <> woo_ce_get_option( 'offset' ) )
						woo_ce_update_option( 'offset', $export->offset );
				}
				$export->order_dates_from = '';
				$export->order_dates_to = '';

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
				if( $export->type == 'orders' ) {
					$dataset[] = 'orders';
					$export->fields = $_POST['order_fields'];
					$export->order_dates_from = $_POST['order_dates_from'];
					$export->order_dates_to = $_POST['order_dates_to'];
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

					$timeout = 600;
					if( isset( $_POST['timeout'] ) ) {
						$timeout = $_POST['timeout'];
						if( $timeout <> woo_ce_get_option( 'timeout' ) )
							woo_ce_update_option( 'timeout', $timeout );
					}

					if( !ini_get( 'safe_mode' ) )
						set_time_limit( $timeout );

					$args = array(
						'limit_volume' => $export->limit_volume,
						'offset' => $export->offset,
						'order_dates_from' => woo_ce_format_order_date( $export->order_dates_from ),
						'order_dates_to' => woo_ce_format_order_date( $export->order_dates_to )
					);
					if( isset( $woo_ce['debug'] ) && $woo_ce['debug'] ) {
						woo_ce_export_dataset( $dataset, $args );
					} else {
						woo_ce_generate_csv_header( $export->type );
						woo_ce_export_dataset( $dataset, $args );

						exit();
					}
				}
				break;

		}

	}
	add_action( 'admin_init', 'woo_ce_admin_init' );

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

			case 'update':
				$custom_orders = $_POST['custom_orders'];
				if( $custom_orders ) {
					$custom_orders = explode( "\n", trim( $custom_orders ) );
					$size = count( $custom_orders );
					if( $size ) {
						for( $i = 0; $i < $size; $i++ )
							$custom_orders[$i] = trim( $custom_orders[$i] );
						woo_ce_update_option( 'custom_orders', $custom_orders );
					}
				}

				$message = __( 'Custom Fields saved.', 'woo_ce' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
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