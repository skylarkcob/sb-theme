window.wp = window.wp || {};
window.sb_core = window.sb_core || {};
window.sb_theme = window.sb_theme || {};

var sb_ajax_loader,
    sb_receive_media_selected,
    sb_option_form_submit = false;

(function($){

    var sb_option = $('div.sb-option'),
        option_form = $('#sb-options-form'),
        file_frame = null,
        new_post_id = 0,
        old_post_id = '',
        body = $('body');

    // Detect SB option form submit
    (function(){
        $('#sb-options-form').on('submit', function(e){
            sb_option_form_submit = true;
        });
    })();

    function sb_theme_make_sb_option_unsaved_message(selector) {
        var sb_options = selector.closest('div.sb-options');
        sb_options.attr('data-option-changed', 1);
    }

    window.sb_default_quick_tags = function() {
        QTags.addButton('hr', 'hr', '<hr>\n', '', 'w');
        QTags.addButton('dl', 'dl', '<dl>\n', '</dl>\n\n', 'w');
        QTags.addButton('dt', 'dt', '\t<dt>', '</dt>\n', 'w');
        QTags.addButton('dd', 'dd', '\t<dd>', '</dd>\n', 'w');
    };

    sb_core.sb_ajax_loader = function(status) {
        var ajax_loader = $('div.sb-ajax-loader');
        if(status) {
            ajax_loader.addClass('active');
        } else {
            ajax_loader.removeClass('active');
        }
    };

    sb_core.sb_receive_media_selected = function(file_frame) {
        $('.sb-options').attr('data-option-changed', 1);
        return file_frame.state().get('selection').first().toJSON();
    }

    sb_theme.receive_selected_media_items = function(file_frame) {
        return file_frame.state().get('selection');
    }

    window.sb_get_admin_post_type = function() {
        return $('input[name="post_type"]').val();
    };

    function sb_is_image_url(url) {
        var result = true,
            extension = url.slice(-4);
        if(extension != '.png' && extension != '.jpg' && extension != '.gif' && extension != '.bmp'  && extension != 'jpeg') {
            result = false;
        }
        return result;
    }

    function sb_is_url(text) {
        var url_regex = new RegExp('^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)');
        if(url_regex.test(text)) {
            return true;
        }
        return false;
    }

    // Cập nhật chiều cao cho iframe
    (function(){
        $('iframe').on('ready', function(e) {
            var that = $(this);
            alert(that.contents().find('body').attr('style'));
        });
    })();

    // Detect options changes
    (function(){
        $('.sb-theme.sb-options').find('.sb-remove-media, .reset-button, .sb-add-sidebar, .sb-icon-delete').on('click', function(e){
            sb_theme_make_sb_option_unsaved_message($(this));
        });

        $('.sb-theme.sb-options').find('.switch-button').on('click', function(e){
            var that = $(this);
            if(!that.hasClass('active')) {
                sb_theme_make_sb_option_unsaved_message(that);
            }
        });

        $('.sb-theme.sb-options').find('select, input[type="checkbox"], input[type="radio"]').on('change', function(e){
            sb_theme_make_sb_option_unsaved_message($(this));
        });

        $('.sb-theme.sb-options').find('textarea, input').on('input', function(e){
            var that = $(this);
            if(!that.hasClass('test-field')) {
                sb_theme_make_sb_option_unsaved_message(that);
            }
        });
        $(window).bind('beforeunload', function(e) {
            var sb_option_page = $('.sb-theme.sb-options');
            if(sb_option_page.length && parseInt(sb_option_page.attr('data-option-changed')) == 1 && !sb_option_form_submit) {
                return 'Are you sure?';
            }
        });
    })();

    // Test send mail SMTP
    (function(){
        $('.sb-option .test-smtp-mail').on('click', function(e){
            e.preventDefault();
            var that = $(this),
                container = that.parent(),
                input = container.find('input'),
                td = that.closest('td');
            if(!$.trim(input.val())) {
                input.focus();
                return false;
            }
            sb_core.sb_ajax_loader(true);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: sb_core_admin_ajax.url,
                data: {
                    action: 'sb_theme_test_smtp_mail',
                    email: input.val()
                },
                success: function(response){
                    sb_core.sb_ajax_loader(false);
                    input.val('');
                    if($.trim(response.message)) {
                        alert(response.message);
                    }
                    td.find('.test-mail-debug').html(response.debug);
                }
            });
        });
    })();

    // Tối ưu hóa cơ sở dữ liệu
    (function(){
        $('.sb-theme-optimize-database.sb-button').on('click', function(e){
            e.preventDefault();
            sb_core.sb_ajax_loader(true);
            var that = $(this),
                container = that.parent(),
                delete_revision = container.find('.delete-revision'),
                delete_auto_draft = container.find('.delete-auto-draft'),
                delete_spam_comment = container.find('.delete-spam-comment'),
                delete_trash = container.find('.delete-trash'),
                delete_transient = container.find('.delete-transient'),
                data = null;
            data = {
                action: 'sb_theme_optimize_database'
            };
            if(delete_revision.is(':checked')) {
                data.delete_revision = 1;
            }
            if(delete_auto_draft.is(':checked')) {
                data.delete_auto_draft = 1;
            }
            if(delete_spam_comment.is(':checked')) {
                data.delete_spam_comment = 1;
            }
            if(delete_trash.is(':checked')) {
                data.delete_trash = 1;
            }
            if(delete_transient.is(':checked')) {
                data.delete_transient = 1;
            }
            $.post(sb_core_admin_ajax.url, data, function(resp){
                sb_core.sb_ajax_loader(false);
            });
        });
    })();

    // Disable current tab clicked
    (function(){
        $('div.sb-option .section-item > a').on('click', function(e){
            var that = $(this),
                tab_item = that.parent(),
                tab_container = tab_item.parent();

            if(that.parent().hasClass('active')) {
                return false;
            } else {
                e.preventDefault();
                tab_container.find('.tab-item').removeClass('active');
                tab_item.addClass('active');
            }
            var data = {
                'action' : 'sb_theme_admin_sidebar_change'
            };
            $.post(sb_core_admin_ajax.url, data, function(resp){
                window.location.href = that.attr('href');
            });
        });
    })();

    // Hide save changes button for empty field
    (function(){
        if(!option_form.find('h3.setting-title').length && option_form.attr('data-page') != 'sb-options') {
            option_form.css({'display': 'none'});
        }
        if(option_form.find('div.sb-plugins').length) {
            option_form.find('p.submit').css({'display': 'none'});
            option_form.find('div.reset-button').css({'display': 'none'});
        }
    })();

    // Hide updated message
    (function(){
        setTimeout(function(){
            sb_option.find('div.updated').fadeOut(2000);
            var page_url = window.location.href;
            if(page_url.indexOf('settings-updated') >= 0 && sb_option.length) {
                page_url = page_url.slice(0, page_url.indexOf('&'));
                window.history.pushState('string', '', page_url);
            }
        }, 1000);
    })();

    // Function datetime picker
    (function(){
        $('input.sb-datetime').each(function(index, el){
            var that = $(el),
                datetime_format = that.attr('data-datetime-format'),
                min_date = that.attr('data-min-date'),
                max_date = that.attr('data-max-date'),
                options = {};
            if(!$.trim(datetime_format)) {
                datetime_format = 'dd/mm/yy';
            }
            options['dateFormat'] = datetime_format;
            if($.trim(min_date)) {
                options['minDate'] = min_date;
            }
            if($.trim(max_date)) {
                options['maxDate'] = max_date;
            }
            that.datepicker(options);
        });
    })();

    // Turn on or turn off switch button
    (function(){
        sb_option.find('label.switch-button').each(function(){
            var that = $(this);
            that.click(function(){
                that = $(this);
                var dataSwitch = 'on',
                    switchValue = 0,
                    currentDataSwitch = that.attr('data-switch'),
                    otherButton;

                if(dataSwitch == currentDataSwitch) {
                    dataSwitch = 'off';
                    switchValue = 1;
                }

                otherButton = that.closest('div.switch-options').find('[data-switch="' + dataSwitch + '"]');
                otherButton.removeClass('active');
                that.addClass('active');
                that.closest('div.switch-options').find('input').val(switchValue);
            });
        });
    })();

    // Load SB Plugins
    (function(){
        var sb_plugins = sb_option.find('div.sb-plugins');
        if(sb_plugins.length) {
            var plugin_name = sb_plugins.attr('data-plugin'),
                plugins = plugin_name.split(',');

            $.each(plugins, function(index, value){
                var data = {
                    'action': 'sb_plugins',
                    'sb_plugin_slug': value
                };
                $.post(sb_core_admin_ajax.url, data, function(response){
                    sb_plugins.find('.sb-plugin-list').prepend(response);
                });
            });
            $(document).ajaxStop(function() {
                sb_plugins.find('.sb-ajax-load').fadeOut();
            });
        }
    })();

    // Khởi tạo lại thiết lập mặc định
    (function(){
        sb_option.find('div.reset-button > span').on('click', function(){
            var that = $(this),
                data_page = that.parent(),
                option_page = data_page.parent().attr('data-page'),
                data = {
                    'action': 'sb_option_reset',
                    'sb_option_page': option_page
                };
            data_page = data_page.parent().attr('data-page')
            that.find('img').css({'display': 'inline-block'});
            $.post(sb_core_admin_ajax.url, data, function(response){
                var data_option = $.parseJSON(response),
                    option_form = that.parent();
                option_form = option_form.parent();
                if(typeof data_option == 'object') {
                    if(data_page == 'sb_paginate') {
                        option_form.find('input[name="sb_options[paginate][label]"]').val(data_option['label']);
                        option_form.find('input[name="sb_options[paginate][next_text]"]').val(data_option['next_text']);
                        option_form.find('input[name="sb_options[paginate][previous_text]"]').val(data_option['previous_text']);
                        option_form.find('input[name="sb_options[paginate][range]"]').val(data_option['range']);
                        option_form.find('input[name="sb_options[paginate][anchor]"]').val(data_option['anchor']);
                        option_form.find('input[name="sb_options[paginate][gap]"]').val(data_option['gap']);
                        option_form.find('select[name="sb_options[paginate][style]"]').val(data_option['style']);
                        option_form.find('select[name="sb_options[paginate][border_radius]"]').val(data_option['border_radius']);
                    }
                }
                that.find('img').css({'display': 'none'});
            });
        });
    })();

    // List sidebar option
    (function(){
        function append_new_sidebar(list) {
            var $li = $('<li class="ui-state-default sb-sidebar-custom"/>'),
                data_sidebar = parseInt(list.attr('data-sidebar')) + 1;
            $html = '<div class="sb-sidebar-line"><input type="text" name="' + list.attr('data-name') + '[' + data_sidebar + '][name]"><input type="text" name="' + list.attr('data-name') + '[' + data_sidebar + '][description]"><input type="text" name="' + list.attr('data-name') + '[' + data_sidebar + '][id]"></div>';
            list.attr('data-sidebar', data_sidebar);

            $html += '<img alt="" src="' + list.attr('data-icon-drag') + '" class="sb-icon-drag">';
            $html += '<img alt="" src="' + list.attr('data-icon-delete') + '" class="sb-icon-delete">';
            $li.attr('data-sidebar', data_sidebar);
            $li.html($html);
            list.append($li);
            list.parent().find('.sb-sidebar-count').val(data_sidebar);
            list.sortable('refresh');
        }
        if($('#sb-sortable-sidebar').length) {
            $('#sb-sortable-sidebar').sortable({
                cancel: ':input, .ui-state-disabled, .sb-icon-delete',
                placeholder: 'ui-state-highlight',
                stop: function(event, ui) {
                    sb_theme_make_sb_option_unsaved_message($(ui.item));
                }
            });
        }
        $('button.sb-add-sidebar').on('click', function(e){
            e.preventDefault();
            var sortable_list = $(this).parent().find('.ui-sortable');
            append_new_sidebar(sortable_list);
            return false;
        });
        sb_option.delegate('.sb-icon-delete', 'click', function(){
            var that = $(this),
                $li = that.parent(),
                $list = $li.parent(),
                has_value = false,
                data_sidebar = parseInt($list.attr('data-sidebar')) - 1;
            if(!$li.hasClass('sb-default-sidebar')) {
                $li.find('input').each(function(){
                    if('' != $(this).val()) {
                        has_value = true;
                    }
                });
                if(has_value && confirm($list.attr('data-message-confirm'))) {
                    that.parent().remove();
                    $list.attr('data-sidebar', data_sidebar);
                    $list.parent().find('.sb-sidebar-count').val(data_sidebar);
                } else {
                    that.parent().remove();
                    $list.attr('data-sidebar', data_sidebar);
                    $list.parent().find('.sb-sidebar-count').val(data_sidebar);
                }
            }
        });
    })();

    // Deactivate SB Core
    (function(){
        $('#sb-core span.deactivate > a').attr('href', 'javascript:;');
        $('#sb-core span.deactivate > a').on('click', function(){
            var that = $(this),
                data = null,
                deactivate_link = that.attr('href');
            data = {
                'action': 'sb_core_deactivate_message'
            };
            $.post(sb_core_admin_ajax.url, data, function(response){
                if(confirm(response)){
                    data = {
                        action: 'sb_deactivate_all_sb_product'
                    };
                    $.post(sb_core_admin_ajax.url, data, function(){
                        window.location = window.location.pathname;
                    });
                } else {
                    return false;
                }
            });
            return false;
        });
        if($('#sb-core').hasClass('inactive')) {
            var data = {
                action: 'sb_deactivate_all_sb_product'
            };
            $.post(sb_core_admin_ajax.url, data, function(response){

            });
        }
    })();

    // SB Core thickbox and update links
    (function(){
        $('#sb-core a.thickbox').each(function(i, el){
            var that = $(this),
                core_tr = that.closest('tr#sb-core'),
                core_update = null;
            that.attr('href', 'https://wordpress.org/plugins/sb-core/');
            that.attr('target', '_blank');
            if(core_tr.hasClass('update')) {
                core_update = core_tr.next();
                if(core_update.hasClass('plugin-update-tr')) {
                    core_update.fadeOut();
                    core_update.remove();
                    core_tr.children().each(function(i, el_th) {
                        $(el_th).css({'box-shadow': '0 -1px 0 rgba(0, 0, 0, 0.1) inset'});
                    });
                    core_update.find('a').each(function(i, el_child){
                        if($(this).hasClass('thickbox')) {
                            $(this).attr('href', 'https://wordpress.org/plugins/sb-core/');
                            $(this).attr('target', '_blank');
                            $(this).on('click', function(e){
                                e.preventDefault();
                                window.open($(this).attr('href'));
                                return false;
                            });
                        } else {
                            $.post(sb_core_admin_ajax.url, {'action': 'sb_core_get_admin_url', 'name': 'update-core.php'}, function(resp){
                                $(el_child).attr('href', resp);
                            });
                        }
                    });
                }
            }
        });
        $('#sb-core a.thickbox').on('click', function(e){
            e.preventDefault();
            var that = $(this);
            window.open(that.attr('href'));
            return false;
        });
    })();

    function sb_theme_sortable_update_result(ui_item) {
        var data = '',
            that = ui_item,
            single_ui_order = null,
            sortable_connect_active = null,
            sortable_container = that.closest('div.sb-sortable'),
            sortable_active_list = sortable_container.find('.sb-sortable-list.active-sortable'),
            sortable_source_list = sortable_container.find('.sb-sortable-list.sortable-source');
        if(!sortable_container.length) {
            sortable_container = that.parent();
            sortable_container = sortable_container.parent();
            sortable_active_list = sortable_container.find('.sb-sortable-list.active-sortable');
            sortable_source_list = sortable_container.find('.sb-sortable-list.sortable-source');
        }
        if(sortable_active_list.children().length < sortable_source_list.children().length) {
            if(!sortable_active_list.hasClass('sortable-row')) {
                sortable_active_list.css({'height': sortable_source_list.height()});
            }
        } else {
            sortable_active_list.css({'height': 'auto'});
        }
        single_ui_order = sortable_container.find('input.ui-item-order');
        sortable_connect_active = sortable_container.find('input.active-sortable-value');
        if(single_ui_order.length) {
            sortable_container.find('ul.sb-sortable-list li').each(function(i, el){
                var p = $(el).find('.ui-item-id').val();
                data += p + ',';
            });
            data = data.slice(0, -1);
            single_ui_order.val(data);
        }
        if(sortable_connect_active.length) {
            data = '';
            sortable_container.find('ul.sb-sortable-list.active-sortable li').each(function(i, el){
                var p = $(el).attr('data-term');
                if(typeof p === 'undefined') {
                    p = $(el).attr('data-value');
                }
                if(data.indexOf(p) != -1) {
                    return;
                } else {
                    data += p + ',';
                }
            });
            data = data.slice(0, -1);
            sortable_connect_active.val(data);
        }
    }

    // Di chuyển đối tượng tới danh sách kích hoạt
    var sb_theme_sortable_move_to_include = function(selector) {
        var container = selector.closest('div'),
            sortable_source = null;
        sortable_source = container.find('.sb-sortable-list.click-to-connect.sortable-source');
        selector.appendTo(sortable_source)
            .live('click', function(){
                sb_theme_make_sb_option_unsaved_message(selector);
                sb_theme_sortable_move_to_exclude(selector);
                sb_theme_sortable_update_result($(this));
            });
    };

    // Di chuyển đối tượng tới danh sách nguồn
    var sb_theme_sortable_move_to_exclude = function(selector) {
        var container = selector.closest('div'),
            sortable_active = null;
        sortable_active = container.find('.sb-sortable-list.click-to-connect.active-sortable');
        selector.appendTo(sortable_active)
            .live('click', function(){
                sb_theme_make_sb_option_unsaved_message(selector);
                sb_theme_sortable_move_to_include(selector);
                sb_theme_sortable_update_result($(this));
            });
    };

    // Xử lý sự kiện khi người dùng nhấn chuột vào đối tượng trong danh sách kết nối
    (function(){
        $('.sb-sortable-list.click-to-connect.sortable-source > li').live('click', function(){
            var that = $(this);
            if(that.hasClass('ui-state-disabled')) {
                return false;
            }
            sb_theme_sortable_move_to_include(that);
        }).trigger('click');
        $('.sb-sortable-list.click-to-connect.active-sortable > li').live('click', function(){
            var that = $(this);
            if(that.hasClass('ui-state-disabled')) {
                return false;
            }
            sb_theme_sortable_move_to_exclude(that);
        }).trigger('click');
    })();

    // UI Sortable List
    (function(){
        $('ul.sb-sortable-list.active-sortable').each(function(i, el){
            var that = $(this),
                sortable_active_list = that,
                sortable_container = sortable_active_list.closest('div.sb-sortable'),
                sortable_source_list = null,
                sortable_source_list_height = 0,
                sortable_active_list_height = 0;
            if(!sortable_container.length) {
                sortable_container = sortable_active_list.parent();
            }
            if(sortable_active_list.length) {
                sortable_source_list = sortable_container.find('ul.sb-sortable-list.sortable-source')
                if(sortable_source_list.length) {
                    sortable_active_list_height = sortable_active_list.height();
                    sortable_source_list_height = sortable_source_list.height();
                    if(sortable_active_list_height < sortable_source_list_height) {
                        sortable_active_list.css({'height': sortable_source_list_height});
                    }
                }
            }
        });

        if(!$('ul.sb-sortable-list').hasClass('ui-sortable')) {
            var remove_item = false,
                sortable_container = null;
            if(!$('ul.sb-sortable-list').length) {
                return;
            }
            $('ul.sb-sortable-list').sortable({
                cancel: ':input, .ui-state-disabled, .sb-icon-delete',
                connectWith: '.connected-sortable',
                placeholder: 'ui-state-highlight',
                receive: function(event, ui) {
                    remove_item = false;
                },
                over: function(event, ui) {
                    remove_item = false;
                },
                out: function(event, ui) {
                    remove_item = true;
                },
                beforeStop: function(event, ui) {
                    var that = $(ui.item),
                        sortable_list = that.parent();
                    if(remove_item && sortable_list.hasClass('out-remove')) {
                        var ui_panel = ui.item.closest('div.sb-ui-panel'),
                            input_count = ui_panel.find('input.ui-item-count'),
                            count = parseInt(input_count.val());
                        count--;
                        input_count.val(count);
                        ui_panel.find('button.ui-add-item').attr('data-count', count);
                        ui.item.remove();
                    }
                },
                sort: function(event, ui) {
                    var that = $(this),
                        ui_state_highlight = that.find('.ui-state-highlight');
                    ui_state_highlight.css({'height': ui.item.outerHeight()});
                    if(that.hasClass('display-inline')) {
                        ui_state_highlight.css({'width': ui.item.outerWidth()});
                    }
                },
                stop: function(event, ui) {
                    var data = '',
                        that = $(ui.item),
                        single_ui_order = null,
                        sortable_connect_active = null,
                        sortable_container = that.closest('div.sb-sortable'),
                        sortable_active_list = sortable_container.find('.sb-sortable-list.active-sortable'),
                        sortable_source_list = sortable_container.find('.sb-sortable-list.sortable-source');
                    if(!sortable_container.length) {
                        sortable_container = that.parent();
                        sortable_container = sortable_container.parent();
                        sortable_active_list = sortable_container.find('.sb-sortable-list.active-sortable');
                        sortable_source_list = sortable_container.find('.sb-sortable-list.sortable-source');
                    }
                    if(sortable_active_list.children().length < sortable_source_list.children().length) {
                        if(!sortable_active_list.hasClass('sortable-row')) {
                            sortable_active_list.css({'height': sortable_source_list.height()});
                        }
                    } else {
                        sortable_active_list.css({'height': 'auto'});
                    }
                    single_ui_order = sortable_container.find('input.ui-item-order');
                    sortable_connect_active = sortable_container.find('input.active-sortable-value');
                    if(single_ui_order.length) {
                        sortable_container.find('ul.sb-sortable-list li').each(function(i, el){
                            var p = $(el).find('.ui-item-id').val();
                            data += p + ',';
                        });
                        data = data.slice(0, -1);
                        single_ui_order.val(data);
                    }
                    if(sortable_connect_active.length) {
                        data = '';
                        sortable_container.find('ul.sb-sortable-list.active-sortable li').each(function(i, el){
                            var p = $(el).attr('data-term');
                            if(typeof p === 'undefined') {
                                p = $(el).attr('data-value');
                            }
                            if(data.indexOf(p) != -1) {
                                return;
                            } else {
                                data += p + ',';
                            }
                        });
                        data = data.slice(0, -1);
                        sortable_connect_active.val(data);
                    }
                    sb_theme_make_sb_option_unsaved_message(that);
                }
            });
        }
    })();

    function sb_build_add_ui_data(button) {
        var that = button,
            data_name = that.attr('data-name'),
            data_count = that.attr('data-count'),
            data_type = that.attr('data-type'),
            ui_panel = that.closest('div.sb-ui-panel'),
            input_order = ui_panel.find('.ui-item-order'),
            next_id = button.attr('data-next-id'),
            order = input_order.val(),
            data = null;
        data = {
            action: 'sb_add_ui_item',
            data_name: data_name,
            data_count: data_count,
            data_type: data_type,
            data_id: next_id
        };
        data_count++;
        if(!order.trim()) {
            order += next_id;
        } else {
            order += ',' + next_id;
        }

        next_id++;
        button.attr('data-next-id', next_id);
        ui_panel.find('input.ui-item-count').val(data_count);
        input_order.val(order);
        button.attr('data-count', data_count);
        return data;
    }

    function sb_switch_reset_ajax(button, show) {
        if(show) {
            button.find('img').addClass('active');
        } else {
            button.find('img').removeClass('active');
        }
    }

    function sb_reset_ui_complete(button) {
        var ui_panel = button.closest('div.sb-ui-panel'),
            add_button = ui_panel.find('button.ui-add-item'),
            input_order = ui_panel.find('input.ui-item-order'),
            input_count = ui_panel.find('input.ui-item-count');
        button.closest('div').find('.sb-sortable-list').html('');
        input_order.val('');
        input_count.val(0);
        add_button.attr('data-count', 0);
        add_button.attr('data-next-id', 1);
        sb_switch_reset_ajax(button, false);
    }

    (function(){
        $('.sb-ui-panel button.reset').on('click', function(e){
            e.preventDefault();
            var that = $(this),
                data = null,
                data_type = that.attr('data-type'),
                option_panel = that.closest('div.sb-option');
            if(confirm(option_panel.attr('data-message-confirm'))) {
                sb_switch_reset_ajax(that, true);
                data = {
                    action: 'sb_ui_reset',
                    data_type: data_type
                };
                $.post(sb_core_admin_ajax.url, data, function(resp){
                    sb_reset_ui_complete(that);
                });
            }
        });
    })();

    (function(){
        $('button.ui-add-item').on('click', function(e){
            e.preventDefault();
            var that = $(this),
                ui_list = that.parent().find('.sb-sortable-list');
            $.post(sb_core_admin_ajax.url, sb_build_add_ui_data(that), function(resp){
                ui_list.append(resp);
            });
        })
    })();

    // Các chức năng quản lý slider
    (function(){
        $('#list_slider_items').sortable({
            cancel: ':input, .ui-state-disabled, .sb-icon-delete',
            placeholder: 'ui-state-highlight',
            sort: function(event, ui) {
                var that = $(this),
                    ui_state_highlight = that.find('.ui-state-highlight');
                ui_state_highlight.css({'height': ui.item.outerHeight()});
                if(that.hasClass('display-inline')) {
                    ui_state_highlight.css({'width': ui.item.outerWidth()});
                }
            },
            stop: function(event, ui) {
                var that = $(this),
                    list_slider_items = that,
                    slider_item_container = list_slider_items.parent(),
                    item_order = slider_item_container.find('.item-order'),
                    item_order_value = '';
                list_slider_items.find('li').each(function(index, el){
                    var li_item = $(el);
                    item_order_value += li_item.attr('data-item');
                    item_order_value += ',';
                });
                item_order_value = item_order_value.slice(0, -1);
                item_order.val(item_order_value);
            }
        });

        // Thêm đối tượng vào danh sách
        $('.slider-items .btn-add-item').live('click', function(e){
            e.preventDefault();
            var that = $(this);
            if(file_frame) {
                file_frame.uploader.uploader.param('post_id', new_post_id);
                file_frame.open();
                return;
            }
            file_frame = wp.media({title: 'Insert Media', button:{text: 'Use this image'}, multiple: true});
            file_frame.on('select', function(){
                var media_datas = sb_theme.receive_selected_media_items(file_frame);
                media_datas.map(function(media_data){
                    media_data = media_data.toJSON();
                    var media_url = media_data.url,
                        media_id = parseInt(media_data.id),
                        slider_items_container = that.closest('.slider-items'),
                        items_container = slider_items_container.find('.items-container'),
                        list_slider_items = slider_items_container.find('.list-slider-items'),
                        count_item = parseInt(list_slider_items.attr('data-items')),
                        max_item_id = parseInt(list_slider_items.attr('data-max-id')),
                        item_order = slider_items_container.find('.item-order'),
                        item_order_value = item_order.val(),
                        item_html = '';
                    if(media_id > 0) {
                        count_item++;
                        max_item_id++;
                        item_html += '<li data-item="' + max_item_id + '">';
                        item_html += '<img class="item-image" src="' + media_url + '">';
                        item_html += '<div class="item-info">';
                        item_html += '<input type="text" placeholder="Tiêu đề" value="" class="item-title" name="sbmb_slider_items[items][' + max_item_id + '][title]">';
                        item_html += '<input type="url" placeholder="Đường dẫn đến trang đích" value="" class="item-link" name="sbmb_slider_items[items][' + max_item_id + '][link]">';
                        item_html += '<textarea class="item-description" name="sbmb_slider_items[items][' + max_item_id + '][description]"></textarea>';
                        item_html += '</div>';
                        item_html += '<input type="hidden" class="item-image-url" name="sbmb_slider_items[items][' + max_item_id + '][image_url]" value="' + media_url + '">';
                        item_html += '<input type="hidden" class="item-image-id" name="sbmb_slider_items[items][' + max_item_id + '][image_id]" value="' + media_id + '">';
                        item_html += '<span class="item-icon icon-delete icon-sortable-ui"></span>';
                        item_html += '<span class="item-icon icon-drag icon-sortable-ui"></span>';
                        item_html += '</li>';
                        list_slider_items.append(item_html);
                        list_slider_items.attr('data-items', count_item);
                        list_slider_items.attr('data-max-id', max_item_id);
                        if($.trim(item_order_value)) {
                            item_order_value += ',';
                        }
                        item_order_value += max_item_id;
                        item_order.val(item_order_value);
                    }
                });
                file_frame = null;
            });
            file_frame.open();
        });

        // Thay đổi hình ảnh của đối tượng
        $('.list-slider-items .item-image').live('click', function(e){
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
                    media_url = media_data.url,
                    media_id = parseInt(media_data.id),
                    slider_item = that.parent(),
                    item_image_url = slider_item.find('.item-image-url'),
                    item_image_id = slider_item.find('.item-image-id'),
                    item_html = '';
                if(media_id > 0) {
                    that.attr('src', media_url);
                    item_image_url.val(media_url);
                    item_image_id.val(media_id);
                }
                file_frame = null;
            });
            file_frame.open();
        });

        // Xóa đối tượng ra khỏi danh sách slider
        $('.list-slider-items .icon-delete').live('click', function(e){
            e.preventDefault();
            var that = $(this),
                slider_item = that.parent(),
                list_slider_items = slider_item.parent(),
                slider_item_container = list_slider_items.parent(),
                item_order = slider_item_container.find('.item-order'),
                item_order_value = '';
            if(confirm('Bạn có chắc là muốn xóa không?')) {
                slider_item.remove();
                list_slider_items.find('li').each(function(index, el){
                    var li_item = $(el);
                    item_order_value += li_item.attr('data-item');
                    item_order_value += ',';
                });
                item_order_value = item_order_value.slice(0, -1);
                item_order.val(item_order_value);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: sb_core_admin_ajax.url,
                    data: {
                        action: 'sb_theme_remove_slider_item',
                        item_id: parseInt(slider_item.attr('data-item')),
                        post_id: parseInt(list_slider_items.attr('data-post'))
                    },
                    success: function(response){

                    }
                });
            }
        });

    })();

    // Thêm và xóa hình ảnh
    (function(){
        $('.sb-insert-media').live('click', function(e){
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

        $('.sb-remove-media').live('click', function(e){
            e.preventDefault();
            var that = $(this),
                media_container = that.closest('.sb-media-upload');
            media_container.find('input').val('').attr('value', '');
            media_container.find('.image-preview').removeClass('has-image').html('');
        });

        $('.sb-media-upload .image-url').live('change input', function(e){
            e.preventDefault();
            var that = $(this),
                media_container = that.closest('.sb-media-upload'),
                image_preview_container = media_container.find('.image-preview'),
                image_text = that.val();
            if($.trim(image_text) && sb_is_image_url(image_text)) {
                image_preview_container.html('<img alt="" src="' + image_text + '">');
                image_preview_container.addClass('has-image');
            } else {
                image_preview_container.html('');
                image_preview_container.removeClass('has-image');
            }
            media_container.find('.media-id').val(0);
        });
    })();

    window.sb_receive_media_upload = function (file_frame) {
        var media_data = sb_core.sb_receive_media_selected(file_frame);
        old_post_id = wp.media.model.settings.post.id;
        return media_data.url;
    };

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

    // SB Post Widget
    (function(){
        // Ẩn hoặc hiện chọn chuyên mục theo kiểu lấy bài viết
        (function(){
            $('.sb-post-widget .select-type-post').live('change', function(e){
                var that = $(this),
                    list_cats = that.closest('div.sb-widget').find('.post-cat');
                if('category' == that.val()) {
                    list_cats.delay(10).fadeIn();
                } else {
                    list_cats.delay(10).fadeOut();
                }
            });
        })();

        // Thay đổi thông tin taxonomy của chuyên mục hiện tại
        (function(){
            $('.sb-post-widget .post-cat option').live('click', function(e){
                var that = $(this),
                    taxonomy = that.attr('data-taxonomy'),
                    input_taxonomy = that.closest('div.sb-post-widget').find('input.taxonomy');
                input_taxonomy.val(taxonomy);
            });
        })();

        // Hiển thị hoặc ẩn các chức năng tương xứng với tùy chọn
        (function(){
            // Hàm điều khiển ẩn hoặc hiện các mục chức năng
            function sb_post_widget_switch_only_thumbnail(selector, value) {
                var widget_container = selector.closest('div.sb-widget');
                if(value) {
                    widget_container.find('.show-excerpt').fadeOut();
                    widget_container.find('.excerpt-length').fadeOut();
                    widget_container.find('.title-length').fadeOut();
                    widget_container.find('fieldset.post-info').fadeOut();
                } else {
                    widget_container.find('.show-excerpt').fadeIn();
                    if(widget_container.find('.show-excerpt input').is(':checked')) {
                        widget_container.find('.excerpt-length').fadeIn();
                    }
                    widget_container.find('.title-length').fadeIn();
                    widget_container.find('fieldset.post-info').fadeIn();
                }
            }

            // Xử lý sự kiện khi người dùng nhấn chuột vào ô chỉ hiển thị hình ảnh thu nhỏ
            $('.sb-post-widget .only-thumbnail input').live('click', function(e){
                var that = $(this),
                    check_result = that.is(':checked');
                if(check_result) {
                    $('.sb-post-widget .disable-thumbnail input').attr('checked', false);
                }
                sb_post_widget_switch_only_thumbnail(that, check_result);
            });

            // Xử lý sự kiện khi người dùng nhấn chuột vào ô không hiển thị hình ảnh thu nhỏ
            $('.sb-post-widget .disable-thumbnail input').live('click', function(e){
                var that = $(this),
                    check_result = that.is(':checked');
                if(check_result) {
                    $('.sb-post-widget .only-thumbnail input').attr('checked', false);
                    sb_post_widget_switch_only_thumbnail(that, false);
                }
            });

            // Xử lý sự kiện khi người dùng nhấn chuột vào ô hiển thị trích dẫn
            $('.sb-post-widget .show-excerpt input').live('click', function(e){
                var that = $(this),
                    widget_container = that.closest('div.sb-widget'),
                    excerpt_length = widget_container.find('.excerpt-length');
                if(that.is(':checked')) {
                    excerpt_length.fadeIn();
                } else {
                    excerpt_length.fadeOut();
                }
            })
        })();
    })();

    // Thêm css cho trang cài đặt dưới dạng dòng
    (function(){
        var sb_theme_row_setting = $('div.sbt-row-setting'),
            setting_table = sb_theme_row_setting.closest('table.form-table');
        if(sb_theme_row_setting.length) {
            setting_table.addClass('row-setting-table');
        }
    })();

    // SB Theme row Settings tab
    (function(){
        $('.sbt-row-setting .nav-tab-wrapper .nav-tab').on('click', function(e){
            e.preventDefault();
            var that = $(this),
                tab_container = that.parent(),
                sbt_row_setting = that.closest('div');
            tab_container.find('.nav-tab').removeClass('nav-tab-active');
            that.addClass('nav-tab-active');
            sbt_row_setting.find('.tab-content-item').removeClass('active');
            sbt_row_setting.find('.tab-content-item.' + that.attr('data-tab')).addClass('active');
        });
    })();

    // Checkbox click
    (function(){
        $('.sb-options input[type="checkbox"]').on('click', function(e){
            var that = $(this);
            if(that.is(':checked')) {
                that.attr('value', 1);
            } else {
                that.attr('value', 0);
            }
        });
    })();

    // SB Tab Widget
    (function(){
        body.find('div.widgets-sortables').each(function(){
            var that = $(this);
            sb_remove_parent_sidebar_tab_select(that);
            that.bind('DOMNodeInserted DOMNodeRemoved', function() {
                sb_remove_parent_sidebar_tab_select(that);
            });
        });

        function sb_remove_parent_sidebar_tab_select(selector) {
            var sidebar_id = selector.attr('id');
            selector.find('.sb-tab-widget').each(function(){
                var that = $(this);
                that.find('.sb-list-sidebars option[value="' + sidebar_id + '"], .sb-list-sidebars option[value*="orphaned_widgets"]').remove();
            });
        }
    })();

    // Lựa chọn địa giới hành chính
    (function(){
        // Hàm sử dụng cho meta box
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

        // Hàm sử dụng khi tạo và sửa chuyên mục
        $('.edit-tags-php .wrap .form-field select.select-term').on('change', function(e){
            e.preventDefault();
            var that = $(this),
                term = parseInt(that.val()),
                container = that.closest('.wrap'),
                taxonomy = that.attr('data-taxonomy'),
                district = container.find('.form-field select[name="district"]'),
                ward = container.find('.form-field select[name="ward"]'),
                hamlet = container.find('.form-field select[name="hamlet"]'),
                street = container.find('.form-field select[name="street"]');
            switch (taxonomy) {
                case 'province':
                    ward.find('option:not(:first)').remove();
                    hamlet.find('option:not(:first)').remove();
                    street.find('option:not(:first)').remove();
                    break;
                case 'district':
                    if(typeof hamlet != 'undefined') {
                        hamlet.find('option:not(:first)').remove();
                    }
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

    // Thay đổi chuyên mục thuộc post type cho SB Post Widget
    (function(){
        $('.sb-post-widget .select-post-type').live('change', function(e){
            e.preventDefault();
            var that = $(this),
                container = that.closest('.sb-post-widget'),
                select_category = container.find('.select-term');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: sb_theme.ajax_url,
                data: {
                    action: 'sb_theme_post_widget_change_post_type_taxonomy',
                    post_type: that.val(),
                    show_count: that.attr('data-show-count')
                },
                success: function(response){
                    select_category.find('optgroup').remove();
                    select_category.find('option:not(:first)').remove();
                    if(response.successful && $.trim(response.html_data)) {
                        select_category.append(response.html_data);
                    }
                }
            });
        });
    })();

    // Xóa product cat thumb
    (function(){
        $('#product_cat_thumbnail').closest('.form-field').remove();
    })();
})(jQuery);