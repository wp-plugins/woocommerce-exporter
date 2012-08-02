<?php
/*
Plugin Name: WooCommerce - Exporter
Plugin URI: http://www.visser.com.au/woocommerce/plugins/exporter/
Description: Export store details out of WooCommerce into a CSV-formatted file.
Version: 1.0.1
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
$woo_ce['name'] = __( 'WooCommerce Exporter', 'woo_dm' );
$woo_ce['menu'] = __( 'Store Export', 'woo_de' );

if( is_admin() ) {

	function woo_ce_admin_init() {

		if( isset( $_POST['action'] ) ) {
			if( $_POST['action'] == 'export' ) {
				$dataset = array();
				if( $_POST['dataset'] == 'categories' )
					$dataset[] = 'categories';
				if( $_POST['dataset'] == 'products' )
					$dataset[] = 'products';
				if( $dataset ) {

					if( isset( $_POST['timeout'] ) )
						$timeout = $_POST['timeout'];
					else
						$timeout = 600;

					if( !ini_get( 'safe_mode' ) )
						set_time_limit( $timeout );

					woo_ce_generate_csv_header();
					woo_ce_export_dataset( $dataset );
					exit();
				}
			}
		}

	}
	add_action( 'admin_init', 'woo_ce_admin_init' );

	function woo_ce_html_page() {

		global $wpdb, $woo_ce;

		woo_ce_template_header();
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

		$url = 'tools.php?page=woo_ce';
		if( function_exists( 'woo_pd_init' ) )
			$woo_pd_url = 'admin.php?page=woo_pd';
		else
			$woo_pd_url = 'http://www.visser.com.au/woocommerce/plugins/product-importer-deluxe/';

		$categories = woo_ce_return_count( 'categories' );
		$products = woo_ce_return_count( 'products' );

		include_once( 'templates/admin/woo-admin_ce-export.php' );

	}

}
?>