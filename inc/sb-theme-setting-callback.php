<?php
defined('ABSPATH') or die('Please do not pip me!');

function sb_theme_footer_text_callback() {
    $options = SB_Option::get();
    $value = isset($options['theme']['footer_text']) ? $options['theme']['footer_text'] : '';
    $id = 'sb_theme_footer_text';
    $name = 'sb_options[theme][footer_text]';
    $desc = ('vi' == SB_Core::get_language()) ? 'Thông tin chữ hiển thị dưới chân trang web, bạn có thể nhập địa chỉ, email, số điện thoại,' : __('The information displays on footer area, e.g: email, phone, address,...', 'sb-theme');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $desc
    );
    SB_Field::rich_editor_field($args);
}

function sb_theme_setting_field_footer_text() {
    $text = ('vi' == SB_Core::get_language()) ? 'Chữ dưới chân trang' : __('Footer text', 'sb-theme');
    sb_theme_add_setting_field('sb_theme_footer_text', $text, 'sb_theme_footer_text_callback');
}

function sb_theme_front_page_widget_callback() {
    $args = array(
        'option_name' => 'front_page_widget',
        'taxonomy' => 'category'
    );
    SB_Field::sortable_term($args);
}

function sb_theme_setting_field_front_page_widget() {
    sb_theme_add_setting_field('sb_theme_front_page_widget', __('Front page widget', 'sb-theme'), 'sb_theme_front_page_widget_callback');
}

function sb_theme_term_widget_callback($taxonomy, $option_name = 'term_widget') {
    $args = array(
        'option_name' => $option_name,
        'taxonomy' => $taxonomy
    );
    SB_Field::sortable_term($args);
}

function sb_theme_setting_field_term_widget($callback, $title = '', $option_name = 'term_widget') {
    if(empty($title)) {
        $title = __('Nhóm chuyên mục', 'sb-theme');
    }
    sb_theme_add_setting_field('sb_theme_' . $option_name, $title, $callback);
}

function sb_theme_setting_field_category_widget_display($taxonomy, $name) {
    $args = array(
        'option_name' => $name,
        'taxonomy' => $taxonomy
    );
    SB_Field::sortable_term($args);
}

function sb_theme_category_widget_callback() {
    sb_theme_setting_field_category_widget_display('category', 'category_widget');
}

function sb_theme_setting_field_category_widget($title = '') {
    if(empty($title)) {
        $title = ('vi' == SB_Core::get_language()) ? 'Nhóm chuyên mục' : __('Sortable Categories', 'sb-theme');
    }
    sb_theme_add_setting_field('sb_theme_category_widget', $title, 'sb_theme_category_widget_callback');
}

function sb_theme_hotline_callback() {
    $value = SB_Option::get_theme_option_single_key('hotline');
    $args = array(
        'id' => 'sb_theme_hotline',
        'name' => SB_Option::build_sb_theme_option_name(array('hotline')),
        'value' => $value
    );
    SB_Field::text($args);
}

function sb_theme_setting_field_hotline() {
    sb_theme_add_setting_field('sb_theme_hotline', __('Hotline', 'sb-theme'), 'sb_theme_hotline_callback');
}

function sb_theme_taxonomy_channel_ui_connect_callback() {
    $args = array(
        'option_name' => 'channel_ui_connect',
        'taxonomy' => 'channel'
    );
    SB_Field::sortable_term($args);
}

function sb_theme_taxonomy_channel_ui_connect($title = '') {
    if(empty($title)) {
        $title = __('Channel widget', 'sb-theme');
    }
    sb_theme_add_setting_field('sb_theme_taxonomy_channel_ui_connect', $title, 'sb_theme_taxonomy_channel_ui_connect_callback');
}

function sb_theme_rss_feed_callback() {
    $id = 'sb_theme_rss_feed';
    $name = 'sb_options[theme][rss_feed]';
    $description = __('You can remove or add new feed data.', 'sb-theme');
    $list_feeds = SB_Option::get_theme_rss_feed();
    $order = SB_Option::get_theme_option(array('keys' => array('rss_feed', 'order')));
    if(empty($order)) {
        foreach($list_feeds as $feed) {
            $order .= $feed['id'] . ',';
        }
        $order = trim($order, ',');
    }
    $args = array(
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'list_feeds' => $list_feeds,
        'order' => $order
    );
    SB_Field::rss_feed($args);
}

