<ul class="subsubsub">
	<li><a href="#export-type"><?php _e( 'Export Type', 'woo_ce' ); ?></a> |</li>
<?php if( $product_fields ) { ?>
	<li><a href="#export-products"><?php _e( 'Export: Products', 'woo_ce' ); ?></a> |</li>
<?php } ?>
<?php if( $order_fields ) { ?>
	<li><a href="#export-orders"><?php _e( 'Export: Orders', 'woo_ce' ); ?></a> |</li>
<?php } ?>
<?php if( $customer_fields ) { ?>
	<li><a href="#export-customers"><?php _e( 'Export: Customers', 'woo_ce' ); ?></a> |</li>
<?php } ?>
<?php if( $coupon_fields ) { ?>
	<li><a href="#export-coupons"><?php _e( 'Export: Coupons', 'woo_ce' ); ?></a> |</li>
<?php } ?>
	<li><a href="#export-options"><?php _e( 'Export Options', 'woo_ce' ); ?></a></li>
</ul>
<br class="clear" />
<h3><?php _e( 'Export Type', 'woo_ce' ); ?></h3>
<form method="post" onsubmit="showProgress()">
	<div id="poststuff">

		<div class="postbox" id="export-type">
			<h3 class="hndle"><?php _e( 'Export Type', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Select the data type you want to export.', 'woo_ce' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<input type="radio" id="products" name="dataset" value="products"<?php disabled( $products, 0 ); ?><?php checked( $dataset, 'products' ); ?> />
							<label for="products"><?php _e( 'Products', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $products; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="categories" name="dataset" value="categories"<?php disabled( $categories, 0 ); ?><?php checked( $dataset, 'categories' ); ?> />
							<label for="categories"><?php _e( 'Categories', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $categories; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="tags" name="dataset" value="tags"<?php disabled( $tags, 0 ); ?><?php checked( $dataset, 'tags' ); ?> />
							<label for="tags"><?php _e( 'Tags', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $tags; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="orders" name="dataset" value="orders"<?php disabled( $orders, 0 ); ?><?php disabled( $woo_cd_exists, false ); ?><?php checked( $dataset, 'orders' ); ?>/>
							<label for="orders"><?php _e( 'Orders', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $orders; ?>)</span>
<?php if( !function_exists( 'woo_cd_admin_init' ) ) { ?>
							<span class="description"> - <?php echo sprintf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
<?php } ?>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="customers" name="dataset" value="customers"<?php disabled( $customers, 0 ); ?><?php disabled( $woo_cd_exists, false ); ?><?php checked( $dataset, 'customers' ); ?>/>
							<label for="customers"><?php _e( 'Customers', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $customers; ?>)</span>
<?php if( !function_exists( 'woo_cd_admin_init' ) ) { ?>
							<span class="description"> - <?php echo sprintf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
<?php } ?>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="coupons" name="dataset" value="coupons"<?php disabled( $coupons, 0 ); ?><?php disabled( $woo_cd_exists, false ); ?><?php checked( $dataset, 'coupons' ); ?> />
							<label for="coupons"><?php _e( 'Coupons', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $coupons; ?>)</span>
<?php if( !function_exists( 'woo_cd_admin_init' ) ) { ?>
							<span class="description"> - <?php echo sprintf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
<?php } ?>
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Export', 'woo_ce' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

	</div>

	<h3><?php _e( 'Export: Products', 'woo_ce' ); ?></h3>
	<div id="poststuff">

<?php if( $product_fields ) { ?>
		<div class="postbox" id="export-products">
			<h3 class="hndle"><?php _e( 'Product Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
	<?php if( $products ) { ?>
				<p class="description"><?php _e( 'Select the Product fields you would like to export.', 'woo_ce' ); ?></p>
				<p><a href="javascript:void(0)" id="products-checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="products-uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
				<table>

		<?php foreach( $product_fields as $product_field ) { ?>
					<tr>
						<td>
							<label>
								<input type="checkbox" name="product_fields[<?php echo $product_field['name']; ?>]" class="product_field"<?php checked( $product_field['default'], 1 ); ?> />
								<?php echo $product_field['label']; ?>
							</label>
						</td>
					</tr>

		<?php } ?>
				</table>
				<p class="submit">
					<input type="submit" id="export_products" value="<?php _e( 'Export Products', 'woo_ce' ); ?> " class="button-primary" />
				</p>
	<?php } else { ?>
				<p><?php _e( 'No Products have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

<?php } ?>

	</div>

<?php if( $order_fields ) { ?>
	<h3><?php _e( 'Export: Orders', 'woo_ce' ); ?></h3>
	<div id="poststuff">

		<div class="postbox" id="export-orders">
			<h3 class="hndle"><?php _e( 'Order Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
	<?php if( $orders ) { ?>
				<p class="description"><?php _e( 'Select the Order fields you would like to export.', 'woo_ce' ); ?></p>
		<?php if( function_exists( 'woo_cd_admin_init' ) ) { ?>
				<p><a href="javascript:void(0)" id="orders-checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="orders-uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
		<?php } else { ?>
				<p>Check All | Uncheck All</p>
		<?php } ?>
				<table>

		<?php foreach( $order_fields as $order_field ) { ?>
					<tr>
						<td>
							<label>
								<input type="checkbox" name="order_fields[<?php echo $order_field['name']; ?>]" class="order_field"<?php checked( $order_field['default'], 1 ); ?><?php disabled( $woo_cd_exists, false ); ?> />
								<?php echo $order_field['label']; ?>
							</label>
						</td>
					</tr>

		<?php } ?>
				</table>
				<p class="submit">
		<?php if( function_exists( 'woo_cd_admin_init' ) ) { ?>
					<input type="submit" id="export_orders" value="<?php _e( 'Export Orders', 'woo_ce' ); ?>" class="button-primary" />
		<?php } else { ?>
					<input type="button" class="button button-disabled" value="<?php _e( 'Export Orders', 'woo_ce' ); ?>" />
		<?php } ?>
				</p>
	<?php } else { ?>
				<p><?php _e( 'No Orders have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

	</div>

<?php } ?>

<?php if( $customer_fields ) { ?>
	<h3><?php _e( 'Export: Customers', 'woo_ce' ); ?></h3>
	<div id="poststuff">

		<div class="postbox" id="export-customers">
			<h3 class="hndle"><?php _e( 'Customer Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
	<?php if( $customers ) { ?>
				<p class="description"><?php _e( 'Select the Customer fields you would like to export.', 'woo_ce' ); ?></p>
		<?php if( function_exists( 'woo_cd_admin_init' ) ) { ?>
				<p><a href="javascript:void(0)" id="customers-checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="customers-uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
		<?php } else { ?>
				<p>Uncheck All | Check All</p>
		<?php } ?>
				<table>

		<?php foreach( $customer_fields as $customer_field ) { ?>
					<tr>
						<td>
							<label>
								<input type="checkbox" name="customer_fields[<?php echo $customer_field['name']; ?>]" class="customer_field"<?php checked( $customer_field['default'], 1 ); ?><?php disabled( $woo_cd_exists, false ); ?> />
								<?php echo $customer_field['label']; ?>
							</label>
						</td>
					</tr>

		<?php } ?>
				</table>
				<p class="submit">
		<?php if( function_exists( 'woo_cd_admin_init' ) ) { ?>
					<input type="submit" id="export_customers" value="<?php _e( 'Export Customers', 'woo_ce' ); ?>" class="button-primary" />
		<?php } else { ?>
					<input type="button" class="button button-disabled" value="<?php _e( 'Export Customers', 'woo_ce' ); ?>" />
		<?php } ?>
				</p>
	<?php } else { ?>
				<p><?php _e( 'No Customers have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

	</div>

<?php } ?>
<?php if( $coupon_fields ) { ?>
	<h3><?php _e( 'Export: Coupons', 'woo_ce' ); ?></h3>
	<div id="poststuff">

		<div class="postbox" id="export-coupons">
			<h3 class="hndle"><?php _e( 'Coupon Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
	<?php if( $coupons ) { ?>
				<p class="description"><?php _e( 'Select the Coupon fields you would like to export.', 'woo_ce' ); ?></p>
		<?php if( function_exists( 'woo_cd_admin_init' ) ) { ?>
				<p><a href="javascript:void(0)" id="coupons-checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="coupons-uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
		<?php } else { ?>
				<p>Uncheck All | Check All</p>
		<?php } ?>
				<table>

		<?php foreach( $coupon_fields as $coupon_field ) { ?>
					<tr>
						<td>
							<label>
								<input type="checkbox" name="coupon_fields[<?php echo $coupon_field['name']; ?>]" class="coupon_field"<?php checked( $coupon_field['default'], 1 ); ?><?php disabled( $woo_cd_exists, false ); ?> />
								<?php echo $coupon_field['label']; ?>
							</label>
						</td>
					</tr>

		<?php } ?>
				</table>
				<p class="submit">
		<?php if( function_exists( 'woo_cd_admin_init' ) ) { ?>
					<input type="submit" id="export_coupons" value="<?php _e( 'Export Coupons', 'woo_ce' ); ?>" class="button-primary" />
		<?php } else { ?>
					<input type="button" class="button button-disabled" value="<?php _e( 'Export Coupons', 'woo_ce' ); ?>" />
		<?php } ?>
				</p>
	<?php } else { ?>
				<p><?php _e( 'No Coupons have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

	</div>

<?php } ?>

	<h3><?php _e( 'Export Options', 'woo_ce' ); ?></h3>
	<div id="poststuff">

		<div class="postbox" id="export-options">
			<h3 class="hndle"><?php _e( 'Export Options', 'woo_ce' ); ?></h3>
			<div class="inside">
				<table class="form-table">

					<?php do_action( 'woo_ce_export_options' ); ?>

					<tr>
						<th>
							<label for="delimiter"><?php _e( 'Field delimiter', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="text" size="3" id="delimiter" name="delimiter" value="<?php echo $delimiter; ?>" size="1" maxlength="1" class="text" />
							<p class="description"><?php _e( 'The field delimiter is the character separating each cell in your CSV. This is typically the \',\' (comma) character.', 'woo_pc' ); ?></p>
						</td>
					</tr>

					<tr>
						<th>
							<label for="category_separator"><?php _e( 'Category separator', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="text" size="3" id="category_separator" name="category_separator" value="<?php echo $category_separator; ?>" size="1" class="text" />
							<p class="description"><?php _e( 'The Product Category separator allows you to assign individual Products to multiple Product Categories/Tags/Images at a time. It is suggested to use the \'|\' (vertical pipe) character between each item. For instance: <code>Clothing|Mens|Shirts</code>.', 'woo_ce' ); ?></p>
						</td>
					</tr>

					<tr>
						<th>
							<label for="limit_volume"><?php _e( 'Limit volume', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="text" size="3" id="limit_volume" name="limit_volume" value="<?php echo $limit_volume; ?>" size="5" class="text" />
							<p class="description"><?php _e( 'Limit volume allows for partial exporting of a dataset. This is useful when encountering timeout and/or memory errors during the default export. By default this is not used and is left empty.', 'woo_ce' ); ?></p>
						</td>
					</tr>

					<tr>
						<th>
							<label for="offset"><?php _e( 'Volume offset', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="text" size="3" id="offset" name="offset" value="<?php echo $offset; ?>" size="5" class="text" />
							<p class="description"><?php _e( 'Volume offset allows for partial exporting of a dataset, to be used in conjuction with Limit volme option above. By default this is not used and is left empty.', 'woo_ce' ); ?></p>
						</td>
					</tr>

<?php if( !ini_get( 'safe_mode' ) ) { ?>
					<tr>
						<th>
							<label for="timeout"><?php _e( 'Script timeout', 'woo_ce' ); ?>: </label>
						</th>
						<td>
							<select id="timeout" name="timeout">
								<option value="600"<?php selected( $timeout, 600 ); ?>><?php echo sprintf( __( '%s minutes', 'woo_ce' ), 10 ); ?></option>
								<option value="1800"<?php selected( $timeout, 1800 ); ?>><?php echo sprintf( __( '%s minutes', 'woo_ce' ), 30 ); ?></option>
								<option value="3600"<?php selected( $timeout, 3600 ); ?>><?php echo sprintf( __( '%s hour', 'woo_ce' ), 1 ); ?></option>
								<option value="0"<?php selected( $timeout, 0 ); ?>><?php _e( 'Unlimited', 'woo_ce' ); ?></option>
							</select>
							<p class="description"><?php _e( 'Script timeout defines how long WooCommerce Exporter is \'allowed\' to process your CSV file, once the time limit is reached the export process halts.', 'woo_ce' ); ?></p>
						</td>
					</tr>
<?php } ?>
				</table>
			</div>
		</div>
		<!-- .postbox -->

	</div>
	<input type="hidden" name="action" value="export" />
</form>

<?php if( function_exists( 'woo_cd_admin_init' ) ) { ?>
<form method="post">
	<h3><?php _e( 'Custom Fields', 'woo_ce' ); ?></h3>
	<p><?php _e( 'To include additional custom Order meta in the Export Orders table above fill the Orders text box then click Save Custom Fields.', 'woo_ce' ); ?></p>
	<div id="poststuff">

		<div class="postbox" id="export-options">
			<h3 class="hndle"><?php _e( 'Custom Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
				<table class="form-table">

					<tr>
						<th>
							<label>Orders</label>
						</th>
						<td>
							<textarea name="custom_orders" rows="5" cols="70"><?php echo $custom_orders; ?></textarea>
							<p class="description"><?php _e( 'Include additional custom Order meta in your exported CSV by adding each custom Order meta to a new line above.', 'woo_cd' ); ?></p>
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Save Custom Fields', 'woo_ce' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

	</div>
	<input type="hidden" name="action" value="update" />
</form>
<?php } ?>