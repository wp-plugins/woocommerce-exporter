<?php
include_once( WOO_CE_PATH . 'includes/functions-products.php' );
include_once( WOO_CE_PATH . 'includes/functions-categories.php' );
include_once( WOO_CE_PATH . 'includes/functions-tags.php' );
include_once( WOO_CE_PATH . 'includes/functions-orders.php' );
include_once( WOO_CE_PATH . 'includes/functions-coupons.php' );
include_once( WOO_CE_PATH . 'includes/functions-customers.php' );

include_once( WOO_CE_PATH . 'includes/formatting.php' );

include_once( WOO_CE_PATH . 'includes/functions-csv.php' );

if( is_admin() ) {

	/* Start of: WordPress Administration */

	function woo_ce_detect_non_woo_install() {

		if( !woo_is_woo_activated() && ( woo_is_jigo_activated() || woo_is_wpsc_activated() ) ) {
			$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';
			$message = __( 'We have detected another e-Commerce Plugin than WooCommerce activated, please check that you are using Store Exporter Deluxe for the correct platform.', 'woo_ce' ) . '<a href="' . $troubleshooting_url . '" target="_blank">' . __( 'Need help?', 'woo_ce' ) . '</a>';
			woo_ce_admin_notice( $message, 'error', 'plugins.php' );
		}
		woo_ce_plugin_page_notices();

	}

	function woo_ce_plugin_page_notices() {

		global $pagenow;

		if( $pagenow == 'plugins.php' ) {
			if( woo_is_jigo_activated() || woo_is_wpsc_activated() ) {
				$r_plugins = array(
					'woocommerce-exporter/exporter.php'
				);
				$i_plugins = get_plugins();
				foreach( $r_plugins as $path ) {
					if( isset( $i_plugins[$path] ) ) {
						add_action( 'after_plugin_row_' . $path, 'woo_ce_plugin_page_notice', 10, 3 );
						break;
					}
				}
			}
		}
	}

	function woo_ce_plugin_page_notice( $file, $data, $context ) {

		if( is_plugin_active( $file ) ) { ?>
<tr class='plugin-update-tr su-plugin-notice'>
	<td colspan='3' class='plugin-update colspanchange'>
		<div class='update-message'>
			<?php printf( __( '%1$s is intended to be used with a WooCommerce store, please check that you are using Store Exporter with the correct e-Commerce platform.', 'woo_ce' ), $data['Name'] ); ?>
		</div>
	</td>
</tr>
<?php
		}

	}

	// Display admin notice on screen load
	function woo_ce_admin_notice( $message = '', $priority = 'updated', $screen = '' ) {

		if( empty( $priority ) )
			$priority = 'updated';
		if( !empty( $message ) )
			add_action( 'admin_notices', woo_ce_admin_notice_html( $message, $priority, $screen ) );

	}

	// HTML template for admin notice
	function woo_ce_admin_notice_html( $message = '', $priority = 'updated', $screen = '' ) {

		// Display admin notice on specific screen
		if( !empty( $screen ) ) {
			global $pagenow;
			if( $pagenow <> $screen )
				return;
		} ?>
<div id="message" class="<?php echo $priority; ?>">
	<p><?php echo $message; ?></p>
</div>
<?php

	}

	// Add Store Export to WordPress Administration menu
	function woo_ce_admin_menu() {

		add_submenu_page( 'woocommerce', __( 'Store Exporter', 'woo_ce' ), __( 'Store Export', 'woo_ce' ), 'export', 'woo_ce', 'woo_ce_html_page' );

	}
	add_action( 'admin_menu', 'woo_ce_admin_menu' );

	// HTML template header on Store Exporter screen
	function woo_ce_template_header( $title = '', $icon = 'woocommerce' ) {

		if( $title )
			$output = $title;
		else
			$output = __( 'Store Export', 'woo_ce' ); ?>
<div class="wrap">
	<div id="icon-<?php echo $icon; ?>" class="icon32 icon32-woocommerce-importer"><br /></div>
	<h2>
		<?php echo $output; ?>
		<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'woo_ce' ); ?></a>
	</h2>
<?php
	}

	// HTML template footer on Store Exporter screen
	function woo_ce_template_footer() { ?>
</div>
<?php
	}

	// HTML template for header prompt on Store Exporter screen
	function woo_ce_support_donate() {

		$output = '';
		$show = true;
		if( function_exists( 'woo_vl_we_love_your_plugins' ) ) {
			if( in_array( WOO_CE_DIRNAME, woo_vl_we_love_your_plugins() ) )
				$show = false;
		}
		if( function_exists( 'woo_cd_admin_init' ) )
			$show = false;
		if( $show ) {
			$donate_url = 'http://www.visser.com.au/#donations';
			$rate_url = 'http://wordpress.org/support/view/plugin-reviews/' . WOO_CE_DIRNAME;
			$output = '
	<div id="support-donate_rate" class="support-donate_rate">
		<p>' . sprintf( __( '<strong>Like this Plugin?</strong> %s and %s.', 'woo_ce' ), '<a href="' . $donate_url . '" target="_blank">' . __( 'Donate to support this Plugin', 'woo_ce' ) . '</a>', '<a href="' . add_query_arg( array( 'rate' => '5' ), $rate_url ) . '#postform" target="_blank">rate / review us on WordPress.org</a>' ) . '</p>
	</div>
';
		}
		echo $output;

	}

	// Saves the state of Export fields for next export
	function woo_ce_save_fields( $type, $fields = array() ) {

		if( $fields == false )
			$fields = array();
		if( $type && isset( $fields ) )
			woo_ce_update_option( $type . '_fields', $fields );

	}

	// Add Store Export to filter types on the WordPress Media screen
	function woo_ce_add_post_mime_type( $post_mime_types = array() ) {

		$post_mime_types['text/csv'] = array( __( 'Store Exports', 'woo_ce' ), __( 'Manage Store Exports', 'woo_ce' ), _n_noop( 'Store Export <span class="count">(%s)</span>', 'Store Exports <span class="count">(%s)</span>' ) );
		return $post_mime_types;

	}
	add_filter( 'post_mime_types', 'woo_ce_add_post_mime_type' );

	// In-line display of CSV file and export details when viewed via WordPress Media screen
	function woo_ce_read_csv_file( $post = null ) {

		if( !$post ) {
			if( isset( $_GET['post'] ) )
				$post = get_post( $_GET['post'] );
		}

		if( $post->post_type != 'attachment' )
			return false;

		if( $post->post_mime_type != 'text/csv' )
			return false;

		$filename = $post->post_name;
		$filepath = get_attached_file( $post->ID );
		$contents = __( 'No export entries were found, please try again with different export filters.', 'woo_ce' );
		if( file_exists( $filepath ) ) {
			$handle = fopen( $filepath, "r" );
			$contents = stream_get_contents( $handle );
			fclose( $handle );
		} else {
			// This resets the _wp_attached_file Post meta key to the correct value
			update_attached_file( $post->ID, $post->guid );
			// Try grabbing the file contents again
			$filepath = get_attached_file( $post->ID );
			if( file_exists( $filepath ) ) {
				$handle = fopen( $filepath, "r" );
				$contents = stream_get_contents( $handle );
				fclose( $handle );
			}
		}
		if( !empty( $contents ) )
			include_once( WOO_CE_PATH . 'templates/admin/woo-admin_ce-media_csv_file.php' );

		$export_type = get_post_meta( $post->ID, '_woo_export_type', true );
		$columns = get_post_meta( $post->ID, '_woo_columns', true );
		$rows = get_post_meta( $post->ID, '_woo_rows', true );
		$start_time = get_post_meta( $post->ID, '_woo_start_time', true );
		$end_time = get_post_meta( $post->ID, '_woo_end_time', true );
		$idle_memory_start = get_post_meta( $post->ID, '_woo_idle_memory_start', true );
		$data_memory_start = get_post_meta( $post->ID, '_woo_data_memory_start', true );
		$data_memory_end = get_post_meta( $post->ID, '_woo_data_memory_end', true );
		$idle_memory_end = get_post_meta( $post->ID, '_woo_idle_memory_end', true );
		include_once( WOO_CE_PATH . 'templates/admin/woo-admin_ce-media_export_details.php' );

	}
	add_action( 'edit_form_after_editor', 'woo_ce_read_csv_file' );

	// List of Export types used on Store Exporter screen
	function woo_ce_return_export_types() {

		$export_types = array();
		$export_types['products'] = __( 'Products', 'woo_ce' );
		$export_types['categories'] = __( 'Categories', 'woo_ce' );
		$export_types['tags'] = __( 'Tags', 'woo_ce' );
		$export_types['orders'] = __( 'Orders', 'woo_ce' );
		$export_types['customers'] = __( 'Customers', 'woo_ce' );
		$export_types['coupons'] = __( 'Coupons', 'woo_ce' );
		$export_types = apply_filters( 'woo_ce_export_types', $export_types );
		return $export_types;

	}

	// Returns label of Export type slug used on Store Exporter screen
	function woo_ce_export_type_label( $export_type = '', $echo = false ) {

		$output = '';
		if( !empty( $export_type ) ) {
			$export_types = woo_ce_return_export_types();
			if( array_key_exists( $export_type, $export_types ) )
				$output = $export_types[$export_type];
		}
		if( $echo )
			echo $output;
		else
			return $output;

	}

	// Returns number of an Export type prior to export, used on Store Exporter screen
	function woo_ce_return_count( $export_type ) {

		global $wpdb;

		$count_sql = null;
		switch( $export_type ) {

			case 'products':
				$post_type = 'product';
				$args = array(
					'post_type' => $post_type,
					'posts_per_page' => 1
				);
				$query = new WP_Query( $args );
				$count = $query->found_posts;
				break;

			case 'categories':
				$term_taxonomy = 'product_cat';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'tags':
				$term_taxonomy = 'product_tag';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'orders':
				$post_type = 'shop_order';
				$args = array(
					'post_type' => $post_type,
					'posts_per_page' => 1
				);
				$query = new WP_Query( $args );
				$count = $query->found_posts;
				break;

			case 'customers':
				$post_type = 'shop_order';
				$args = array(
					'post_type' => $post_type,
					'posts_per_page' => -1,
					'post_status' => woo_ce_post_statuses(),
					'cache_results' => false,
					'no_found_rows' => false,
					'tax_query' => array(
						array(
							'taxonomy' => 'shop_order_status',
							'field' => 'slug',
							'terms' => array( 'pending', 'on-hold', 'processing', 'completed' )
						),
					),
				);
				$orders = new WP_Query( $args );
				$count = $orders->found_posts;
				if( $count > 100 ) {
					$count = sprintf( '~%s *', $count );
				} else {
					$customers = array();
					if ( $orders->have_posts() ) {
						while ( $orders->have_posts() ) {
							$orders->the_post();
							$email = get_post_meta( get_the_ID(), '_billing_email', true );
							if( !in_array( $email, $customers ) ) {
								$customers[get_the_ID()] = $email;
							}
							unset( $email );
						}
						$count = count( $customers );
					}
					wp_reset_postdata();
				}
/*
				if( false ) {
					$orders = get_posts( $args );
					if( $orders ) {
						$customers = array();
						foreach( $orders as $order ) {
							$order->email = get_post_meta( $order->ID, '_billing_email', true );
							if( empty( $order->email ) ) {
								if( $order->user_id = get_post_meta( $order->ID, '_customer_user', true ) ) {
									$user = get_userdata( $order->user_id );
									if( $user )
										$order->email = $user->user_email;
									unset( $user );
								} else {
									$order->email = '-';
								}
							}
							if( !in_array( $order->email, $customers ) ) {
								$customers[$order->ID] = $order->email;
								$count++;
							}
						}
						unset( $orders, $order );
					}
				}
*/
				break;

			case 'coupons':
				$post_type = 'shop_coupon';
				$count = wp_count_posts( $post_type );
				break;

		}
		if( isset( $count ) || $count_sql ) {
			if( isset( $count ) ) {
				if( is_object( $count ) ) {
					$count = (array)$count;
					$count = (int)array_sum( $count );
				}
				return $count;
			} else {
				if( $count_sql )
					$count = $wpdb->get_var( $count_sql );
				else
					$count = 0;
			}
			return $count;
		} else {
			return 0;
		}

	}

	// HTML active class for the currently selected tab on the Store Exporter screen
	function woo_ce_admin_active_tab( $tab_name = null, $tab = null ) {

		if( isset( $_GET['tab'] ) && !$tab )
			$tab = $_GET['tab'];
		else if( !isset( $_GET['tab'] ) && woo_ce_get_option( 'skip_overview', false ) )
			$tab = 'export';
		else
			$tab = 'overview';

		$output = '';
		if( isset( $tab_name ) && $tab_name ) {
			if( $tab_name == $tab )
				$output = ' nav-tab-active';
		}
		echo $output;

	}

	// HTML template for each tab on the Store Exporter screen
	function woo_ce_tab_template( $tab = '' ) {

		if( !$tab )
			$tab = 'overview';

		// Store Exporter Deluxe
		$woo_cd_exists = false;
		if( !function_exists( 'woo_cd_admin_init' ) ) {
			$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
			$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );
		} else {
			$woo_cd_exists = true;
		}
		$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/';

		switch( $tab ) {

			case 'overview':
				$skip_overview = woo_ce_get_option( 'skip_overview', false );
				break;

			case 'export':
				$export_type = ( isset( $_POST['dataset'] ) ? $_POST['dataset'] : 'products' );

				$products = woo_ce_return_count( 'products' );
				$categories = woo_ce_return_count( 'categories' );
				$tags = woo_ce_return_count( 'tags' );
				$orders = woo_ce_return_count( 'orders' );
				$coupons = woo_ce_return_count( 'coupons' );
				$customers = woo_ce_return_count( 'customers' );

				if( $product_fields = woo_ce_get_product_fields() ) {
					foreach( $product_fields as $key => $product_field ) {
						if( !isset( $product_fields[$key]['disabled'] ) )
							$product_fields[$key]['disabled'] = 0;
					}
					$args = array(
						'hide_empty' => 1
					);
					$product_categories = woo_ce_get_product_categories( $args );
					$args = array(
						'hide_empty' => 1
					);
					$product_tags = woo_ce_get_product_tags( $args );
					$product_statuses = get_post_statuses();
					$product_statuses['trash'] = __( 'Trash', 'woo_ce' );
					$product_types = woo_ce_get_product_types();
					$product_orderby = woo_ce_get_option( 'product_orderby', 'ID' );
					$product_order = woo_ce_get_option( 'product_order', 'DESC' );
				}
				if( $category_fields = woo_ce_get_category_fields() ) {
					$category_orderby = woo_ce_get_option( 'category_orderby', 'ID' );
					$category_order = woo_ce_get_option( 'category_order', 'DESC' );
				}
				if( $tag_fields = woo_ce_get_tag_fields() ) {
					$tag_orderby = woo_ce_get_option( 'tag_orderby', 'ID' );
					$tag_order = woo_ce_get_option( 'tag_order', 'DESC' );
				}
				$order_fields = woo_ce_get_order_fields();
				$customer_fields = woo_ce_get_customer_fields();
				$coupon_fields = woo_ce_get_coupon_fields();

				// Export options
				$upsell_formatting = woo_ce_get_option( 'upsell_formatting', 1 );
				$crosssell_formatting = woo_ce_get_option( 'crosssell_formatting', 1 );
				$limit_volume = woo_ce_get_option( 'limit_volume' );
				$offset = woo_ce_get_option( 'offset' );
				break;

			case 'archive':
				if( isset( $_GET['deleted'] ) ) {
					$message = __( 'Archived export has been deleted.', 'woo_ce' );
					woo_ce_admin_notice( $message );
				}
				if( $files = woo_ce_get_archive_files() ) {
					foreach( $files as $key => $file )
						$files[$key] = woo_ce_get_archive_file( $file );
				}
				break;

			case 'settings':
				$export_filename = woo_ce_get_option( 'export_filename', 'woo-export_%dataset%-%date%.csv' );
				$delete_csv = woo_ce_get_option( 'delete_csv', 0 );
				$timeout = woo_ce_get_option( 'timeout', 0 );
				$encoding = woo_ce_get_option( 'encoding', 'UTF-8' );
				$bom = woo_ce_get_option( 'bom', 1 );
				$delimiter = woo_ce_get_option( 'delimiter', ',' );
				$category_separator = woo_ce_get_option( 'category_separator', '|' );
				$escape_formatting = woo_ce_get_option( 'escape_formatting', 'all' );
				$date_format = woo_ce_get_option( 'date_format', 'd/m/Y' );
				$file_encodings = ( function_exists( 'mb_list_encodings' ) ? mb_list_encodings() : false );
				break;

			case 'tools':
				// Product Importer Deluxe
				if( function_exists( 'woo_pd_init' ) ) {
					$woo_pd_url = add_query_arg( 'page', 'woo_pd' );
					$woo_pd_target = false;
				} else {
					$woo_pd_url = 'http://www.visser.com.au/woocommerce/plugins/product-importer-deluxe/';
					$woo_pd_target = ' target="_blank"';
				}
				break;

		}
		if( $tab )
			include_once( WOO_CE_PATH . 'templates/admin/woo-admin_ce-export_' . $tab . '.php' );

	}

	function woo_ce_export_options_export_format() {

		ob_start(); ?>
<tr>
	<th>
		<label><?php _e( 'Export format', 'woo_ce' ); ?></label>
	</th>
	<td>
		<label><input type="radio" name="export_format" value="csv"<?php checked( 'csv', 'csv' ); ?> /> <?php _e( 'CSV', 'woo_ce' ); ?> <span class="description"><?php _e( '(Comma separated values)', 'woo_ce' ); ?></span></label><br />
		<label><input type="radio" name="export_format" value="xml" disabled="disabled" /> <?php _e( 'XML', 'woo_ce' ); ?> <span class="description"><?php _e( '(EXtensible Markup Language)', 'woo_ce' ); ?></label>
		<p class="description"><?php _e( 'Adjust the export format to generate different export file formats.', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
		ob_end_flush();

	}

	// Displays a HTML notice where the memory allocated to WordPress falls below 64MB
	function woo_ce_memory_prompt() {

		if( !woo_ce_get_option( 'dismiss_memory_prompt', 0 ) ) {
			$memory_limit = (int)( ini_get( 'memory_limit' ) );
			$minimum_memory_limit = 64;
			if( $memory_limit < $minimum_memory_limit ) {
				$memory_url = add_query_arg( 'action', 'dismiss_memory_prompt' );
				$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';
				$message = sprintf( __( 'We recommend setting memory to at least %dMB, your site has only %dMB allocated to it. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'woo_ce' ), $minimum_memory_limit, $memory_limit, $troubleshooting_url ) . '<span style="float:right;"><a href="' . $memory_url . '">' . __( 'Dismiss', 'woo_ce' ) . '</a></span>';
				woo_ce_admin_notice( $message, 'error' );
			}
		}

	}

	// Displays a HTML notice when a WordPress or Store Exporter error is encountered
	function woo_ce_fail_notices() {

		woo_ce_memory_prompt();
		$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';
		if( isset( $_GET['failed'] ) ) {
			$message = '';
			if( isset( $_GET['message'] ) )
				$message = urldecode( $_GET['message'] );
			if( $message )
				$message = __( 'A WordPress or server error caused the exporter to fail, the exporter was provided with a reason: ', 'woo_ce' ) . '<em>' . $message . '</em>' . ' (<a href="' . $troubleshooting_url . '" target="_blank">' . __( 'Need help?', 'woo_ce' ) . '</a>)';
			else
				$message = __( 'A WordPress or server error caused the exporter to fail, no reason was provided, please get in touch so we can reproduce and resolve this.', 'woo_ce' ) . ' (<a href="' . $troubleshooting_url . '" target="_blank">' . __( 'Need help?', 'woo_ce' ) . '</a>)';
			woo_ce_admin_notice( $message, 'error' );
		}
		if( isset( $_GET['empty'] ) ) {
			$message = __( 'No export entries were found, please try again with different export filters.', 'woo_ce' );
			woo_ce_admin_notice( $message, 'error' );
		}
		if( get_transient( WOO_CE_PREFIX . '_running' ) ) {
			$message = __( 'A WordPress or server error caused the exporter to fail with a blank screen, this is either a memory or timeout issue, please get in touch so we can reproduce and resolve this.', 'woo_ce' ) . ' (<a href="' . $troubleshooting_url . '" target="_blank">' . __( 'Need help?', 'woo_ce' ) . '</a>)';
			woo_ce_admin_notice( $message, 'error' );
			delete_transient( WOO_CE_PREFIX . '_running' );
		}

	}

	// Returns a list of archived exports
	function woo_ce_get_archive_files() {

		$post_type = 'attachment';
		$meta_key = '_woo_export_type';
		$args = array(
			'post_type' => $post_type,
			'post_mime_type' => 'text/csv',
			'meta_key' => $meta_key,
			'meta_value' => null,
			'posts_per_page' => -1,
			'cache_results' => false,
			'no_found_rows' => false
		);
		if( isset( $_GET['filter'] ) ) {
			$filter = $_GET['filter'];
			if( !empty( $filter ) )
				$args['meta_value'] = $filter;
		}
		$files = get_posts( $args );
		return $files;

	}

	// Returns an archived export with additional details
	function woo_ce_get_archive_file( $file = '' ) {

		$wp_upload_dir = wp_upload_dir();
		$file->export_type = get_post_meta( $file->ID, '_woo_export_type', true );
		$file->export_type_label = woo_ce_export_type_label( $file->export_type );
		if( empty( $file->export_type ) )
			$file->export_type = __( 'Unassigned', 'woo_ce' );
		if( empty( $file->guid ) )
			$file->guid = $wp_upload_dir['url'] . '/' . basename( $file->post_title );
		$file->post_mime_type = get_post_mime_type( $file->ID );
		if( !$file->post_mime_type )
			$file->post_mime_type = __( 'N/A', 'woo_ce' );
		$file->media_icon = wp_get_attachment_image( $file->ID, array( 80, 60 ), true );
		if( $author_name = get_user_by( 'id', $file->post_author ) )
			$file->post_author_name = $author_name->display_name;
		$t_time = strtotime( $file->post_date, current_time( 'timestamp' ) );
		$time = get_post_time( 'G', true, $file->ID, false );
		if( ( abs( $t_diff = time() - $time ) ) < 86400 )
			$file->post_date = sprintf( __( '%s ago' ), human_time_diff( $time ) );
		else
			$file->post_date = mysql2date( __( 'Y/m/d' ), $file->post_date );
		unset( $author_name, $t_time, $time );
		return $file;

	}

	// HTML template for displaying the current export type filter on the Archives screen
	function woo_ce_archives_quicklink_current( $current = '' ) {

		$output = '';
		if( isset( $_GET['filter'] ) ) {
			$filter = $_GET['filter'];
			if( $filter == $current )
				$output = ' class="current"';
		} else if( $current == 'all' ) {
			$output = ' class="current"';
		}
		echo $output;

	}

	// HTML template for displaying the number of each export type filter on the Archives screen
	function woo_ce_archives_quicklink_count( $type = '' ) {

		$output = '0';
		$post_type = 'attachment';
		$meta_key = '_woo_export_type';
		$args = array(
			'post_type' => $post_type,
			'meta_key' => $meta_key,
			'meta_value' => null,
			'numberposts' => -1,
			'cache_results' => false,
			'no_found_rows' => false
		);
		if( $type )
			$args['meta_value'] = $type;
		if( $posts = get_posts( $args ) )
			$output = count( $posts );
		echo $output;

	}

	/* End of: WordPress Administration */

}

/* Start of: Common */

// Export process for CSV file
function woo_ce_export_dataset( $export_type, &$output = null ) {

	global $export;

	$separator = $export->delimiter;
	$export->columns = array();
	$export->total_rows = 0;
	$export->total_columns = 0;
	set_transient( WOO_CE_PREFIX . '_running', time(), woo_ce_get_option( 'timeout', MINUTE_IN_SECONDS ) );

	switch( $export_type ) {

		// Products
		case 'products':
			$fields = woo_ce_get_product_fields( 'summary' );
			if( $export->fields = array_intersect_assoc( $fields, $export->fields ) ) {
				foreach( $export->fields as $key => $field )
					$export->columns[] = woo_ce_get_product_field( $key );
			}
			$export->data_memory_start = woo_ce_current_memory_usage();
			if( $products = woo_ce_get_products( $export->args ) ) {
				$export->total_rows = count( $products );
				$export->total_columns = $size = count( $export->columns );
				if( $export->export_format == 'csv' ) {
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
					}
				}
				$weight_unit = get_option( 'woocommerce_weight_unit' );
				$dimension_unit = get_option( 'woocommerce_dimension_unit' );
				$height_unit = $dimension_unit;
				$width_unit = $dimension_unit;
				$length_unit = $dimension_unit;
				if( !empty( $export->fields ) ) {
					foreach( $products as $product ) {

						if( $export->export_format == 'xml' )
							$child = $output->addChild( substr( $export->type, 0, -1 ) );

						$product = woo_ce_get_product_data( $product, $export->args );
						foreach( $export->fields as $key => $field ) {
							if( isset( $product->$key ) ) {
								if( is_array( $field ) ) {
									foreach( $field as $array_key => $array_value ) {
										if( !is_array( $array_value ) ) {
											if( $export->export_format == 'csv' )
												$output .= woo_ce_escape_csv_value( $array_value, $export->delimiter, $export->escape_formatting );
											else if( $export->export_format == 'xml' )
												$child->addChild( $array_key, htmlspecialchars( $array_value ) );
										}
									}
								} else {
									if( $export->export_format == 'csv' )
										$output .= woo_ce_escape_csv_value( $product->$key, $export->delimiter, $export->escape_formatting );
									else if( $export->export_format == 'xml' )
										$child->addChild( $key, htmlspecialchars( $product->$key ) );
								}
							}
							if( $export->export_format == 'csv' )
								$output .= $separator;
						}

						if( $export->export_format == 'csv' )
							$output = substr( $output, 0, -1 ) . "\n";
					}
				}
				unset( $products, $product );
			}
			$export->data_memory_end = woo_ce_current_memory_usage();
			break;

		// Categories
		case 'categories':
			$fields = woo_ce_get_category_fields( 'summary' );
			if( $export->fields = array_intersect_assoc( $fields, $export->fields ) ) {
				foreach( $export->fields as $key => $field )
					$export->columns[] = woo_ce_get_category_field( $key );
			}
			$export->data_memory_start = woo_ce_current_memory_usage();
			$category_args = array(
				'orderby' => ( isset( $export->args['category_orderby'] ) ? $export->args['category_orderby'] : 'ID' ),
				'order' => ( isset( $export->args['category_order'] ) ? $export->args['category_order'] : 'ASC' ),
			);
			if( $categories = woo_ce_get_product_categories( $category_args ) ) {
				$export->total_rows = count( $categories );
				$export->total_columns = $size = count( $export->columns );
				if( $export->export_format == 'csv' ) {
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
					}
				}
				if( !empty( $export->fields ) ) {
					foreach( $categories as $category ) {

						if( $export->export_format == 'xml' )
							$child = $output->addChild( str_replace( 'ies', 'y', $export->type ) );

						foreach( $export->fields as $key => $field ) {
							if( isset( $category->$key ) ) {
								if( $export->export_format == 'csv' )
									$output .= woo_ce_escape_csv_value( $category->$key, $export->delimiter, $export->escape_formatting );
								else if( $export->export_format == 'xml' )
									$child->addChild( $key, htmlspecialchars( $category->$key ) );
							}
							if( $export->export_format == 'csv' )
								$output .= $separator;
						}
						if( $export->export_format == 'csv' )
							$output = substr( $output, 0, -1 ) . "\n";
					}
				}
				unset( $categories, $category );
			}
			$export->data_memory_end = woo_ce_current_memory_usage();
			break;

		// Tags
		case 'tags':
			$fields = woo_ce_get_tag_fields( 'summary' );
			if( $export->fields = array_intersect_assoc( $fields, $export->fields ) ) {
				foreach( $export->fields as $key => $field )
					$export->columns[] = woo_ce_get_tag_field( $key );
			}
			$export->data_memory_start = woo_ce_current_memory_usage();
			$tag_args = array(
				'orderby' => ( isset( $export->args['tag_orderby'] ) ? $export->args['tag_orderby'] : 'ID' ),
				'order' => ( isset( $export->args['tag_order'] ) ? $export->args['tag_order'] : 'ASC' ),
			);
			if( $tags = woo_ce_get_product_tags( $tag_args ) ) {
				$export->total_rows = count( $tags );
				$export->total_columns = $size = count( $export->columns );
				if( $export->export_format == 'csv' ) {
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$output .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
					}
				}
				if( !empty( $export->fields ) ) {
					foreach( $tags as $tag ) {

						if( $export->export_format == 'xml' )
							$child = $output->addChild( substr( $export->type, 0, -1 ) );

						foreach( $export->fields as $key => $field ) {
							if( isset( $tag->$key ) ) {
								if( $export->export_format == 'csv' )
									$output .= woo_ce_escape_csv_value( $tag->$key, $export->delimiter, $export->escape_formatting );
								else if( $export->export_format == 'xml' )
									$child->addChild( $key, htmlspecialchars( $tag->$key ) );
							}
							if( $export->export_format == 'csv' )
								$output .= $separator;
						}
						if( $export->export_format == 'csv' )
							$output = substr( $output, 0, -1 ) . "\n";
					}
				}
				unset( $tags, $tag );
			}
			$export->data_memory_end = woo_ce_current_memory_usage();
			break;

		// Orders
		case 'orders':
		// Customers
		case 'customers':
		// Coupons
		case 'coupons':
			if( $export->export_format == 'csv' )
				$output = apply_filters( 'woo_ce_export_dataset', $export->type );
			else if( $export->export_format == 'xml' )
				$output = apply_filters( 'woo_ce_export_dataset', $export->type, $output );
			break;

	}
	// Export completed successfully
	delete_transient( WOO_CE_PREFIX . '_running' );
	// Check that the export file is populated, export columns have been assigned and rows counted
	if( $output && $export->total_rows && !empty( $export->columns ) ) {
		if( $export->export_format == 'csv' ) {
			if( $export->bom ) {
				// $output .= chr(239) . chr(187) . chr(191) . '';
				$output = "\xEF\xBB\xBF" . $output;
			}
			$output = woo_ce_file_encoding( $output );
		}
		if( WOO_CE_DEBUG )
			set_transient( WOO_CE_PREFIX . '_debug_log', base64_encode( $output ), woo_ce_get_option( 'timeout', MINUTE_IN_SECONDS ) );
		else
			return $output;
	}

}

