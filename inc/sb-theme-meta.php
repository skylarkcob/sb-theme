<?php
defined('ABSPATH') or die('Please do not pip me!');

/*
 * Tạo thumbnail cho chuyên mục
 */
function sb_theme_term_meta_thumbnail($taxonomies = array()) {
    $fields = array(
        array(
            'name' => 'thumbnail',
            'type' => 'array'
        )
    );
    $args = array(
        'taxonomies' => $taxonomies,
        'callback' => 'sb_theme_meta_term_thumbnail_callback',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);
}

function sb_theme_meta_term_thumbnail_callback( $term ) {
    SB_Theme::the_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, 'thumbnail');

    $args = array(
        'id' => 'sb_theme_term_thumbnail',
        'label' => __('Ảnh thumbnail', 'sb-theme'),
        'name' => 'thumbnail',
        'value' => $value,
        'description' => __('Bạn có thể nhập địa chỉ hoặc tải lên hình ảnh mới.', 'sb-theme'),
        'autocomplete' => false
    );
    SB_Term_Field::image_upload($args);
}

if(SB_Tool::use_term_thumbnail()) {
    $taxs = SB_Option::get_taxonomy_use_thumbnail();
    if(count($taxs) < 1) {
        $taxs[] = 'category';
    }
    sb_theme_term_meta_thumbnail($taxs);
}

/*
 * Lựa chọn màu sắc cho chuyên mục
 */
function sb_theme_term_meta_color($taxonomies = array()) {
    $fields = array(
        array(
            'name' => 'color',
            'type' => 'text'
        )
    );
    $args = array(
        'taxonomies' => $taxonomies,
        'callback' => 'sb_theme_meta_term_color_callback',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);
}

function sb_theme_meta_term_color_callback( $term ) {
    SB_Theme::the_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, 'color');
    $args = array(
        'id' => 'sb_theme_term_color',
        'label' => __('Màu sắc', 'sb-theme'),
        'name' => 'color',
        'value' => $value,
        'description' => __('Lựa chọn màu sắc đại diện cho chuyên mục.', 'sb-theme'),
        'autocomplete' => false
    );
    SB_Term_Field::color_picker($args);
}

/*
 * Tạo mối liên hệ giữa các địa giới hành chính
 */
if(SB_Tool::use_administrative_boundaries()) {
    $use_taxonomies = SB_Core::get_administrative_boundaries_taxonomies();

    // Quận huyện thuộc tỉnh thành
    if(in_array('province', $use_taxonomies)) {
        $fields = array(
            array(
                'name' => 'province',
                'type' => 'number'
            )
        );
        $args = array(
            'taxonomies' => array('district'),
            'callback' => 'sb_theme_term_meta_district_province_edit',
            'create_callback' => 'sb_theme_term_meta_district_province_create',
            'fields' => $fields
        );
        $term_meta = new SB_Term_Meta($args);
    }

    // Phường xã thuộc quận huyện
    $key = 'ward_district';

    $fields = array(
        array(
            'name' => 'district',
            'type' => 'number'
        )
    );
    $args = array(
        'taxonomies' => array('ward'),
        'callback' => 'sb_theme_term_meta_' . $key . '_edit',
        'create_callback' => 'sb_theme_term_meta_' . $key . '_create',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);

    // Thôn xóm thuộc phường xã, thôn xóm thuộc quận huyện
    $key = 'hamlet_ward';

    $fields = array(
        array(
            'name' => 'district',
            'type' => 'number'
        )
    );

    if(SB_Core::is_hamlet_of_ward()) {
        $fields[] = array(
            'name' => 'ward',
            'type' => 'number'
        );
    }

    $args = array(
        'taxonomies' => array('hamlet'),
        'callback' => 'sb_theme_term_meta_' . $key . '_edit',
        'create_callback' => 'sb_theme_term_meta_' . $key . '_create',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);

    // Đường phố thuộc quận huyện, đường phố thuộc xã phường
    $key = 'street_district';

    $fields = array(
        array(
            'name' => 'district',
            'type' => 'number'
        )
    );

    if(SB_Core::is_street_of_ward()) {
        $fields[] = array(
            'name' => 'ward',
            'type' => 'number'
        );
    }

    $args = array(
        'taxonomies' => array('street'),
        'callback' => 'sb_theme_term_meta_' . $key . '_edit',
        'create_callback' => 'sb_theme_term_meta_' . $key . '_create',
        'fields' => $fields
    );
    $term_meta = new SB_Term_Meta($args);
}

