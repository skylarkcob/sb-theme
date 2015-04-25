(function($){
    function sb_theme_get_param_by_name(url, name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(url);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    // All form
    (function(){
        $('.sb-theme-login form').on('submit', function(e){
            var that = $(this),
                captcha_code = that.find('input.captcha-code'),
                valid = true;
            if(captcha_code.length && !$.trim(captcha_code.val())) {
                valid = false;
                captcha_code.focus();
            }
            if(!valid) {
                e.preventDefault();
            }
        })
    })();

    // Login form
    (function(){
        $('#loginform').on('submit', function(e){
            var that = $(this),
                user_login = that.find('#user_login'),
                user_password = that.find('#user_pass'),
                valid = true;
            if(!$.trim(user_login.val())) {
                user_login.focus();
                valid = false;
            } else if(!$.trim(user_password.val())) {
                user_password.focus();
                valid = false;
            }
            if(!valid) {
                e.preventDefault();
            }
        });
    })();

    // Register form
    (function(){
        $('#registerform').on('submit', function(e){
            var that = $(this),
                user_login = that.find('#user_login'),
                user_email = that.find('#user_email'),
                valid = true;
            if(!$.trim(user_login.val())) {
                user_login.focus();
                valid = false;
            } else if(!$.trim(user_email.val())) {
                user_email.focus();
                valid = false;
            }
            if(!valid) {
                e.preventDefault();
            }
        });
    })();

    // Lost password form
    (function(){
        $('#lostpasswordform').on('submit', function(e){
            var that = $(this),
                user_login = that.find('#user_login'),
                valid = true;
            if(!$.trim(user_login.val())) {
                user_login.focus();
                valid = false;
            }
            if(!valid) {
                e.preventDefault();
            }
        });
    })();

    // Add action class for link
    (function(){
        $('#nav > a').each(function(i, el){
            var that = $(this),
                action = sb_theme_get_param_by_name(that.attr('href'), 'action');
            that.addClass(action);
        });
    })();

    // Change captcha image
    (function(){
        $('img.sb-captcha-image').on('click', function(e){
            e.preventDefault();

            var that = $(this),
                captcha = that,
                data = null;
            if(that.hasClass('disabled')) {
                return;
            }
            captcha.css({opacity: 0.2});
            data = {
                'action': 'sb_reload_captcha',
                len: captcha.attr('data-len')
            };
            that.addClass('disabled');
            $.post(sb_theme_login.ajax_url, data, function(resp){
                if($.trim(resp)) {
                    captcha.attr('src', resp);
                    that.removeClass('disabled');
                    captcha.css({opacity: 1});
                }
            });
        });
    })();

    // Social login
    (function(){
        $('.login .btn-social').on('click', function(e){
            var that = $(this),
                data_social = that.attr('data-social');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: sb_theme_login.ajax_url,
                data: {
                    action: 'sb_theme_login_social',
                    data_social: data_social
                },
                success: function(response){
                    if($.trim(response.url)) {
                        window.location.href = response.url;
                    } else {
                        alert(response.message)
                    }
                }
            });
        });
    })();
})(jQuery);