<script type="text/javascript">
	function showProgress() {
		window.scrollTo(0,0);
		document.getElementById('progress').style.display = 'block';
		document.getElementById('content').style.display = 'none';
	}
</script>
<div id="content">
	<h3><?php _e( 'Export to CSV', 'woo_ce' ); ?></h3>
	<p><?php _e( 'When you click the Export button below Store Export will create a CSV file for you to save to your computer.', 'woo_ce' ); ?></p>
	<p><?php _e( 'This formatted CSV file will contain the Product details from your WooCommerce store.', 'woo_ce' ); ?></p>
	<p><?php _e( 'Once you\'ve saved the download file, you can use <a href="' . $woo_pd_url . '"' . $woo_pd_target . '>Product Importer Deluxe</a> to merge changes back into your store, or import store details into another WooCommerce instance.', 'woo_ce' ); ?></p>
	<form method="post" onsubmit="showProgress()">
		<div id="poststuff">

			<div class="postbox">
				<h3 class="hndle"><?php _e( 'Export WooCommerce Details', 'woo_ce' ); ?></h3>
				<div class="inside">
					<table class="form-table">

						<tr>
							<th>
								<label for="products"><?php _e( 'Products', 'woo_ce' ); ?></label>
							</th>
							<td>
								<input type="radio" id="products" name="dataset" value="products"<?php if( $products == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $products; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="customers"><?php _e( 'Customers', 'woo_ce' ); ?></label>
							</th>
							<td>
								<input type="radio" id="customers" name="dataset" value="customers"<?php if( $customers == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $customers; ?>)
							</td>
						</tr>

					</table>
				</div>
			</div>
			<!-- .postbox -->

			<div class="postbox">
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
									<option value="0"><?php _e( 'Unlimited', 'woo_ce' ); ?>&nbsp;</option>
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
		<p class="submit">
			<input type="submit" value="<?php _e( 'Export', 'woo_ce' ); ?>" class="button-primary" />
		</p>
		<input type="hidden" name="action" value="export" />
	</form>
</div>
<div id="progress" style="display:none;">
	<p><?php _e( 'Chosen WooCommerce details are being exported, this process can take awhile. Time for a beer?', 'woo_ce' ); ?></p>
	<img src="<?php echo plugins_url( '/templates/admin/images/progress.gif', $woo_ce['relpath'] ); ?>" alt="" />
	<p><?php _e( 'Return to <a href="' . $url . '">WooCommerce Exporter</a>.', 'woo_ce' ); ?>
</div>