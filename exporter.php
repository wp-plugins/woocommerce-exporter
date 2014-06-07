<?php
/*
Plugin Name: WooCommerce - Store Exporter
Plugin URI: http://www.visser.com.au/woocommerce/plugins/exporter/
Description: Export store details out of WooCommerce into simple formatted files (e.g. CSV, XML, TXT, etc.).
Version: 1.7
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WOO_CE_DIRNAME', basename( dirname( __FILE__ ) ) );
define( 'WOO_CE_RELPATH', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
define( 'WOO_CE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WOO_CE_PREFIX', 'woo_ce' );

// Turn this on to enable additional debugging options at export time
define( 'WOO_CE_DEBUG', false );

// Avoid conflicts if Store Exporter Deluxe is activated
if( defined( 'WOO_CD_PREFIX' ) == false ) {
	include_once( WOO_CE_PATH . 'includes/common.php' );
	include_once( WOO_CE_PATH . 'includes/functions.php' );
}

function woo_ce_i18n() {

	load_plugin_textdomain( 'woo_ce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'init', 'woo_ce_i18n' );

if( is_admin() ) {

	/* Start of: WordPress Administration */

	// Initial scripts and export process
	function woo_ce_admin_init() {

		global $export, $wp_roles;

		// Now is the time to de-activate Store Exporter if Store Exporter Deluxe is activated
		if( defined( 'WOO_CD_PREFIX' ) ) {
			include_once( WOO_CE_PATH . 'includes/install.php' );
			woo_ce_deactivate_ce();
		}

		// Check that we are on the Store Exporter screen
		$page = ( isset($_GET['page'] ) ? $_GET['page'] : false );
		if( $page != strtolower( WOO_CE_PREFIX ) )
			return;

		// Detect other platform versions
		woo_ce_detect_non_woo_install();

		// Add Store Exporter widgets to Export screen
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_date' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_status' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_customer' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_user_role' );
		add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_coupon' );
		add_action( 'woo_ce_export_order_options_after_table', 'woo_ce_orders_order_sorting' );
		add_action( 'woo_ce_export_customer_options_before_table', 'woo_ce_customers_filter_by_status' );
		add_action( 'woo_ce_export_user_options_after_table', 'woo_ce_users_user_sorting' );
		add_action( 'woo_ce_export_coupon_options_before_table', 'woo_ce_coupons_coupon_sorting' );
		add_action( 'woo_ce_export_options', 'woo_ce_orders_items_formatting' );
		add_action( 'woo_ce_export_options', 'woo_ce_orders_max_order_items' );
		add_action( 'woo_ce_export_after_form', 'woo_ce_products_custom_fields' );
		add_action( 'woo_ce_export_after_form', 'woo_ce_orders_custom_fields' );
		add_action( 'woo_ce_export_options', 'woo_ce_export_options_export_format' );
		add_action( 'woo_ce_export_options', 'woo_ce_export_options_gallery_format' );

		$action = woo_get_action();
		switch( $action ) {

			// Prompt on Export screen when insufficient memory (less than 64M is allocated)
			case 'dismiss_memory_prompt':
				woo_ce_update_option( 'dismiss_memory_prompt', 1 );
				$url = add_query_arg( 'action', null );
				wp_redirect( $url );
				exit();
				break;

			// Save skip overview preference
			case 'skip_overview':
				$skip_overview = false;
				if( isset( $_POST['skip_overview'] ) )
					$skip_overview = 1;
				woo_ce_update_option( 'skip_overview', $skip_overview );

				if( $skip_overview == 1 ) {
					$url = add_query_arg( 'tab', 'export' );
					wp_redirect( $url );
					exit();
				}
				break;

			// This is where the magic happens
			case 'export':

				// Set up the basic export options
				$export = new stdClass();
				$export->start_time = time();
				$export->idle_memory_start = woo_ce_current_memory_usage();
				$export->delete_temporary_csv = woo_ce_get_option( 'delete_csv', 0 );
				$export->encoding = woo_ce_get_option( 'encoding', get_option( 'blog_charset', 'UTF-8' ) );
				if( $export->encoding == 'System default' )
					$export->encoding = 'UTF-8';
				$export->delimiter = woo_ce_get_option( 'delimiter', ',' );
				$export->category_separator = woo_ce_get_option( 'category_separator', '|' );
				$export->bom = woo_ce_get_option( 'bom', 1 );
				$export->escape_formatting = woo_ce_get_option( 'escape_formatting', 'all' );
				$export->date_format = woo_ce_get_option( 'date_format', 'd/m/Y' );

				// Save export option changes made on the Export screen
				$export->limit_volume = ( isset( $_POST['limit_volume'] ) ? $_POST['limit_volume'] : '' );
				woo_ce_update_option( 'limit_volume', $export->limit_volume );
				if( $export->limit_volume == '' )
					$export->limit_volume = -1;
				$export->offset = ( isset( $_POST['offset'] ) ? $_POST['offset'] : '' );
				woo_ce_update_option( 'offset', $export->offset );
				if( $export->offset == '' )
					$export->offset = 0;

				// Set default values for all export options to be later passed onto the export process
				$export->fields = false;
				$export->export_format = woo_ce_get_option( 'export_format', 'csv' );

				// Product sorting
				$export->product_categories = false;
				$export->product_tags = false;
				$export->product_status = false;
				$export->product_type = false;
				$export->product_orderby = false;
				$export->product_order = false;
				$export->upsell_formatting = false;
				$export->crosssell_formatting = false;

				// Category sorting
				$export->category_orderby = false;
				$export->category_order = false;

				// Tag sorting
				$export->tag_orderby = false;
				$export->tag_order = false;

				// Order sorting
				$export->order_dates_filter = false;
				$export->order_dates_from = '';
				$export->order_dates_to = '';
				$export->order_status = false;
				$export->order_customer = false;
				$export->order_user_roles = false;
				$export->order_items = 'combined';
				$export->order_orderby = false;
				$export->order_order = false;
				$export->max_order_items = false;

				$export->type = ( isset( $_POST['dataset'] ) ? $_POST['dataset'] : false );
				switch( $export->type ) {

					case 'products':
						// Set up dataset specific options
						$export->fields = ( isset( $_POST['product_fields'] ) ? $_POST['product_fields'] : false );
						$export->fields_order = ( isset( $_POST['product_fields_order'] ) ? $_POST['product_fields_order'] : false );
						$export->product_categories = ( isset( $_POST['product_filter_categories'] ) ? woo_ce_format_product_filters( $_POST['product_filter_categories'] ) : false );
						$export->product_tags = ( isset( $_POST['product_filter_tags'] ) ? woo_ce_format_product_filters( $_POST['product_filter_tags'] ) : false );
						$export->product_status = ( isset( $_POST['product_filter_status'] ) ? woo_ce_format_product_filters( $_POST['product_filter_status'] ) : false );
						$export->product_type = ( isset( $_POST['product_filter_type'] ) ? woo_ce_format_product_filters( $_POST['product_filter_type'] ) : false );
						$export->product_orderby = ( isset( $_POST['product_orderby'] ) ? $_POST['product_orderby'] : false );
						$export->product_order = ( isset( $_POST['product_order'] ) ? $_POST['product_order'] : false );
						$export->upsell_formatting = ( isset( $_POST['product_upsell_formatting'] ) ? $_POST['product_upsell_formatting'] : false );
						$export->crosssell_formatting = ( isset( $_POST['product_crosssell_formatting'] ) ? $_POST['product_crosssell_formatting'] : false );

						// Save dataset export specific options
						// @mod - Add support for saving Product Categories, Prduct Tags, Product Status, Product Type
						if( $export->product_orderby <> woo_ce_get_option( 'product_orderby' ) )
							woo_ce_update_option( 'product_orderby', $export->product_orderby );
						if( $export->product_order <> woo_ce_get_option( 'product_order' ) )
							woo_ce_update_option( 'product_order', $export->product_order );
						if( $export->upsell_formatting <> woo_ce_get_option( 'upsell_formatting' ) )
							woo_ce_update_option( 'upsell_formatting', $export->upsell_formatting );
						if( $export->crosssell_formatting <> woo_ce_get_option( 'crosssell_formatting' ) )
							woo_ce_update_option( 'crosssell_formatting', $export->crosssell_formatting );
						break;

					case 'categories':
						// Set up dataset specific options
						$export->fields = ( isset( $_POST['category_fields'] ) ? $_POST['category_fields'] : false );
						$export->category_orderby = ( isset( $_POST['category_orderby'] ) ? $_POST['category_orderby'] : false );
						$export->category_order = ( isset( $_POST['category_order'] ) ? $_POST['category_order'] : false );

						// Save dataset export specific options
						if( $export->category_orderby <> woo_ce_get_option( 'category_orderby' ) )
							woo_ce_update_option( 'category_orderby', $export->category_orderby );
						if( $export->category_order <> woo_ce_get_option( 'category_order' ) )
							woo_ce_update_option( 'category_order', $export->category_order );
						break;

					case 'tags':
						// Set up dataset specific options
						$export->fields = ( isset( $_POST['tag_fields'] ) ? $_POST['tag_fields'] : false );
						$export->tag_orderby = ( isset( $_POST['tag_orderby'] ) ? $_POST['tag_orderby'] : false );
						$export->tag_order = ( isset( $_POST['tag_order'] ) ? $_POST['tag_order'] : false );

						// Save dataset export specific options
						if( $export->tag_orderby <> woo_ce_get_option( 'tag_orderby' ) )
							woo_ce_update_option( 'tag_orderby', $export->tag_orderby );
						if( $export->tag_order <> woo_ce_get_option( 'tag_order' ) )
							woo_ce_update_option( 'tag_order', $export->tag_order );
						break;


				}
				if( $export->type ) {

					$timeout = 600;
					if( isset( $_POST['timeout'] ) ) {
						$timeout = (int)$_POST['timeout'];
						if( $timeout <> woo_ce_get_option( 'timeout' ) )
							woo_ce_update_option( 'timeout', $timeout );
					}
					if( !ini_get( 'safe_mode' ) )
						@set_time_limit( $timeout );

					@ini_set( 'memory_limit', WP_MAX_MEMORY_LIMIT );
					@ini_set( 'max_execution_time', (int)$timeout );

					$export->args = array(
						'limit_volume' => $export->limit_volume,
						'offset' => $export->offset,
						'encoding' => $export->encoding,
						'date_format' => $export->date_format,
						'product_categories' => $export->product_categories,
						'product_tags' => $export->product_tags,
						'product_status' => $export->product_status,
						'product_type' => $export->product_type,
						'product_orderby' => $export->product_orderby,
						'product_order' => $export->product_order,
						'category_orderby' => $export->category_orderby,
						'category_order' => $export->category_order,
						'tag_orderby' => $export->tag_orderby,
						'tag_order' => $export->tag_order,
						'order_status' => $export->order_status,
						'order_dates_filter' => $export->order_dates_filter,
						'order_dates_from' => woo_ce_format_order_date( $export->order_dates_from ),
						'order_dates_to' => woo_ce_format_order_date( $export->order_dates_to ),
						'order_customer' => $export->order_customer,
						'order_user_roles' => $export->order_user_roles,
						'order_items' => $export->order_items,
						'order_orderby' => $export->order_orderby,
						'order_order' => $export->order_order
					);
					woo_ce_save_fields( $export->type, $export->fields );

					if( $export->export_format == 'csv' )
						$export->filename = woo_ce_generate_csv_filename( $export->type );

					// Print file contents to debug export screen
					if( WOO_CE_DEBUG ) {

						woo_ce_export_dataset( $export->type );
						$export->idle_memory_end = woo_ce_current_memory_usage();
						$export->end_time = time();

					// Print file contents to browser
					} else {
						if( $export->export_format == 'csv' ) {

							// Generate CSV contents
							$bits = woo_ce_export_dataset( $export->type );
							unset( $export->fields );
							if( !$bits ) {
								wp_redirect( add_query_arg( 'empty', true ) );
								exit();
							}
							if( $export->delete_temporary_csv ) {

								// Print to browser
								woo_ce_generate_csv_header( $export->type );
								echo $bits;
								exit();

							} else {

								// Save to file and insert to WordPress Media
								if( $export->filename && $bits ) {
									$post_ID = woo_ce_save_file_attachment( $export->filename, 'text/csv' );
									$upload = wp_upload_bits( $export->filename, null, $bits );
									if( $upload['error'] ) {
										wp_delete_attachment( $post_ID, true );
										wp_redirect( add_query_arg( array( 'failed' => true, 'message' => urlencode( $upload['error'] ) ) ) );
										return;
									}
									$attach_data = wp_generate_attachment_metadata( $post_ID, $upload['file'] );
									wp_update_attachment_metadata( $post_ID, $attach_data );
									update_attached_file( $post_ID, $upload['file'] );
									if( $post_ID ) {
										woo_ce_save_file_guid( $post_ID, $export->type, $upload['url'] );
										woo_ce_save_file_details( $post_ID );
									}
									$export_type = $export->type;
									unset( $export );

									// The end memory usage and time is collected at the very last opportunity prior to the CSV header being rendered to the screen
									woo_ce_update_file_detail( $post_ID, '_woo_idle_memory_end', woo_ce_current_memory_usage() );
									woo_ce_update_file_detail( $post_ID, '_woo_end_time', time() );

									// Generate CSV header
									woo_ce_generate_csv_header( $export_type );
									unset( $export_type );

									// Print file contents to screen
									if( $upload['file'] )
										readfile( $upload['file'] );
									else
										wp_redirect( add_query_arg( 'failed', true ) );
									unset( $upload );
								} else {
									wp_redirect( add_query_arg( 'failed', true ) );
								}
								exit();

							}

						}
					}
				}
				break;

			// Save changes on Settings screen
			case 'save':
				woo_ce_update_option( 'export_filename', (string)$_POST['export_filename'] );
				woo_ce_update_option( 'delete_csv', (int)$_POST['delete_temporary_csv'] );
				woo_ce_update_option( 'delimiter', (string)$_POST['delimiter'] );
				woo_ce_update_option( 'category_separator', (string)$_POST['category_separator'] );
				woo_ce_update_option( 'bom', (string)$_POST['bom'] );
				woo_ce_update_option( 'encoding', (string)$_POST['encoding'] );
				woo_ce_update_option( 'escape_formatting', (string)$_POST['escape_formatting'] );
				woo_ce_update_option( 'date_format', (string)$_POST['date_format'] );
				$message = __( 'Changes have been saved.', 'woo_ce' );
				woo_ce_admin_notice( $message );
				break;

		}

	}
	add_action( 'admin_init', 'woo_ce_admin_init', 11 );

	// HTML templates and form processor for Store Exporter screen
	function woo_ce_html_page() {

		global $wpdb, $export;

		$title = apply_filters( 'woo_ce_template_header', '' );
		woo_ce_template_header( $title );
		woo_ce_support_donate();
		$action = woo_get_action();
		switch( $action ) {

			case 'export':
				$message = __( 'Chosen WooCommerce details have been exported from your store.', 'woo_ce' );
				woo_ce_admin_notice( $message );
				$output = '';
				if( WOO_CE_DEBUG ) {
					if( false === ( $export_log = get_transient( WOO_CE_PREFIX . '_debug_log' ) ) ) {
						$export_log = __( 'No export entries were found, please try again with different export filters.', 'woo_ce' );
					} else {
						delete_transient( WOO_CE_PREFIX . '_debug_log' );
						$export_log = base64_decode( $export_log );
					}
					$output = '
<h3>' . __( 'Export Details', 'woo_ce' ) . '</h3>
<textarea id="export_log">' . print_r( $export, true ) . '</textarea><hr />
<h3>' . sprintf( __( 'Export Log: %s', 'woo_ce' ), $export->filename ) . '</h3>
<textarea id="export_log">' . $export_log . '</textarea>
';
				}
				echo $output;

				woo_ce_manage_form();
				break;

			case 'update':
				// Save Custom Product Meta
				if( isset( $_POST['custom_products'] ) ) {
					$custom_products = $_POST['custom_products'];
					$custom_products = explode( "\n", trim( $custom_products ) );
					$size = count( $custom_products );
					if( $size ) {
						for( $i = 0; $i < $size; $i++ )
							$custom_products[$i] = trim( $custom_products[$i] );
						woo_ce_update_option( 'custom_products', $custom_products );
					}
				}
				// Save Custom Order Meta
				if( isset( $_POST['custom_orders'] ) ) {
					$custom_orders = $_POST['custom_orders'];
					$custom_orders = explode( "\n", trim( $custom_orders ) );
					$size = count( $custom_orders );
					if( $size ) {
						for( $i = 0; $i < $size; $i++ )
							$custom_orders[$i] = trim( $custom_orders[$i] );
						woo_ce_update_option( 'custom_orders', $custom_orders );
					}
				}
				// Save Custom Order Item Meta
				if( isset( $_POST['custom_order_items'] ) ) {
					$custom_order_items = $_POST['custom_order_items'];
					if( !empty( $custom_order_items ) ) {
						$custom_order_items = explode( "\n", trim( $custom_order_items ) );
						$size = count( $custom_order_items );
						if( $size ) {
							for( $i = 0; $i < $size; $i++ )
								$custom_order_items[$i] = trim( $custom_order_items[$i] );
							woo_ce_update_option( 'custom_order_items', $custom_order_items );
						}
					} else {
						woo_ce_update_option( 'custom_order_items', '' );
					}
				}
				$message = __( 'Custom Fields saved.', 'woo_ce' );
				woo_ce_admin_notice( $message );
				woo_ce_manage_form();
				break;

			default:
				woo_ce_manage_form();
				break;

		}
		woo_ce_template_footer();

	}

	// HTML template for Export screen
	function woo_ce_manage_form() {

		$tab = false;
		if( isset( $_GET['tab'] ) )
			$tab = $_GET['tab'];
		// If Skip Overview is set then jump to Export screen
		else if( woo_ce_get_option( 'skip_overview', false ) )
			$tab = 'export';
		$url = add_query_arg( 'page', 'woo_ce' );
		woo_ce_fail_notices();

		include_once( WOO_CE_PATH . 'templates/admin/tabs.php' );

	}

	/* End of: WordPress Administration */

}
?>