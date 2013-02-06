<?php
/*

Filename: common.php
Description: common.php loads commonly accessed functions across the Visser Labs suite.

- woo_is_admin_icon_valid

- woo_get_action

*/

if( is_admin() ) {

	/* Start of: WordPress Administration */

	if( !function_exists( 'woo_is_admin_icon_valid' ) ) {
		function woo_is_admin_icon_valid( $icon = 'tools' ) {

			switch( $icon ) {

				case 'index':
				case 'edit':
				case 'post':
				case 'link':
				case 'comments':
				case 'page':
				case 'users':
				case 'upload':
				case 'tools':
				case 'plugins':
				case 'themes':
				case 'profile':
				case 'admin':
					return $icon;
					break;

			}

		}
	}

	include_once( 'common-dashboard_widgets.php' );

	/* End of: WordPress Administration */

}

if( !function_exists( 'woo_get_action' ) ) {

	function woo_get_action( $switch = false ) {

		if( $switch ) {

			if( isset( $_GET['action'] ) )
				$action = $_GET['action'];
			else if( !isset( $action ) && isset( $_POST['action'] ) )
				$action = $_POST['action'];
			else
				$action = false;

		} else {

			if( isset( $_POST['action'] ) )
				$action = $_POST['action'];
			else if( !isset( $action ) && isset( $_GET['action'] ) )
				$action = $_GET['action'];
			else
				$action = false;

		}
		return $action;

	}

}
?>