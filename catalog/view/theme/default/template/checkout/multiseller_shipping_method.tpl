<div class="ms-shipping-choice">
<form>
	<?php if (isset($error_warning) && $error_warning) { ?>
		<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
	<?php } ?>

	<p><?php echo $text_product_shipping_method; ?></p>
	
	<input type="hidden" id="checkout_totals" name="checkout_totals" value="<?php echo $checkout_totals; ?>" />
	
	<table>
	
	<!-- Combined Shipping (For each seller) -->
	
	<?php foreach ($seller_shipping as $combined_seller) { ?>
		<tr>
			<td colspan="5"><b><?php echo $seller; ?></b> <a href="<?php echo $combined_seller['seller_href']; ?>"> <?php echo $combined_seller['seller_name']; ?> </a> </td>
		</tr>
		
		<?php $at_least_one_shippable = false; ?>
		<?php foreach ($products as $product) { ?>
			<?php if ($product['seller_id'] == $combined_seller['seller_id']) { ?>
				<tr>
					<td class="name"><?php echo $ms_checkout_product_name; ?> <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
						<?php foreach ($product['option'] as $option) { ?>
							<br />
							&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
						<?php } ?>
					</td>
					<!--<td class="model"><?php echo $ms_checkout_product_model; ?> <?php echo $product['model']; ?></td>-->
					<td class="model"><?php echo $ms_checkout_product_delivery_type; ?> <?php if ($product['shippable']) { echo $ms_checkout_delivery_type_shippable; } else { echo $ms_checkout_delivery_type_digital; } ?></td>
					<td class="quantity"><?php echo $ms_checkout_product_quantity; ?> <?php echo $product['quantity']; ?></td>
					<td class="price"><?php echo $ms_checkout_product_price; ?> <?php echo $product['price']; ?></td>
					<td class="total"><?php echo $ms_checkout_product_total; ?> <?php echo $product['total']; ?></td>
				</tr>
				
				<?php $last_product_id = $product['product_id']; ?>
				<?php $at_least_one_shippable = $at_least_one_shippable || $product['shippable']; ?>
			<?php } ?>
		<?php } ?>
		
		<input type="hidden" name="seller_shipping_cost_old[<?php echo $combined_seller['seller_id']; ?>]" value="<?php echo $combined_seller['shipping_cost']; ?>" />
		<input type="hidden" name="seller_total_weight[<?php echo $combined_seller['seller_id']; ?>]" value="<?php echo $combined_seller['total_seller_weight']; ?>" />
		
		<?php if ($combined_seller['shipping_methods'] && $at_least_one_shippable) { ?>
			<tr><td colspan="5">
				<table class="radio">
					<?php foreach ($combined_seller['shipping_methods'] as $shipping_method) { ?>
						<?php if (!$shipping_method['error']) { ?>
							<input type="hidden" name="seller_shipping_cost_new[<?php echo $combined_seller['seller_id']; ?>][<?php echo $shipping_method['shipping_method_id']; ?>]" value="<?php echo $shipping_method['cost_unformatted']; ?>" />
							
							<input type="hidden" name="seller_shipping_method_name[<?php echo $combined_seller['seller_id']; ?>][<?php echo $shipping_method['shipping_method_id']; ?>]" value="<?php echo $shipping_method['method_descriptions'][$language_id]['name']; ?>" />
							
							<tr class="highlight">
								<td>
									<input type="radio" name="seller_shipping_method[<?php echo $combined_seller['seller_id']; ?>]" value="<?php echo $shipping_method['shipping_method_id']; ?>" id="<?php echo $shipping_method['shipping_method_id']; ?>" <?php if ($shipping_method['shipping_method_id'] == $combined_seller['shipping_methods'][0]['shipping_method_id']) { echo "checked = \"checked\""; } ?> />
								</td>
								
								<td><label for="<?php echo $shipping_method['shipping_method_id']; ?>"><?php echo $shipping_method['method_descriptions'][$language_id]['name'] . ( ( !empty($shipping_method['comment']) && $shipping_method['comment'] != "" ) ? " (" . $shipping_method['comment'] . ")" : "" ); ?></label></td>
								
								<td style="text-align: right;"><label for="<?php echo $shipping_method['shipping_method_id']; ?>"><?php echo $shipping_method['cost']; ?></label></td>
							</tr>
						<?php } else { ?>
							<tr>
								<td colspan="3"><div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div></td>
							</tr>
						<?php } ?>
					<?php } ?>
				</table>
				
				<script type="text/javascript">
					$("body").on('change', "input[name='seller_shipping_method[<?php echo $combined_seller['seller_id']; ?>]']", function() {
						var url = 'jxChangeSellerShippingMethod' + '&seller_id=' + <?php echo $combined_seller['seller_id']; ?>;
						var anInput = $(this);
						$.ajax({
							type: "POST",
							dataType: "json",
							url: $('base').attr('href') + 'index.php?route=checkout/multiseller_shipping_method/' + url,
							data: anInput.parents("form").serialize(),
							beforeSend: function() {
								
							},
							success: function(jsonData) {
								$('#checkout_totals').val(jsonData['checkout_totals_unformatted']);
								var shippingMethod = $('input[name="seller_shipping_method[<?php echo $combined_seller['seller_id']; ?>]"]:checked').val().toString();
								var shippingCostOld = $('input[name="seller_shipping_cost_new[<?php echo $combined_seller['seller_id']; ?>][' + shippingMethod + ']"]').val();
								$('input[name="seller_shipping_cost_old[<?php echo $combined_seller['seller_id']; ?>]"]').val(shippingCostOld);
								$('#totals_formatted').html(jsonData['checkout_totals']);
							}
						});
					});
				</script>
				
			</td></tr>
		<?php } else if ($at_least_one_shippable) { ?>
			<tr><td colspan="5">
				<p><?php echo $text_no_shipping_to_you; ?> <br />
				<b><?php echo $text_no_shipping_removed; ?></b></p>
			</td></tr>
		<?php } ?>
	<?php } ?>
	
	<!-- Fixed Shipping (For each product separately) -->
	<?php $previous_seller_id = null; ?>
	<?php foreach ($products as $product) { ?>
		
		<?php if ( $product['shipping_type'] == MsShipping::SHIPPING_TYPE_FIXED || $product['shipping_type'] == MsShipping::SHIPPING_TYPE_NOT_DEFINED )  { ?>
			
			<?php if ($product['seller_id'] !== $previous_seller_id) { ?>
				<tr>
					<td colspan="5"><b><?php echo $seller; ?></b> <a href="<?php echo $product['seller_href']; ?>"> <?php echo $product['seller_name']; ?> </a> </td>
				</tr>
			<?php } ?>
			<?php $previous_seller_id = $product['seller_id']; ?>
		
			<input type="hidden" name="shipping_cost_old[<?php echo $product['product_id']; ?>]" value="<?php echo $product['shipping_cost']; ?>" />
			
			<tr>
				<td class="name"><?php echo $ms_checkout_product_name; ?> <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
					<?php foreach ($product['option'] as $option) { ?>
						<br />
						&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
					<?php } ?>
				</td>
				<!--<td class="model"><?php echo $ms_checkout_product_model; ?> <?php echo $product['model']; ?></td>-->
				<td class="model"><?php echo $ms_checkout_product_delivery_type; ?> <?php if ($product['shippable']) { echo $ms_checkout_delivery_type_shippable; } else { echo $ms_checkout_delivery_type_digital; } ?></td>
				<td class="quantity"><?php echo $ms_checkout_product_quantity; ?> <?php echo $product['quantity']; ?></td>
				<td class="price"><?php echo $ms_checkout_product_price; ?> <?php echo $product['price']; ?></td>
				<td class="total"><?php echo $ms_checkout_product_total; ?> <?php echo $product['total']; ?></td>
			</tr>

			<?php if ($product['shipping_methods'] && $product['shipping_type'] == MsShipping::SHIPPING_TYPE_FIXED) { ?>
				
				<tr><td colspan="5">
				
					<table class="radio">
						<?php foreach ($product['shipping_methods'] as $shipping_method) { ?>
							<?php if (!$shipping_method['error']) { ?>
								<input type="hidden" name="shipping_cost_new[<?php echo $product['product_id']; ?>][<?php echo $shipping_method['shipping_method_id']; ?>]" value="<?php echo $shipping_method['cost_unformatted']; ?>" />
								
								<input type="hidden" name="shipping_method_name[<?php echo $product['product_id']; ?>][<?php echo $shipping_method['shipping_method_id']; ?>]" value="<?php echo $shipping_method['method_descriptions'][$language_id]['name']; ?>" />
								
								<tr class="highlight">
									<td>
										<input type="radio" name="shipping_method[<?php echo $product['product_id']; ?>]" value="<?php echo $shipping_method['shipping_method_id']; ?>" id="<?php echo $shipping_method['shipping_method_id']; ?>" <?php if ($shipping_method['shipping_method_id'] == $product['shipping_methods'][0]['shipping_method_id']) { echo "checked = \"checked\""; } ?> />
									</td>
									
									<td><label for="<?php echo $shipping_method['shipping_method_id']; ?>"><?php echo $shipping_method['method_descriptions'][$language_id]['name'] . ( ( !empty($shipping_method['comment']) && $shipping_method['comment'] != "" ) ? " (" . $shipping_method['comment'] . ")" : "" ); ?></label></td>
									<td style="text-align: right;"><label for="<?php echo $shipping_method['shipping_method_id']; ?>"><?php echo $shipping_method['cost']; ?></label></td>
								</tr>
							<?php } else { ?>
								<tr>
									<td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
								</tr>
							<?php } ?>
						<?php } ?>
					</table>
					
					<script type="text/javascript">
						$("body").on("change", "input[name='shipping_method[<?php echo $product['product_id']; ?>]']", function() {
							var url = 'jxChangeShippingMethod' + '&product_id=' + <?php echo $product['product_id']; ?>;
							var anInput = $(this);
							$.ajax({
								type: "POST",
								dataType: "json",
								url: $('base').attr('href') + 'index.php?route=checkout/multiseller_shipping_method/' + url,
								data: anInput.parents("form").serialize(),
								beforeSend: function() {
									//console.log(anInput);
								},
								success: function(jsonData) {
									$('#checkout_totals').val(jsonData['checkout_totals_unformatted']);
									var shippingMethod = $('input[name="shipping_method[<?php echo $product['product_id']; ?>]"]:checked').val().toString();
									var shippingCostOld = $('input[name="shipping_cost_new[<?php echo $product['product_id']; ?>][' + shippingMethod + ']"]').val();
									$('input[name="shipping_cost_old[<?php echo $product['product_id']; ?>]"]').val(shippingCostOld);
									$('#totals_formatted').html(jsonData['checkout_totals']);
								}
							});
						});
					</script>
					
				</td></tr>
			<?php } else if ($product['shippable']) { ?>
				<tr><td colspan="5">
					<?php if ($product['shipping_type'] == MsShipping::SHIPPING_TYPE_NOT_DEFINED) { ?>
						<p><?php echo $text_shipping_impossible; ?> <br />
					<?php } else { ?>
						<p><?php echo $text_no_shipping_to_you; ?> <br />
					<?php } ?>
						<b><?php echo $text_no_shipping_removed; ?></b></p>
				</td></tr>
			<?php } ?>
			
		<?php } ?>
		
	<?php } ?>
	
	<tr>
		<td colspan="4"> </td>
		<td> <b> <?php echo $ms_checkout_total_price; ?> </b> <span id="totals_formatted"><?php echo $checkout_totals_formatted; ?></span> </td>
	</tr>
	
	</table>

	<br />
	<br />
</form>
</div>

<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>