function sb_theme_setting_field_rss_feed() {
    sb_theme_add_setting_field('sb_theme_rss_feed', __('RSS Feed', 'sb-theme'), 'sb_theme_rss_feed_callback');
}

function sb_theme_addthis_callback() {
    $value = SB_Option::get_theme_option(array('keys' => array('addthis_profile_id')));
    $args = array(
        'id' => 'sb_theme_addthis_profile_id',
        'name' => 'sb_options[theme][addthis_profile_id]',
        'description' => __('Mã ID tài khoản AddThis của bạn.', 'sb-theme'),
        'field_class' => 'width-medium',
        'value' => $value
    );
    SB_Field::text_field($args);
}

function sb_theme_setting_field_addthis() {
    sb_theme_add_setting_field('sb_theme_addthis_profile_id', __('AddThis Profile ID', 'sb-theme'), 'sb_theme_addthis_callback');
}

function sb_theme_add_to_head_callback() {
    $value = SB_Option::get_theme_option(array('keys' => array('add_to_head')));
    $args = array(
        'id' => 'sb_theme_add_to_head',
        'name' => 'sb_options[theme][add_to_head]',
        'description' => __('Đặt các đoạn mã của bạn vào đây thì sẽ xuất hiện vào thẻ head của trang.', 'sb-theme'),
        'field_class' => 'width-large',
        'value' => $value,
        'media_buttons' => false,
        'teeny' => true,
        'tinymce' => false,
        'only_text' => true,
        'textarea_rows' => 10
    );
    SB_Field::textarea($args);
}

function sb_theme_setting_field_add_to_head() {
    sb_theme_add_setting_field('sb_theme_add_to_head', __('Thêm vào thẻ head', 'sb-theme'), 'sb_theme_add_to_head_callback');
}

function sb_theme_sharethis_callback() {
    $value = SB_Option::get_theme_option(array('keys' => array('sharethis_pub_key')));
    $args = array(
        'id' => 'sb_theme_sharethis_pub_key',
        'name' => 'sb_options[theme][sharethis_pub_key]',
        'description' => __('Mã publisher trên tài khoản ShareThis của bạn.', 'sb-theme'),
        'field_class' => 'width-medium',
        'value' => $value
    );
    SB_Field::text_field($args);
}

function sb_theme_setting_field_sharethis() {
    sb_theme_add_setting_field('sb_theme_sharethis_pub_key', __('ShareThis Publisher key', 'sb-theme'), 'sb_theme_sharethis_callback');
}

function sb_theme_google_analytics_callback() {
    $value = SB_Option::get_theme_option_single_key('google_analytics');
    $args = array(
        'id' => 'sb_theme_google_analytics',
        'name' => SB_Option::build_sb_theme_option_name(array('google_analytics')),
        'description' => __('Mã ID theo dõi Google Analytics của bạn.', 'sb-theme'),
        'field_class' => 'width-medium',
        'value' => $value
    );
    SB_Field::text_field($args);
}

function sb_theme_setting_field_google_analtyics() {
    sb_theme_add_setting_field('sb_theme_google_analytics', __('Google Analytics', 'sb-theme'), 'sb_theme_google_analytics_callback');
}

