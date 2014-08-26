<?php
/**
*
* Filename: common.php
* Description: common.php loads commonly accessed functions across the Visser Labs suite.
* 
* - woo_get_action
*
*/

if( ! function_exists( 'woo_get_action' ) ) {
	function woo_get_action( $prefer_get = false ) {

		if ( isset( $_GET['action'] ) && $prefer_get )
			return $_GET['action'];

		if ( isset( $_POST['action'] ) )
			return $_POST['action'];

		if ( isset( $_GET['action'] ) )
			return $_GET['action'];

		return false;

	}
}
?>