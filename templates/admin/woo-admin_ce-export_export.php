<?php
if( isset( $_POST['dataset'] ) )
	$dataset = $_POST['dataset'];
else
	$dataset = 'products';

$products = woo_ce_return_count( 'products' );
$categories = woo_ce_return_count( 'categories' );
$tags = woo_ce_return_count( 'tags' );
$sales = woo_ce_return_count( 'orders' );
$customers = woo_ce_return_count( 'customers' );

$product_fields = woo_ce_get_product_fields();
$sale_fields = woo_ce_get_sale_fields();
?>
<ul class="subsubsub">
	<li><a href="#export-type"><?php _e( 'Export Type', 'woo_ce' ); ?></a> |</li>
	<li><a href="#export-products"><?php _e( 'Export: Products', 'woo_ce' ); ?></a> |</li>
	<li><a href="#export-sales"><?php _e( 'Export: Sales', 'woo_ce' ); ?></a> |</li>
	<li><a href="#export-options"><?php _e( 'Export Options', 'woo_ce' ); ?></a></li>
</ul>
<br class="clear" />
<h3><?php _e( 'Export Type', 'woo_ce' ); ?></h3>
<!--
<p><?php _e( 'When you click the Export button below Store Export will create a CSV file for you to save to your computer.', 'woo_ce' ); ?></p>
<p><?php _e( 'This formatted CSV file will contain the Product details from your WooCommerce store.', 'woo_ce' ); ?></p>
<p><?php _e( 'Once you\'ve saved the download file, you can use <a href="' . $woo_pd_url . '"' . $woo_pd_target . '>Product Importer Deluxe</a> to merge changes back into your store, or import store details into another WooCommerce instance.', 'woo_ce' ); ?></p>
-->
<form method="post" onsubmit="showProgress()">
	<div id="poststuff">

		<div class="postbox" id="export-type">
			<h3 class="hndle"><?php _e( 'Export Type', 'woo_ce' ); ?></h3>
			<div class="inside">
				<table class="form-table">

					<tr>
						<th>
							<label for="products"><?php _e( 'Products', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="radio" id="products" name="dataset" value="products"<?php echo disabled( $products, 0 ) . checked( $dataset, 'products' ); ?>/> (<?php echo $products; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="sales"><?php _e( 'Sales', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="radio" id="sales" name="dataset" value="sales"<?php echo disabled( $sales, 0 ) . checked( $dataset, 'sales' ) ?>/> (<?php echo $sales; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="customers"><?php _e( 'Customers', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="radio" id="customers" name="dataset" value="customers"<?php echo disabled( $customers, 0 ) . checked( $dataset, 'customers' ); ?>/> (<?php echo $customers; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="tags"><?php _e( 'Categories', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="radio" id="categories" name="dataset" value="categories"<?php echo disabled( $categories, 0 ) . checked( $dataset, 'categories' ); ?> /> (<?php echo $categories; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="tags"><?php _e( 'Tags', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="radio" id="tags" name="dataset" value="tags"<?php echo disabled( $tags, 0 ) . checked( $dataset, 'tags' ); ?> /> (<?php echo $tags; ?>)
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Export', 'woo_ce' ); ?> " class="button-primary" />
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
				<p class="description"><?php _e( 'Select the Product fields you would like to export.', 'woo_ce' ); ?></p>
				<!-- <p><a href="#"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="#"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p> -->
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
			</div>
		</div>
		<!-- .postbox -->

<?php } ?>

	</div>

	<h3><?php _e( 'Export: Sales', 'woo_ce' ); ?></h3>
	<div id="poststuff">

		<div class="postbox" id="export-sales">
			<h3 class="hndle"><?php _e( 'Sale Fields', 'woo_ce' ); ?></h3>
			<div class="inside">
				<!-- <p><a href="#"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="#"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p> -->
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
					<input type="submit" value="<?php _e( 'Export Sales', 'woo_ce' ); ?> " class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

	</div>

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
								<option value="600" selected="selected">10 <?php _e( 'minutes', 'woo_ce' ); ?>&nbsp;</option>
								<option value="1800">30 <?php _e( 'minutes', 'woo_ce' ); ?>&nbsp;</option>
								<option value="3600">1 <?php _e( 'hour', 'woo_ce' ); ?>&nbsp;</option>
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
