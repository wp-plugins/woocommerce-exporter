var $j = jQuery.noConflict();

$j(function() {

	// Date Picker
	$j('.datepicker').datepicker({
		dateFormat: 'dd/mm/yy'
	});

	// Export button
	$j('#export_products').click(function(){
		$j('input:radio[name=dataset]:nth(0)').attr('checked',true);
	});
	$j('#export_orders').click(function(){
		$j('input:radio[name=dataset]:nth(3)').attr('checked',true);
	});
	$j('#export_customers').click(function(){
		$j('input:radio[name=dataset]:nth(4)').attr('checked',true);
	});
	$j('#export_coupons').click(function(){
		$j('input:radio[name=dataset]:nth(5)').attr('checked',true);
	});

	// Select all
	$j('#products-checkall').click(function () {
		$j('#export-products').find(':checkbox').attr('checked', true);
	});
	$j('#products-uncheckall').click(function () {
		$j('#export-products').find(':checkbox').attr('checked', false);
	});

	$j('#orders-checkall').click(function () {
		$j('#export-orders').find(':checkbox').attr('checked', true);
	});
	$j('#orders-uncheckall').click(function () {
		$j('#export-orders').find(':checkbox').attr('checked', false);
	});

	$j('#customers-checkall').click(function () {
		$j('#export-customers').find(':checkbox').attr('checked', true);
	});
	$j('#customers-uncheckall').click(function () {
		$j('#export-customers').find(':checkbox').attr('checked', false);
	});

	$j('#coupons-checkall').click(function () {
		$j('#export-coupons').find(':checkbox').attr('checked', true);
	});
	$j('#coupons-uncheckall').click(function () {
		$j('#export-coupons').find(':checkbox').attr('checked', false);
	});

});