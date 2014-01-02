<?php
// Returns a list of WooCommerce Product Categories to export process
function woo_ce_get_product_categories( $args = array() ) {

	$output = '';
	if( $args ) {
		$orderby = $args['category_orderby'];
		$order = $args['category_order'];
	}
	$term_taxonomy = 'product_cat';
	$args = array(
		'orderby' => $orderby,
		'order' => $order,
		'hide_empty' => 0
	);
	$categories = get_terms( $term_taxonomy, $args );
	if( $categories )
		$output = $categories;
	return $output;

}

// Returns a list of Category export columns
function woo_ce_get_category_fields( $format = 'full' ) {

	$fields = array();
	$fields[] = array(
		'name' => 'term_id',
		'label' => __( 'Term ID', 'woo_ce' ),
		'default' => 1
	);
	$fields[] = array(
		'name' => 'name',
		'label' => __( 'Category Name', 'woo_ce' ),
		'default' => 1
	);
	$fields[] = array(
		'name' => 'slug',
		'label' => __( 'Category Slug', 'woo_ce' ),
		'default' => 1
	);

/*
	$fields[] = array(
		'name' => '',
		'label' => __( '', 'woo_ce' ),
		'default' => 1
	);
*/

	// Allow Plugin/Theme authors to add support for additional Category columns
	$fields = apply_filters( 'woo_ce_category_fields', $fields );

	$remember = woo_ce_get_option( 'categories_fields' );
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

// Returns the export column header label based on an export column slug
function woo_ce_get_category_field( $name = null, $format = 'name' ) {

	$output = '';
	if( $name ) {
		$fields = woo_ce_get_category_fields();
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

?>