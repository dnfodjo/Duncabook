<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="index.php?route=multiseller/attribute/create&token=<?php echo $token; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
      </div>
      <h1><?php echo $ms_attribute_heading; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $ms_attribute_heading; ?></h3>
      </div>
      <div class="panel-body">
		<div class="table-responsive">
		<table class="list mmTable table table-bordered table-hover" style="text-align: center" id="list-attributes">
			<thead>
			<tr>
				<td><?php echo $ms_name; ?></a></td>
				<td><?php echo $ms_type; ?></a></td>
				<td><?php echo $ms_sort_order; ?></a></td>
				<td><?php echo $ms_status; ?></a></td>
				<td><?php echo $ms_action; ?></a></td>
			</tr>
			<tr class="filter">
				<td><input type="text"/></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			</thead>
			<tbody></tbody>
		</table>
		</form>
      </div>
	</div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
$(function() {
	$('#list-attributes').dataTable( {
		"sAjaxSource": "index.php?route=multiseller/attribute/getTableData&token=<?php echo $token; ?>",
		"aoColumns": [
			{ "mData": "name" },
			{ "mData": "type" },
			{ "mData": "sort_order" },
			{ "mData": "status" },
			{ "mData": "actions", "bSortable": false, "sClass": "text-right" }
		]
	});
});
</script> 