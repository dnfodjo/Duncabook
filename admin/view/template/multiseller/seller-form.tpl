<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" id="ms-submit-button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $this->url->link('multiseller/seller', 'token=' . $token); ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $ms_catalog_sellers_heading; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo isset($seller['seller_id']) ? $ms_catalog_sellerinfo_heading : $ms_catalog_sellers_newseller; ?></h3>
      </div>
      <div class="panel-body">
        <form id="ms-sellerinfo" class="form-horizontal">
            <input type="hidden" id="seller_id" name="seller[seller_id]" value="<?php echo $seller['seller_id']; ?>" />
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                <li><a href="#tab-commission" data-toggle="tab"><?php echo $ms_commissions_fees; ?></a></li>
            </ul>
            <div class="tab-content">
            <div class="tab-pane active" id="tab-general">

            <fieldset>
            <legend><?php echo $ms_catalog_sellerinfo_customer_data; ?></legend>
             <div class="form-group required">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_customer; ?></label>

                <div class="col-sm-10">
                <?php if (!$seller['seller_id']) { ?>
                <select class="form-control" name="customer[customer_id]">
                    <optgroup label="<?php echo $ms_catalog_sellerinfo_customer_new; ?>">
                    <option value="0"><?php echo $ms_catalog_sellerinfo_customer_create_new; ?></option>
                    </optgroup>
                    <?php if (isset($customers)) { ?>
                    <optgroup label="<?php echo $ms_catalog_sellerinfo_customer_existing; ?>">
                    <?php foreach ($customers as $c) { ?>
                    <option value="<?php echo $c['c.customer_id']; ?>"><?php echo $c['c.name']; ?></option>
                    <?php } ?>
                    </optgroup>
                    <?php } ?>
                </select>
                <?php } else { ?>
                    <a href="<?php echo $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $seller['seller_id'], 'SSL'); ?>"><?php echo $seller['name']; ?></a>
                <?php } ?>
                </div>
            </div>

            <div class="form-group required">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_customer_firstname; ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="customer[firstname]" value="" />
                </div>
            </div>

            <div class="form-group required">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_customer_lastname; ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="customer[lastname]" value="" />
                </div>
            </div>

            <div class="form-group required">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_customer_email; ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="customer[email]" value="" />
                </div>
            </div>

            <div class="form-group required">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_customer_password; ?></label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="customer[password]" value="" />
                </div>
            </div>

            <div class="form-group required">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_customer_password_confirm; ?></label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="customer[password_confirm]" value="" />
                </div>
            </div>
            </fieldset>

            <fieldset>
            <legend><?php echo $ms_catalog_sellerinfo_seller_data; ?></legend>
            <div class="form-group required">
                <label class="col-sm-2 control-label required"><?php echo $ms_catalog_sellerinfo_nickname; ?></label>
                <?php if (!empty($seller['ms.nickname'])) { ?>
                    <div class="col-sm-10" style="padding-top: 5px">
                        <b><?php echo $seller['ms.nickname']; ?></b>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="seller[nickname]" value="<?php echo $seller['ms.nickname']; ?>" />
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_keyword; ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="seller[keyword]" value="<?php echo $seller['keyword']; ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_sellergroup; ?></label>
                <div class="col-sm-10"><select class="form-control" name="seller[seller_group]">
                    <?php foreach ($seller_groups as $group) { ?>
                    <option value="<?php echo $group['seller_group_id']; ?>" <?php if ($seller['ms.seller_group'] == $group['seller_group_id']) { ?>selected="selected"<?php } ?>><?php echo $group['name']; ?></option>
                    <?php } ?>
                </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span data-toggle="tooltip" title="<?php echo $ms_catalog_sellerinfo_product_validation_note; ?>"><?php echo $ms_catalog_sellerinfo_product_validation; ?></span>
                </label>
                <div class="col-sm-10">
                        <select class="form-control" name="seller[product_validation]">
                            <option value="1" <?php if($seller['ms.product_validation'] == 1) { ?> selected="selected" <?php } ?>><?php echo $ms_config_product_validation_none; ?></option>
                        <option value="2" <?php if($seller['ms.product_validation'] == 2) { ?> selected="selected" <?php } ?>><?php echo $ms_config_product_validation_approval; ?></option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_description; ?></label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="seller[description]"><?php echo $seller['ms.description']; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_company; ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="seller[company]" value="<?php echo $seller['ms.company']; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_country; ?></label>
                <div class="col-sm-10"><select class="form-control" name="seller[country]">
                        <?php if (1==1) { ?>
                    <option value="" selected="selected"><?php echo $ms_catalog_sellerinfo_country_dont_display; ?></option>
                    <?php } else { ?>
                    <option value=""><?php echo $ms_catalog_sellerinfo_country_dont_display; ?></option>
                    <?php } ?>

                    <?php foreach ($countries as $country) { ?>
                    <?php if ($seller['ms.country_id'] == $country['country_id']) { ?>
                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_zone; ?></label>
                <div class="col-sm-10">
                    <select class="form-control" name="seller[zone]">
                    </select>
                    <p class="ms-note"><?php echo $ms_catalog_sellerinfo_zone_note; ?></p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_paypal; ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="seller[paypal]" value="<?php echo $seller['ms.paypal']; ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_avatar; ?></label>
                <div class="col-sm-10">
                    <div id="sellerinfo_avatar_files">
                        <?php if (!empty($seller['avatar'])) { ?>
                        <input type="hidden" name="seller[avatar_name]" value="<?php echo $seller['avatar']['name']; ?>" />
                        <img src="<?php echo $seller['avatar']['thumb']; ?>" />
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php $msSeller = new ReflectionClass('MsSeller'); ?>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_status; ?></label>
                <div class="col-sm-10">
                    <select class="form-control" name="seller[status]">
                    <?php foreach ($msSeller->getConstants() as $cname => $cval) { ?>
                        <?php if (strpos($cname, 'STATUS_') !== FALSE) { ?>
                            <option value="<?php echo $cval; ?>" <?php if ($seller['ms.seller_status'] == $cval) { ?>selected="selected"<?php } ?>><?php echo $this->language->get('ms_seller_status_' . $cval); ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_catalog_sellerinfo_notify; ?></label>
                <div class="col-sm-10">
                <input type="radio" name="seller[notify]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="seller[notify]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span data-toggle="tooltip" title="<?php echo $ms_catalog_sellerinfo_message_note; ?>"><?php echo $ms_catalog_sellerinfo_message; ?></span>
                </label>

                <div class="col-sm-10">
                    <textarea class="form-control" name="seller[message]" disabled="disabled"></textarea>
                </div>
            </div>
            </fieldset>

            </div>

            <div class="tab-pane" id="tab-commission">
            <table class="form">
            <input type="hidden" name="seller[commission_id]" value="<?php echo $seller['commission_id']; ?>" />
            <?php if (isset($seller['actual_fees'])) { ?>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $ms_commission_actual; ?></label>
                <div class="col-sm-10"><?php echo $seller['actual_fees']; ?></div>
            </div>
            <?php } ?>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $this->language->get('ms_commission_' . MsCommission::RATE_SALE); ?></label>
                <div class="col-sm-10 control-inline">
                    <input type="hidden" name="seller[commission][<?php echo MsCommission::RATE_SALE; ?>][rate_id]" value="<?php echo isset($seller['commission_rates'][MsCommission::RATE_SALE]['rate_id']) ? $seller['commission_rates'][MsCommission::RATE_SALE]['rate_id'] : ''; ?>" />
                    <input type="hidden" name="seller[commission][<?php echo MsCommission::RATE_SALE; ?>][rate_type]" value="<?php echo MsCommission::RATE_SALE; ?>" />
                    <?php echo $this->currency->getSymbolLeft(); ?>
                    <input type="text" class="form-control" name="seller[commission][<?php echo MsCommission::RATE_SALE; ?>][flat]" value="<?php echo isset($seller['commission_rates'][MsCommission::RATE_SALE]['flat']) ? $this->currency->format($seller['commission_rates'][MsCommission::RATE_SALE]['flat'], $this->config->get('config_currency'), '', FALSE) : '' ?>" size="3"/>
                    <?php echo $this->currency->getSymbolRight(); ?>
                    +<input type="text" class="form-control" name="seller[commission][<?php echo MsCommission::RATE_SALE; ?>][percent]" value="<?php echo isset($seller['commission_rates'][MsCommission::RATE_SALE]['percent']) ? $seller['commission_rates'][MsCommission::RATE_SALE]['percent'] : ''; ?>" size="3"/>%
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $this->language->get('ms_commission_' . MsCommission::RATE_LISTING); ?></label>
                <div class="col-sm-10 control-inline">
                    <input type="hidden" name="seller[commission][<?php echo MsCommission::RATE_LISTING; ?>][rate_id]" value="<?php echo isset($seller['commission_rates'][MsCommission::RATE_LISTING]['rate_id']) ? $seller['commission_rates'][MsCommission::RATE_LISTING]['rate_id'] : ''; ?>" />
                    <input type="hidden" name="seller[commission][<?php echo MsCommission::RATE_LISTING; ?>][rate_type]" value="<?php echo MsCommission::RATE_LISTING; ?>" />
                    <?php echo $this->currency->getSymbolLeft(); ?>
                    <input type="text" class="form-control" name="seller[commission][<?php echo MsCommission::RATE_LISTING; ?>][flat]" value="<?php echo isset($seller['commission_rates'][MsCommission::RATE_LISTING]['flat']) ? $this->currency->format($seller['commission_rates'][MsCommission::RATE_LISTING]['flat'], $this->config->get('config_currency'), '', FALSE) : '' ?>" size="3"/>
                    <?php echo $this->currency->getSymbolRight(); ?>
                    +<input type="text" class="form-control" name="seller[commission][<?php echo MsCommission::RATE_LISTING; ?>][percent]" value="<?php echo isset($seller['commission_rates'][MsCommission::RATE_LISTING]['percent']) ? $seller['commission_rates'][MsCommission::RATE_LISTING]['percent'] : ''; ?>" size="3"/>%
                    <select class="form-control" name="seller[commission][<?php echo MsCommission::RATE_LISTING; ?>][payment_method]">
                        <optgroup label="<?php echo $ms_payment_method; ?>">
                            <option value="0" <?php if(isset($seller['commission_rates'][MsCommission::RATE_LISTING]) && $seller['commission_rates'][MsCommission::RATE_LISTING]['payment_method'] == 0) { ?> selected="selected" <?php } ?>><?php echo $ms_payment_method_inherit; ?></option>
                            <option value="<?php echo MsPayment::METHOD_BALANCE; ?>" <?php if(isset($seller['commission_rates'][MsCommission::RATE_LISTING]) && $seller['commission_rates'][MsCommission::RATE_LISTING]['payment_method'] == MsPayment::METHOD_BALANCE) { ?> selected="selected" <?php } ?>><?php echo $ms_payment_method_balance; ?></option>
                            <option value="<?php echo MsPayment::METHOD_PAYPAL; ?>" <?php if(isset($seller['commission_rates'][MsCommission::RATE_LISTING]) && $seller['commission_rates'][MsCommission::RATE_LISTING]['payment_method'] == MsPayment::METHOD_PAYPAL) { ?> selected="selected" <?php } ?>><?php echo $ms_payment_method_paypal; ?></option>
                        </optgroup>
                    </select>
                </div>
            </div>
            </table>
            </div>
            <!--  end commission tab -->
			</div>
        </div>
        </form>
      </div>
    </div>
  </div>
	
	<script type="text/javascript">
	$(function() {
		$('input[name^="customer"]').parents('div.form-group').hide();
		$('[name="seller[notify]"], [name="seller[message]"]').parents('div.form-group').show();
		$('select[name="customer[customer_id]"]').bind('change', function() {
			if (this.value == '0') {
				$('input[name^="customer"]').parents('div.form-group').show();
				$('[name="seller[notify]"], [name="seller[message]"]').parents('div.form-group').hide();
			} else {
				$('input[name^="customer"]').parents('div.form-group').hide();
				$('[name="seller[notify]"], [name="seller[message]"]').parents('div.form-group').show();
			}
		}).change();
	
		$('input[name="seller[notify]"]').change(function() {
			if ($(this).val() == 0) {
				$('textarea[name="seller[message]"]').val('').attr('disabled','disabled');
			} else {
				$('textarea[name="seller[message]"]').removeAttr('disabled');
			}
		});
	
		$("#ms-submit-button").click(function() {
			var button = $(this);
			var id = $(this).attr('id');
			$.ajax({
				type: "POST",
				dataType: "json",
				url: 'index.php?route=multiseller/seller/jxsavesellerinfo&token=<?php echo $token; ?>',
				data: $('#ms-sellerinfo').serialize(),
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
							$('[name="'+error+'"]').after('<div class="text-danger">' + jsonData.errors[error] + '</div>');
						}
						window.scrollTo(0,0);
					} else {
						window.location = 'index.php?route=multiseller/seller&token=<?php echo $token; ?>';
					}
				 	}
			});
		});

		$("select[name='seller[country]']").bind('change', function() {
			$.ajax({
				url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + this.value,
				dataType: 'json',
				beforeSend: function() {
					$("select[name='seller[country]']").after('<i class="fa fa-circle-o-notch fa-spin"></i>');
				},
				complete: function() {
					$('.fa-spin').remove();
				},
				success: function(json) {
					html = '<option value=""><?php echo $ms_catalog_sellerinfo_zone_select; ?></option>';

					if (json['zone']) {
						for (i = 0; i < json['zone'].length; i++) {
							html += '<option value="' + json['zone'][i]['zone_id'] + '"';
							
							if (json['zone'][i]['zone_id'] == '<?php echo $seller['ms.zone_id']; ?>') {
								html += ' selected="selected"';
							}
			
							html += '>' + json['zone'][i]['name'] + '</option>';
						}
					} else {
						html += '<option value="0" selected="selected"><?php echo $ms_catalog_sellerinfo_zone_not_selected; ?></option>';
					}
					
					$("select[name='seller[zone]']").html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}).trigger('change');
	});
	</script>
<?php echo $footer; ?>