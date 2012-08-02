<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	/* WordPress Administration Menu */
	function woo_ce_admin_menu() {

		add_submenu_page( 'woocommerce', __( 'Store Export', 'woo_ce' ), __( 'Store Export', 'woo_ce' ), 'manage_options', 'woo_ce', 'woo_ce_html_page' );

	}
	add_action( 'admin_menu', 'woo_ce_admin_menu' );

	function woo_ce_return_count( $dataset ) {

		global $wpdb;

		$count_sql = null;
		switch( $dataset ) {

			case 'products':
				$post_type = 'product';
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

		global $wpdb;

		$csv = '';
		$separator = ',';

		foreach( $dataset as $datatype ) {

			$csv = null;

			switch( $datatype ) {

				case 'products':
					$columns = array(
						'SKU',
						'Product Name',
						'Permalink',
						'Description',
						'Excerpt',
						'Price',
						'Sale Price',
						'Weight',
						'Weight Unit',
						'Height',
						'Height Unit',
						'Width',
						'Width Unit',
						'Length',
						'Length Unit',
						'Category',
						'Tag',
						'Quantity',
						'External Link',
						'Product Status',
						'Comment Status'
					);
					for( $i = 0; $i < count( $columns ); $i++ ) {
						if( $i == ( count( $columns ) - 1 ) )
							$csv .= '"' . $columns[$i] . "\"\n";
						else
							$csv .= '"' . $columns[$i] . '"' . $separator;
					}
					$products_sql = "SELECT `ID`, `post_title` as name, `post_name` as permalink, `post_content` as description, `post_excerpt` as excerpt, `post_status` as status, `comment_status` as comments FROM `" . $wpdb->posts . "` WHERE `post_type` = 'product'";
					$products = $wpdb->get_results( $products_sql );
					if( $products ) {
						$weight_unit = get_option( 'woocommerce_weight_unit' );
						$dimension_unit = get_option( 'woocommerce_dimension_unit' );
						$height_unit = $dimension_unit;
						$width_unit = $dimension_unit;
						$length_unit = $dimension_unit;
						foreach( $products as $product ) {

							$product_data = get_post_meta( $product->ID, 'product_metadata', true );

							$product->sku = get_post_meta( $product->ID, '_sku', true );
							$product->description = woo_ce_clean_html( $product->description );
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

							foreach( $product as $key => $value )
								$product->$key = '"' . woo_ce_has_value( $value ) . '"';

							$csv .= 
								$product->sku . $separator . 
								$product->name . $separator . 
								$product->permalink . $separator . 
								$product->description . $separator . 
								$product->excerpt . $separator . 
								$product->price . $separator . 
								$product->sale_price . $separator . 
								$product->weight . $separator . 
								$product->weight_unit . $separator . 
								$product->height . $separator . 
								$product->height_unit . $separator . 
								$product->width . $separator . 
								$product->width_unit . $separator . 
								$product->length . $separator . 
								$product->length_unit . $separator . 
								$product->category . $separator . 
								$product->tag . $separator . 
								$product->quantity . $separator . 
								$product->external_link . $separator . 
								$product->status . $separator . 
								$product->comments . 
							"\n";

						}
					}
					break;

			}

			if( WP_DEBUG )
				echo '<code>' . str_replace( "\n", '<br />', $csv ) . '</code>' . '<br />';
			else
				echo $csv;

		}

	}

	function woo_ce_get_product_categories( $product_id ) {

		global $wpdb;

		$categories_sql = "SELECT term_taxonomy.`term_id` as term_id FROM `" . $wpdb->term_taxonomy . "` as term_taxonomy, `" . $wpdb->term_relationships . "` as term_relationships WHERE term_relationships.`term_taxonomy_id` = term_taxonomy.`term_taxonomy_id` AND term_relationships.`object_id` = " . $product_id . " AND term_taxonomy.`taxonomy` = 'product_cat'";
		$categories = $wpdb->get_results( $categories_sql, ARRAY_A );
		if( $categories ) {
			$term_taxonomy = 'product_cat';
			$output = '';
			for( $i = 0; $i < count( $categories ); $i++ ) {
				$category = get_term( $categories[$i]['term_id'], $term_taxonomy );
				$output .= $category->name . '|';
			}
			$output = substr( $output, 0, -1 );
		}
		return $output;

	}

	function woo_ce_get_product_tags( $product_id ) {

		global $wpdb;

		$tags_sql = "SELECT term_taxonomy.`term_id` as term_id FROM `" . $wpdb->term_taxonomy . "` as term_taxonomy, `" . $wpdb->term_relationships . "` as term_relationships WHERE term_relationships.`term_taxonomy_id` = term_taxonomy.`term_taxonomy_id` AND term_relationships.`object_id` = " . $product_id . " AND term_taxonomy.`taxonomy` = 'product_tag'";
		$tags = $wpdb->get_results( $tags_sql, ARRAY_A );
		if( $tags ) {
			$term_taxonomy = 'product_tag';
			$output = '';
			for( $i = 0; $i < count( $tags ); $i++ ) {
				$tag = get_term( $tags[$i]['term_id'], $term_taxonomy );
				$output .= $tag->name . '|';
			}
			$output = substr( $output, 0, -1 );
		}
		return $output;

	}

	function woo_ce_generate_csv_header() {

		$filename = 'woo-export.csv';

		header( 'Content-type: application/csv' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

	}

	function woo_ce_has_value( $value ) {

		switch( $value ) {

			case '0':
				$value = null;
				break;

			default:
				$value = htmlspecialchars_decode( $value );
				break;

		}
		return $value;

	}

	function woo_ce_clean_html( $data ) {

		$data = htmlentities( $data );
		$data = str_replace( ',', '&#44;', $data );
		$data = str_replace( "\n", '<br />', $data );

		return $data;

	}

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

	/* End of: WordPress Administration */

}
?>