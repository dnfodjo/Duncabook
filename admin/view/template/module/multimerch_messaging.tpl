<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" id="saveSettings" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $mmes_messaging; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $mmes_messaging; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-settings">
		<div class="form-group required">
			<label class="col-sm-2 control-label"><?php echo $mmess_config_enable; ?></label>
			<div class="col-sm-10">
				<input type="radio" name="mmess_conf_enable" value="1" <?php if($mmess_conf_enable == 1) { ?> checked="checked" <?php } ?>  />
				<?php echo $text_yes; ?>
				<input type="radio" name="mmess_conf_enable" value="0" <?php if($mmess_conf_enable == 0) { ?> checked="checked" <?php } ?>  />
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
			url: 'index.php?route=module/multimerch_messaging/savesettings&token=<?php echo $token; ?>',
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