var canCheckSticky = true;

function slideDetails(j) {
	var info = j.siblings('.slider-info');
	info.find('.slide-title span').html(j.find('.multi-slide').first().data('title'));
	info.find('.slide-description span').html(j.find('.multi-slide').first().data('caption'));
	j.find('.multi-slide').click(function() {
		info.find('.slide-title span').html((jQuery(this).data('title') ? jQuery(this).data('title') : ""));
		info.find('.slide-description span').html((jQuery(this).data('caption') ? jQuery(this).data('caption') : ""));
	});
}
function slideFeatured(j) {
	j.parent().parent().siblings('.slider-featured').css('background-image', j.find('.multi-slide').first().css('background-image'));
	j.find('.multi-slide').click(function() {
		j.parent().parent().siblings('.slider-featured').css('background-image', jQuery(this).css('background-image'));
	});
}

jQuery(function($) {
	$(document).ready(function() {

		//-------Multi-Slider Functionality-------

		if($('.multi-slide').length) {
			$('.multi-slider-wrap').each(function(i, j) {
				var reviewsWidth = $(j).width();
				var curScroll = 0;
				var slideCount = $(j).find('.multi-slide').length;
				var canRotate = true;

				function getVisibleSlides() {
					switch(true) {
						/** case (window.innerWidth > 1024):
							return 4; */
						case (window.innerWidth > 767):
							return 3;
						case (window.innerWidth > 640):
							return 2;
						default: 
							return 1;
					}
				}

				var visibleSlides = getVisibleSlides();

				function initializeReviews() {
					var sliderHeight = 0;
					$(j).find('.multi-slider').height('');
					$(j).find('.multi-slide').each(function(k, l) {
						$(l).outerWidth(Math.max((reviewsWidth / visibleSlides), (reviewsWidth / $(j).find('.multi-slide').length)) - 8 + 'px');
						sliderHeight = Math.max(sliderHeight, $(l).outerHeight());
					});
					$(j).height(sliderHeight + 'px');
				}
				function adjustSlides() {
					var oldWidth = reviewsWidth;
					var oldVisible = visibleSlides;
					reviewsWidth = $(j).width();
					visibleSlides = getVisibleSlides();
					initializeReviews();
					$(j).scrollLeft(curScroll * (reviewsWidth / oldWidth) * (oldVisible / visibleSlides));
					curScroll = $(j).scrollLeft();
					//$('.multi-slider-wrap').scrollLeft(($('.multi-slider-wrap').scrollLeft() + reviewsWidth - oldWidth) * (reviewsWidth / oldWidth));
				}
				
				initializeReviews();
				
				if($(j).parent().parent().siblings('.slider-featured').length) {
					slideFeatured($(j));
				}
				
				if($(j).siblings('.slider-info').length) {
					slideDetails($(j));
				}
				else {
					console.log('fail');
				}
				
				$(j).on('initialize', function() {
					adjustSlides();
				});
				$(window).resize(function() {
					adjustSlides();
				});
				$(j).find('.multi-prev').click(function() {
					var slideWidth = $(j).find('.multi-slide').first().outerWidth() + 8;
					var oldScroll = $(j).scrollLeft();
					var newScroll = oldScroll - slideWidth;
					if(!canRotate) {
						setTimeout(function() {
							$(j).find('.multi-prev').trigger('click');
						}, 50);
					}
					else {
						canRotate = false;
						if(newScroll < -5) {
							$(j).animate({'scrollLeft' : ((slideWidth * slideCount) - reviewsWidth)}, 350, function() {
								curScroll = $(j).scrollLeft();
								canRotate = true;
							});
						}
						else {
							$(j).animate({'scrollLeft' : newScroll}, 350, function() {
								curScroll = $(j).scrollLeft();
								canRotate = true;
							});
						}
					}
				});
				$(j).find('.multi-next').click(function() {
					var slideWidth = $(j).find('.multi-slide').first().outerWidth() + 8;
					var oldScroll = $(j).scrollLeft();
					var newScroll = oldScroll + slideWidth;
					if(!canRotate) {
						setTimeout(function() {
							$(j).find('.multi-next').trigger('click');
						}, 50);
					}
					else {
						canRotate = false;
						if(newScroll > ((slideWidth * slideCount) - reviewsWidth + 5)) {
							$(j).animate({'scrollLeft' : 0}, 350, function() {
								curScroll = $(j).scrollLeft();
								canRotate = true;
							});
						}
						else {
							$(j).animate({'scrollLeft' : newScroll}, 350, function() {
								curScroll = $(j).scrollLeft();
								canRotate = true;
							});
						}
					}
					//$('.multi-slider-wrap').scrollLeft(oldScroll + slideWidth);
				});
			});
		}

	});
});