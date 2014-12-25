(function($){
    var body = $('body'),
        _window = $(window);

    window.sb_switch_loading = function (value, selector) {
        var loading = selector || $('div.loading');
        if(value) {
            loading.removeClass('hidden');
            loading.addClass('visible');
        } else {
            loading.addClass('hidden');
            loading.removeClass('visible');
        }
    };

    window.sb_is_ajax_has_data = function (response) {
        if(!response.trim() || parseInt(response) == 0) {
            return false;
        }
        return true;
    };

    window.sb_is_email = function(email) {
        var regex = '/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
        return regex.test(email);
    };

    window.sb_switch_button_ajax_loading = function(button, show) {
        var ajax_loading = button.find('img.ajax-loading');
        if(show) {
            ajax_loading.removeClass('hidden');
            ajax_loading.addClass('visible');
            ajax_loading.fadeIn();
        } else {
            ajax_loading.removeClass('visible');
            ajax_loading.addClass('hidden');
            ajax_loading.fadeOut();
        }
    };

    // Scroll top button
    (function(){
        var scrollToTop = $( '#sb-scroll-top'),
            scrollPositionShown = 100,
            currentPosition = _window.scrollTop();

        if ( scrollToTop.length ) {
            function showScrollTop() {
                scrollToTop.fadeIn();
            }

            if ( currentPosition > scrollPositionShown ) {
                showScrollTop();
            }

            _window.scroll( function() {
                if ( $( this ).scrollTop() > scrollPositionShown ) {
                    showScrollTop();
                } else {
                    scrollToTop.fadeOut();
                }
            });

            scrollToTop.click(function(){
                $('html, body').animate({scrollTop : 0},800);
                return false;
            });

        }
    })();

    // Mobile menu
    (function(){
        $('.mobile-menu-button').on('click', function(e){
            var that = $(this),
                mobile_menu_container = that.closest('div.sb-mobile-menu'),
                sb_site = body.find('div.sb-site');
            mobile_menu_container.toggleClass('active');
            if(mobile_menu_container.hasClass('right')) {
                sb_site.removeClass('move-right');
                sb_site.toggleClass('move-left');
            } else {
                sb_site.removeClass('move-left');
                sb_site.toggleClass('move-right');
            }
            sb_site.toggleClass('moved');
        });
    })();

    // Float ads
    (function(){
        var content_container = $('.sb-site'),
            container_width = content_container.width(),
            window_width = window.innerWidth,
            float_ads_left = $('.sb-float-ads.left'),
            float_ads_right = $('.sb-float-ads.right'),
            margin_number = 0,
            padding_number = 0;
        if((window_width - 20) <= container_width) {
            content_container = $('.sb-wrap.container');
            container_width = content_container.width();
        }
        margin_number = (container_width/2) + (float_ads_left.width() + parseInt(content_container.css('padding-left')) + parseInt(content_container.css('padding-right')));
        float_ads_left.css({'left': '50%', 'margin-left': '-' + margin_number + 'px'});
        float_ads_right.css({'right': '50%', 'margin-right': '-' + margin_number + 'px'});
    })();
})(jQuery);