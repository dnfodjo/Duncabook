<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="ms-shipping-methods-page">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		  <a href="<?php echo $insert; ?>" data-toggle="tooltip" class="btn btn-primary"><i class="fa fa-plus"></i></a>
		  <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" onclick="$('form').submit()" data-original-title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading; ?></h1>
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

	<?php if ($error_shipping) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_shipping; ?>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading; ?></h3>
      </div>
      <div class="panel-body">
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td style="width: 40px"><?php echo $ms_shipping_methods_column_id; ?></td>
							<td><?php echo $ms_shipping_methods_column_name; ?></td>
							<td style="text-align: center; width: 120px"><?php echo $ms_shipping_methods_column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($shipping_methods) { ?>
						<?php foreach ($shipping_methods as $shipping_method) { ?>
						<tr>
							<td style="text-align: center;"><?php if ($shipping_method['selected']) { ?>
								<input type="checkbox" name="selected[]" value="<?php echo $shipping_method['shipping_method_id']; ?>" checked="checked" />
								<?php } else { ?>
								<input type="checkbox" name="selected[]" value="<?php echo $shipping_method['shipping_method_id']; ?>" />
								<?php } ?></td>
							<td><?php echo $shipping_method['shipping_method_id']; ?></td>
							<td><?php echo $shipping_method['name']; ?></td>
							<td style="text-align: center">
								<a class="ms-button ms-button-edit" href="<?php echo $this->url->link('multiseller/shipping-method/update', 'token=' . $this->session->data['token'] . '&shipping_method_id=' . $shipping_method['shipping_method_id'], 'SSL'); ?>" title="<?php echo $button_edit; ?>"></a>
								<a class="ms-button ms-button-delete" href="<?php echo $this->url->link('multiseller/shipping-method/delete', 'token=' . $this->session->data['token'] . '&shipping_method_id=' . $shipping_method['shipping_method_id'], 'SSL'); ?>" title="<?php echo $button_delete; ?>"></a>
							</td>
						</tr>
						<?php } ?>
						<?php } else { ?>
						<tr>
							<td class="center" colspan="4"><?php echo $text_no_results; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</form>

        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
	</div>
  </div>
</div>

<?php echo $footer; ?>