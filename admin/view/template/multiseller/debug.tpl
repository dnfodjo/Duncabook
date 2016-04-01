<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $ms_debug_heading; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exclamation-triangle"></i> <?php echo $ms_debug_info; ?></h3>
      </div>
      <div class="panel-body">
        <textarea wrap="off" rows="15" readonly class="form-control" style="white-space: normal">
		Version information:
		====================
		OpenCart: <?php echo VERSION; ?>

		vQmod: <?php echo VQMod::$_vqversion; ?>

		Theme: <?php echo $this->config->get('config_template'); ?>

		MultiMerch: <?php echo $this->MsLoader->appVer; ?> / <?php echo $this->MsLoader->dbVer; ?>


		Installed extensions:
		====================
		<?php foreach($installed_extensions as $e) { ?>
			<?php echo strip_tags($e['name']); ?> <?php echo $e['version']; ?>

		<?php } ?>

		Other extensions:
		====================
		<?php foreach($other_extensions as $e) { ?>
			<?php echo strip_tags($e['name']); ?> <?php echo $e['version']; ?>

		<?php } ?>

		Error log (last 50 lines):
		====================
		<?php echo($error_log); ?>


		Latest vQmod log (last 150 lines):
		====================
		<?php echo($vqmod_log); ?>

        </textarea>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>