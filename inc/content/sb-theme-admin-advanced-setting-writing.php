<?php
defined('ABSPATH') or die('Please do not pip me!');
$lang = SB_Core::get_language();
global $sb_theme_roles;
$sb_theme_roles = SB_Membership::get_roles();

$args = array(
    'title' => ('vi' == $lang) ? 'Trang viết bài' : __('Post writing', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Các thông tin liên quan đến trang đăng bài viết và chỉnh sửa bài viết.' : __('The functions on page when user create post or edit post.', 'sb-theme'),
    'callback' => 'sb_theme_advanced_setting_writing_add_post_page_group'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_add_post_page_group() {
    $tab_base_option_name = 'writing';
    $lang = SB_Core::get_language();
    $key = 'confirm_publish';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 1);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => ('vi' == $lang) ? 'Kích hoạt chức năng hiển thị thông báo xác nhận đăng bài?' : __('Use functions to showing confirm message before post published?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    $key = 'restore_link_title';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 1);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => ('vi' == $lang) ? 'Khôi phục chức năng điền tiêu đề cho link?' : __('Use title field when insert link to post?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    $key = 'page_add_post_front_end';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => ('vi' == $lang) ? 'Trang đăng tin' : __('Create post page', 'sb-theme'),
        'value' => $value
    );
    SB_Field::select_page($args);
}
$text = 'Bạn có thể cài đặt giới hạn dung lượng tập tin, giới hạn dung lượng hình ảnh,' . esc_html(SB_THEME_THREE_DOT) . ' và các vấn đề liên quan đến media tại đây.';
if('vi' != $lang) {
    $text = __('Media is the place for you to upload the files and share it on website.', 'sb-theme');
}
$args = array(
    'title' => 'Media',
    'description' => $text,
    'callback' => 'sb_theme_advanced_setting_writing_media_group'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_media_group() {
    $tab_base_option_name = 'writing';
    $lang = SB_Core::get_language();
    $key = 'limit_file_size';
    $value = SB_Core::get_file_size_limit();
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'value' => $value,
        'label' => ('vi' == $lang) ? 'Dung lượng tập tin' : __('File size', 'sb-theme'),
        'description' => ('vi' == $lang) ? 'Dung lượng tối đa cho phép tập tin được tải lên hosting, dung lượng được tính bằng KB.' : __('Maximum file size allows to upload to hosting, file size is in KB.', 'sb-theme'),
        'autocomplete' => false,
        'attributes' => array(
            'min' => 0
        )
    );
    SB_Field::number($args);

    $key = 'allow_image_type';
    $value = implode(',', SB_Core::get_image_type_allow());
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'value' => $value,
        'label' => ('vi' == $lang) ? 'Đuôi hình ảnh' : __('Image extesion', 'sb-theme'),
        'description' => ('vi' == $lang) ? 'Những định dạng đuôi hình ảnh cho phép người dùng được tải lên hosting, mỗi định dạng được cách nhau bằng dấu phẩy. Ví dụ: image/jpeg,image/png.' : __('The image extesion that allows users to upload to hosting, each format are separated by commas. For example: image/jpeg,image/png.', 'sb-theme'),
        'autocomplete' => false
    );
    SB_Field::text($args);

    $key = 'media_link_to';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $options = array(
        'none' => ('vi' == $lang) ? 'Không dùng link' : __('No link', 'sb-theme'),
        'file' => ('vi' == $lang) ? 'Đường dẫn tới tập tin' : __('Link to media file', 'sb-theme'),
        'custom' => ('vi' == $lang) ? 'Đường dẫn tùy chọn' : __('Custom link', 'sb-theme'),
        'post' => ('vi' == $lang) ? 'Đường dẫn đến bài viết' : __('Link to post', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'value' => $value,
        'label' => ('vi' == $lang) ? 'Link cho media' : __('Media link', 'sb-theme'),
        'description' => ('vi' == $lang) ? 'Cài đặt link mặc định cho media khi chèn vào bài viết.' : __('The default link for media file when insert it to post.', 'sb-theme'),
        'autocomplete' => false,
        'options' => $options
    );
    SB_Field::select($args);
}

$args = array(
    'title' => ('vi' == $lang) ? 'Hạn chế quyền đăng bài viết' : __('Create post permission', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Bạn có thể thiết lập cho thành viên thuộc các nhóm bên dưới không có quyền đăng bài viết mà chỉ được soạn nháp.' : __('You can set up for members in groups have no permission to publish post but they still save draft post.', 'sb-theme'),
    'callback' => 'sb_theme_advanced_setting_writing_limit_publish_post'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_limit_publish_post() {
    $tab_base_option_name = 'writing';
    $lang = SB_Core::get_language();
    $key = 'limit_publish_post_role';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'sortable_callback' => 'sb_theme_advanced_setting_writing_limit_publish_post_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_writing_limit_publish_post_sortable_active_callback',
        'sortable_description' => ('vi' == $lang) ? 'Tất cả các nhóm người dùng hiện có trên website.' : __('All user groups on your website.', 'sb-theme'),
        'sortable_active_description' => ('vi' == $lang) ? 'Nhóm người dùng được áp dụng hạn chế đăng bài viết.' : __('User groups have no permission to publish post.', 'sb-theme'),
        'before' => '',
        'field_class' => 'display-inline',
        'click_to_connect' => true,
        'value' => $value
    );
    SB_Field::sortable_ui_connect_row($args);
}

function sb_theme_advanced_setting_writing_limit_publish_post_sortable_callback() {
    global $sb_theme_roles;
    $active_roles = SB_Membership::get_limit_publish_post_roles();
    foreach($sb_theme_roles as $key => $value) {
        if(in_array($key, $active_roles)) {
            continue;
        }
        $class = 'ui-state-default';
        if('administrator' == $key || 'super_administrator' == $key || 'banned' == $key) {
            $class = 'ui-state-disabled';
        }
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
    }
}

function sb_theme_advanced_setting_writing_limit_publish_post_sortable_active_callback() {
    $active_roles = SB_Membership::get_limit_publish_post_roles();
    $roles = $active_roles;
    foreach($roles as $key) {
        $role = SB_Membership::get_role($key);
        $value = SB_Membership::get_role_label($role->name);
        $class = 'ui-state-default';
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
    }
}

$args = array(
    'title' => ('vi' == $lang) ? 'Hạn chế quyền xóa bài viết' : __('Delete post permission', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Bạn có thể thiết lập cho thành viên thuộc các nhóm bên dưới không có quyền xóa bài viết.' : __('You can set up for members in groups have no permission to delete post.', 'sb-theme'),
    'callback' => 'sb_theme_advanced_setting_writing_limit_delete_post'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_limit_delete_post() {
    $tab_base_option_name = 'writing';
    $lang = SB_Core::get_language();
    $key = 'limit_delete_post_role';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'sortable_callback' => 'sb_theme_advanced_setting_writing_limit_delete_post_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_writing_limit_delete_post_sortable_active_callback',
        'sortable_description' => ('vi' == $lang) ? 'Tất cả các nhóm người dùng hiện có trên website.' : __('All user groups on your website.', 'sb-theme'),
        'sortable_active_description' => ('vi' == $lang) ? 'Nhóm người dùng được áp dụng hạn chế đăng bài viết.' : __('User groups have no permission to delete post.', 'sb-theme'),
        'before' => '',
        'field_class' => 'display-inline',
        'click_to_connect' => true,
        'value' => $value
    );
    SB_Field::sortable_ui_connect_row($args);
}

function sb_theme_advanced_setting_writing_limit_delete_post_sortable_callback() {
    global $sb_theme_roles;
    $active_roles = SB_Membership::get_limit_delete_post_roles();
    $added_roles = array();
    foreach($sb_theme_roles as $key => $value) {
        if(in_array($key, $active_roles) || in_array($key, $added_roles)) {
            continue;
        }
        $class = 'ui-state-default';
        if('administrator' == $key || 'super_administrator' == $key || 'banned' == $key) {
            $class = 'ui-state-disabled';
        }
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
        $added_roles[] = $key;
    }
}

function sb_theme_advanced_setting_writing_limit_delete_post_sortable_active_callback() {
    $active_roles = SB_Membership::get_limit_delete_post_roles();
    $roles = $active_roles;
    foreach($roles as $key) {
        $role = SB_Membership::get_role($key);
        $value = SB_Membership::get_role_label($role->name);
        $class = 'ui-state-default';
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
    }
}

$args = array(
    'title' => ('vi' == $lang) ? 'Hạn chế quyền xóa bài viết đã được đăng' : __('Delete published post permission', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Bạn có thể thiết lập cho thành viên thuộc các nhóm bên dưới không có quyền xóa bài viết đã được đăng.' : __('You can set up for members in groups have no permission to delete published post.', 'sb-theme'),
    'callback' => 'sb_theme_advanced_setting_writing_limit_delete_published_post'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_limit_delete_published_post() {
    $tab_base_option_name = 'writing';
    $lang = SB_Core::get_language();
    $key = 'limit_delete_published_post_role';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'sortable_callback' => 'sb_theme_advanced_setting_writing_limit_delete_published_post_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_writing_limit_delete_published_post_sortable_active_callback',
        'sortable_description' => ('vi' == $lang) ? 'Tất cả các nhóm người dùng hiện có trên website.' : __('All user groups on your website.', 'sb-theme'),
        'sortable_active_description' => ('vi' == $lang) ? 'Nhóm người dùng được áp dụng hạn chế xóa bài viết đã được đăng.' : __('User groups have no permission to delete published post.', 'sb-theme'),
        'before' => '',
        'field_class' => 'display-inline',
        'click_to_connect' => true,
        'value' => $value
    );
    SB_Field::sortable_ui_connect_row($args);
}

function sb_theme_advanced_setting_writing_limit_delete_published_post_sortable_callback() {
    global $sb_theme_roles;
    $active_roles = SB_Membership::get_limit_delete_published_post_roles();
    $added_roles = array();
    foreach($sb_theme_roles as $key => $value) {
        if(in_array($key, $active_roles) || in_array($key, $added_roles)) {
            continue;
        }
        $class = 'ui-state-default';
        if('administrator' == $key || 'super_administrator' == $key || 'banned' == $key) {
            $class = 'ui-state-disabled';
        }
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
        $added_roles[] = $key;
    }
}

function sb_theme_advanced_setting_writing_limit_delete_published_post_sortable_active_callback() {
    $active_roles = SB_Membership::get_limit_delete_published_post_roles();
    $roles = $active_roles;
    foreach($roles as $key) {
        $role = SB_Membership::get_role($key);
        $value = SB_Membership::get_role_label($role->name);
        $class = 'ui-state-default';
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
    }
}

$args = array(
    'title' => ('vi' == $lang) ? 'Hạn chế quyền sửa bài viết đã được đăng' : __('Edit published post permission', 'sb-theme'),
    'description' => ('vi' == $lang) ? 'Bạn có thể thiết lập cho thành viên thuộc các nhóm bên dưới không có quyền chỉnh sửa bài viết đã được đăng.' : __('You can set up for members in groups have no permission to edit published post.', 'sb-theme'),
    'callback' => 'sb_theme_advanced_setting_writing_limit_edit_published_post'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_limit_edit_published_post() {
    $tab_base_option_name = 'writing';
    $lang = SB_Core::get_language();
    $key = 'limit_edit_published_post_role';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'sortable_callback' => 'sb_theme_advanced_setting_writing_limit_edit_published_post_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_writing_limit_edit_published_post_sortable_active_callback',
        'sortable_description' => ('vi' == $lang) ? 'Tất cả các nhóm người dùng hiện có trên website.' : __('All user groups on your website.', 'sb-theme'),
        'sortable_active_description' => ('vi' == $lang) ? 'Nhóm người dùng được áp dụng hạn chế sửa bài viết đã được đăng.' : __('User groups have no permission to edit published post.', 'sb-theme'),
        'before' => '',
        'field_class' => 'display-inline',
        'click_to_connect' => true,
        'value' => $value
    );
    SB_Field::sortable_ui_connect_row($args);
}

function sb_theme_advanced_setting_writing_limit_edit_published_post_sortable_callback() {
    global $sb_theme_roles;
    $active_roles = SB_Membership::get_limit_edit_published_post_roles();
    $added_roles = array();
    foreach($sb_theme_roles as $key => $value) {
        if(in_array($key, $active_roles) || in_array($key, $added_roles)) {
            continue;
        }
        $class = 'ui-state-default';
        if('administrator' == $key || 'super_administrator' == $key || 'banned' == $key) {
            $class = 'ui-state-disabled';
        }
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
        $added_roles[] = $key;
    }
}

function sb_theme_advanced_setting_writing_limit_edit_published_post_sortable_active_callback() {
    $active_roles = SB_Membership::get_limit_edit_published_post_roles();
    $roles = $active_roles;
    foreach($roles as $key) {
        $role = SB_Membership::get_role($key);
        $value = SB_Membership::get_role_label($role->name);
        $class = 'ui-state-default';
        echo '<li data-value="' . $key . '" class="' . $class . '">' . $value . '</li>';
    }
}