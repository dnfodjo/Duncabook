<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="ms-settings">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button id="saveSettings" type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $ms_settings_heading; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($success)) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $ms_settings_heading; ?></h3>
      </div>
      <div class="panel-body">
        <form id="settings" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
                <ul class="nav nav-tabs" id="tabs">
			 		<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			 		<li><a href="#tab-productform" data-toggle="tab"><?php echo $ms_config_productform; ?></a></li>
			 		<li><a href="#tab-finances" data-toggle="tab"><?php echo $ms_config_finances; ?></a></li>
					<li><a href="#tab-miscellaneous" data-toggle="tab"><?php echo $ms_config_miscellaneous; ?></a></li>
			 	</ul>

                <div class="tab-content">
			 	<!-- BEGIN GENERAL TAB -->
			 	<div class="tab-pane active" id="tab-general">
					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_notification_email_note; ?>"><?php echo $ms_config_notification_email; ?></span></label>
						<div class="col-sm-10">
							<input class="form-control" size="20" type="text" name="msconf_notification_email" value="<?php echo $msconf_notification_email; ?>" />
						</div>
					</div>				
				
					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_seller_validation_note; ?>"><?php echo $ms_config_seller_validation; ?></span></label>
						<div class="col-sm-10">
						  	<select class="form-control" name="msconf_seller_validation">
						  	  <option value="1" <?php if($msconf_seller_validation == 1) { ?> selected="selected" <?php } ?>><?php echo $ms_config_seller_validation_none; ?></option>
							  <!--<option value="2" <?php if($msconf_seller_validation == 2) { ?> selected="selected" <?php } ?>><?php echo $ms_config_seller_validation_activation; ?></option>-->
							  <option value="3" <?php if($msconf_seller_validation == 3) { ?> selected="selected" <?php } ?>><?php echo $ms_config_seller_validation_approval; ?></option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_product_validation_note; ?>"><?php echo $ms_config_product_validation; ?></span></label>
						<div class="col-sm-10">
						  	<select class="form-control" name="msconf_product_validation">
							<option value="1" <?php if($msconf_product_validation == 1) { ?> selected="selected" <?php } ?>><?php echo $ms_config_product_validation_none; ?></option>
							<option value="2" <?php if($msconf_product_validation == 2) { ?> selected="selected" <?php } ?>><?php echo $ms_config_product_validation_approval; ?></option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allow_inactive_seller_products_note; ?>"><?php echo $ms_config_allow_inactive_seller_products; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_allow_inactive_seller_products" value="1" <?php if($msconf_allow_inactive_seller_products == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_allow_inactive_seller_products" value="0" <?php if($msconf_allow_inactive_seller_products == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?>
							</label>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_disable_product_after_quantity_depleted_note; ?>"><?php echo $ms_config_disable_product_after_quantity_depleted; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_disable_product_after_quantity_depleted" value="1" <?php if($msconf_disable_product_after_quantity_depleted == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?>
							</label>
							<label class="radio-inline"><input type="radio" name="msconf_disable_product_after_quantity_depleted" value="0" <?php if($msconf_disable_product_after_quantity_depleted == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?>
							</label>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allow_relisting_note; ?>"><?php echo $ms_config_allow_relisting; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_allow_relisting" value="1" <?php if($msconf_allow_relisting == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?>
							</label>
							<label class="radio-inline"><input type="radio" name="msconf_allow_relisting" value="0" <?php if($msconf_allow_relisting == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?>
							</label>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_banner_note; ?>"><?php echo $ms_config_enable_banner; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_seller_banner" value="1" <?php if($this->config->get('msconf_enable_seller_banner')) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?>
							</label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_seller_banner" value="0" <?php if(!$this->config->get('msconf_enable_seller_banner')) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?>
							</label>
					  	</div>
					</div>

					<!--
					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_one_page_seller_registration_note; ?>"><?php echo $ms_config_enable_one_page_seller_registration; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_one_page_seller_registration" value="1" <?php if($msconf_enable_one_page_seller_registration == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?>
							</label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_one_page_seller_registration" value="0" <?php if($msconf_enable_one_page_seller_registration == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?>
							</label>
						</div>
					</div>
					-->

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_seller_terms_page_note; ?>"><?php echo $ms_config_seller_terms_page; ?></span></label>
						<div class="col-sm-10">
							<select class="form-control" name="msconf_seller_terms_page">
								<option value="0"><?php echo $text_none; ?></option>
								<?php foreach ($informations as $information) { ?>
								<?php if ($information['information_id'] == $msconf_seller_terms_page) { ?>
								<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_graphical_sellermenu_note; ?>"><?php echo $ms_config_graphical_sellermenu; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_graphical_sellermenu" value="1" <?php if($msconf_graphical_sellermenu == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?>
							</label>
							<label class="radio-inline"><input type="radio" name="msconf_graphical_sellermenu" value="0" <?php if($msconf_graphical_sellermenu == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?>
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_rte_note; ?>"><?php echo $ms_config_enable_rte; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_rte" value="1" <?php if($msconf_enable_rte == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?>
							</label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_rte" value="0" <?php if($msconf_enable_rte == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?>
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_rte_whitelist_note; ?>"><?php echo $ms_config_rte_whitelist; ?></span></label>
						<div class="col-sm-10">
							<input class="form-control" type="text" name="msconf_rte_whitelist" value="<?php echo $msconf_rte_whitelist; ?>" />
						</div>
					</div>
				</div>
				<!-- END GENERAL TAB -->

			 	<!-- BEGIN PRODUCT FORM TAB -->
			 	<div class="tab-pane" id="tab-productform">
					<div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_minmax_product_price_note; ?>"><?php echo $ms_config_minmax_product_price; ?></span></label>
						<div class="col-sm-10 control-inline">
							<span><?php echo $ms_config_min; ?></span> <input class="form-control" type="text" name="msconf_minimum_product_price" value="<?php echo $msconf_minimum_product_price; ?>" size="4"/>
							<span><?php echo $ms_config_max; ?></span> <input class="form-control" type="text" name="msconf_maximum_product_price" value="<?php echo $msconf_maximum_product_price; ?>" size="4"/>
						</div>
					</div>

		   			<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allow_free_products_note; ?>"><?php echo $ms_config_allow_free_products; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_allow_free_products" value="1" <?php if($msconf_allow_free_products == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_allow_free_products" value="0" <?php if($msconf_allow_free_products == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>

		   			<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allow_specials_note; ?>"><?php echo $ms_config_allow_specials; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_allow_specials" value="1" <?php if($msconf_allow_specials == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_allow_specials" value="0" <?php if($msconf_allow_specials == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>

		   			<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allow_discounts_note; ?>"><?php echo $ms_config_allow_discounts; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_allow_discounts" value="1" <?php if($msconf_allow_discounts == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_allow_discounts" value="0" <?php if($msconf_allow_discounts == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allow_multiple_categories_note; ?>"><?php echo $ms_config_allow_multiple_categories; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_allow_multiple_categories" value="1" <?php if($msconf_allow_multiple_categories == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_allow_multiple_categories" value="0" <?php if($msconf_allow_multiple_categories == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_additional_category_restrictions_note; ?>"><?php echo $ms_config_additional_category_restrictions; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_additional_category_restrictions" value="0" <?php if($msconf_additional_category_restrictions == 0) { ?> checked="checked" <?php } ?>  /><?php echo $ms_none; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_additional_category_restrictions" value="1" <?php if($msconf_additional_category_restrictions == 1) { ?> checked="checked" <?php } ?>  /><?php echo $ms_topmost_categories; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_additional_category_restrictions" value="2" <?php if($msconf_additional_category_restrictions == 2) { ?> checked="checked" <?php } ?>  /><?php echo $ms_parent_categories; ?></label>
					  	</div>
					</div>

		   			<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_restrict_categories_note; ?>"><?php echo $ms_config_restrict_categories; ?></span></label>
						<div class="col-sm-10">
                          <div id="product-category" class="well well-sm" style="height: 150px; overflow: auto;">
                            <?php foreach ($categories as $category) { ?>
                              <input type="checkbox" name="msconf_restrict_categories[]" value="<?php echo $category['category_id']; ?>" <?php if (isset($msconf_restrict_categories) && in_array($category['category_id'], $msconf_restrict_categories)) { ?>checked="checked"<?php } ?> /> <?php echo $category['name']; ?><br>
                            <?php } ?>
                          </div>
					  	</div>
					</div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_product_included_fields_note; ?>"><?php echo $ms_config_product_included_fields; ?></span></label>
                        <div class="col-sm-10">
                          <div class="well well-sm" style="height: 150px; overflow: auto;">
                            <?php foreach ($product_included_fieds as $field_code=>$field_name) { ?>
                              <input type="checkbox" name="msconf_product_included_fields[]" value="<?php echo $field_code; ?>" <?php if (isset($msconf_product_included_fields) && in_array($field_code, $msconf_product_included_fields)) { ?>checked="checked"<?php } ?> /> <?php echo $field_name; ?><br>
                            <?php } ?>
                          </div>
                        </div>
                    </div>

					<div class="form-group">
						  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allowed_image_types_note; ?>"><?php echo $ms_config_allowed_image_types; ?></span></label>
						  <div class="col-sm-10">
						  	<input class="form-control" type="text" name="msconf_allowed_image_types" value="<?php echo $msconf_allowed_image_types; ?>" />
						  </div>
					</div>

					<div class="form-group">
						  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allowed_download_types_note; ?>"><?php echo $ms_config_allowed_download_types; ?></span></label>
						  <div class="col-sm-10">
						  	<input class="form-control" type="text" name="msconf_allowed_download_types" value="<?php echo $msconf_allowed_download_types; ?>" />
						  </div>
					</div>

					<div class="form-group">
						  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_images_limits_note; ?>"><?php echo $ms_config_images_limits; ?></span></label>
						  <div class="col-sm-10 control-inline">
						    <span><?php echo $ms_config_min; ?></span> <input class="form-control" type="text" name="msconf_images_limits[]" value="<?php echo $msconf_images_limits[0]; ?>" size="3" />
						    <span><?php echo $ms_config_max; ?></span> <input class="form-control" type="text" name="msconf_images_limits[]" value="<?php echo $msconf_images_limits[1]; ?>" size="3" />
						  </div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_downloads_limits_note; ?>"><?php echo $ms_config_downloads_limits; ?></span></label>
						<div class="col-sm-10 control-inline">
						    <span><?php echo $ms_config_min; ?></span> <input class="form-control" type="text" name="msconf_downloads_limits[]" value="<?php echo $msconf_downloads_limits[0]; ?>" size="3" />
                            <span><?php echo $ms_config_max; ?></span> <input class="form-control" type="text" name="msconf_downloads_limits[]" value="<?php echo $msconf_downloads_limits[1]; ?>" size="3" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_shipping_note; ?>"><?php echo $ms_config_enable_shipping; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_shipping" value="1" <?php if($msconf_enable_shipping == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_shipping" value="0" <?php if($msconf_enable_shipping == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_shipping" value="2" <?php if($msconf_enable_shipping == 2) { ?> checked="checked" <?php } ?>  /><?php echo $text_seller_select; ?></label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_quantities_note; ?>"><?php echo $ms_config_enable_quantities; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_quantities" value="1" <?php if($msconf_enable_quantities == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_quantities" value="0" <?php if($msconf_enable_quantities == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_quantities" value="2" <?php if($msconf_enable_quantities == 2) { ?> checked="checked" <?php } ?>  /><?php echo $text_shipping_dependent; ?></label>
					  	</div>
					</div>

					<!--
					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_provide_buyerinfo_note; ?>"><?php echo $ms_config_provide_buyerinfo; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_provide_buyerinfo" value="1" <?php if($msconf_provide_buyerinfo == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_provide_buyerinfo" value="0" <?php if($msconf_provide_buyerinfo == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_provide_buyerinfo" value="2" <?php if($msconf_provide_buyerinfo == 2) { ?> checked="checked" <?php } ?>  /><?php echo $text_shipping_dependent; ?></label>
					  	</div>
					</div>
					-->
				</div>
				<!-- END PRODUCT FORM TAB -->

			 	<!-- BEGIN FINANCES TAB -->
			 	<div class="tab-pane" id="tab-finances">
					<div class="form-group">
						  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_credit_order_statuses_note; ?>"><?php echo $ms_config_credit_order_statuses; ?></span></label>
						  <div class="col-sm-10">
                              <div class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($order_statuses as $status) { ?>
                                  <input type="checkbox" name="msconf_credit_order_statuses[]" value="<?php echo $status['order_status_id']; ?>" <?php if (in_array($status['order_status_id'], $msconf_credit_order_statuses)) { ?>checked="checked"<?php } ?> /> <?php echo $status['name']; ?><br>
                                <?php } ?>
                              </div>
                          </div>
					</div>

					<div class="form-group">
						  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_debit_order_statuses_note; ?>"><?php echo $ms_config_debit_order_statuses; ?></span></label>
						  <div class="col-sm-10">
                              <div class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($order_statuses as $status) { ?>
                                  <input type="checkbox" name="msconf_debit_order_statuses[]" value="<?php echo $status['order_status_id']; ?>" <?php if (in_array($status['order_status_id'], $msconf_debit_order_statuses)) { ?>checked="checked"<?php } ?> /> <?php echo $status['name']; ?><br>
                                <?php } ?>
                              </div>
                          </div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allow_withdrawal_requests_note; ?>"><?php echo $ms_config_allow_withdrawal_requests; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_allow_withdrawal_requests" value="1" <?php if($msconf_allow_withdrawal_requests == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_allow_withdrawal_requests" value="0" <?php if($msconf_allow_withdrawal_requests == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>

					<div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_withdrawal_waiting_period_note; ?>"><?php echo $ms_config_withdrawal_waiting_period; ?></span></label>
						<div class="col-sm-10 control-inline">
							<input class="form-control" type="text" size="3" name="msconf_withdrawal_waiting_period" value="<?php echo $msconf_withdrawal_waiting_period; ?>" /><?php echo $ms_days; ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_minimum_withdrawal_note; ?>"><?php echo $ms_config_minimum_withdrawal; ?></span></label>
						<div class="col-sm-10 control-inline">
							<input class="form-control" type="text" name="msconf_minimum_withdrawal_amount" value="<?php echo $msconf_minimum_withdrawal_amount; ?>" size="3"/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_allow_partial_withdrawal_note; ?>"><?php echo $ms_config_allow_partial_withdrawal; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_allow_partial_withdrawal" value="1" <?php if($msconf_allow_partial_withdrawal == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_allow_partial_withdrawal" value="0" <?php if($msconf_allow_partial_withdrawal == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_paypal_address_note; ?>"><?php echo $ms_config_paypal_address; ?></span></label>
						<div class="col-sm-10">
							<input class="form-control" type="text" name="msconf_paypal_address" value="<?php echo $msconf_paypal_address; ?>" size="30"/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_paypal_sandbox_note; ?>"><?php echo $ms_config_paypal_sandbox; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_paypal_sandbox" value="1" <?php if($msconf_paypal_sandbox == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_paypal_sandbox" value="0" <?php if($msconf_paypal_sandbox == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>
				</div>

				<!-- BEGIN MISCELLANEOUS TAB -->
			 	<div class="tab-pane" id="tab-miscellaneous">
                    <fieldset>
                    <legend><?php echo $ms_config_image_sizes; ?></legend>

					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $ms_config_seller_avatar_image_size; ?></label>
						<div class="col-sm-10 control-inline control-label">
                            <div class="row">
							<span class="col-sm-2"><?php echo $ms_config_seller_avatar_image_size_seller_profile; ?></span>
                            <span class="col-sm-3"><input class="form-control" type="text" name="msconf_seller_avatar_seller_profile_image_width" value="<?php echo $msconf_seller_avatar_seller_profile_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_seller_avatar_seller_profile_image_height" value="<?php echo $msconf_seller_avatar_seller_profile_image_height; ?>" size="3" /></span>
                            </div>

                            <div class="row">
							<span class="col-sm-2"><?php echo $ms_config_seller_avatar_image_size_seller_list; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_seller_avatar_seller_list_image_width" value="<?php echo $msconf_seller_avatar_seller_list_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_seller_avatar_seller_list_image_height" value="<?php echo $msconf_seller_avatar_seller_list_image_height; ?>" size="3" />							</span>
                            </div>

                            <div class="row">
							<span class="col-sm-2"><?php echo $ms_config_seller_avatar_image_size_product_page; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_seller_avatar_product_page_image_width" value="<?php echo $msconf_seller_avatar_product_page_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_seller_avatar_product_page_image_height" value="<?php echo $msconf_seller_avatar_product_page_image_height; ?>" size="3" /></span>
                            </div>

                            <div class="row">
							<span class="col-sm-2"><?php echo $ms_config_seller_avatar_image_size_seller_dashboard; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_seller_avatar_dashboard_image_width" value="<?php echo $msconf_seller_avatar_dashboard_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_seller_avatar_dashboard_image_height" value="<?php echo $msconf_seller_avatar_dashboard_image_height; ?>" size="3" /></span>
                            </div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $ms_config_seller_banner_size; ?></label>
						<div class="col-sm-10 control-inline control-label">
                            <div class="row">
							<span class="col-sm-2"></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_product_seller_banner_width" value="<?php echo $msconf_product_seller_banner_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_product_seller_banner_height" value="<?php echo $msconf_product_seller_banner_height; ?>" size="3" /></span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $ms_config_image_preview_size; ?></label>
						<div class="col-sm-10 control-inline control-label">
                            <div class="row">
							<span class="col-sm-2"><?php echo $ms_config_image_preview_size_seller_avatar; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_preview_seller_avatar_image_width" value="<?php echo $msconf_preview_seller_avatar_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_preview_seller_avatar_image_height" value="<?php echo $msconf_preview_seller_avatar_image_height; ?>" size="3" /></span>
							</div>

                            <div class="row">
							<span class="col-sm-2"><?php echo $ms_config_image_preview_size_product_image; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_preview_product_image_width" value="<?php echo $msconf_preview_product_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_preview_product_image_height" value="<?php echo $msconf_preview_product_image_height; ?>" size="3" /></span>
                            </div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $ms_config_product_image_size; ?></label>
						<div class="col-sm-10 control-inline">
                            <div class="row">
							<span class="col-sm-2"><?php echo $ms_config_product_image_size_seller_profile; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_product_seller_profile_image_width" value="<?php echo $msconf_product_seller_profile_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_product_seller_profile_image_height" value="<?php echo $msconf_product_seller_profile_image_height; ?>" size="3" /></span>
							</div>

                            <div class="row">
							<span class="col-sm-2"><?php echo $ms_config_product_image_size_seller_products_list; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_product_seller_products_image_width" value="<?php echo $msconf_product_seller_products_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_product_seller_products_image_height" value="<?php echo $msconf_product_seller_products_image_height; ?>" size="3" /></span>
							</div>

                            <div class="row">
							<span class="col-sm-2"><?php echo $ms_config_product_image_size_seller_products_list_account; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_product_seller_product_list_seller_area_image_width" value="<?php echo $msconf_product_seller_product_list_seller_area_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_product_seller_product_list_seller_area_image_height" value="<?php echo $msconf_product_seller_product_list_seller_area_image_height; ?>" size="3" /></span>
                            </div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_uploaded_image_size_note; ?>"><?php echo $ms_config_uploaded_image_size; ?></span></label>
						<div class="col-sm-10 control-inline">
                            <div class="row">
                            <span class="col-sm-2"><?php echo $ms_config_min; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_min_uploaded_image_width" value="<?php echo $msconf_min_uploaded_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_min_uploaded_image_height" value="<?php echo $msconf_min_uploaded_image_height; ?>" size="3" /></span>
                            </div>

                            <div class="row">
                            <span class="col-sm-2"><?php echo $ms_config_max; ?></span>
							<span class="col-sm-3"><input class="form-control" type="text" name="msconf_max_uploaded_image_width" value="<?php echo $msconf_max_uploaded_image_width; ?>" size="3" /> x <input class="form-control" type="text" name="msconf_max_uploaded_image_height" value="<?php echo $msconf_max_uploaded_image_height; ?>" size="3" /></span>
                            </div>
						</div>
					</div>
                    </fieldset>

                    <fieldset>
                    <legend><?php echo $ms_config_seo; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_seo_urls_seller_note; ?>"><?php echo $ms_config_enable_seo_urls_seller; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_seo_urls_seller" value="1" <?php if($msconf_enable_seo_urls_seller == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_seo_urls_seller" value="0" <?php if($msconf_enable_seo_urls_seller == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_seo_urls_product_note; ?>"><?php echo $ms_config_enable_seo_urls_product; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_seo_urls_product" value="1" <?php if($msconf_enable_seo_urls_product == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_seo_urls_product" value="0" <?php if($msconf_enable_seo_urls_product == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>

					<!--<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php //echo $ms_config_enable_update_seo_urls_note; ?>"><?php //echo $ms_config_enable_update_seo_urls; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_update_seo_urls" value="1" <?php //if($msconf_enable_update_seo_urls == 1) { ?> checked="checked" <?php //} ?>  />
							<?php //echo $text_yes; ?>
							<input type="radio" name="msconf_enable_update_seo_urls" value="0" <?php //if($msconf_enable_update_seo_urls == 0) { ?> checked="checked" <?php //} ?>  />
							<?php //echo $text_no; ?>
					  	</div>
					</div>-->

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_non_alphanumeric_seo_note; ?>"><?php echo $ms_config_enable_non_alphanumeric_seo; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_non_alphanumeric_seo" value="1" <?php if($msconf_enable_non_alphanumeric_seo == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_non_alphanumeric_seo" value="0" <?php if($msconf_enable_non_alphanumeric_seo == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_sellers_slug_note; ?>"><?php echo $ms_config_sellers_slug; ?></span></label>
						<div class="col-sm-10">
							<input class="form-control" type="text" name="msconf_sellers_slug" value="<?php echo isset($msconf_sellers_slug) ? $msconf_sellers_slug : 'sellers' ; ?>" />
						</div>
					</div>
                    </fieldset>

                    <fieldset>
                    <legend><?php echo $ms_config_attributes; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_attribute_display_note; ?>"><?php echo $ms_config_attribute_display; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_attribute_display" value="0" <?php if($msconf_attribute_display == 0) { ?> checked="checked" <?php } ?>  /><?php echo $ms_config_attribute_display_mm; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_attribute_display" value="1" <?php if($msconf_attribute_display == 1) { ?> checked="checked" <?php } ?>  /><?php echo $ms_config_attribute_display_oc; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_attribute_display" value="2" <?php if($msconf_attribute_display == 2) { ?> checked="checked" <?php } ?>  /><?php echo $ms_config_attribute_display_both; ?></label>
						</div>
					</div>
                    </fieldset>

                    <fieldset>
                    <legend><?php echo $ms_config_privacy; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_enable_private_messaging_note; ?>"><?php echo $ms_config_enable_private_messaging; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_enable_private_messaging" value="2" <?php if($msconf_enable_private_messaging == 2) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_enable_private_messaging" value="0" <?php if($msconf_enable_private_messaging == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_hide_customer_email_note; ?>"><?php echo $ms_config_hide_customer_email; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_hide_customer_email" value="1" <?php if($msconf_hide_customer_email == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_hide_customer_email" value="0" <?php if($msconf_hide_customer_email == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_hide_email_in_email_note; ?>"><?php echo $ms_config_hide_email_in_email; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_hide_emails_in_emails" value="1" <?php if($msconf_hide_emails_in_emails == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_hide_emails_in_emails" value="0" <?php if($msconf_hide_emails_in_emails == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
						</div>
					</div>
                    </fieldset>

                    <fieldset>
                    <legend><?php echo $ms_config_seller; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_seller_change_nickname_note; ?>"><?php echo $ms_config_seller_change_nickname; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_change_seller_nickname" value="1" <?php if ($msconf_change_seller_nickname == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_change_seller_nickname" value="0" <?php if ($msconf_change_seller_nickname == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_nickname_rules_note; ?>"><?php echo $ms_config_nickname_rules; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_nickname_rules" value="0" <?php if ($msconf_nickname_rules == 0) { ?> checked="checked" <?php } ?>  /><?php echo $ms_config_nickname_rules_alnum; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_nickname_rules" value="1" <?php if ($msconf_nickname_rules == 1) { ?> checked="checked" <?php } ?>  /><?php echo $ms_config_nickname_rules_ext; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_nickname_rules" value="2" <?php if ($msconf_nickname_rules == 2) { ?> checked="checked" <?php } ?>  /><?php echo $ms_config_nickname_rules_utf; ?></label>
					  	</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_avatars_for_sellers_note; ?>"><?php echo $ms_config_avatars_for_sellers; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_avatars_for_sellers" value="0" <?php if ($msconf_avatars_for_sellers == 0) { ?> checked="checked" <?php } ?>  /><?php echo $ms_config_avatars_manually; ?></label>
						</div>
					</div>
                    </fieldset>

                    <fieldset>
                    <legend><?php echo $ms_config_other; ?></legend>
					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_hide_sellers_product_count_note; ?>"><?php echo $ms_config_hide_sellers_product_count; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline"><input type="radio" name="msconf_hide_sellers_product_count" value="1" <?php if($msconf_hide_sellers_product_count == 1) { ?> checked="checked" <?php } ?>  /><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="msconf_hide_sellers_product_count" value="0" <?php if($msconf_hide_sellers_product_count == 0) { ?> checked="checked" <?php } ?>  /><?php echo $text_no; ?></label>
						</div>
					</div>

					<div class="form-group">
						  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $ms_config_display_order_statuses_note; ?>"><?php echo $ms_config_display_order_statuses; ?></span></label>
						  <div class="col-sm-10">
                              <div class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($order_statuses as $status) { ?>
                                  <input type="checkbox" name="msconf_display_order_statuses[]" value="<?php echo $status['order_status_id']; ?>" <?php if (in_array($status['order_status_id'], $msconf_display_order_statuses)) { ?>checked="checked"<?php } ?> /> <?php echo $status['name']; ?><br>
                                <?php } ?>
                              </div>
                          </div>
					</div>
				    </fieldset>
				</div>
				<!-- END MISCELLANEOUS TAB -->
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
			url: 'index.php?route=module/multiseller/savesettings&token=<?php echo $token; ?>',
			data: $('#settings').serialize(),
			success: function(jsonData) {
				if (jsonData.errors) {
					$("#error").html('');
					for (error in jsonData.errors) {
						if (!jsonData.errors.hasOwnProperty(error)) {
							continue;
						}
						$("#error").append('<p>'+jsonData.errors[error]+'</p>');
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
</div>