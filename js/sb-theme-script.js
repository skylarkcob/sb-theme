window.wp = window.wp || {};
window.sb_core = window.sb_core || {};
window.sb_theme = window.sb_theme || {};

var sb_password_strength,
    sb_refresh,
    sb_resize_iframe,
    sb_ajax_loader;

(function($){

    var body = $('body'),
        _window = $(window),
        file_frame = null,
        new_post_id = 0,
        old_post_id = '';

    sb_core.sb_receive_media_selected = function(file_frame) {
        $('.sb-options').attr('data-option-changed', 1);
        return file_frame.state().get('selection').first().toJSON();
    }

    sb_core.sb_is_image_url = function(url) {
        var result = true,
            extension = url.slice(-4);
        if(extension != '.png' && extension != '.jpg' && extension != '.gif' && extension != '.bmp'  && extension != 'jpeg') {
            result = false;
        }
        return result;
    }

    window.sb_is_array = function(variable){
        if((Object.prototype.toString.call(variable) === '[object Array]')) {
            return true;
        }
        return false;
    };

    sb_core.sb_refresh = function() {
        window.location.href = window.location.href;
    };

    sb_core.sb_ajax_loader = function(status) {
        var ajax_loader = $('div.sb-ajax-loader');
        if(status) {
            ajax_loader.addClass('active');
        } else {
            ajax_loader.removeClass('active');
        }
    };

    sb_theme.ajax_loader = function(status) {
        sb_core.sb_ajax_loader(status);
    };

    sb_theme.social_login_ajax = function(data_social) {
        sb_theme.ajax_loader(true);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: sb_theme.ajax_url,
            data: {
                action: 'sb_theme_login_social',
                data_social: data_social
            },
            success: function(response){
                sb_theme.ajax_loader(false);
                if($.trim(response.url)) {
                    window.location.href = response.url;
                } else {
                    alert(response.message)
                }
            }
        });
    };

    sb_core.sb_resize_iframe = function(obj, divisor, min_height) {
        divisor = divisor || 1;
        min_height = min_height || 100;
        var height = obj.contentWindow.document.body.offsetHeight;
        height /= divisor;
        $(obj).css({'height' : height + 'px', 'min-height' : min_height + 'px'});
    };

    sb_core.sb_password_strength = function($pass1, $pass2, $strengthResult, $submitButton, blacklistArray) {
        var pass1 = $pass1.val(),
            pass2 = $pass2.val(),
            strength = 0;
        if(!$.trim(pass1)) {
            return;
        }
        $submitButton.attr('disabled', 'disabled');
        $strengthResult.removeClass('short bad good strong');
        blacklistArray = blacklistArray.concat(wp.passwordStrength.userInputBlacklist());
        strength = wp.passwordStrength.meter(pass1, blacklistArray, pass2);
        switch(strength) {
            case 2:
                $strengthResult.addClass('bad').html(pwsL10n.bad);
                break;
            case 3:
                $strengthResult.addClass('good').html(pwsL10n.good);
                break;
            case 4:
                $strengthResult.addClass('strong').html(pwsL10n.strong);
                break;
            case 5:
                $strengthResult.addClass('short').html(pwsL10n.mismatch);
                break;
            default:
                $strengthResult.addClass('short').html(pwsL10n.short);
        }
        if (3 <= strength && pass1 == pass2) {
            $submitButton.removeAttr('disabled');
        }
        return strength;
    };

    window.sb_set_cookie = function(cname, cvalue, exmin) {
        var d = new Date();
        d.setTime(d.getTime() + (exmin * 60 * 1000));
        var expires = "expires=" + d.toGMTString(),
            my_cookies = cname + "=" + cvalue + "; " + expires + "; path=/";
        document.cookie = my_cookies;
    };

    window.sb_stop_mouse_wheel = function(e) {
        if(!e) {
            e = window.event;
        }
        if(e.preventDefault) {
            e.preventDefault();
        }
        e.returnValue = false;
    };

    window.sb_number_format = function(number, separator, currency) {
        currency = currency || ' ₫';
        separator = separator || ',';
        var number_string = number.toString(),
            decimal = '.',
            numbers = number_string.split('.'),
            number_len = 0,
            last = '',
            result = '';
        if(!window.sb_is_array(numbers)) {
            numbers = number_string.split(',');
            decimal = ',';
        }
        if(window.sb_is_array(numbers)) {
            number_string = numbers[0];
        }
        number_len = parseInt(number_string.length);
        last = number_string.slice(-3);
        if(number_len > 3) {
            result += separator + last;
        } else {
            result += last;
        }

        while(number_len > 3) {
            number_len -= 3;
            number_string = number_string.slice(0, number_len);
            last = number_string.slice(-3)

            if(number_len <= 3) {
                result = last + result;
            } else {
                result = separator + last + result;
            }
        }
        if(window.sb_is_array(numbers) && $.isNumeric(numbers[1])) {
            result += decimal + numbers[1];
        }
        result += currency;
        result = $.trim(result);
        return result;
    };

    // Add default class to external links
    (function(){
        $('a').filter(function() {
            return this.hostname && this.hostname !== location.hostname;
        }).addClass('external');
    })();

    // Change captcha image
    (function(){
        $('.sb-captcha .reload, img.sb-captcha-image').on('click', function(e){
            e.preventDefault();

            var that = $(this),
                captcha = that.parent().find('.captcha-code'),
                data = null;
            if(that.hasClass('captcha-code')) {
                that = captcha;
            }
            if(that.hasClass('disabled')) {
                return;
            }
            captcha.css({opacity: 0.2});
            data = {
                action: 'sb_theme_change_captcha',
                len: captcha.attr('data-len')
            };
            that.addClass('disabled');
            $.post(sb_core_ajax.url, data, function(resp){
                if($.trim(resp)) {
                    captcha.attr('src', resp);
                    that.removeClass('disabled');
                    captcha.css({opacity: 1});
                }
            });
        });
    })();

    sb_theme.scroll_to_position = function(pos, time) {
        time = time || 1000;
        $('html, body').stop().animate({scrollTop: pos}, time);
    };


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

    sb_theme.go_to_top = function() {
        sb_theme.scroll_to_position(0);
        return false;
    };

    // Scroll top button, di chuyển về đầu trang
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
                sb_theme.go_to_top();
            });

        }
    })();

    // Mobile menu
    (function(){
        $('.mobile-menu-button').on('click', function(e){
            var that = $(this),
                mobile_menu_container = that.closest('div.sb-mobile-menu'),
                sb_site = body.find('div.sb-site'),
                sb_site_container = sb_site.find('.sb-site-container'),
                is_left = true;
            mobile_menu_container.toggleClass('active');
            if(mobile_menu_container.hasClass('right')) {
                sb_site_container.css({left: 'auto'});
                is_left = false;
            } else {
                sb_site_container.css({right: 'auto'});
                is_left = true;
            }
            if(is_left) {
                sb_site.removeClass('move-left');
                sb_site.toggleClass('move-right');
            } else {
                sb_site.removeClass('move-right');
                sb_site.toggleClass('move-left');
            }
            sb_site.toggleClass('moved');
            sb_site_container.toggleClass('moved');
            if(!is_left) {
                if(sb_site_container.hasClass('moved')) {
                    sb_site_container.css({right: '250px'});
                } else {
                    sb_site_container.css({right: '0'});
                }
            } else {
                if(sb_site_container.hasClass('moved')) {
                    sb_site_container.css({left: '250px'});
                } else {
                    sb_site_container.css({left: '0'});
                }
            }
        });

        $('.an-aside-mobile-menu > div').on('click', function(e){
            var that = $(this),
                sb_site = that.closest('div.sb-site'),
                sb_site_container = sb_site.find('.sb-site-container');
            sb_site.removeClass('moved move-left move-right');
            sb_site_container.removeClass('moved');
        });
    })();

    // Thêm nút mở rộng thu gọn cho submenu trên điện thoại
    (function(){
        $('.sb-mobile-menu .sf-menu li.menu-item-has-children').on('mouseover', function(e){
            e.preventDefault();
            return false;
        });
        $('.sb-mobile-menu .sf-menu li.menu-item-has-children').append('<i class="fa fa-plus icon-collapse-expand icon-expand"></i>');
        $('.sb-mobile-menu .sf-menu li.menu-item-has-children').find('.icon-collapse-expand').each(function(index, el){
            var icon_collapse_expand = $(el);
            icon_collapse_expand.on('click', function(e){
                var that = $(this),
                    list_item = that.parent();
                if(that.hasClass('active')) {
                    that.removeClass('fa-minus');
                    that.removeClass('icon-collapse');
                    that.addClass('fa-plus');
                    that.addClass('icon-expand');
                    list_item.find('.sub-menu:first').slideUp();
                } else {
                    that.removeClass('fa-plus');
                    that.removeClass('icon-expand');
                    that.addClass('fa-minus');
                    that.addClass('icon-collapse');
                    list_item.find('.sub-menu:first').slideDown();
                }
                that.toggleClass('active');
            });
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

    // SB Comment
    (function(){
        (function(){
            $('#respond').on('submit', function(event){
                var commentBody = $(this).find('textarea'),
                    commentName = $(this).find('#author'),
                    commentEmail = $(this).find('#email'),
                    mathCaptcha = $(this).find('#mc-input'),
                    data = {
                        action: 'sb_comment',
                        comment_body: commentBody.val(),
                        comment_name: commentName.val(),
                        comment_email: commentEmail.val()
                    };

                if((commentBody.length && !commentBody.val().trim()) || (commentName.length && !commentName.val().trim()) || (commentEmail.length && !commentEmail.val().trim()) || (mathCaptcha.length && (!mathCaptcha.val().trim() || false == $.isNumeric(mathCaptcha.val())))) {
                    if(event.preventDefault) {
                        event.preventDefault();
                    } else {
                        event.returnValue = false;
                    }
                }
            });
        })();

        (function() {
            $('.comment-tools .comment-like').on('click', function (e) {
                var that = $(this),
                    data = null,
                    tool_box_container = that.closest('.comment-tools'),
                    like = parseInt(that.find('.count').text()),
                    comment_id = tool_box_container.attr('data-comment'),
                    session_key = that.attr('data-session-liked-key');
                if (!that.hasClass('disable')) {
                    data = {
                        action: 'sb_comment_like',
                        comment_id: comment_id,
                        session_key: session_key
                    };
                    $.post(sb_core_ajax.url, data, function (resp) {
                        resp = parseInt(resp);
                        if (1 == resp) {
                            that.addClass('disable');
                            like++;
                            that.find('.count').text(like);
                            $.session.set(session_key, 1);
                        }
                    });
                }
            });
            $('.comment-tools .comment-report').on('click', function (e) {
                var that = $(this);
            });
            $('.comment-tools .comment-share').on('click', function (e) {
                var that = $(this),
                    share_item_container = that.find('.list-share');
                share_item_container.toggleClass('active');
                share_item_container.find('i').fadeIn();
            });
            $('.comment-tools .list-share > i').on('click', function (e) {
                var that = $(this),
                    share_item_container = that.closest('span');
                share_item_container.find('i').fadeOut();
                window.open(that.attr('data-url'), 'ShareWindow', 'height=450, width=550, toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
            });
        })();
    })();

    // SB Paginate
    (function(){
        $("nav.sb-paginate a.paginate-item").on("click", function(){
            $(this).css({"opacity": "0.5"});
        });
    })();

    // SB Login Page
    (function(){
        // Hide form error message
        (function(){
            $('.sb-login-form input, .sb-signup-form input, .sb-lost-password-form input').on('keyup', function(e){
                e.preventDefault();
                var that = $(this),
                    sb_form = that.closest('form');
                sb_form.find('.errors').removeClass('active');
            });
            $('.sb-user-profile input').on('keyup', function(e){
                e.preventDefault();
                var that = $(this);
                that.parent().find('.notification').html('');
            });
        })();

        // Save button
        (function(){
            $('.sb-user-profile .btn-save').attr('disabled', 'disabled');
            $('.sb-user-profile').on('keyup', function(e){
                e.preventDefault();
                var that = $(this),
                    save_button = that.find('.btn-save');
                save_button.removeAttr('disabled');
            });
            $('.sb-user-profile select').on('change', function(e){
                e.preventDefault();
                var that = $(this),
                    user_profile = that.closest('div.sb-user-profile'),
                    save_button = user_profile.find('.btn-save');
                save_button.removeAttr('disabled');
            });
            $('.sb-user-profile .btn-save').on('click', function(e){
                e.preventDefault();
                var that = $(this),
                    content_inner = that.closest('div.content-inner'),
                    user_id = parseInt(that.attr('data-id')),
                    data = null,
                    valid = true;
                if(that.hasClass('save-account')) {
                    var email = content_inner.find('.user-email');
                    if(!$.trim(email.val())) {
                        email.focus();
                        valid = false;
                    }
                    if(valid) {
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: sb_core_ajax.url,
                            data: {
                                action: 'sb_login_page_change_email',
                                id: user_id,
                                email: email.val(),
                                security: $('#security').val()
                            },
                            success: function(response){
                                var data = response;
                                if(data.updated == true) {
                                    window.location.href = window.location.href;
                                } else {
                                    email.parent().find('.notification').html(data.message);
                                }
                            }
                        });
                    }
                } else if(that.hasClass('btn-save-password')) {
                    var current_password = content_inner.find('.user-current-password'),
                        new_password = content_inner.find('.user-password'),
                        re_new_password = content_inner.find('.re-password');
                    valid = true;
                    if(!$.trim(new_password.val())) {
                        new_password.focus();
                        valid = false;
                    } else if(!$.trim(new_password.val())) {
                        new_password.focus();
                        valid = false;
                    } else if(!$.trim(re_new_password.val()) || re_new_password.val() != new_password.val()) {
                        re_new_password.focus();
                        valid = false;
                    }
                    if(valid) {
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: sb_core_ajax.url,
                            data: {
                                action: 'sb_login_page_change_password',
                                current_password: current_password.val(),
                                new_password: new_password.val(),
                                re_new_password: re_new_password.val(),
                                id: user_id,
                                security: $('#security').val()
                            },
                            success: function(response){
                                var data = response;
                                if(data.updated == true) {
                                    window.location.href = window.location.href;
                                } else {
                                    if(data.field == 'current_password') {
                                        current_password.focus();
                                        current_password.parent().find('.notification').html(data.message);
                                    } else if(data.field == 'new_password') {
                                        new_password.focus();
                                        new_password.parent().find('.notification').html(data.message);
                                    } else if(data.field == 're_new_password') {
                                        re_new_password.focus();
                                        re_new_password.parent().find('.notification').html(data.message);
                                    }
                                }
                            }
                        });
                    }
                } else if(that.hasClass('save-personal-info')) {
                    var user_name = content_inner.find('.user-name'),
                        user_gender = content_inner.find('.user-gender'),
                        user_birth_day = content_inner.find('.user-birth-day'),
                        user_birth_month = content_inner.find('.user-birth-month'),
                        user_birth_year = content_inner.find('.user-birth-year'),
                        user_phone = content_inner.find('.user-phone'),
                        user_identity = content_inner.find('.user-identity'),
                        user_address = content_inner.find('.user-address');
                    valid = true;
                    if(!$.trim(user_name.val())) {
                        user_name.focus();
                        valid = false;
                    } else if(!$.trim(user_phone.val())) {
                        user_phone.focus();
                        valid = false;
                    } else if(!$.trim(user_identity.val())) {
                        user_identity.focus();
                        valid = false;
                    } else if(!$.trim(user_address.val())) {
                        user_address.focus();
                        valid = false;
                    }
                    if(valid) {
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: sb_core_ajax.url,
                            data: {
                                action: 'sb_login_page_change_personal_info',
                                user_name: user_name.val(),
                                user_gender: user_gender.val(),
                                user_birth_day: user_birth_day.val(),
                                user_birth_month: user_birth_month.val(),
                                user_birth_year: user_birth_year.val(),
                                user_phone: user_phone.val(),
                                user_identity: user_identity.val(),
                                user_address: user_address.val(),
                                id: user_id,
                                security: $('#security').val()
                            },
                            success: function(response){
                                window.location.href = window.location.href;
                            }
                        });
                    }
                }
            });
        })();

        // Account page
        (function(){
            $('.sb-user-profile .current-password').on('keyup', function(e){
                var that = $(this),
                    current_password = that.find('input.user-current-password'),
                    content_inner = that.closest('div.content-inner');
                if(current_password.val().length > 0) {
                    content_inner.find('.main-password, .re-password').removeAttr('disabled');
                } else {
                    content_inner.find('.main-password, .re-password').attr('disabled', 'disabled');
                }
            })
        })();

        // Nút quan tâm bài viết
        (function(){
            $('.btn-interest .btn').on('click', function(e){
                var that = $(this),
                    container = that.parent(),
                    post_id = that.attr('data-post'),
                    interested = that.attr('data-interested'),
                    count = container.find('.count');
                that.addClass('disabled');
                interested++;
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: sb_core_ajax.url,
                    data: {
                        action: 'sb_theme_interest_post',
                        post_id: post_id,
                        interested: interested
                    },
                    success: function(response){
                        count.html(interested);
                        that.attr('data-interested', interested);
                    }
                });
            });
        })();

        // Sign up form
        (function(){
            $('.sb-signup-form').on('submit', function(e){
                e.preventDefault();
                var that = $(this),
                    full_name = that.find('.signup-fullname'),
                    email = that.find('.signup-email'),
                    phone = that.find('.signup-phone'),
                    address = that.find('.signup-address'),
                    password = that.find('.signup-password'),
                    re_password = that.find('.signup-password-2'),
                    errors = that.find('.errors'),
                    hidden_fields = that.find('.hidden-fields'),
                    success_field = that.find('.success-field'),
                    captcha_field = that.find('.signup-captcha'),
                    valid = true,
                    data = null;

                if(!$.trim(email.val())) {
                    email.focus();
                    valid = false;
                } else if(full_name.hasClass('must-have') && !$.trim(full_name.val())) {
                    full_name.focus();
                    valid = false;
                } else if(phone.hasClass('must-have') && !$.trim(phone.val())) {
                    phone.focus();
                    valid = false;
                } else if(address.hasClass('must-have') && !$.trim(address.val())) {
                    address.focus();
                    valid = false;
                } else if(!$.trim(password.val())) {
                    password.focus();
                    valid = false;
                } else if(!$.trim(re_password.val())) {
                    re_password.focus();
                    valid = false;
                } else if(re_password.val() != password.val()) {
                    re_password.focus();
                    valid = false;
                } else if(captcha_field.length && !$.trim(captcha_field.val())) {
                    captcha_field.focus();
                    valid = false;
                }
                if(valid) {
                    if(!success_field.length || parseInt(success_field.val()) != 1) {
                        e.preventDefault();
                    }
                    sb_core.sb_ajax_loader(true);
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: sb_core_ajax.url,
                        data: {
                            action: 'sb_login_page_signup',
                            email: email.val(),
                            password: password.val(),
                            name: full_name.val(),
                            phone: phone.val(),
                            address: address.val(),
                            security: that.find('#security').val(),
                            captcha: captcha_field.val()
                        },
                        success: function(response){
                            sb_core.sb_ajax_loader(false);
                            var data = response;
                            if(parseInt(data.valid) == 1) {
                                hidden_fields.append(data.success_field);
                                if($.trim(data.redirect)) {
                                    window.location.href = data.redirect;
                                }
                            } else {
                                errors.html(data.message);
                                errors.addClass('active');
                            }
                            if($.trim(data.captcha)) {
                                that.find('img.sb-captcha-image').attr('src', data.captcha);
                            }
                        }
                    });
                }
            });
        })();

        // Login form
        (function(){
            $('.sb-login-form').on('submit', function(e){
                var that = $(this),
                    login_email = that.find('.login-email'),
                    login_password = that.find('.login-password'),
                    redirect = that.find('.redirect'),
                    submit_button = that.find('.login-submit'),
                    login_remember = that.find('.login-remember'),
                    errors = that.find('.errors'),
                    data_valid = true;
                e.preventDefault();
                if(!$.trim(login_email.val())) {
                    login_email.focus();
                    data_valid = false;
                } else if(!$.trim(login_password.val())) {
                    login_password.focus();
                    data_valid = false;
                }
                if(data_valid) {
                    submit_button.addClass('disabled');
                    sb_theme.ajax_loader(true);
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: sb_core_ajax.url,
                        data: {
                            action: 'sb_login_page_login',
                            email: login_email.val(),
                            password: login_password.val(),
                            remember: login_remember.val(),
                            security: that.find('#security').val()
                        },
                        success: function(response){
                            sb_theme.ajax_loader(false);
                            var data = response;
                            if(data.logged_in == true) {
                                window.location.href = redirect.val();
                            } else {
                                // Fail
                                if($.trim(data.message)) {
                                    submit_button.removeClass('disabled');
                                    errors.html(data.message);
                                    errors.addClass('active');
                                    setTimeout(function(){
                                        //window.location.href = data.redirect;
                                    }, 3000);
                                } else {
                                    login_email.focus();
                                    submit_button.removeClass('disabled');
                                    errors.addClass('active');
                                }
                            }
                            if(data.logged_in == false && data.block_login == true) {
                                sb_core.sb_refresh();
                            }
                        }
                    });
                }
            });
        })();

        // Lost password form
        (function(){
            // Send activation code
            $('.sb-lost-password-form').on('submit', function(e){
                var that = $(this),
                    login_email = that.find('.login-email'),
                    redirect = that.find('.redirect'),
                    submit_button = that.find('.login-submit'),
                    errors = that.find('.errors'),
                    user_id = that.find('.user-id'),
                    data_valid = true;
                e.preventDefault();
                if(!$.trim(login_email.val())) {
                    login_email.focus();
                    data_valid = false;
                }
                if(data_valid) {
                    submit_button.addClass('disabled');
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: sb_core_ajax.url,
                        data: {
                            action: 'sb_login_page_lost_password',
                            email: login_email.val(),
                            security: that.find('#security').val()
                        },
                        success: function(response){
                            var data = response;
                            if(data.user_id > 0) {
                                window.location.href = data.redirect;
                            } else {
                                // Fail
                                login_email.focus();
                                submit_button.removeClass('disabled');
                                errors.addClass('active');
                            }
                        }
                    });
                }
            });

            // Verify activation code
            $('.sb-lost-password-form.verify').on('submit', function(e){
                var that = $(this),
                    redirect = that.find('.redirect'),
                    submit_button = that.find('.login-submit'),
                    activation_code = that.find('.activation-code'),
                    errors = that.find('.errors'),
                    user_id = that.find('.user-id'),
                    data_valid = true;
                e.preventDefault();
                if(!$.trim(activation_code.val())) {
                    activation_code.focus();
                    data_valid = false;
                }
                if(data_valid) {
                    submit_button.addClass('disabled');
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: sb_core_ajax.url,
                        data: {
                            action: 'sb_login_page_verify_activation_code',
                            code: activation_code.val(),
                            user_id: user_id.val(),
                            security: that.find('#security').val()
                        },
                        success: function(response){
                            var data = response;
                            if(data.valid == true) {
                                window.location.href = data.redirect;
                            } else {
                                // Fail
                                activation_code.focus();
                                submit_button.removeClass('disabled');
                                errors.addClass('active');
                            }
                        }
                    });
                }
            });

            // Update new password
            $('.sb-lost-password-form.reset').on('submit', function(e){
                var that = $(this),
                    redirect = that.find('.redirect'),
                    submit_button = that.find('.login-submit'),
                    activation_code = that.find('.query-code'),
                    errors = that.find('.errors'),
                    user_id = that.find('.user-id'),
                    reset_password = that.find('.reset-password'),
                    re_reset_password = that.find('.reset-password-2'),
                    data_valid = true,
                    data = null;
                e.preventDefault();
                if(!$.trim(reset_password.val())) {
                    reset_password.focus();
                    data_valid = false;
                } else if(!$.trim(re_reset_password.val()) || re_reset_password.val() != reset_password.val()) {
                    re_reset_password.focus();
                    data_valid = false;
                }
                if(data_valid) {
                    submit_button.addClass('disabled');
                    data = {
                        action: 'sb_login_page_reset_password',
                        code: activation_code.val(),
                        password: reset_password.val(),
                        user_id: user_id.val(),
                        security: that.find('#security').val()
                    };
                    $.post(sb_core_ajax.url, data, function(resp){
                        resp = $.parseJSON(resp);
                        if(resp.updated) {
                            window.location.href = resp.redirect;
                        }
                    });
                }
            });

            // Check password strength
            $('body').on('keyup', function(e) {
                var that = $(this),
                    reset_password_form = that.find('.sb-lost-password-form.reset');
                if(!reset_password_form.length) {
                    e.preventDefault();
                    return false;
                }
                var reset_password = reset_password_form.find('.reset-password'),
                    re_reset_password = reset_password_form.find('.reset-password-2'),
                    reset_password_strength = reset_password_form.find('.reset-password-strength'),
                    reset_password_submit = reset_password_form.find('.login-submit'),
                    reset_password_black_list = ['admin'];
                sb_core.sb_password_strength(reset_password, re_reset_password, reset_password_strength, reset_password_submit, reset_password_black_list);
            });
        })();

        // Verify email
        (function(){
            $('.sb-verify-email-form').on('submit', function(e){
                e.preventDefault();
                var that = $(this),
                    activation_code = that.find('.activation-code'),
                    valid = true,
                    user_id = that.find('.user-id'),
                    redirect = that.find('.redirect'),
                    data = null;
                if(!$.trim(activation_code.val())) {
                    activation_code.focus();
                    valid = false;
                }
                if(valid) {
                    data = {
                        action: 'sb_login_page_verify_email',
                        code: activation_code.val(),
                        security: that.find('#security').val(),
                        id: user_id.val()
                    };
                    $.post(sb_core_ajax.url, data, function(resp){
                        resp = parseInt(resp);
                        if(1 == resp) {
                            window.location.href = redirect.val();
                        } else {
                            activation_code.focus();
                            that.find('.errors').addClass('active');
                        }
                    });
                }
            });
        })();

        // Check password strength
        (function(){
            $('body').on('keyup', function(e) {

                var that = $(this),
                    main_password = that.find('.main-password'),
                    re_password = that.find('.re-password'),
                    indicator = that.find('.password-meter'),
                    submit_button = that.find('.btn-save-password'),
                    black_list = ['admin'];
                sb_core.sb_password_strength(main_password, re_password, indicator, submit_button, black_list);
            });
        })();
    })();

    // Đăng nhập mạng xã hội
    (function(){
        $('.sb-login-form .btn-social').on('click', function(e){
            var that = $(this),
                data_social = that.attr('data-social');
            sb_theme.social_login_ajax(data_social);
        });
    })();

    // Lựa chọn địa giới hành chính
    (function(){
        $('.administrative-boundaries .sb-term-field select').on('change', function(e){
            e.preventDefault();
            var that = $(this),
                term = parseInt(that.val()),
                container = that.closest('.administrative-boundaries'),
                taxonomy = that.attr('data-taxonomy'),
                district = container.find('.sb-term-field select[name="sbmb_district"]'),
                ward = container.find('.sb-term-field select[name="sbmb_ward"]'),
                hamlet = container.find('.sb-term-field select[name="sbmb_hamlet"]'),
                street = container.find('.sb-term-field select[name="sbmb_street"]');
            switch (taxonomy) {
                case 'province':
                    ward.find('option:not(:first)').remove();
                    hamlet.find('option:not(:first)').remove();
                    street.find('option:not(:first)').remove();
                    break;
                case 'district':
                    hamlet.find('option:not(:first)').remove();
                    break;
                case 'ward':
                    hamlet.find('option:not(:first)').remove();
                    break;
                case 'hamlet':
                    break;
            }
            if(term >= 0) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: sb_theme.ajax_url,
                    data: {
                        action: 'sb_theme_administrative_boundaries_change',
                        term: term,
                        taxonomy: taxonomy
                    },
                    success: function(response){
                        if(response.successful) {
                            if($.trim(response.html_data)) {
                                switch (taxonomy) {
                                    case 'province':
                                        district.html(response.html_data);
                                        if(0 == term) {
                                            ward.find('option:not(:first)').remove();
                                            hamlet.find('option:not(:first)').remove();
                                            street.find('option:not(:first)').remove();
                                        }
                                        break;
                                    case 'district':
                                        ward.html(response.html_data);
                                        if($.trim(response.html_street)) {
                                            street.html(response.html_street);
                                        }
                                        if(0 == term) {
                                            hamlet.find('option:not(:first)').remove();
                                        }
                                        break;
                                    case 'ward':
                                        hamlet.html(response.html_data);
                                        if($.trim(response.html_street)) {
                                            street.html(response.html_street);
                                        }
                                        if(0 == term) {
                                            hamlet.find('option:not(:first)').remove();
                                        }
                                        break;
                                    case 'hamlet':
                                        break;
                                }
                            }
                        } else {
                            container.find('select').val(0);
                        }
                    }
                });
            }
        });
    })();

    // Thêm và xóa hình ảnh
    (function(){
        $('.sb-insert-media').on('click', function(e){
            e.preventDefault();
            var that = $(this);
            if(file_frame) {
                file_frame.uploader.uploader.param('post_id', new_post_id);
                file_frame.open();
                return;
            }
            file_frame = wp.media({title: 'Insert Media', button:{text: 'Use this image'}, multiple: false});
            file_frame.on('select', function(){
                var media_data = sb_core.sb_receive_media_selected(file_frame),
                    media_container = that.closest('.sb-media-upload'),
                    image_preview_container = media_container.find('.image-preview'),
                    image_input = media_container.find('input.image-url'),
                    media_id_input = media_container.find('input.media-id');
                image_input.val(media_data.url);
                image_input.attr('value', media_data.url);
                media_id_input.val(media_data.id);
                if(image_preview_container.length) {
                    image_preview_container.html('<img alt="" src="' + media_data.url + '">');
                    image_preview_container.addClass('has-image');
                }
                file_frame = null;
            });
            file_frame.open();
        });

        $('.sb-remove-media').on('click', function(e){
            e.preventDefault();
            var that = $(this),
                media_container = that.closest('.sb-media-upload');
            media_container.find('input').val('').attr('value', '');
            media_container.find('.image-preview').removeClass('has-image').html('');
        });

        $('.sb-media-upload .image-url').on('change input', function(e){
            e.preventDefault();
            var that = $(this),
                media_container = that.closest('.sb-media-upload'),
                image_preview_container = media_container.find('.image-preview'),
                image_text = that.val();
            if($.trim(image_text) && sb_core.sb_is_image_url(image_text)) {
                image_preview_container.html('<img alt="" src="' + image_text + '">');
                image_preview_container.addClass('has-image');
            } else {
                image_preview_container.html('');
                image_preview_container.removeClass('has-image');
            }
            media_container.find('.media-id').val(0);
        });
    })();

})(jQuery);