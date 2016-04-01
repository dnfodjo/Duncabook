<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" id="saveSettings" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $ms_catalog_shipping_settings; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="alert alert-danger hidden"><i class="fa fa-exclamation-circle"></i>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $ms_catalog_shipping_settings; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-settings" class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_product_shipping_cost_estimation_note; ?>"><?php echo $ms_config_enable_product_shipping_cost_estimation; ?></span></label>
				<div class="col-sm-10">
					<input type="radio" name="msship_product_shipping_cost_estimation" value="1" <?php if($msship_product_shipping_cost_estimation == 1) { ?> checked="checked" <?php } ?>  />
					<?php echo $text_yes; ?>
					<input type="radio" name="msship_product_shipping_cost_estimation" value="0" <?php if($msship_product_shipping_cost_estimation == 0) { ?> checked="checked" <?php } ?>  />
					<?php echo $text_no; ?>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_minicart_shipping_estimate_note; ?>"><?php echo $ms_config_enable_minicart_shipping_estimate; ?></span></label>
				<div class="col-sm-10">
					<input type="radio" name="msship_enable_minicart_shipping_estimate" value="1" <?php if($msship_enable_minicart_shipping_estimate == 1) { ?> checked="checked" <?php } ?>  />
					<?php echo $text_yes; ?>
					<input type="radio" name="msship_enable_minicart_shipping_estimate" value="0" <?php if($msship_enable_minicart_shipping_estimate == 0) { ?> checked="checked" <?php } ?>  />
					<?php echo $text_no; ?>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_physical_product_categories_note; ?>"><?php echo $ms_config_physical_product_categories; ?></span></label>
				<div class="col-sm-10">
					<div class="well well-sm" style="height: 150px; overflow: auto;">
						<?php foreach ($categories as $category) { ?>
						  <input type="checkbox" name="msship_physical_product_categories[]" value="<?php echo $category['category_id']; ?>" <?php if (isset($msship_physical_product_categories) && in_array($category['category_id'], $msship_physical_product_categories)) { ?>checked="checked"<?php } ?> /> <?php echo $category['name']; ?><br>
						<?php } ?>
				    </div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_digital_product_categories_note; ?>"><?php echo $ms_config_digital_product_categories; ?></span></label>
				<div class="col-sm-10">
					<div class="well well-sm" style="height: 150px; overflow: auto;">
						<?php foreach ($categories as $category) { ?>
						  <input type="checkbox" name="msship_digital_product_categories[]" value="<?php echo $category['category_id']; ?>" <?php if (isset($msship_digital_product_categories) && in_array($category['category_id'], $msship_digital_product_categories)) { ?>checked="checked"<?php } ?> /> <?php echo $category['name']; ?><br>
						<?php } ?>
				    </div>
				</div>
			</div>

			<!--
			<tr>
				<td>
					<span><?php echo $ms_config_download_limit_applies; ?></span>
					<span class="help"><?php echo $ms_config_download_limit_applies_note; ?></span>
				</td>
				<td>
					<input type="radio" name="msship_download_limit_applies" value="1" <?php if($msship_download_limit_applies == 1) { ?> checked="checked" <?php } ?>  />
					<?php echo $ms_config_download_limit_applies_digital; ?>
					<input type="radio" name="msship_download_limit_applies" value="0" <?php if($msship_download_limit_applies == 0) { ?> checked="checked" <?php } ?>  />
					<?php echo $ms_config_download_limit_applies_all; ?>
				</td>
			</tr>
			-->
		</form>
	  </div>
	</div>
  </div>
</div>

<script>
$(function() {
	$("#saveSettings").click(function() {
	    $.ajax({
			type: "POST",
			dataType: "json",
			url: 'index.php?route=module/multimerch_shipping/savesettings&token=<?php echo $token; ?>',
			data: $('#form-settings').serialize(),
			success: function(jsonData) {
				$('.alert-danger').addClass('hidden').find('i').text('');
				if (jsonData.errors) {
					for (error in jsonData.errors) {
					    if (!jsonData.errors.hasOwnProperty(error)) {
					        continue;
					    }
					    $('.alert-danger').removeClass('hidden').find('i').append('<p>'+jsonData.errors[error]+'</p>');
					}
				} else {
					//$(".alert-success").removeClass('hidden').find('i').append('<p>'+jsonData.success+'</p>');
					window.location.reload();
				}
	       	}
		});
	});
});
</script>

<?php echo $footer; ?>