<?php echo $header; ?>
<div class="container">
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
    <h1><?php echo $ms_account_transactions_heading; ?></h1>
	<?php echo $ms_account_transactions_balance; ?> <b><?php echo $ms_balance_formatted; ?></b> <span style="color: gray"><?php echo $ms_reserved_formatted; ?></span><br />
	<?php echo $ms_account_transactions_earnings; ?> <b><?php echo $earnings; ?></b><br />

	<!-- BALANCE RECORDS -->
	<h2><?php echo $ms_account_transactions_records; ?></h2>
	<div class="table-responsive">
	<table class="list table table-bordered table-hover" style="text-align: center" id="list-transactions">
		<thead>
			<tr>
				<td class="tiny"><?php echo $ms_id; ?></td>
				<td class="small"><?php echo $ms_account_transactions_amount; ?></td>
				<td><?php echo $ms_account_transactions_description; ?></td>
				<td class="medium"><?php echo $ms_date_created; ?></td>
			</tr>
			
			<tr class="filter">
				<td><input type="text"/></td>
				<td><input type="text"/></td>
				<td><input type="text"/></td>
				<td><input type="text"/></td>
			</tr>
		</thead>
		
		<tbody>
		</tbody>
	</table>
	</div>
	<br />
	
	<!-- PAYMENTS -->
	<h2><?php echo $ms_payment_payments; ?></h2>
	<div class="table-responsive">
	<table class="list table table-bordered table-hover" style="text-align: center" id="list-payments">
	<thead>
	<tr>
		<td class="tiny"><?php echo $ms_id; ?></td>
		<td class="medium"><?php echo $ms_type; ?></td>
		<td class="small"><?php echo $ms_amount; ?></td>
		<td><?php echo $ms_description; ?></td>
		<td class="medium"><?php echo $ms_status; ?></td>
		<td class="medium"><?php echo $ms_date_paid; ?></td>
	</tr>
	
	<tr class="filter">
		<td><input type="text"/></td>
		<td></td>
		<td><input type="text"/></td>
		<td><input type="text"/></td>
		<td></td>
		<td><input type="text"/></td>
	</tr>
	</thead>
	
	<tbody></tbody>
	</table>
	</div>

	  <div class="buttons clearfix">
		<div class="pull-left"><a href="<?php echo $link_back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
	  </div>
	  <?php echo $content_bottom; ?></div>
	<?php echo $column_right; ?></div>
</div>

<script>
$(function() {
	$('#list-transactions').dataTable( {
		"sAjaxSource": $('base').attr('href') + "index.php?route=seller/account-transaction/getTransactionData",
		"aoColumns": [
			{ "mData": "transaction_id" },
			{ "mData": "amount" },
			{ "mData": "description", "bSortable": false },
			{ "mData": "date_created" }
		],
        "aaSorting":  [[3,'desc']]
	});

	$('#list-payments').dataTable( {
		"sAjaxSource": $('base').attr('href') + "index.php?route=seller/account-transaction/getPaymentData",
		"aoColumns": [
			{ "mData": "payment_id" },
			{ "mData": "payment_type" },
			{ "mData": "amount" },
			{ "mData": "description", "bSortable": false },
			{ "mData": "payment_status" },
			{ "mData": "date_created" },
		],
        "aaSorting":  [[5,'desc']]
	});
});
</script>

<?php echo $footer; ?>