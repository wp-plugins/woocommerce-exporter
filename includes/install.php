<?php
// De-activate Store Exporter to limit conflicts
function woo_ce_deactivate_ce() {

	$plugins = array(
		'woocommerce-exporter/exporter.php',
		'woocommerce-store-exporter/exporter.php'
	);
	deactivate_plugins( $plugins, true );

}
?>