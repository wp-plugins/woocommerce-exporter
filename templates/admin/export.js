var $j = jQuery.noConflict();
$j(function() {

	// This controls the Skip Overview link on the Overview screen
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
		$j('table.ui-sortable').sortable({
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
				field_row_indexes(this);
			}
		});
	
		function field_row_indexes(obj) {
			$j(obj).each(function(index, el){
				$j('input.field_order', el).val( parseInt( $j(el).index(obj) ) );
			});
		};
	}
*/

	// Select all field options for this export type
	$j('.checkall').click(function () {
		$j(this).closest('.postbox').find(':checkbox').attr('checked', true);
	});
	// Unselect all field options for this export type
	$j('.uncheckall').click(function () {
		$j(this).closest('.postbox').find(':checkbox').attr('checked', false);
	});

	$j('.export-types').hide();
	$j('.export-options').hide();

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
	// Brands
	$j('#export-products-filters-brands').hide();
	if( $j('#products-filters-brands').attr('checked') ) {
		$j('#export-products-filters-brands').show();
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

	$j('#export-brands').hide();

	$j('#export-orders').hide();
	$j('#export-orders-filters-status').hide();
	if( $j('#orders-filters-status').attr('checked') ) {
		$j('#export-orders-filters-status').show();
	}
	$j('#export-orders-filters-date').hide();
	if( $j('#orders-filters-date').attr('checked') ) {
		$j('#export-orders-filters-date').show();
	}
	$j('#export-orders-filters-customer').hide();
	if( $j('#orders-filters-customer').attr('checked') ) {
		$j('#export-orders-filters-customer').show();
	}
	$j('#export-orders-filters-user_role').hide();
	if( $j('#orders-filters-user_role').attr('checked') ) {
		$j('#export-orders-filters-user_role').show();
	}
	$j('#export-orders-filters-coupon').hide();
	if( $j('#orders-filters-coupon').attr('checked') ) {
		$j('#export-orders-filters-coupon').show();
	}
	$j('#export-orders-filters-category').hide();
	if( $j('#orders-filters-category').attr('checked') ) {
		$j('#export-orders-filters-category').show();
	}
	$j('#export-orders-filters-tag').hide();
	if( $j('#orders-filters-tag').attr('checked') ) {
		$j('#export-orders-filters-tag').show();
	}

	$j('#export-customers-filters-status').hide();
	if( $j('#customers-filters-status').attr('checked') ) {
		$j('#export-customers-filters-status').show();
	}
	$j('#export-customers').hide();
	$j('#export-users').hide();
	$j('#export-coupons').hide();
	$j('#export-subscriptions').hide();
	$j('#export-attributes').hide();

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

	$j('#orders-filters-date').click(function(){
		$j('#export-orders-filters-date').toggle();
	});
	$j('#orders-filters-status').click(function(){
		$j('#export-orders-filters-status').toggle();
	});
	$j('#orders-filters-customer').click(function(){
		$j('#export-orders-filters-customer').toggle();
	});
	$j('#orders-filters-user_role').click(function(){
		$j('#export-orders-filters-user_role').toggle();
	});
	$j('#orders-filters-coupon').click(function(){
		$j('#export-orders-filters-coupon').toggle();
	});
	$j('#orders-filters-category').click(function(){
		$j('#export-orders-filters-category').toggle();
	});
	$j('#orders-filters-tag').click(function(){
		$j('#export-orders-filters-tag').toggle();
	});

	$j('#customers-filters-status').click(function(){
		$j('#export-customers-filters-status').toggle();
	});

	// Export types
	$j('#products').click(function(){
		$j('.export-types').hide();
		$j('#export-products').show();

		$j('.export-options').hide();
		$j('.product-options').show();
	});
	$j('#categories').click(function(){
		$j('.export-types').hide();
		$j('#export-categories').show();

		$j('.export-options').hide();
		$j('.category-options').show();
	});
	$j('#tags').click(function(){
		$j('.export-types').hide();
		$j('#export-tags').show();

		$j('.export-options').hide();
		$j('.tag-options').show();
	});
	$j('#brands').click(function(){
		$j('.export-types').hide();
		$j('#export-brands').show();

		$j('.export-options').hide();
		$j('.brand-options').show();
	});
	$j('#orders').click(function(){
		$j('.export-types').hide();
		$j('#export-orders').show();

		$j('.export-options').hide();
		$j('.order-options').show();
	});
	$j('#customers').click(function(){
		$j('.export-types').hide();
		$j('#export-customers').show();

		$j('.export-options').hide();
		$j('.customer-options').show();
	});
	$j('#users').click(function(){
		$j('.export-types').hide();
		$j('#export-users').show();

		$j('.export-options').hide();
		$j('.user-options').show();
	});
	$j('#coupons').click(function(){
		$j('.export-types').hide();
		$j('#export-coupons').show();

		$j('.export-options').hide();
		$j('.coupon-options').show();
	});
	$j('#subscriptions').click(function(){
		$j('.export-types').hide();
		$j('#export-subscriptions').show();

		$j('.export-options').hide();
		$j('.subscription-options').show();
	});
	$j('#attributes').click(function(){
		$j('.export-types').hide();
		$j('#export-attributes').show();

		$j('.export-options').hide();
		$j('.attribute-options').show();
	});

	// Export button
	$j('#export_products').click(function(){
		$j('input:radio[name=dataset]:nth(0)').attr('checked',true);
	});
	$j('#export_categories').click(function(){
		$j('input:radio[name=dataset]:nth(1)').attr('checked',true);
	});
	$j('#export_tags').click(function(){
		$j('input:radio[name=dataset]:nth(2)').attr('checked',true);
	});
	$j('#export_brands').click(function(){
		$j('input:radio[name=dataset]:nth(3)').attr('checked',true);
	});
	$j('#export_orders').click(function(){
		$j('input:radio[name=dataset]:nth(4)').attr('checked',true);
	});
	$j('#export_customers').click(function(){
		$j('input:radio[name=dataset]:nth(5)').attr('checked',true);
	});
	$j('#export_users').click(function(){
		$j('input:radio[name=dataset]:nth(6)').attr('checked',true);
	});
	$j('#export_coupons').click(function(){
		$j('input:radio[name=dataset]:nth(7)').attr('checked',true);
	});
	$j('#export_subscriptions').click(function(){
		$j('input:radio[name=dataset]:nth(8)').attr('checked',true);
	});
	$j('#export_attributes').click(function(){
		$j('input:radio[name=dataset]:nth(9)').attr('checked',true);
	});

	$j("#auto_type").change(function () {
		var type = $j('select[name=auto_type]').val();
		switch( type ) {

			case 'orders':
				var type = 'order';
				break;

		}
		$j('.export-options').hide();
		$j('.'+type+'-options').show();
	});

	$j(document).ready(function() {
		// This auto-selects the export type based on the link from the Overview screen
		var href = jQuery(location).attr('href');
		// If this is the Export tab
		if (href.toLowerCase().indexOf('tab=export') >= 0) {
			// If the URL includes an in-line link
			if (href.toLowerCase().indexOf('#') >= 0 ) {
				var type = href.substr(href.indexOf("#") + 1);
				var type = type.replace('export-','');
				$j('#'+type).trigger('click');
			} else {
				// This auto-selects the last known export type based on stored WordPress option, defaults to Products
				var type = $j('input:radio[name=dataset]:checked').val();
				$j('#'+type).trigger('click');
			}
		} else if (href.toLowerCase().indexOf('tab=settings') >= 0) {
			$j("#auto_type").trigger("change");
		} else {
			// This auto-selects the last known export type based on stored WordPress option, defaults to Products
			var type = $j('input:radio[name=dataset]:checked').val();
			$j('#'+type).trigger('click');
		}
	});

});