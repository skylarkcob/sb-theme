<?php
defined('ABSPATH') or die('Please do not pip me!');
$lang = SB_Core::get_language();
/*
 * Cài đặt thêm ảnh thumbnail cho chuyên mục
 */
$args = array(
    'title' => ('vi' == $lang) ? 'Ảnh thumbnail cho chuyên mục' : __('Term thumbnail', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Tương tự như bài viết, bạn cũng có thể tạo ảnh đại diện cho chuyên mục.' : __('Similar posts, you can also create thumbnail image for terms.', 'sb-theme'),
    'callback' => 'sb_theme_advanced_setting_general_term_thumbnail'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_general_term_thumbnail() {
    $lang = SB_Core::get_language();
    $tab_base_option_name = 'general';
    $key = 'use_term_thumbnail';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => ('vi' == $lang) ? 'Sử dụng ảnh thumbnail cho chuyên mục?' : __('Use thumbnail image for terms?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $args = array(
            'title' => ('vi' == $lang) ? 'Chọn taxonomy hỗ trợ thumbnail' : __('Choose taxonomies use thumbnail image', 'sb-theme'),
            'description' => ('vi' == $lang) ? 'Hãy chọn những taxonomy mà bạn muốn hỗ trợ sử dụng ảnh thumbnail cho term.' : __('Choose these taxonoies you want its terms has thumbnail image.', 'sb-theme'),
            'callback' => 'sb_theme_advanced_setting_general_taxonomy_use_thumbnail'
        );
        SB_Admin_Custom::row_setting_field($args);
    }
}

function sb_theme_advanced_setting_general_taxonomy_use_thumbnail() {
    $tab_base_option_name = 'general';
    $key = 'taxonomy_use_thumbnail';
	$actives = SB_Option::get_taxonomy_use_thumbnail();
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'sortable_callback' => 'sb_theme_advanced_setting_general_taxonomy_use_thumbnail_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_general_taxonomy_use_thumbnail_sortable_active_callback',
        'sortable_description' => ('vi' == SB_Core::get_language()) ? 'Tất cả các taxonomy hiện có trên website.' : __('All taxonomies on your website.', 'sb-theme'),
        'sortable_active_description' => ('vi' == SB_Core::get_language()) ? 'Taxonomy được hỗ trợ cho phép term hiển thị ảnh thumbnail.' : __('Taxonomies are allowed terms have thumbnail iamge.', 'sb-theme'),
        'before' => '',
        'field_class' => 'display-inline',
        'click_to_connect' => true,
	    'value' => implode(',', $actives)
    );
    SB_Field::sortable_ui_connect_row($args);
}

function sb_theme_advanced_setting_general_taxonomy_use_thumbnail_sortable_callback() {
    $taxs = get_taxonomies(array(), 'objects');
    $actives = SB_Option::get_taxonomy_use_thumbnail();
    foreach($taxs as $key => $value) {
        if(in_array($key, $actives)) {
            continue;
        }
        $class = 'ui-state-default';
        if('nav_menu' == $key || 'link_category' == $key || 'post_format' == $key) {
            $class = 'ui-state-disabled';
        }
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value->labels->singular_name . '</li>';
    }
}

function sb_theme_advanced_setting_general_taxonomy_use_thumbnail_sortable_active_callback() {
    $actives = SB_Option::get_taxonomy_use_thumbnail();
    foreach($actives as $key) {
        $tax = get_taxonomy($key);
        if(!is_object($tax)) {
            continue;
        }
        $value = $tax->labels->singular_name;
        $class = 'ui-state-default';
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
    }
}

/*
 * Cài đặt hệ thống địa giới hành chính
 */
