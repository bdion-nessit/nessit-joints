function updateFavorites(data, button) {
	jQuery(button).siblings('.favorite-count').html(data.count + ' Favorites');
}
function initializeButtons() {
	jQuery(function($) {
		$('.custom_button').off('click', '', toggle_modal);
		$('.update-post').off('click', '', updatePost);
		$('.custom_button').on('click', '', toggle_modal);
		$('.update-post').on('click', '', updatePost);
	});
}

initializeButtons();
