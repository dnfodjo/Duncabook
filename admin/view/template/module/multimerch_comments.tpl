<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" id="saveSettings" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $ms_menu_comments; ?></h1>
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

    <div class="alert alert-success hidden"><i class="fa fa-check-circle"></i>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $ms_menu_comments; ?></h3>
      </div>
      <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-settings" class="form-horizontal">
				<!-- PRODUCT COMMENTS -->
				<fieldset>
				<legend><?php echo $ms_config_product_comments; ?></legend>
				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_enable_note; ?>"><?php echo $ms_config_comments_enable; ?></span></label>
					<div class="col-sm-10">
						<input type="radio" name="mscomm_comments_enable" value="1" <?php if($mscomm_comments_enable == 1) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_yes; ?>
						<input type="radio" name="mscomm_comments_enable" value="0" <?php if($mscomm_comments_enable == 0) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_no; ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_allow_guests_note; ?>"><?php echo $ms_config_comments_allow_guests; ?></span></label>
					<div class="col-sm-10">
						<input type="radio" name="mscomm_comments_allow_guests" value="1" <?php if($mscomm_comments_allow_guests == 1) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_yes; ?>
						<input type="radio" name="mscomm_comments_allow_guests" value="0" <?php if($mscomm_comments_allow_guests == 0) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_no; ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_enforce_customer_data_note; ?>"><?php echo $ms_config_comments_enforce_customer_data; ?></span></label>
					<div class="col-sm-10">
						<input type="radio" name="mscomm_comments_enforce_customer_data" value="1" <?php if($mscomm_comments_enforce_customer_data == 1) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_yes; ?>
						<input type="radio" name="mscomm_comments_enforce_customer_data" value="0" <?php if($mscomm_comments_enforce_customer_data == 0) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_no; ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_enable_customer_captcha_note; ?>"><?php echo $ms_config_comments_enable_customer_captcha; ?></span></label>
					<div class="col-sm-10">
						<input type="radio" name="mscomm_comments_enable_customer_captcha" value="1" <?php if($mscomm_comments_enable_customer_captcha == 1) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_yes; ?>
						<input type="radio" name="mscomm_comments_enable_customer_captcha" value="0" <?php if($mscomm_comments_enable_customer_captcha == 0) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_no; ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_perpage_note; ?>"><?php echo $ms_config_comments_perpage; ?></span></label>
					<div class="col-sm-10">
						<input size="2" type="text" class="form-control" name="mscomm_comments_perpage" value="<?php echo $mscomm_comments_perpage; ?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_maxlen_note; ?>"><?php echo $ms_config_comments_maxlen; ?></span></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mscomm_comments_maxlen" value="<?php echo $mscomm_comments_maxlen; ?>" size="3"/>
					</div>
				</div>
				</fieldset>
				<!-- PRODUCT COMMENTS END -->

				<!-- SELLER COMMENTS -->
				<fieldset>
				<legend><?php echo $ms_config_seller_comments; ?></legend>
				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_seller_comments_enable_note; ?>"><?php echo $ms_config_seller_comments_enable; ?></span></label>
					<div class="col-sm-10">
						<input type="radio" name="mscomm_seller_comments_enable" value="1" <?php if($mscomm_seller_comments_enable == 1) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_yes; ?>
						<input type="radio" name="mscomm_seller_comments_enable" value="0" <?php if($mscomm_seller_comments_enable == 0) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_no; ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_allow_guests_note; ?>"><?php echo $ms_config_comments_allow_guests; ?></span></label>
					<div class="col-sm-10">
						<input type="radio" name="mscomm_seller_comments_allow_guests" value="1" <?php if($mscomm_seller_comments_allow_guests == 1) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_yes; ?>
						<input type="radio" name="mscomm_seller_comments_allow_guests" value="0" <?php if($mscomm_seller_comments_allow_guests == 0) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_no; ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_enforce_customer_data_note; ?>"><?php echo $ms_config_comments_enforce_customer_data; ?></span></label>
					<div class="col-sm-10">
						<input type="radio" name="mscomm_seller_comments_enforce_customer_data" value="1" <?php if($mscomm_seller_comments_enforce_customer_data == 1) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_yes; ?>
						<input type="radio" name="mscomm_seller_comments_enforce_customer_data" value="0" <?php if($mscomm_seller_comments_enforce_customer_data == 0) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_no; ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_enable_customer_captcha_note; ?>"><?php echo $ms_config_comments_enable_customer_captcha; ?></span></label>
					<div class="col-sm-10">
						<input type="radio" name="mscomm_seller_comments_enable_customer_captcha" value="1" <?php if($mscomm_seller_comments_enable_customer_captcha == 1) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_yes; ?>
						<input type="radio" name="mscomm_seller_comments_enable_customer_captcha" value="0" <?php if($mscomm_seller_comments_enable_customer_captcha == 0) { ?> checked="checked" <?php } ?>  />
						<?php echo $text_no; ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_perpage_note; ?>"><?php echo $ms_config_comments_perpage; ?></span></label>
					<div class="col-sm-10">
						<input size="2" type="text" class="form-control" name="mscomm_seller_comments_perpage" value="<?php echo $mscomm_seller_comments_perpage; ?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_comments_maxlen_note; ?>"><?php echo $ms_config_comments_maxlen; ?></span></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mscomm_seller_comments_maxlen" value="<?php echo $mscomm_seller_comments_maxlen; ?>" size="3"/>
					</div>
				</div>
				</fieldset>
				<!-- SELLER COMMENTS END -->
		</form>
	</div>
  </div>
</div>

<script>
$(function() {
	$("#saveSettings").click(function() {
	    $.ajax({
			type: "POST",
			dataType: "json",
			url: 'index.php?route=module/multimerch_comments/savesettings&token=<?php echo $token; ?>',
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
					$(".alert-success").removeClass('hidden').find('i').append('<p>'+jsonData.success+'</p>');
					window.location.reload();
				}
	       	}
		});
	});
});
</script>

<?php echo $footer; ?>