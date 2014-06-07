<?php
if( is_admin() ) {

	// HTML template for Custom Products widget on Store Exporter screen
	function woo_ce_products_custom_fields() { ?>
<form method="post" id="export-products-custom-fields" class="export-options product-options">
	<div id="poststuff">

		<div class="postbox" id="export-options product-options">
			<h3 class="hndle"><?php _e( 'Custom Product Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'To include additional custom Product meta in the Export Products table above fill the Products text box then click Save Custom Fields.', 'woo_ce' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<label><?php _e( 'Product meta', 'woo_ce' ); ?></label>
						</th>
						<td>
							<textarea name="custom_products" rows="5" cols="70" disabled="disabled">-</textarea>
							<p class="description"><?php _e( 'Include additional custom Product meta in your exported CSV by adding each custom Product meta name to a new line above.<br />For example: <code>Customer UA, Customer IP Address</code>', 'woo_ce' ); ?></p>
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Save Custom Fields', 'woo_ce' ); ?>" class="button button-disabled" />
				</p>
				<p class="description"><?php printf( __( 'For more information on custom Product meta consult our <a href="%s" target="_blank">online documentation</a>.', 'woo_ce' ), $troubleshooting_url ); ?></p>
			</div>
			<!-- .inside -->
		</div>
		<!-- .postbox -->

	</div>
	<!-- #poststuff -->
	<input type="hidden" name="action" value="update" />
</form>
<!-- #export-products-custom-fields -->
<?php
		ob_end_flush();

	}

}

// Returns a list of WooCommerce Product IDs to export process
function woo_ce_get_products( $args = array() ) {

	$limit_volume = -1;
	$offset = 0;
	$product_categories = false;
	$product_tags = false;
	$product_status = false;
	$product_type = false;
	$orderby = 'ID';
	$order = 'ASC';
	if( $args ) {
		$limit_volume = $args['limit_volume'];
		$offset = $args['offset'];
		if( !empty( $args['product_categories'] ) )
			$product_categories = $args['product_categories'];
		if( !empty( $args['product_tags'] ) )
			$product_tags = $args['product_tags'];
		if( !empty( $args['product_status'] ) )
			$product_status = $args['product_status'];
		if( !empty( $args['product_type'] ) )
			$product_type = $args['product_type'];
		if( isset( $args['product_orderby'] ) )
			$orderby = $args['product_orderby'];
		if( isset( $args['product_order'] ) )
			$order = $args['product_order'];
	}
	$post_type = array( 'product', 'product_variation' );
	$args = array(
		'post_type' => $post_type,
		'orderby' => $orderby,
		'order' => $order,
		'offset' => $offset,
		'posts_per_page' => $limit_volume,
		'post_status' => woo_ce_post_statuses(),
		'no_found_rows' => false,
		'fields' => 'ids'
	);
	if( $product_categories ) {
		$term_taxonomy = 'product_cat';
		$args['tax_query'] = array(
			array(
				'taxonomy' => $term_taxonomy,
				'field' => 'id',
				'terms' => $product_categories
			)
		);
	}
	if( $product_tags ) {
		$term_taxonomy = 'product_tag';
		$args['tax_query'] = array(
			array(
				'taxonomy' => $term_taxonomy,
				'field' => 'id',
				'terms' => $product_tags
			)
		);
	}
	if( $product_status )
		$args['post_status'] = woo_ce_post_statuses( $product_status, true );
	if( $product_type ) {
		if( in_array( 'variation', $product_type ) ) {
			$args['post_type'] = 'product_variation';
		} else {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_type',
					'field' => 'slug',
					'terms' => $product_type
				)
			);
		}
	}
	$products = array();
	$product_ids = new WP_Query( $args );
	if( $product_ids->posts ) {
		foreach( $product_ids->posts as $product_id ) {
			$product = get_post( $product_id );
			if( $product->post_type == 'product_variation' ) {
				// Check if Parent exists
				if( $product->post_parent ) {
					if( !get_post( $product->post_parent ) ) {
						unset( $product );
						continue;
					}
				}
			}
			$products[] = $product_id;
		}
		unset( $product_ids, $product_id );
	}
	return $products;

}

