<ul class="subsubsub">
	<li><a href="#export-type"><?php _e( 'Export Type', 'woo_ce' ); ?></a> |</li>
	<li><a href="#export-options"><?php _e( 'Export Options', 'woo_ce' ); ?></a></li>
	<?php do_action( 'woo_ce_export_quicklinks' ); ?>
</ul>
<br class="clear" />
<p><?php _e( 'Select an export type from the list below to export entries. Once you have selected an export type you may select the fields you would like to export and optional filters available for each export type. When you click the export button below, Store Exporter will create an export file for you to save to your computer.', 'woo_ce' ); ?></p>
<form method="post" action="<?php echo add_query_arg( array( 'failed' => null, 'empty' => null, 'message' => null ) ); ?>" id="postform">
	<div id="poststuff">

		<div class="postbox" id="export-type">
			<h3 class="hndle"><?php _e( 'Export Type', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Select the data type you want to export.', 'woo_ce' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<input type="radio" id="products" name="dataset" value="products"<?php disabled( $products, 0 ); ?><?php checked( $export_type, 'products' ); ?> />
							<label for="products"><?php _e( 'Products', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $products; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="categories" name="dataset" value="categories"<?php disabled( $categories, 0 ); ?><?php checked( $export_type, 'categories' ); ?> />
							<label for="categories"><?php _e( 'Categories', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $categories; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="tags" name="dataset" value="tags"<?php disabled( $tags, 0 ); ?><?php checked( $export_type, 'tags' ); ?> />
							<label for="tags"><?php _e( 'Tags', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $tags; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="brands" name="dataset" value="brands"<?php disabled( $brands, 0 ); ?><?php checked( $export_type, 'brands' ); ?> />
							<label for="brands"><?php _e( 'Brands', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $brands; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="orders" name="dataset" value="orders"<?php disabled( $orders, 0 ); ?><?php checked( $export_type, 'orders' ); ?>/>
							<label for="orders"><?php _e( 'Orders', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $orders; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="customers" name="dataset" value="customers"<?php disabled( $customers, 0 ); ?><?php checked( $export_type, 'customers' ); ?>/>
							<label for="customers"><?php _e( 'Customers', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $customers; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="users" name="dataset" value="users"<?php disabled( $users, 0 ); ?><?php checked( $export_type, 'users' ); ?>/>
							<label for="users"><?php _e( 'Users', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $users; ?>)</span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="coupons" name="dataset" value="coupons"<?php disabled( $coupons, 0 ); ?><?php checked( $export_type, 'coupons' ); ?> />
							<label for="coupons"><?php _e( 'Coupons', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $coupons; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

					<tr>
						<th>
							<input type="radio" id="subscriptions" name="dataset" value="subscriptions"<?php disabled( $subscriptions, 0 ); ?><?php checked( $export_type, 'subscriptions' ); ?> />
							<label for="subscriptions"><?php _e( 'Subscriptions', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $subscriptions; ?>)</span>
							<span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span>
						</td>
					</tr>

<!--
					<tr>
						<th>
							<input type="radio" id="attributes" name="dataset" value="attributes"<?php disabled( $attributes, 0 ); ?><?php checked( $export_type, 'attributes' ); ?> />
							<label for="attributes"><?php _e( 'Attributes', 'woo_ce' ); ?></label>
						</th>
						<td>
							<span class="description">(<?php echo $attributes; ?>)</span>
						</td>
					</tr>
-->

				</table>
			</div>
		</div>
		<!-- .postbox -->

<?php if( $product_fields ) { ?>
		<div id="export-products" class="export-types">

			<div class="postbox">
				<h3 class="hndle"><?php _e( 'Product Fields', 'woo_ce' ); ?></h3>
				<div class="inside">
	<?php if( $products ) { ?>
					<p class="description"><?php _e( 'Select the Product fields you would like to export, your field selection is saved for future exports.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="products-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="products-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table class="ui-sortable">

		<?php foreach( $product_fields as $key => $product_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="product_fields[<?php echo $product_field['name']; ?>]" class="product_field"<?php ( isset( $product_field['default'] ) ? checked( $product_field['default'], 1 ) : '' ); ?><?php disabled( $product_field['disabled'], 1 ); ?> />
									<?php echo $product_field['label']; ?>
									<?php if( $product_field['disabled'] ) { ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span><?php } ?>
									<input type="hidden" name="product_fields_order[<?php echo $product_field['name']; ?>]" class="field_order" value="" />
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="submit" id="export_products" value="<?php _e( 'Export Products', 'woo_ce' ); ?> " class="button-primary" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Product field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Products have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
			</div>
			<!-- .postbox -->

			<div id="export-products-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Product Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_product_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_product_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_product_options_after_table' ); ?>

				</div>
				<!-- .inside -->

			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-products -->

<?php } ?>
		<div id="export-categories" class="export-types">

			<div class="postbox">
				<h3 class="hndle"><?php _e( 'Category Fields', 'woo_ce' ); ?></h3>
				<div class="inside">
					<p class="description"><?php _e( 'Select the Category fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="categories-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="categories-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table class="ui-sortable">

<?php foreach( $category_fields as $category_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="category_fields[<?php echo $category_field['name']; ?>]" class="category_field"<?php ( isset( $category_field['default'] ) ? checked( $category_field['default'], 1 ) : '' ); ?><?php disabled( $category_field['disabled'], 1 ); ?> />
									<?php echo $category_field['label']; ?>
								</label>
							</td>
						</tr>

<?php } ?>
					</table>
					<p class="submit">
						<input type="submit" id="export_categories" value="<?php _e( 'Export Categories', 'woo_ce' ); ?> " class="button-primary" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Category field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-categories-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Category Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<p><label><?php _e( 'Category Sorting', 'woo_ce' ); ?></label></p>
					<div>
						<select name="category_orderby">
							<option value="id"<?php selected( 'id', $category_orderby ); ?>><?php _e( 'Term ID', 'woo_ce' ); ?></option>
							<option value="name"<?php selected( 'name', $category_orderby ); ?>><?php _e( 'Category Name', 'woo_ce' ); ?></option>
						</select>
						<select name="category_order">
							<option value="ASC"<?php selected( 'ASC', $category_order ); ?>><?php _e( 'Ascending', 'woo_ce' ); ?></option>
							<option value="DESC"<?php selected( 'DESC', $category_order ); ?>><?php _e( 'Descending', 'woo_ce' ); ?></option>
						</select>
						<p class="description"><?php _e( 'Select the sorting of Categories within the exported file. By default this is set to export Categories by Term ID in Desending order.', 'woo_ce' ); ?></p>
					</div>

				</div>
				<!-- .inside -->
			</div>
			<!-- #export-categories-filters -->

		</div>
		<!-- #export-categories -->

		<div id="export-tags" class="export-types">

			<div class="postbox">
				<h3 class="hndle"><?php _e( 'Tag Fields', 'woo_ce' ); ?></h3>
				<div class="inside">
					<p class="description"><?php _e( 'Select the Tag fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="tags-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="tags-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table class="ui-sortable">

<?php foreach( $tag_fields as $tag_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="tag_fields[<?php echo $tag_field['name']; ?>]" class="tag_field"<?php ( isset( $tag_field['default'] ) ? checked( $tag_field['default'], 1 ) : '' ); ?><?php disabled( $tag_field['disabled'], 1 ); ?> />
									<?php echo $tag_field['label']; ?>
								</label>
							</td>
						</tr>

<?php } ?>
					</table>
					<p class="submit">
						<input type="submit" id="export_tags" value="<?php _e( 'Export Tags', 'woo_ce' ); ?> " class="button-primary" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Tag field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-tags-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Product Tag Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<p><label><?php _e( 'Product Tag Sorting', 'woo_ce' ); ?></label></p>
					<div>
						<select name="tag_orderby">
							<option value="id"<?php selected( 'id', $tag_orderby ); ?>><?php _e( 'Term ID', 'woo_ce' ); ?></option>
							<option value="name"<?php selected( 'name', $tag_orderby ); ?>><?php _e( 'Tag Name', 'woo_ce' ); ?></option>
						</select>
						<select name="tag_order">
							<option value="ASC"<?php selected( 'ASC', $tag_order ); ?>><?php _e( 'Ascending', 'woo_ce' ); ?></option>
							<option value="DESC"<?php selected( 'DESC', $tag_order ); ?>><?php _e( 'Descending', 'woo_ce' ); ?></option>
						</select>
						<p class="description"><?php _e( 'Select the sorting of Product Tags within the exported file. By default this is set to export Product Tags by Term ID in Desending order.', 'woo_ce' ); ?></p>
					</div>

				</div>
				<!-- .inside -->
			</div>
			<!-- #export-tags-filters -->

		</div>
		<!-- #export-tags -->

<?php if( $brand_fields ) { ?>
		<div id="export-brands" class="export-types">

			<div class="postbox">
				<h3 class="hndle"><?php _e( 'Brand Fields', 'woo_ce' ); ?></h3>
				<div class="inside">
	<?php if( $brands ) { ?>
					<p class="description"><?php _e( 'Select the Brand fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="brands-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="brands-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table class="ui-sortable">

		<?php foreach( $brand_fields as $brand_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="brand_fields[<?php echo $brand_field['name']; ?>]" class="brand_field"<?php ( isset( $brand_field['default'] ) ? checked( $brand_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $brand_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Brands', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Brand field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Brands have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-brands-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Brand Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<p><label><?php _e( 'Brand Sorting', 'woo_ce' ); ?></label></p>
					<div>
						<select name="brand_orderby" disabled="disabled">
							<option value="id"><?php _e( 'Term ID', 'woo_ce' ); ?></option>
							<option value="name"><?php _e( 'Brand Name', 'woo_ce' ); ?></option>
						</select>
						<select name="brand_order" disabled="disabled">
							<option value="ASC"><?php _e( 'Ascending', 'woo_ce' ); ?></option>
							<option value="DESC"><?php _e( 'Descending', 'woo_ce' ); ?></option>
						</select>
						<p class="description"><?php _e( 'Select the sorting of Brands within the exported file. By default this is set to export Product Brands by Term ID in Desending order.', 'woo_ce' ); ?></p>
					</div>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-brands -->

<?php } ?>
<?php if( $order_fields ) { ?>
		<div id="export-orders" class="export-types">

			<div class="postbox">
				<h3 class="hndle">
					<?php _e( 'Order Fields', 'woo_ce' ); ?>
				</h3>
				<div class="inside">

	<?php if( $orders ) { ?>
					<p class="description"><?php _e( 'Select the Order fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="orders-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="orders-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table class="ui-sortable">

		<?php foreach( $order_fields as $order_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="order_fields[<?php echo $order_field['name']; ?>]" class="order_field"<?php ( isset( $order_field['default'] ) ? checked( $order_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $order_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Orders', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Order field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Orders have been found.', 'woo_ce' ); ?></p>
	<?php } ?>

				</div>
			</div>
			<!-- .postbox -->

			<div id="export-orders-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Order Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_order_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_order_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_order_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-orders -->

<?php } ?>
<?php if( $customer_fields ) { ?>
		<div id="export-customers" class="export-types">

			<div class="postbox">
				<h3 class="hndle"><?php _e( 'Customer Fields', 'woo_ce' ); ?></h3>
				<div class="inside">
	<?php if( $customers ) { ?>
					<p class="description"><?php _e( 'Select the Customer fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="customers-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="customers-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table class="ui-sortable">

		<?php foreach( $customer_fields as $customer_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="customer_fields[<?php echo $customer_field['name']; ?>]" class="customer_field"<?php ( isset( $customer_field['default'] ) ? checked( $customer_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $customer_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Customers', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Customer field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Customers have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-customers-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Customer Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_customer_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_customer_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_customer_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-customers -->

<?php } ?>
<?php if( $user_fields ) { ?>
		<div id="export-users" class="export-types">

			<div class="postbox">
				<h3 class="hndle"><?php _e( 'User Fields', 'woo_ce' ); ?></h3>
				<div class="inside">
	<?php if( $users ) { ?>
					<p class="description"><?php _e( 'Select the User fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="users-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="users-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table class="ui-sortable">

		<?php foreach( $user_fields as $user_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="user_fields[<?php echo $user_field['name']; ?>]" class="user_field"<?php ( isset( $user_field['default'] ) ? checked( $user_field['default'], 1 ) : '' ); ?><?php disabled( $user_field['disabled'], 1 ); ?> />
									<?php echo $user_field['label']; ?>
									<?php if( $user_field['disabled'] ) { ?><span class="description"> - <?php printf( __( 'available in %s', 'woo_ce' ), $woo_cd_link ); ?></span><?php } ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="submit" id="export_users" class="button-primary" value="<?php _e( 'Export Users', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular User field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Users have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-users-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'User Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_user_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_user_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_user_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-users -->

<?php } ?>
<?php if( $coupon_fields ) { ?>
		<div id="export-coupons" class="export-types">

			<div class="postbox">
				<h3 class="hndle"><?php _e( 'Coupon Fields', 'woo_ce' ); ?></h3>
				<div class="inside">
	<?php if( $coupons ) { ?>
					<p class="description"><?php _e( 'Select the Coupon fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="coupons-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="coupons-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table class="ui-sortable">

		<?php foreach( $coupon_fields as $coupon_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="coupon_fields[<?php echo $coupon_field['name']; ?>]" class="coupon_field"<?php ( isset( $coupon_field['default'] ) ? checked( $coupon_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $coupon_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Coupons', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Coupon field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Coupons have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

			<div id="export-coupons-filters" class="postbox">
				<h3 class="hndle"><?php _e( 'Coupon Filters', 'woo_ce' ); ?></h3>
				<div class="inside">

					<?php do_action( 'woo_ce_export_coupon_options_before_table' ); ?>

					<table class="form-table">
						<?php do_action( 'woo_ce_export_coupon_options_table' ); ?>
					</table>

					<?php do_action( 'woo_ce_export_coupon_options_after_table' ); ?>

				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->

		</div>
		<!-- #export-coupons -->

<?php } ?>
<?php if( $subscription_fields ) { ?>
		<div id="export-subscriptions" class="export-types">

			<div class="postbox">
				<h3 class="hndle"><?php _e( 'Subscription Fields', 'woo_ce' ); ?></h3>
				<div class="inside">
	<?php if( $subscriptions ) { ?>
					<p class="description"><?php _e( 'Select the Subscription fields you would like to export.', 'woo_ce' ); ?></p>
					<p><a href="javascript:void(0)" id="subscriptions-checkall" class="checkall"><?php _e( 'Check All', 'woo_ce' ); ?></a> | <a href="javascript:void(0)" id="subscriptions-uncheckall" class="uncheckall"><?php _e( 'Uncheck All', 'woo_ce' ); ?></a></p>
					<table class="ui-sortable">

		<?php foreach( $subscription_fields as $subscription_field ) { ?>
						<tr>
							<td>
								<label>
									<input type="checkbox" name="subscription_fields[<?php echo $subscription_field['name']; ?>]" class="subscription_field"<?php ( isset( $subscription_field['default'] ) ? checked( $subscription_field['default'], 1 ) : '' ); ?> disabled="disabled" />
									<?php echo $subscription_field['label']; ?>
								</label>
							</td>
						</tr>

		<?php } ?>
					</table>
					<p class="submit">
						<input type="button" class="button button-disabled" value="<?php _e( 'Export Subscriptions', 'woo_ce' ); ?>" />
					</p>
					<p class="description"><?php _e( 'Can\'t find a particular Subscription field in the above export list?', 'woo_ce' ); ?> <a href="<?php echo $troubleshooting_url; ?>" target="_blank"><?php _e( 'Get in touch', 'woo_ce' ); ?></a>.</p>
	<?php } else { ?>
					<p><?php _e( 'No Subscriptions have been found.', 'woo_ce' ); ?></p>
	<?php } ?>
				</div>
				<!-- .inside -->
			</div>
			<!-- .postbox -->
		</div>
		<!-- #export-subscriptions -->
<?php } ?>
		<div class="postbox" id="export-options">
			<h3 class="hndle"><?php _e( 'Export Options', 'woo_ce' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'You can find additional export options under the Settings tab at the top of this screen.', 'woo_ce' ); ?></p>

				<?php do_action( 'woo_ce_export_options_before' ); ?>

				<table class="form-table">

					<?php do_action( 'woo_ce_export_options' ); ?>

					<tr class="export-options product-options">
						<th><label for=""><?php _e( 'Up-sells formatting', 'woo_ce' ); ?></label></th>
						<td>
							<label><input type="radio" name="product_upsell_formatting" value="0"<?php checked( $upsell_formatting, 0 ); ?> />&nbsp;<?php _e( 'Export Up-Sells as Product ID', 'woo_ce' ); ?></label><br />
							<label><input type="radio" name="product_upsell_formatting" value="1"<?php checked( $upsell_formatting, 1 ); ?> />&nbsp;<?php _e( 'Export Up-Sells as Product SKU', 'woo_ce' ); ?></label>
							<p class="description"><?php _e( 'Choose the up-sell formatting that is accepted by your WooCommerce import Plugin (e.g. Product Importer Deluxe, Product Import Suite, etc.).', 'woo_ce' ); ?></p>
						</td>
					</tr>

					<tr class="export-options product-options">
						<th><label for=""><?php _e( 'Cross-sells formatting', 'woo_ce' ); ?></label></th>
						<td>
							<label><input type="radio" name="product_crosssell_formatting" value="0"<?php checked( $crosssell_formatting, 0 ); ?> />&nbsp;<?php _e( 'Export Cross-Sells as Product ID', 'woo_ce' ); ?></label><br />
							<label><input type="radio" name="product_crosssell_formatting" value="1"<?php checked( $crosssell_formatting, 1 ); ?> />&nbsp;<?php _e( 'Export Cross-Sells as Product SKU', 'woo_ce' ); ?></label>
							<p class="description"><?php _e( 'Choose the cross-sell formatting that is accepted by your WooCommerce import Plugin (e.g. Product Importer Deluxe, Product Import Suite, etc.).', 'woo_ce' ); ?></p>
						</td>
					</tr>

					<tr>
						<th>
							<label for="offset"><?php _e( 'Volume offset', 'woo_ce' ); ?></label> / <label for="limit_volume"><?php _e( 'Limit volume', 'woo_ce' ); ?></label>
						</th>
						<td>
							<input type="text" size="3" id="offset" name="offset" value="<?php echo $offset; ?>" size="5" class="text" /> <?php _e( 'to', 'woo_ce' ); ?> <input type="text" size="3" id="limit_volume" name="limit_volume" value="<?php echo $limit_volume; ?>" size="5" class="text" />
							<p class="description"><?php _e( 'Volume offset and limit allows for partial exporting of an export type (e.g. records 0 to 500, etc.). This is useful when encountering timeout and/or memory errors during the a large or memory intensive export. To be used effectively both fields must be filled. By default this is not used and is left empty.', 'woo_ce' ); ?></p>
						</td>
					</tr>

					<?php do_action( 'woo_ce_export_options_table_after' ); ?>

				</table>

				<?php do_action( 'woo_ce_export_options_after' ); ?>

			</div>
		</div>
		<!-- .postbox -->

	</div>
	<!-- #poststuff -->
	<input type="hidden" name="action" value="export" />
</form>

<?php do_action( 'woo_ce_export_after_form' ); ?>