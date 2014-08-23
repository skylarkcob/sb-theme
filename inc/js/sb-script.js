(function($){

    var body = $( 'body' ),
        _window = $( window );

    (function(){
        var wooProductImage = $(".wp-post-image");
        if(wooProductImage.length) {
            body.find(wooProductImage).each(function(){
                var that = $(this);
                that.on('click', function(){
                    setTimeout(function(){
                        $(".pp_pic_holder.pp_woocommerce").find(".ppt").removeAttr('style');
                    }, 500);
                });
            });
        }
    })();

    (function(){
        var mobileSidebar = $("div.sb-mobile-sidebar");
        mobileSidebar.css({"height": body.height()});
        $(".button-control i").on("click", function(){
            var that = $(this),
                mobileSidebar = that.closest("div.sb-mobile-sidebar"),
                dataCollapse = mobileSidebar.attr("data-collapse");

            if("collapse" == dataCollapse) {
                mobileSidebar.css({"left": "-240px"});
                mobileSidebar.attr("data-collapse", "collapsed");
            } else {
                mobileSidebar.css({"left": "0"});
                mobileSidebar.attr("data-collapse", "collapse");
            }
        });
    })();

    (function(){
        body.delegate("a.upload-dismiss-errors", "click", function(event){
            event.preventDefault();
            var that = $(this);
            that.closest("div.media-uploader-status").fadeOut();
        });
    })();

    (function(){
        var embedBox = $("div.file-embed");
        body.find(embedBox).each(function(){
            var that = $(this);
            that.bind("mousewheel", function(){
                return false;
            });
        });
    })();
	
    (function(){
        // Nút trở về đầu trang
        var scrollToTop = $( "#scroll-to-top"),
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
	
    (function(){
        // SB Tab Widget
        body.find(".sb-tab-widget").each(function(){
            var aTab = $(this), listTab = aTab.find("ul.list-tab"), tabContent = aTab.find("div.tab-content");
            tabContent.children("div.tab-item").each(function(){
                var widgetId = $(this).attr("id");
                $(this).find('a.tab-title').attr("href", "#" + widgetId).wrap('<li></li>').parent().detach().appendTo(listTab);
            });
            listTab.find("li:first-child").addClass("active");
            tabContent.find("div:first-child").addClass("active");
            if(listTab.is(":empty")) {
                listTab.closest("div").find("div.tab-content").css({"marginTop": 0});
            }

            aTab.find("a.tab-title").each(function(){
                var tabButton = $(this);
                tabButton.click(function(){
                    aTab.find("ul.nav > li").each(function(){
                        $(this).removeClass("active");
                    });
                    $(this).parent().addClass("active");
                    $(this).closest("div.sb-tab-widget").find("div.tab-content > div").each(function(){
                        $(this).removeClass("active");
                    });
                    $($(this).attr("href")).addClass("active");
                    return false;
                });
            });
        });
    })();
	
    (function(){
        $("#respond").on("submit", function(event){
            var commentBody = $(this).find("textarea"), commentName = $(this).find("#author"), commentEmail = $(this).find("#email"), mathCaptcha = $(this).find("#mc-input");
            if((commentBody.length && "" == commentBody.val()) || (commentName.length && "" == commentName.val()) || (commentEmail.length && "" == commentEmail.val()) || (mathCaptcha.length && ("" == mathCaptcha.val() || false == $.isNumeric(mathCaptcha.val())))) {
                if(event.preventDefault) {
                    event.preventDefault();
                } else {
                    event.returnValue = false;
                }
            }
        });
    })();
	
    (function(){
        var sbLogoAds = $("div.sb-logo-ads");
        if(sbLogoAds.length) {
            var sbLeaderboard = sbLogoAds.find("div.sb-ads");
            if(sbLeaderboard.length) {
                var marginTop = Math.abs(parseInt(sbLogoAds.height()/2)) - 45;
                sbLeaderboard.css({"marginTop": marginTop + "px"});
            }
        }
    })();
	
})(jQuery);

var addthis_config = addthis_config||{};
addthis_config.data_track_addressbar = false;
addthis_config.data_track_clickback = false;