function woo_ce_get_product_data( $product_id = 0, $args = array() ) {

	// Get Product defaults
	$weight_unit = get_option( 'woocommerce_weight_unit' );
	$dimension_unit = get_option( 'woocommerce_dimension_unit' );
	$height_unit = $dimension_unit;
	$width_unit = $dimension_unit;
	$length_unit = $dimension_unit;

	$product = get_post( $product_id );
	$product->parent_id = '';
	$product->parent_sku = '';
	if( $product->post_type == 'product_variation' ) {
		// Assign Parent ID for Variants then check if Parent exists
		if( $product->parent_id = $product->post_parent )
			$product->parent_sku = get_post_meta( $product->post_parent, '_sku', true );
		else
			$product->parent_id = '';
	}
	$product->product_id = $product->ID;
	$product->sku = get_post_meta( $product->ID, '_sku', true );
	$product->name = get_the_title( $product->ID );
	$product->permalink = get_permalink( $product->ID );
	$product->slug = $product->post_name;
	$product->description = $product->post_content;
	$product->excerpt = $product->post_excerpt;
	$product->regular_price = get_post_meta( $product->ID, '_regular_price', true );
	if( isset( $product->regular_price ) && $product->regular_price != '' )
		$product->regular_price = wc_format_localized_price( $product->regular_price );
	$product->price = get_post_meta( $product->ID, '_price', true );
	if( $product->regular_price != '' && ( $product->regular_price <> $product->price ) )
		$product->price = $product->regular_price;
	if( isset( $product->price ) && $product->price != '' )
		$product->price = wc_format_localized_price( $product->price );
	$product->sale_price = get_post_meta( $product->ID, '_sale_price', true );
	if( isset( $product->sale_price ) && $product->sale_price != '' )
		$product->sale_price = wc_format_localized_price( $product->sale_price );
	$product->sale_price_dates_from = woo_ce_format_sale_price_dates( get_post_meta( $product->ID, '_sale_price_dates_from', true ) );
	$product->sale_price_dates_to = woo_ce_format_sale_price_dates( get_post_meta( $product->ID, '_sale_price_dates_to', true ) );
	$product->post_date = woo_ce_format_date( $product->post_date );
	$product->post_modified = woo_ce_format_date( $product->post_modified );
	$product->type = woo_ce_get_product_assoc_type( $product->ID );
	if( $product->post_type == 'product_variation' )
		$product->type = __( 'Variation', 'woo_ce' );
	$product->visibility = woo_ce_format_visibility( get_post_meta( $product->ID, '_visibility', true ) );
	$product->featured = woo_ce_format_switch( get_post_meta( $product->ID, '_featured', true ) );
	$product->virtual = woo_ce_format_switch( get_post_meta( $product->ID, '_virtual', true ) );
	$product->downloadable = woo_ce_format_switch( get_post_meta( $product->ID, '_downloadable', true ) );
	$product->weight = get_post_meta( $product->ID, '_weight', true );
	$product->weight_unit = ( $product->weight != '' ? $weight_unit : '' );
	$product->height = get_post_meta( $product->ID, '_height', true );
	$product->height_unit = ( $product->height != '' ? $height_unit : '' );
	$product->width = get_post_meta( $product->ID, '_width', true );
	$product->width_unit = ( $product->width != '' ? $width_unit : '' );
	$product->length = get_post_meta( $product->ID, '_length', true );
	$product->length_unit = ( $product->length != '' ? $length_unit : '' );
	$product->category = woo_ce_get_product_assoc_categories( $product->ID, $product->parent_id );
	$product->tag = woo_ce_get_product_assoc_tags( $product->ID );
	$product->manage_stock = woo_ce_format_switch( get_post_meta( $product->ID, '_manage_stock', true ) );
	$product->allow_backorders = woo_ce_format_switch( get_post_meta( $product->ID, '_backorders', true ) );
	$product->sold_individually = woo_ce_format_switch( get_post_meta( $product->ID, '_sold_individually', true ) );
	$product->upsell_ids = woo_ce_get_product_assoc_upsell_ids( $product->ID );
	$product->crosssell_ids = woo_ce_get_product_assoc_crosssell_ids( $product->ID );
	$product->quantity = get_post_meta( $product->ID, '_stock', true );
	$product->stock_status = woo_ce_format_stock_status( get_post_meta( $product->ID, '_stock_status', true ), $product->quantity );
	$product->image = woo_ce_get_product_assoc_featured_image( $product->ID );
	$product->product_gallery = woo_ce_get_product_assoc_product_gallery( $product->ID );
	$product->tax_status = woo_ce_format_tax_status( get_post_meta( $product->ID, '_tax_status', true ) );
	$product->tax_class = woo_ce_format_tax_class( get_post_meta( $product->ID, '_tax_class', true ) );
	$product->product_url = get_post_meta( $product->ID, '_product_url', true );
	$product->button_text = get_post_meta( $product->ID, '_button_text', true );
	$product->file_download = woo_ce_get_product_assoc_file_downloads( $product->ID );
	$product->download_limit = get_post_meta( $product->ID, '_download_limit', true );
	$product->download_expiry = get_post_meta( $product->ID, '_download_expiry', true );
	$product->download_type = woo_ce_format_download_type( get_post_meta( $product->ID, '_download_type', true ) );
	$product->purchase_note = get_post_meta( $product->ID, '_purchase_note', true );
	$product->product_status = woo_ce_format_product_status( $product->post_status );
	$product->enable_reviews = woo_ce_format_comment_status( $product->comment_status );
	$product->menu_order = $product->menu_order;

	// Attributes
	if( $attributes = woo_ce_get_product_attributes() ) {
		if( $product->post_type == 'product_variation' ) {
			foreach( $attributes as $attribute ) {
				$product->{'attribute_' . $attribute->attribute_name} = get_post_meta( $product->ID, sprintf( 'attribute_pa_%s', $attribute->attribute_name ), true );
			}
		} else {
			$product->attributes = maybe_unserialize( get_post_meta( $product->ID, '_product_attributes', true ) );
			if( !empty( $product->attributes ) ) {
				foreach( $attributes as $attribute ) {
					if( isset( $product->attributes['pa_' . $attribute->attribute_name] ) )
						$product->{'attribute_' . $attribute->attribute_name} = woo_ce_get_product_assoc_attributes( $product->ID, $product->attributes['pa_' . $attribute->attribute_name] );
				}
			}
		}
	}

	// Advanced Google Product Feed - http://plugins.leewillis.co.uk/downloads/wp-e-commerce-product-feeds/
	if( function_exists( 'woocommerce_gpf_install' ) ) {
		$product->gpf_data = get_post_meta( $product->ID, '_wpec_gpf_data', true );
		$product->gpf_availability = ( isset( $product->gpf_data['availability'] ) ? woo_ce_format_gpf_availability( $product->gpf_data['availability'] ) : '' );
		$product->gpf_condition = ( isset( $product->gpf_data['condition'] ) ? woo_ce_format_gpf_condition( $product->gpf_data['condition'] ) : '' );
		$product->gpf_brand = ( isset( $product->gpf_data['brand'] ) ? $product->gpf_data['brand'] : '' );
		$product->gpf_product_type = ( isset( $product->gpf_data['product_type'] ) ? $product->gpf_data['product_type'] : '' );
		$product->gpf_google_product_category = ( isset( $product->gpf_data['google_product_category'] ) ? $product->gpf_data['google_product_category'] : '' );
		$product->gpf_gtin = ( isset( $product->gpf_data['gtin'] ) ? $product->gpf_data['gtin'] : '' );
		$product->gpf_mpn = ( isset( $product->gpf_data['mpn'] ) ? $product->gpf_data['mpn'] : '' );
		$product->gpf_gender = ( isset( $product->gpf_data['gender'] ) ? $product->gpf_data['gender'] : '' );
		$product->gpf_age_group = ( isset( $product->gpf_data['age_group'] ) ? $product->gpf_data['age_group'] : '' );
		$product->gpf_color = ( isset( $product->gpf_data['color'] ) ? $product->gpf_data['color'] : '' );
		$product->gpf_size = ( isset( $product->gpf_data['size'] ) ? $product->gpf_data['size'] : '' );
	}

	// WooCommerce MSRP Pricing - http://woothemes.com/woocommerce/
	if( function_exists( 'woocommerce_msrp_activate' ) ) {
		$product->msrp = get_post_meta( $product->ID, '_msrp_price', true );
		if( $product->msrp == false && $product->post_type == 'product_variation' )
			$product->msrp = get_post_meta( $product->ID, '_msrp', true );
			if( isset( $product->msrp ) && $product->msrp != '' )
				$product->msrp = wc_format_localized_price( $product->msrp );
	}

	// All in One SEO Pack - http://wordpress.org/extend/plugins/all-in-one-seo-pack/
	if( function_exists( 'aioseop_activate' ) ) {
		$product->aioseop_keywords = get_post_meta( $product->ID, '_aioseop_keywords', true );
		$product->aioseop_description = get_post_meta( $product->ID, '_aioseop_description', true );
		$product->aioseop_title = get_post_meta( $product->ID, '_aioseop_title', true );
		$product->aioseop_titleatr = get_post_meta( $product->ID, '_aioseop_titleatr', true );
		$product->aioseop_menulabel = get_post_meta( $product->ID, '_aioseop_menulabel', true );
	}

	// WordPress SEO - http://wordpress.org/plugins/wordpress-seo/
	if( function_exists( 'wpseo_admin_init' ) ) {
		$product->wpseo_focuskw = get_post_meta( $product->ID, '_yoast_wpseo_focuskw', true );
		$product->wpseo_metadesc = get_post_meta( $product->ID, '_yoast_wpseo_metadesc', true );
		$product->wpseo_title = get_post_meta( $product->ID, '_yoast_wpseo_title', true );
		$product->wpseo_googleplus_description = get_post_meta( $product->ID, '_yoast_wpseo_google-plus-description', true );
		$product->wpseo_opengraph_description = get_post_meta( $product->ID, '_yoast_wpseo_opengraph-description', true );
	}

	// Ultimate SEO - http://wordpress.org/plugins/seo-ultimate/
	if( function_exists( 'su_wp_incompat_notice' ) ) {
		$product->useo_meta_title = get_post_meta( $product->ID, '_su_title', true );
		$product->useo_meta_description = get_post_meta( $product->ID, '_su_description', true );
		$product->useo_meta_keywords = get_post_meta( $product->ID, '_su_keywords', true );
		$product->useo_social_title = get_post_meta( $product->ID, '_su_og_title', true );
		$product->useo_social_description = get_post_meta( $product->ID, '_su_og_description', true );
		$product->useo_meta_noindex = get_post_meta( $product->ID, '_su_meta_robots_noindex', true );
		$product->useo_meta_noautolinks = get_post_meta( $product->ID, '_su_disable_autolinks', true );
	}

	// Allow Plugin/Theme authors to add support for additional Product columns
	return apply_filters( 'woo_ce_product_item', $product, $product->ID );

}

