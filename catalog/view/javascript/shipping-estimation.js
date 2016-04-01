$(function() {
	$(document).delegate('#button_get_rates:not(.disabled)', 'click', function() {
		$('#tab-shipping-estimation .shipping_methods').load($('base').attr('href') + 'index.php?route=module/ms-shipping-estimate/getRates&product_id=' + shipping_estimate_product_id + '&geo_zone_id=' + $('#shipping_geo_zone').val() + '&quantity=' + $('[name^="quantity"]').val());
	});
});
