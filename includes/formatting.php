<?php
function woo_ce_clean_html( $data ) {

	if( function_exists( 'mb_convert_encoding' ) ) {
		$output_encoding = 'ISO-8859-1';
		$data = mb_convert_encoding( trim( $data ), 'UTF-8', $output_encoding );
	} else {
		$data = trim( $data );
	}
	$data = str_replace( ',', '&#44;', $data );
	$data = str_replace( "\n", '<br />', $data );

	return $data;

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
?>