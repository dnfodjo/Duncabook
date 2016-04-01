<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" id="ms-submit-button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $this->url->link('multiseller/attribute', 'token=' . $this->session->data['token']); ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <form method="post" enctype="multipart/form-data" id="ms-attribute" class="form-horizontal">
    	<input type="hidden" name="attribute_id" value="<?php echo $attribute['attribute_id']; ?>" />

          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $ms_name; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="attribute_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute['attribute_description'][$language['language_id']]['name']) ? $attribute['attribute_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $ms_name; ?>" class="form-control" />
              </div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $ms_description; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                  <input type="text" size="60" name="attribute_description[<?php echo $language['language_id']; ?>][description]" value="<?php echo isset($attribute['attribute_description'][$language['language_id']]['description']) ? $attribute['attribute_description'][$language['language_id']]['description'] : ''; ?>"  placeholder="<?php echo $ms_description; ?>" class="form-control" />
              </div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $ms_attribute_group; ?></label>
            <div class="col-sm-10">
                <select class="form-control" name="attribute_group_id">
                    <?php foreach($attribute_groups as $attribute_group) { ?>
                    <option value="<?php echo $attribute_group['attribute_group_id']; ?>" <?php if ($attribute_group['attribute_group_id'] == $attribute['attribute_group_id']) { ?>selected="selected"<?php } ?>><?php echo $attribute_group['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $ms_type; ?></label>
            <div class="col-sm-10">
                <select class="form-control" name="attribute_type">
                    <option value="<?php echo MsAttribute::TYPE_CHECKBOX; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_CHECKBOX) { ?>selected<?php } ?>><?php echo $ms_type_checkbox; ?></option>
                    <option value="<?php echo MsAttribute::TYPE_DATE; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_DATE) { ?>selected<?php } ?>><?php echo $ms_type_date; ?></option>
                    <option value="<?php echo MsAttribute::TYPE_DATETIME; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_DATETIME) { ?>selected<?php } ?>><?php echo $ms_type_datetime; ?></option>
                    <?php /* ?><option value="<?php echo MsAttribute::TYPE_FILE; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_FILE) { ?>selected<?php } ?>><?php echo $ms_type_file; ?></option><?php */ ?>
                    <option value="<?php echo MsAttribute::TYPE_IMAGE; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_IMAGE) { ?>selected<?php } ?>><?php echo $ms_type_image; ?></option>
                    <option value="<?php echo MsAttribute::TYPE_RADIO; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_RADIO) { ?>selected<?php } ?>><?php echo $ms_type_radio; ?></option>
                    <option value="<?php echo MsAttribute::TYPE_SELECT; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_SELECT) { ?>selected<?php } ?>><?php echo $ms_type_select; ?></option>
                    <option value="<?php echo MsAttribute::TYPE_TEXT; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_TEXT) { ?>selected<?php } ?>><?php echo $ms_type_text; ?></option>
                    <option value="<?php echo MsAttribute::TYPE_TEXTAREA; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_TEXTAREA) { ?>selected<?php } ?>><?php echo $ms_type_textarea; ?></option>
                    <option value="<?php echo MsAttribute::TYPE_TIME; ?>" <?php if (isset($attribute['attribute_type']) && $attribute['attribute_type'] == MsAttribute::TYPE_TIME) { ?>selected<?php } ?>><?php echo $ms_type_time; ?></option>
			    </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $ms_attribute_text_type; ?></label>
            <div class="col-sm-10">
				<label class="radio-inline"><input type="radio" name="text_type" value="normal" <?php if(!$attribute['multilang'] && !$attribute['number']) { ?>checked="checked"<?php } ?> /><?php echo $ms_attribute_normal; ?></label>
				<label class="radio-inline"><input type="radio" name="text_type" value="multilang" <?php if($attribute['multilang']) { ?>checked="checked"<?php } ?> /><?php echo $ms_attribute_multilang; ?></label>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $ms_attribute_tab_display; ?></label>
            <div class="col-sm-10">
				<label class="radio-inline"><input type="radio" name="tab_display" value="1" <?php if(isset($attribute['tab_display']) && $attribute['tab_display']) { ?>checked="checked"<?php } ?> /><?php echo $text_yes; ?></label>
				<label class="radio-inline"><input type="radio" name="tab_display" value="0" <?php if(!$attribute['tab_display'] || !isset($attribute['tab_display'])) { ?>checked="checked"<?php } ?> /><?php echo $text_no; ?></label>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $ms_attribute_required; ?></label>
            <div class="col-sm-10">
                <input class="form-control" type="checkbox" name="required" <?php if($attribute['required']) { ?>checked="checked"<?php } ?> />
            </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $ms_sort_order; ?></label>
              <div class="col-sm-10 control-inline">
                <input class="form-control" type="text" name="sort_order" value="<?php echo $attribute['sort_order']; ?>" size="1" />
              </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $ms_enabled; ?></label>
            <div class="col-sm-10">
                <input class="form-control" type="checkbox" name="enabled" <?php if($attribute['enabled'] || !isset($attribute['attribute_id'])) { ?>checked="checked"<?php } ?> />
            </div>
          </div>
          
          <table id="attribute-value" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td class="text-left"><span class="required">*</span> <?php echo $ms_attribute_value; ?></td>
                    <td class="text-left"><?php echo $ms_image; ?></td>
                    <td class="text-right"><?php echo $ms_sort_order; ?></td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
            <tr class="ffSample">
                <td>
                  <?php foreach ($languages as $language) { ?>
                  <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                      <input type="text" name="attribute_value[0][attribute_value_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $ms_name; ?>" class="form-control" />
                  </div>
                  <?php } ?>
                </td>

                <td>
                    <a href="" id="field0" data-toggle="image" class="img-thumbnail"><img src="<?php echo $no_image; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a>
                    <input type="hidden" name="attribute_value[0][image]" value="" id="field0" />
                </td>

                <td>
                    <input type="text" name="attribute_value[0][sort_order]" value="" size="1" class="form-control" />
                </td>

                <td>
                    <button type="button" data-toggle="tooltip" title="<?php echo $ms_delete; ?>" class="ms-button-del btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                </td>
            </tr>

            <?php $attribute_value_row = 1; ?>
            <?php if (isset($attribute['attribute_values'])) { ?>
            <?php foreach ($attribute['attribute_values'] as $attribute_value) { ?>
            <tr>
                <td>
                  <input type="hidden" name="attribute_value[<?php echo $attribute_value_row; ?>][attribute_value_id]" value="<?php echo $attribute_value['attribute_value_id']; ?>" />

                  <?php foreach ($languages as $language) { ?>
                  <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                      <input type="text" name="attribute_value[<?php echo $attribute_value_row; ?>][attribute_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_value['attribute_value_description'][$language['language_id']]) ? $attribute_value['attribute_value_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $ms_name; ?>" class="form-control" />
                  </div>
                  <div class="text-danger"></div>
                  <?php } ?>
                </td>

                <td>
                    <a href="" id="field0" data-toggle="image" class="img-thumbnail"><img src="<?php echo $attribute_value['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a>
                    <input type="hidden" name="attribute_value[<?php echo $attribute_value_row; ?>][image]" value="<?php echo $attribute_value['image']; ?>" id="field<?php echo $attribute_value_row; ?>" />
                </td>

                <td>
                    <input type="text" name="attribute_value[<?php echo $attribute_value_row; ?>][sort_order]" value="<?php echo $attribute_value['sort_order']; ?>" size="1" class="form-control" />
                    <div class="text-danger"></div>
                </td>

                <td>
                    <button type="button" data-toggle="tooltip" title="<?php echo $ms_delete; ?>" class="ms-button-del btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                </td>
            </tr>
            <?php $attribute_value_row++; ?>
            <?php } ?>
            <?php } ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $ms_add_attribute_value; ?>" class="ffClone btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$(function() {
	$('select[name="attribute_type"]').bind('change', function() {
		if (this.value == '<?php echo MsAttribute::TYPE_SELECT; ?>' || this.value == '<?php echo MsAttribute::TYPE_RADIO; ?>' || this.value == '<?php echo MsAttribute::TYPE_CHECKBOX; ?>' || this.value == '<?php echo MsAttribute::TYPE_IMAGE; ?>') {
			$('#attribute-value').show();
		} else {
			$('#attribute-value').hide();
		}
		
		if (this.value == '<?php echo MsAttribute::TYPE_TEXT; ?>' || this.value == '<?php echo MsAttribute::TYPE_TEXTAREA; ?>') {
			$('[name="text_type"], [name="tab_display"]').parents('.form-group').show();
		} else {
			$('[name="text_type"], [name="tab_display"]').parents('.form-group').hide();
		}
	}).change();

	$("#ms-submit-button").click(function() {
		var button = $(this);
		var id = $(this).attr('id');
		$.ajax({
			type: "POST",
			dataType: "json",
			url: 'index.php?route=multiseller/attribute/jxsubmitattribute&token=<?php echo $token; ?>',
			data: $('#ms-attribute').serialize(),
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

                        $('[name="'+error+'"]').parents('.col-sm-10, td').append('<div class="text-danger">' + jsonData.errors[error] + '</div>');
                    }
                } else {
                    window.location = jsonData['redirect'];
                }
			}
		});
	});
	
	$('body').delegate("button.ffClone", "click", function() {
		var lastRow = $(this).parents('table').find('tbody tr:last input:last').attr('name');
		if (typeof lastRow == "undefined") {
			var newRowNum = 1;
		} else {
			var newRowNum = parseInt(lastRow.match(/[0-9]+/)) + 1;
		}

		var newRow = $(this).parents('table').find('tbody tr.ffSample').clone();
		newRow.find('input,select').attr('name', function(i,name) {
			return name.replace('[0]','[' + newRowNum + ']');
		});
		
		// %!@#$!!
		newRow.find('input[id^="field"]').attr('id', function(i,id) {
			return id.replace('0',newRowNum);
		});		
		
		$(this).parents('table').find('tbody').append(newRow.removeAttr('class'));
	});
	
	$("body").delegate(".ms-button-del", "click", function() {
		$(this).parents('tr').remove();
	});
});
</script> 
<?php echo $footer; ?>