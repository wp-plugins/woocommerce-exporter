<?php
/*
Plugin Name: WooCommerce - Store Exporter
Plugin URI: http://www.visser.com.au/woocommerce/plugins/exporter/
Description: Export store details out of WooCommerce into a CSV-formatted file.
Version: 1.4.9
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

define( 'WOO_CE_DIRNAME', basename( dirname( __FILE__ ) ) );
define( 'WOO_CE_RELPATH', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
define( 'WOO_CE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WOO_CE_PREFIX', 'woo_ce' );

// Turn this on to enable additional debugging options at export time
define( 'WOO_CE_DEBUG', false );

include_once( WOO_CE_PATH . 'includes/functions.php' );
include_once( WOO_CE_PATH . 'includes/functions-alternatives.php' );
include_once( WOO_CE_PATH . 'includes/common.php' );

function woo_ce_i18n() {

	load_plugin_textdomain( 'woo_ce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'init', 'woo_ce_i18n' );

if( is_admin() ) {

	/* Start of: WordPress Administration */

	// Add Export and Docs links to the Plugins screen
	function woo_ce_add_settings_link( $links, $file ) {

		static $this_plugin;

		if( !$this_plugin ) $this_plugin = plugin_basename( __FILE__ );
		if( $file == $this_plugin ) {
			$docs_url = 'http://www.visser.com.au/docs/';
			$docs_link = sprintf( '<a href="%s" target="_blank">' . __( 'Docs', 'woo_ce' ) . '</a>', $docs_url );
			$export_link = sprintf( '<a href="%s">' . __( 'Export', 'woo_ce' ) . '</a>', add_query_arg( 'page', 'woo_ce', 'admin.php' ) );
			array_unshift( $links, $docs_link );
			array_unshift( $links, $export_link );
		}
		return $links;

	}
	add_filter( 'plugin_action_links', 'woo_ce_add_settings_link', 10, 2 );

	// Load CSS and jQuery scripts for Store Exporter screen
	function woo_ce_enqueue_scripts( $hook ) {

		$page = 'woocommerce_page_woo_ce';
		if( $page == $hook ) {
			// WooCommerce
			global $woocommerce;
			wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css' );

			// Date Picker
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-ui-datepicker', plugins_url( '/templates/admin/jquery-ui-datepicker.css', __FILE__ ) );

			// Chosen
			wp_enqueue_script( 'jquery-chosen', plugins_url( '/js/chosen.jquery.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_style( 'jquery-chosen', plugins_url( '/templates/admin/chosen.css', __FILE__ ) );

			// Common
			wp_enqueue_style( 'woo_ce_styles', plugins_url( '/templates/admin/woo-admin_ce-export.css', __FILE__ ) );
			wp_enqueue_script( 'woo_ce_scripts', plugins_url( '/templates/admin/woo-admin_ce-export.js', __FILE__ ), array( 'jquery' ) );
		}

	}
	add_action( 'admin_enqueue_scripts', 'woo_ce_enqueue_scripts' );

	// Initial scripts and export process
	function woo_ce_admin_init() {

		global $export, $wp_roles;

		include_once( 'includes/formatting.php' );

		$action = woo_get_action();
		switch( $action ) {

			case 'dismiss_memory_prompt':
				woo_ce_update_option( 'dismiss_memory_prompt', 1 );
				$url = add_query_arg( 'action', null );
				wp_redirect( $url );
				exit();
				break;

			case 'export':
				$export = new stdClass();
				$export->start_time = time();
				$export->idle_memory_start = woo_ce_current_memory_usage();
				$export->delimiter = $_POST['delimiter'];
				if( $export->delimiter <> woo_ce_get_option( 'delimiter' ) )
					woo_ce_update_option( 'delimiter', $export->delimiter );
				$export->category_separator = $_POST['category_separator'];
				if( $export->category_separator <> woo_ce_get_option( 'category_separator' ) )
					woo_ce_update_option( 'category_separator', $export->category_separator );
				$export->bom = $_POST['bom'];
				if( $export->bom <> woo_ce_get_option( 'bom' ) )
					woo_ce_update_option( 'bom', $export->bom );
				$export->escape_formatting = $_POST['escape_formatting'];
				if( $export->escape_formatting <> woo_ce_get_option( 'escape_formatting' ) )
					woo_ce_update_option( 'escape_formatting', $export->escape_formatting );
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
				$export->delete_temporary_csv = 0;
				if( !empty( $_POST['delete_temporary_csv'] ) ) {
					$export->delete_temporary_csv = (int)$_POST['delete_temporary_csv'];
					if( $export->delete_temporary_csv <> woo_ce_get_option( 'delete_csv' ) )
						woo_ce_update_option( 'delete_csv', $export->delete_temporary_csv );
				}
				$export->encoding = 'UTF-8';
				if( !empty( $_POST['encoding'] ) ) {
					$export->encoding = (string)$_POST['encoding'];
					if( $export->encoding <> woo_ce_get_option( 'encoding' ) )
						woo_ce_update_option( 'encoding', $export->encoding );
				}
				if( !empty( $_POST['date_format'] ) ) {
					$export->date_format = (string)$_POST['date_format'];
					if( $export->date_format <> woo_ce_get_option( 'date_format' ) )
						woo_ce_update_option( 'date_format', $export->date_format );
				}
				$export->fields = false;
				$export->product_categories = false;
				$export->product_tags = false;
				$export->product_status = false;
				$export->product_type = false;
				$export->product_orderby = false;
				$export->product_order = false;
				$export->category_orderby = false;
				$export->category_order = false;
				$export->tag_orderby = false;
				$export->tag_order = false;
				$export->order_dates_filter = false;
				$export->order_dates_from = '';
				$export->order_dates_to = '';
				$export->order_status = false;
				$export->order_customer = false;
				$export->order_user_roles = false;
				$export->order_items = 'combined';
				$export->order_orderby = false;
				$export->order_order = false;
				$export->type = ( isset( $_POST['dataset'] ) ) ? $_POST['dataset'] : false;
				switch( $export->type ) {

					case 'products':
						$export->fields = ( isset( $_POST['product_fields'] ) ) ? $_POST['product_fields'] : false;
						$export->product_categories = ( isset( $_POST['product_filter_categories'] ) ) ? woo_ce_format_product_filters( $_POST['product_filter_categories'] ) : false;
						$export->product_tags = ( isset( $_POST['product_filter_tags'] ) ) ? woo_ce_format_product_filters( $_POST['product_filter_tags'] ) : false;
						$export->product_status = ( isset( $_POST['product_filter_status'] ) ) ? woo_ce_format_product_filters( $_POST['product_filter_status'] ) : false;
						$export->product_type = ( isset( $_POST['product_filter_type'] ) ) ? woo_ce_format_product_filters( $_POST['product_filter_type'] ) : false;
						$export->product_orderby = ( isset( $_POST['product_orderby'] ) ) ? $_POST['product_orderby'] : false;
						if( $export->product_orderby <> woo_ce_get_option( 'product_orderby' ) )
							woo_ce_update_option( 'product_orderby', $export->product_orderby );
						$export->product_order = ( isset( $_POST['product_order'] ) ) ? $_POST['product_order'] : false;
						if( $export->product_order <> woo_ce_get_option( 'product_order' ) )
							woo_ce_update_option( 'product_order', $export->product_order );
						break;

					case 'categories':
						$export->fields = ( isset( $_POST['category_fields'] ) ) ? $_POST['category_fields'] : false;
						$export->category_orderby = ( isset( $_POST['category_orderby'] ) ) ? $_POST['category_orderby'] : false;
						if( $export->category_orderby <> woo_ce_get_option( 'category_orderby' ) )
							woo_ce_update_option( 'category_orderby', $export->category_orderby );
						$export->category_order = ( isset( $_POST['category_order'] ) ) ? $_POST['category_order'] : false;
						if( $export->category_order <> woo_ce_get_option( 'category_order' ) )
							woo_ce_update_option( 'category_order', $export->category_order );
						break;

					case 'tags':
						$export->fields = ( isset( $_POST['tag_fields'] ) ) ? $_POST['tag_fields'] : false;
						$export->tag_orderby = ( isset( $_POST['tag_orderby'] ) ) ? $_POST['tag_orderby'] : false;
						if( $export->tag_orderby <> woo_ce_get_option( 'tag_orderby' ) )
							woo_ce_update_option( 'tag_orderby', $export->tag_orderby );
						$export->tag_order = ( isset( $_POST['tag_order'] ) ) ? $_POST['tag_order'] : false;
						if( $export->tag_order <> woo_ce_get_option( 'tag_order' ) )
							woo_ce_update_option( 'tag_order', $export->tag_order );
						break;

					case 'orders':
						$export->fields = ( isset( $_POST['order_fields'] ) ) ? $_POST['order_fields'] : false;
						$export->order_dates_filter = ( isset( $_POST['order_dates_filter'] ) ) ? $_POST['order_dates_filter'] : false;
						$export->order_dates_from = $_POST['order_dates_from'];
						$export->order_dates_to = $_POST['order_dates_to'];
						$export->order_status = ( isset( $_POST['order_filter_status'] ) ) ? woo_ce_format_product_filters( $_POST['order_filter_status'] ) : false;
						$export->order_customer = ( isset( $_POST['order_customer'] ) ) ? $_POST['order_customer'] : false;
						$export->order_user_roles = ( isset( $_POST['order_filter_user_role'] ) ) ? woo_ce_format_user_role_filters( $_POST['order_filter_user_role'] ) : false;
						if( isset( $_POST['order_items'] ) ) {
							$export->order_items = $_POST['order_items'];
							if( $export->order_items <> woo_ce_get_option( 'order_items_formatting' ) )
								woo_ce_update_option( 'order_items_formatting', $export->order_items );
						}
						if( isset( $_POST['max_order_items'] ) ) {
							$export->max_order_items = (int)$_POST['max_order_items'];
							if( $export->max_order_items <> woo_ce_get_option( 'max_order_items' ) )
								woo_ce_update_option( 'max_order_items', $export->max_order_items );
						}
						$export->order_orderby = ( isset( $_POST['order_orderby'] ) ) ? $_POST['order_orderby'] : false;
						if( $export->order_orderby <> woo_ce_get_option( 'order_orderby' ) )
							woo_ce_update_option( 'order_orderby', $export->order_orderby );
						$export->order_order = ( isset( $_POST['order_order'] ) ) ? $_POST['order_order'] : false;
						if( $export->order_order <> woo_ce_get_option( 'order_order' ) )
							woo_ce_update_option( 'order_order', $export->order_order );
						break;

					case 'customers':
						$export->fields = $_POST['customer_fields'];
						break;

					case 'coupons':
						$export->fields = $_POST['coupon_fields'];
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

					$args = array(
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
					$export->filename = woo_ce_generate_csv_filename( $export->type );
					if( WOO_CE_DEBUG ) {

						woo_ce_export_dataset( $export->type, $args );
						$export->idle_memory_end = woo_ce_current_memory_usage();
						$export->end_time = time();

					} else {

						// Generate CSV contents
						$bits = woo_ce_export_dataset( $export->type, $args );
						unset( $export->fields );
						if( !$bits ) {
							wp_redirect( add_query_arg( 'empty', true ) );
							exit();
						}
						if( isset( $export->delete_temporary_csv ) && $export->delete_temporary_csv ) {

							// Print to browser
							woo_ce_generate_csv_header( $export->type );
							echo $bits;
							exit();

						} else {

							// Save to file and insert to WordPress Media
							if( $export->filename && $bits ) {
								$post_ID = woo_ce_save_csv_file_attachment( $export->filename );
								$upload = wp_upload_bits( $export->filename, null, $bits );
								if( $upload['error'] ) {
									wp_delete_attachment( $post_ID, true );
									wp_redirect( add_query_arg( array( 'failed' => true, 'message' => urlencode( $upload['error'] ) ) ) );
									return;
								}
								$attach_data = wp_generate_attachment_metadata( $post_ID, $upload['file'] );
								wp_update_attachment_metadata( $post_ID, $attach_data );
								if( $post_ID ) {
									woo_ce_save_csv_file_guid( $post_ID, $export->type, $upload['url'] );
									woo_ce_save_csv_file_details( $post_ID );
								}
								$export_type = $export->type;
								unset( $export );

								// The end memory usage and time is collected at the very last opportunity prior to the CSV header being rendered to the screen
								woo_ce_update_csv_file_detail( $post_ID, '_woo_idle_memory_end', woo_ce_current_memory_usage() );
								woo_ce_update_csv_file_detail( $post_ID, '_woo_end_time', time() );

								// Generate CSV header
								woo_ce_generate_csv_header( $export_type );
								unset( $export_type );

								// Print file contents to screen
								if( $upload['file'] ) {
									readfile( $upload['file'] );
								} else {
									wp_redirect( add_query_arg( 'failed', true ) );
								}
								unset( $upload );
							} else {
								wp_redirect( add_query_arg( 'failed', true ) );
							}
							exit();

						}
					}
				}
				break;

			default:
				// Detect other platform versions
				woo_ce_detect_non_woo_install();

				add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_date' );
				add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_status' );
				add_action( 'woo_ce_export_order_options_before_table', 'woo_ce_orders_filter_by_customer' );
				add_action( 'woo_ce_export_order_options_after_table', 'woo_ce_orders_order_sorting' );
				add_action( 'woo_ce_export_after_form', 'woo_ce_products_custom_fields' );
				break;

		}

	}
	add_action( 'admin_init', 'woo_ce_admin_init' );

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
<h3>' . __( 'Export Details' ) . '</h3>
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
		$url = add_query_arg( 'page', 'woo_ce' );
		woo_ce_fail_notices();
		include_once( WOO_CE_PATH . 'templates/admin/woo-admin_ce-export.php' );

	}

	/* End of: WordPress Administration */

}
?>