// Lựa chọn tỉnh thành cho quận huyện khi chỉnh sửa
function sb_theme_term_meta_district_province_edit( $term ) {
    SB_Theme::the_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, 'province');
    $args = array(
        'id' => 'sb_theme_term_meta_district_province',
        'label' => __('Tỉnh thành', 'sb-theme'),
        'name' => 'province',
        'value' => $value,
        'taxonomy' => 'province',
        'description' => __('Lựa chọn tỉnh thành mà quận huyện hiện tại thuộc về.', 'sb-theme')
    );
    SB_Term_Field::select_term($args);
}

// Lựa chọn tỉnh thành cho quận huyện khi tạo
function sb_theme_term_meta_district_province_create() {
    SB_Theme::the_term_meta_nonce();
    $args = array(
        'id' => 'sb_theme_term_meta_district_province',
        'label' => __('Tỉnh thành', 'sb-theme'),
        'name' => 'province',
        'value' => 0,
        'taxonomy' => 'province',
        'before' => '<div class="form-field term-province-wrap">',
        'after' => '</div>',
        'description' => __('Lựa chọn tỉnh thành mà quận huyện hiện tại thuộc về.', 'sb-theme'),
        'autocomplete' => true
    );
    SB_Term_Field::select_term($args);
}

// Lựa chọn quận huyện cho phường xã khi chỉnh sửa
function sb_theme_term_meta_ward_district_edit( $term ) {
    $name = 'district';
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, $name);
    $key = 'ward_district';
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => $value,
        'taxonomy' => $name,
        'description' => __('Lựa chọn quận huyện mà phường xã hiện tại thuộc về.', 'sb-theme')
    );
    SB_Term_Field::select_term($args);
}

// Lựa chọn quận huyện cho phường xã khi tạo
function sb_theme_term_meta_ward_district_create() {
    $key = 'ward_district';
    $name = 'district';
    SB_Theme::the_term_meta_nonce();
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => 0,
        'taxonomy' => $name,
        'before' => '<div class="form-field term-' . $name . '-wrap">',
        'after' => '</div>',
        'description' => __('Lựa chọn quận huyện mà phường xã hiện tại thuộc về.', 'sb-theme'),
        'autocomplete' => true
    );
    SB_Term_Field::select_term($args);
}

// Lựa chọn quận huyện và phường xã cho thôn xóm khi chỉnh sửa
function sb_theme_term_meta_hamlet_ward_edit( $term ) {
    $name = 'district';
    SB_Theme::the_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, $name);
    $key = 'hamlet_district';
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => $value,
        'taxonomy' => $name,
        'description' => __('Lựa chọn quận huyện mà thôn xóm hiện tại thuộc về.', 'sb-theme')
    );
    SB_Term_Field::select_term($args);

    if(SB_Core::is_hamlet_of_ward()) {
        $name = 'ward';
        $value = SB_Term::get_meta($term->term_id, $term->taxonomy, $name);
        $key = 'hamlet_ward';
        $args = array(
            'id' => 'sb_theme_term_meta_' . $key,
            'label' => __('Phường xã', 'sb-theme'),
            'name' => $name,
            'value' => $value,
            'taxonomy' => $name,
            'description' => __('Lựa chọn phường xã mà thôn xóm hiện tại thuộc về.', 'sb-theme')
        );
        SB_Term_Field::select_term($args);
    }
}