// Returns Product Categories associated to a specific Product
function woo_ce_get_product_assoc_categories( $product_id = 0, $parent_id = 0 ) {

	global $export;

	$output = '';
	$term_taxonomy = 'product_cat';
	// Return Product Categories of Parent if this is a Variation
	if( $parent_id )
		$product_id = $parent_id;
	if( $product_id )
		$categories = wp_get_object_terms( $product_id, $term_taxonomy );
	if( !empty( $categories ) && is_wp_error( $categories ) == false ) {
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

// Returns Product Tags associated to a specific Product
function woo_ce_get_product_assoc_tags( $product_id = 0 ) {

	global $export;

	$output = '';
	$term_taxonomy = 'product_tag';
	$tags = wp_get_object_terms( $product_id, $term_taxonomy );
	if( !empty( $tags ) && is_wp_error( $tags ) == false ) {
		$size = count( $tags );
		for( $i = 0; $i < $size; $i++ ) {
			if( $tag = get_term( $tags[$i]->term_id, $term_taxonomy ) )
				$output .= $tag->name . $export->category_separator;
		}
		$output = substr( $output, 0, -1 );
	}
	return $output;

}

// Returns the Featured Image associated to a specific Product
function woo_ce_get_product_assoc_featured_image( $product_id = 0 ) {

	$output = '';
	if( $product_id ) {
		if( $thumbnail_id = get_post_meta( $product_id, '_thumbnail_id', true ) )
			$output = wp_get_attachment_url( $thumbnail_id );
	}
	return $output;

}

// Returns the Product Galleries associated to a specific Product
function woo_ce_get_product_assoc_product_gallery( $product_id = 0 ) {

	global $export;

	if( $product_id ) {
		if( $images = get_post_meta( $product_id, '_product_image_gallery', true ) ) {
			$output = str_replace( ',', $export->category_separator, $images );
			return $output;
		}
	}

}

// Returns the Product Type of a specific Product
function woo_ce_get_product_assoc_type( $product_id = 0 ) {

	global $export;

	$output = '';
	$term_taxonomy = 'product_type';
	$types = wp_get_object_terms( $product_id, $term_taxonomy );
	if( empty( $types ) )
		$types = array( get_term_by( 'name', 'simple', $term_taxonomy ) );
	if( $types ) {
		$size = count( $types );
		for( $i = 0; $i < $size; $i++ ) {
			$type = get_term( $types[$i]->term_id, $term_taxonomy );
			$output .= woo_ce_format_product_type( $type->name ) . $export->category_separator;
		}
		$output = substr( $output, 0, -1 );
	}
	return $output;

}

// Returns the Up-Sell associated to a specific Product
function woo_ce_get_product_assoc_upsell_ids( $product_id = 0 ) {

	global $export;

	$output = '';
	if( $product_id ) {
		$upsell_ids = get_post_meta( $product_id, '_upsell_ids', true );
		// Convert Product ID to Product SKU as per Up-Sells Formatting
		if( $export->upsell_formatting == 1 && !empty( $upsell_ids ) ) {
			$size = count( $upsell_ids );
			for( $i = 0; $i < $size; $i++ ) {
				$upsell_ids[$i] = get_post_meta( $upsell_ids[$i], '_sku', true );
				if( empty( $upsell_ids[$i] ) )
					unset( $upsell_ids[$i] );
			}
			// 'reindex' array
			$upsell_ids = array_values( $upsell_ids );
		}
		$output = woo_ce_convert_product_ids( $upsell_ids );
	}
	return $output;

}

// Returns the Cross-Sell associated to a specific Product
function woo_ce_get_product_assoc_crosssell_ids( $product_id = 0 ) {

	global $export;

	$output = '';
	if( $product_id ) {
		$crosssell_ids = get_post_meta( $product_id, '_crosssell_ids', true );
		// Convert Product ID to Product SKU as per Cross-Sells Formatting
		if( $export->crosssell_formatting == 1 && !empty( $crosssell_ids ) ) {
			$size = count( $crosssell_ids );
			for( $i = 0; $i < $size; $i++ ) {
				$crosssell_ids[$i] = get_post_meta( $crosssell_ids[$i], '_sku', true );
				// Remove Cross-Sell if SKU is empty
				if( empty( $crosssell_ids[$i] ) )
					unset( $crosssell_ids[$i] );
			}
			// 'reindex' array
			$crosssell_ids = array_values( $crosssell_ids );
		}
		$output = woo_ce_convert_product_ids( $crosssell_ids );
	}
	return $output;
	
}

// Returns Product Attributes associated to a specific Product
function woo_ce_get_product_assoc_attributes( $product_id = 0, $attribute = array() ) {

	global $export;

	$output = '';
	if( $product_id ) {
		$terms = array();
		if( $attribute['is_taxonomy'] == 1 )
			$terms = wp_get_object_terms( $product_id, $attribute['name'] );
		if( !empty( $terms ) && is_wp_error( $terms ) == false ) {
			$size = count( $terms );
			for( $i = 0; $i < $size; $i++ )
				$output .= $terms[$i]->slug . $export->category_separator;
			unset( $terms );
		}
		$output = substr( $output, 0, -1 );
	}
	return $output;

}

// Returns File Downloads associated to a specific Product
function woo_ce_get_product_assoc_file_downloads( $product_id = 0 ) {

	global $export;

	$output = '';
	if( $product_id ) {
		if( version_compare( WOOCOMMERCE_VERSION, '2.0', '>=' ) ) {
			// If WooCommerce 2.0+ is installed then use new _downloadable_files Post meta key
			if( $file_downloads = maybe_unserialize( get_post_meta( $product_id, '_downloadable_files', true ) ) ) {
				foreach( $file_downloads as $file_download )
					$output .= $file_download['file'] . $export->category_separator;
				unset( $file_download, $file_downloads );
			}
			$output = substr( $output, 0, -1 );
		} else {
			// If WooCommerce -2.0 is installed then use legacy _file_paths Post meta key
			if( $file_downloads = maybe_unserialize( get_post_meta( $product_id, '_file_paths', true ) ) ) {
				foreach( $file_downloads as $file_download )
					$output .= $file_download . $export->category_separator;
				unset( $file_download, $file_downloads );
			}
			$output = substr( $output, 0, -1 );
		}
	}
	return $output;

}

// Returns a list of Product export columns
function woo_ce_get_product_fields( $format = 'full' ) {

	$fields = array();
	$fields[] = array(
		'name' => 'parent_id',
		'label' => __( 'Parent ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'parent_sku',
		'label' => __( 'Parent SKU', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'product_id',
		'label' => __( 'Product ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'sku',
		'label' => __( 'Product SKU', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'name',
		'label' => __( 'Product Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'slug',
		'label' => __( 'Slug', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'permalink',
		'label' => __( 'Permalink', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'description',
		'label' => __( 'Description', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'excerpt',
		'label' => __( 'Excerpt', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'post_date',
		'label' => __( 'Product Published', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'post_modified',
		'label' => __( 'Product Modified', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'type',
		'label' => __( 'Type', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'visibility',
		'label' => __( 'Visibility', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'featured',
		'label' => __( 'Featured', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'virtual',
		'label' => __( 'Virtual', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'downloadable',
		'label' => __( 'Downloadable', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'price',
		'label' => __( 'Price', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'sale_price',
		'label' => __( 'Sale Price', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'sale_price_dates_from',
		'label' => __( 'Sale Price Dates From', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'sale_price_dates_to',
		'label' => __( 'Sale Price Dates To', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'weight',
		'label' => __( 'Weight', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'weight_unit',
		'label' => __( 'Weight Unit', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'height',
		'label' => __( 'Height', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'height_unit',
		'label' => __( 'Height Unit', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'width',
		'label' => __( 'Width', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'width_unit',
		'label' => __( 'Width Unit', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'length',
		'label' => __( 'Length', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'length_unit',
		'label' => __( 'Length Unit', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'category',
		'label' => __( 'Category', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'tag',
		'label' => __( 'Tag', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'image',
		'label' => __( 'Featured Image', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'product_gallery',
		'label' => __( 'Product Gallery', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'tax_status',
		'label' => __( 'Tax Status', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'tax_class',
		'label' => __( 'Tax Class', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'file_download',
		'label' => __( 'File Download', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'download_limit',
		'label' => __( 'Download Limit', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'download_expiry',
		'label' => __( 'Download Expiry', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'download_type',
		'label' => __( 'Download Type', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'manage_stock',
		'label' => __( 'Manage Stock', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'quantity',
		'label' => __( 'Quantity', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'stock_status',
		'label' => __( 'Stock Status', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'allow_backorders',
		'label' => __( 'Allow Backorders', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'sold_individually',
		'label' => __( 'Sold Individually', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'upsell_ids',
		'label' => __( 'Up-Sells', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'crosssell_ids',
		'label' => __( 'Cross-Sells', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'product_url',
		'label' => __( 'Product URL', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'button_text',
		'label' => __( 'Button Text', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'purchase_note',
		'label' => __( 'Purchase Note', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'product_status',
		'label' => __( 'Product Status', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'enable_reviews',
		'label' => __( 'Enable Reviews', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'menu_order',
		'label' => __( 'Sort Order', 'woo_ce' )
	);

/*
	$fields[] = array(
		'name' => '',
		'label' => __( '', 'woo_ce' )
	);
*/

	// Allow Plugin/Theme authors to add support for additional Product columns
	$fields = apply_filters( 'woo_ce_product_fields', $fields );

	if( $remember = woo_ce_get_option( 'products_fields', array() ) ) {
		$remember = maybe_unserialize( $remember );
		$size = count( $fields );
		for( $i = 0; $i < $size; $i++ ) {
			$fields[$i]['disabled'] = 0;
			$fields[$i]['default'] = 1;
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
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ )
				$fields[$i]['order'] = $i;
			return $fields;
			break;

	}

}

function woo_ce_extend_product_fields( $fields ) {

	// Attributes
	if( $attributes = woo_ce_get_product_attributes() ) {
		foreach( $attributes as $attribute ) {
			if( empty( $attribute->attribute_label ) )
				$attribute->attribute_label = $attribute->attribute_name;
			$fields[] = array(
				'name' => sprintf( 'attribute_%s', $attribute->attribute_name ),
				'label' => sprintf( __( 'Attribute: %s', 'woo_ce' ), ucwords( $attribute->attribute_label ) )
			);
		}
	}

	// Advanced Google Product Feed - http://www.leewillis.co.uk/wordpress-plugins/
	if( function_exists( 'woocommerce_gpf_install' ) ) {
		$fields[] = array(
			'name' => 'gpf_availability',
			'label' => __( 'Advanced Google Product Feed - Availability', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_condition',
			'label' => __( 'Advanced Google Product Feed - Condition', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_brand',
			'label' => __( 'Advanced Google Product Feed - Brand', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_productype',
			'label' => __( 'Advanced Google Product Feed - Product Type', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_google_product_category',
			'label' => __( 'Advanced Google Product Feed - Google Product Category', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_gtin',
			'label' => __( 'Advanced Google Product Feed - Global Trade Item Number (GTIN)', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_mpn',
			'label' => __( 'Advanced Google Product Feed - Manufacturer Part Number (MPN)', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_gender',
			'label' => __( 'Advanced Google Product Feed - Gender', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_agegroup',
			'label' => __( 'Advanced Google Product Feed - Age Group', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_colour',
			'label' => __( 'Advanced Google Product Feed - Colour', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'gpf_size',
			'label' => __( 'Advanced Google Product Feed - Size', 'woo_ce' )
		);
	}

	// WooCommerce MSRP Pricing - http://woothemes.com/woocommerce/
	if( function_exists( 'woocommerce_msrp_activate' ) ) {
		$fields[] = array(
			'name' => 'msrp',
			'label' => __( 'Manufacturer Suggested Retail Price (MSRP)', 'woo_ce' )
		);
	}

	// All in One SEO Pack - http://wordpress.org/extend/plugins/all-in-one-seo-pack/
	if( function_exists( 'aioseop_activate' ) ) {
		$fields[] = array(
			'name' => 'aioseop_keywords',
			'label' => __( 'All in One SEO - Keywords', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'aioseop_description',
			'label' => __( 'All in One SEO - Description', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'aioseop_title',
			'label' => __( 'All in One SEO - Title', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'aioseop_title_attributes',
			'label' => __( 'All in One SEO - Title Attributes', 'woo_ce' )
		);
		$fields[] = array(
			'name' => 'aioseop_menu_label',
			'label' => __( 'All in One SEO - Menu Label', 'woo_ce' )
		);
	}

	// WordPress SEO - http://wordpress.org/plugins/wordpress-seo/
	if( function_exists( 'wpseo_admin_init' ) ) {
		$fields[] = array(
			'name' => 'wpseo_focuskw',
			'label' => __( 'WordPress SEO - Focus Keyword', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'wpseo_metadesc',
			'label' => __( 'WordPress SEO - Meta Description', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'wpseo_title',
			'label' => __( 'WordPress SEO - SEO Title', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'wpseo_googleplus_description',
			'label' => __( 'WordPress SEO - Google+ Description', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'wpseo_opengraph_description',
			'label' => __( 'WordPress SEO - Facebook Description', 'woo_pd' )
		);
	}

	// Ultimate SEO - http://wordpress.org/plugins/seo-ultimate/
	if( function_exists( 'su_wp_incompat_notice' ) ) {
		$fields[] = array(
			'name' => 'useo_meta_title',
			'label' => __( 'Ultimate SEO - Title Tag', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'useo_meta_description',
			'label' => __( 'Ultimate SEO - Meta Description', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'useo_meta_keywords',
			'label' => __( 'Ultimate SEO - Meta Keywords', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'useo_social_title',
			'label' => __( 'Ultimate SEO - Social Title', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'useo_social_description',
			'label' => __( 'Ultimate SEO - Social Description', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'useo_meta_noindex',
			'label' => __( 'Ultimate SEO - NoIndex', 'woo_pd' )
		);
		$fields[] = array(
			'name' => 'useo_meta_noautolinks',
			'label' => __( 'Ultimate SEO - Disable Autolinks', 'woo_pd' )
		);
	}

	return $fields;

}
add_filter( 'woo_ce_product_fields', 'woo_ce_extend_product_fields' );

// Returns the export column header label based on an export column slug
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

// Returns a list of WooCommerce Product Types to export process
function woo_ce_get_product_types() {

	$term_taxonomy = 'product_type';
	$args = array(
		'hide_empty' => 0
	);
	$types = get_terms( $term_taxonomy, $args );
	if( !empty( $types ) && is_wp_error( $types ) == false ) {
		$size = count( $types );
		for( $i = 0; $i < $size; $i++ ) {
			$output[$types[$i]->slug] = array(
				'name' => $types[$i]->name,
				'count' => $types[$i]->count
			);
		}
		$output['variation'] = array(
			'name' => __( 'variation', 'woo_ce' ),
			'count' => woo_ce_get_product_type_variation_count()
		);
		asort( $output );
		return $output;
	}

}

function woo_ce_get_product_type_variation_count() {

	$post_type = 'product_variation';
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => 1
	);
	$query = new WP_Query( $args );
	$size = $query->found_posts;
	return $size;

}

// Returns a list of WooCommerce Product Categories to export process
function woo_ce_get_product_attributes() {

	global $wpdb;

	$output = array();
	$attributes_sql = "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies";
	$attributes = $wpdb->get_results( $attributes_sql );
	$wpdb->flush();
	if( $attributes )
		$output = $attributes;
	unset( $attributes );
	return $output;

}
?>