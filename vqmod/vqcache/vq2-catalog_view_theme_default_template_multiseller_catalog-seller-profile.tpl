<?php echo $header; ?>
<div class="container ms-catalog-seller-profile">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li> <a href="<?php echo $breadcrumb['href']; ?>"> <?php echo $breadcrumb['text']; ?> </a> </li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">
		<!-- left column -->
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-8'; ?>
        <?php } ?>
		<div class="<?php echo $class; ?> seller-data">
			<?php if ($this->config->get('msconf_enable_seller_banner') && isset($seller['banner'])) { ?>
			<ul class="thumbnails seller-banner">
					<li><a class="thumbnail"><img src="<?php echo $seller['banner']; ?>" title="<?php echo $seller['nickname']; ?>" alt="<?php echo $seller['nickname']; ?>" /></a></li>
			</ul>
			<?php } ?>
			<div class="seller-description"><?php echo $seller['description']; ?></div>

			<?php if ($seller['products']) { ?>
			<hr />
			<h3><?php echo $ms_catalog_seller_profile_products; ?></h3>
			<div class="row">
			  <?php foreach ($seller['products'] as $product) { ?>
			  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="product-thumb transition">
				  <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
				  <div class="caption">
					<h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
				  </div>
				  <div class="button-group">
					<a href="<?php echo $product['href']; ?>"><button type="button" class="btn btn-main btn-block"><span><?php echo $ms_view; ?></span></button></a>
				  </div>
				</div>
			  </div>
			  <?php } ?>
			</div>
			<?php } ?>
			<!-- end products -->

				<?php if ($this->config->get('mscomm_seller_comments_enable')) { ?>
				<hr />
                <h3><?php echo $ms_catalog_seller_profile_tab_comments; ?></h3>
                <div class="row" id="cmContainer">
					<script type="text/javascript">
						$(function(){
							$('#cmContainer .pcForm').load('index.php?route=module/multimerch_comments/renderForm&seller_id=<?php echo $seller_id; ?>>');
							$('#cmContainer .pcComments').load('index.php?route=module/multimerch_comments/renderComments&seller_id=<?php echo $seller_id; ?>');
						});
					</script>
	                <div class="col-lg-12" id="tab-comments">
                        <div class="pcComments"></div>
                        <div class="pcForm"></div>
					</div>
				</div>
				<?php } ?>
			
		</div>

		<!-- right column -->
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
			<div class="info-box">
				<a class="avatar-box thumbnail" href="<?php echo $seller['href']; ?>"><img src="<?php echo $seller['thumb']; ?>" /></a>
				<div>
				<ul class="list-unstyled">
					<li><h3><?php echo $seller['nickname']; ?></h3></li>
					<?php if (isset($seller['country']) && $seller['country']) { ?><li><?php echo $ms_catalog_seller_profile_country; ?> <?php echo $seller['country']; ?></li><?php } ?>
					<?php if (isset($seller['company']) && $seller['company']) { ?><li><?php echo $ms_catalog_seller_profile_company; ?> <?php echo $seller['company']; ?></li><?php } ?>
					<?php if ($seller['website']) { ?><li><?php echo $ms_catalog_seller_profile_website; ?> <?php echo $seller['website']; ?></li><?php } ?>
					<li><?php echo $ms_catalog_seller_profile_totalsales; ?> <?php echo $seller['total_sales']; ?></li>
					<li><?php echo $ms_catalog_seller_profile_totalproducts; ?> <?php echo $seller['total_products']; ?></li>

				<li>
				<?php foreach($seller['badges'] as $badge) { ?>
					<img src="<?php echo $badge['image']; ?>" title="<?php echo $badge['description']; ?>" />
				<?php } ?>
				</li>
			
				</ul>
				<a href="<?php echo $seller['href']; ?>" class="btn btn-default btn-block"><span><?php echo $ms_catalog_seller_profile_view_products; ?></span></a>
				</div>
			</div>


			    <?php if ($this->config->get('msconf_sl_status') && !empty($seller['social_links'])) { ?>
                    <hr />
                    <div class="ms-social-links">
                        <h3><?php echo $ms_catalog_seller_profile_social; ?></h3>
                        <ul>
                            <?php foreach($seller['social_links'] as $link) { ?>
                                <?php if($this->MsLoader->MsHelper->isValidUrl($link['channel_value'])) { ?>
                                    <li><a target="_blank" href="<?php echo $this->MsLoader->MsHelper->addScheme($link['channel_value']); ?>"><img src="<?php echo $link['image']; ?>" /></a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
				<?php } ?>
			
			<?php if ($this->config->get('mmess_conf_enable') || $this->config->get('msconf_enable_private_messaging') == 2) { ?>
				<?php if ((!$this->customer->getId()) || ($this->customer->getId() != $seller['seller_id'])) { ?>
					<?php echo $contactForm; ?>
					<div class="contact">
						<h3><?php echo $ms_sellercontact_title ?></h3>
						<?php if ($this->customer->getId()) { ?>
						  <div class="button-group">
							<button type="button" class="btn btn-default btn-block ms-sellercontact" data-toggle="modal" data-target="#contactDialog"><span><?php echo $ms_catalog_product_contact; ?></span></button>
						  </div>
						<?php } else { ?>
							<?php echo sprintf($this->language->get('ms_sellercontact_signin'), $this->url->link('account/login', '', 'SSL'), $seller['nickname']); ?>
						<?php } ?>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	  </div>
	  <?php echo $content_bottom; ?>
	</div>
	<?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?>
