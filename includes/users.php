<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	// HTML template for disabled User Sorting widget on Store Exporter screen
	function woo_ce_users_user_sorting() {

		ob_start(); ?>
<p><label><?php _e( 'User Sorting', 'woo_ce' ); ?></label></p>
<div>
	<select name="user_orderby" disabled="disabled">
		<option value="ID"><?php _e( 'User ID', 'woo_ce' ); ?></option>
		<option value="display_name"><?php _e( 'Display Name', 'woo_ce' ); ?></option>
		<option value="user_name"><?php _e( 'Name', 'woo_ce' ); ?></option>
		<option value="user_login"><?php _e( 'Username', 'woo_ce' ); ?></option>
		<option value="nicename"><?php _e( 'Nickname', 'woo_ce' ); ?></option>
		<option value="email"><?php _e( 'E-mail', 'woo_ce' ); ?></option>
		<option value="url"><?php _e( 'Website', 'woo_ce' ); ?></option>
		<option value="registered"><?php _e( 'Date Registered', 'woo_ce' ); ?></option>
		<option value="rand"><?php _e( 'Random', 'woo_ce' ); ?></option>
	</select>
	<select name="user_order" disabled="disabled">
		<option value="ASC"><?php _e( 'Ascending', 'woo_ce' ); ?></option>
		<option value="DESC"><?php _e( 'Descending', 'woo_ce' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Select the sorting of Users within the exported file. By default this is set to export User by User ID in Desending order.', 'woo_ce' ); ?></p>
</div>
<?php
		ob_end_flush();

	}

	/* End of: WordPress Administration */

}

// Returns a list of User export columns
function woo_ce_get_user_fields( $format = 'full' ) {

	$fields = array();
	$fields[] = array(
		'name' => 'user_id',
		'label' => __( 'User ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'user_name',
		'label' => __( 'Username', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'user_role',
		'label' => __( 'User Role', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'first_name',
		'label' => __( 'First Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'last_name',
		'label' => __( 'Last Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'full_name',
		'label' => __( 'Full Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'nick_name',
		'label' => __( 'Nickname', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'email',
		'label' => __( 'E-mail', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'url',
		'label' => __( 'Website', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'date_registered',
		'label' => __( 'Date Registered', 'woo_ce' )
	);

/*
	$fields[] = array(
		'name' => '',
		'label' => __( '', 'woo_ce' )
	);
*/

	// Allow Plugin/Theme authors to add support for additional User columns
	$fields = apply_filters( 'woo_ce_user_fields', $fields );

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
			break;

	}

}
?>