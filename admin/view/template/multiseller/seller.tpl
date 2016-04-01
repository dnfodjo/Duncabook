<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $link_create_seller; ?>" data-toggle="tooltip" title="<?php echo $ms_catalog_sellers_create; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
      </div>
      <h1><?php echo $ms_catalog_sellers_heading; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
   <?php if (isset($success) && $success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $ms_catalog_sellers_heading; ?></h3>
      </div>
      <div class="panel-body">
		<?php echo $total_balance; ?><br /><br />
		<div class="table-responsive">
		<table class="table table-bordered table-hover" style="text-align: center" id="list-sellers">
			<thead>
				<tr>
					<td class="tiny"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
					<td><?php echo $ms_seller; ?></td>
					<td><?php echo $ms_catalog_sellers_email; ?></td>
					<td class="tiny"><?php echo $ms_catalog_sellers_total_products; ?></td>
					<td class="tiny"><?php echo $ms_catalog_sellers_total_sales; ?></td>
					<td class="small"><?php echo $ms_catalog_sellers_total_earnings; ?></td>
					<td class="medium"><?php echo $ms_catalog_sellers_current_balance; ?></td>
					<td class="medium"><?php echo $ms_catalog_sellers_status; ?></td>
					<td class="medium"><?php echo $ms_catalog_sellers_date_created; ?></td>
					<td class="large"><?php echo $ms_action; ?></td>
				</tr>
				<tr class="filter">
					<td></td>
					<td><input type="text" name="search_seller" class="search_init" /></td>
					<td><input type="text" name="filter_email" /></td>
					<td><input type="text" name="filter_total_products" /></td>
					<td><input type="text" name="filter_total_sales" /></td>
					<td><input type="text" name="filter_total_earnings" /></td>
					<td><input type="text" name="filter_balance" /></td>
					<td></td>
					<td><input type="text" name="filter_date_created" /></td>
					<td></td>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		</div>
	</div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#list-sellers').dataTable( {
		"sAjaxSource": "index.php?route=multiseller/seller/getTableData&token=<?php echo $token; ?>",
		"aoColumns": [
			{ "mData": "checkbox", "bSortable": false },
			{ "mData": "seller" },
			{ "mData": "email" },
			{ "mData": "total_products" },
			{ "mData": "total_sales" },
			{ "mData": "total_earnings" },
			{ "mData": "balance" },
			{ "mData": "status" },
			{ "mData": "date_created" },
			{ "mData": "actions", "bSortable": false, "sClass": "text-right" }
		]
	} );

	$(document).on('click', '.ms-button-paypal', function() {
		var button = $(this);
		var seller_id = button.parents('tr').children('td:first').find('input:checkbox').val();
		$(this).hide().before('<a class="ms-button ms-loading" />');
		$.ajax({
			type: "POST",
			dataType: "json",
			url: 'index.php?route=multiseller/seller/jxPayBalance&seller_id='+ seller_id +'&token=<?php echo $token; ?>',
			complete: function(jqXHR, textStatus) {
				if (textStatus != 'success') {
					button.show().prev('.ms-loading').remove();
				}
			},
			success: function(jsonData) {
				if (jsonData.success) {
					$("<div style='display:none'>" + jsonData.form + "</div>").appendTo('body').children("form").submit();
				} else {
					button.show().prev('.ms-loading').remove();
				}
			}
		});
	});

	$(document).on('click', '.ms-button-delete', function() {
    	return confirm("<?php echo $this->language->get('text_confirm'); ?>");
	});
});
</script>
<?php echo $footer; ?> 