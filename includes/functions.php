<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	/* WordPress Administration menu */
	function woo_ce_admin_menu() {

		add_submenu_page( 'woocommerce', __( 'Store Export', 'woo_ce' ), __( 'Store Export', 'woo_ce' ), 'manage_options', 'woo_ce', 'woo_ce_html_page' );

	}
	add_action( 'admin_menu', 'woo_ce_admin_menu' );

	function woo_ce_template_header( $title = '', $icon = 'tools' ) {

		global $woo_ce;

		if( $title )
			$output = $title;
		else
			$output = $woo_ce['menu'];
		$icon = woo_is_admin_icon_valid( $icon ); ?>
<div class="wrap">
	<div id="icon-<?php echo $icon; ?>" class="icon32"><br /></div>
	<h2>
		<?php echo $output; ?>
		<a href="<?php echo add_query_arg( 'tab', 'export' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'woo_ce' ); ?></a>
	</h2>
<?php
	}

	function woo_ce_template_footer() { ?>
</div>
<?php
	}

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

	function woo_ce_save_fields( $dataset, $fields = array() ) {

		if( $dataset && !empty( $fields ) ) {
			$type = $dataset[0];
			woo_ce_update_option( $type . '_fields', $fields );
		}

	}

	function woo_ce_generate_csv_header( $dataset = '' ) {

		$filename = woo_ce_generate_csv_filename( $dataset );
		if( $filename ) {
			header( 'Content-Encoding: UTF-8' );
			header( 'Content-type: application/csv; charset=UTF-8' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );
		}

	}

	function woo_ce_generate_csv_filename( $dataset = '' ) {

		$date = date( 'Ymd' );
		$output = 'woo-export_default-' . $date . '.csv';
		if( $dataset ) {
			$filename = 'woo-export_' . $dataset . '-' . $date . '.csv';
			if( $filename )
				$output = $filename;
		}
		return $output;

	}

	function woo_ce_orders_filter_by_date() {

		$current_month = date( 'F' );
		$last_month = date( 'F', mktime( 0, 0, 0, date( 'n' )-1, 1, date( 'Y' ) ) );
		$order_dates_from = '-';
		$order_dates_to = '-';

		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-date" /> <?php _e( 'Filter Orders by Order Date', 'woo_ce' ); ?></label></p>
<div id="export-orders-filters-date" class="separator">
	<ul>
		<li>
			<label><input type="radio" name="order_dates_filter" value="current_month" disabled="disabled" /> <?php _e( 'Current month', 'woo_ce' ); ?> (<?php echo $current_month; ?>)</label>
		</li>
		<li>
			<label><input type="radio" name="order_dates_filter" value="last_month" disabled="disabled" /> <?php _e( 'Last month', 'woo_ce' ); ?> (<?php echo $last_month; ?>)</label>
		</li>
		<li>
			<label><input type="radio" name="order_dates_filter" value="manual" disabled="disabled" /> <?php _e( 'Manual', 'woo_ce' ); ?></label>
			<div style="margin-top:0.2em;">
				<input type="text" size="10" maxlength="10" id="order_dates_from" name="order_dates_from" value="<?php echo $order_dates_from; ?>" class="text" disabled="disabled" /> to <input type="text" size="10" maxlength="10" id="order_dates_to" name="order_dates_to" value="<?php echo $order_dates_to; ?>" class="text" disabled="disabled" />
				<p class="description"><?php _e( 'Filter the dates of Orders to be included in the export. Default is the date of the first order to today.', 'woo_ce' ); ?></p>
			</div>
		</li>
	</ul>
</div>
<!-- #export-orders-filters-date -->
<?php
		ob_end_flush();

	}

	function woo_ce_orders_filter_by_customer() {

		ob_start(); ?>
<p><label for="order_customer"><?php _e( 'Filter Orders by Customer', 'woo_ce' ); ?></label></p>
<div id="export-orders-filters-date" class="separator">
	<select id="order_customer" name="order_customer" disabled="disabled">
		<option value=""><?php _e( 'Show all customers', 'woo_ce' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Filter Orders by Customer (unique e-mail address) to be included in the export. Default is to include all Orders.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-date -->
<?php
		ob_end_flush();

	}

	function woo_ce_orders_filter_by_status() {

		$order_statuses = woo_ce_get_order_statuses();
		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-status" /> <?php _e( 'Filter Orders by Order Status', 'wpsc_ce' ); ?></label></p>
<div id="export-orders-filters-status" class="separator">
	<ul>
<?php foreach( $order_statuses as $order_status ) { ?>
		<li><label><input type="checkbox" name="order_filter_status[<?php echo $order_status->name; ?>]" value="<?php echo $order_status->name; ?>" disabled="disabled" /> <?php echo ucfirst( $order_status->name ); ?></label></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the Order Status you want to filter exported Orders by. Default is to include all Order Status options.', 'wpsc_ce' ); ?></p>
</div>
<!-- #export-orders-filters-status -->
<?php
		ob_end_flush();

	}

	function woo_ce_add_post_mime_type( $post_mime_types = array() ) {

		$post_mime_types['text/csv'] = array( __( 'Store Exports', 'woo_ce' ), __( 'Manage Store Exports', 'woo_ce' ), _n_noop( 'Store Export <span class="count">(%s)</span>', 'Store Exports <span class="count">(%s)</span>' ) );
		return $post_mime_types;

	}
	add_filter( 'post_mime_types', 'woo_ce_add_post_mime_type' );

	function woo_ce_read_csv_file( $post = null ) {

		if( $post->post_type != 'attachment' )
			return false;

		if( $post->post_mime_type != 'text/csv' )
			return false;

		$filename = $post->post_name;
		$filepath = get_attached_file( $post->ID );
		if( file_exists( $filepath ) ) {
			$handle = fopen( $filepath, "r" );
			$contents = stream_get_contents( $handle );
			fclose( $handle );
		}
		if( $contents ) { ?>
	<div class="postbox-container">
		<div class="postbox">
			<h3 class="hndle"><?php _e( 'CSV File', 'woo_ce' ); ?></h3>
			<div class="inside">
				<textarea style="font:12px Consolas, Monaco, Courier, monospace; width:100%; height:200px;"><?php echo $contents; ?></textarea>
			</div>
			<!-- .inside -->
		</div>
		<!-- .postbox -->
	</div>
	<!-- .postbox-container -->
<?php
		}

	}
	add_action( 'edit_form_after_editor', 'woo_ce_read_csv_file' );

	function woo_ce_return_export_types() {

		$export_types = array();
		$export_types['products'] = __( 'Products', 'woo_ce' );
		$export_types['categories'] = __( 'Categories', 'woo_ce' );
		$export_types['tags'] = __( 'Tags', 'woo_ce' );
		$export_types['orders'] = __( 'Orders', 'woo_ce' );
		$export_types['customers'] = __( 'Customers', 'woo_ce' );
		$export_types['coupons'] = __( 'Coupons', 'woo_ce' );
		return $export_types;

	}

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

	function woo_ce_return_count( $dataset ) {

		global $wpdb;

		$count_sql = null;
		switch( $dataset ) {

			/* WooCommerce */

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
				break;

			case 'customers':
				$post_type = 'shop_order';
				$count = wp_count_posts( $post_type );
				if( woo_ce_count_object( $count ) > 100 ) {
						$count = '~' . woo_ce_count_object( $count ) . ' *';
				} else {
					$args = array(
						'post_type' => $post_type,
						'numberposts' => -1,
						'post_status' => woo_ce_post_statuses(),
						'tax_query' => array(
							array(
								'taxonomy' => 'shop_order_status',
								'field' => 'slug',
								'terms' => array( 'completed', 'processing', 'on-hold' )
							)
						)
					);
					$orders = get_posts( $args );
					if( $orders ) {
						$customers = array();
						foreach( $orders as $order ) {
							if( $order->email = get_post_meta( $order->ID, '_billing_email', true ) ) {
								if( !in_array( $order->email, $customers ) ) {
									$customers[$order->ID] = $order->email;
									$count++;
								}
							}
						}
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

	function woo_ce_count_object( $object = 0 ) {
	
		$count = 0;
		if( is_object( $object ) ) {
			foreach( $object as $key => $item )
				$count = $item + $count;
		} else {
			$count = $object;
		}
		return $count;

	}

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

				/* Products */
				case 'products':
					$fields = woo_ce_get_product_fields( 'summary' );
					$export->fields = array_intersect_assoc( $fields, $export->fields );
					if( $export->fields ) {
						foreach( $export->fields as $key => $field )
							$export->columns[] = woo_ce_get_product_field( $key );
					}
					$size = count( $export->columns );
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$csv .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$csv .= woo_ce_escape_csv_value( $export->columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
					}
					$products = woo_ce_get_products( $export->args );
					if( $products ) {
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
					break;

				/* Categories */
				case 'categories':
					$columns = array(
						__( 'Category', 'woo_ce' )
					);
					$size = count( $columns );
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$csv .= woo_ce_escape_csv_value( $columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$csv .= woo_ce_escape_csv_value( $columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
					}
					$categories = woo_ce_get_product_categories();
					if( $categories ) {
						foreach( $categories as $category ) {
							$csv .= 
								$category->name
								 . 
							"\n";
						}
						unset( $categories, $category );
					}
					break;

				/* Tags */
				case 'tags':
					$columns = array(
						__( 'Tags', 'woo_ce' )
					);
					$size = count( $columns );
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$csv .= woo_ce_escape_csv_value( $columns[$i], $export->delimiter, $export->escape_formatting ) . "\n";
						else
							$csv .= woo_ce_escape_csv_value( $columns[$i], $export->delimiter, $export->escape_formatting ) . $separator;
					}
					$tags = woo_ce_get_product_tags();
					if( $tags ) {
						foreach( $tags as $tag ) {
							$csv .= 
								$tag->name
								 . 
							"\n";
						}
						unset( $tags, $tag );
					}
					break;

				/* Orders */
				case 'orders':
				/* Customers */
				case 'customers':
				/* Coupons */
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

	/* Products */

	function woo_ce_get_products( $args = array() ) {

		$limit_volume = -1;
		$offset = 0;
		$product_categories = false;
		$product_tags = false;
		$product_status = false;
		if( $args ) {
			$limit_volume = $args['limit_volume'];
			$offset = $args['offset'];
			if( !empty( $args['product_categories'] ) )
				$product_categories = $args['product_categories'];
			if( !empty( $args['product_tags'] ) )
				$product_tags = $args['product_tags'];
			if( !empty( $args['product_status'] ) )
				$product_status = $args['product_status'];
		}
		$post_type = 'product';
		$args = array(
			'post_type' => $post_type,
			'numberposts' => $limit_volume,
			'offset' => $offset,
			'post_status' => woo_ce_post_statuses()
		);
		if( $product_categories ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $product_categories
				)
			);
		}
		if( $product_tags ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_tag',
					'field' => 'id',
					'terms' => $product_tags
				)
			);
		}
		if( $product_status )
			$args['post_status'] = wpsc_ce_post_statuses( $product_status, true );
		$products = get_posts( $args );
		if( $products ) {
			$weight_unit = get_option( 'woocommerce_weight_unit' );
			$dimension_unit = get_option( 'woocommerce_dimension_unit' );
			$height_unit = $dimension_unit;
			$width_unit = $dimension_unit;
			$length_unit = $dimension_unit;
			foreach( $products as $key => $product ) {
				$products[$key]->sku = get_post_meta( $product->ID, '_sku', true );
				$products[$key]->name = get_the_title( $product->ID );
				$products[$key]->description = woo_ce_clean_html( $product->post_content );
				$products[$key]->price = get_post_meta( $product->ID, '_price', true );
				$products[$key]->sale_price = get_post_meta( $product->ID, '_sale_price', true );
				$products[$key]->slug = $product->post_name;
				$products[$key]->permalink = get_permalink( $product->ID );
				$products[$key]->excerpt = woo_ce_clean_html( $product->post_excerpt );
				$products[$key]->weight = get_post_meta( $product->ID, '_weight', true );
				$products[$key]->weight_unit = $weight_unit;
				$products[$key]->height = get_post_meta( $product->ID, '_height', true );
				$products[$key]->height_unit = $height_unit;
				$products[$key]->width = get_post_meta( $product->ID, '_width', true );
				$products[$key]->width_unit = $width_unit;
				$products[$key]->length = get_post_meta( $product->ID, '_length', true );
				$products[$key]->length_unit = $length_unit;
				$products[$key]->category = woo_ce_get_product_assoc_categories( $product->ID );
				$products[$key]->tag = woo_ce_get_product_assoc_tags( $product->ID );
				$products[$key]->quantity = get_post_meta( $product->ID, '_stock', true );
				$products[$key]->external_link = get_post_meta( $product->ID, '_product_url', true );
				$products[$key]->product_status = woo_ce_format_product_status( $product->post_status );
				$products[$key]->comment_status = woo_ce_format_comment_status( $product->comment_status );
			}
		}
		return $products;

	}

	function woo_ce_get_product_assoc_categories( $product_id = 0 ) {

		global $export;

		$output = '';
		$term_taxonomy = 'product_cat';
		if( $product_id )
			$categories = wp_get_object_terms( $product_id, $term_taxonomy );
		if( $categories ) {
			$size = count( $categories );
			for( $i = 0; $i < $size; $i++ ) {
				if( $categories[$i]->parent == '0' ) {
					$output .= $categories[$i]->name . $export->category_separator;
				} else {
					// Check if Parent -> Child
					$parent_category = get_term( $categories[$i]->parent, $term_taxonomy );
					// Check if Parent -> Child -> Subchild
					if( $parent_category->parent == '0' ) {
						$output .= $parent_category->name . '>' . $categories[$i]->name . $export->category_separator;
						$output = str_replace( $parent_category->name . $export->category_separator, '', $output );
					} else {
						$root_category = get_term( $parent_category->parent, $term_taxonomy );
						$output .= $root_category->name . '>' . $parent_category->name . '>' . $categories[$i]->name . $export->category_separator;
						$output = str_replace( array(
							$root_category->name . '>' . $parent_category->name . $export->category_separator,
							$parent_category->name . $export->category_separator
						), '', $output );
					}
					unset( $root_category, $parent_category );
				}
			}
			$output = substr( $output, 0, -1 );
		} else {
			$output .= __( 'Uncategorized', 'woo_ce' );
		}
		return $output;

	}

	function woo_ce_get_product_assoc_tags( $product_id = 0 ) {

		global $export;

		$output = '';
		$term_taxonomy = 'product_tag';
		$tags = wp_get_object_terms( $product_id, $term_taxonomy );
		if( $tags ) {
			$size = count( $tags );
			for( $i = 0; $i < $size; $i++ ) {
				$tag = get_term( $tags[$i]->term_id, $term_taxonomy );
				$output .= $tag->name . $export->category_separator;
			}
			$output = substr( $output, 0, -1 );
		}
		return $output;

	}

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

	function woo_ce_get_product_fields( $format = 'full' ) {

		$fields = array();
		$fields[] = array(
			'name' => 'sku',
			'label' => __( 'SKU', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'name',
			'label' => __( 'Product Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'permalink',
			'label' => __( 'Permalink', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'description',
			'label' => __( 'Description', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'excerpt',
			'label' => __( 'Excerpt', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'price',
			'label' => __( 'Price', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'sale_price',
			'label' => __( 'Sale Price', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'weight',
			'label' => __( 'Weight', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'weight_unit',
			'label' => __( 'Weight Unit', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'height',
			'label' => __( 'Height', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'height_unit',
			'label' => __( 'Height Unit', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'width',
			'label' => __( 'Width', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'width_unit',
			'label' => __( 'Width Unit', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'length',
			'label' => __( 'Length', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'length_unit',
			'label' => __( 'Length Unit', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'category',
			'label' => __( 'Category', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'tag',
			'label' => __( 'Tag', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'quantity',
			'label' => __( 'Quantity', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'external_link',
			'label' => __( 'External Link', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'product_status',
			'label' => __( 'Product Status', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'comment_status',
			'label' => __( 'Comment Status', 'woo_ce' ),
			'default' => 1
		);

/*
		$fields[] = array(
			'name' => '',
			'label' => __( '', 'woo_ce' ),
			'default' => 1
		);
*/

		/* Allow Plugin/Theme authors to add support for additional Product columns */
		$fields = apply_filters( 'woo_ce_product_fields', $fields );

		$remember = woo_ce_get_option( 'products_fields' );
		if( $remember ) {
			$remember = maybe_unserialize( $remember );
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( !array_key_exists( $fields[$i]['name'], $remember ) )
					$fields[$i]['default'] = 0;
			}
		}

		switch( $format ) {

			case 'summary':
				$output = array();
				$size = count( $fields );
				for( $i = 0; $i < $size; $i++ )
					$output[$fields[$i]['name']] = 'on';
				return $output;
				break;

			case 'full':
			default:
				return $fields;

		}

	}

	function woo_ce_get_product_field( $name = null, $format = 'name' ) {

		$output = '';
		if( $name ) {
			$fields = woo_ce_get_product_fields();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( $fields[$i]['name'] == $name ) {
					switch( $format ) {

						case 'name':
							$output = $fields[$i]['label'];
							break;

						case 'full':
							$output = $fields[$i];
							break;

					}
					$i = $size;
				}
			}
		}
		return $output;

	}

	/* Categories */

	function woo_ce_get_product_categories() {

		$output = '';
		$term_taxonomy = 'product_cat';
		$args = array(
			'hide_empty' => 0
		);
		$categories = get_terms( $term_taxonomy, $args );
		if( $categories )
			$output = $categories;
		return $output;

	}

	/* Tags */

	function woo_ce_get_product_tags() {

		$output = '';
		$term_taxonomy = 'product_tag';
		$args = array(
			'hide_empty' => 0
		);
		$tags = get_terms( $term_taxonomy, $args );
		if( $tags )
			$output = $tags;
		return $output;

	}

	/* Orders */

	function woo_ce_get_order_fields( $format = 'full' ) {

		$fields = array();
		$fields[] = array(
			'name' => 'purchase_id',
			'label' => __( 'Purchase ID', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'purchase_total',
			'label' => __( 'Purchase Total', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_discount',
			'label' => __( 'Order Discount', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_shipping_tax',
			'label' => __( 'Order Shipping Tax', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'payment_gateway',
			'label' => __( 'Payment Gateway', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'shipping_method',
			'label' => __( 'Shipping Method', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'payment_status',
			'label' => __( 'Payment Status', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_key',
			'label' => __( 'Order Key', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'purchase_date',
			'label' => __( 'Purchase Date', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'customer_note',
			'label' => __( 'Customer Note', 'woo_ce' ),
			'default' => 1
		);
/*
		$fields[] = array(
			'name' => 'order_notes',
			'label' => __( 'Order Notes', 'woo_ce' ),
			'default' => 1
		);
*/
		$fields[] = array( 
			'name' => 'user_id',
			'label' => __( 'User ID', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'user_name',
			'label' => __( 'Username', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_full_name',
			'label' => __( 'Billing: Full Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_first_name',
			'label' => __( 'Billing: First Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_last_name',
			'label' => __( 'Billing: Last Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_company',
			'label' => __( 'Billing: Company', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_address',
			'label' => __( 'Billing: Street Address', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_city',
			'label' => __( 'Billing: City', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_postcode',
			'label' => __( 'Billing: ZIP Code', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_state',
			'label' => __( 'Billing: State (prefix)', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_state_full',
			'label' => __( 'Billing: State', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_country',
			'label' => __( 'Billing: Country (prefix)', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_country_full',
			'label' => __( 'Billing: Country', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_phone',
			'label' => __( 'Billing: Phone Number', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_email',
			'label' => __( 'Billing: E-mail Address', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_full_name',
			'label' => __( 'Shipping: Full Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_first_name',
			'label' => __( 'Shipping: First Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_last_name',
			'label' => __( 'Shipping: Last Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_company',
			'label' => __( 'Shipping: Company', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_address',
			'label' => __( 'Shipping: Street Address', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_city',
			'label' => __( 'Shipping: City', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_postcode',
			'label' => __( 'Shipping: ZIP Code', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_state',
			'label' => __( 'Shipping: State (prefix)', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_state_full',
			'label' => __( 'Shipping: State', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_country',
			'label' => __( 'Shipping: Country (prefix)', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_country_full',
			'label' => __( 'Shipping: Country', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_product_id',
			'label' => __( 'Order Items: Product ID', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_variation_id',
			'label' => __( 'Order Items: Variation ID', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_sku',
			'label' => __( 'Order Items: SKU', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_name',
			'label' => __( 'Order Items: Product Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_variation',
			'label' => __( 'Order Items: Product Variation', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_tax_class',
			'label' => __( 'Order Items: Tax Class', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_quantity',
			'label' => __( 'Order Items: Quantity', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_total',
			'label' => __( 'Order Items: Total', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_subtotal',
			'label' => __( 'Order Items: Subtotal', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_tax',
			'label' => __( 'Order Items: Tax', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_tax_subtotal',
			'label' => __( 'Order Items: Tax Subtotal', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'order_items_type',
			'label' => __( 'Order Items: Type', 'woo_ce' ),
			'default' => 1
		);

/*
		$fields[] = array(
			'name' => '',
			'label' => __( '', 'woo_ce' ),
			'default' => 1
		);
*/

		/* Allow Plugin/Theme authors to add support for additional Order columns */
		$fields = apply_filters( 'woo_ce_order_fields', $fields );

		$remember = woo_ce_get_option( 'orders_fields' );
		if( $remember ) {
			$remember = maybe_unserialize( $remember );
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( $fields[$i] ) {
					if( !array_key_exists( $fields[$i]['name'], $remember ) )
						$fields[$i]['default'] = 0;
				}
			}
		}

		switch( $format ) {

			case 'summary':
				$output = array();
				$size = count( $fields );
				for( $i = 0; $i < $size; $i++ )
					$output[$fields[$i]['name']] = 'on';
				return $output;
				break;

			case 'full':
			default:
				return $fields;

		}

	}

	function woo_ce_get_order_field( $name = null, $format = 'name' ) {

		$output = '';
		if( $name ) {
			$fields = woo_ce_get_order_fields();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( $fields[$i]['name'] == $name ) {
					switch( $format ) {

						case 'name':
							$output = $fields[$i]['label'];
							break;

						case 'full':
							$output = $fields[$i];
							break;

					}
					$i = $size;
				}
			}
		}
		return $output;

	}

	function woo_ce_format_order_date( $date ) {

		$output = $date;
		if( $date )
			$output = str_replace( '/', '-', $date );
		return $output;

	}

	/* Customers */

	function woo_ce_get_customer_fields( $format = 'full' ) {

		$fields = array();
		$fields[] = array(
			'name' => 'user_id',
			'label' => __( 'User ID', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'user_name',
			'label' => __( 'Username', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_full_name',
			'label' => __( 'Billing: Full Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_first_name',
			'label' => __( 'Billing: First Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_last_name',
			'label' => __( 'Billing: Last Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_company',
			'label' => __( 'Billing: Company', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_address',
			'label' => __( 'Billing: Street Address', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_city',
			'label' => __( 'Billing: City', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_postcode',
			'label' => __( 'Billing: ZIP Code', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_state',
			'label' => __( 'Billing: State (prefix)', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_state_full',
			'label' => __( 'Billing: State', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_country',
			'label' => __( 'Billing: Country', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_phone',
			'label' => __( 'Billing: Phone Number', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'billing_email',
			'label' => __( 'Billing: E-mail Address', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_full_name',
			'label' => __( 'Shipping: Full Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_first_name',
			'label' => __( 'Shipping: First Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_last_name',
			'label' => __( 'Shipping: Last Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_company',
			'label' => __( 'Shipping: Company', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_address',
			'label' => __( 'Shipping: Street Address', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_city',
			'label' => __( 'Shipping: City', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_postcode',
			'label' => __( 'Shipping: ZIP Code', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_state',
			'label' => __( 'Shipping: State (prefix)', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_state_full',
			'label' => __( 'Shipping: State', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_country',
			'label' => __( 'Shipping: Country (prefix)', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_country_full',
			'label' => __( 'Shipping: Country', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'total_spent',
			'label' => __( 'Total Spent', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'completed_orders',
			'label' => __( 'Completed Orders', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'total_orders',
			'label' => __( 'Total Orders', 'woo_ce' ),
			'default' => 1
		);

		/* Allow Plugin/Theme authors to add support for additional Customer columns */
		$fields = apply_filters( 'woo_ce_customer_fields', $fields );

		$remember = woo_ce_get_option( 'customers_fields' );
		if( $remember ) {
			$remember = maybe_unserialize( $remember );
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( !array_key_exists( $fields[$i]['name'], $remember ) )
					$fields[$i]['default'] = 0;
			}
		}

		switch( $format ) {

			case 'summary':
				$output = array();
				$size = count( $fields );
				for( $i = 0; $i < $size; $i++ )
					$output[$fields[$i]['name']] = 'on';
				return $output;
				break;

			case 'full':
			default:
				return $fields;

		}

	}

	function woo_ce_get_customer_field( $name = null, $format = 'name' ) {

		$output = '';
		if( $name ) {
			$fields = woo_ce_get_customer_fields();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( $fields[$i]['name'] == $name ) {
					switch( $format ) {

						case 'name':
							$output = $fields[$i]['label'];
							break;

						case 'full':
							$output = $fields[$i];
							break;

					}
					$i = $size;
				}
			}
		}
		return $output;

	}

	/* Coupons */

	function woo_ce_get_coupon_fields( $format = 'full' ) {

		$fields = array();
		$fields[] = array(
			'name' => 'coupon_code',
			'label' => __( 'Coupon Code', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'coupon_description',
			'label' => __( 'Coupon Description', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'discount_type',
			'label' => __( 'Discount Type', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'coupon_amount',
			'label' => __( 'Coupon Amount', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'individual_use',
			'label' => __( 'Individual Use', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'apply_before_tax',
			'label' => __( 'Apply before tax', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'exclude_sale_items',
			'label' => __( 'Exclude sale items', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'minimum_amount',
			'label' => __( 'Minimum Amount', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'product_ids',
			'label' => __( 'Products', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'exclude_product_ids',
			'label' => __( 'Exclude Products', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'product_categories',
			'label' => __( 'Product Categories', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'exclude_product_categories',
			'label' => __( 'Exclude Product Categories', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'customer_email',
			'label' => __( 'Customer e-mails', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'usage_limit',
			'label' => __( 'Usage Limit', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array(
			'name' => 'expiry_date',
			'label' => __( 'Expiry Date', 'woo_ce' ),
			'default' => 1
		);

/*
		$fields[] = array(
			'name' => '',
			'label' => __( '', 'woo_ce' ),
			'default' => 1
		);
*/

		/* Allow Plugin/Theme authors to add support for additional Coupon columns */
		$fields = apply_filters( 'woo_ce_coupon_fields', $fields );

		$remember = woo_ce_get_option( 'coupons_fields' );
		if( $remember ) {
			$remember = maybe_unserialize( $remember );
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( !array_key_exists( $fields[$i]['name'], $remember ) )
					$fields[$i]['default'] = 0;
			}
		}

		switch( $format ) {

			case 'summary':
				$output = array();
				$size = count( $fields );
				for( $i = 0; $i < $size; $i++ )
					$output[$fields[$i]['name']] = 'on';
				return $output;
				break;

			case 'full':
			default:
				return $fields;

		}

	}

	function woo_ce_get_coupon_field( $name = null, $format = 'name' ) {

		$output = '';
		if( $name ) {
			$fields = woo_ce_get_coupon_fields();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( $fields[$i]['name'] == $name ) {
					switch( $format ) {

						case 'name':
							$output = $fields[$i]['label'];
							break;

						case 'full':
							$output = $fields[$i];
							break;

					}
					$i = $size;
				}
			}
		}
		return $output;

	}

	/* Export */

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

	function woo_ce_tab_template( $tab = '' ) {

		global $woo_ce;

		if( !$tab )
			$tab = 'overview';

		/* Store Exporter Deluxe */
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
					$product_categories = woo_ce_get_product_categories();
					$product_tags = woo_ce_get_product_tags();
					$product_statuses = get_post_statuses();
					$product_statuses['trash'] = __( 'Trash', 'woo_ce' );
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
				$file_encodings = mb_list_encodings();
				break;

			case 'tools':
				/* Product Importer Deluxe */
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

	function woo_ce_save_csv_file_attachment( $filename = '' ) {

		$output = 0;
		if( !empty( $filename ) ) {
			$object = array(
				'post_title' => $filename,
				'post_type' => 'woo-export',
				'post_mime_type' => 'text/csv'
			);
			$post_ID = wp_insert_attachment( $object, $filename );
			if( $post_ID )
				$output = $post_ID;
		}
		return $output;

	}

	function woo_ce_save_csv_file_guid( $post_ID, $export_type, $upload_url ) {

		add_post_meta( $post_ID, '_woo_export_type', $export_type );
		$object = array(
			'ID' => $post_ID,
			'guid' => $upload_url
		);
		wp_update_post( $object );

	}


	function woo_ce_get_order_statuses() {

		$args = array(
			'hide_empty' => false
		);
		$terms = get_terms( 'shop_order_status', $args );
		return $terms;

	}

	function woo_ce_memory_prompt() {

		if( !woo_ce_get_option( 'dismiss_memory_prompt', 0 ) ) {
			$memory_limit = (int)( ini_get( 'memory_limit' ) );
			$minimum_memory_limit = 64;
			if( $memory_limit < $minimum_memory_limit ) {
				ob_start();
				$memory_url = add_query_arg( 'action', 'dismiss_memory_prompt' );
				$message = sprintf( __( 'We recommend setting memory to at least 64MB, your site has %dMB currently allocated. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'woo_ce' ), $memory_limit, 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ); ?>
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
		return $file;

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