(function($){
    var body = $('body'),
        _window = $(window);
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