// Returns the Post object of the export file saved as an attachment to the WordPress Media library
function woo_ce_save_file_attachment( $filename = '', $post_mime_type = 'text/csv' ) {

	$output = 0;
	if( !empty( $filename ) ) {
		$post_type = 'woo-export';
		$args = array(
			'post_title' => $filename,
			'post_type' => $post_type,
			'post_mime_type' => $post_mime_type
		);
		if( $post_ID = wp_insert_attachment( $args, $filename ) )
			$output = $post_ID;
	}
	return $output;

}

// Updates the GUID of the export file attachment to match the correct file URL
function woo_ce_save_file_guid( $post_ID, $export_type, $upload_url = '' ) {

	add_post_meta( $post_ID, '_woo_export_type', $export_type );
	if( !empty( $upload_url ) ) {
		$args = array(
			'ID' => $post_ID,
			'guid' => $upload_url
		);
		wp_update_post( $args );
	}

}

// Save critical export details against the archived export
function woo_ce_save_file_details( $post_ID ) {

	global $export;

	add_post_meta( $post_ID, '_woo_start_time', $export->start_time );
	add_post_meta( $post_ID, '_woo_idle_memory_start', $export->idle_memory_start );
	add_post_meta( $post_ID, '_woo_columns', $export->total_columns );
	add_post_meta( $post_ID, '_woo_rows', $export->total_rows );
	add_post_meta( $post_ID, '_woo_data_memory_start', $export->data_memory_start );
	add_post_meta( $post_ID, '_woo_data_memory_end', $export->data_memory_end );

}

