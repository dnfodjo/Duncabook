<div class="list-group">

				<span class="list-group-item"><b><?php echo $ms_account_customer_account; ?></b></span>
			
  <?php if (!$logged) { ?>
  <a href="<?php echo $login; ?>" class="list-group-item"><?php echo $text_login; ?></a><a href="<?php echo $forgotten; ?>" class="list-group-item"><?php echo $text_forgotten; ?></a>
  <?php } ?>
  <a href="<?php echo $account; ?>" class="list-group-item"><?php echo $text_account; ?></a>
  <?php if ($logged) { ?>
  <a href="<?php echo $edit; ?>" class="list-group-item"><?php echo $text_edit; ?></a> <a href="<?php echo $password; ?>" class="list-group-item"><?php echo $text_password; ?></a>
  <?php } ?>
  <a href="<?php echo $address; ?>" class="list-group-item"><?php echo $text_address; ?></a> <a href="<?php echo $wishlist; ?>" class="list-group-item"><?php echo $text_wishlist; ?></a> <a href="<?php echo $order; ?>" class="list-group-item"><?php echo $text_order; ?></a> <a href="<?php echo $download; ?>" class="list-group-item"><?php echo $text_download; ?></a><a href="<?php echo $recurring; ?>" class="list-group-item"><?php echo $text_recurring; ?></a> <a href="<?php echo $reward; ?>" class="list-group-item"><?php echo $text_reward; ?></a> <a href="<?php echo $return; ?>" class="list-group-item"><?php echo $text_return; ?></a> <a href="<?php echo $transaction; ?>" class="list-group-item"><?php echo $text_transaction; ?></a> <a href="<?php echo $newsletter; ?>" class="list-group-item"><?php echo $text_newsletter; ?></a>

				<?php if ($this->config->get('mmess_conf_enable')) { ?>
					<a href="<?php echo $this->url->link('account/msconversation', '', 'SSL'); ?>" class="list-group-item"><?php echo $ms_account_messages; ?></a>
				<?php } ?>
			
  <?php if ($logged) { ?>
  <a href="<?php echo $logout; ?>" class="list-group-item"><?php echo $text_logout; ?></a>
  <?php } ?>
</div>

			    <div class="list-group">
			        <span class="list-group-item"><b><?php echo $ms_account_seller_account; ?></b></span>
                    <?php if ($ms_seller_created) { ?>
                    <?php if ($this->MsLoader->MsSeller->getStatus($this->customer->getId()) == MsSeller::STATUS_ACTIVE) { ?>
                        <a class="list-group-item" href="<?php echo $this->url->link('seller/account-dashboard', '', 'SSL'); ?>"><?php echo $ms_account_dashboard; ?></a>
                    <?php } ?>

				    <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-profile', '', 'SSL'); ?>"><?php echo $ms_account_sellerinfo; ?></a>
				
                    <?php if ($this->MsLoader->MsSeller->getStatus($this->customer->getId()) == MsSeller::STATUS_ACTIVE) { ?>
                        <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-product/create', '', 'SSL'); ?>"><?php echo $ms_account_newproduct; ?></a>
                        <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-product', '', 'SSL'); ?>"><?php echo $ms_account_products; ?></a>

			<?php if ($ms_seller_created && $this->MsLoader->MsSeller->getStatus($this->customer->getId()) == MsSeller::STATUS_ACTIVE) { ?>
				<?php if ($this->config->get('msconf_enable_shipping') > 0) { ?>
				    <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-shipping-settings', '', 'SSL'); ?>"><?php echo $ms_account_shipping_settings; ?></a>
				<?php } ?>
			<?php } ?>
			
                        <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-order', '', 'SSL'); ?>"><?php echo $ms_account_orders; ?></a>
                        <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-transaction', '', 'SSL'); ?>"><?php echo $ms_account_transactions; ?></a>

				<?php if ($this->config->get('mmess_conf_enable')) { ?>
				    <a href="<?php echo $this->url->link('account/msconversation', '', 'SSL'); ?>" class="list-group-item"><?php echo $ms_account_messages; ?></a>
				<?php } ?>
			
                        <?php if ($this->config->get('msconf_allow_withdrawal_requests')) { ?>
                        <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-withdrawal', '', 'SSL'); ?>"><?php echo $ms_account_withdraw; ?></a>
                        <?php } ?>
                        <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-stats', '', 'SSL'); ?>"><?php echo $ms_account_stats; ?></a>
                    <?php } ?>
                    <?php } else { ?>
                        <a class="list-group-item" href="<?php echo $this->url->link('account/login', '', 'SSL'); ?>"><?php echo $text_login; ?></a>
                        <a class="list-group-item" href="<?php echo $this->url->link('account/register-seller', '', 'SSL'); ?>"><?php echo $ms_account_register_seller; ?></a>
                    <?php } ?>
				</div>
			
