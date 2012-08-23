<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	/* WordPress Administration Menu */
	function woo_ce_admin_menu() {

		add_submenu_page( 'woocommerce', __( 'Store Export', 'woo_ce' ), __( 'Store Export', 'woo_ce' ), 'manage_options', 'woo_ce', 'woo_ce_html_page' );

	}
	add_action( 'admin_menu', 'woo_ce_admin_menu', 11 );

	function woo_ce_template_header() {

		global $woo_ce; ?>
<div class="wrap">
	<div id="icon-tools" class="icon32"><br /></div>
	<h2><?php echo $woo_ce['menu']; ?></h2>
<?php
	}

	function woo_ce_template_footer() { ?>
</div>
<?php
	}

	function woo_ce_return_count( $dataset ) {

		global $wpdb;

		$count_sql = null;
		switch( $dataset ) {

			case 'products':
				$post_type = 'product';
				$count = wp_count_posts( $post_type );
				break;

			case 'customers':
				$post_type = 'shop_order';
				$count = wp_count_posts( $post_type );
				break;

		}
		if( isset( $count ) || $count_sql ) {
			if( isset( $count ) ) {
				if( is_object( $count ) ) {
					$count_object = $count;
					$count = 0;
					foreach( $count_object as $key => $item )
						$count = $item + $count;
				}
			} else {
				$count = $wpdb->get_var( $count_sql );
			}
			return $count;
		} else {
			return false;
		}

	}

	function woo_ce_export_dataset( $dataset ) {

		global $wpdb, $woo_ce, $export;

		$csv = '';

		foreach( $dataset as $datatype ) {

			$csv = null;

			switch( $datatype ) {

				case 'customers':
					$columns = array(
						__( 'Full Name', 'woo_ce' ),
						__( 'First Name', 'woo_ce' ),
						__( 'Street Address', 'woo_ce' ),
						__( 'City', 'woo_ce' ),
						__( 'State', 'woo_ce' ),
						__( 'Zip Code', 'woo_ce' ),
						__( 'Phone Number', 'woo_ce' ),
						__( 'E-mail', 'woo_ce' )
					);
					for( $i = 0; $i < count( $columns ); $i++ ) {
						if( $i == ( count( $columns ) - 1 ) )
							$csv .= '"' . $columns[$i] . "\"\n";
						else
							$csv .= '"' . $columns[$i] . '"' . $export->delimiter;
					}
					$post_type = 'shop_order';
					$orders_sql = "SELECT `ID` FROM `" . $wpdb->posts . "` WHERE `post_type` = '" . $post_type . "'";
					$orders = $wpdb->get_results( $orders_sql );
					if( $orders ) {
						foreach( $orders as $order ) {
							$order->first_name = get_post_meta( $order->ID, '_billing_first_name', true );
							$order->last_name = get_post_meta( $order->ID, '_billing_last_name', true );
							$order->full_name = $order->first_name . ' ' . $order->last_name;
							$order->billing_address = get_post_meta( $order->ID, '_billing_address_1', true );
							$order->billing_address_alt = get_post_meta( $order->ID, '_billing_address_2', true );
							if( $order->billing_address_alt )
								$order->billing_address .= ' ' . $order->billing_address_alt;
							$order->billing_city = get_post_meta( $order->ID, '_billing_city', true );
							$order->billing_postcode = get_post_meta( $order->ID, '_billing_postcode', true );
							$order->billing_country = get_post_meta( $order->ID, '_billing_country', true );
							$order->billing_state = get_post_meta( $order->ID, '_billing_state', true );
							$order->billing_phone = get_post_meta( $order->ID, '_billing_phone', true );
							$order->billing_email = get_post_meta( $order->ID, '_billing_email', true );

							foreach( $order as $key => $value )
								$order->$key = '"' . woo_ce_has_value( $value ) . '"';

							if( isset( $order->billing_email ) && $order->billing_email ) {
								if( !strstr( $csv, $order->billing_email ) ) {
									$csv .= 
										$order->full_name . $export->delimiter . 
										$order->first_name . $export->delimiter . 
										$order->billing_address . $export->delimiter . 
										$order->billing_city . $export->delimiter . 
										$order->billing_state . $export->delimiter . 
										$order->billing_postcode . $export->delimiter . 
										$order->billing_phone . $export->delimiter . 
										$order->billing_email . 
									"\n";
								}
							}

						}
					}
					unset( $orders, $order );
					break;

				case 'products':
					$export->columns = array(
						__( 'SKU', 'woo_ce' ),
						__( 'Product Name', 'woo_ce' ),
						__( 'Permalink', 'woo_ce' ),
						__( 'Description', 'woo_ce' ),
						__( 'Excerpt', 'woo_ce' ),
						__( 'Price', 'woo_ce' ),
						__( 'Sale Price', 'woo_ce' ),
						__( 'Weight', 'woo_ce' ),
						__( 'Weight Unit', 'woo_ce' ),
						__( 'Height', 'woo_ce' ),
						__( 'Height Unit', 'woo_ce' ),
						__( 'Width', 'woo_ce' ),
						__( 'Width Unit', 'woo_ce' ),
						__( 'Length', 'woo_ce' ),
						__( 'Length Unit', 'woo_ce' ),
						__( 'Category', 'woo_ce' ),
						__( 'Tag', 'woo_ce' ),
						__( 'Quantity', 'woo_ce' ),
						__( 'External Link', 'woo_ce' ),
						__( 'Product Status', 'woo_ce' ),
						__( 'Comment Status', 'woo_ce' )
					);

					/* Allow Plugin/Theme authors to add support for additional Product details */
					$export->columns = apply_filters( 'woo_ce_options_addons', $export->columns );

					$size = count( $export->columns );
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$csv .= escape_csv_value( $export->columns[$i] ) . "\n";
						else
							$csv .= escape_csv_value( $export->columns[$i] ) . $export->delimiter;
					}
					$post_type = 'product';
					$products_args = array(
						'post_type' => $post_type,
						'numberposts' => -1
					);
					$products = get_posts( $products_args );
					if( $products ) {
						$weight_unit = get_option( 'woocommerce_weight_unit' );
						$dimension_unit = get_option( 'woocommerce_dimension_unit' );
						$height_unit = $dimension_unit;
						$width_unit = $dimension_unit;
						$length_unit = $dimension_unit;
						foreach( $products as $product ) {

							$product_data = get_post_meta( $product->ID, 'product_metadata', true );

							$product->sku = get_post_meta( $product->ID, '_sku', true );
							$product->name = $product->post_title;
							$product->permalink = $product->post_name;
							$product->description = woo_ce_clean_html( $product->post_content );
							$product->excerpt = woo_ce_clean_html( $product->excerpt );
							$product->price = get_post_meta( $product->ID, '_price', true );
							$product->sale_price = get_post_meta( $product->ID, '_sale_price', true );
							if( $product->weight ) {
								$product->weight = get_post_meta( $product->ID, '_weight', true );
								$product->weight_unit = $weight_unit;
							}
							if( $product->height ) {
								$product->height = get_post_meta( $product->ID, '_height', true );
								$product->height_unit = $height_unit;
							}
							if( $product->width ) {
								$product->width = get_post_meta( $product->ID, '_width', true );
								$product->width_unit = $width_unit;
							}
							if( $product->length ) {
								$product->length = get_post_meta( $product->ID, '_length', true );
								$product->length_unit = $length_unit;
							}
							$product->category = woo_ce_get_product_categories( $product->ID );
							$product->tag = woo_ce_get_product_tags( $product->ID );
							$product->quantity = get_post_meta( $product->ID, '_stock', true );
							$product->external_link = $product_data['external_link'];

							foreach( $product as $key => $value ) {
								if( is_array( $value ) ) {
									foreach( $value as $array_key => $array_value )
										$value[$array_key] = escape_csv_value( $array_value );
									$product->$key = $value;
								} else {
									$product->$key = escape_csv_value( $value );
								}
							}

							$csv .= 
								$product->sku . $export->delimiter . 
								$product->name . $export->delimiter . 
								$product->permalink . $export->delimiter . 
								$product->description . $export->delimiter . 
								$product->excerpt . $export->delimiter . 
								$product->price . $export->delimiter . 
								$product->sale_price . $export->delimiter . 
								$product->weight . $export->delimiter . 
								$product->weight_unit . $export->delimiter . 
								$product->height . $export->delimiter . 
								$product->height_unit . $export->delimiter . 
								$product->width . $export->delimiter . 
								$product->width_unit . $export->delimiter . 
								$product->length . $export->delimiter . 
								$product->length_unit . $export->delimiter . 
								$product->category . $export->delimiter . 
								$product->tag . $export->delimiter . 
								$product->quantity . $export->delimiter . 
								$product->external_link . $export->delimiter . 
								$product->status . $export->delimiter . 
								$product->comments . 
							"\n";

						}
					}
					unset( $products, $product );
					break;

			}

			if( isset( $woo_ce['debug'] ) && $woo_ce['debug'] )
				echo '<code>' . str_replace( "\n", '<br />', $csv ) . '</code>' . '<br />';
			else
				echo $csv;

		}

	}

	function woo_ce_get_product_categories( $product_id = null ) {

		global $export, $wpdb;

		$output = '';
		$term_taxonomy = 'product_cat';
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
					} else {
						$root_category = get_term( $parent_category->parent, $term_taxonomy );
						$output .= $root_category->name . '>' . $parent_category->name . '>' . $categories[$i]->name . $export->category_separator;
					}
					unset( $root_category, $parent_category );
				}
			}
			$output = substr( $output, 0, -1 );
		}
		return $output;

	}

	function woo_ce_get_product_tags( $product_id ) {

		global $wpdb;

		$output = '';
		$term_taxonomy = 'product_tag';
		$tags = wp_get_object_terms( $product_id, $term_taxonomy );
		if( $tags ) {
			for( $i = 0; $i < count( $tags ); $i++ )
				$output .= $tags[$i]->name . '|';
			$output = substr( $output, 0, -1 );
		}
		return $output;

	}

	function woo_ce_generate_csv_header( $dataset = '' ) {

		$filename = 'woo-export_' . $dataset . '.csv';

		header( 'Content-type: application/csv' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

	}

	function woo_ce_has_value( $value = '' ) {

		switch( $value ) {

			case '0':
				$value = null;
				break;

			default:
				if( is_string( $value ) )
					$value = htmlspecialchars_decode( $value );
				break;

		}
		return $value;

	}

	/* End of: WordPress Administration */

}
?>