// Lựa chọn quận huyện và phường xã cho thôn xóm khi tạo
function sb_theme_term_meta_hamlet_ward_create() {
    $key = 'hamlet_district';
    $name = 'district';
    SB_Theme::the_term_meta_nonce();
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => 0,
        'taxonomy' => $name,
        'before' => '<div class="form-field term-' . $name . '-wrap">',
        'after' => '</div>',
        'description' => __('Lựa chọn quận huyện mà thôn xóm hiện tại thuộc về.', 'sb-theme'),
        'autocomplete' => true
    );
    SB_Term_Field::select_term($args);

    if(SB_Core::is_hamlet_of_ward()) {
        $key = 'hamlet_ward';
        $name = 'ward';
        $args = array(
            'id' => 'sb_theme_term_meta_' . $key,
            'label' => __('Phường xã', 'sb-theme'),
            'name' => $name,
            'value' => 0,
            'taxonomy' => $name,
            'before' => '<div class="form-field term-' . $name . '-wrap">',
            'after' => '</div>',
            'description' => __('Lựa chọn phường xã mà thôn xóm hiện tại thuộc về.', 'sb-theme'),
            'autocomplete' => true
        );
        SB_Term_Field::select_term($args);
    }
}

// Lựa chọn quận huyện và phường xã cho đường phố khi chỉnh sửa
function sb_theme_term_meta_street_district_edit( $term ) {
    $name = 'district';
    SB_Theme::the_term_meta_nonce();
    $value = SB_Term::get_meta($term->term_id, $term->taxonomy, $name);
    $current_district = $value;
    $key = 'street_district';
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => $value,
        'taxonomy' => $name,
        'description' => __('Lựa chọn quận huyện mà đường phố hiện tại thuộc về.', 'sb-theme')
    );
    SB_Term_Field::select_term($args);

    if(SB_Core::is_street_of_ward()) {
        $key = 'street_ward';
        $name = 'ward';
        $value = SB_Term::get_meta($term->term_id, $term->taxonomy, $name);
        $args = array(
            'id' => 'sb_theme_term_meta_' . $key,
            'label' => __('Phường xã', 'sb-theme'),
            'name' => $name,
            'value' => $value,
            'taxonomy' => $name,
            'before' => '<div class="form-field term-' . $name . '-wrap">',
            'after' => '</div>',
            'description' => __('Lựa chọn phường xã mà đường phố hiện tại thuộc về.', 'sb-theme'),
            'autocomplete' => true,
            'force_empty' => true,
            'load_item' => false
        );
        if($current_district > 0) {
            $terms = SB_Term::get_ward_by_district($current_district);
            $custom_options = '';
            foreach($terms as $cat) {
                if(!is_object($cat)) {
                    continue;
                }
                $custom_options .= SB_Field::get_option(array('value' => $cat->term_id, 'attributes' => array('data-taxonomy' => $name), 'selected' => $value, 'text' => $cat->name));
            }
            $args['custom_options'] = $custom_options;
        }
        SB_Term_Field::select_term($args);
    }
}