function sb_theme_facebook_api_callback() {
    $value = SB_Option::get_theme_option(array('keys' => array('facebook_api', 'app_id')));
    $keys = array('facebook_api', 'app_id');
    $args = array(
        'id' => 'sb_theme_facebook_api_app_id_profile_id',
        'name' => SB_Option::build_sb_theme_option_name($keys),
        'description' => __('Facebook APP ID.', 'sb-theme'),
        'value' => $value,
        'container_class' => 'margin-bottom'
    );
    SB_Field::text_field($args);
    $value = SB_Option::get_theme_option(array('keys' => array('facebook_api', 'app_secret')));
    $args = array(
        'id' => 'sb_theme_facebook_api_app_secret_profile_id',
        'name' => SB_Option::build_sb_theme_option_name(array('facebook_api', 'app_secret')),
        'description' => __('Facebook APP secrect.', 'sb-theme'),
        'value' => $value,
        'container_class' => 'margin-bottom'
    );
    SB_Field::text_field($args);
    $value = SB_Option::get_theme_option(array('keys' => array('facebook_api', 'callback_url')));
    $args = array(
        'id' => 'sb_theme_facebook_api_callback_url_profile_id',
        'name' => SB_Option::build_sb_theme_option_name(array('facebook_api', 'callback_url')),
        'description' => __('Đường dẫn trả về khi thực thi lệnh từ Facebook.', 'sb-theme'),
        'value' => $value
    );
    SB_Field::text_field($args);
}

function sb_theme_setting_field_facebook_api() {
    sb_theme_add_setting_field('sb_theme_facebook_api', __('Facebook API', 'sb-theme'), 'sb_theme_facebook_api_callback');
}

function sb_theme_facebook_fanpage_id_callback() {
    $value = SB_Option::get_theme_option_single_key('facebook_fanpage');
    $args = array(
        'id' => 'sb_theme_facebook_fanpage_id',
        'name' => 'sb_options[theme][facebook_fanpage]',
        'description' => __('Mã ID của Fanpage trên Facebook.', 'sb-theme'),
        'value' => $value
    );
    SB_Field::text_field($args);
}

function sb_theme_setting_field_facebook_fanpage() {
    sb_theme_add_setting_field('sb_theme_facebook_fanpage_id', __('Facebook Fanpage ID', 'sb-theme'), 'sb_theme_facebook_fanpage_id_callback');
}

function sb_theme_scroll_top_callback() {
    $name = 'sb_options[theme][scroll_top]';
    $options = SB_Option::get();
    $value = isset($options['theme']['scroll_top']) ? $options['theme']['scroll_top'] : 1;
    $description = __('Bạn có thể bật hoặc tắt chức năng cho phép hiển thị nút quay về đầu trang.', 'sb-theme');
    $id = 'sb_theme_scroll_top';
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_theme_setting_field_scroll_top() {
    sb_theme_add_setting_field('sb_theme_scroll_to_top', __('Nút về đầu trang', 'sb-theme'), 'sb_theme_scroll_top_callback');
}

function sb_theme_social_callback() {
    $options = SB_Option::get();
    $args = array();
    $socials = SB_Theme::get_supported_socials();
    foreach($socials as $social => $name) {
        $field = array(
            'id' => 'sb_theme_social_' . $social,
            'name' => 'sb_options[theme][social][' . $social . ']',
            'value' => isset($options['theme']['social'][$social]) ? $options['theme']['social'][$social] : '',
            'description' => ('vi' == SB_Core::get_language()) ? 'Đường dẫn tới trang ' . $name . ' của bạn.' : sprintf(__('Your %s page url.', 'sb-theme'), $name)
        );
        array_push($args, $field);
    }
    $args = apply_filters('sb_theme_social_field', $args);
    SB_Field::social_field($args);
}

function sb_theme_setting_field_social() {
    $text = ('vi' == SB_Core::get_language()) ? 'Mạng xã hội' : __('Socials', 'sb-theme');
    sb_theme_add_setting_field('sb_theme_social', $text, 'sb_theme_social_callback');
}

function sb_theme_no_thumbnail_callback() {
    $options = SB_Option::get();
    $value = isset($options['theme']['thumbnail']) ? $options['theme']['thumbnail'] : '';
    $args = array(
        'id' => 'sb_theme_no_thumbnail',
        'name' => 'sb_options[theme][thumbnail]',
        'value' => $value,
        'description' => __('Bạn có thể nhập vào đường dẫn hoặc tải lên hình ảnh mới.', 'sb-theme')
    );
    SB_Field::media_image($args);
}

function sb_theme_setting_field_no_thumbnail() {
    sb_theme_add_setting_field('sb_theme_no_thumbnail', __('Ảnh thumbnail mặc định', 'sb-theme'), 'sb_theme_no_thumbnail_callback');
}