<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="ms-comment-page">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $ms_comments_heading; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $ms_comments_heading; ?></h3>
      </div>
      <div class="panel-body">
		<div class="table-responsive">
			<table class="list table table-bordered table-hover" style="text-align: center" id="list-comments">
			<thead>
				<tr>
					<td class="tiny"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
					<td class="large"><?php echo $ms_name; ?></a></td>
					<td class="medium"><?php echo $ms_product; ?></a></td>
					<td class="medium"><?php echo $ms_seller; ?></a></td>
					<td class="comment"><?php echo $ms_comments_comment; ?></a></td>
					<td class="medium"><?php echo $ms_date; ?></a></td>
					<td class="medium"><?php echo $ms_action; ?></a></td>
				</tr>

				<tr class="filter">
					<td></td>
					<td><input type="text"/></td>
					<td><input type="text"/></td>
					<td></td>
					<td><input type="text"/></td>
					<td></td>
					<td></td>
				</tr>
			</thead>
			<tbody></tbody>
			</table>
		</div>
	  </div>
	</div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('#list-comments').dataTable( {
		"sAjaxSource": "index.php?route=multiseller/comment/getTableData&token=<?php echo $token; ?>",
		"aoColumns": [
			{ "mData": "checkbox", "bSortable": false },
			{ "mData": "customer_name" },
			{ "mData": "product_name" },
			{ "mData": "seller_name", "bSortable": false, "sClass": "right" },
			{ "mData": "comment" },
			{ "mData": "date_created" },
			{ "mData": "actions", "bSortable": false, "sClass": "right" }
		],
        "aaSorting":  [[5,'desc']]
	});
	
	$(document).on('click', '.ms-button-delete', function() {
		var comment_id = $(this).parents('tr').children('td:first').find('input:checkbox').val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: 'index.php?route=multiseller/comment/jxDelete&comment_id='+ comment_id +'&token=<?php echo $token; ?>',
			beforeSend: function() {
				$('.warning').text('').hide();
			},
			complete: function(jqXHR, textStatus) {
				//console.log(textStatus);
				window.location.reload();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('.warning').text(textStatus).show();
			},				
			success: function(jsonData) {
				window.location.reload();
			}
		});
	});
});
</script>

<?php echo $footer; ?> 