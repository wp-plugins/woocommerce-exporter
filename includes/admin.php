<?php
// Display admin notice on screen load
function woo_ce_admin_notice( $message = '', $priority = 'updated', $screen = '' ) {

	if( $priority == false || $priority == '' )
		$priority = 'updated';
	if( $message <> '' )
		add_action( 'admin_notices', woo_ce_admin_notice_html( $message, $priority, $screen ) );

}

// HTML template for admin notice
function woo_ce_admin_notice_html( $message = '', $priority = 'updated', $screen = '' ) {

	// Display admin notice on specific screen
	if( !empty( $screen ) ) {

		global $pagenow;

		if( is_array( $screen ) ) {
			if( in_array( $pagenow, $screen ) == false )
				return;
		} else {
			if( $pagenow <> $screen )
				return;
		}

	} ?>
<div id="message" class="<?php echo $priority; ?>">
	<p><?php echo $message; ?></p>
</div>
<?php

}

// HTML template header on Store Exporter screen
function woo_ce_template_header( $title = '', $icon = 'woocommerce' ) {

	if( $title )
		$output = $title;
	else
		$output = __( 'Store Export', 'woo_ce' ); ?>
<div class="wrap">
	<div id="icon-<?php echo $icon; ?>" class="icon32 icon32-woocommerce-importer"><br /></div>
	<h2>
		<?php echo $output; ?>
		<a href="<?php echo add_query_arg( array( 'tab' => 'export', 'empty' => null ) ); ?>" class="add-new-h2"><?php _e( 'Add New', 'woo_ce' ); ?></a>
	</h2>
<?php

}

// HTML template footer on Store Exporter screen
function woo_ce_template_footer() { ?>
</div>
<!-- .wrap -->
<?php
}

// Add Export and Docs links to the Plugins screen
function woo_ce_add_settings_link( $links, $file ) {

	static $this_plugin;

	if( !$this_plugin ) $this_plugin = plugin_basename( __FILE__ );
	if( $file == $this_plugin ) {
		$docs_url = 'http://www.visser.com.au/docs/';
		$docs_link = sprintf( '<a href="%s" target="_blank">' . __( 'Docs', 'woo_ce' ) . '</a>', $docs_url );
		$export_link = sprintf( '<a href="%s">' . __( 'Export', 'woo_ce' ) . '</a>', add_query_arg( 'page', 'woo_ce', 'admin.php' ) );
		array_unshift( $links, $docs_link );
		array_unshift( $links, $export_link );
	}
	return $links;

}
add_filter( 'plugin_action_links', 'woo_ce_add_settings_link', 10, 2 );

// Load CSS and jQuery scripts for Store Exporter screen
function woo_ce_enqueue_scripts( $hook ) {

	$page = 'woocommerce_page_woo_ce';
	if( $page == $hook ) {

		// Simple check that WooCommerce is activated
		if( class_exists( 'WooCommerce' ) ) {

			global $woocommerce;

			// Load WooCommerce default Admin styling
			wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css' );

		}

		// Date Picker
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui-datepicker', plugins_url( '/templates/admin/jquery-ui-datepicker.css', WOO_CE_RELPATH ) );

		// Chosen
		wp_enqueue_script( 'jquery-chosen', plugins_url( '/js/chosen.jquery.js', WOO_CE_RELPATH ), array( 'jquery' ) );
		wp_enqueue_style( 'jquery-chosen', plugins_url( '/templates/admin/chosen.css', WOO_CE_RELPATH ) );

		// Common
		wp_enqueue_style( 'woo_ce_styles', plugins_url( '/templates/admin/export.css', WOO_CE_RELPATH ) );
		wp_enqueue_script( 'woo_ce_scripts', plugins_url( '/templates/admin/export.js', WOO_CE_RELPATH ), array( 'jquery', 'jquery-ui-sortable' ) );

	}

}
add_action( 'admin_enqueue_scripts', 'woo_ce_enqueue_scripts' );

// HTML active class for the currently selected tab on the Store Exporter screen
function woo_ce_admin_active_tab( $tab_name = null, $tab = null ) {

	if( isset( $_GET['tab'] ) && !$tab )
		$tab = $_GET['tab'];
	else if( !isset( $_GET['tab'] ) && woo_ce_get_option( 'skip_overview', false ) )
		$tab = 'export';
	else
		$tab = 'overview';

	$output = '';
	if( isset( $tab_name ) && $tab_name ) {
		if( $tab_name == $tab )
			$output = ' nav-tab-active';
	}
	echo $output;

}