// Lựa chọn quận huyện và phường xã cho đường phố khi tạo
function sb_theme_term_meta_street_district_create() {
    $key = 'street_district';
    $name = 'district';
    SB_Theme::the_term_meta_nonce();
    $args = array(
        'id' => 'sb_theme_term_meta_' . $key,
        'label' => __('Quận huyện', 'sb-theme'),
        'name' => $name,
        'value' => 0,
        'taxonomy' => $name,
        'before' => '<div class="form-field term-' . $name . '-wrap">',
        'after' => '</div>',
        'description' => __('Lựa chọn quận huyện mà đường phố hiện tại thuộc về.', 'sb-theme'),
        'autocomplete' => true
    );
    SB_Term_Field::select_term($args);

    if(SB_Core::is_street_of_ward()) {
        $key = 'street_ward';
        $name = 'ward';
        $args = array(
            'id' => 'sb_theme_term_meta_' . $key,
            'label' => __('Phường xã', 'sb-theme'),
            'name' => $name,
            'value' => 0,
            'taxonomy' => $name,
            'before' => '<div class="form-field term-' . $name . '-wrap">',
            'after' => '</div>',
            'description' => __('Lựa chọn phường xã mà đường phố hiện tại thuộc về.', 'sb-theme'),
            'autocomplete' => true,
            'force_empty' => true,
            'load_item' => false
        );
        if(isset($_SESSION['sb_theme_current_district'])) {
            $current_district = absint($_SESSION['sb_theme_current_district']);
            if($current_district > 0) {
                $terms = SB_Term::get_ward_by_district($current_district);
                $custom_options = '';
                foreach($terms as $cat) {
                    if(!is_object($cat)) {
                        continue;
                    }
                    $custom_options .= SB_Field::get_option(array('value' => $cat->term_id, 'attributes' => array('data-taxonomy' => $name), 'selected' => 0, 'text' => $cat->name));
                }
                $args['custom_options'] = $custom_options;
            }
        }
        SB_Term_Field::select_term($args);
    }
}

/*
 * Tạo meta box cho post type, người dùng chọn thông tin địa giới hành chính
 */
if(SB_Tool::use_administrative_boundaries()) {
    $use_taxonomies = SB_Core::get_administrative_boundaries_taxonomies();
    $post_types = SB_Option::get_post_type_use_administrative_boundaries();
    $post_type = SB_Core::get_add_new_post_type();
    if(in_array($post_type, $post_types)) {
        $fields = array();
        foreach($use_taxonomies as $tax_id) {
            $fields[] = array('name' => $tax_id, 'type' => 'number');
        }
        $args = array(
            'title' => apply_filters('sb_theme_meta_box_administrative_boundaries_title', __('Thông tin địa giới hành chính', 'sb-theme')),
            'id' => 'sb_theme_metabox_administrative_boundaries',
            'post_types' => SB_Option::get_post_type_use_administrative_boundaries(),
            'fields' => $fields,
            'callback' => 'sb_theme_metabox_administrative_boundaries_callback'
        );
        $meta_box = new SB_Meta_Box($args);
    }
}

function sb_theme_metabox_administrative_boundaries_callback() {
    sb_theme_get_meta_box('meta-box-administrative-boundaries');
}

/*
 * Thêm mục vào khung publish khi tạo mới hoặc sửa bài viết
 */
function sb_theme_publish_box_meta_field() {
    global $post;
    if(!SB_Core::is_valid_object($post)) {
        return;
    }
    $post_type = $post->post_type;
    $post_types = SB_Core::publish_box_meta_field_post_types();
    if(in_array($post_type, $post_types)) {
        SB_Theme::the_meta_box_nonce();
        $post_types_use_featured_meta_field = SB_Core::post_type_use_featured_meta_field();
        if(SB_Tool::post_use_featured_meta() && in_array($post_type, $post_types_use_featured_meta_field)) {
            $key = 'featured';
            $value = 0;
            if(SB_Post::is($post)) {
                $value = SB_Post::get_meta($post->ID, $key);
            }
            $args = array(
                'id' => 'sb_theme_meta_featured_post',
                'name' => $key,
                'value' => $value,
                'label' => __('Featured?', 'sb-theme'),
                'container_class' => 'misc-pub-section',
                'option_value' => 1
            );
            SB_Field::checkbox($args);
        }
        do_action('sb_theme_post_publish_box_meta_field');
        if('post' != $post_type) {
            do_action('sb_theme_' . $post_type . '_publish_box_meta_field');
        }
    }
}
add_action('post_submitbox_misc_actions', 'sb_theme_publish_box_meta_field');

