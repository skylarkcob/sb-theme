window.sb_core = window.sb_core || {};

var sb_password_strength,
    sb_refresh,
    sb_resize_iframe,
    sb_ajax_loader;

(function($){

    var body = $('body'),
        _window = $(window);

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
        $('.sb-captcha .reload, .sb-captcha-image').on('click', function(e){
            e.preventDefault();

            var that = $(this),
                captcha = that.parent().find('.captcha-code'),
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
            $.post(sb_core_ajax.url, data, function(resp){
                if(sb_core.sb_is_ajax_has_data(resp)) {
                    captcha.attr('src', resp);
                    that.removeClass('disabled');
                    captcha.css({opacity: 1});
                }
            });
        });
    })();

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
                sb_site = body.find('div.sb-site'),
                sb_site_container = sb_site.find('.sb-site-container');
            mobile_menu_container.toggleClass('active');
            if(mobile_menu_container.hasClass('right')) {
                sb_site.removeClass('move-right');
                sb_site.toggleClass('move-left');
            } else {
                sb_site.removeClass('move-left');
                sb_site.toggleClass('move-right');
            }
            sb_site.toggleClass('moved');
            sb_site_container.toggleClass('moved');
        });

        $('.an-aside-mobile-menu > div').on('click', function(e){
            var that = $(this),
                sb_site = that.closest('div.sb-site'),
                sb_site_container = sb_site.find('.sb-site-container');
            sb_site.removeClass('moved move-left move-right');
            sb_site_container.removeClass('moved');
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
                        'action': 'sb_comment',
                        'comment_body': commentBody.val(),
                        'comment_name': commentName.val(),
                        'comment_email': commentEmail.val()
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
                        'action': 'sb_comment_like',
                        'comment_id': comment_id,
                        'session_key': session_key
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
                                'action': 'sb_login_page_change_email',
                                'id': user_id,
                                'email': email.val(),
                                'security': $('#security').val()
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
                                'action': 'sb_login_page_change_password',
                                'current_password': current_password.val(),
                                'new_password': new_password.val(),
                                're_new_password': re_new_password.val(),
                                'id': user_id,
                                'security': $('#security').val()
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
                                'action': 'sb_login_page_change_personal_info',
                                'user_name': user_name.val(),
                                'user_gender': user_gender.val(),
                                'user_birth_day': user_birth_day.val(),
                                'user_birth_month': user_birth_month.val(),
                                'user_birth_year': user_birth_year.val(),
                                'user_phone': user_phone.val(),
                                'user_identity': user_identity.val(),
                                'user_address': user_address.val(),
                                'id': user_id,
                                'security': $('#security').val()
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

        // Sign up form
        (function(){
            $('.sb-signup-form').on('submit', function(e){
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
                            var data = response;
                            if(data.valid == 1) {
                                hidden_fields.append(data.success_field);
                                that.submit();
                            } else {
                                errors.html(data.message);
                                errors.addClass('active');
                            }
                        }
                    });
                } else {
                    e.preventDefault();
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
                                        window.location.href = data.redirect;
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
                        'action': 'sb_login_page_verify_email',
                        'code': activation_code.val(),
                        'security': that.find('#security').val(),
                        'id': user_id.val()
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

})(jQuery);