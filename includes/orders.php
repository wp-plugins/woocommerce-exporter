<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	// HTML template for disabled Filter Orders by Date widget on Store Exporter screen
	function woo_ce_orders_filter_by_date() {

		$current_month = date( 'F' );
		$last_month = date( 'F', mktime( 0, 0, 0, date( 'n' )-1, 1, date( 'Y' ) ) );
		$order_dates_from = '-';
		$order_dates_to = '-';

		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-date" /> <?php _e( 'Filter Orders by Order Date', 'woo_ce' ); ?></label></p>
<div id="export-orders-filters-date" class="separator">
	<ul>
		<li>
			<label><input type="radio" name="order_dates_filter" value="current_month" disabled="disabled" /> <?php _e( 'Current month', 'woo_ce' ); ?> (<?php echo $current_month; ?>)</label>
		</li>
		<li>
			<label><input type="radio" name="order_dates_filter" value="last_month" disabled="disabled" /> <?php _e( 'Last month', 'woo_ce' ); ?> (<?php echo $last_month; ?>)</label>
		</li>
		<li>
			<label><input type="radio" name="order_dates_filter" value="last_quarter" disabled="disabled" /> <?php _e( 'Last quarter', 'woo_ce' ); ?> (Nov. - Jan.)</label>
		</li>
		<li>
			<label><input type="radio" name="order_dates_filter" value="manual" disabled="disabled" /> <?php _e( 'Manual', 'woo_ce' ); ?></label>
			<div style="margin-top:0.2em;">
				<input type="text" size="10" maxlength="10" id="order_dates_from" name="order_dates_from" value="<?php echo $order_dates_from; ?>" class="text" disabled="disabled" /> to <input type="text" size="10" maxlength="10" id="order_dates_to" name="order_dates_to" value="<?php echo $order_dates_to; ?>" class="text" disabled="disabled" />
				<p class="description"><?php _e( 'Filter the dates of Orders to be included in the export. Default is the date of the first order to today.', 'woo_ce' ); ?></p>
			</div>
		</li>
	</ul>
</div>
<!-- #export-orders-filters-date -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by Customer widget on Store Exporter screen
	function woo_ce_orders_filter_by_customer() {

		ob_start(); ?>
<p><label for="order_customer"><?php _e( 'Filter Orders by Customer', 'woo_ce' ); ?></label></p>
<div id="export-orders-filters-customer" class="separator">
	<select id="order_customer" name="order_customer" disabled="disabled">
		<option value=""><?php _e( 'Show all customers', 'woo_ce' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Filter Orders by Customer (unique e-mail address) to be included in the export. Default is to include all Orders.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-customer -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by User Role widget on Store Exporter screen
	function woo_ce_orders_filter_by_user_role() {

		$user_roles = woo_ce_get_user_roles();
		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-user_role" /> <?php _e( 'Filter Orders by User Role', 'woo_ce' ); ?></label></p>
<div id="export-orders-filters-user_role" class="separator">
	<ul>
<?php foreach( $user_roles as $key => $user_role ) { ?>
		<li><label><input type="checkbox" name="order_filter_user_role[<?php echo $key; ?>]" value="<?php echo $key; ?>" disabled="disabled" /> <?php echo ucfirst( $user_role['name'] ); ?></label></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the User Roles you want to filter exported Orders by. Default is to include all User Role options.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-user_role -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by Coupon Code widget on Store Exporter screen
	function woo_ce_orders_filter_by_coupon() {

		$args = array(
			'coupon_orderby' => 'ID',
			'coupon_order' => 'DESC'
		);
		$coupons = woo_ce_get_coupons( $args );
		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-coupon" /> <?php _e( 'Filter Orders by Coupon Code', 'woo_ce' ); ?></label></p>
<div id="export-orders-filters-coupon" class="separator">
	<ul>
<?php foreach( $coupons as $key => $coupon ) { ?>
		<li><label><input type="checkbox" name="order_filter_coupon[<?php echo $key; ?>]" disabled="disabled" /> <?php echo get_the_title( $coupon ); ?></label></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the Coupon Codes you want to filter exported Orders by. Default is to include all Orders with and without assigned Coupon Codes.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-coupon -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Order Items Formatting on Store Exporter screen
	function woo_ce_orders_items_formatting() {

		ob_start(); ?>
<tr class="export-options order-options">
	<th><label for="order_items"><?php _e( 'Order items formatting', 'woo_ce' ); ?></label></th>
	<td>
		<ul>
			<li>
				<label><input type="radio" name="order_items" value="combined" disabled="disabled" />&nbsp;<?php _e( 'Place Order Items within a grouped single Order row', 'woo_ce' ); ?></label>
				<p class="description"><?php _e( 'For example: <code>Cart Items: SKU</code> cell might contain <code>SPECK-IPHONE|INCASE-NANO|-</code> for 3 Order items within an Order', 'woo_ce' ); ?></p>
			</li>
			<li>
				<label><input type="radio" name="order_items" value="unique" disabled="disabled" />&nbsp;<?php _e( 'Place Order Items on individual cells within a single Order row', 'woo_ce' ); ?></label>
				<p class="description"><?php _e( 'For example: <code>Cart Items: SKU</code> would become <code>Cart Item #1: SKU</code> with <codeSPECK-IPHONE</code> for the first Order item within an Order', 'woo_ce' ); ?></p>
			</li>
			<li>
				<label><input type="radio" name="order_items" value="individual" disabled="disabled" />&nbsp;<?php _e( 'Place each Order Item within their own Order row', 'woo_ce' ); ?></label>
				<p class="description"><?php _e( 'For example: An Order with 3 Order items will display a single Order item on each row', 'woo_ce' ); ?></p>
			</li>
		</ul>
		<p class="description"><?php _e( 'Choose how you would like Order Items to be presented within Orders.', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
		ob_end_flush();

	}

	// HTML template for disabled Max Order Items widget on Store Exporter screen
	function woo_ce_orders_max_order_items() {

		ob_start(); ?>
<tr class="export-options order-options">
	<th>
		<label for="max_order_items"><?php _e( 'Max unique Order items', 'woo_ce' ); ?>: </label>
	</th>
	<td>
		<input type="text" id="max_order_items" name="max_order_items" size="3" class="text" value="10" disabled="disabled" />
		<p class="description"><?php _e( 'Manage the number of Order cart item colums displayed when the \'Place Order Items on individual cells within a single Order row\' Order items formatting option is selected.', 'woo_ce' ); ?></p>
	</td>
</tr>
<?php
		ob_end_flush();

	}

	// HTML template for disabled Filter Orders by Order Status widget on Store Exporter screen
	function woo_ce_orders_filter_by_status() {

		$order_statuses = woo_ce_get_order_statuses();
		ob_start(); ?>
<p><label><input type="checkbox" id="orders-filters-status" /> <?php _e( 'Filter Orders by Order Status', 'woo_ce' ); ?></label></p>
<div id="export-orders-filters-status" class="separator">
	<ul>
<?php foreach( $order_statuses as $order_status ) { ?>
		<li><label><input type="checkbox" name="order_filter_status[<?php echo $order_status->name; ?>]" value="<?php echo $order_status->name; ?>" disabled="disabled" /> <?php echo ucfirst( $order_status->name ); ?></label></li>
<?php } ?>
	</ul>
	<p class="description"><?php _e( 'Select the Order Status you want to filter exported Orders by. Default is to include all Order Status options.', 'woo_ce' ); ?></p>
</div>
<!-- #export-orders-filters-status -->
<?php
		ob_end_flush();

	}

	// HTML template for disabled Order Sorting widget on Store Exporter screen
	function woo_ce_orders_order_sorting() {

		ob_start(); ?>
<p><label><?php _e( 'Order Sorting', 'woo_ce' ); ?></label></p>
<div>
	<select name="order_orderby" disabled="disabled">
		<option value="ID"><?php _e( 'Order ID', 'woo_ce' ); ?></option>
		<option value="title"><?php _e( 'Order Name', 'woo_ce' ); ?></option>
		<option value="date"><?php _e( 'Date Created', 'woo_ce' ); ?></option>
		<option value="modified"><?php _e( 'Date Modified', 'woo_ce' ); ?></option>
		<option value="rand"><?php _e( 'Random', 'woo_ce' ); ?></option>
	</select>
	<select name="order_order" disabled="disabled">
		<option value="ASC"><?php _e( 'Ascending', 'woo_ce' ); ?></option>
		<option value="DESC"><?php _e( 'Descending', 'woo_ce' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Select the sorting of Orders within the exported file. By default this is set to export Orders by Order ID in Desending order.', 'woo_ce' ); ?></p>
</div>
<?php
		ob_end_flush();

	}

	// HTML template for disabled Custom Orders widget on Store Exporter screen
	function woo_ce_orders_custom_fields() {

		$troubleshooting_url = 'http://www.visser.com.au/documentation/store-exporter-deluxe/usage/';
		ob_start(); ?>
<form method="post" id="export-orders-custom-fields" class="export-options order-options">
	<div id="poststuff">

		<div class="postbox" id="export-options">
			<h3 class="hndle"><?php _e( 'Custom Order Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'To include additional custom Order and Order Item meta in the Export Orders table above fill the Orders and Order Items text box then click Save Custom Fields.', 'woo_ce' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<label><?php _e( 'Order meta', 'woo_ce' ); ?></label>
						</th>
						<td>
							<textarea name="custom_orders" rows="5" cols="70" disabled="disabled">-</textarea>
							<p class="description"><?php _e( 'Include additional custom Order meta in your exported CSV by adding each custom Order meta name to a new line above.<br />For example: <code>Customer UA, Customer IP Address</code>', 'woo_ce' ); ?></p>
						</td>
					</tr>

					<tr>
						<th>
							<label><?php _e( 'Order Item meta', 'woo_ce' ); ?></label>
						</th>
						<td>
							<textarea name="custom_order_items" rows="5" cols="70" disabled="disabled">-</textarea>
							<p class="description"><?php _e( 'Include additional custom Order Item meta in your exported CSV by adding each custom Order Item meta name to a new line above.<br />For example: <code>Personalized Message</code>.', 'woo_ce' ); ?></p>
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Save Custom Fields', 'woo_ce' ); ?>" class="button-primary" />
				</p>
				<p class="description"><?php printf( __( 'For more information on custom Order and Order Item meta consult our <a href="%s" target="_blank">online documentation</a>.', 'woo_ce' ), $troubleshooting_url ); ?></p>
			</div>
			<!-- .inside -->
		</div>
		<!-- .postbox -->

	</div>
	<input type="hidden" name="action" value="update" />
</form>
<?php
		ob_end_flush();

	}

	/* End of: WordPress Administration */

}

// Returns a list of Order export columns
function woo_ce_get_order_fields( $format = 'full' ) {

	$fields = array();
	$fields[] = array(
		'name' => 'purchase_id',
		'label' => __( 'Purchase ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'purchase_total',
		'label' => __( 'Order Total', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_discount',
		'label' => __( 'Order Discount', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'coupon_code',
		'label' => __( 'Coupon Code', 'woo_ce' )
	);
/*
	$fields[] = array(
		'name' => 'order_incl_tax',
		'label' => __( 'Order Incl. Tax', 'woo_ce' )
	);
*/
	$fields[] = array(
		'name' => 'order_excl_tax',
		'label' => __( 'Order Excl. Tax', 'woo_ce' )
	);
/*
	$fields[] = array(
		'name' => 'order_tax_rate',
		'label' => __( 'Order Tax Rate', 'woo_ce' )
	);
*/
	$fields[] = array(
		'name' => 'order_sales_tax',
		'label' => __( 'Sales Tax Total', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_shipping_tax',
		'label' => __( 'Shipping Tax Total', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'payment_gateway_id',
		'label' => __( 'Payment Gateway ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'payment_gateway',
		'label' => __( 'Payment Gateway', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_method_id',
		'label' => __( 'Shipping Method ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_method',
		'label' => __( 'Shipping Method', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_cost',
		'label' => __( 'Shipping Cost', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'payment_status',
		'label' => __( 'Payment Status', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_key',
		'label' => __( 'Order Key', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'purchase_date',
		'label' => __( 'Purchase Date', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'purchase_time',
		'label' => __( 'Purchase Time', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'customer_note',
		'label' => __( 'Customer Note', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_notes',
		'label' => __( 'Order Notes', 'woo_ce' )
	);
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
		'name' => 'ip_address',
		'label' => __( 'Checkout IP Address', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'browser_agent',
		'label' => __( 'Checkout Browser Agent', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_full_name',
		'label' => __( 'Billing: Full Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_first_name',
		'label' => __( 'Billing: First Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_last_name',
		'label' => __( 'Billing: Last Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_company',
		'label' => __( 'Billing: Company', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_address',
		'label' => __( 'Billing: Street Address', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_city',
		'label' => __( 'Billing: City', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_postcode',
		'label' => __( 'Billing: ZIP Code', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_state',
		'label' => __( 'Billing: State (prefix)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_state_full',
		'label' => __( 'Billing: State', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_country',
		'label' => __( 'Billing: Country (prefix)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_country_full',
		'label' => __( 'Billing: Country', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_phone',
		'label' => __( 'Billing: Phone Number', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'billing_email',
		'label' => __( 'Billing: E-mail Address', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_full_name',
		'label' => __( 'Shipping: Full Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_first_name',
		'label' => __( 'Shipping: First Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_last_name',
		'label' => __( 'Shipping: Last Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_company',
		'label' => __( 'Shipping: Company', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_address',
		'label' => __( 'Shipping: Street Address', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_city',
		'label' => __( 'Shipping: City', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_postcode',
		'label' => __( 'Shipping: ZIP Code', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_state',
		'label' => __( 'Shipping: State (prefix)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_state_full',
		'label' => __( 'Shipping: State', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_country',
		'label' => __( 'Shipping: Country (prefix)', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'shipping_country_full',
		'label' => __( 'Shipping: Country', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_product_id',
		'label' => __( 'Order Items: Product ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_variation_id',
		'label' => __( 'Order Items: Variation ID', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_sku',
		'label' => __( 'Order Items: SKU', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_name',
		'label' => __( 'Order Items: Product Name', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_variation',
		'label' => __( 'Order Items: Product Variation', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_tax_class',
		'label' => __( 'Order Items: Tax Class', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_quantity',
		'label' => __( 'Order Items: Quantity', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_total',
		'label' => __( 'Order Items: Total', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_subtotal',
		'label' => __( 'Order Items: Subtotal', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_tax',
		'label' => __( 'Order Items: Tax', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_tax_subtotal',
		'label' => __( 'Order Items: Tax Subtotal', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_type',
		'label' => __( 'Order Items: Type', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_category',
		'label' => __( 'Order Items: Category', 'woo_ce' )
	);
	$fields[] = array(
		'name' => 'order_items_tag',
		'label' => __( 'Order Items: Tag', 'woo_ce' )
	);
/*
	$fields[] = array(
		'name' => '',
		'label' => __( '', 'woo_ce' )
	);
*/

	// Allow Plugin/Theme authors to add support for additional Order columns
	$fields = apply_filters( 'woo_ce_order_fields', $fields );

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

function woo_ce_format_order_date( $date ) {

	$output = $date;
	if( $date )
		$output = str_replace( '/', '-', $date );
	return $output;

}

// Returns a list of WooCommerce Order statuses
function woo_ce_get_order_statuses() {

	$args = array(
		'hide_empty' => false
	);
	$terms = get_terms( 'shop_order_status', $args );
	if( !empty( $terms ) && is_wp_error( $terms ) == false )
		return $terms;

}

?>