function toggle_modal() {
	var elemID = jQuery(this).attr('id');
    var modal = jQuery('#' + elemID + '-modal');
	if(jQuery(this).data('modal')) {
		modal = jQuery('.modal.' + jQuery(this).data('modal'));
	}
			
    if(modal.length) {
    	modal.show();
		modal.find('.multi-slider-wrap').trigger('initialize');
    }
   }

jQuery(function($) {
	$(document).ready(function() {

	    $('.custom_button').on('click', '', toggle_modal);

	    $('.modal-content .close-x').click(function() {
	      $(this).parent().parent().hide();
	    });
	    $('.modal-background').click(function() {
	      $(this).parent().hide();
	    });
		
	});
});