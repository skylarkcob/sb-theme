<?php
global $sb_theme_roles;
$sb_theme_roles = SB_Membership::get_roles();

$args = array(
    'title' => 'Trang viết bài',
    'description' => 'Các thông tin liên quan đến trang đăng bài viết và chỉnh sửa bài viết.',
    'callback' => 'sb_theme_advanced_setting_writing_add_post_page_group'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_add_post_page_group() {
    $tab_base_option_name = 'writing';

    $key = 'confirm_publish';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 1);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Kích hoạt chức năng hiển thị thông báo xác nhận đăng bài?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    $key = 'restore_link_title';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 1);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Khôi phục chức năng điền tiêu đề cho link?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);

    $key = 'page_add_post_front_end';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Trang đăng tin', 'sb-theme'),
        'value' => $value
    );
    SB_Field::select_page($args);
}

$args = array(
    'title' => 'Media',
    'description' => 'Bạn có thể cài đặt giới hạn dung lượng tập tin, giới hạn dung lượng hình ảnh,... và các vấn đề liên quan đến media tại đây.',
    'callback' => 'sb_theme_advanced_setting_writing_media_group'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_media_group() {
    $tab_base_option_name = 'writing';

    $key = 'limit_file_size';
    $value = SB_Core::get_file_size_limit();
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'value' => $value,
        'label' => __('Dung lượng tập tin', 'sb-theme'),
        'description' => __('Dung lượng tối đa cho phép tập tin được tải lên hosting, dung lượng được tính bằng KB.', 'sb-theme'),
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
        'label' => __('Đuôi hình ảnh', 'sb-theme'),
        'description' => __('Những định dạng đuôi hình ảnh cho phép người dùng được tải lên hosting, mỗi định dạng được cách nhau bằng dấu phẩy. Ví dụ: image/jpeg,image/png.', 'sb-theme'),
        'autocomplete' => false
    );
    SB_Field::text($args);

    $key = 'media_link_to';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $options = array(
        'none' => __('Không dùng link', 'sb-theme'),
        'file' => __('Đường dẫn tới tập tin', 'sb-theme'),
        'custom' => __('Đường dẫn tùy chọn', 'sb-theme'),
        'post' => __('Đường dẫn đến bài viết', 'sb-theme')
    );
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'value' => $value,
        'label' => __('Link cho media', 'sb-theme'),
        'description' => __('Cài đặt link mặc định cho media khi chèn vào bài viết.', 'sb-theme'),
        'autocomplete' => false,
        'options' => $options
    );
    SB_Field::select($args);
}

$args = array(
    'title' => 'Hạn chế quyền đăng bài viết',
    'description' => 'Bạn có thể thiết lập cho thành viên thuộc các nhóm bên dưới không có quyền đăng bài viết mà chỉ được soạn nháp.',
    'callback' => 'sb_theme_advanced_setting_writing_limit_publish_post'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_limit_publish_post() {
    $tab_base_option_name = 'writing';

    $key = 'limit_publish_post_role';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Các nhóm người dùng hiện có trên website:', 'sb-theme'),
        'sortable_callback' => 'sb_theme_advanced_setting_writing_limit_publish_post_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_writing_limit_publish_post_sortable_active_callback',
        'sortable_description' => __('Tất cả các nhóm người dùng hiện có trên website.', 'sb-theme'),
        'sortable_active_description' => __('Nhóm người dùng được áp dụng hạn chế đăng bài viết.', 'sb-theme'),
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
    'title' => 'Hạn chế quyền xóa bài viết',
    'description' => 'Bạn có thể thiết lập cho thành viên thuộc các nhóm bên dưới không có quyền xóa bài viết.',
    'callback' => 'sb_theme_advanced_setting_writing_limit_delete_post'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_limit_delete_post() {
    $tab_base_option_name = 'writing';

    $key = 'limit_delete_post_role';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Các nhóm người dùng hiện có trên website:', 'sb-theme'),
        'sortable_callback' => 'sb_theme_advanced_setting_writing_limit_delete_post_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_writing_limit_delete_post_sortable_active_callback',
        'sortable_description' => __('Tất cả các nhóm người dùng hiện có trên website.', 'sb-theme'),
        'sortable_active_description' => __('Nhóm người dùng được áp dụng hạn chế xóa bài viết.', 'sb-theme'),
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
    'title' => 'Hạn chế quyền xóa bài viết đã được đăng',
    'description' => 'Bạn có thể thiết lập cho thành viên thuộc các nhóm bên dưới không có quyền xóa bài viết đã được đăng.',
    'callback' => 'sb_theme_advanced_setting_writing_limit_delete_published_post'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_limit_delete_published_post() {
    $tab_base_option_name = 'writing';

    $key = 'limit_delete_published_post_role';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Các nhóm người dùng hiện có trên website:', 'sb-theme'),
        'sortable_callback' => 'sb_theme_advanced_setting_writing_limit_delete_published_post_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_writing_limit_delete_published_post_sortable_active_callback',
        'sortable_description' => __('Tất cả các nhóm người dùng hiện có trên website.', 'sb-theme'),
        'sortable_active_description' => __('Nhóm người dùng được áp dụng hạn chế xóa bài viết đã được đăng.', 'sb-theme'),
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
    'title' => 'Hạn chế quyền sửa bài viết đã được đăng',
    'description' => 'Bạn có thể thiết lập cho thành viên thuộc các nhóm bên dưới không có quyền chỉnh sửa bài viết đã được đăng.',
    'callback' => 'sb_theme_advanced_setting_writing_limit_edit_published_post'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_limit_edit_published_post() {
    $tab_base_option_name = 'writing';

    $key = 'limit_edit_published_post_role';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_membership_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Các nhóm người dùng hiện có trên website:', 'sb-theme'),
        'sortable_callback' => 'sb_theme_advanced_setting_writing_limit_edit_published_post_sortable_callback',
        'sortable_active_callback' => 'sb_theme_advanced_setting_writing_limit_edit_published_post_sortable_active_callback',
        'sortable_description' => __('Tất cả các nhóm người dùng hiện có trên website.', 'sb-theme'),
        'sortable_active_description' => __('Nhóm người dùng được áp dụng hạn chế sửa bài viết đã được đăng.', 'sb-theme'),
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