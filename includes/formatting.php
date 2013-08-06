<?php
function woo_ce_clean_html( $content ) {

	if( function_exists( 'mb_convert_encoding' ) ) {
		$output_encoding = 'ISO-8859-1';
		$content = mb_convert_encoding( trim( $content ), 'UTF-8', $output_encoding );
	} else {
		$content = trim( $content );
	}
	// $content = str_replace( ',', '&#44;', $content );
	// $content = str_replace( "\n", '<br />', $content );

	return $content;

}

if( !function_exists( 'escape_csv_value' ) ) {
	function escape_csv_value( $value ) {

		$value = str_replace( '"', '""', $value ); // First off escape all " and make them ""
		$value = str_replace( PHP_EOL, ' ', $value );
		return '"' . $value . '"'; // If I have new lines or commas escape them

	}
}

function woo_ce_escape_csv_value( $value = '', $delimiter = ',', $format = 'all' ) {

	$output = $value;
	if( !empty( $output ) ) {
		$output = str_replace( '"', '""', $output );
		// output = str_replace( PHP_EOL, ' ', $output );
		$output = str_replace( PHP_EOL, "\r\n", $output );
		switch( $format ) {
	
			case 'all':
				$output = '"' . $output . '"';
				break;
	
			case 'excel':
				if( strstr( $output, $delimiter ) !== false || strstr( $output, "\r\n" ) !== false )
					$output = '"' . $output . '"';
				break;
	
		}
	}
	return $output;

}

function woo_ce_format_product_status( $product_status ) {

	$output = $product_status;
	switch( $product_status ) {

		case 'publish':
			$output = __( 'Publish', 'woo_ce' );
			break;

		case 'draft':
			$output = __( 'Draft', 'woo_ce' );
			break;

		case 'trash':
			$output = __( 'Trash', 'woo_ce' );
			break;

	}
	return $output;

}

function woo_ce_format_comment_status( $comment_status ) {

	$output = $comment_status;
	switch( $comment_status ) {

		case 'open':
			$output = __( 'Open', 'woo_ce' );
			break;

		case 'closed':
			$output = __( 'Closed', 'woo_ce' );
			break;

	}
	return $output;

}

function woo_ce_format_switch( $input = '', $output_format = 'answer' ) {

	$input = strtolower( $input );
	switch( $input ) {

		case '1':
		case 'yes':
		case 'on':
		case 'open':
		case 'active':
			$input = '1';
			break;

		case '0':
		case 'no':
		case 'off':
		case 'closed':
		case 'inactive':
		default:
			$input = '0';
			break;

	}
	$output = '';
	switch( $output_format ) {

		case 'int':
			$output = $input;
			break;

		case 'answer':
			switch( $input ) {

				case '1':
					$output = __( 'Yes', 'woo_ce' );
					break;

				case '0':
					$output = __( 'No', 'woo_ce' );
					break;

			}
			break;

		case 'boolean':
			switch( $input ) {

				case '1':
					$output = 'on';
					break;

				case '0':
					$output = 'off';
					break;

			}
			break;

	}
	return $output;

}

function woo_ce_format_product_filters( $product_filters = array() ) {

	$output = array();
	if( !empty( $product_filters ) ) {
		foreach( $product_filters as $product_filter ) {
			$output[] = $product_filter;
		}
	}
	return $output;

}

function woo_ce_format_product_type( $type_id = '' ) {

	$output = $type_id;
	if( $output ) {
		$product_types = array(
			'simple' => __( 'Simple', 'woocommerce' ),
			'downloadable' => __( 'Downloadable', 'woocommerce' ),
			'grouped' => __( 'Grouped', 'woocommerce' ),
			'virtual' => __( 'Virtual', 'woocommerce' ),
			'variable' => __( 'Variable', 'woocommerce' ),
			'external' => __( 'External / Affiliate', 'woocommerce' )
		);
		if( isset( $product_types[$type_id] ) )
			$output = $product_types[$type_id];
	}
	return $output;

}
?>