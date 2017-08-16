jQuery(function($) {
    $(document).ready(function() {
		
		//Function to return a JSON of data to be used in AJAX calls
        function queryData(source) {
            return {
                query_data:{
                    post_type:$(source).data('post_type'),
                    meta_key:$(source).data('meta_key'),
                    orderby:$(source).data('orderby'),
                    posts_per_page:$(source).data('posts_per_page'),
                    s:$(source).data('s'),
                    order:($(source).hasClass('asc') ? 'ASC' : 'DESC'),
                },
                callback:$(source).data('callback'),
				taxonomy:$(source).data('taxonomy'),
				terms:$(source).data('terms'),
            };
        }
        
        var request; //Initialize request
		
		//Function for sorting posts
        $('.post-sort').click(function() {
			//If another request is being processed, abort it
            if(request) {
                request.abort();
            }
			
            var _this = this; //Store this as a variable to prevent scope issues
            $(this).toggleClass('asc')
                .siblings('.post-filter').toggleClass('asc'); //Toggle the filter between ASC and DESC order
            var dataToSend = queryData(this); //Get data to send
			
			//AJAX call
            request = $.post(ajax_admin_url.ajax_url, {
                action:'custom_post_query',
                data:dataToSend,
                dataType:'json',
            }, function(resp) {
                doCustomPostQuery(resp, _this);
            });
        });
        
		//Function for filtering posts
        $('.post-filter').on('input', function() {
            $(this).data('s', $(this).val())
                .siblings('.post-sort').data('s', $(this).val()); //Takes this's value and sets it in a data field for search values
			
			//If another request is being processed, abort it
            if(request) {
                request.abort();
            }
			
            var _this = this; //Store this as a variable to prevent scope issues
            var dataToSend = queryData(this); //Get data to send
			
			//AJAX Call
            request = $.post(ajax_admin_url.ajax_url, {
                action:'custom_post_query',
                data:dataToSend,
                dataType:'json',
            }, function(resp) {
                doCustomPostQuery(resp, _this);
            });
        });
        
        //Process the results of the query
        function doCustomPostQuery(resp, button) {
            resp = $.parseJSON(resp); //Convert response to a JSON
			
			//If the response content is a string, replace the current sort/filter block's content with it
            if(resp.type === 'string') {
               $(button).siblings('.post-sort-content').html(resp.content);
            }
			
			//Reset toggle_modal on click trigger because of new elements
			$('.custom_button').off('click', '', toggle_modal);
			$('.custom_button').on('click', '', toggle_modal);
        }
    });
});