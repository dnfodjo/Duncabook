<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" id="ms-submit-button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $this->url->link('multiseller/seller-group', 'token=' . $this->session->data['token']); ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <input type="hidden" name="seller_group[seller_group_id]" value="<?php echo $seller_group['seller_group_id']; ?>" />
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-commission" data-toggle="tab"><?php echo $ms_commissions_fees; ?></a></li>
        </ul>

        <div class="tab-content">
        <div class="tab-pane active" id="tab-general">

          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $ms_name; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="seller_group[description][<?php echo $language['language_id']; ?>][name]" value="<?php echo $seller_group['description'][$language['language_id']]['name']; ?>" placeholder="<?php echo $ms_name; ?>" class="form-control" />
              </div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $ms_description; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="seller_group[description][<?php echo $language['language_id']; ?>][description]" cols="40" rows="5" placeholder="<?php echo $ms_description; ?>" class="form-control"><?php echo $seller_group['description'][$language['language_id']]['description']; ?></textarea>
              </div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group <?php if($seller_group['seller_group_id'] == $this->config->get('msconf_default_seller_group_id')) { ?>required<?php } ?>">
              <label class="col-sm-2 control-label"><?php echo $ms_product_period; ?></label>
              <div class="col-sm-10 control-inline">
                  <input class="form-control" type="text" name="seller_group[product_period]" value="<?php echo isset($seller_group['product_period']) ? $seller_group['product_period'] : '' ?>" size="5"/>
              </div>
          </div>

          <div class="form-group <?php if($seller_group['seller_group_id'] == $this->config->get('msconf_default_seller_group_id')) { ?>required<?php } ?>">
              <label class="col-sm-2 control-label"><?php echo $ms_product_quantity; ?></label>
              <div class="col-sm-10 control-inline">
                  <input class="form-control" type="text" name="seller_group[product_quantity]" value="<?php echo isset($seller_group['product_quantity']) ? $seller_group['product_quantity'] : '' ?>" size="5"/>
              </div>
          </div>
        </div>

		<div class="tab-pane" id="tab-commission">
		<input type="hidden" name="seller_group[commission_id]" value="<?php echo $seller_group['commission_id']; ?>" />

        <div class="form-group <?php if($seller_group['seller_group_id'] == $this->config->get('msconf_default_seller_group_id')) { ?>required<?php } ?>">
            <label class="col-sm-2 control-label"><?php echo $this->language->get('ms_commission_' . MsCommission::RATE_SALE); ?></label>
            <div class="col-sm-10 control-inline">
                <input type="hidden" name="seller_group[commission_rates][<?php echo MsCommission::RATE_SALE; ?>][rate_id]" value="<?php echo isset($seller_group['commission_rates'][MsCommission::RATE_SALE]['rate_id']) ? $seller_group['commission_rates'][MsCommission::RATE_SALE]['rate_id'] : ''; ?>" />
                <input type="hidden" name="seller_group[commission_rates][<?php echo MsCommission::RATE_SALE; ?>][rate_type]" value="<?php echo MsCommission::RATE_SALE; ?>" />
                <?php echo $this->currency->getSymbolLeft(); ?>
                <input type="text" class="form-control" name="seller_group[commission_rates][<?php echo MsCommission::RATE_SALE; ?>][flat]" value="<?php echo isset($seller_group['commission_rates'][MsCommission::RATE_SALE]['flat']) ? $this->currency->format($seller_group['commission_rates'][MsCommission::RATE_SALE]['flat'], $this->config->get('config_currency'), '', FALSE) : '' ?>" size="3"/>
                <?php echo $this->currency->getSymbolRight(); ?>
                +<input type="text" class="form-control" name="seller_group[commission_rates][<?php echo MsCommission::RATE_SALE; ?>][percent]" value="<?php echo isset($seller_group['commission_rates'][MsCommission::RATE_SALE]['percent']) ? $seller_group['commission_rates'][MsCommission::RATE_SALE]['percent'] : ''; ?>" size="3"/>%
            </div>
        </div>

         <div class="form-group <?php if($seller_group['seller_group_id'] == $this->config->get('msconf_default_seller_group_id')) { ?>required<?php } ?>">
            <label class="col-sm-2 control-label"><?php echo $this->language->get('ms_commission_' . MsCommission::RATE_LISTING); ?></label>
            <div class="col-sm-10 control-inline">
                <input type="hidden" name="seller_group[commission_rates][<?php echo MsCommission::RATE_LISTING; ?>][rate_id]" value="<?php echo isset($seller_group['commission_rates'][MsCommission::RATE_LISTING]['rate_id']) ? $seller_group['commission_rates'][MsCommission::RATE_LISTING]['rate_id'] : ''; ?>" />
                <input type="hidden" name="seller_group[commission_rates][<?php echo MsCommission::RATE_LISTING; ?>][rate_type]" value="<?php echo MsCommission::RATE_LISTING; ?>" />
                <?php echo $this->currency->getSymbolLeft(); ?>
                <input type="text" class="form-control" name="seller_group[commission_rates][<?php echo MsCommission::RATE_LISTING; ?>][flat]" value="<?php echo isset($seller_group['commission_rates'][MsCommission::RATE_LISTING]['flat']) ? $this->currency->format($seller_group['commission_rates'][MsCommission::RATE_LISTING]['flat'], $this->config->get('config_currency'), '', FALSE) : '' ?>" size="3"/>
                <?php echo $this->currency->getSymbolRight(); ?>
                +<input type="text" class="form-control" name="seller_group[commission_rates][<?php echo MsCommission::RATE_LISTING; ?>][percent]" value="<?php echo isset($seller_group['commission_rates'][MsCommission::RATE_LISTING]['percent']) ? $seller_group['commission_rates'][MsCommission::RATE_LISTING]['percent'] : ''; ?>" size="3"/>%
                <select class="form-control" name="seller_group[commission_rates][<?php echo MsCommission::RATE_LISTING; ?>][payment_method]">
                    <optgroup label="<?php echo $ms_payment_method; ?>">
                        <option value="0" <?php if(isset($seller_group['commission_rates'][MsCommission::RATE_LISTING]) && $seller_group['commission_rates'][MsCommission::RATE_LISTING]['payment_method'] == 0) { ?> selected="selected" <?php } ?>><?php echo $ms_payment_method_inherit; ?></option>
                        <option value="<?php echo MsPayment::METHOD_BALANCE; ?>" <?php if(isset($seller_group['commission_rates'][MsCommission::RATE_LISTING]) && $seller_group['commission_rates'][MsCommission::RATE_LISTING]['payment_method'] == MsPayment::METHOD_BALANCE) { ?> selected="selected" <?php } ?>><?php echo $ms_payment_method_balance; ?></option>
                        <option value="<?php echo MsPayment::METHOD_PAYPAL; ?>" <?php if(isset($seller_group['commission_rates'][MsCommission::RATE_LISTING]) && $seller_group['commission_rates'][MsCommission::RATE_LISTING]['payment_method'] == MsPayment::METHOD_PAYPAL) { ?> selected="selected" <?php } ?>><?php echo $ms_payment_method_paypal; ?></option>
                    </optgroup>
                </select>
            </div>
        </div>

        <div class="form-group <?php if($seller_group['seller_group_id'] == $this->config->get('msconf_default_seller_group_id')) { ?>required<?php } ?>">
            <label class="col-sm-2 control-label"><?php echo $this->language->get('ms_commission_' . MsCommission::RATE_SIGNUP); ?></label>
            <div class="col-sm-10 control-inline">
                <input type="hidden" name="seller_group[commission_rates][<?php echo MsCommission::RATE_SIGNUP; ?>][rate_id]" value="<?php echo isset($seller_group['commission_rates'][MsCommission::RATE_SIGNUP]['rate_id']) ? $seller_group['commission_rates'][MsCommission::RATE_SIGNUP]['rate_id'] : ''; ?>" />
                <input type="hidden" name="seller_group[commission_rates][<?php echo MsCommission::RATE_SIGNUP; ?>][rate_type]" value="<?php echo MsCommission::RATE_SIGNUP; ?>" />
                <?php echo $this->currency->getSymbolLeft(); ?>
                <input type="text" class="form-control" name="seller_group[commission_rates][<?php echo MsCommission::RATE_SIGNUP; ?>][flat]" value="<?php echo isset($seller_group['commission_rates'][MsCommission::RATE_SIGNUP]['flat']) ? $this->currency->format($seller_group['commission_rates'][MsCommission::RATE_SIGNUP]['flat'], $this->config->get('config_currency'), '', FALSE) : '' ?>" size="3"/>
                <?php echo $this->currency->getSymbolRight(); ?>
                +<input type="text" class="form-control" name="seller_group[commission_rates][<?php echo MsCommission::RATE_SIGNUP; ?>][percent]" value="<?php echo isset($seller_group['commission_rates'][MsCommission::RATE_SIGNUP]['percent']) ? $seller_group['commission_rates'][MsCommission::RATE_SIGNUP]['percent'] : ''; ?>" size="3"/>%
                <select class="form-control" name="seller_group[commission_rates][<?php echo MsCommission::RATE_SIGNUP; ?>][payment_method]">
                    <optgroup label="<?php echo $ms_payment_method; ?>">
                        <option value="0" <?php if(isset($seller_group['commission_rates'][MsCommission::RATE_SIGNUP]) && $seller_group['commission_rates'][MsCommission::RATE_SIGNUP]['payment_method'] == 0) { ?> selected="selected" <?php } ?>><?php echo $ms_payment_method_inherit; ?></option>
                        <option value="<?php echo MsPayment::METHOD_BALANCE; ?>" <?php if(isset($seller_group['commission_rates'][MsCommission::RATE_SIGNUP]) && $seller_group['commission_rates'][MsCommission::RATE_SIGNUP]['payment_method'] == MsPayment::METHOD_BALANCE) { ?> selected="selected" <?php } ?>><?php echo $ms_payment_method_balance; ?></option>
                        <option value="<?php echo MsPayment::METHOD_PAYPAL; ?>" <?php if(isset($seller_group['commission_rates'][MsCommission::RATE_SIGNUP]) && $seller_group['commission_rates'][MsCommission::RATE_SIGNUP]['payment_method'] == MsPayment::METHOD_PAYPAL) { ?> selected="selected" <?php } ?>><?php echo $ms_payment_method_paypal; ?></option>
                    </optgroup>
                </select>
            </div>
        </div>
		</div>
		<!--  end commission tab -->
        </div>
        </form>
      </div>
	</div>
  </div>
</div>

<script>
$("#ms-submit-button").click(function() {
	var id = $(this).attr('id');
    $.ajax({
		type: "POST",
		dataType: "json",
		url: 'index.php?route=multiseller/seller-group/jxSave&token=<?php echo $token; ?>',
		data: $('#form').serialize(),
        beforeSend: function() {
            $('div.text-danger').remove();
            $('.alert-danger').hide().find('i').text('');
        },
        complete: function(jqXHR, textStatus) {
            button.show().prev('span.wait').remove();
            $('.alert-danger').hide().find('i').text('');
        },
		success: function(jsonData) {
            if (!jQuery.isEmptyObject(jsonData.errors)) {
                for (error in jsonData.errors) {
                    $('[name="'+error+'"]').parents('.col-sm-10, td').append('<div class="text-danger">' + jsonData.errors[error] + '</div>');
                }
                window.scrollTo(0,0);
            } else {
                window.location = 'index.php?route=multiseller/seller-group&token=<?php echo $token; ?>';
            }
       	}
	});
});
</script>
<?php echo $footer; ?> 