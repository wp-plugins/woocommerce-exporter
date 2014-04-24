<form method="post">
	<table class="form-table">
		<tbody>

			<?php do_action( 'woo_ce_export_settings_before' ); ?>

			<tr valign="top">
				<th scope="row"><label for="export_filename"><?php _e( 'Export Filename', 'woo_ce' ); ?></label></th>
				<td>
					<input name="export_filename" type="text" id="export_filename" value="<?php echo $export_filename; ?>" class="regular-text code" />
					<p class="description"><?php _e( 'The filename of the exported dataset. Tags can be used: ', 'woo_ce' ); ?> <code>%dataset%</code>, <code>%date%</code>, <code>%time%</code>, <code>%store_name%</code>.</p>
				</td>
			</tr>

			<tr>
				<th>
					<label for="delete_temporary_csv"><?php _e( 'Enable Archives', 'woo_ce' ); ?></label>
				</th>
				<td>
					<select id="delete_temporary_csv" name="delete_temporary_csv">
						<option value="0"<?php selected( $delete_csv, 0 ); ?>><?php _e( 'Yes', 'woo_ce' ); ?></option>
						<option value="1"<?php selected( $delete_csv, 1 ); ?>><?php _e( 'No', 'woo_ce' ); ?></option>
					</select>
					<p class="description"><?php _e( 'Save copies of CSV exports to the WordPress Media for downloading later. By default this option is turned on.', 'woo_ce' ); ?></p>
				</td>
			</tr>

			<tr>
				<th><?php _e( 'Date Format', 'woo_ce' ); ?></th>
				<td>
					<fieldset>
						<label title="F j, Y"><input type="radio" name="date_format" value="F j, Y"<?php checked( $date_format, 'F j, Y' ); ?>> <span><?php echo date( 'F j, Y' ); ?></span></label><br>
						<label title="Y/m/d"><input type="radio" name="date_format" value="Y/m/d"<?php checked( $date_format, 'Y/m/d' ); ?>> <span><?php echo date( 'Y/m/d' ); ?></span></label><br>
						<label title="m/d/Y"><input type="radio" name="date_format" value="m/d/Y"<?php checked( $date_format, 'm/d/Y' ); ?>> <span><?php echo date( 'm/d/Y' ); ?></span></label><br>
						<label title="d/m/Y"><input type="radio" name="date_format" value="d/m/Y"<?php checked( $date_format, 'd/m/Y' ); ?>> <span><?php echo date( 'd/m/Y' ); ?></span></label><br>
<!--
						<label><input type="radio" name="date_format" id="date_format_custom_radio" value="\c\u\s\t\o\m"> Custom: </label><input type="text" name="date_format_custom" value="F j, Y" class="small-text"> <span class="example"> January 6, 2014</span> <span class="spinner"></span>
						<p><a href="http://codex.wordpress.org/Formatting_Date_and_Time"><?php _e( 'Documentation on date and time formatting', 'woo_ce' ); ?></a>.</p>
-->
					</fieldset>
					<p class="description"><?php _e( 'The date format option affects how date\'s are presented within your CSV file. Default is set to DD/MM/YYYY.', 'woo_ce' ); ?></p>
				</td>
			</tr>
<?php if( !ini_get( 'safe_mode' ) ) { ?>
			<tr>
				<th>
					<label for="timeout"><?php _e( 'Script Timeout', 'woo_ce' ); ?>: </label>
				</th>
				<td>
					<select id="timeout" name="timeout">
						<option value="600"<?php selected( $timeout, 600 ); ?>><?php printf( __( '%s minutes', 'woo_ce' ), 10 ); ?></option>
						<option value="1800"<?php selected( $timeout, 1800 ); ?>><?php printf( __( '%s minutes', 'woo_ce' ), 30 ); ?></option>
						<option value="3600"<?php selected( $timeout, 3600 ); ?>><?php printf( __( '%s hour', 'woo_ce' ), 1 ); ?></option>
						<option value="0"<?php selected( $timeout, 0 ); ?>><?php _e( 'Unlimited', 'woo_ce' ); ?></option>
					</select>
					<p class="description"><?php _e( 'Script timeout defines how long WooCommerce Exporter is \'allowed\' to process your CSV file, once the time limit is reached the export process halts.', 'woo_ce' ); ?></p>
				</td>
			</tr>
<?php } ?>

			<?php do_action( 'woo_ce_export_settings_after' ); ?>

		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'woo_ce' ); ?>" />
	</p>

	<input type="hidden" name="action" value="save" />
</form>
