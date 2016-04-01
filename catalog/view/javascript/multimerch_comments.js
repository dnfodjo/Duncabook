$(function() {
	$(document).delegate("#tab-comments .links a", 'click', function() {
		var page = $(this).attr('href').match(/\d*$/);
		$('#tab-comments .pcComments').load($(this).attr('href'));
		return false;
	});

	$(document).delegate('#mc-submit:not(.disabled)', 'click', function() {
        if(typeof ms_comments_product_id !== 'undefined'){
            var url = $('base').attr('href') + 'index.php?route=module/multimerch_comments/submitComment&product_id='+ms_comments_product_id;
            var render_url = 'index.php?route=module/multimerch_comments/renderComments&product_id='+ms_comments_product_id;
        }else if(typeof ms_comments_seller_id !== 'undefined'){
            var url = $('base').attr('href') + 'index.php?route=module/multimerch_comments/submitComment&seller_id='+ms_comments_seller_id;
            var render_url = 'index.php?route=module/multimerch_comments/renderComments&seller_id='+ms_comments_seller_id;
        }
        
		$.ajax({
			type: "POST",
			dataType: "json",
			url: url,
			data: $('#pcForm').serialize(),
			beforeSend: function() {
				$('.pcForm .alert-success, .pcForm .alert-danger').remove();
				$('#mc-submit').addClass('disabled');
			},
			complete: function() {
				$('#mc-submit').removeClass('disabled');
			},
			success: function(jsonData) {
				if (!$.isEmptyObject(jsonData.errors)) {
					var errors = '';
					jQuery.each(jsonData.errors, function(index, item) {
					    errors += item + '<br>';
					});

					$('#comment-title').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>' + errors + '</div>');
				} else {
					$('#comment-title').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i>' + jsonData.success + '</div>');
					$('#tab-comments input[type="text"]:not(:disabled), #tab-comments textarea:not(:disabled)').val('');
					$('#tab-comments .pcComments').load(render_url);
				}
			}
		});
	});
	
	$('#mc_text[maxlength]').keyup(function(){
		var limit = parseInt($(this).attr('maxlength'));
		if($(this).val().length > limit){
			$(this).val($(this).val().substr(0, limit));
		}
	});
});
