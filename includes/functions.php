<?php
include_once( $woo_ce['abspath'] . '/includes/functions-products.php' );
include_once( $woo_ce['abspath'] . '/includes/functions-categories.php' );
include_once( $woo_ce['abspath'] . '/includes/functions-tags.php' );
include_once( $woo_ce['abspath'] . '/includes/functions-orders.php' );
include_once( $woo_ce['abspath'] . '/includes/functions-coupons.php' );
include_once( $woo_ce['abspath'] . '/includes/functions-customers.php' );

if( is_admin() ) {

	/* Start of: WordPress Administration */

	// Add Store Export to WordPress Administration menu
	function woo_ce_admin_menu() {

		add_submenu_page( 'woocommerce', __( 'Store Exporter', 'woo_ce' ), __( 'Store Export', 'woo_ce' ), 'manage_woocommerce', 'woo_ce', 'woo_ce_html_page' );

	}
	add_action( 'admin_menu', 'woo_ce_admin_menu' );

	// HTML template header on Store Exporter screen
	function woo_ce_template_header( $title = '', $icon = 'woocommerce' ) {

		global $woo_ce;

		if( $title )
			$output = $title;
		else
			$output = $woo_ce['menu']; ?>
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

		global $woo_ce;

		$output = '';
		$show = true;
		if( function_exists( 'woo_vl_we_love_your_plugins' ) ) {
			if( in_array( $woo_ce['dirname'], woo_vl_we_love_your_plugins() ) )
				$show = false;
		}
		if( function_exists( 'woo_cd_admin_init' ) )
			$show = false;
		if( $show ) {
			$donate_url = 'http://www.visser.com.au/#donations';
			$rate_url = 'http://wordpress.org/support/view/plugin-reviews/' . $woo_ce['dirname'];
			$output = '
	<div id="support-donate_rate" class="support-donate_rate">
		<p>' . sprintf( __( '<strong>Like this Plugin?</strong> %s and %s.', 'woo_ce' ), '<a href="' . $donate_url . '" target="_blank">' . __( 'Donate to support this Plugin', 'woo_ce' ) . '</a>', '<a href="' . add_query_arg( array( 'rate' => '5' ), $rate_url ) . '#postform" target="_blank">rate / review us on WordPress.org</a>' ) . '</p>
	</div>
';
		}
		echo $output;

	}

	// Saves the state of Export fields for next export
	function woo_ce_save_fields( $dataset, $fields = array() ) {

		if( $dataset && !empty( $fields ) ) {
			$type = $dataset[0];
			woo_ce_update_option( $type . '_fields', $fields );
		}

	}

	// File output header for CSV file
	function woo_ce_generate_csv_header( $dataset = '' ) {

		global $export;

		$filename = woo_ce_generate_csv_filename( $dataset );
		if( $filename ) {
			header( sprintf( 'Content-Encoding: %s', $export->encoding ) );
			header( sprintf( 'Content-Type: text/csv; charset=%s', $export->encoding ) );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );
		}

	}

	// Function to generate filename of CSV file based on the Export type
	function woo_ce_generate_csv_filename( $dataset = '' ) {

		$date = date( 'Ymd' );
		$output = sprintf( 'woo-export_default-%s.csv', $date );
		if( $dataset ) {
			$filename = sprintf( 'woo-export_%s-%s.csv', $dataset, $date );
			if( $filename )
				$output = $filename;
		}
		return $output;

	}

	function woo_ce_unload_export_global() {

		global $export;
		unset( $export );

	}

	// Add Store Export to filter types on the WordPress Media screen
	function woo_ce_add_post_mime_type( $post_mime_types = array() ) {

		$post_mime_types['text/csv'] = array( __( 'Store Exports', 'woo_ce' ), __( 'Manage Store Exports', 'woo_ce' ), _n_noop( 'Store Export <span class="count">(%s)</span>', 'Store Exports <span class="count">(%s)</span>' ) );
		return $post_mime_types;

	}
	add_filter( 'post_mime_types', 'woo_ce_add_post_mime_type' );

	// In-line display of CSV file and export details when viewed via WordPress Media screen
	function woo_ce_read_csv_file( $post = null ) {

		global $woo_ce;

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
		}
		if( $contents )
			include_once( $woo_ce['abspath'] . '/templates/admin/woo-admin_ce-media_csv_file.php' );

		$dataset = get_post_meta( $post->ID, '_woo_export_type', true );
		$columns = get_post_meta( $post->ID, '_woo_columns', true );
		$rows = get_post_meta( $post->ID, '_woo_rows', true );
		$start_time = get_post_meta( $post->ID, '_woo_start_time', true );
		$end_time = get_post_meta( $post->ID, '_woo_end_time', true );
		$idle_memory_start = get_post_meta( $post->ID, '_woo_idle_memory_start', true );
		$data_memory_start = get_post_meta( $post->ID, '_woo_data_memory_start', true );
		$data_memory_end = get_post_meta( $post->ID, '_woo_data_memory_end', true );
		$idle_memory_end = get_post_meta( $post->ID, '_woo_idle_memory_end', true );
		include_once( $woo_ce['abspath'] . '/templates/admin/woo-admin_ce-media_export_details.php' );

	}
	add_action( 'edit_form_after_editor', 'woo_ce_read_csv_file' );

	if( !function_exists( 'woo_ce_current_memory_usage' ) ) {
		function woo_ce_current_memory_usage() {

			$output = '';
			if( function_exists( 'memory_get_usage' ) )
				$output = round( memory_get_usage() / 1024 / 1024, 2 );
			return $output;

		}
	}

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
	function woo_ce_return_count( $dataset ) {

		global $wpdb;

		$count_sql = null;
		switch( $dataset ) {

			case 'products':
				$post_type = 'product';
				$count = wp_count_posts( $post_type );
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
				$count = wp_count_posts( $post_type );
				$exclude_post_types = array( 'auto-draft' );
				if( woo_ce_count_object( $count, $exclude_post_types ) > 100 ) {
					$count = sprintf( '~%s *', woo_ce_count_object( $count, $exclude_post_types ) );
				} else {
					$count = woo_ce_count_object( $count, $exclude_post_types );
				}
				break;

			case 'customers':
				$post_type = 'shop_order';
				$count = wp_count_posts( $post_type );
				$exclude_post_types = array( 'auto-draft' );
				if( woo_ce_count_object( $count, $exclude_post_types ) > 100 ) {
					$count = sprintf( '~%s *', woo_ce_count_object( $count, $exclude_post_types ) );
				} else {
					$count = 0;
					$args = array(
						'post_type' => $post_type,
						'numberposts' => -1,
						'post_status' => woo_ce_post_statuses(),
						'cache_results' => false,
						'tax_query' => array(
							array(
								'taxonomy' => 'shop_order_status',
								'field' => 'slug',
								'terms' => array( 'pending', 'on-hold', 'processing', 'completed' )
							)
						)
					);
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
				break;

			case 'coupons':
				$post_type = 'shop_coupon';
				$count = wp_count_posts( $post_type );
				break;

		}
		if( isset( $count ) || $count_sql ) {
			if( isset( $count ) ) {
				$count = woo_ce_count_object( $count );
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

	// Export process for CSV file
	function woo_ce_export_dataset( $dataset, $args = array() ) {

		global $wpdb, $woo_ce, $export;

		$csv = '';
		if( $export->bom )
			$csv .= chr(239) . chr(187) . chr(191) . '';
		$separator = $export->delimiter;
		$export->args = $args;
		foreach( $dataset as $datatype ) {

			$csv = '';
			switch( $datatype ) {

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
						$size = count( $export->columns );
						$export->total_columns = $size;
						for( $i = 0; $i < $size; $i++ ) {
							if( $i == ( $size - 1 ) )
								$csv .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
							else
								$csv .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
						}
						unset( $export->columns );
						$weight_unit = get_option( 'woocommerce_weight_unit' );
						$dimension_unit = get_option( 'woocommerce_dimension_unit' );
						$height_unit = $dimension_unit;
						$width_unit = $dimension_unit;
						$length_unit = $dimension_unit;
						foreach( $products as $product ) {
							foreach( $export->fields as $key => $field ) {
								if( isset( $product->$key ) ) {
									if( is_array( $field ) ) {
										foreach( $field as $array_key => $array_value ) {
											if( !is_array( $array_value ) )
												$csv .= woo_ce_escape_csv_value( $array_value, $export->delimiter, $export->escape_formatting );
										}
									} else {
										$csv .= woo_ce_escape_csv_value( $product->$key, $export->delimiter, $export->escape_formatting );
									}
								}
								$csv .= $separator;
							}
							$csv = substr( $csv, 0, -1 ) . "\n";
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
					if( $categories = woo_ce_get_product_categories( $export->args ) ) {
						$export->total_rows = count( $categories );
						$size = count( $export->columns );
						$export->total_columns = $size;
						for( $i = 0; $i < $size; $i++ ) {
							if( $i == ( $size - 1 ) )
								$csv .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
							else
								$csv .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
						}
						unset( $export->columns );
						foreach( $categories as $category ) {
							foreach( $export->fields as $key => $field ) {
								if( isset( $category->$key ) )
									$csv .= woo_ce_escape_csv_value( woo_ce_clean_html( $category->$key ), $export->delimiter, $export->escape_formatting );
								$csv .= $separator;
							}
							$csv = substr( $csv, 0, -1 ) . "\n";
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
					if( $tags = woo_ce_get_product_tags( $export->args ) ) {
						$export->total_rows = count( $tags );
						$size = count( $export->columns );
						$export->total_columns = $size;
						for( $i = 0; $i < $size; $i++ ) {
							if( $i == ( $size - 1 ) )
								$csv .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
							else
								$csv .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
						}
						unset( $export->columns );
						foreach( $tags as $tag ) {
							foreach( $export->fields as $key => $field ) {
								if( isset( $tag->$key ) )
									$csv .= woo_ce_escape_csv_value( woo_ce_clean_html( $tag->$key ), $export->delimiter, $export->escape_formatting );
								$csv .= $separator;
							}
							$csv = substr( $csv, 0, -1 ) . "\n";
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
					$csv = apply_filters( 'woo_ce_export_dataset', $datatype, $export );
					break;

			}
			if( $csv ) {
				$csv = utf8_decode( $csv );
				if( isset( $woo_ce['debug'] ) && $woo_ce['debug'] )
					$woo_ce['debug_log'] = $csv;
				else
					return $csv;
			} else {
				return false;
			}

		}

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

	// HTML active class for the currently selected tab on the Store Exporter screen
	function woo_ce_admin_active_tab( $tab_name = null, $tab = null ) {

		if( isset( $_GET['tab'] ) && !$tab )
			$tab = $_GET['tab'];
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

		global $woo_ce;

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

			case 'export':
				$dataset = 'products';
				if( isset( $_POST['dataset'] ) )
					$dataset = $_POST['dataset'];

				$products = woo_ce_return_count( 'products' );
				$categories = woo_ce_return_count( 'categories' );
				$tags = woo_ce_return_count( 'tags' );
				$orders = woo_ce_return_count( 'orders' );
				$coupons = woo_ce_return_count( 'coupons' );
				$customers = woo_ce_return_count( 'customers' );

				$product_fields = woo_ce_get_product_fields();
				if( $product_fields ) {
					foreach( $product_fields as $key => $product_field ) {
						if( !isset( $product_fields[$key]['disabled'] ) )
							$product_fields[$key]['disabled'] = 0;
					}
					$product_categories = woo_ce_get_product_categories();
					$product_tags = woo_ce_get_product_tags();
					$product_statuses = get_post_statuses();
					$product_statuses['trash'] = __( 'Trash', 'woo_ce' );
					$product_types = woo_ce_get_product_types();
					$product_orderby = woo_ce_get_option( 'product_orderby', 'ID' );
					$product_order = woo_ce_get_option( 'product_order', 'DESC' );
				}
				$category_fields = woo_ce_get_category_fields();
				if( $category_fields ) {
					$category_orderby = woo_ce_get_option( 'category_orderby', 'ID' );
					$category_order = woo_ce_get_option( 'category_order', 'DESC' );
				}
				$tag_fields = woo_ce_get_tag_fields();
				if( $tag_fields ) {
					$tag_orderby = woo_ce_get_option( 'tag_orderby', 'ID' );
					$tag_order = woo_ce_get_option( 'tag_order', 'DESC' );
				}
				$order_fields = woo_ce_get_order_fields();
				$customer_fields = woo_ce_get_customer_fields();
				$coupon_fields = woo_ce_get_coupon_fields();

				$delimiter = woo_ce_get_option( 'delimiter', ',' );
				$category_separator = woo_ce_get_option( 'category_separator', '|' );
				$bom = woo_ce_get_option( 'bom', 1 );
				$escape_formatting = woo_ce_get_option( 'escape_formatting', 'all' );
				$limit_volume = woo_ce_get_option( 'limit_volume' );
				$offset = woo_ce_get_option( 'offset' );
				$timeout = woo_ce_get_option( 'timeout', 0 );
				$delete_csv = woo_ce_get_option( 'delete_csv', 0 );
				$file_encodings = false;
				if( function_exists( 'mb_list_encodings' ) )
					$file_encodings = mb_list_encodings();
				$encoding = woo_ce_get_option( 'encoding', 'UTF-8' );
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

			case 'archive':
				$files = woo_ce_get_archive_files();
				if( $files ) {
					foreach( $files as $key => $file )
						$files[$key] = woo_ce_get_archive_file( $file );
				}
				break;

		}
		if( $tab )
			include_once( $woo_ce['abspath'] . '/templates/admin/woo-admin_ce-export_' . $tab . '.php' );

	}

	// Returns the Post object of the CSV file saved as an attachment to the WordPress Media library
	function woo_ce_save_csv_file_attachment( $filename = '' ) {

		$output = 0;
		if( !empty( $filename ) ) {
			$post_type = 'woo-export';
			$object = array(
				'post_title' => $filename,
				'post_type' => $post_type,
				'post_mime_type' => 'text/csv'
			);
			$post_ID = wp_insert_attachment( $object, $filename );
			if( $post_ID )
				$output = $post_ID;
		}
		return $output;

	}

	// Updates the GUID of the CSV file attachment to match the correct CSV URL
	function woo_ce_save_csv_file_guid( $post_ID, $export_type, $upload_url = '' ) {

		add_post_meta( $post_ID, '_woo_export_type', $export_type );
		if( !empty( $upload_url ) ) {
			$object = array(
				'ID' => $post_ID,
				'guid' => $upload_url
			);
			wp_update_post( $object );
		}

	}

	// Save critical export details against the archived export
	function woo_ce_save_csv_file_details( $post_ID ) {

		global $export;

		add_post_meta( $post_ID, '_woo_start_time', $export->start_time );
		add_post_meta( $post_ID, '_woo_idle_memory_start', $export->idle_memory_start );
		add_post_meta( $post_ID, '_woo_columns', $export->total_columns );
		add_post_meta( $post_ID, '_woo_rows', $export->total_rows );
		add_post_meta( $post_ID, '_woo_data_memory_start', $export->data_memory_start );
		add_post_meta( $post_ID, '_woo_data_memory_end', $export->data_memory_end );

	}

	// Update detail of existing archived export
	function woo_ce_update_csv_file_detail( $post_ID, $detail, $value ) {

		if( strstr( $detail, '_woo_' ) !== false )
			update_post_meta( $post_ID, $detail, $value );

	}

	// Returns a list of WordPress User Roles
	function woo_ce_get_user_roles() {

		global $wp_roles;
		$user_roles = $wp_roles->roles;
		return $user_roles;

	}

	// Displays a HTML notice where the memory allocated to WordPress falls below 64MB
	function woo_ce_memory_prompt() {

		if( !woo_ce_get_option( 'dismiss_memory_prompt', 0 ) ) {
			$memory_limit = (int)( ini_get( 'memory_limit' ) );
			$minimum_memory_limit = 64;
			if( $memory_limit < $minimum_memory_limit ) {
				ob_start();
				$memory_url = add_query_arg( 'action', 'dismiss_memory_prompt' );
				$message = sprintf( __( 'We recommend setting memory to at least %dMB, your site has %dMB currently allocated. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'woo_ce' ), $minimum_memory_limit, $memory_limit, 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ); ?>
<div class="error settings-error">
	<p>
		<strong><?php echo $message; ?></strong>
		<span style="float:right;"><a href="<?php echo $memory_url; ?>"><?php _e( 'Dismiss', 'woo_ce' ); ?></a></span>
	</p>
</div>
<?php
				ob_end_flush();
			}
		}

	}

	// Displays a HTML notice when a WordPress or Store Exporter error is encountered
	function woo_ce_fail_notices() {

		$message = false;
		if( isset( $_GET['failed'] ) )
			$message = __( 'A WordPress error caused the exporter to fail, please get in touch.', 'woo_ce' );
		if( isset( $_GET['empty'] ) )
			$message = __( 'No export entries were found, please try again with different export filters.', 'woo_ce' );
		if( $message ) {
			ob_start(); ?>
<div class="updated settings-error">
	<p>
		<strong><?php echo $message; ?></strong>
	</p>
</div>
<?php
			ob_end_flush();
		}
	}

	// Returns a list of archived exports
	function woo_ce_get_archive_files() {

		$args = array(
			'post_type' => 'attachment',
			'post_mime_type' => 'text/csv',
			'meta_key' => '_woo_export_type',
			'meta_value' => null,
			'posts_per_page' => -1
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
		$author_name = get_user_by( 'id', $file->post_author );
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
		$args = array(
			'post_type' => 'attachment',
			'meta_key' => '_woo_export_type',
			'meta_value' => null,
			'numberposts' => -1
		);
		if( $type )
			$args['meta_value'] = $type;
		$posts = get_posts( $args );
		if( $posts )
			$output = count( $posts );
		echo $output;

	}

	/* End of: WordPress Administration */

}

/* Start of: Common */

function woo_ce_get_option( $option = null, $default = false ) {

	global $woo_ce;

	$output = '';
	if( isset( $option ) ) {
		$separator = '_';
		$output = get_option( $woo_ce['prefix'] . $separator . $option, $default );
	}
	return $output;

}

function woo_ce_update_option( $option = null, $value = null ) {

	global $woo_ce;

	$output = false;
	if( isset( $option ) && isset( $value ) ) {
		$separator = '_';
		$output = update_option( $woo_ce['prefix'] . $separator . $option, $value );
	}
	return $output;

}

/* End of: Common */
?>