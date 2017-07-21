jQuery(function($) {
    $(document).ready(function() {
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
            };
        }
        
        var request;
        $('.post-sort').click(function() {
            if(request) {
                request.abort();
            }
            var _this = this;
            $(this).toggleClass('asc')
                .siblings('.post-filter').toggleClass('asc');
            var dataToSend = queryData(this);
            request = $.post(ajax_admin_url.ajax_url, {
                action:'custom_post_query',
                data:dataToSend,
                dataType:'json',
            }, function(resp) {
                doCustomPostQuery(resp, _this);
            });
        });
        
        $('.post-filter').on('input', function() {
            $(this).data('s', $(this).val())
                .siblings('.post-sort').data('s', $(this).val());
            if(request) {
                request.abort();
            }
            var _this = this;
            var dataToSend = queryData(this);
            request = $.post(ajax_admin_url.ajax_url, {
                action:'custom_post_query',
                data:dataToSend,
                dataType:'json',
            }, function(resp) {
                doCustomPostQuery(resp, _this);
            });
        });
        
        
        function doCustomPostQuery(resp, button) {
            resp = $.parseJSON(resp);
            if(resp.type === 'string') {
               $(button).siblings('.post-sort-content').html(resp.content);
            }
        }
    });
});