// HTML template for each tab on the Store Exporter screen
function woo_ce_tab_template( $tab = '' ) {

	if( !$tab )
		$tab = 'overview';

	// Store Exporter Deluxe
	$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
	$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );
	$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/';

	switch( $tab ) {

		case 'overview':
			$skip_overview = woo_ce_get_option( 'skip_overview', false );
			break;

		case 'export':
			$export_type = ( isset( $_POST['dataset'] ) ? $_POST['dataset'] : 'products' );

			$products = woo_ce_return_count( 'products' );
			$categories = woo_ce_return_count( 'categories' );
			$tags = woo_ce_return_count( 'tags' );
			$orders = woo_ce_return_count( 'orders' );
			$customers = woo_ce_return_count( 'customers' );
			$users = woo_ce_return_count( 'users' );
			$coupons = woo_ce_return_count( 'coupons' );

			if( $product_fields = woo_ce_get_product_fields() ) {
				foreach( $product_fields as $key => $product_field ) {
					if( !isset( $product_fields[$key]['disabled'] ) )
						$product_fields[$key]['disabled'] = 0;
				}
				$args = array(
					'hide_empty' => 1
				);
				$product_categories = woo_ce_get_product_categories( $args );
				$args = array(
					'hide_empty' => 1
				);
				$product_tags = woo_ce_get_product_tags( $args );
				$product_statuses = get_post_statuses();
				$product_statuses['trash'] = __( 'Trash', 'woo_ce' );
				$product_types = woo_ce_get_product_types();
				$product_orderby = woo_ce_get_option( 'product_orderby', 'ID' );
				$product_order = woo_ce_get_option( 'product_order', 'DESC' );
			}
			if( $category_fields = woo_ce_get_category_fields() ) {
				$category_orderby = woo_ce_get_option( 'category_orderby', 'ID' );
				$category_order = woo_ce_get_option( 'category_order', 'DESC' );
			}
			if( $tag_fields = woo_ce_get_tag_fields() ) {
				$tag_orderby = woo_ce_get_option( 'tag_orderby', 'ID' );
				$tag_order = woo_ce_get_option( 'tag_order', 'DESC' );
			}
			$order_fields = woo_ce_get_order_fields();
			$customer_fields = woo_ce_get_customer_fields();
			$user_fields = woo_ce_get_user_fields();
			$coupon_fields = woo_ce_get_coupon_fields();

			// Export options
			$upsell_formatting = woo_ce_get_option( 'upsell_formatting', 1 );
			$crosssell_formatting = woo_ce_get_option( 'crosssell_formatting', 1 );
			$limit_volume = woo_ce_get_option( 'limit_volume' );
			$offset = woo_ce_get_option( 'offset' );
			break;

		case 'archive':
			if( isset( $_GET['deleted'] ) ) {
				$message = __( 'Archived export has been deleted.', 'woo_ce' );
				woo_ce_admin_notice( $message );
			}
			if( $files = woo_ce_get_archive_files() ) {
				foreach( $files as $key => $file )
					$files[$key] = woo_ce_get_archive_file( $file );
			}
			break;

		case 'settings':
			$export_filename = woo_ce_get_option( 'export_filename', 'woo-export_%dataset%-%date%.csv' );
			$delete_csv = woo_ce_get_option( 'delete_csv', 0 );
			$timeout = woo_ce_get_option( 'timeout', 0 );
			$encoding = woo_ce_get_option( 'encoding', 'UTF-8' );
			$bom = woo_ce_get_option( 'bom', 1 );
			$delimiter = woo_ce_get_option( 'delimiter', ',' );
			$category_separator = woo_ce_get_option( 'category_separator', '|' );
			$escape_formatting = woo_ce_get_option( 'escape_formatting', 'all' );
			$date_format = woo_ce_get_option( 'date_format', 'd/m/Y' );
			$file_encodings = ( function_exists( 'mb_list_encodings' ) ? mb_list_encodings() : false );
			break;

		case 'tools':
			// Product Importer Deluxe
			if( function_exists( 'woo_pd_init' ) ) {
				$woo_pd_url = add_query_arg( 'page', 'woo_pd' );
				$woo_pd_target = false;
			} else {
				$woo_pd_url = 'http://www.visser.com.au/woocommerce/plugins/product-importer-deluxe/';
				$woo_pd_target = ' target="_blank"';
			}
			break;

	}
	if( $tab )
		include_once( WOO_CE_PATH . 'templates/admin/tabs-' . $tab . '.php' );

}

// HTML template for header prompt on Store Exporter screen
function woo_ce_support_donate() {

	$output = '';
	$show = true;
	if( function_exists( 'woo_vl_we_love_your_plugins' ) ) {
		if( in_array( WOO_CE_DIRNAME, woo_vl_we_love_your_plugins() ) )
			$show = false;
	}
	if( $show ) {
		$donate_url = 'http://www.visser.com.au/#donations';
		$rate_url = 'http://wordpress.org/support/view/plugin-reviews/' . WOO_CE_DIRNAME;
		$output = '
<div id="support-donate_rate" class="support-donate_rate">
	<p>' . sprintf( __( '<strong>Like this Plugin?</strong> %s and %s.', 'woo_ce' ), '<a href="' . $donate_url . '" target="_blank">' . __( 'Donate to support this Plugin', 'woo_ce' ) . '</a>', '<a href="' . add_query_arg( array( 'rate' => '5' ), $rate_url ) . '#postform" target="_blank">rate / review us on WordPress.org</a>' ) . '</p>
</div>
';
	}
	echo $output;

}
?>
