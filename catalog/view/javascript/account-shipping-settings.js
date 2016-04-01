$(function() {
	if ($("input[name='shipping_type']:checked").val() == 0) {
		$('#seller_shipping_method_heading').hide();
		$('#seller_shipping_method_table').hide();
	}

	$("body").on("change", "input[name='shipping_type']", function() {
		if ($(this).val() == 1) {
			$('#seller_shipping_method_heading').show();
			$('#seller_shipping_method_table').show();
		} else {
			$('#seller_shipping_method_heading').hide();
			$('#seller_shipping_method_table').hide();
		}
	});
	
	$('body').delegate(".ffRemove", "click", function() {
		$(this).parents('tr[name="shipping-method-row"]').remove();
	});
	
	$('body').delegate(".ffClone", "click", function() {
		var lastRow = $(this).parents('table[name="seller_shipping_method"]').find('tbody tr:last input:last').attr('name');
		if (typeof lastRow == "undefined") {
			var newRowNum = 1;
		} else {
			var newRowNum = parseInt(lastRow.match(/[0-9]+/)) + 1;
		}

		var newRow = $(this).parents('table[name="seller_shipping_method"]').find('tbody tr.ffSample').clone();
		newRow.find('input,select').attr('name', function(i,name) {
			return name.replace('[0]','[' + newRowNum + ']');
		});
		
		$(this).parents('table[name="seller_shipping_method"]').find('tbody').append(newRow.removeAttr('class'));
	});
	
	$("#ms-submit-button").click(function() {
		var button = $(this);
		var url = 'jxsubmitsettings';
		
		$.ajax({
			type: "POST",
			dataType: "json",
			url: $('base').attr('href') + 'index.php?route=seller/account-shipping-settings/'+url,
			data: $(this).parents("form").serialize(),
			beforeSend: function() {
				button.hide();
			},			
			success: function(jsonData) {
				$('.error').text('');
				if (!jQuery.isEmptyObject(jsonData.errors)) {
					button.show();
					for (error in jsonData.errors) {
						if (!jsonData.errors.hasOwnProperty(error)) {
							continue;
						}
						$('#error_'+error).text(jsonData.errors[error]);
						window.scrollTo(0,0);
					}
				} else {
					location = jsonData['redirect'];
				}
		   	}
		});
	});
	
});
