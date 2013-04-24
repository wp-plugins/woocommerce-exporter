<?php
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
?>