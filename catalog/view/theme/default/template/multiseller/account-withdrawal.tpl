<?php echo $header; ?>
<div class="container ms-account-withdrawal">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if (isset($success) && ($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?></div>
  <?php } ?>

  <?php if (isset($error_warning) && $error_warning) { ?>
  	<div class="alert alert-danger warning main"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="ms-account-transaction <?php echo $class; ?>"><?php echo $content_top; ?>
    <h1><?php echo $ms_account_withdraw_heading; ?></h1>

	<p><?php echo $ms_account_withdraw_balance; ?> <b><?php echo $ms_account_balance_formatted; ?></b> <span style="color: gray"><?php echo $ms_account_reserved_formatted; ?></span></p>
	<p><?php echo $ms_account_withdraw_balance_available; ?> <b><?php echo $balance_available_formatted; ?></b></p>
	<p><?php echo $ms_account_withdraw_minimum; ?> <b><?php echo $this->currency->format($this->config->get('msconf_minimum_withdrawal_amount'),$this->config->get('config_currency')); ?></b></p>
	
	<?php if ($balance_available <= 0) { ?>
		<div class="alert alert-warning"><?php echo $ms_account_withdraw_no_funds; ?></div>
	<?php } else if (!isset($paypal) || empty($paypal)) { ?>
		<div class="alert alert-warning"><?php echo $ms_account_withdraw_no_paypal; ?></div>
	<?php } else if (!$withdrawal_minimum_reached) { ?>
		<div class="alert alert-warning"><?php echo $ms_account_withdraw_minimum_not_reached; ?></div>
	<?php } ?>
	
	<form id="ms-withdrawal" class="ms-form">
		<div class="content">
			<?php if (!$withdrawal_minimum_reached || !isset($paypal) || empty($paypal) || $balance_available <= 0) { ?>
			<div class="ms-overlay"></div>
			<?php } ?>
			
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $ms_account_withdraw_amount; ?></label>
					<div class="col-sm-10">
						<?php if ($msconf_allow_partial_withdrawal) { ?>
						<p>
							<input type="radio" class="form-inline" name="withdraw_all" value="0" checked="checked" />
							<input type="text" class="form-control" style="width: 100px; display: inline" name="withdraw_amount" value="<?php echo $this->currency->format($this->config->get('msconf_minimum_withdrawal_amount'),$this->config->get('config_currency'), '', FALSE); ?>" />
							<?php echo $currency_code; ?>
						</p>
						<?php } ?>
						
						<p>
							<input type="radio" name="withdraw_all" value="1" <?php if (!$msconf_allow_partial_withdrawal) { ?>checked="checked"<?php } ?> />
							<span><?php echo $ms_account_withdraw_all; ?> (<?php echo $balance_available_formatted; ?>)</span>
						</p>
						<p class="ms-note"><?php echo $ms_account_withdraw_amount_note; ?></p>
						<p class="error" id="error_withdraw_amount"></p>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $ms_account_withdraw_method; ?></label>
					<div class="col-sm-10">
						<p>
							<input type="radio" name="withdraw_method" value="paypal" checked="checked" />
							<span><?php echo $ms_account_withdraw_method_paypal; ?></span>
						</p>
						<p class="ms-note"><?php echo $ms_account_withdraw_method_note; ?></p>
						<p class="error" id="error_withdraw_method"></p>
					</div>
				</div>
			</table>
		</div>

		<div class="buttons">
			<div class="pull-left"><a href="<?php echo $link_back; ?>" class="btn btn-default"><span><?php echo $button_back; ?></span></a></div>
			<?php if ($withdrawal_minimum_reached && isset($paypal) && !empty($paypal)) { ?>
			<div class="pull-right"><a class="btn btn-primary" id="ms-submit-request"><span><?php echo $ms_button_submit_request; ?></span></a></div>
			<?php } ?>
		</div>
	</form>
    <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>

<?php echo $footer; ?>