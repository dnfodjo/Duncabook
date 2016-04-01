<?php echo $header; ?>
<div class="container" class="ms-account-shipping-settings">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if (isset($success) && ($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?></div>
  <?php } ?>

  <?php if (isset($error_warning) && $error_warning) { ?>
  	<div class="alert alert-danger warning main"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="ms-account-transaction <?php echo $class; ?>"><?php echo $content_top; ?>
    <h1><?php echo $ms_account_shipping_settings_heading; ?></h1>

	<form id="ms-account-shipping-settings" method="post" enctype="multipart/form-data">
	<div class="table-responsive">
	<table class="list table table-bordered table-hover ms-shipping-settings">
			<!-- Shipping Type: Fixed/Combinable -->
			<tr>
				<td><?php echo $ms_account_shipping_settings_type; ?></td>
				<td>
					<input type="radio" name="shipping_type" value="0" <?php if ($shipping_type == MsShipping::SHIPPING_TYPE_FIXED) { ?> checked="checked" <?php } ?>/>
					<?php echo $ms_shipping_type_fixed; ?>
					<input type="radio" name="shipping_type" value="1" <?php if ($shipping_type == MsShipping::SHIPPING_TYPE_COMBINABLE) { ?> checked="checked" <?php } ?>/>
					<?php echo $ms_shipping_type_combinable; ?>
					<p class="ms-note"><?php echo $ms_account_shipping_settings_type_note; ?></p>
				</td>
			</tr>
			
			<!-- Combinable shipping type section -->
			
			<tr name="seller_shipping_method_heading" id="seller_shipping_method_heading">
				<td colspan="2">
					<h3><span class="required">*</span><?php echo $ms_product_shipping_methods; ?></h3>
					<p class="error" id="error_shipping_methods_cost"></p>
					<p class="error" id="error_shipping_methods_weight"></p>
				</td>
			</tr>
			
			<tr name="seller_shipping_method_table" id="seller_shipping_method_table">
				<td colspan="2">
					<div class="table-responsive">
					<table class="list table table-bordered table-hover ms-shipping-settings" id="seller_shipping_method" name="seller_shipping_method">
					<!-- Shipping method table -->
						<thead>
							<tr>
								<td class="left"><?php echo $ms_seller_shipping_method_name; ?></td>
								<td class="left"><?php echo $ms_seller_shipping_method_comment; ?></td>
								<td class="left"><?php echo $ms_seller_shipping_method_geo_zone; ?></td>
								<td class="left"><span class="required">*</span><?php echo $ms_seller_shipping_method_weight_step; ?></td>
								<td class="left"><?php echo $ms_seller_shipping_method_weight_unit; ?></td>
								<td class="left"><span class="required">*</span><?php echo $ms_seller_shipping_method_cost_per_unit; ?></td>
								<td class="left"></td>
							</tr>
						</thead>
						<tbody>
						
						<!-- sample row -->
						<tr class="ffSample" name="shipping-method-row">
						
							<td class="left">
								<select class="form-control" name="ms_shipping_methods[0][shipping_method_id]">
									<?php foreach ($shipping_methods as $shipping_method) { ?>
										<option value="<?php echo $shipping_method['shipping_method_id']; ?>"><?php echo $shipping_method['name']; ?></option>
									<?php } ?>
								</select>
							</td>
							
							<td class="left">
								<input type="text" class="form-control" name="ms_shipping_methods[0][comment]" value="" size="25" />
							</td>
							
							<td class="left">
								<select class="form-control" name="ms_shipping_methods[0][geo_zone_id]">
									<?php foreach ($geo_zones as $geo_zone) { ?>
										<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
									<?php } ?>
								</select>
							</td>
							
							<td class="left">
								<input type="text" class="form-control" name="ms_shipping_methods[0][weight_step]" value="" size="8" />
							</td>
							
							<td class="left">
								<select class="form-control" name="ms_shipping_methods[0][weight_class_id]">
									<?php foreach ($weight_classes as $weight_class) { ?>
										<?php if ($weight_class['weight_class_id'] == $this->config->get('config_weight_class_id')) { ?>
											<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							
							<td class="left">
								<?php echo $this->currency->getSymbolLeft($this->config->get('config_currency')); ?> <input type="text" class="form-control input-auto" name="ms_shipping_methods[0][cost_per_unit]" value="" size="8" /> <?php echo $this->currency->getSymbolRight($this->config->get('config_currency')); ?>
							</td>
							
							<td class="left">
								<button type="button" data-toggle="tooltip" title="<?php echo $ms_product_shipping_method_button_remove; ?>" class="ffRemove ms-button-del btn btn-danger"><i class="fa fa-minus-circle"></i></button>
							</td>
						</tr>		        
						<!-- /sample row -->
						
						<?php $row = 1; ?>
						<?php if (isset($ms_shipping_methods) && is_array($ms_shipping_methods)) { ?>
						<?php foreach ($ms_shipping_methods as $ms_shipping_method) { ?>
						<tr id="shipping-method-row<?php echo $row; ?>" name="shipping-method-row">
						
							<td class="left">
								<select class="form-control" name="ms_shipping_methods[<?php echo $row; ?>][shipping_method_id]">
									<?php foreach ($shipping_methods as $shipping_method) { ?>
										<?php if ($ms_shipping_method['shipping_method_id'] == $shipping_method['shipping_method_id']) { ?>
											<option value="<?php echo $shipping_method['shipping_method_id']; ?>" selected="selected"><?php echo $shipping_method['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $shipping_method['shipping_method_id']; ?>"><?php echo $shipping_method['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							
							<td class="left">
								<input type="text" class="form-control" name="ms_shipping_methods[<?php echo $row; ?>][comment]" value="<?php echo $ms_shipping_method['comment']; ?>" size="25" />
							</td>
							
							<td class="left">
								<select class="form-control" name="ms_shipping_methods[<?php echo $row; ?>][geo_zone_id]">
									<?php foreach ($geo_zones as $geo_zone) { ?>
										<?php if ($ms_shipping_method['geo_zone_id'] == $geo_zone['geo_zone_id']) { ?>
											<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							
							<td class="left">
								<input type="text" class="form-control" name="ms_shipping_methods[<?php echo $row; ?>][weight_step]" value="<?php echo $ms_shipping_method['weight_step']; ?>" size="8" />
							</td>
							
							<td class="left">
								<select class="form-control" name="ms_shipping_methods[<?php echo $row; ?>][weight_class_id]">
									<?php foreach ($weight_classes as $weight_class) { ?>
										<?php if ($ms_shipping_method['weight_class_id'] == $weight_class['weight_class_id']) { ?>
											<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							
							<td class="left">
								<?php echo $this->currency->getSymbolLeft($this->config->get('config_currency')); ?> <input type="text" class="form-control input-auto" name="ms_shipping_methods[<?php echo $row; ?>][cost_per_unit]" value="<?php echo $ms_shipping_method['cost_per_unit']; ?>" size="8" /> <?php echo $this->currency->getSymbolRight($this->config->get('config_currency')); ?>
							</td>
							
							<td class="left">
								<button type="button" data-toggle="tooltip" title="<?php echo $ms_product_shipping_method_button_remove; ?>" class="ffRemove ms-button-del btn btn-danger"><i class="fa fa-minus-circle"></i></button>
							</td>
							
						</tr>
						<?php $row++; ?>
						<?php } ?>
						<?php } ?>
						</tbody>
						<tfoot>
						  <tr>
							<td colspan="6"></td>
							<td class="left">
								<button type="button" data-toggle="tooltip" title="<?php echo $ms_product_shipping_method_button_add; ?>" class="ffClone btn btn-primary"><i class="fa fa-plus-circle"></i></button>
							  </td>
						  </tr>
						</tfoot>
					</table>
					</div>
				</td>
			</tr>
	</table>
	</div>

	<div class="buttons clearfix">
		<div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
		<div class="pull-right"><a class="btn btn-primary" id="ms-submit-button"><?php echo $ms_button_submit; ?></a></div>
	</div>
	<?php echo $content_bottom; ?></div>
	<?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>