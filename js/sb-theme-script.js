(function($){
    var body = $('body'),
        _window = $(window);

    window.sb_switch_loading = function (value) {
        var loading = $('div.loading');
        if(value) {
            loading.removeClass('noShow');
            loading.addClass('show');
        } else {
            loading.addClass('noShow');
            loading.removeClass('show');
        }
    };

    window.sb_is_ajax_has_data = function (response) {
        if(!response.trim() || parseInt(response) == 0) {
            return false;
        }
        return true;
    };

    window.sb_is_email = function(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
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

    (function(){
        // Nút trở về đầu trang
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

})(jQuery);