function sb_theme_publish_box_meta_field_save($post_id) {
    if(!SB_Core::check_before_save_post_meta($post_id)) {
        return $post_id;
    }
    if(SB_Tool::post_use_featured_meta()) {
        $value = isset($_POST['featured']) ? 1 : 0;
        SB_Post::update_meta($post_id, 'featured', $value);
    }
    return $post_id;
}
add_action('save_post', 'sb_theme_publish_box_meta_field_save');

/*
 * Tạo thông tin mở rộng cho quảng cáo
 */
$fields = array(
    array(
        'name' => 'ads_position',
        'type' => 'text'
    ),
    array(
        'name' => 'ads_expire_date',
        'type' => 'datetime'
    ),
    array(
        'name' => 'ads_link',
        'type' => 'url'
    ),
    array(
        'name' => 'ads_image',
        'type' => 'media_image'
    ),
    array(
        'name' => 'ads_code',
        'type' => 'textarea'
    ),
    array(
        'name' => 'ads_active',
        'type' => 'checkbox'
    )
);

$args = array(
    'fields' => $fields,
    'id' => 'sb_theme_ads_information',
    'callback' => 'sb_theme_ads_information_callback',
    'post_type' => SB_Core::get_post_type_ads_name(),
    'title' => __('Thông tin quảng cáo', 'sb-theme')
);
$sb_theme_meta = new SB_Meta_Box($args);

function sb_theme_ads_information_callback() {
    SB_Theme::get_meta_box('meta-box-ads-information');
}

/*
 * Tạo thông tin mở rộng cho support
 */
$fields = array(
    array(
        'name' => 'support_position',
        'type' => 'text'
    ),
    array(
        'name' => 'support_name',
        'type' => 'text'
    ),
    array(
        'name' => 'support_yahoo',
        'type' => 'text'
    ),
    array(
        'name' => 'support_skype',
        'type' => 'text'
    ),
    array(
        'name' => 'support_phone',
        'type' => 'text'
    ),
    array(
        'name' => 'support_chuc_vu',
        'type' => 'text'
    ),
    array(
        'name' => 'support_avatar',
        'type' => 'media_image'
    ),
    array(
        'name' => 'support_active',
        'type' => 'checkbox'
    )
);

$args = array(
    'fields' => $fields,
    'id' => 'sb_theme_support_information',
    'callback' => 'sb_theme_support_information_callback',
    'post_type' => SB_Theme::get_post_type_support_name(),
    'title' => __('Thông tin hỗ trợ', 'sb-theme')
);
$sb_theme_meta = new SB_Meta_Box($args);

function sb_theme_support_information_callback() {
    SB_Theme::get_meta_box('meta-box-support-information');
}

/*
 * Tạo thông tin mở rộng cho slider
 */
$fields = array(
    array(
        'name' => 'slider_position',
        'type' => 'text'
    ),
    array(
        'name' => 'slider_active',
        'type' => 'checkbox'
    )
);

$args = array(
    'fields' => $fields,
    'id' => 'sb_theme_slider_information',
    'callback' => 'sb_theme_slider_information_callback',
    'post_type' => SB_Core::get_post_type_slider_name(),
    'title' => __('Thông tin slider', 'sb-theme')
);
$sb_theme_meta = new SB_Meta_Box($args);

function sb_theme_slider_information_callback() {
    SB_Theme::get_meta_box('meta-box-slider-information');
}

// Thông tin các item trong slider
$fields = array(
    array(
        'name' => 'slider_items',
        'type' => 'array'
    )
);

$args = array(
    'fields' => $fields,
    'id' => 'sb_theme_slider_items_information',
    'callback' => 'sb_theme_slider_items_information_callback',
    'post_type' => SB_Core::get_post_type_slider_name(),
    'title' => __('Các đối tượng hiển thị trên slider', 'sb-theme')
);
$sb_theme_meta = new SB_Meta_Box($args);

function sb_theme_slider_items_information_callback() {
    SB_Theme::get_meta_box('meta-box-slider-items-information');
}