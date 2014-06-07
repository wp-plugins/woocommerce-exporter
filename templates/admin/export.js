var $j = jQuery.noConflict();
$j(function() {

	$j('#skip_overview').click(function(){
		$j('#skip_overview_form').submit();
	});

	// Date Picker
	if( $j.isFunction($j.fn.datepicker) ) {
		$j('.datepicker').datepicker({
			dateFormat: 'dd/mm/yy'
		});
	}

	// Chosen
	if( $j.isFunction($j.fn.chosen) ) {
		$j(".chzn-select").chosen({
			search_contains: true
		});
	}

	// Sortable export columns
/*
	if( $j.isFunction($j.fn.sortable) ) {
		$j('#export-products table').sortable({
			items:'tr',
			cursor:'move',
			axis:'y',
			handle: 'td',
			scrollSensitivity:40,
			helper:function(e,ui){
				ui.children().each(function(){
					jQuery(this).width(jQuery(this).width());
				});
				ui.css('left', '0');
				return ui;
			},
			start:function(event,ui){
				ui.item.css('background-color','#f6f6f6');
			},
			stop:function(event,ui){
				ui.item.removeAttr('style');
				field_row_indexes();
			}
		});
	
		function field_row_indexes() {
			jQuery('#export-products table tr').each(function(index, el){
				jQuery('input.field_order', el).val( parseInt( jQuery(el).index('#export-products table tr') ) );
			});
		};
	}
*/

	// Select all
	$j('.checkall').click(function () {
		$j(this).closest('.postbox').find(':checkbox').attr('checked', true);
	});
	$j('.uncheckall').click(function () {
		$j(this).closest('.postbox').find(':checkbox').attr('checked', false);
	});

	// Show Products widgets by default
	$j('.export-options').hide();
	$j('#products').trigger('click');
	$j('.product-options').show();

	// Categories
	$j('#export-products-filters-categories').hide();
	if( $j('#products-filters-categories').attr('checked') ) {
		$j('#export-products-filters-categories').show();
	}
	// Tags
	$j('#export-products-filters-tags').hide();
	if( $j('#products-filters-tags').attr('checked') ) {
		$j('#export-products-filters-tags').show();
	}
	// Product Status
	$j('#export-products-filters-status').hide();
	if( $j('#products-filters-status').attr('checked') ) {
		$j('#export-products-filters-status').show();
	}
	$j('#export-products-filters-type').hide();
	if( $j('#products-filters-type').attr('checked') ) {
		$j('#export-products-filters-type').show();
	}
	$j('#export-categories').hide();
	$j('#export-tags').hide();
	$j('#export-orders').hide();
	$j('#export-orders-filters-status').hide();
	if( $j('#orders-filters-status').attr('checked') ) {
		$j('#export-orders-filters-status').show();
	}
	$j('#export-orders-filters-date').hide();
	if( $j('#orders-filters-date').attr('checked') ) {
		$j('#export-orders-filters-date').show();
	}
	$j('#export-orders-filters-user_role').hide();
	if( $j('#orders-filters-user_role').attr('checked') ) {
		$j('#export-orders-filters-user_role').show();
	}
	$j('#export-orders-filters-coupon').hide();
	if( $j('#orders-filters-coupon').attr('checked') ) {
		$j('#export-orders-filters-coupon').show();
	}
	$j('#export-customers-filters-status').hide();
	if( $j('#customers-filters-status').attr('checked') ) {
		$j('#export-customers-filters-status').show();
	}
	$j('#export-customers').hide();
	$j('#export-users').hide();
	$j('#export-coupons').hide();

	$j('#products-filters-categories').click(function(){
		$j('#export-products-filters-categories').toggle();
	});
	$j('#products-filters-tags').click(function(){
		$j('#export-products-filters-tags').toggle();
	});
	$j('#products-filters-status').click(function(){
		$j('#export-products-filters-status').toggle();
	});
	$j('#products-filters-type').click(function(){
		$j('#export-products-filters-type').toggle();
	});
	$j('#orders-filters-status').click(function(){
		$j('#export-orders-filters-status').toggle();
	});
	$j('#orders-filters-date').click(function(){
		$j('#export-orders-filters-date').toggle();
	});
	$j('#orders-filters-user_role').click(function(){
		$j('#export-orders-filters-user_role').toggle();
	});
	$j('#orders-filters-coupon').click(function(){
		$j('#export-orders-filters-coupon').toggle();
	});
	$j('#customers-filters-status').click(function(){
		$j('#export-customers-filters-status').toggle();
	});

	// Export types
	$j('#products').click(function(){
		$j('#export-products').show();
		$j('#export-categories').hide();
		$j('#export-tags').hide();
		$j('#export-orders').hide();
		$j('#export-customers').hide();
		$j('#export-users').hide();
		$j('#export-coupons').hide();

		$j('.export-options').hide();
		$j('.product-options').show();
	});
	$j('#categories').click(function(){
		$j('#export-products').hide();
		$j('#export-categories').show();
		$j('#export-tags').hide();
		$j('#export-orders').hide();
		$j('#export-customers').hide();
		$j('#export-users').hide();
		$j('#export-coupons').hide();

		$j('.export-options').hide();
		$j('.category-options').show();
	});
	$j('#tags').click(function(){
		$j('#export-products').hide();
		$j('#export-categories').hide();
		$j('#export-tags').show();
		$j('#export-orders').hide();
		$j('#export-customers').hide();
		$j('#export-users').hide();
		$j('#export-coupons').hide();

		$j('.export-options').hide();
		$j('.tag-options').show();
	});
	$j('#orders').click(function(){
		$j('#export-products').hide();
		$j('#export-categories').hide();
		$j('#export-tags').hide();
		$j('#export-orders').show();
		$j('#export-customers').hide();
		$j('#export-users').hide();
		$j('#export-coupons').hide();

		$j('.export-options').hide();
		$j('.order-options').show();
	});
	$j('#customers').click(function(){
		$j('#export-products').hide();
		$j('#export-categories').hide();
		$j('#export-tags').hide();
		$j('#export-orders').hide();
		$j('#export-customers').show();
		$j('#export-users').hide();
		$j('#export-coupons').hide();

		$j('.export-options').hide();
		$j('.customer-options').show();
	});
	$j('#users').click(function(){
		$j('#export-products').hide();
		$j('#export-categories').hide();
		$j('#export-tags').hide();
		$j('#export-orders').hide();
		$j('#export-customers').hide();
		$j('#export-users').show();
		$j('#export-coupons').hide();

		$j('.export-options').hide();
		$j('.user-options').show();
	});
	$j('#coupons').click(function(){
		$j('#export-products').hide();
		$j('#export-categories').hide();
		$j('#export-tags').hide();
		$j('#export-orders').hide();
		$j('#export-customers').hide();
		$j('#export-users').hide();
		$j('#export-coupons').show();

		$j('.export-options').hide();
		$j('.coupon-options').show();
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
	$j('#export_users').click(function(){
		$j('input:radio[name=dataset]:nth(5)').attr('checked',true);
	});
	$j('#export_coupons').click(function(){
		$j('input:radio[name=dataset]:nth(6)').attr('checked',true);
	});

	// Export jump link from Overview tab
	$j(document).ready(function() {
		var href = jQuery(location).attr('href');
		if (href.toLowerCase().indexOf('tab=export') >= 0) {
			if (href.toLowerCase().indexOf('#') >= 0 ) {
				var type = href.substr(href.indexOf("#") + 1)
				var type = type.replace('export-','');
				$j('#'+type).trigger('click');
			}
		}
	});

});