<?php echo $header; ?>
<div class="container">
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
		<div id="content" class="ms-product <?php echo $class; ?> ms-account-profile"><?php echo $content_top; ?>
			<h1><?php echo $ms_account_sellerinfo_heading; ?></h1>
			<div class="row">
				<div class="col-sm-3">
					<ul>
					<?php if(isset($group_menu) && $group_menu){ ?>
						<?php foreach($group_menu as $link){ ?>
						<li><a href="<?php echo $link['link'];?>" title="<?php echo $link['name'];?>"><?php echo $link['name'];?></a></li>
						<?php }?>
					<?php }?>
					<ul>
				</div>
				<div class="col-sm-9">
					<form id="ms-sellerinfo" class="ms-form form-horizontal" method="POST">
						<div class="form-group required">
								<label class="col-sm-2 control-label"><?php echo $as_main_information_city; ?></label>
								<div class="col-sm-10">
									<input type="text" class="form-control"  name="main_address_city" value="<?php echo $main_address_city; ?>" />
								</div>
						</div>
						<div class="buttons">
							<div class="pull-right"><button type="submit" class="btn btn-primary" id="ms-submit-button"><?php echo $ms_button_save; ?></button></div>
						</div>
					</form>
				</div>
			</div>
		<?php echo $content_bottom; ?></div>
	<?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>