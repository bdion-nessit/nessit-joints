jQuery(function($) {
    $(document).ready(function() {
        var request;
        $('.post-sort').click(function() {
            if(request) {
                request.abort();
            }
            var _this = this;
            $(this).toggleClass('asc');
            var dataToSend = {
                query_data:{
                    post_type:$(this).data('post_type'),
                    meta_key:$(this).data('meta_key'),
                    orderby:$(this).data('orderby'),
                    posts_per_page:$(this).data('posts_per_page'),
                    order:($(this).hasClass('asc') ? 'ASC' : 'DESC'),
                },
                callback:$(this).data('callback'),
            };
            request = $.post(ajax_admin_url.ajax_url, {
                action:'post_sort',
                data:dataToSend,
                dataType:'json',
            }, function(resp) {
                doPostSort(resp, _this);
            });
        });
        
        function doPostSort(resp, button) {
            resp = $.parseJSON(resp);
            if(resp.type === 'string') {
               $(button).siblings('.post-sort-content').html(resp.content);
            }
        }
        
    });
});