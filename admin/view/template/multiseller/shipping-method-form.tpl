<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" id="ms-submit-button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $this->url->link('multiseller/badge', 'token=' . $this->session->data['token']); ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div style="display: none" class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading; ?></h3>
      </div>
      <div class="panel-body">
        <form method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
			<input type="hidden" name="shipping_method[shipping_method_id]" value="<?php echo $shipping_method['shipping_method_id']; ?>" />
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_name; ?></label>
				<div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
					<input class="form-control" type="text" name="shipping_method[description][<?php echo $language['language_id']; ?>][name]" value="<?php echo $shipping_method['description'][$language['language_id']]['name']; ?>" />
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					<p class="error" id="error_name_<?php echo $language['language_id']; ?>"></p>
				<?php } ?>
				</div>
			</div>

			<?php foreach ($languages as $language) { ?>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $ms_description; ?></label>
				<div class="col-sm-10">
					<textarea class="form-control" type="text" name="shipping_method[description][<?php echo $language['language_id']; ?>][description]"><?php echo $shipping_method['description'][$language['language_id']]['description']; ?></textarea>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
					<p class="error" id="error_description"></p>
				</div>
			</div>
			<?php } ?>
		</form>
      </div>
	</div>
  </div>
</div>

<script>
$("#ms-submit-button").click(function() {
	var button = $(this);
	var id = $(this).attr('id');
	$.ajax({
		type: "POST",
		dataType: "json",
		url: 'index.php?route=multiseller/shipping-method/jxSave&token=<?php echo $token; ?>',
		data: $('#form').serialize(),
		beforeSend: function() {
			$('div.text-danger').remove();
            $('.alert-danger').hide().find('i').text('');
		},
		complete: function(jqXHR, textStatus) {
            button.show().prev('span.wait').remove();
            $('.alert-danger').hide().find('i').text('');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$('.alert-danger').show().find('i').text(textStatus);
		},
		success: function(jsonData) {
			if (!jQuery.isEmptyObject(jsonData.errors)) {
				for (error in jsonData.errors) {
				    if (!jsonData.errors.hasOwnProperty(error)) {
				        continue;
				    }

					$('[name="'+error+'"]').after('<div class="text-danger">' + jsonData.errors[error] + '</div>');
				}
			} else {
				window.location = 'index.php?route=multiseller/shipping-method&token=<?php echo $token; ?>';
			}
		}
	});
});
</script>
<?php echo $footer; ?>