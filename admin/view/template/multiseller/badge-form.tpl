<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" id="ms-submit-button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $this->url->link('multiseller/badge', 'token=' . $this->session->data['token']); ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $ms_catalog_insert_badge_heading; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $ms_catalog_insert_badge_heading; ?></h3>
      </div>
      <div class="panel-body">
        <form method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
			<input type="hidden" name="badge[badge_id]" value="<?php echo $badge['badge_id']; ?>" />

			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_name; ?></label>
				<div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
					<input class="form-control" type="text" name="badge[description][<?php echo $language['language_id']; ?>][name]" value="<?php echo $badge['description'][$language['language_id']]['name']; ?>" />
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
				<?php } ?>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $ms_description; ?></label>
				<div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
					<textarea class="form-control" name="badge[description][<?php echo $language['language_id']; ?>][description]" cols="40" rows="5"><?php echo $badge['description'][$language['language_id']]['description']; ?></textarea>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" />
				<?php } ?>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $ms_badges_image; ?></label>
				<div class="col-sm-10">
					<a href="" id="thumb-field0" data-toggle="image" class="img-thumbnail"><img src="<?php echo ($badge['image'] != '' ? $badge['image'] : $no_image); ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a>
					<input type="hidden" name="badge[image]" value="" id="input-field0"/>
				</div>
			</div>
		</form>
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
		url: 'index.php?route=multiseller/badge/jxSave&token=<?php echo $token; ?>',
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
				window.location = 'index.php?route=multiseller/badge&token=<?php echo $token; ?>';
			}
		}
	});
});
</script>
<?php echo $footer; ?>