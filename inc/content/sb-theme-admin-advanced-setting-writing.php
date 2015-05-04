<?php
global $sb_theme_roles;
$sb_theme_roles = SB_Membership::get_roles();

$args = array(
    'title' => 'Xác nhận đăng bài viết',
    'description' => 'Sẽ có đôi lúc bạn nhấn nhầm vào nút đăng bài viết thay vì lưu bài, chức năng này sẽ hiển thị thông báo xác nhận cho bạn.',
    'callback' => 'sb_theme_advanced_setting_writing_confirm_publish'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_confirm_publish() {
    $tab_base_option_name = 'writing';

    $key = 'confirm_publish';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $value = SB_Option::check_switch_value($value, 1);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => __('Kích hoạt chức năng hiển thị thống báo xác nhận đăng bài?', 'sb-theme'),
        'value' => $value
    );
    SB_Field::checkbox($args);
}

$args = array(
    'title' => 'Hỗ trợ tiêu đề cho link',
    'description' => 'Trong phiên bản 4.2 trở về sau, WordPress không hiển thị tiêu đề khi chèn link, bạn có thể dùng chức năng này để bật lại thông tin tiêu đề cho link.',
    'callback' => 'sb_theme_advanced_setting_writing_restore_link_title'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_restore_link_title() {
    $tab_base_option_name = 'writing';

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
}

$args = array(
    'title' => 'Viết bài bên ngoài front-end',
    'description' => 'Nếu bạn muốn cho người dùng đăng bài viết bên ngoài dashboard thì hãy lựa chọn trang bạn đã tạo bên dưới.',
    'callback' => 'sb_theme_advanced_setting_writing_add_post_front_end'
);
SB_Admin_Custom::row_setting_field($args);

function sb_theme_advanced_setting_writing_add_post_front_end() {
    $tab_base_option_name = 'writing';

    $key = 'page_add_post_front_end';
    $value = SB_Option::get_advanced_setting($tab_base_option_name, $key);
    $args = array(
        'id' => 'sb_theme_advanced_' . $tab_base_option_name . '_' . $key,
        'name' => SB_Option::build_sb_theme_advanced_option_name(array($tab_base_option_name, $key)),
        'label' => '',
        'value' => $value
    );
    SB_Field::select_page($args);
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