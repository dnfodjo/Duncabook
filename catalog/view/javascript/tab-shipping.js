$(function() {
    /*
    $('body').on('change', "input[name='product_enable_shipping']", function() {
		if ($(this).val() == 1) {
            ProductShippingCategories(1)
			if (!$("input[name='product_quantity']").hasClass("ffUnchangeable")) {
				$("input[name='product_quantity']").parents("tr").show();
			}
			if (typeof msGlobals.downloadsLimitApplication != 'undefined') {
				if (msGlobals.downloadsLimit > 0 && msGlobals.downloadsLimitApplication == 1) {
					$("span[name='downloads_required']").hide();
				}
			}
		} else {
            ProductShippingCategories(0)
			if (!$("input[name='product_quantity']").hasClass("ffUnchangeable")) {
				$("input[name='product_quantity']").parents("tr").hide();
			}
			if (typeof msGlobals.downloadsLimitApplication != 'undefined') {
				if (msGlobals.downloadsLimit > 0 && msGlobals.downloadsLimitApplication == 1) {
					$("span[name='downloads_required']").show();
				}
			}
		}
	});

    function ProductShippingCategories(type)
    {
        var product_id  =   $('input[name=product_id]').val();

        $.ajax({
            type: "POST",
            url: $('base').attr('href') + 'index.php?route=seller/account-product/jxshippingcategories',
            data: {'type':type,'product_id':product_id},
            success: function(out)
            {
                $('#product_category_block').html(out);
            }
        });
    }
    */
});
