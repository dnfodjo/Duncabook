<?php echo $header; ?>
<div class="container ms-account-conversation">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
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
    <div id="content" class="<?php echo $class; ?> ms-account-order"><?php echo $content_top; ?>
		<h1><?php echo $ms_account_conversations_heading; ?></h1>

		<div class="table-responsive">
        <table class="list table table-bordered table-hover" id="list-conversations">
			<thead>
				<tr>
					<td><?php echo $ms_account_conversations_status; ?></td>
					<td><?php echo $ms_account_conversations_with; ?></td>
					<td><?php echo $ms_account_conversations_title; ?></td>
					<td><?php echo $ms_last_message; ?></td>
					<td class="small"><?php echo $ms_action; ?></td>
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
		$('#list-conversations').dataTable( {
			"sAjaxSource": $('base').attr('href') + "index.php?route=account/msconversation/getTableData",
			"aaSorting": [[ 3, "desc" ]],
			"aoColumns": [
				{ "mData": "icon", "bSortable": false },
				{ "mData": "with", "bSortable": false },
				{ "mData": "title" },
				{ "mData": "last_message_date" },
				{ "mData": "actions", "bSortable": false, "sClass": "text-center" }
			],
		});
	});
</script>

<?php echo $footer; ?>