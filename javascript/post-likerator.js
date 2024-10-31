(function($){
	$(document).ready(function(){
		var button = $('.post-likerator-button');

		if (button.length < 1){
			return false;
		}

		button.click(likePost);

		function likePost(){
			var clickedButton = $(this);

			var dataObj = {
				post_id: clickedButton.attr('data-post-id')
			};

			$.post(
				post_likerator.ajaxurl,
				{
					action: 'post_likerator_ajax',
					security: post_likerator_nonce,
					data: JSON.stringify(dataObj)
				},
				function(response){
					var parsed = '';
					try {
						parsed = JSON.parse(response);
					}catch(error){
						console.warn(error);
					}

					clickedButton.children('.post-likerator-count').text(parsed.likes);

					if (parsed.user_has_liked == true){
						clickedButton.addClass('user-has-liked');
					}else{
						clickedButton.removeClass('user-has-liked');
					}
				}
			);
		}
	});
})(jQuery);