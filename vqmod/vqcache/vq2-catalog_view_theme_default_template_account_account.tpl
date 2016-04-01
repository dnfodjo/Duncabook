<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
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
      <h2><?php echo $text_my_account; ?></h2>

				<?php $unread_messages = (int)$this->MsLoader->MsConversation->unreadMessages($this->customer->getId()); ?>
				<?php if ($unread_messages) { ?>
                    <div class="alert alert-danger hidden"><i class="fa fa-exclamation-circle">
                        <?php if ($unread_messages == 1) {
                            echo $ms_account_unread_pm;
                        } else {
                            echo sprintf($ms_account_unread_pms, $unread_messages);
                        }?>
                        </i><button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
				<?php } ?>
			
      <ul class="list-unstyled">
        <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
        <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
        <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
        <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>

				<?php if ($this->config->get('mmess_conf_enable')) { ?>
					<li>
						<a href="<?php echo $this->url->link('account/msconversation', '', 'SSL'); ?>"><?php echo $ms_account_messages; ?></a>
					</li>
				<?php } ?>
			
      </ul>
      <h2><?php echo $text_my_orders; ?></h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
        <?php if ($reward) { ?>
        <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
        <?php } ?>
        <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
        <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
        <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
      </ul>

			    <h2><?php echo $ms_account_seller_account; ?></h2>
					<ul class="list-unstyled ms-sellermenu <?php if ($this->config->get('msconf_graphical_sellermenu')) { ?>graphical<?php } ?>">
                        <?php if ($ms_seller_created && $this->MsLoader->MsSeller->getStatus($this->customer->getId()) == MsSeller::STATUS_ACTIVE) { ?>
                            <li>
                                <a href="<?php echo $this->url->link('seller/account-dashboard', '', 'SSL'); ?>">
                                    <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                        <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-graph-96.png" />
                                    <?php } ?>
                                    <?php echo $ms_account_dashboard; ?>
                                </a>
                            </li>
                        <?php } ?>

                        <li>
                            <a href="<?php echo $this->url->link('seller/account-profile', '', 'SSL'); ?>">
                                <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                    <?php if ($ms_seller_created) { ?>
                                    <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-profile-96.png" />
                                    <?php } else { ?>
                                    <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-profile-plus-96.png" />
                                    <?php } ?>
                                <?php } ?>
                                <?php echo $ms_seller_created ? $ms_account_sellerinfo : $ms_account_sellerinfo_new; ?>
                            </a>
                        </li>

                        <?php if ( ($ms_seller_created) && ( ($this->MsLoader->MsSeller->getStatus($this->customer->getId()) == MsSeller::STATUS_ACTIVE) || ($this->config->get('msconf_allow_inactive_seller_products')) ) ) { ?>
                            <li>
                                <a href="<?php echo $this->url->link('seller/account-product/create', '', 'SSL'); ?>">
                                    <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                        <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-bag-plus-96.png" />
                                    <?php } ?>
                                    <?php echo $ms_account_newproduct; ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->url->link('seller/account-product', '', 'SSL'); ?>">
                                    <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                        <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-bag-96.png" />
                                    <?php } ?>
                                    <?php echo $ms_account_products; ?>
                                </a>
                            </li>
                        <?php } ?>

			<?php if ($ms_seller_created && $this->MsLoader->MsSeller->getStatus($this->customer->getId()) == MsSeller::STATUS_ACTIVE) { ?>
				<?php if ($this->config->get('msconf_enable_shipping')) { ?>
					<li>
						<a href="<?php echo $this->url->link('seller/account-shipping-settings', '', 'SSL'); ?>">
                            <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-woodbox-96.png" />
                            <?php } ?>
                            <?php echo $ms_account_shipping_settings; ?>
					    </a>
					</li>
				<?php } ?>
			<?php } ?>
			
                        <?php if ($ms_seller_created && $this->MsLoader->MsSeller->getStatus($this->customer->getId()) == MsSeller::STATUS_ACTIVE) { ?>
                            <li>
                                <a href="<?php echo $this->url->link('seller/account-order', '', 'SSL'); ?>">
                                    <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                        <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-cart-96.png" />
                                    <?php } ?>
                                    <?php echo $ms_account_orders; ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->url->link('seller/account-transaction', '', 'SSL'); ?>">
                                    <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                        <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-book-96.png" />
                                    <?php } ?>
                                    <?php echo $ms_account_transactions; ?>
                                </a>
                            </li>
                            <?php if ($this->config->get('msconf_allow_withdrawal_requests')) { ?>
                                <li>
                                    <a href="<?php echo $this->url->link('seller/account-withdrawal', '', 'SSL'); ?>">
                                        <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                            <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-dollar-96.png" />
                                        <?php } ?>
                                        <?php echo $ms_account_withdraw; ?>
                                    </a>
                                </li>
                            <?php } ?>
                                <li>
                                    <a href="<?php echo $this->url->link('seller/account-stats', '', 'SSL'); ?>">
                                        <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                            <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-stats-96.png" />
                                        <?php } ?>
                                        <?php echo $ms_account_stats; ?>
                                    </a>
                                </li>
                        <?php } ?>

			<?php if ($ms_seller_created && $this->MsLoader->MsSeller->getStatus($this->customer->getId()) == MsSeller::STATUS_ACTIVE) { ?>
				<?php if ($this->config->get('mmess_conf_enable')) { ?>
					<li>
						<a href="<?php echo $this->url->link('account/msconversation', '', 'SSL'); ?>">
                            <?php if($this->config->get('msconf_graphical_sellermenu')) { ?>
                                <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-envelope-96.png" />
                            <?php } ?>
                            <?php echo $ms_account_messages; ?>
					    </a>
					</li>
				<?php } ?>
			<?php } ?>
			
					</ul>
			
      <h2><?php echo $text_my_newsletter; ?></h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
      </ul>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
