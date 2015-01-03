<?php
function sb_theme_footer_text_callback() {
    $options = SB_Option::get();
    $value = isset($options['theme']['footer_text']) ? $options['theme']['footer_text'] : '';
    $id = 'sb_theme_footer_text';
    $name = 'sb_options[theme][footer_text]';
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => __('The text shows on footer area, you can enter address, phone, email,...', 'sb-theme')
    );
    SB_Field::rich_editor_field($args);
}

function sb_theme_setting_field_footer_text() {
    sb_theme_add_setting_field('sb_theme_footer_text', __('Footer text', 'sb-theme'), 'sb_theme_footer_text_callback');
}

function sb_theme_front_page_widget_callback() {
    $args = array(
        'option_name' => 'front_page_widget',
        'taxonomy' => 'category'
    );
    SB_Field::sortble_term($args);
}

function sb_theme_setting_field_front_page_widget() {
    sb_theme_add_setting_field('sb_theme_front_page_widget', __('Front page widget', 'sb-theme'), 'sb_theme_front_page_widget_callback');
}

function sb_theme_category_widget_callback() {
    $args = array(
        'option_name' => 'category_widget',
        'taxonomy' => 'category'
    );
    SB_Field::sortble_term($args);
}

function sb_theme_setting_field_category_widget() {
    sb_theme_add_setting_field('sb_theme_category_widget', __('Category widget', 'sb-theme'), 'sb_theme_category_widget_callback');
}

function sb_theme_taxonomy_channel_ui_connect_callback() {
    $args = array(
        'option_name' => 'channel_ui_connect',
        'taxonomy' => 'channel'
    );
    SB_Field::sortble_term($args);
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
        'description' => __('Your profile ID at AddThis.', 'sb-theme'),
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
        'description' => __('Put your code here and it will be inserted into head meta.', 'sb-theme'),
        'field_class' => 'width-large',
        'value' => $value,
        'media_buttons' => false,
        'teeny' => true,
        'tinymce' => false,
        'only_text' => true,
        'textarea_rows' => 10
    );
    SB_Field::rich_editor($args);
}

function sb_theme_setting_field_add_to_head() {
    sb_theme_add_setting_field('sb_theme_add_to_head', __('Add to head', 'sb-theme'), 'sb_theme_add_to_head_callback');
}

function sb_theme_sharethis_callback() {
    $value = SB_Option::get_theme_option(array('keys' => array('sharethis_pub_key')));
    $args = array(
        'id' => 'sb_theme_sharethis_pub_key',
        'name' => 'sb_options[theme][sharethis_pub_key]',
        'description' => __('Your publisher key on ShareThis website.', 'sb-theme'),
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
        'description' => __('Your Google Analytics tracking ID.', 'sb-theme'),
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
        'description' => __('Facebook callback url.', 'sb-theme'),
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
        'description' => __('Your fanpage ID at Facebook.', 'sb-theme'),
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
    $description = __('You can turn on or turn off the scroll to top button on your site.', 'sb-theme');
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
    sb_theme_add_setting_field('sb_theme_scroll_to_top', __('Scroll to top', 'sb-theme'), 'sb_theme_scroll_top_callback');
}

function sb_theme_social_callback() {
    $options = SB_Option::get();
    $args = array();
    $field = array(
        'id' => 'sb_theme_social_facebook',
        'name' => 'sb_options[theme][social][facebook]',
        'value' => isset($options['theme']['social']['facebook']) ? $options['theme']['social']['facebook'] : '',
        'description' => __('The url to your Facebook page.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_twitter',
        'name' => 'sb_options[theme][social][twitter]',
        'value' => isset($options['theme']['social']['twitter']) ? $options['theme']['social']['twitter'] : '',
        'description' => __('The url to your Twitter account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_gplus',
        'name' => 'sb_options[theme][social][gplus]',
        'value' => isset($options['theme']['social']['gplus']) ? $options['theme']['social']['gplus'] : '',
        'description' => __('The url to your Google Plus account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_youtube',
        'name' => 'sb_options[theme][social][youtube]',
        'value' => isset($options['theme']['social']['youtube']) ? $options['theme']['social']['youtube'] : '',
        'description' => __('The url to your YouTube channel.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_linkedin',
        'name' => 'sb_options[theme][social][linkedin]',
        'value' => isset($options['theme']['social']['linkedin']) ? $options['theme']['social']['linkedin'] : '',
        'description' => __('The url to your LinkedIn account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_pinterest',
        'name' => 'sb_options[theme][social][pinterest]',
        'value' => isset($options['theme']['social']['pinterest']) ? $options['theme']['social']['pinterest'] : '',
        'description' => __('The url to your Pinterest account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_zingme',
        'name' => 'sb_options[theme][social][zingme]',
        'value' => isset($options['theme']['social']['zingme']) ? $options['theme']['social']['zingme'] : '',
        'description' => __('The url to your Zing Me account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_rss',
        'name' => 'sb_options[theme][social][rss]',
        'value' => isset($options['theme']['social']['rss']) ? $options['theme']['social']['rss'] : '',
        'description' => __('The url for RSS on your site.', 'sb-theme')
    );
    array_push($args, $field);
    $args = apply_filters('sb_theme_social', $args);
    SB_Field::social_field($args);
}

function sb_theme_setting_field_social() {
    sb_theme_add_setting_field('sb_theme_social', __('Social settings', 'sb-theme'), 'sb_theme_social_callback');
}

function sb_theme_no_thumbnail_callback() {
    $options = SB_Option::get();
    $value = isset($options['theme']['thumbnail']) ? $options['theme']['thumbnail'] : '';
    $args = array(
        'id' => 'sb_theme_no_thumbnail',
        'name' => 'sb_options[theme][thumbnail]',
        'value' => $value,
        'description' => __('You can enter url or upload new thumbnail image file.', 'sb-theme')
    );
    SB_Field::media_image($args);
}

function sb_theme_setting_field_no_thumbnail() {
    sb_theme_add_setting_field('sb_theme_no_thumbnail', __('Thumbnail image', 'sb-theme'), 'sb_theme_no_thumbnail_callback');
}