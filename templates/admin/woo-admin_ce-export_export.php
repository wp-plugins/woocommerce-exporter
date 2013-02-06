<ul class="subsubsub">
	<li><a href="#export-type"><?php _e( 'Export Type', 'woo_ce' ); ?></a> |</li>
<?php if( $product_fields ) { ?>
	<li><a href="#export-products"><?php _e( 'Export: Products', 'woo_ce' ); ?></a> |</li>
<?php } ?>
<?php if( $sale_fields ) { ?>
	<li><a href="#export-sales"><?php _e( 'Export: Sales', 'woo_ce' ); ?></a> |</li>
<?php } ?>
<?php if( $customer_fields ) { ?>
	<li><a href="#export-customers"><?php _e( 'Export: Customers', 'woo_ce' ); ?></a> |</li>
<?php } ?>
<?php if( $coupons ) { ?>
	<li><a href="#export-coupons"><?php _e( 'Export: Coupons', 'woo_ce' ); ?></a> |</li>
<?php } ?>
	<li><a href="#export-options"><?php _e( 'Export Options', 'woo_ce' ); ?></a></li>
</ul>
<br class="clear" />
<h3><?php _e( 'Export Type', 'woo_ce' ); ?></h3>
<!--
<p><?php _e( 'When you click the Export button below Store Export will create a CSV file for you to save to your computer.', 'woo_ce' ); ?></p>
<p><?php _e( 'This formatted CSV file will contain the Product details from your WooCommerce store.', 'woo_ce' ); ?></p>
<p><?php echo sprintf( __( 'Once you\'ve saved the download file, you can use <a href="%s"%s>Product Importer Deluxe</a> to merge changes back into your store, or import store details into another WooCommerce instance.', 'woo_ce' ), $woo_pd_url, $woo_pd_target ); ?></p>
-->
<form method="post" onsubmit="showProgress()">
	<div id="poststuff">

		<div class="postbox" id="export-type">
			<h3 class="hndle"><?php _e( 'Export Type', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Select the data type you want to export.', 'jigo_ce' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<input type="radio" id="products" name="dataset" value="products"<?php echo disabled( $products, 0 ) . checked( $dataset, 'products' ); ?> />
							<label for="products"><?php _e( 'Products', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $products; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="categories" name="dataset" value="categories"<?php echo disabled( $categories, 0 ) . checked( $dataset, 'categories' ); ?> />
							<label for="categories"><?php _e( 'Categories', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $categories; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="tags" name="dataset" value="tags"<?php echo disabled( $tags, 0 ) . checked( $dataset, 'tags' ); ?> />
							<label for="tags"><?php _e( 'Tags', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $tags; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="sales" name="dataset" value="sales"<?php echo disabled( $sales, 0 ) . checked( $dataset, 'sales' ) ?>/>
							<label for="sales"><?php _e( 'Sales', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $sales; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="coupons" name="dataset" value="coupons"<?php echo disabled( $coupons, 0 ) . checked( $dataset, 'coupons' ); ?> />
							<label for="coupons"><?php _e( 'Coupons', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $coupons; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="customers" name="dataset" value="customers"<?php echo disabled( $customers, 0 ) . checked( $dataset, 'customers' ); ?>/>
							<label for="customers"><?php _e( 'Customers', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $customers; ?>)</span>
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
				<p><a href="#"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="#"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
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
					<input type="submit" value="<?php _e( 'Export Products', 'woo_ce' ); ?> " class="button-primary" />
				</p>
	<?php } else { ?>
				<p><?php _e( 'No Products have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

<?php } ?>

	</div>

<?php if( $sale_fields ) { ?>
	<h3><?php _e( 'Export: Sales', 'woo_ce' ); ?></h3>
	<div id="poststuff">

		<div class="postbox" id="export-sales">
			<h3 class="hndle"><?php _e( 'Sale Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
	<?php if( $sales ) { ?>
				<p class="description"><?php _e( 'Select the Sale fields you would like to export.', 'woo_ce' ); ?></p>
				<p><a href="#"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="#"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
				<table>

		<?php foreach( $sale_fields as $sale_field ) { ?>
					<tr>
						<td>
							<label>
								<input type="checkbox" name="sale_fields[<?php echo $sale_field['name']; ?>]" class="sale_field"<?php checked( $sale_field['default'], 1 ); ?> />
								<?php echo $sale_field['label']; ?>
							</label>
						</td>
					</tr>

		<?php } ?>
				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Export Sales', 'woo_ce' ); ?>" class="button-primary" />
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
				<p><a href="#"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="#"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
				<table>

		<?php foreach( $customer_fields as $customer_field ) { ?>
					<tr>
						<td>
							<label>
								<input type="checkbox" name="customer_fields[<?php echo $customer_field['name']; ?>]" class="customer_field"<?php checked( $customer_field['default'], 1 ); ?> />
								<?php echo $customer_field['label']; ?>
							</label>
						</td>
					</tr>

		<?php } ?>
				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Export Customers', 'woo_ce' ); ?>" class="button-primary" />
				</p>
	<?php } else { ?>
				<p><?php _e( 'No Customers have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

	</div>

<?php } ?>
<?php if( $customer_fields ) { ?>
	<h3><?php _e( 'Export: Coupons', 'woo_ce' ); ?></h3>
	<div id="poststuff">

		<div class="postbox" id="export-coupons">
			<h3 class="hndle"><?php _e( 'Customer Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
	<?php if( $coupons ) { ?>
				<p class="description"><?php _e( 'Select the Customer fields you would like to export.', 'woo_ce' ); ?></p>
				<p><a href="#"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="#"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
				<table>

		<?php foreach( $coupon_fields as $coupon_field ) { ?>
					<tr>
						<td>
							<label>
								<input type="checkbox" name="coupon_fields[<?php echo $coupon_field['name']; ?>]" class="coupon_field"<?php checked( $coupon_field['default'], 1 ); ?> />
								<?php echo $coupon_field['label']; ?>
							</label>
						</td>
					</tr>

		<?php } ?>
				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Export Coupons', 'woo_ce' ); ?>" class="button-primary" />
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

					<tr>
						<th>
							<label for="delimiter"><?php _e( 'Field delimiter', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="text" size="3" id="delimiter" name="delimiter" value="," size="1" class="text" />
							<p class="description"><?php _e( 'The field delimiter is the character separating each cell in your CSV. This is typically the \',\' (comma) character.', 'woo_pc' ); ?></p>
						</td>
					</tr>

					<tr>
						<th>
							<label for="category_separator"><?php _e( 'Category separator', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="text" size="3" id="category_separator" name="category_separator" value="|" size="1" class="text" />
							<p class="description"><?php _e( 'The Product Category separator allows you to assign individual Products to multiple Product Categories/Tags/Images at a time. It is suggested to use the \'|\' (vertical pipe) character between each item. For instance: <code>Clothing|Mens|Shirts</code>.', 'woo_ce' ); ?></p>
						</td>
					</tr>

<?php if( !ini_get( 'safe_mode' ) ) { ?>
					<tr>
						<th>
							<label for="timeout"><?php _e( 'Script timeout', 'woo_ce' ); ?>: </label>
						</th>
						<td>
							<select id="timeout" name="timeout">
								<option value="600"><?php echo sprintf( __( '%s minutes', 'woo_ce' ), 10 ); ?></option>
								<option value="1800"><?php echo sprintf( __( '%s minutes', 'woo_ce' ), 30 ); ?></option>
								<option value="3600"><?php echo sprintf( __( '%s hour', 'woo_ce' ), 1 ); ?></option>
								<option value="0" selected="selected"><?php _e( 'Unlimited', 'woo_ce' ); ?>&nbsp;</option>
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
