<h2 id="comment-title"><?php echo $ms_comments_post_comment; ?></h2>

<form id="pcForm">
<b><?php echo $ms_comments_name; ?></b><br />
<input type="text" class="form-control" class="form-control" name="mc_name" value="<?php echo $mc_name; ?>" <?php if ($this->customer->isLogged() && $mscomm_comments_enforce_customer_data) { ?> disabled="disabled" <?php } ?> /><br />

<b><?php echo $ms_comments_email; ?></b><br />
<input type="text" class="form-control" name="mc_email" value="<?php echo $mc_email; ?>" <?php if ($this->customer->isLogged() && $mscomm_comments_enforce_customer_data) { ?> disabled="disabled" <?php } ?> /><br />

<b><?php echo $ms_comments_comment; ?></b>
<textarea id="mc_text" name="mc_text" cols="40" class="form-control" rows="8" <?php if ($mscomm_comments_maxlen > 0) echo "maxlength='$mscomm_comments_maxlen'" ?>></textarea>
<span style="font-size: 11px;"><?php echo $ms_comments_note; ?></span><br /> <br />

<?php if (!$this->customer->isLogged() || ($this->customer->isLogged() && $this->config->get('mscomm_comments_enable_customer_captcha'))) { ?>
<b><?php echo $ms_comments_captcha; ?></b><br />
<input type="text" class="form-control" name="mc_captcha" value="" />
<br />
<img src="index.php?route=tool/captcha" alt="" id="mc_captcha" /><br />
<br />
<?php } ?>
</form>
<div class="buttons">
  <div class="pull-right">
	<input class="btn btn-primary" type="submit" id="mc-submit" value="<?php echo $button_submit; ?>" />
  </div>
</div>

<script type="text/javascript">
	var ms_comments_product_id = <?php echo $product_id; ?>;
	var ms_comments_wait = '<?php echo $ms_comments_wait; ?>';
</script>