$args = array(
    'title' => ('vi' == $lang) ? 'Địa giới hành chính' : __('Administrative boundaries', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Chức năng địa giới hành chính sẽ phù hợp với các nội dung có liên quan đến vị trí địa lý.' : __('Administrative boundary function will match the content related to the geographic location.', 'sb-theme'),
    'callback' => 'sb_theme_advanced_setting_general_administrative_boundaries'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_general_administrative_boundaries() {
    $tab_base_option_name = 'general';
    $key = 'use_administrative_boundaries';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => ('vi' == SB_Core::get_language()) ? 'Tạo taxonomy quản lý thông tin các tỉnh, quận, huyện?' : __('Create taxonomy manages province, district, wards,... information?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $args = array(
            'title' => ('vi' == SB_Core::get_language()) ? 'Chọn post type sử dụng địa giới hành chính' : __('Choose post types use administrative boudaries', 'sb-theme'),
            'description' => ('vi' == SB_Core::get_language()) ? 'Hãy chọn những post type mà bạn muốn sử dụng thông tin địa giới hành chính.' : __('Let choose post types that you want they have have administrative boundaries information.', 'sb-theme'),
            'callback' => 'sb_theme_advanced_setting_general_post_type_use_administrative_boundaries'
        );
        SB_Admin_Custom::row_setting_field($args);
    }
}

function sb_theme_advanced_setting_general_post_type_use_administrative_boundaries() {
    $tab_base_option_name = 'general';
    $key = 'post_type_use_administrative_boundaries';
	$actives = SB_Option::get_post_type_use_administrative_boundaries();
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'sortable_callback' => 'sb_theme_advanced_setting_general_post_type_use_administrative_boundaries_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_general_post_type_use_administrative_boundaries_sortable_active_callback',
        'sortable_description' => ('vi' == SB_Core::get_language()) ? 'Tất cả các post type hiện có trên website.' : __('All post types on your website.', 'sb-theme'),
        'sortable_active_description' => ('vi' == SB_Core::get_language()) ? 'Post type được hỗ trợ cho phép sử dụng thông tin địa giới hành chính.' : __('Post types are allowed to use administrative boundaries.', 'sb-theme'),
        'before' => '',
        'field_class' => 'display-inline',
        'click_to_connect' => true,
	    'value' => implode(',', $actives)
    );
    SB_Field::sortable_ui_connect_row($args);
}

function sb_theme_advanced_setting_general_post_type_use_administrative_boundaries_sortable_callback() {
    $lists = get_post_types(array('public' => true), 'objects');
    $actives = SB_Option::get_post_type_use_administrative_boundaries();
    foreach($lists as $key => $value) {
        if(in_array($key, $actives)) {
            continue;
        }
        $class = 'ui-state-default';
        if('attachment' == $key || 'revision' == $key || 'nav_menu_item' == $key) {
            $class = 'ui-state-disabled';
        }
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value->labels->singular_name . '</li>';
    }
}

function sb_theme_advanced_setting_general_post_type_use_administrative_boundaries_sortable_active_callback() {
    $actives = SB_Option::get_post_type_use_administrative_boundaries();
    foreach($actives as $key) {
        $tax = get_post_type_object($key);
        if(!is_object($tax)) {
            continue;
        }
        $value = $tax->labels->singular_name;
        $class = 'ui-state-default';
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
    }
}

/*
 * Cài đặt hệ thống tạo cache cho website
 */
$args = array(
    'title' => ('vi' == $lang) ? 'Hệ thống transient' : __('Transient cache', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Transient là chức năng cho phép bạn lưu lại dữ liệu vào cơ sở dữ liệu, bạn có thể dùng chức năng này để tăng tốc độ tải trang web.' : __('Transients API offers a simple and standardized way of storing cached data in the database temporarily by giving it a custom name and a timeframe after which it will expire and be deleted.', 'sb-theme'),
    'callback' => 'sb_theme_advanced_setting_general_cache'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_general_cache() {
    $lang = SB_Core::get_language();
    $menu_base = 'advanced';
    $tab_base_option_name = 'general';

    $key = 'cache_enabled';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 0);
    $args = array(
        'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => ('vi' == $lang) ? 'Kích hoạt chức năng transient?' : __('Activate the theme to use Transients API?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    if((bool)$value) {
        $key = 'query_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Lưu cache cho truy vấn bài viết?' : __('Use transient cache for posts query?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'menu_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Lưu cache cho menu?' : __('Use transient cache for menu?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'widget_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Lưu cache cho widget?' : __('Use transient cache for widget?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'post_images_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Lưu cache cho hình ảnh trong bài viết?' : __('Use transient cache for post thumbnail?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);

        $key = 'post_comment_count_cache_enabled';
        $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
        $value = SB_Option::check_switch_value($value, 0);
        $args = array(
            'id' => 'sb_theme_' . $menu_base . '_' . $tab_base_option_name . '_' . $key,
            'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
            'label' => ('vi' == $lang) ? 'Lưu cache cho số bình luận của bài viết?' : __('Use transient cache for post comment count?', 'sb-theme'),
            'value' => $value
        );
        SB_Field::checkbox($args);
    }
}