<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if (isset($success) && $success) { ?>
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
    <div id="content" class="<?php echo $class; ?> ms-account-dashboard"><?php echo $content_top; ?>
    <h1><?php echo $ms_account_dashboard_heading; ?></h1>
    <div class="row">
	<div class="overview col-md-2">
		<h3><?php echo $ms_account_dashboard_overview; ?></h3>
		<a href="<?php echo $this->url->link('seller/catalog-seller/profile', 'seller_id=' . $seller['seller_id']); ?>"><img src="<?php echo $seller['avatar']; ?>" /></a><br />
		<span class="nickname"><?php echo $seller['ms.nickname']; ?></span>
		<p><span><?php echo $ms_date_created; ?>:</span> <span><?php echo $seller['date_created']; ?></span></p>
		
		
	</div>

	<div class="stats col-md-2">
		<h3><?php echo $ms_account_dashboard_stats; ?></h3>
		<p><span><?php echo $ms_account_dashboard_balance; ?>:</span> <span><?php echo $seller['balance']; ?></span></p>
		<p><span><?php echo $ms_account_dashboard_total_sales; ?>:</span> <span><?php echo $seller['total_sales']; ?></span></p>
		<p><span><?php echo $ms_account_dashboard_total_earnings; ?>:</span> <span><?php echo $seller['total_earnings']; ?></span></p>
		<p><span><?php echo $ms_account_dashboard_sales_month; ?>:</span> <span><?php echo $seller['sales_month']; ?></span></p>
		<p><span><?php echo $ms_account_dashboard_earnings_month; ?>:</span> <span><?php echo $seller['earnings_month']; ?></span></p>

				<h3><?php echo $ms_account_badges; ?></h3>
				<p>
				<?php if (isset($seller['badges']) && $seller['badges']) { ?>
				<?php foreach($seller['badges'] as $badge) { ?>
					<img src="<?php echo $badge['image']; ?>" title="<?php echo $badge['description']; ?>" />
				<?php } ?>
				<?php } else { ?>
					<p><?php echo $ms_account_badges_nobadges; ?></p>
				<?php } ?>
				</p>
			
	</div>
	<div class="nav col-md-5"></div>

	<div class="nav col-md-3">
		<h3><?php echo $ms_account_dashboard_nav; ?></h3>
		<a href="<?php echo $this->url->link('seller/account-profile', '', 'SSL'); ?>">
			<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-profile.png" />
			<span><?php echo $ms_account_dashboard_nav_profile; ?></span>
		</a>

		<a href="<?php echo $this->url->link('seller/account-product/create', '', 'SSL'); ?>">
			<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-bag-plus.png" />
			<span><?php echo $ms_account_dashboard_nav_product; ?></span>
		</a>

		<a href="<?php echo $this->url->link('seller/account-product', '', 'SSL'); ?>">
			<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-bag.png" />
			<span><?php echo $ms_account_dashboard_nav_products; ?></span>
		</a>
		
		<a href="<?php echo $this->url->link('seller/account-order', '', 'SSL'); ?>">
			<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-cart-96.png" />
			<span><?php echo $ms_account_dashboard_nav_orders; ?></span>
		</a>
		
		<a href="<?php echo $this->url->link('seller/account-transaction', '', 'SSL'); ?>">
			<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-book-96.png" />
			<span><?php echo $ms_account_dashboard_nav_balance; ?></span>
		</a>
		

				<?php if ($this->config->get('mmess_conf_enable') == 1) { ?>
				<a href="<?php echo $this->url->link('account/msconversation', '', 'SSL'); ?>">
					<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-envelope-96.png" />
					<span><?php echo $ms_account_messages; ?></span>
				</a>
				<?php } ?>
			
		<?php if ($this->config->get('msconf_allow_withdrawal_requests')) { ?>
		<a href="<?php echo $this->url->link('seller/account-withdrawal', '', 'SSL'); ?>">
			<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-dollar.png" />
			<span><?php echo $ms_account_dashboard_nav_payout; ?></span>
		</a>
		<?php } ?>
		
		<a href="<?php echo $this->url->link('seller/account-stats', '', 'SSL'); ?>">
			<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/ms-stats.png" />
			<span><?php echo $ms_account_stats; ?></span>
		</a>
	</div>
    </div>

	<h2><?php echo $ms_account_dashboard_orders; ?></h2>
	<table class="list table table-bordered">
		<thead>
			<tr>
				<td><?php echo $ms_account_orders_id; ?></td>
				<?php if (!$this->config->get('msconf_hide_customer_email')) { ?>
					<td><?php echo $ms_account_orders_customer; ?></td>
				<?php } ?>
				<td><?php echo $ms_status; ?></td>
				<td style="width: 40%"><?php echo $ms_account_orders_products; ?></td>
				<td><?php echo $ms_date_created; ?></td>
				<td><?php echo $ms_account_orders_total; ?></td>
				<td><?php echo $ms_action; ?></td>
			</tr>
		</thead>
		
		<tbody>
		<?php if (isset($orders) && $orders) { ?>
			<?php foreach ($orders as $order) { ?>
			<tr>
				<td><?php echo $order['order_id']; ?></td>
				<?php if (!$this->config->get('msconf_hide_customer_email')) { ?>
					<td><?php echo $order['customer']; ?></td>
				<?php } ?>
				<td><?php echo $order['status']; ?></td>
				<td class="left products">
				<?php foreach ($order['products'] as $p) { ?>
				<p>
					<span class="name"><?php if ($p['quantity'] > 1) { echo "{$p['quantity']} x "; } ?> <a href="<?php echo $this->url->link('product/product', 'product_id=' . $p['product_id'], 'SSL'); ?>"><?php echo $p['name']; ?></a></span>
                    <?php foreach ($p['options'] as $option) { ?>
                    <br />
                    &nbsp;<small> - <?php echo $option['name']; ?>:<?php echo $option['value']; ?></small>
                    <?php } ?>
                    <span class="total"><?php echo $this->currency->format($p['seller_net_amt'], $this->config->get('config_currency')); ?></span>
				</p>
				<?php } ?>
				</td>
				<td><?php echo $order['date_created']; ?></td>
				<td><?php echo $order['total']; ?></td>
				<td>
					<a href="<?php echo $this->url->link('seller/account-order/viewOrder', 'order_id=' . $order['order_id']); ?>" class="ms-button ms-button-view" title="<?php echo $this->language->get('ms_view_modify') ?>"></a>
				</td>
			</tr>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td class="center" colspan="6"><?php echo $ms_account_orders_noorders; ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	
      <div class="buttons clearfix">
        <div class="pull-left"><a href="<?php echo $link_back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
