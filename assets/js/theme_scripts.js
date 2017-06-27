jQuery(function($) {
	$(document).ready(function() {
		function toggle_modal() {
    		var elemID = $(this).attr('id');
    		var modal = $('#' + elemID + '-modal');
    		if(modal.length) {
    			modal.show();
    		}
    	}

	    $('.custom_button').click(toggle_modal);

	    $('.modal-content .close-x').click(function() {
	      $(this).parent().parent().hide();
	    });
	    $('.modal-background').click(function() {
	      $(this).parent().hide();
	    });

	    $('.hamburger-menu-wrap').click(function() {
	    	$(this).parent().toggleClass('active');
	    });
	    
	});
});