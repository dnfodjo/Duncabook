<table class="ms-shipping">
	
	<tr>
		<td></td>
		<td>
			<input type="hidden" class="form-control input-auto" class="ms_input_small" name="shipping_length" value="<?php echo $product['length']; ?>" size="8" />  <input type="hidden" class="form-control input-auto" class="ms_input_small" name="shipping_width" value="<?php echo $product['width']; ?>" size="8" />  <input type="hidden" class="form-control input-auto" class="ms_input_small" name="shipping_height" value="<?php echo $product['height']; ?>" size="8" />
			<p class="error" id="error_shipping_dimensions"></p>
		</td>
	</tr>
	

	
	<tr>
		<td><?php if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) { ?><span class="required">*</span><?php } ?></td>
		<td>
			<input type="hidden" class="form-control" name="shipping_weight" value="<?php echo $product['weight']; ?>" />
			<p class="error" id="error_shipping_weight"></p>
		</td>
	</tr>
	

	
	
	<!-- Continue only if Fixed Shipping Type selected for seller -->
	<?php if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) { ?>
	
		<tr>
			<td colspan="2">
				<h3><span class="required">*</span><?php echo $ms_product_shipping_methods; ?></h3>
				<p class="error" id="error_shipping_methods"></p>
			</td>
		</tr>
		
		<!-- Shipping method table -->
		<table id="shipping_method" class="list table table-bordered table-hover">
			<thead>
				<tr>
					<td class="text-left"><?php echo $ms_product_shipping_method_name; ?></td>
					<td class="text-left"><?php echo $ms_product_shipping_method_comment; ?></td>
					<td class="text-left"><span class="required">*</span><?php echo $ms_product_shipping_method_cost; ?></td>
					<!--<td class="text-left"><?php //echo $ms_product_shipping_method_currency; ?></td>-->
					<td class="text-left"><?php echo $ms_product_shipping_method_geo_zone; ?></td>
					<td class="text-left"></td>
				</tr>
			</thead>
			<tbody>
			
			<!-- sample row -->
			<tr class="ffSample">
			
				<td class="text-left">
					<select name="ms_shipping_methods[0][shipping_method_id]" class="form-control">
						<?php foreach ($shipping_methods as $shipping_method) { ?>
							<option value="<?php echo $shipping_method['shipping_method_id']; ?>"><?php echo $shipping_method['name']; ?></option>
						<?php } ?>
					</select>
				</td>
				
				<td class="text-left">
					<input type="text" class="form-control" name="ms_shipping_methods[0][comment]" value="" size="25" />
				</td>
			
				<td class="text-left">
					<?php echo $this->currency->getSymbolLeft($this->config->get('config_currency')); ?> <input type="text" class="form-control input-auto" name="ms_shipping_methods[0][cost]" value="" size="8" /> <?php echo $this->currency->getSymbolRight($this->config->get('config_currency')); ?>
				</td>
				
				<td class="text-left">
					<select name="ms_shipping_methods[0][geo_zone_id]" class="form-control">
						<?php foreach ($geo_zones as $geo_zone) { ?>
							<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
						<?php } ?>
					</select>
				</td>
				
				<td class="text-center">
					<button type="button" class="btn btn-danger ffRemove"><i class="fa fa-trash-o"></i></button>
				</td>
			</tr>		        
			<!-- /sample row -->
			
			<?php $row = 1; ?>
			<?php if (isset($ms_shipping_methods) && is_array($ms_shipping_methods)) { ?>
			<?php foreach ($ms_shipping_methods as $ms_shipping_method) { ?>
			<tr id="shipping-method-row<?php echo $row; ?>">
				<td class="text-left">
					<select name="ms_shipping_methods[<?php echo $row; ?>][shipping_method_id]" class="form-control">
						<?php foreach ($shipping_methods as $shipping_method) { ?>
							<?php if ($ms_shipping_method['shipping_method_id'] == $shipping_method['shipping_method_id']) { ?>
								<option value="<?php echo $shipping_method['shipping_method_id']; ?>" selected="selected"><?php echo $shipping_method['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $shipping_method['shipping_method_id']; ?>"><?php echo $shipping_method['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
				
				<td class="text-left">
					<input type="text" class="form-control" name="ms_shipping_methods[<?php echo $row; ?>][comment]" value="<?php echo $ms_shipping_method['comment']; ?>" size="25" />
				</td>
				
				<td class="text-left">
					<?php echo $this->currency->getSymbolLeft($this->config->get('config_currency')); ?> <input type="text" class="form-control  input-auto" name="ms_shipping_methods[<?php echo $row; ?>][cost]" value="<?php echo $ms_shipping_method['cost']; ?>" size="8" /> <?php echo $this->currency->getSymbolRight($this->config->get('config_currency')); ?>
				</td>
				
				<td class="text-left">
					<select name="ms_shipping_methods[<?php echo $row; ?>][geo_zone_id]" class="form-control">
						<?php foreach ($geo_zones as $geo_zone) { ?>
							<?php if ($ms_shipping_method['geo_zone_id'] == $geo_zone['geo_zone_id']) { ?>
								<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
				
				<td class="text-center">
					<button type="button" class="btn btn-danger ffRemove"><i class="fa fa-trash-o"></i></button>
				</td>
			</tr>
			<?php $row++; ?>
			<?php } ?>
			<?php } ?>
			</tbody>
			<tfoot>
			  <tr>
				<td colspan="4"></td>
				<td class="text-center">
					<a class="btn btn-primary ffClone"><i class="fa fa-plus"></i></a>
				</td>
			  </tr>
			</tfoot>
		</table>
	<?php } ?>
</table>

<script type="text/javascript">
	$('body').delegate("a.ffRemove", "click", function() {
		$(this).parents('tr').remove();
	});
</script>