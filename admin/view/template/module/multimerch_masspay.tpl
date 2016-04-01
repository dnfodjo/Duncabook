<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" id="saveSettings" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $ms_config_masspay; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $ms_config_masspay; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-settings" class="form-horizontal">
			<div class="form-group required">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_paypal_api_username_note; ?>"><?php echo $ms_config_paypal_api_username; ?></span></label>
				<div class="col-sm-10 control-inline">
					<input type="text"  class="form-control" name="msconf_masspay_api_username" value="<?php echo $msconf_masspay_api_username; ?>" size="30"/>
				</div>
			</div>

			<div class="form-group required">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_paypal_api_password_note; ?>"><?php echo $ms_config_paypal_api_password; ?></span></label>
				<div class="col-sm-10 control-inline">
					<input type="text"  class="form-control" name="msconf_masspay_api_password" value="<?php echo $msconf_masspay_api_password; ?>" size="30"/>
				</div>
			</div>

			<div class="form-group required">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_paypal_api_signature_note; ?>"><?php echo $ms_config_paypal_api_signature; ?></span></label>
				<div class="col-sm-10 control-inline">
					<input type="text"  class="form-control" name="msconf_masspay_api_signature" value="<?php echo $msconf_masspay_api_signature; ?>" size="30"/>
				</div>
			</div>

			<div class="form-group required">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_paypal_sandbox_note; ?>"><?php echo $ms_config_paypal_sandbox; ?></span></label>
				<div class="col-sm-10">
					<input type="radio" name="msconf_masspay_sandbox" value="1" <?php if($msconf_masspay_sandbox == 1) { ?> checked="checked" <?php } ?>  />
					<?php echo $text_yes; ?>
					<input type="radio" name="msconf_masspay_sandbox" value="0" <?php if($msconf_masspay_sandbox == 0) { ?> checked="checked" <?php } ?>  />
					<?php echo $text_no; ?>
				</div>
			</div>
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
			url: 'index.php?route=module/multimerch_masspay/savesettings&token=<?php echo $token; ?>',
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
					window.location.reload();
				}
	       	}
		});
	});
});
</script>

<?php echo $footer; ?>