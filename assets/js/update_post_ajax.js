jQuery(function($) {
	$('.custom_button.update-post').click(function() { 
		var request;
		var noErrors = true;
		var _this = this;
		
		$(this).siblings('.required').each(function(i, j) {
			if(!$(j).val()) {
				noErrors = false;
				return;
			}
		}).promise().done(function() {
			if(!noErrors) {
			   return;
			}
			if(request) {
				request.abort();
			}
			var dataToSend = {
				'action':$(_this).data('action'),
				'post_type':$(_this).data('post_type'),
				'post_fields':{
					'post_content':$(_this).siblings('input[name="post_content"], textarea[name="post_content"]').val(),
				},
			};

			request = $.post(ajax_admin_url.ajax_url, {
				'action':'update_post',
				'data':dataToSend,
				'dataType':'json',
			}, function(resp) {
				processUpdate(resp, _this);
			});
		});
	});
	
	function processUpdate(resp, button) {
		resp = $.parseJSON(resp);
		$(button).siblings('.message').html(resp.message)
			.addClass('message-' + (resp.status === 'error' ? 'red' : 'green'));
		if(resp.status === 'success') {
		   $(button).siblings('input[type="text"], textarea').val('');
		}
	}
	
});