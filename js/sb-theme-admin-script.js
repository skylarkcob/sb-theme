(function($){
    (function(){
        $('select.logo-type').on('change', function(e){
            var that = $(this),
                container = that.closest('td'),
                logo_text = container.find('div.logo-text');
            if(that.val() == 'text') {
                logo_text.fadeIn();
            } else {
                logo_text.fadeOut();
            }
        });
    })();

})(jQuery);