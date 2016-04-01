<?php echo $header; ?>
<div class="container ms-account-product-form" xmlns="http://www.w3.org/1999/html">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="alert alert-danger warning main" style="display: none"><i class="fa fa-exclamation-circle"></i></div>

  <?php if (isset($success) && ($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?></div>
  <?php } ?>

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
    <h1><?php echo $heading; ?></h1>

	<form id="ms-new-product" class="form-horizontal" method="post" enctype="multipart/form-data">
		<input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
		<input type="hidden" name="action" id="ms_action" />
		<input type="hidden" name="list_until" value="<?php echo isset($list_until) ? $list_until : '' ?>" />

		<ul id="general-tabs" class="nav nav-tabs">
			<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $ms_account_product_tab_general; ?></a></li>

				<?php if ($this->config->get('msconf_enable_shipping')) { ?>
				    <li id="shipping_tab"><a href="#tab-shipping" data-toggle="tab"><?php echo $ms_account_product_tab_shipping; ?></a></li>
				<?php } ?>
			

			<?php
			$data_tab_fields = array('model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'manufacturer', 'taxClass', 'subtract', 'stockStatus', 'dateAvailable', 'filters');
			$intersection_fields = array_intersect($data_tab_fields, $this->config->get('msconf_product_included_fields'));
     		if (!empty($intersection_fields)) { ?>
     		<?php } ?>


			<?php if ($this->config->get('msconf_allow_specials')) { ?>
     		<li><a href="#tab-specials" data-toggle="tab"><?php echo $ms_account_product_tab_specials; ?></a></li>
     		<?php } ?>

     		<?php if ($this->config->get('msconf_allow_discounts')) { ?>
     		<li><a href="#tab-discounts" data-toggle="tab"><?php echo $ms_account_product_tab_discounts; ?></a></li>
     		<?php } ?>
		</ul>

     	<!-- general tab -->
		<div class="tab-content ms-product">
     	<div id="tab-general" class="tab-pane active">
     		<?php if (count($languages) > 1) { ?>
			<?php $first = key($languages); ?>
			<ul class="nav nav-tabs" id="language-tabs">
				<?php foreach ($languages as $k => $language) { ?>
				<li <?php if ($k == $first) { ?> class="active" <?php } ?>><a data-toggle="tab" href="#language<?php echo $language['language_id']; ?>"><img src="image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
				<?php } ?>
			</ul>
			<?php } ?>

			<div class="tab-content">
			<?php
			reset($languages); $first = key($languages);
			foreach ($languages as $k => $language) {
				$langId = $language['language_id'];
				?>
				
				<div class="ms-language-div tab-pane <?php if ($k == $first) { echo 'active'; } ?>" id="language<?php echo $langId; ?>">
                    <fieldset>
                    <legend><?php echo $ms_account_product_name_description; ?></legend>
					<div class="form-group <?php if ($k == $first) { echo 'required'; } ?>">
						<label class="col-sm-2 control-label"><?php echo $ms_account_product_name; ?></label>
						<div class="col-sm-10">
							<input type="text" maxlength="100" class="form-control" name="languages[<?php echo $langId; ?>][product_name]" value="<?php echo $product['languages'][$langId]['name']; ?>" />
							<p class="ms-note"><?php echo $ms_account_product_name_note; ?></p>
							<p class="error" id="error_product_name_<?php echo $langId; ?>"></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $ms_account_product_description; ?></label>
						<div class="col-sm-10">
							<!-- todo strip tags if rte disabled -->
							<textarea name="languages[<?php echo $langId; ?>][product_description]" class="form-control" value="<?php echo $product['languages'][$langId]['description']; ?>"></textarea>
							<p class="ms-note"><?php echo $ms_account_product_description_note; ?></p>
							<p class="error" id="error_product_description_<?php echo $langId; ?>"></p>
						</div>
					</div>

                    <?php if (in_array('metaDescription', $this->config->get('msconf_product_included_fields'))) { ?>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $ms_account_product_meta_description; ?></label>
						<div class="col-sm-10">
							<!-- todo strip tags if rte disabled -->
							<textarea class="form-control"  name="languages[<?php echo $langId; ?>][product_meta_description]"><?php echo strip_tags(htmlspecialchars_decode($product['languages'][$langId]['meta_description'])); ?></textarea>
							<p class="ms-note"><?php echo $ms_account_product_meta_description_note; ?></p>
							<p class="error" id="error_product_meta_description_<?php echo $langId; ?>"></p>
						</div>
					</div>
                    <?php } ?>

                    <?php if (in_array('metaKeywords', $this->config->get('msconf_product_included_fields'))) { ?>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $ms_account_product_meta_keyword; ?></label>
						<div class="col-sm-10">
							<!-- todo strip tags if rte disabled -->
							<textarea class="form-control"  name="languages[<?php echo $langId; ?>][product_meta_keyword]"><?php echo strip_tags(htmlspecialchars_decode($product['languages'][$langId]['meta_keyword'])); ?></textarea>
							<p class="ms-note"><?php echo $ms_account_product_meta_keyword_note; ?></p>
							<p class="error" id="error_product_meta_keyword_<?php echo $langId; ?>"></p>
						</div>
					</div>
                    <?php } ?>

					
					<?php if (in_array('model', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_model; ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="product_model" value="<?php echo $product['model']; ?>" />
					<p class="error" id="error_product_model"></p>
				</div>
			</div>
			<?php } ?>
			<?php if (in_array('sku', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_sku; ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="product_sku" value="<?php echo $product['sku']; ?>" />
					<p class="ms-note"><?php echo $ms_account_product_sku_note; ?></p>
				</div>
			</div>
			<?php } ?>
			<?php if (in_array('upc', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_upc; ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="product_upc" value="<?php echo $product['upc']; ?>" />
					<p class="ms-note"><?php echo $ms_account_product_upc_note; ?></p>
				</div>
			</div>
			<?php } ?>
			<?php if (in_array('ean', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_ean; ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="product_ean" value="<?php echo $product['ean']; ?>" />
					<p class="ms-note"><?php echo $ms_account_product_ean_note; ?></p>
				</div>
			</div>
			<?php } ?>
			
			<?php if (in_array('jan', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_jan; ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="product_jan" value="<?php echo $product['jan']; ?>" />
					<p class="ms-note"><?php echo $ms_account_product_jan_note; ?></p>
				</div>
			</div>
			<?php } ?>
			<?php if (in_array('isbn', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_isbn; ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="product_isbn" value="<?php echo $product['isbn']; ?>" />
					<p class="ms-note"><?php echo $ms_account_product_isbn_note; ?></p>
				</div>
			</div>
			<?php } ?>
			
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_tags; ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="languages[<?php echo $langId; ?>][product_tags]" value="<?php echo $product['languages'][$langId]['tags']; ?>" />
					<p class="ms-note"><?php echo $ms_account_product_tags_note; ?></p>
					<p class="error" id="error_product_tags_<?php echo $langId; ?>"></p>
				</div>
			</div>
			
			<?php if (in_array('mpn', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_mpn; ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="product_mpn" value="<?php echo $product['mpn']; ?>" />
					<p class="ms-note"><?php echo $ms_account_product_mpn_note; ?></p>
				</div>
			</div>
			<?php } ?>
			<?php if (in_array('manufacturer', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_manufacturer; ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="product_manufacturer" value="<?php echo $product['manufacturer'] ?>" />
					<input type="hidden" name="product_manufacturer_id" value="<?php echo $product['manufacturer_id']; ?>" />
					<p class="ms-note"><?php echo $ms_account_product_manufacturer_note; ?></p>
				</div>
			</div>
			<?php } ?>
			<?php if (in_array('taxClass', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_tax_class; ?></label>
				<div class="col-sm-10">
					<select class="form-control" name="product_tax_class_id">
						<option value="0"><?php echo $text_none; ?></option>
						<?php foreach ($tax_classes as $tax_class) { ?>
						<?php if ($tax_class['tax_class_id'] == $product['tax_class_id']) { ?>
						<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php } ?>
			<?php if (in_array('subtract', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_subtract; ?></label>
				<div class="col-sm-10">
					<select class="form-control" name="product_subtract">
						<?php if ($product['subtract']) { ?>
						<option value="1" selected="selected"><?php echo $text_yes; ?></option>
						<option value="0"><?php echo $text_no; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_yes; ?></option>
						<option value="0" selected="selected"><?php echo $text_no; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php } ?>
			<?php if (in_array('stockStatus', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_stock_status; ?></label>
				<div class="col-sm-10">
					<select class="form-control" name="product_stock_status_id">
						<?php foreach ($stock_statuses as $stock_status) { ?>
						<?php if ($stock_status['stock_status_id'] == $product['stock_status_id']) { ?>
						<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php } ?>
			<?php if (in_array('dateAvailable', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group required">
				<label class="col-sm-2 control-label"><?php echo $ms_account_product_date_available; ?></label>
				<div class="col-sm-10"><input type="text" class="form-control" name="product_date_available" value="<?php echo $date_available; ?>" size="12" class="date" /></div>
			</div>
			<?php } ?>

			<?php if (in_array('filters', $this->config->get('msconf_product_included_fields'))) { ?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-filter"><span data-toggle="tooltip"><?php echo $this->language->get('ms_entry_filter'); ?></span></label>
				<div class="col-sm-10">
					<input type="text" name="filter" value="" placeholder="<?php echo $this->language->get('ms_autocomplete'); ?>" id="input-filter" class="form-control" />
					<div id="product-filter" class="well well-sm" style="height: 150px; overflow: auto;">
					<?php if ($product_filters) foreach ($product_filters as $product_filter) { ?>
						<div id="product-filter<?php echo $product_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i>
							<?php echo $product_filter['name']; ?><input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
			<?php } ?>
					

					<?php if (isset($multilang_attributes) && !empty($multilang_attributes)) { ?>
					<?php foreach ($multilang_attributes as &$attr) { ?>
					<div class="form-group <?php if ($attr['required'] && $k == $first) { echo 'required'; } ?>">
						<label class="col-sm-2 control-label"><?php echo $attr['mad.name']; ?></label>
						<div class="col-sm-10">
							<?php if ($attr['attribute_type'] == MsAttribute::TYPE_TEXT) { ?>
								<input type="text" class="form-control" name="languages[<?php echo $langId; ?>][product_attributes][<?php echo $attr['attribute_id']; ?>][value]" value="<?php echo isset($multilang_attribute_values[$attr['attribute_id']][$langId]) ? $multilang_attribute_values[$attr['attribute_id']][$langId]['value'] : '' ?>" />
								<input type="hidden" name="languages[<?php echo $langId; ?>][product_attributes][<?php echo $attr['attribute_id']; ?>][value_id]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo $multilang_attribute_values[$attr['attribute_id']][$langId]['value_id']; } ?>" />
							<?php } ?>

							<?php if ($attr['attribute_type'] == MsAttribute::TYPE_TEXTAREA) { ?>
								<textarea class="form-control"  name="languages[<?php echo $langId; ?>][product_attributes][<?php echo $attr['attribute_id']; ?>][value]"><?php echo isset($multilang_attribute_values[$attr['attribute_id']][$langId]) ? $multilang_attribute_values[$attr['attribute_id']][$langId]['value'] : '' ?></textarea>
								<input type="hidden" name="languages[<?php echo $langId; ?>][product_attributes][<?php echo $attr['attribute_id']; ?>][value_id]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo $multilang_attribute_values[$attr['attribute_id']][$langId]['value_id']; } ?>" />
							<?php } ?>
							<p class="ms-note"><?php echo $attr['description']; ?></p>
							<p class="error"></p>
						</div>
					</div>
					<?php } ?>
					<?php } ?>
					</fieldset>
				</div>
			<?php } ?>
			</div>

			<fieldset>
            	<legend><?php echo $ms_account_product_price_attributes; ?></legend>

				<div class="form-group required">
					<label class="col-sm-2 control-label"><?php echo $ms_account_product_price; ?></label>
					<div class="col-sm-10">
						<span class="vertical-align: auto"><?php echo $this->currency->getSymbolLeft($this->config->get('config_currency')); ?></span>
						<input type="text" class="form-control inline" name="product_price" value="<?php echo $product['price']; ?>" <?php if (isset($seller['commissions']) && $seller['commissions'][MsCommission::RATE_LISTING]['percent'] > 0) { ?>class="ms-price-dynamic"<?php } ?> />
						<span class="vertical-align: auto"><?php echo $this->currency->getSymbolRight($this->config->get('config_currency')); ?></span>
						<p class="ms-note"><?php echo $ms_account_product_price_note; ?></p>
						<p class="error" id="error_product_price"></p>
					</div>
				</div>

				<div class="form-group required">
					<label class="col-sm-2 control-label"><?php echo $ms_account_product_category; ?></label>
					<div class="col-sm-10" id="product_category_block">
						<?php if (!$msconf_allow_multiple_categories) { ?>

						<select class="form-control" name="product_category">
							<option selected="selected" value="books"><?php echo 'Books'; ?></option>
							<?php foreach ($categories as $category) { ?>
                                <?php if($msconf_enable_categories && $this->config->get('msconf_enable_shipping') == 2) { ?>
                                    <?php if($product['shipping'] == 1 || $product['shipping'] == NULL) { ?>
                                        <?php if(in_array($category['category_id'],$msship_physical_product_categories)) { ?>
                                            <option value="<?php echo $category['category_id']; ?>" <?php if (in_array($category['category_id'], explode(',',$product['category_id'])) && !$category['disabled']) { ?>selected="selected"<?php } ?> <?php echo ($category['disabled'] ? 'disabled' : ''); ?>><?php echo $category['name']; ?></option>
                                    <?php }} else { ?>
                                        <?php if(in_array($category['category_id'],$msship_digital_product_categories)) { ?>
                                            <option value="<?php echo $category['category_id']; ?>" <?php if (in_array($category['category_id'], explode(',',$product['category_id'])) && !$category['disabled']) { ?>selected="selected"<?php } ?> <?php echo ($category['disabled'] ? 'disabled' : ''); ?>><?php echo $category['name']; ?></option>
                                    <?php }} ?>
                                <?php } else { ?>
                                <option value="<?php echo $category['category_id']; ?>" <?php if (in_array($category['category_id'], explode(',',$product['category_id'])) && !$category['disabled']) { ?>selected="selected"<?php } ?> <?php echo ($category['disabled'] ? 'disabled' : ''); ?>><?php echo $category['name']; ?></option>
							<?php }} ?>
						</select>

						<?php } else { ?>

						<div class="scrollbox">
						<?php $class = 'odd'; ?>
						<?php foreach ($categories as $category) { ?>
                            <?php if($msconf_enable_categories && $this->config->get('msconf_enable_shipping') == 2) { ?>
                                <?php if($product['shipping'] == 1 || $product['shipping'] == NULL) { ?>
                                    <?php if(in_array($category['category_id'],$msship_physical_product_categories)) { ?>
                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                        <div class="<?php echo $class; ?> <?php echo ($category['disabled'] ? 'disabled' : ''); ?>">
                                            <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" <?php if (in_array($category['category_id'], explode(',',$product['category_id'])) && !$category['disabled']) { ?>checked="checked"<?php } ?> <?php if ($category['disabled']) { ?>disabled="disabled"<?php } ?>/>
                                            <?php echo $category['name']; ?>
                                        </div>
                                <?php }} else { ?>
                                     <?php if(in_array($category['category_id'],$msship_digital_product_categories)) { ?>
                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                        <div class="<?php echo $class; ?> <?php echo ($category['disabled'] ? 'disabled' : ''); ?>">
                                            <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" <?php if (in_array($category['category_id'], explode(',',$product['category_id'])) && !$category['disabled']) { ?>checked="checked"<?php } ?> <?php if ($category['disabled']) { ?>disabled="disabled"<?php } ?>/>
                                            <?php echo $category['name']; ?>
                                        </div>
                                <?php }} ?>
                            <?php } else { ?>
                                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                <div class="<?php echo $class; ?> <?php echo ($category['disabled'] ? 'disabled' : ''); ?>">
                                    <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" <?php if (in_array($category['category_id'], explode(',',$product['category_id'])) && !$category['disabled']) { ?>checked="checked"<?php } ?> <?php if ($category['disabled']) { ?>disabled="disabled"<?php } ?>/>
                                    <?php echo $category['name']; ?>
                                </div>
                            <?php }} ?>
						</div>

						<?php } ?>

						<p class="ms-note"><?php echo $ms_account_product_category_note; ?></p>
						<p class="error" id="error_product_category"></p>
					</div>
				</div>

				<?php if ($this->config->get('msconf_enable_shipping') == 2) { ?>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $ms_account_product_enable_shipping; ?></label>
					<div class="col-sm-10">
						<input type="radio" name="product_enable_shipping" value="1" <?php if($product['shipping'] == 1) { ?> checked="checked" <?php } ?>/>
						<?php echo $text_yes; ?>
						<input type="radio" name="product_enable_shipping" value="0" <?php if($product['shipping'] == 0) { ?> checked="checked" <?php } ?>/>
						<?php echo $text_no; ?>
						<p class="ms-note"><?php echo $ms_account_product_enable_shipping_note; ?></p>
						<p class="error" id="error_product_enable_shipping"></p>
					</div>
				</div>
				<?php } ?>

				<div class="form-group" <?php if (!$enable_quantities) { ?>style="display: none"<?php } ?>>
					<label class="col-sm-2 control-label"><?php echo $ms_account_product_quantity; ?></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="product_quantity" value="<?php echo $product['quantity']; ?>" />
						<p class="ms-note"><?php echo $ms_account_product_quantity_note; ?></p>
						<p class="error" id="error_product_quantity"></p>
					</div>
				</div>

				<?php if (isset($normal_attributes) && !empty($normal_attributes)) { ?>
				<?php foreach ($normal_attributes as $attr) { ?>
				<div class="form-group <?php if ($attr['required']) { echo 'required'; } ?>">
					<label class="col-sm-2 control-label"><?php echo $attr['name']; ?></label>
					<div class="col-sm-10">
						<?php if ($attr['attribute_type'] == MsAttribute::TYPE_SELECT) { ?>
							<select class="form-control" name="product_attributes[<?php echo $attr['attribute_id']; ?>]">
							<option value=""><?php echo $text_select; ?></option>
							<?php foreach ($attr['values'] as $attr_value) { ?>
							<option value="<?php echo $attr_value['attribute_value_id']; ?>" <?php if (isset($normal_attribute_values[$attr['attribute_id']]) && array_key_exists($attr_value['attribute_value_id'], $normal_attribute_values[$attr['attribute_id']])) { ?>selected="selected"<?php } ?>><?php echo $attr_value['name']; ?></option>
							<?php } ?>
							</select>
						<?php } ?>

						<?php if ($attr['attribute_type'] == MsAttribute::TYPE_RADIO) { ?>
						<?php foreach ($attr['values'] as $attr_value) { ?>
							<input type="radio" name="product_attributes[<?php echo $attr['attribute_id']; ?>]" value="<?php echo $attr_value['attribute_value_id']; ?>" <?php if (isset($normal_attribute_values[$attr['attribute_id']]) && array_key_exists($attr_value['attribute_value_id'], $normal_attribute_values[$attr['attribute_id']])) { ?>checked="checked"<?php } ?> />
							<label><?php echo $attr_value['name']; ?></label>
							<br />
						<?php } ?>
						<?php } ?>

						<?php if ($attr['attribute_type'] == MsAttribute::TYPE_IMAGE) { ?>
						<?php foreach ($attr['values'] as $attr_value) { ?>
							<input type="radio" name="product_attributes[<?php echo $attr['attribute_id']; ?>]" value="<?php echo $attr_value['attribute_value_id']; ?>" <?php if (isset($normal_attribute_values[$attr['attribute_id']]) && array_key_exists($attr_value['attribute_value_id'], $normal_attribute_values[$attr['attribute_id']])) { ?>checked="checked"<?php } ?> style="vertical-align: middle"/>
							<label><?php echo $attr_value['name']; ?></label>
							<img src="<?php echo $attr_value['image']; ?>" style="vertical-align: middle; padding: 1px; border: 1px solid #DDDDDD; margin-bottom: 10px" />
							<br />
						<?php } ?>
						<?php } ?>

						<?php if ($attr['attribute_type'] == MsAttribute::TYPE_CHECKBOX) { ?>
						<?php foreach ($attr['values'] as $attr_value) { ?>
							<input type="checkbox" name="product_attributes[<?php echo $attr['attribute_id']; ?>][]" value="<?php echo $attr_value['attribute_value_id']; ?>" <?php if (isset($normal_attribute_values[$attr['attribute_id']]) && array_key_exists($attr_value['attribute_value_id'], $normal_attribute_values[$attr['attribute_id']])) { ?>checked="checked"<?php } ?> />
							<label><?php echo $attr_value['name']; ?></label>
							<br />
						<?php } ?>
						<?php } ?>

						<?php if ($attr['attribute_type'] == MsAttribute::TYPE_TEXT) { ?>
							<input type="text" class="form-control" name="product_attributes[<?php echo $attr['attribute_id']; ?>][value]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo current(reset($normal_attribute_values[$attr['attribute_id']])); } ?>" />
							<input type="hidden" name="product_attributes[<?php echo $attr['attribute_id']; ?>][value_id]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo key($normal_attribute_values[$attr['attribute_id']]); } ?>" />
						<?php } ?>

						<?php if ($attr['attribute_type'] == MsAttribute::TYPE_TEXTAREA) { ?>
							<textarea class="form-control"  name="product_attributes[<?php echo $attr['attribute_id']; ?>][value]" cols="40" rows="5"><?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo current(reset($normal_attribute_values[$attr['attribute_id']])); } ?></textarea>
							<input type="hidden" name="product_attributes[<?php echo $attr['attribute_id']; ?>][value_id]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo key($normal_attribute_values[$attr['attribute_id']]); } ?>" />
						<?php } ?>

						<?php if ($attr['attribute_type'] == MsAttribute::TYPE_DATE) { ?>
							<div class="input-group date">
							<input type="text" class="form-control inline" name="product_attributes[<?php echo $attr['attribute_id']; ?>][value]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo current(reset($normal_attribute_values[$attr['attribute_id']])); } ?>" data-date-format="YYYY-MM-DD" />
							<span class="input-group-btn">
								<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
							</div>
							<input type="hidden" name="product_attributes[<?php echo $attr['attribute_id']; ?>][value_id]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo key($normal_attribute_values[$attr['attribute_id']]); } ?>" />
						<?php } ?>

						<?php if ($attr['attribute_type'] == MsAttribute::TYPE_DATETIME) { ?>
							<div class="input-group datetime">
							<input type="text" class="form-control inline" name="product_attributes[<?php echo $attr['attribute_id']; ?>][value]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo current(reset($normal_attribute_values[$attr['attribute_id']])); } ?>" data-date-format="YYYY-MM-DD HH:mm" />
							<span class="input-group-btn">
								<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
							</div>
							<input type="hidden" name="product_attributes[<?php echo $attr['attribute_id']; ?>][value_id]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo key($normal_attribute_values[$attr['attribute_id']]); } ?>" />
						<?php } ?>

						<?php if ($attr['attribute_type'] == MsAttribute::TYPE_TIME) { ?>
							<div class="input-group time">
							<input type="text" class="form-control inline" name="product_attributes[<?php echo $attr['attribute_id']; ?>][value]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo current(reset($normal_attribute_values[$attr['attribute_id']])); } ?>" data-date-format="HH:mm" />
							<span class="input-group-btn">
								<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
							</div>
							<input type="hidden" name="product_attributes[<?php echo $attr['attribute_id']; ?>][value_id]" value="<?php if (isset($normal_attribute_values[$attr['attribute_id']])) { echo key($normal_attribute_values[$attr['attribute_id']]); } ?>" />
						<?php } ?>

						<p class="ms-note"><?php echo $attr['description']; ?></p>
						<p class="error"></p>
					</div>
				</div>
				<?php } ?>
				<?php } ?>
			</fieldset>

			<fieldset>
				<legend><?php echo $ms_account_product_files; ?></legend>

				<div class="form-group <?php if ($msconf_images_limits[0] > 0) { echo 'required'; } ?>">
					<label class="col-sm-2 control-label"><?php echo $ms_account_product_image; ?></label>
					<div class="col-sm-10">
						<!--<input type="file" name="ms-file-addimages" id="ms-file-addimages" />-->
						<a name="ms-file-addimages" id="ms-file-addimages" class="btn btn-primary"><span><?php echo $ms_button_select_images; ?></span></a>
						<p class="ms-note"><?php echo $ms_account_product_image_note; ?></p>
						<div class="error" id="error_product_image"></div>

						<div class="image progress"></div>

						<div class="product_image_files">
						<?php if (isset($product['images'])) { ?>
						<?php $i = 0; ?>
						<?php foreach ($product['images'] as $image) { ?>
							<div class="ms-image">
								<input type="hidden" name="product_images[]" value="<?php echo $image['name']; ?>" />
								<img src="<?php echo $image['thumb']; ?>" />
								<span class="ms-remove"></span>
							</div>
						<?php $i++; ?>
						<?php } ?>
						<?php } ?>
						</div>
					</div>
				</div>

				<div class="form-group <?php if ($msconf_downloads_limits[0] > 0) { echo 'required'; } ?>">
					<label class="col-sm-2 control-label"><?php echo $ms_account_product_download; ?></label>
					<div class="col-sm-10">
						<!--<input type="file" name="ms-file-addfiles" id="ms-file-addfiles" />-->
						<a name="ms-file-addfiles" id="ms-file-addfiles" class="btn btn-primary"><span><?php echo $ms_button_select_files; ?></span></a>
						<p class="ms-note"><?php echo $ms_account_product_download_note; ?></p>
						<div class="error" id="error_product_download"></div>
						<div class="download progress"></div>
						<div class="product_download_files">
						<?php if (isset($product['downloads'])) { ?>
						<?php $i = 0; ?>
						<?php foreach ($product['downloads'] as $download) { ?>
							<div class="ms-download">
								<input type="hidden" name="product_downloads[<?php echo $i; ?>][download_id]" value="<?php echo isset($clone) ? '' : $download['id']; ?>" />
								<input type="hidden" name="product_downloads[<?php echo $i; ?>][filename]" value="<?php echo (isset($clone)) ? $download['src'] : ''; ?>" />
								<span class="ms-download-name"><?php echo $download['name']; ?></span>
								<div class="ms-buttons">
									<a href="<?php echo $download['href']; ?>" class="ms-button-download" title="<?php echo $ms_download; ?>"></a>
										<!--<input id="ms-update-<?php echo $download['id']; ?>" name="ms-update-<?php echo $download['id']; ?>" class="ms-file-updatedownload" type="file" multiple="false" />-->
									<a id="ms-update-<?php echo $download['id']; ?>" name="ms-update-<?php echo $download['id']; ?>" class="ms-file-updatedownload ms-button-update" title="<?php echo $ms_update; ?>"></a>
									<a class="ms-button-delete" title="<?php echo $ms_delete; ?>"></a>
								</div>
							</div>
						<?php $i++; ?>
						<?php } ?>
						<?php } ?>
						</div>
					</div>
				</div>
			</fieldset>

			<?php if ($seller['ms.product_validation'] == MsProduct::MS_PRODUCT_VALIDATION_APPROVAL) { ?>
			<fieldset>
				<legend><?php echo $ms_account_product_message_reviewer; ?></legend>

				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $ms_account_product_message; ?></label>
					<div class="col-sm-10">
						<textarea class="form-control"  name="product_message"></textarea>
						<p class="ms-note"><?php echo $ms_account_product_message_note; ?></p>
						<p class="error" id="error_product_message"></p>
					</div>
				</div>
			</fieldset>
			<?php } ?>
		</div>


			



				<?php if ($this->config->get('msconf_enable_shipping')) { ?>
					<div id="tab-shipping" class="tab-pane tab-content"></div>
				<?php } ?>
			
		<!-- specials tab -->
		<?php if ($this->config->get('msconf_allow_specials')) { ?>
		<div id="tab-specials" class="tab-pane">
			<legend><?php echo $ms_account_product_tab_specials; ?></legend>
			<p class="error" id="error_specials"></p>

			<table class="list table table-bordered table-hover">
				<thead>
				<tr>
					<td><span class="required">*</span><?php echo $ms_account_product_priority; ?></td>
					<td <?php if ($hide_customer_groups) { ?> class="hidden" <?php } ?>><span class="required">*</span><?php echo $ms_account_product_customer_group; ?></td>
					<td><span class="required">*</span><?php echo $ms_account_product_price; ?></td>
					<td><span class="required">*</span><?php echo $ms_account_product_date_start; ?></td>
					<td><span class="required">*</span><?php echo $ms_account_product_date_end; ?></td>
					<td></td>
				</tr>
				</thead>

				<tbody>

				<!-- sample row -->
				<tr class="ffSample">
					<td><input type="text" class="form-control inline small" name="product_specials[0][priority]" value="" size="2" /></td>

					<td <?php if ($hide_customer_groups) { ?> class="hidden" <?php } ?>>
						<select name="product_specials[0][customer_group_id]" class="form-control inline">
						  <?php foreach ($customer_groups as $customer_group) { ?>
						  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
						  <?php } ?>
						</select>
					</td>

					<td><input type="text" class="form-control inline small" name="product_specials[0][price]" value="" /></td>
					<td>
						<div class="input-group date">
						<input type="text" class="form-control inline" name="product_specials[0][date_start]" value="" data-date-format="YYYY-MM-DD" />
						<span class="input-group-btn">
                			<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
						</span>
						</div>
					</td>
					<td>
						<div class="input-group date">
						<input type="text" class="form-control inline" name="product_specials[0][date_end]" value="" data-date-format="YYYY-MM-DD" />
						<span class="input-group-btn">
                			<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
						</span>
						</div>
					</td>
					<td><a class="ms-button-delete" title="<?php echo $ms_delete; ?>"></a></td>
				</tr>

				<?php if (isset($product['specials'])) { ?>
				<?php $special_row = 1; ?>
				<?php foreach ($product['specials'] as $product_special) { ?>
				<tr>
					<td><input type="text" class="form-control inline small" name="product_specials[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>

					<td <?php if ($hide_customer_groups) { ?> class="hidden" <?php } ?>>
						<select name="product_specials[<?php echo $special_row; ?>][customer_group_id]" class="form-control inline">
						  <?php foreach ($customer_groups as $customer_group) { ?>
						  <option value="<?php echo $customer_group['customer_group_id']; ?>" <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?> selected="selected" <?php } ?>><?php echo $customer_group['name']; ?></option>
						  <?php } ?>
						</select>
					</td>

					<td><input type="text" class="form-control inline small" name="product_specials[<?php echo $special_row; ?>][price]" value="<?php echo $this->MsLoader->MsHelper->uniformDecimalPoint($product_special['price']); ?>" /></td>
					<td>
						<div class="input-group date">
						<input type="text" class="form-control inline" name="product_specials[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" data-date-format="YYYY-MM-DD" />
						<span class="input-group-btn">
                			<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
						</span>
						</div>
					</td>
					<td>
						<div class="input-group date">
						<input type="text" class="form-control inline" name="product_specials[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" data-date-format="YYYY-MM-DD" />
						<span class="input-group-btn">
                			<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
						</span>
						</div>
					</td>
					<td><a class="ms-button-delete" title="<?php echo $ms_delete; ?>"></a></td>
				</tr>
				<?php $special_row++; ?>
				<?php } ?>
				<?php } ?>
				</tbody>

				<tfoot>
				<tr>
				<td colspan="6" class="text-center"><a class="btn btn-primary ffClone"><?php echo $ms_button_add_special; ?></a></td>
				</tr>
				</tfoot>
			</table>
		</div>
		<?php } ?>

		<!-- Quantity Discounts tab -->
		<?php if ($this->config->get('msconf_allow_discounts')) { ?>
		<div id="tab-discounts" class="tab-pane">
			<legend><?php echo $ms_account_product_tab_discounts; ?></legend>
			<p class="error" id="error_quantity_discounts"></p>
			
			<table class="list table table-bordered table-hover">
				<thead>
				<tr>
					<td><span class="required">*</span><?php echo $ms_account_product_priority; ?></td>
					<td <?php if ($hide_customer_groups) { ?> class="text" <?php } ?>><span class="required">*</span><?php echo $ms_account_product_customer_group; ?></td>
					<td><span class="required">*</span><?php echo $ms_account_product_quantity; ?></td>
					<td><span class="required">*</span><?php echo $ms_account_product_price; ?></td>
					<td><span class="required">*</span><?php echo $ms_account_product_date_start; ?></td>
					<td><span class="required">*</span><?php echo $ms_account_product_date_end; ?></td>
					<td></td>
				</tr>
				</thead>
				
				<tbody>				
				
				<!-- sample row -->
				<tr class="ffSample">				
					<td><input type="text" class="form-control inline small" name="product_discounts[0][priority]" value="" size="2" /></td>

					<td <?php if ($hide_customer_groups) { ?> class="hidden" <?php } ?>>
						<select name="product_discounts[0][customer_group_id]" class="form-control inline">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                        </select>
					</td>

					<td><input type="text" class="form-control inline small" name="product_discounts[0][quantity]" value="" size="2" /></td>
					<td><input type="text" class="form-control inline small" name="product_discounts[0][price]" value="" /></td>
					<td>
						<div class="input-group date">
						<input type="text" class="form-control inline" name="product_discounts[0][date_start]" value="" data-date-format="YYYY-MM-DD" />
						<span class="input-group-btn">
                			<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
						</span>
						</div>
					</td>
					<td>
						<div class="input-group date">
						<input type="text" class="form-control inline" name="product_discounts[0][date_end]" value="" data-date-format="YYYY-MM-DD" />
						<span class="input-group-btn">
                			<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
						</span>
						</div>
					</td>
					<td><a class="ms-button-delete" title="<?php echo $ms_delete; ?>"></a></td>
				</tr>
				
				<?php if (isset($product['discounts'])) { ?>
				<?php $discount_row = 1; ?>
				<?php foreach ($product['discounts'] as $product_discount) { ?>
				<tr>
					<td><input type="text" class="form-control inline small" name="product_discounts[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2" /></td>

					<td <?php if ($hide_customer_groups) { ?> class="hidden" <?php } ?>>
						<select name="product_discounts[<?php echo $discount_row; ?>][customer_group_id]" class="form-control inline">
						  <?php foreach ($customer_groups as $customer_group) { ?>
						  <option value="<?php echo $customer_group['customer_group_id']; ?>" <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?> selected="selected" <?php } ?>><?php echo $customer_group['name']; ?></option>
						  <?php } ?>
						</select>
					</td>

					<td><input type="text" class="form-control inline small" name="product_discounts[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" /></td>
					<td><input type="text" class="form-control inline small" name="product_discounts[<?php echo $discount_row; ?>][price]" value="<?php echo $this->MsLoader->MsHelper->uniformDecimalPoint($product_discount['price']); ?>" /></td>
					<td>
						<div class="input-group date">
						<input type="text" class="form-control inline" name="product_discounts[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" data-date-format="YYYY-MM-DD" />
						<span class="input-group-btn">
                			<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
						</span>
						</div>
					</td>
					<td>
						<div class="input-group date">
						<input type="text" class="form-control inline" name="product_discounts[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" data-date-format="YYYY-MM-DD" />
						<span class="input-group-btn">
                			<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
						</span>
						</div>
					</td>
					<td><a class="ms-button-delete" title="<?php echo $ms_delete; ?>"></a></td>
				</tr>
				<?php $discount_row++; ?>
				<?php } ?>
				<?php } ?>
				</tbody>

				<tfoot>
				<tr>
					<td colspan="7" class="text-center"><a class="btn btn-primary ffClone"><?php echo $ms_button_add_discount; ?></a></td>
				</tr>
				</tfoot>
			</table>
		</div>
		<?php } ?>
		</div>
	</form>
		
		<?php if (isset($seller['commissions']) && ($seller['commissions'][MsCommission::RATE_LISTING]['percent'] > 0 || $seller['commissions'][MsCommission::RATE_LISTING]['flat'] > 0)) { ?>
			<?php if ($seller['commissions'][MsCommission::RATE_LISTING]['percent'] > 0) { ?>
			<p class="alert alert-warning ms-commission">
				<?php echo sprintf($this->language->get('ms_account_product_listing_percent'),$this->currency->format($seller['commissions'][MsCommission::RATE_LISTING]['flat'], $this->config->get('config_currency'))); ?>
				<?php echo $ms_commission_payment_type; ?>
			</p>
			<?php } else if ($seller['commissions'][MsCommission::RATE_LISTING]['flat'] > 0) { ?>
			<p class="alert alert-warning ms-commission">
				<?php echo sprintf($this->language->get('ms_account_product_listing_flat'),$this->currency->format($seller['commissions'][MsCommission::RATE_LISTING]['flat'], $this->config->get('config_currency'))); ?>
				<?php echo $ms_commission_payment_type; ?>
			</p>
			<?php } ?>
			
			<?php if(isset($payment_form)) { ?><div class="ms-payment-form"><?php echo $payment_form; ?></div><?php } ?>
		<?php } ?>
		
		<?php if (isset($list_until) && $list_until != NULL) { ?>
			<p class="alert alert-warning">
				<?php echo sprintf($this->language->get('ms_account_product_listing_until'), date($this->language->get('date_format_short'), strtotime($list_until))); ?>
			</p>
		<?php } ?>

		<div class="buttons">
			<div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><span><?php echo $ms_button_cancel; ?></span></a></div>
			<?php if ($seller['ms.seller_status'] != MsSeller::STATUS_DISABLED && $seller['ms.seller_status'] != MsSeller::STATUS_DELETED && $seller['ms.seller_status'] != MsSeller::STATUS_INCOMPLETE) { ?>
			<div class="pull-right"><a class="btn btn-primary" id="ms-submit-button"><span><?php echo $ms_button_submit; ?></span></a></div>
			<?php } ?>
		</div>
    <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>

<?php $timestamp = time(); ?>
<script>

				<?php if (isset($clone) && $clone == 1) {?>
					$('#tab-shipping').load($('base').attr('href') + 'index.php?route=seller/tab-shipping/getForm&product_id=<?php echo $product['cloned_product_id']; ?>');
				<?php } else { ?>
					$('#tab-shipping').load($('base').attr('href') + 'index.php?route=seller/tab-shipping/getForm&product_id=<?php echo $product['product_id']; ?>');
				<?php } ?>
			
	var msGlobals = {
		timestamp: '<?php echo $timestamp; ?>',
		token : '<?php echo md5($salt . $timestamp); ?>',
		session_id: '<?php echo session_id(); ?>',
		product_id: '<?php echo $product['product_id']; ?>',
		text_delete: '<?php echo htmlspecialchars($ms_delete, ENT_QUOTES, "UTF-8"); ?>',
		text_none: '<?php echo htmlspecialchars($ms_none, ENT_QUOTES, "UTF-8"); ?>',
		uploadError: '<?php echo htmlspecialchars($ms_error_file_upload_error, ENT_QUOTES, "UTF-8"); ?>',
		formError: '<?php echo htmlspecialchars($ms_error_form_submit_error, ENT_QUOTES, "UTF-8"); ?>',
		formNotice: '<?php echo htmlspecialchars($ms_error_form_notice, ENT_QUOTES, "UTF-8"); ?>',
		config_enable_rte: '<?php echo $this->config->get('msconf_enable_rte'); ?>',
		config_enable_quantities: '<?php echo $this->config->get('msconf_enable_quantities'); ?>'
	};
</script>
<?php echo $footer; ?>