// Update detail of existing archived export
function woo_ce_update_file_detail( $post_ID, $detail, $value ) {

	if( strstr( $detail, '_woo_' ) !== false )
		update_post_meta( $post_ID, $detail, $value );

}

// Returns a list of allowed Export type statuses, can be overridden on a per-Export type basis
function woo_ce_post_statuses( $extra_status = array(), $override = false ) {

	$output = array(
		'publish',
		'pending',
		'draft',
		'future',
		'private',
		'trash'
	);
	if( $override ) {
		$output = $extra_status;
	} else {
		if( $extra_status )
			$output = array_merge( $output, $extra_status );
	}
	return $output;

}

// Returns a list of WordPress User Roles
function woo_ce_get_user_roles() {

	global $wp_roles;
	$user_roles = $wp_roles->roles;
	return $user_roles;

}

function woo_ce_add_missing_mime_type( $mime_types = array() ) {

	// Add CSV mime type if it has been removed
	if( !isset( $mime_types['csv'] ) )
		$mime_types['csv'] = 'text/csv';
	return $mime_types;

}
add_filter( 'upload_mimes', 'woo_ce_add_missing_mime_type', 10, 1 );

if( !function_exists( 'woo_ce_current_memory_usage' ) ) {
	function woo_ce_current_memory_usage() {

		$output = '';
		if( function_exists( 'memory_get_usage' ) )
			$output = round( memory_get_usage( true ) / 1024 / 1024, 2 );
		return $output;

	}
}

function woo_ce_get_option( $option = null, $default = false, $allow_empty = false ) {

	$output = '';
	if( isset( $option ) ) {
		$separator = '_';
		$output = get_option( WOO_CE_PREFIX . $separator . $option, $default );
		if( $allow_empty == false && ( $output == false || $output == '' ) )
			$output = $default;
	}
	return $output;

}

function woo_ce_update_option( $option = null, $value = null ) {

	$output = false;
	if( isset( $option ) && isset( $value ) ) {
		$separator = '_';
		$output = update_option( WOO_CE_PREFIX . $separator . $option, $value );
	}
	return $output;

}

/* End of: Common */
?>