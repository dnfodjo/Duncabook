<?php echo $header; ?>
<div class="container ms-account-conversation-view">
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
    <div id="content" class="<?php echo $class; ?> ms-account-order"><?php echo $content_top; ?>
		<h1><?php echo $conversation['title']; ?></h1>

		<form id="ms-message-form" class="ms-form form-horizontal">
			<textarea class="form-control" input-rows="3" cols="50" name="ms-message-text" id="ms-message-text"><?php echo $ms_message_text; ?></textarea>

			<input type="hidden" name="conversation_id" value="<?php echo $conversation['conversation_id']; ?>" />

			<div class="buttons clearfix">
				<div class="pull-left"><button type="button" class="btn btn-default" id="ms-message-reply"><?php echo $ms_post_message; ?></a></div>
			</div>
		</form>

		<hr />

		<div class="ms-messages">
			<div class="ms-message-row ms-message-head">
				<div class="ms-message ms-message-sender">
					<?php echo $ms_sender; ?>
				</div>
				<div class="ms-message ms-message-text">
					<?php echo $ms_message; ?>
				</div>
				<div class="ms-message ms-message-date">
					<?php echo $ms_date; ?>
				</div>
			</div>
			<?php if (isset($messages)) { ?>
				<?php foreach ($messages as $message) { ?>
					<div class="ms-message-row">
						<div class="ms-message ms-message-sender">
							<?php echo ucwords($message['sender']); ?>
						</div>
						<div class="ms-message ms-message-text">
							<?php echo nl2br($message['message']); ?>
						</div>
						<div class="ms-message ms-message-date">
							<?php echo $message['date_created']; ?>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>

<?php echo $footer; ?>