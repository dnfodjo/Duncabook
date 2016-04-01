<?php if ($shipping_methods) { ?>
	<?php if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED || $shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) { ?>
		<div class="table-responsive">
		<table class="table table-bordered">
			<?php foreach ($shipping_methods as $shipping_method) { ?>
				<tr class="highlight">
					<td>
						<?php echo $shipping_method['method_descriptions'][$language_id]['name'] . ( ( !empty($shipping_method['comment']) && $shipping_method['comment'] != "" ) ? " (" . $shipping_method['comment'] . ")" : "" ); ?>
					</td>
					<td style="text-align: right;">
						<?php echo $shipping_method['cost']; ?>
					</td>
				</tr>
			<?php } ?>
		</table>
		</div>
	<?php } ?>
	<!--else $product['shipping_type'] == MsShipping::SHIPPING_TYPE_NOT_DEFINED-->
<?php } else { ?>
	<?php echo $ms_no_shipping_to_geo_zone; ?>
<?php } ?>