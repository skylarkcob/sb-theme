<?php
/*
 * Plugin SB Clean
 */
function sb_clean_menu() {
    SB_Admin_Custom::add_submenu_page('Clean', 'sb_clean', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_clean_menu');

function sb_clean_tab($tabs) {
    $tabs['sb_clean'] = array('title' => 'Clean', 'section_id' => 'sb_clean_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_clean_tab');

function sb_clean_setting_field() {
    SB_Admin_Custom::add_section('sb_clean_section', __('SB Clean options page', 'sb-clean'), 'sb_clean');
    SB_Admin_Custom::add_setting_field('sb_clean_wpdb', __('Clean WPDB', 'sb-clean'), 'sb_clean_section', 'sb_clean_wpdb_callback', 'sb_clean');
    SB_Admin_Custom::add_setting_field('sb_clean_head_meta', __('Clean head meta', 'sb-clean'), 'sb_clean_section', 'sb_clean_head_meta_callback', 'sb_clean');
    SB_Admin_Custom::add_setting_field('sb_clean_post_revision', __('Clean post revisions', 'sb-clean'), 'sb_clean_section', 'sb_clean_post_revision_callback', 'sb_clean');
}
add_action('sb_admin_init', 'sb_clean_setting_field');

function sb_clean_post_revision_callback() {
    $args = array(
        'text' => __('Process', 'sb-clean'),
        'field_class' => 'sb-clean-post-revision',
        'description' => __('Start to clean all post revisions on your database.', 'sb-clean')
    );
    SB_Field::button($args);
}

function sb_clean_wpdb_callback() {
    $name = 'sb_options[clean][wpdb]';
    $options = SB_Option::get();
    $value = isset($options['clean']['wpdb']) ? $options['clean']['wpdb'] : 1;
    $description = __('You can turn on or turn off the function to unset database name and password.', 'sb-clean');
    $args = array(
        'id' => 'sb_clean_wpdb',
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_clean_head_meta_callback() {
    $name = 'sb_options[clean][head_meta]';
    $options = SB_Option::get();
    $value = isset($options['clean']['head_meta']) ? $options['clean']['head_meta'] : 1;
    $description = __('You can turn on or turn off the function to clear WordPress head meta.', 'sb-clean');
    $args = array(
        'id' => 'sb_clean_head_meta',
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_clean_sanitize($input) {
    $data = $input;
    $data['clean']['wpdb'] = isset($input['clean']['wpdb']) ? $input['clean']['wpdb'] : 1;
    $data['clean']['head_meta'] = isset($input['clean']['head_meta']) ? $input['clean']['head_meta'] : 1;
    return $data;
}
//add_filter('sb_options_sanitize', 'sb_clean_sanitize');

/*
 * Plugin SB Comment
 */
function sb_comment_menu() {
    SB_Admin_Custom::add_submenu_page('Discussion', 'sb_comment', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_comment_menu');

function sb_comment_tab($tabs) {
    $tabs['sb_comment'] = array('title' => 'Discussion', 'section_id' => 'sb_comment_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_comment_tab');

function sb_comment_setting_field() {
    SB_Admin_Custom::add_section('sb_comment_section', __('SB Comment options page', 'sb-comment'), 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_spam_check', __('Spam check', 'sb-comment'), 'sb_comment_section', 'sb_comment_spam_check_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_notify_user', __('Notify user', 'sb-comment'), 'sb_comment_section', 'sb_comment_notify_user_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_auto_empty_spam', __('Auto empty spam', 'sb-comment'), 'sb_comment_section', 'sb_comment_auto_empty_spam_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_remove_url', __('Disable website url', 'sb-comment'), 'sb_comment_section', 'sb_comment_remove_url_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_tools', __('Comment tools', 'sb-comment'), 'sb_comment_section', 'sb_comment_tools_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_default_avatar', __('Default avatar', 'sb-comment'), 'sb_comment_section', 'sb_comment_default_avatar_callback', 'sb_comment');
    if(SB_Comment::use_default_avatar()) {
        SB_Admin_Custom::add_setting_field('sb_comment_default_avatar_url', __('Default avatar url', 'sb-comment'), 'sb_comment_section', 'sb_comment_default_avatar_url_callback', 'sb_comment');
    }
}
add_action('sb_admin_init', 'sb_comment_setting_field');

function sb_comment_default_avatar_url_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['default_avatar_url']) ? $options['comment']['default_avatar_url'] : '';
    $id = 'sb_comment_default_avatar_url';
    $name = 'sb_options[comment][default_avatar_url]';
    $description = __('You can turn on or turn off the functions to use default avatar on localhost.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::media_image($args);
}

function sb_comment_default_avatar_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['default_avatar']) ? $options['comment']['default_avatar'] : 0;
    $id = 'sb_comment_default_avatar';
    $name = 'sb_options[comment][default_avatar]';
    $description = __('You can turn on or turn off the functions to use default avatar on localhost.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_remove_url_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['website_url']) ? $options['comment']['website_url'] : 0;
    $id = 'sb_comment_remove_url';
    $name = 'sb_options[comment][website_url]';
    $description = __('You can turn on or turn off the functions to allow user add website url into comment.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_tools_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['tools']) ? $options['comment']['tools'] : 1;
    $id = 'sb_comment_tools';
    $name = 'sb_options[comment][tools]';
    $description = __('You can turn on or turn off the functions to show comment tools.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_auto_empty_spam_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['auto_empty_spam']) ? $options['comment']['auto_empty_spam'] : 1;
    $id = 'sb_comment_auto_empty_spam';
    $name = 'sb_options[comment][auto_empty_spam]';
    $description = __('You can turn on or turn off the functions to allow empty spam automatically.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_notify_user_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['notify_user']) ? $options['comment']['notify_user'] : 1;
    $id = '';
    $name = 'sb_options[comment][notify_user]';
    $description = __('You can turn on or turn off the functions to allow sending email notifications when comment is approved.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_spam_check_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['spam_check']) ? $options['comment']['spam_check'] : 1;
    $id = 'sb_comment_spam_check';
    $name = 'sb_options[comment][spam_check]';
    $description = __('You can turn on or turn off the functions for checking spam comment.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_sanitize($input) {
    $data = $input;
    $data['comment']['spam_check'] = SB_Core::sanitize(isset($input['comment']['spam_check']) ? $input['comment']['spam_check'] : 1, 'bool');
    return $data;
}
//add_filter('sb_options_sanitize', 'sb_comment_sanitize');

/*
 * Plugin SB Login Page
 */
function sb_login_page_menu() {
    SB_Admin_Custom::add_submenu_page('Login', 'sb_login_page', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_login_page_menu');

function sb_login_page_tab($tabs) {
    $tabs['sb_login_page'] = array('title' => 'Login', 'section_id' => 'sb_login_page_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_login_page_tab');

function sb_login_page_add_admin_setting_field($id, $title, $callback) {
    SB_Admin_Custom::add_setting_field($id, $title, 'sb_login_page_section', $callback, 'sb_login_page');
}

function sb_login_page_setting_field() {
    SB_Admin_Custom::add_section('sb_login_page_section', __('SB Login Page options page', 'sb-login-page'), 'sb_login_page');
    sb_login_page_add_admin_setting_field('sb_login_page_user_can_register', __('Allow user register', 'sb-login-page'), 'sb_login_page_user_can_register_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_use_sb_login', __('Use SB Login', 'sb-login-page'), 'sb_login_page_use_sb_login_callback');
    SB_Admin_Custom::add_setting_field('sb_login_page_logo', 'Logo', 'sb_login_page_section', 'sb_login_page_logo_callback', 'sb_login_page');
    if(sb_login_page_use_sb_login()) {
        sb_login_page_add_admin_setting_field('sb_login_page_page_account', __('Account page', 'sb-login-page'), 'sb_login_page_page_account_callback');
        sb_login_page_add_admin_setting_field('sb_login_page_page_login', __('Login page', 'sb-login-page'), 'sb_login_page_page_login_callback');
        sb_login_page_add_admin_setting_field('sb_login_page_page_lost_password', __('Lost password page', 'sb-login-page'), 'sb_login_page_page_lost_password_callback');
        sb_login_page_add_admin_setting_field('sb_login_page_page_register', __('Register page', 'sb-login-page'), 'sb_login_page_page_register_callback');
    }
    sb_login_page_add_admin_setting_field('sb_login_page_login_redirect', __('Login redirect', 'sb-login-page'), 'sb_login_page_login_redirect_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_logout_redirect', __('Logout redirect', 'sb-login-page'), 'sb_login_page_logout_redirect_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_social_login', __('Social login', 'sb-login-page'), 'sb_login_page_social_login_callback');
    sb_login_page_add_admin_setting_field('sb_login_page_use_captcha', __('Use captcha', 'sb-login-page'), 'sb_login_page_use_captcha_callback');
}
add_action('sb_admin_init', 'sb_login_page_setting_field');

function sb_login_page_use_captcha_callback() {
    $value = absint(SB_Option::use_login_captcha());
    $args = array(
        'id' => 'sb_login_page_use_captcha',
        'name' => 'sb_options[login_page][use_captcha]',
        'value' => $value,
        'description' => __('Turn on or turn off the function for user must pass captcha when register.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_social_login_callback() {
    $value = absint(SB_Option::social_login_enabled());
    $args = array(
        'id' => 'sb_login_page_social_login',
        'name' => 'sb_options[login_page][social_login]',
        'value' => $value,
        'description' => __('Turn on or turn off the function for user connect account with social network.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_use_sb_login_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['use_sb_login']) ? intval($options['login_page']['use_sb_login']) : 1;
    $args = array(
        'id' => 'sb_login_page_use_sb_login',
        'name' => 'sb_options[login_page][use_sb_login]',
        'value' => $value,
        'description' => __('Turn on or turn off the function to force using SB Login system.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_logout_redirect_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['logout_redirect']) ? $options['login_page']['logout_redirect'] : 'home';
    $options = array(
        'home' => __('Home', 'sb-login-page'),
        'current' => __('Current', 'sb-login-page')
    );
    $args = array(
        'id' => 'sb_login_page_logout_redirect',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'logout_redirect')),
        'value' => $value,
        'options' => $options,
        'description' => __('Choose the redirect page when user logout.', 'sb-login-page')
    );
    SB_Field::select($args);
}

function sb_login_page_login_redirect_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['login_redirect']) ? $options['login_page']['login_redirect'] : 'current';
    $options = array(
        'home' => __('Home', 'sb-login-page'),
        'profile' => __('Profile', 'sb-login-page'),
        'dashboard' => __('Dashboard', 'sb-login-page'),
        'current' => __('Current', 'sb-login-page')
    );
    $args = array(
        'id' => 'sb_login_page_login_redirect',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'login_redirect')),
        'value' => $value,
        'options' => $options,
        'description' => __('Choose the redirect page when user login.', 'sb-login-page')
    );
    SB_Field::select($args);
}

function sb_login_page_user_can_register_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['users_can_register']) ? intval($options['login_page']['users_can_register']) : 0;
    $users_can_register = intval(get_option('users_can_register'));
    if($value != $users_can_register) {
        $value = $users_can_register;
    }
    $args = array(
        'id' => 'sb_login_page_user_can_register',
        'name' => 'sb_options[login_page][users_can_register]',
        'value' => $value,
        'description' => __('Turn on or turn off the function to allow user can register.', 'sb-theme')
    );
    SB_Field::switch_button($args);
}

function sb_login_page_page_register_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_register']) ? $options['login_page']['page_register'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_register',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_register')),
        'value' => $value,
        'description' => __('Choose the page for user login and sign up.', 'sb-login-page')
    );
    SB_Field::select_page($args);
}

function sb_login_page_page_lost_password_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_lost_password']) ? $options['login_page']['page_lost_password'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_lost_password',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_lost_password')),
        'value' => $value,
        'description' => __('Choose the page for user login and sign up.', 'sb-login-page')
    );
    SB_Field::select_page($args);
}

function sb_login_page_page_account_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_account']) ? $options['login_page']['page_account'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_account',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_account')),
        'value' => $value,
        'description' => __('Choose the page for user login and sign up.', 'sb-login-page')
    );
    SB_Field::select_page($args);
}

function sb_login_page_page_login_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['page_login']) ? $options['login_page']['page_login'] : 0;
    $args = array(
        'id' => 'sb_login_page_page_login',
        'name' => SB_Option::build_sb_option_name(array('login_page', 'page_login')),
        'value' => $value,
        'description' => __('Choose the page for user login and sign up.', 'sb-login-page')
    );
    SB_Field::select_page($args);
}

function sb_login_page_logo_callback() {
    $options = SB_Option::get();
    $value = isset($options['login_page']['logo']) ? $options['login_page']['logo'] : '';
    $args = array(
        'id' => 'sb_login_page_logo',
        'name' => 'sb_options[login_page][logo]',
        'value' => $value,
        'description' => __('You can enter url or upload new logo image file.', 'sb-login-page')
    );
    SB_Field::media_image($args);
}

function sb_login_page_sanitize($input) {
    $data = $input;
    $users_can_register = isset($input['login_page']['users_can_register']) ? (bool)$input['login_page']['users_can_register'] : false;
    if($users_can_register) {
        update_option('users_can_register', 1);
    } else {
        update_option('users_can_register', 0);
    }
    $data['login_page']['logo'] = SB_Core::sanitize(isset($input['login_page']['logo']) ? $input['login_page']['logo'] : '', 'url');
    return $data;
}
add_filter('sb_options_sanitize', 'sb_login_page_sanitize');

/*
 * Plugin SB Paginate
 */
function sb_paginate_menu() {
    SB_Admin_Custom::add_submenu_page('Pagination', 'sb_paginate', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_paginate_menu');

function sb_paginate_tab($tabs) {
    $tabs['sb_paginate'] = array('title' => 'Pagination', 'section_id' => 'sb_paginate_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_paginate_tab');

function sb_paginate_setting_field() {
    SB_Admin_Custom::add_section('sb_paginate_section', __('SB Paginate options page', 'sb-paginate'), 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_label', __('Label', 'sb-paginate'), 'sb_paginate_section', 'sb_paginate_label_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_next_text', __('Next text', 'sb-paginate'), 'sb_paginate_section', 'sb_paginate_next_text_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_previous_text', __('Previous text', 'sb-paginate'), 'sb_paginate_section', 'sb_paginate_previous_text_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_range', 'Range', 'sb_paginate_section', 'sb_paginate_range_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_anchor', 'Anchor', 'sb_paginate_section', 'sb_paginate_anchor_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_gap', 'Gap', 'sb_paginate_section', 'sb_paginate_gap_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_style', __('Style', 'sb-paginate'), 'sb_paginate_section', 'sb_paginate_style_callback', 'sb_paginate');
    SB_Admin_Custom::add_setting_field('sb_paginate_border_radius', __('Border radius', 'sb-paginate'), 'sb_paginate_section', 'sb_paginate_border_radius_callback', 'sb_paginate');
}
add_action('sb_admin_init', 'sb_paginate_setting_field');

function sb_paginate_next_text_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['next_text']) ? $options['paginate']['next_text'] : '&raquo;';
    $id = 'sb_paginate_next_text';
    $name = 'sb_options[paginate][next_text]';
    $description = __('The text for previous page button.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'desciption' => $description
    );
    SB_Field::text_field($args);
}

function sb_paginate_previous_text_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['previous_text']) ? $options['paginate']['previous_text'] : '&laquo;';
    $id = 'sb_paginate_previous_text';
    $name = 'sb_options[paginate][previous_text]';
    $description = __('The text for next page button.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::text_field($args);
}

function sb_paginate_label_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['label']) ? $options['paginate']['label'] : __('Trang', 'sb-paginate');
    $id = 'sb_paginate_label';
    $name = 'sb_options[paginate][label]';
    $description = __('The label text to display before pagination.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::text_field($args);
}

function sb_paginate_range_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['range']) ? $options['paginate']['range'] : 3;
    $description = __('The number of page links to show before and after the current page.', 'sb-paginate');
    printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class="" min="1" max="100"><p class="description">%4$s</p>', 'sb_paginate_range', esc_attr('sb_options[paginate][range]'), $value, $description);
}

function sb_paginate_anchor_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['anchor']) ? $options['paginate']['anchor'] : 1;
    $description = __('The number of page links to show at beginning and end of pagination.', 'sb-paginate');
    printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class="" min="1" max="10"><p class="description">%4$s</p>', 'sb_paginate_anchor', esc_attr('sb_options[paginate][anchor]'), $value, $description);
}

function sb_paginate_gap_callback() {
    $options = SB_Option::get();
    $value = isset($options['paginate']['gap']) ? $options['paginate']['gap'] : 3;
    $description = __('The minimum number of page links before ellipsis shows.', 'sb-paginate');
    printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class="" min="1" max="100"><p class="description">%4$s</p>', 'sb_paginate_gap', esc_attr("sb_options[paginate][gap]"), $value, $description);
}

function sb_paginate_style_callback() {
    $args = array(
        'default' => __('Default', 'sb-paginate'),
        'orange' => __('Orange', 'sb-paginate'),
        'dark' => __('Dark', 'sb-paginate')
    );
    $styles = apply_filters('sb_paginate_style', $args);
    $name = 'sb_paginate_style';
    $options = SB_Option::get();
    $value = isset($options['paginate']['style']) ? $options['paginate']['style'] : 'default';
    $description = __('Choose style color for pagination.', 'sb-paginate');
    ?>
    <label for="<?php echo $name; ?>"></label>
    <select id="<?php echo $name; ?>" name="<?php echo esc_attr('sb_options[paginate][style]'); ?>">
        <?php foreach($styles as $key => $title) : ?>
            <option value="<?php echo $key; ?>"<?php selected( $value, $key ); ?>><?php echo $title; ?></option>
        <?php endforeach; ?>
    </select>
    <p class="description"><?php echo $description; ?></p>
<?php
}

function sb_paginate_border_radius_callback() {
    $args = array(
        'default' => __('Default', 'sb-paginate'),
        'elipse' => __('Elipse', 'sb-paginate'),
        'none' => __('None', 'sb-paginate')
    );
    $styles = apply_filters('sb_paginate_border_radius', $args);
    $name = 'sb_paginate_border_radius';
    $options = SB_Option::get();
    $value = isset($options['paginate']['border_radius']) ? $options['paginate']['border_radius'] : 'default';
    $description = __('You can make navigation buttons have border radius or not.', 'sb-paginate');
    ?>
    <label for="<?php echo $name; ?>"></label>
    <select id="<?php echo $name; ?>" name="<?php echo esc_attr('sb_options[paginate][border_radius]'); ?>">
        <?php foreach($styles as $key => $title) : ?>
            <option value="<?php echo $key; ?>"<?php selected( $value, $key ); ?>><?php echo $title; ?></option>
        <?php endforeach; ?>
    </select>
    <p class="description"><?php echo $description; ?></p>
<?php
}

function sb_paginate_sanitize($input) {
    $data = $input;
    $data['paginate']['range'] = isset($input['paginate']['range']) ? $input['paginate']['range'] : 3;
    $data['paginate']['gap'] = isset($input['paginate']['gap']) ? $input['paginate']['gap'] : 3;
    $data['paginate']['anchor'] = isset($input['paginate']['anchor']) ? $input['paginate']['anchor'] : 1;
    $data['paginate']['style'] = isset($input['paginate']['style']) ? $input['paginate']['style'] : 'default';
    $data['paginate']['border_radius'] = isset($input['paginate']['border_radius']) ? $input['paginate']['border_radius'] : 'default';
    return $data;
}
//add_filter('sb_options_sanitize', 'sb_paginate_sanitize');

/*
 * Plugin SB Post Widget
 */
function sb_post_widget_menu() {
    SB_Admin_Custom::add_submenu_page('Post Widget', 'sb_post_widget', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_post_widget_menu');

function sb_post_widget_tab($tabs) {
    $tabs['sb_post_widget'] = array('title' => 'Post Widget', 'section_id' => 'sb_post_widget_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_post_widget_tab');

function sb_post_widget_setting_field() {
    SB_Admin_Custom::add_section('sb_post_widget_section', __('SB Post Widget options page', 'sb-post-widget'), 'sb_post_widget');
    SB_Admin_Custom::add_setting_field('sb_post_widget_no_thumbnail', __('No thumbnail image', 'sb-post-widget'), 'sb_post_widget_section', 'sb_post_widget_no_thumbnail_callback', 'sb_post_widget');
}
add_action('sb_admin_init', 'sb_post_widget_setting_field');

function sb_post_widget_no_thumbnail_callback() {
    $id = 'sb_post_widget_no_thumbnail';
    $name = 'sb_options[post_widget][no_thumbnail]';
    $value = SB_Option::get_widget_thumbnail_url();
    $description = __('You can enter url or upload new image file.', 'sb-post-widget');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::media_image($args);
}

/*
 * Plugin SB Tab Widget
 */
function sb_tab_widget_menu() {
    SB_Admin_Custom::add_submenu_page('Tab Widget', 'sb_tab_widget', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_tab_widget_menu');

function sb_tab_widget_tab($tabs) {
    $tabs['sb_tab_widget'] = array('title' => 'Tab Widget', 'section_id' => 'sb_tab_widget_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_tab_widget_tab');

function sb_tab_widget_setting_field() {
    SB_Admin_Custom::add_section('sb_tab_widget_section', __('SB Tab Widget options page', 'sb-tab-widget'), 'sb_tab_widget');
    SB_Admin_Custom::add_setting_field('sb_tab_widget_sidebar', __('Tabber widget area', 'sb-tab-widget'), 'sb_tab_widget_section', 'sb_tab_widget_sidebar_callback', 'sb_tab_widget');
}
add_action('sb_admin_init', 'sb_tab_widget_setting_field');

function sb_tab_widget_sidebar_callback() {
    $id = 'sb_tab_widget_sidebar';
    $name = 'sb_options[tab_widget][sidebar]';
    $list_sidebars = sb_tab_widget_get_sidebars();
    $description = __('You can remove or create new widget area for displaying widget on tabber.', 'sb-tab-widget');
    $tabber_sidebar = SB_Core::get_sidebar_by('id', 'sb-tabber');
    $default_sidebars = array(
        array(
            'id' => $tabber_sidebar['id'],
            'name' => $tabber_sidebar['name'],
            'description' => $tabber_sidebar['description']
        )
    );
    $args = array(
        'id' => $id,
        'name' => $name,
        'list_sidebars' => $list_sidebars,
        'description' => $description,
        'default_sidebars' => $default_sidebars
    );
    SB_Field::widget_area($args);
}