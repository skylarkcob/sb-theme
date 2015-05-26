<?php
/**
 * Gọi và chạy tất cả các hook trên giao diện.
 *
 * @package SB Theme
 */

do_action('sb_theme_hook_before');

/*
 * Hàm sử dụng hook init
 */
function sb_theme_init_hook() {
	do_action('sb_theme_init_before');
    $session_started = true;
    if ( version_compare( phpversion(), '5.4.0', '>=' ) ) {
        if ( PHP_SESSION_NONE == session_status() ) {
            $session_started = false;
        }
    } else {
        if ( '' == session_id() ) {
            $session_started = false;
        }
    }
    if(!$session_started) {
        session_start();
    }
    if(!current_user_can('read')) {
        show_admin_bar(false);
    }
    do_action('sb_theme_init');
	do_action('sb_theme_init_after');
}
add_action( 'init', 'sb_theme_init_hook' );

/*
 * Hàm kiểm tra tính hợp lệ của giao diện
 */
add_action( 'sb_theme_init', array( 'SB_Core', 'check_license' ) );

/*
 * Hàm kiểm tra tính hợp lệ của giao diện
 */
function sb_theme_check_license() {
	$transient_name = SB_Cache::build_license_transient_name();
	if(false === ($license = get_transient($transient_name))) {
		if ( sb_core_owner() ) {
			return;
		}
		$is_valid = true;
		if ( ! function_exists( 'sb_theme_check_license' ) || ! method_exists( 'SB_Core', 'check_license' ) || ! has_action( 'sb_theme_init', array( 'SB_Core', 'check_license' ) ) ) {
			$is_valid = false;
		}
		$license = absint($is_valid);
		set_transient($transient_name, $license, DAY_IN_SECONDS);
	}
	if ( 1 != $license ) {
		wp_die( '<strong>' . SB_Message::get_error() . ':</strong>' . ' ' . SB_Message::get_suspended() );
	}
}
add_action( 'sb_theme_init', 'sb_theme_check_license' );

/*
 * Hàm thực hiện sau khi mở khóa trang web
 */
function sb_theme_unlock_license() {
	$sb_unlock_license = isset($_REQUEST['sb_unlock_license']) ? absint('sb_unlock_license') : 0;
	if(1 == $sb_unlock_license) {
		$sb_password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
		if(SB_Core::password_compare($sb_password, SB_THEME_PASS)) {
			$transient_name = 'sb_theme_license';
			delete_transient($transient_name);
			$options = SB_Option::get();
			unset($options['sbcancel']);
			unset($options['license']);
			SB_Option::update($options);
		}
	}
}
add_action('sb_theme_init', 'sb_theme_unlock_license');

/*
 * Hàm thêm style và javascript vào website
 */
function sb_theme_wp_enqueue_scripts_hook() {
    do_action('sb_theme_wp_enqueue_scripts_before');

    if(sb_login_page_is_lost_password_custom_page() || sb_login_page_is_account_custom_page()) {
        SB_Theme::enqueue_password_strength_meter();
    }

    wp_enqueue_script('comment-reply');

    do_action('sb_theme_load_lib_scripts');

    wp_register_script( 'superfish', SB_THEME_LIB_URL . '/superfish/js/superfish.min.js', array( 'jquery', 'hoverIntent' ), false, true );
    wp_register_script( 'supersubs', SB_THEME_LIB_URL . '/superfish/js/supersubs.js', array( 'superfish' ), false, true );

    wp_register_style( 'superfish-style', SB_THEME_LIB_URL . '/superfish/css/superfish.css' );
    wp_register_style( 'superfish-vertical-style', SB_THEME_LIB_URL . '/superfish/css/superfish-vertical.css', array( 'superfish-style' ) );
    wp_register_style( 'superfish-navbar-style', SB_THEME_LIB_URL . '/superfish/css/superfish-navbar.css', array( 'superfish-vertical-style' ) );

    wp_register_style( 'sb-theme-style', SB_THEME_URL . '/css/sb-theme-style.css', array( 'superfish-navbar-style' ) );
    wp_register_script( 'sb-theme', SB_THEME_URL . '/js/sb-theme-script.js', array( 'supersubs' ), false, true );
    wp_localize_script( 'sb-theme', 'sb_theme', array(
        'ajax_url' => SB_Core::get_admin_ajax_url(),
        'ajaxurl' => SB_Core::get_admin_ajax_url(),
        'site_url' => get_bloginfo('url')
    ));

    wp_enqueue_style( 'sb-theme-style' );

    wp_enqueue_script( 'sb-theme' );

    do_action('sb_theme_wp_enqueue_scripts');

    do_action('sb_theme_wp_enqueue_scripts_after');
}
add_action( 'wp_enqueue_scripts', 'sb_theme_wp_enqueue_scripts_hook' );

/*
 * Hàm thêm style và javascript vào trang login
 */
function sb_theme_login_enqueue_scripts() {
    wp_enqueue_style('sb-theme-login-style', SB_THEME_URL . '/css/sb-theme-login-style.css');
    wp_register_script('sb-theme-login', SB_THEME_URL . '/js/sb-theme-login-script.js', array('jquery'), false, true);
    wp_localize_script('sb-theme-login', 'sb_theme_login', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_script('sb-theme-login');
    $logo_url = SB_Option::get_login_logo_url();
    $logo_url = SB_Option::get_media_detail($logo_url);
    $logo_url = $logo_url['url'];
    if(!empty($logo_url)) {
        echo '<style>';
        echo 'body.login div#login h1 a{background-image:url("' . $logo_url . '");}';
        echo '</style>';
    } else {
        printf('<style>body.login div#login h1 a{display:none;}</style>');
    }
}
add_action('login_enqueue_scripts', 'sb_theme_login_enqueue_scripts');

/*
 * Hàm thêm style và javascript vào trang admin
 */
function sb_theme_admin_enqueue_scripts_hook() {
    do_action('sb_theme_admin_enqueue_scripts_before');

    wp_enqueue_media();
    wp_enqueue_script( 'jquery-ui-core' );

    wp_register_style('sb-theme-admin-style', SB_THEME_URL . '/css/sb-theme-admin-style.css');
    wp_register_script( 'sb-theme-admin', SB_THEME_URL . '/js/sb-theme-admin-script.js', array( 'jquery' ), false, true );

    wp_localize_script( 'sb-theme-admin', 'sb_theme', array(
        'ajax_url' => SB_Core::get_admin_ajax_url(),
        'ajaxurl' => SB_Core::get_admin_ajax_url(),
        'site_url' => get_bloginfo('url')
    ));

    wp_enqueue_style('sb-theme-admin-style');
    wp_enqueue_script( 'sb-theme-admin' );

    do_action('sb_theme_admin_enqueue_scripts');

    do_action('sb_theme_admin_enqueue_scripts_after');
}
add_action( 'admin_enqueue_scripts', 'sb_theme_admin_enqueue_scripts_hook' );

function sb_theme_admin_head_hook() { ?>
    <script type="text/javascript">
        var sb_core_admin_ajax = <?php echo json_encode( array( 'url' => SB_Core::get_admin_ajax_url() ) ); ?>;
    </script><?php
}
add_action('admin_footer', 'sb_theme_admin_head_hook');

/*
 * Chạy hàm khi permalink được cập nhật
 */
function sb_theme_update_option_permalink_structure_hook() {
	if(isset($_REQUEST['settings-updated']) && (bool)$_REQUEST['settings-updated']) {
		SB_Core::delete_transient('sb_menu');
		do_action('sb_theme_update_permalink');
	}
}
if($GLOBALS['pagenow'] == 'options-permalink.php') add_action( 'sb_theme_admin_init' , 'sb_theme_update_option_permalink_structure_hook' );

/*
 * Hàm thực hiện sau khi người dùng đăng xuất khỏi hệ thống
 */
function sb_theme_logout_hook() {
    SB_Theme::destroy_social_login();
    do_action('sb_theme_logout');
}
add_action('wp_logout', 'sb_theme_logout_hook');

/*
 * Hàm kiểm tra trước khi hiển thị nội dung trang đăng nhập
 */
function sb_theme_login_init_hook() {
    do_action('sb_theme_login_init');
}
add_action('login_init', 'sb_theme_login_init_hook');

/*
 * Hàm thêm thông tin vào đầu trang login
 */
function sb_theme_login_head_hook() {
    do_action('sb_theme_login_head');
}
add_action('login_head', 'sb_theme_login_head_hook');

/*
 * Hàm kiểm tra trước khi hiển thị nội dung trang đăng nhập
 */
function sb_theme_login_check_social_login() {
    $data_social = isset($_GET['data_social']) ? $_GET['data_social'] : '';
    if(empty($data_social)) {
        $data_social = isset($_GET['state']) ? $_GET['state'] : '';
    }
    $data_social = SB_Core::decrypt($data_social);
    SB_User::check_social_login_data_send_back($data_social);
}
add_action('sb_theme_login_init', 'sb_theme_login_check_social_login');

/*
 * Hàm thêm thông tin vào trang đăng nhập
 */
function sb_theme_login_form_hook() {
    sb_theme_get_content('sb-theme-wp-login-social');
    do_action('sb_theme_login_form');
}
add_action('login_form', 'sb_theme_login_form_hook');

/*
 * Hàm thêm thông tin vào chân trang login
 */
function sb_theme_login_footer_hook() {
    do_action('sb_theme_login_footer');
}
add_action('login_footer', 'sb_theme_login_footer_hook');

/*
 * Hàm thêm thông tin vào trang đăng ký
 */
function sb_theme_register_form_hook() {
    sb_theme_get_content('sb-theme-wp-login-social');
    do_action('sb_theme_register_form');
}
add_action('register_form', 'sb_theme_register_form_hook');

/*
 * Hàm thêm thông tin vào trang quên mật khẩu
 */
function sb_theme_lost_password_form_hook() {
    do_action('sb_theme_lost_password_form');
}
add_action('lostpassword_form', 'sb_theme_lost_password_form_hook');

/*
 * Hàm tạo hook thêm thông tin vào tất cả các form trang login
 */
function sb_theme_all_login_form_hook() {
    sb_theme_get_content('sb-theme-login-form-add-to-all');
    do_action('sb_theme_all_login_form');
}
add_action('sb_theme_login_form', 'sb_theme_all_login_form_hook');
add_action('sb_theme_register_form', 'sb_theme_all_login_form_hook');
add_action('sb_theme_lost_password_form', 'sb_theme_all_login_form_hook');

/*
 * Hạn chế quyền hạn đăng bài, chỉnh sửa bài viết của các nhóm người dùng
 */
function sb_theme_custom_init_roles() {
    SB_Membership::regenerate_roles(true);
}
add_action('sb_theme_after_switch_theme', 'sb_theme_custom_init_roles');
add_action('sb_theme_upgrade', 'sb_theme_custom_init_roles');

function sb_theme_update_permalinks_struct() {
    flush_rewrite_rules();
}
add_action('sb_theme_upgrade', 'sb_theme_update_permalinks_struct');
add_action('sb_theme_activation', 'sb_theme_update_permalinks_struct');

function sb_theme_regenerate_roles_hook() {
    SB_Membership::regenerate_roles();
}
add_action('sb_theme_admin_init', 'sb_theme_regenerate_roles_hook');

/*
 * Kiểm tra số lượng bài viết miễn phí của người dùng
 */
function sb_theme_check_user_post_before_add_new() {
    if(SB_Core::is_add_post_page() && !SB_Core::is_edit_post_page()) {
        if(SB_Membership::is_paid_membership_enabled()) {
            if(in_array(SB_User::get_current_role(), SB_Membership::get_paid_role_ids())) {
                $current_user = SB_User::get_current();
                if(!SB_User::is_admin($current_user->ID)) {
                    $minimum_coin_can_post = SB_Membership::get_minimum_coin_can_post();
                    $pass_free_post = (SB_User::count_all_post($current_user->ID) >= SB_Membership::get_free_post_number()) ? true : false;
                    $user_coin = SB_User::get_coin($current_user->ID);
                    if($minimum_coin_can_post > 0 && $pass_free_post) {
                        if($user_coin < $minimum_coin_can_post) {
                            $transient_name = SB_Cache::build_user_transient_name($current_user->ID, '_minimum_coin_can_post');
                            set_transient($transient_name, 1, MINUTE_IN_SECONDS);
                            $edit_url = SB_Core::get_admin_edit_page_url();
                            wp_redirect($edit_url);
                            exit;
                        }
                    }
                    if($pass_free_post && !SB_Membership::can_user_write_paid_post($current_user->ID)) {
                        $transient_name = SB_Cache::build_user_transient_name($current_user->ID, '_limit_free_post');
                        set_transient($transient_name, 1, MINUTE_IN_SECONDS);
                        $edit_url = SB_Core::get_admin_edit_page_url();
                        wp_redirect($edit_url);
                        exit;
                    }
                }
            }
        }
    }
}
add_action('sb_theme_admin_init', 'sb_theme_check_user_post_before_add_new');

function sb_theme_admin_menu_hook() {
    if(is_admin()) {
        if(SB_Option::use_administrative_boundaries()) {
            $post_types = SB_Option::get_post_type_use_administrative_boundaries();
            foreach($post_types as $post_type) {
                remove_meta_box('provincediv', $post_type, 'side');
                remove_meta_box('districtdiv', $post_type, 'side');
                remove_meta_box('warddiv', $post_type, 'side');
                remove_meta_box('hamletdiv', $post_type, 'side');
                remove_meta_box('streetdiv', $post_type, 'side');
            }
        }
        do_action('sb_theme_admin_menu');
    }
}
add_action('admin_menu', 'sb_theme_admin_menu_hook');

/*
 * Thông báo cho người dùng phải trả phí trước khi đăng bài
 */
function sb_theme_limit_free_post_admin_notices() {
    $current_user = SB_User::get_current();
    $transient_name = SB_Cache::build_user_transient_name($current_user->ID, '_limit_free_post');
    if(false !== get_transient($transient_name)) {
        $message = sprintf(__('Bạn chỉ được phép đăng %s bài viết miễn phí, xin vui lòng nạp tài khoản để đăng bài.', 'sb-theme'), SB_Membership::get_paid_membership_free_post_number());
        SB_Message::admin_notice_error($message);
        delete_transient($transient_name);
    }
    $transient_name = SB_Cache::build_user_transient_name($current_user->ID, '_minimum_coin_can_post');
    if(false !== get_transient($transient_name)) {
        $message = sprintf(__('Số tiền của bạn không đủ để đăng bài viết, xin vui lòng %s.', 'sb-theme'), '<a href="' . SB_Membership::get_add_coin_url() . '">nạp tài khoản</a>');
        SB_Message::admin_notice_error($message);
        delete_transient($transient_name);
    }
}
add_action('sb_theme_admin_notices', 'sb_theme_limit_free_post_admin_notices');

/*
 * Hàm khởi động hook tạo post type và taxonomy
 */
function sb_theme_custom_post_type_and_taxonomy_hook() {
    if(SB_Membership::is_paid_membership_enabled()) {
        if(current_user_can('update_core')) {
            $args = array(
                'name' => __('Transactions', 'sb-theme'),
                'singular_name' => __('Transaction', 'sb-theme'),
                'slug' => 'transaction',
                'has_archive' => false,
                'exclude_from_search' => true,
                'public' => false
            );
            SB_Core::register_post_type_private($args);
        }
    }
    do_action( 'sb_post_type_and_taxonomy' );
    do_action('sb_theme_post_type_and_taxonomy');
    do_action('sb_theme_post_types_and_taxonomies');
}
add_action( 'sb_theme_init', 'sb_theme_custom_post_type_and_taxonomy_hook', 0 );

/*
 * Thêm favicon và meta
 */
function sb_theme_wp_head_hook() {
	SB_Theme::the_favicon_html();
	SB_Theme::the_date_meta_html();
    SB_Theme::the_robots_meta();
	do_action('sb_theme_wp_head');
}
add_action('wp_head', 'sb_theme_wp_head_hook');

/*
 * Xóa các thứ không cần thiết trong thẻ head
 */
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'feed_links');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

/*
 * Thêm thông tin vào chân trang admin
 */
function sb_theme_admin_footer_hook() {
    if(SB_Option::confirm_publish_post()) {
        sb_theme_admin_confirm_publish_post();
    }
    sb_core_ajax_loader();
}
add_action( 'admin_footer', 'sb_theme_admin_footer_hook' );

/*
 * Khai báo các ứng dụng sau khi giao diện được cài đặt
 */
function sb_theme_after_setup_theme_hook() {
    load_theme_textdomain('sb-theme', get_template_directory() . '/languages');
    add_theme_support( 'automatic-feed-links' );
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        )
    );
    register_nav_menus(
        array(
            'top' => __('Top menu', 'sb-theme'),
            'primary'   => __('Primary menu', 'sb-theme'),
            'secondary' => __('Secondary menu', 'sb-theme'),
            'footer' => __('Footer menu', 'sb-theme')
        )
    );
    if(has_filter('sb_theme_statistics', '__return_true')) {
        add_theme_support('sb_statistics');
    }
	do_action('sb_theme_after_setup_theme');
}
add_action('after_setup_theme', 'sb_theme_after_setup_theme_hook');

/*
 * Khởi tạo các sidebar và widget
 */
function sb_theme_widgets_init_hook() {
    do_action('sb_theme_widgets_init_before');
    register_widget('SB_Banner_Widget');
    register_widget('SB_Post_Widget');
    register_widget('SB_Tab_Widget');
    register_widget('SB_Menu_Widget');
    if(class_exists('SB_Video_Widget')) {
        register_widget('SB_Video_Widget');
    }
    if(SB_Option::statistics_enabled() && (bool)SB_Option::get_statistics_switch('visitor_statistics')) {
        register_widget('SB_Statistics_Widget');
    }
    sb_theme_register_sidebar('primary', __('Sidebar chính', 'sb-theme'), __('Sidebar chính trên website của bạn.', 'sb-theme'));
    sb_theme_register_sidebar('secondary', __('Sidebar phụ', 'sb-theme'), __('Sidebar phụ trên website của bạn.', 'sb-theme'));
    sb_theme_register_sidebar('footer', __('Sidebar dưới chân trang', 'sb-theme'), __('Sidebar chứa các widget dưới chân trang web.', 'sb-theme'));
    do_action('sb_theme_widgets_init');
    do_action('sb_theme_widgets_init_after');
}
add_action('widgets_init', 'sb_theme_widgets_init_hook');

/*
 * Chạy hàm sau khi giao diện được thay đổi
 */
function sb_theme_after_switch_hook() {
    sb_theme_error_checking();
    if(is_admin() && defined('SB_CORE_VERSION')) {
        sb_theme_update_default_options();
    }

    SB_Membership::init_roles_and_capabilities();

    flush_rewrite_rules();
}
add_action('sb_theme_after_switch_theme', 'sb_theme_after_switch_hook');

/*
 * Chạy hàm trước khi giao diện được chuyển đổi
 */
function sb_theme_switch_hook($newname, $newtheme) {
    SB_Membership::remove_all_role();
    do_action('sb_theme_switch_theme', $newname, $newtheme);
    do_action('sb_theme_deactivation', $newname, $newtheme);
    do_action('sb_theme_uninstall', $newname, $newtheme);
}
add_action('switch_theme', 'sb_theme_switch_hook', 10, 2);

/*
 * Thêm thông tin vào chân trang front-end
 */
function sb_theme_wp_footer() { ?>
    <script type="text/javascript">
        var sb_core_ajax = <?php echo json_encode( array( 'url' => SB_Core::get_admin_ajax_url() ) ); ?>;
    </script><?php
    $scroll_top = SB_Option::get_scroll_top();
    if($scroll_top) {
        echo '<a id="sb-scroll-top" href="javascript:;" class="go-top-button"><i class="fa fa-chevron-up"></i></a>';
    }
    if(SB_Option::utility_enabled('float_ads')) {
        sb_theme_get_content('content-float-ads');
    }
    if(sb_theme_support_addthis() || SB_Option::utility_enabled('addthis')) {
        include SB_THEME_LIB_PATH . '/addthis/config.php';
    }
    if(SB_Option::utility_enabled('sharethis')) {
        include SB_THEME_LIB_PATH . '/sharethis/config.php';
    }
    sb_core_ajax_loader();
    sb_theme_remove_facebook_login_special_char();
}
add_action('wp_footer', 'sb_theme_wp_footer');

/*
 * Chạy hàm khi plugin WordPress SEO by Yoast được kích hoạt
 */
function sb_theme_wordpress_seo_activation() {
    SB_Option::edit_breadcrumb_sep();
}
register_activation_hook( WP_PLUGIN_DIR . '/wordpress-seo/wp-seo.php', 'sb_theme_wordpress_seo_activation' );

// Chạy hàm khi plugin WooCommerce được kích hoạt
function sb_theme_on_woocommerce_activation() {
    $base_location = get_option('woocommerce_default_country');
    if('VN' != $base_location) {
        update_option('woocommerce_default_country', 'VN');
        update_option('woocommerce_currency', 'VND');
        update_option('woocommerce_currency_pos', 'right');
        update_option('woocommerce_price_thousand_sep', '.');
        update_option('woocommerce_price_decimal_sep', ',');
        update_option('woocommerce_price_num_decimals', 0);
        update_option('woocommerce_cod_enabled', '1');
        update_option('woocommerce_bacs_title', 'Chuyển khoản ngân hàng');
        $checkout_bacs_guide = 'Gửi thanh toán của bạn trực tiếp qua tài khoản ngân hàng của chúng tôi. Xin vui lòng sử dụng mã ID của đơn hàng để làm đối chiếu thanh toán. Hàng hóa sẽ không được vận chuyển cho đến khi chúng tôi nhận được thanh toán.';
        update_option('woocommerce_bacs_description', $checkout_bacs_guide);
        update_option('woocommerce_bacs_instructions', $checkout_bacs_guide);

        update_option('woocommerce_cheque_title', 'Thanh toán bằng Séc');
        $desc = 'Xin vui lòng gửi thông tin thanh toán bằng Séc của bạn đến địa chỉ của cửa hàng.';
        update_option('woocommerce_cheque_description', $desc);
        update_option('woocommerce_cheque_instructions', $desc);

        update_option('woocommerce_cod_title', 'Thanh toán khi nhận hàng');
        $desc = 'Thanh toán khi nhận hàng.';
        update_option('woocommerce_cod_description', $desc);
        update_option('woocommerce_cod_instructions', $desc);
    }
    $permalink_struct = get_option('product_permalink_structure');
    if(empty($permalink_struct)) {
        $permalink_struct = '/product';
        update_option('product_permalink_structure', $permalink_struct);
    }
}
register_activation_hook(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php', 'sb_theme_on_woocommerce_activation');

/*
 * Chạy hàm khi plugin Breadcrumb NavXT được kích hoạt
 */
function sb_theme_bcn_activation() {
    SB_Option::edit_bcn_breadcrumb_sep();
}
register_activation_hook( WP_PLUGIN_DIR . '/breadcrumb-navxt/breadcrumb-navxt.php', 'sb_theme_bcn_activation' );

/*
 * Chạy hàm khi plugin SB Core được kích hoạt
 */
function sb_theme_on_sb_core_activation() {
    sb_theme_deactivate_all_sb_plugin();
}
register_activation_hook(WP_PLUGIN_DIR . '/sb-core/sb-core.php', 'sb_theme_on_sb_core_activation');

/*
 * Chạy hàm khi menu được cập nhật
 */
function sb_theme_on_nav_menu_update( $id ) {
    $locations = get_nav_menu_locations();
    if( is_array( $locations ) && $locations ) {
        $locations = array_keys( $locations, $id );
        if( $locations ) {
            foreach( $locations as $location ) {
                delete_transient( SB_Cache::build_menu_transient_name($location) );
            }
        }
    }
    delete_transient(SB_Cache::build_custom_menu_transient_name());
    do_action('sb_theme_update_menu', $id);
}
add_action( 'wp_update_nav_menu', 'sb_theme_on_nav_menu_update' );

/*
 * Chạy hàm khi bất kỳ một cài đặt nào được cập nhật
 */
function sb_theme_updated_option_hook($option, $old_value, $value) {
    do_action('sb_theme_updated_option', $option, $old_value, $value);
}
add_action('updated_option', 'sb_theme_updated_option_hook', 10, 3);

/*
 * Không cho WordPress tự ping chính mình
 */
function sb_theme_pre_ping_hook(&$links) {
    $home = get_option('home');
    foreach($links as $l => $link) {
        if( 0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }
    do_action('sb_theme_pre_ping', $links);
}
add_action('pre_ping', 'sb_theme_pre_ping_hook');

/*
 * Chạy hàm khi bình luận được thêm vào cơ sở dữ liệu
 */
function sb_theme_insert_comment_hook($comment_id, $comment_object) {
    if(SB_Comment::enable_spam_check() && empty($comment_object->comment_content)) {
        SB_Comment::delete($comment_id);
        wp_die(__('Bình luận spam đã được phát hiện!', 'sb-theme'));
    }
    delete_transient(SB_Cache::build_post_transient_name($comment_object->comment_post_ID . '_comment_number'));
    do_action('sb_theme_insert_comment', $comment_id, $comment_object);
}
add_action('wp_insert_comment', 'sb_theme_insert_comment_hook', 10, 2);

function sb_theme_site_statistics() {
    SB_Theme::run_statistics();
}
add_action('wp_footer', 'sb_theme_site_statistics');

/*
 * Lưu ngày cuối cùng của tháng vào cơ sở dữ liệu
 */
function sb_theme_update_last_date_of_month() {
    $transient_name = SB_Cache::build_last_date_of_month_transient_name();
    if(false === ($checked = get_transient($transient_name))) {
        $today_timestamp = SB_Core::get_today_timestamp();
        $lastday_timestamp = SB_PHP::get_lastday_of_month_timestamp();
        $lastday_timestamp_saved = SB_Option::get_last_date_of_month();
        if($today_timestamp > $lastday_timestamp_saved) {
            SB_Option::update_last_date_of_month($lastday_timestamp);
        }
        set_transient($transient_name, '1', DAY_IN_SECONDS);
    }
}
add_action('sb_theme_init', 'sb_theme_update_last_date_of_month');

/*
 * Đếm bình luận hàng tháng cho bài viết
 */
function sb_theme_count_post_comment_this_month($comment_id, $comment_object) {
    $post_id = $comment_object->comment_post_ID;
    SB_Post::count_comment_this_month($post_id);
}
add_action('sb_theme_insert_comment', 'sb_theme_count_post_comment_this_month', 10, 2);

/*
 * Chạy hàm khi trạng thái bình luận được thay đổi
 */
function sb_transition_comment_status($new_status, $old_status, $comment) {
    if($new_status != $old_status) {
        if('approved' == $new_status && SB_Comment::enable_notify_comment_approved()) {
            SB_Mail::notify_user_for_comment_approved($comment);
        } elseif('spam' == $new_status && SB_Comment::enable_auto_empty_spam()) {
            SB_Comment::delete($comment->comment_ID);
        }
        do_action('sb_theme_transition_comment_status', $new_status, $old_status, $comment);
    }
}
add_action('transition_comment_status', 'sb_transition_comment_status', 10, 3);

/*
 * Chạy hàm khi nâng cấp SB Theme
 */
function sb_theme_on_upgrade_hook() {
    SB_Cache::delete_all_cache();
}
add_action('sb_theme_upgrade', 'sb_theme_on_upgrade_hook');

/*
 * Thêm nonce vào form bình luận
 */
function sb_comment_nonce_field() {
    wp_nonce_field('sb_comment_form');
}
add_action('comment_form', 'sb_comment_nonce_field');

/*
 * Kiểm tra nonce trước khi chấp nhận bình luận
 */
function sb_comment_stop() {
    if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'sb_comment_form')) {
        wp_die(__('Bình luận của bạn không hợp lệ!', 'sb-theme'));
    }
}
add_action('pre_comment_on_post', 'sb_comment_stop');

/*
 * Đặt lịch xóa bình luận spam
 */
function sb_comment_empty_spam_schedule(){
    if(!wp_next_scheduled('sb_comment_empty_spam_cron_job')) {
        wp_schedule_event(time(), 'hourly', 'sb_comment_empty_spam_cron_job');
    }
}
add_action('sb_theme_init', 'sb_comment_empty_spam_schedule');
add_action('sb_comment_empty_spam_cron_job', 'sb_comment_empty_spam_cron_function');

/*
 * Kiểm tra trước khi hiển thị nội dung trang web,
 * nếu hệ thống sử dụng trang tài khoản tùy chỉnh thì chuyển tiếp
 * tới trang này.
 */
function sb_login_page_init() {
    if(sb_login_page_use_sb_login() && SB_Core::is_login_page()) {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        if('logout' == $action) {
            return;
        }
        $acc_page_id = sb_login_page_get_page_account_id();
        if($acc_page_id > 0 && SB_Post::is_published($acc_page_id)) {
            $login_url = sb_login_page_get_page_account_url();
            if(!empty($login_url) && !empty($action)) {
                $login_url = add_query_arg(array('action' => $action), $login_url);
            }
            if(!empty($login_url)) {
                wp_redirect($login_url);
                exit();
            }
        }
    }
}
add_action('sb_theme_login_init', 'sb_login_page_init');

/*
 * Kiểm tra trước khi hiển thị nội dung trang đăng nhập
 */
function sb_login_page_custom_init() {
    $acc_page_id = sb_login_page_get_page_account_id();
    if($acc_page_id > 0) {
        if(SB_User::is_logged_in()) {
            if(sb_login_page_is_login_custom_page() || sb_login_page_is_lost_password_custom_page() || sb_login_page_is_register_custom_page()) {
                wp_redirect(sb_login_page_get_page_account_url());
                exit;
            }
        } else {
            if(!SB_User::can_register() && sb_login_page_is_register_custom_page()) {
                wp_redirect(home_url('/'));
                exit;
            }
            $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
            if(sb_login_page_is_account_custom_page()) {
                if('register' == $action && !SB_User::can_register()) {
                    wp_redirect(home_url('/'));
                    exit;
                }
                if('login' != $action && 'register' != $action && 'lostpassword' != $action && 'verify' != $action) {
                    $account_url = sb_login_page_get_page_account_url();
                    $account_url = add_query_arg(array('action' => 'login'), $account_url);
                    wp_safe_redirect($account_url);
                    exit();
                }
                if('verify' == $action) {
                    $user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
                    if($user_id > 0) {
                        $user = SB_User::get_by('id', $user_id);
                        if(SB_User::is($user)) {
                            $verify_email_session = SB_User::get_verify_email_session($user);
                            if(1 == $verify_email_session) {
                                SB_User::set_verify_email_cookie($user);
                            }
                        }
                    }
                }
            } elseif(sb_login_page_is_lost_password_custom_page()) {
                $step = isset($_REQUEST['step']) ? trim($_REQUEST['step']) : '';
                $code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
                $id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
                if('verify' == $step && !empty($code) && $id > 0) {
                    $user = SB_User::get_by('id', $id);
                    if(!is_wp_error($user) && is_a($user, 'WP_User') && SB_User::check_activation_code($user, $code)) {
                        $lost_password_url = SB_User::get_lost_password_verify_url($code);
                        $lost_password_url = add_query_arg(array('step' => 'reset', 'user_id' => $id));
                        wp_safe_redirect($lost_password_url);
                        exit;
                    }
                } elseif('reset' == $step) {
                    $user = SB_User::get_by('id', $id);
                    if(empty($code) || $id < 1 || 'lostpassword' != $action || !SB_User::is($user) || !SB_User::check_activation_code($user, $code)) {
                        wp_redirect(home_url('/'));
                        exit;
                    }
                }
            }
        }
    }
}
add_action('sb_login_page_init', 'sb_login_page_custom_init');

/*
 * Kiểm tra trước khi chấp nhận yêu cầu khôi phục mật khẩu
 */
function sb_theme_lost_password_post_hook() {
    if(SB_Option::use_login_captcha() && SB_Captcha::need_check() && (!isset($_POST['captcha_code']) || empty($_POST['captcha_code']))) {
        add_filter('allow_password_reset', array('SB_Message', 'empty_captcha_error'));
    }
    if(SB_Option::use_login_captcha() && SB_Captcha::need_check() && isset($_POST['captcha_code']) && !SB_Captcha::check($_POST['captcha_code'])) {
        add_filter('allow_password_reset', array('SB_Message', 'invalid_captcha_error'));
    }
}
add_action('lostpassword_post', 'sb_theme_lost_password_post_hook');

/*
 * Chạy hàm khi plugin SB Login Page ngừng kích hoạt
 */
function sb_login_page_plugin_deactivated() {
    //sb_login_page_delete_page_templates();
}
add_action('sb_login_page_deactivation', 'sb_login_page_plugin_deactivated');

/*
 * Thêm các trường mở rộng cho tài khoản người dùng
 */
function sb_login_page_user_profile_extra_field($user) {
    SB_User::add_more_profile_field($user);
}
add_action('show_user_profile', 'sb_login_page_user_profile_extra_field');
add_action('edit_user_profile', 'sb_login_page_user_profile_extra_field');

/*
 * Cập nhật những thông tin mở rộng cho người dùng
 */
function sb_login_page_save_profile($user_id) {
    SB_User::save_profile_posted($user_id);
    do_action('sb_theme_save_user_profile', $user_id);
}
add_action('personal_options_update', 'sb_login_page_save_profile');
add_action('edit_user_profile_update', 'sb_login_page_save_profile');

/*
 * Tạo tabber sidebar
 */
function sb_tab_widget_load_sidebar() {
	SB_Core::register_sidebar('sb-tabber', 'Tabber Widgets', __( 'Hiển thị các widget dưới dạng tab.', 'sb-theme' ));
    $list_sidebars = sb_tab_widget_get_sidebars();
    foreach($list_sidebars as $sidebar) {
        $sidebar_id = $sidebar['id'];
        $sidebar_name = $sidebar['name'];
        $sidebar_description = $sidebar['description'];
        if(!empty($sidebar_id) && !empty($sidebar_name) && !empty($sidebar_description)) {
            SB_Core::register_sidebar($sidebar_id, $sidebar_name, $sidebar_description);
        }
    }
}
add_action('sb_theme_widgets_init', 'sb_tab_widget_load_sidebar');

/*
 * Chạy hàm khi lưu bài viết
 */
function sb_theme_save_post_hook( $post_id, $post, $update ) {
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    SB_Core::delete_transient('sb_theme_post_' . $post_id);
    do_action('sb_theme_update_post', $post_id, $post, $update);
    do_action('sb_theme_save_post', $post_id, $post, $update);
}
add_action( 'save_post', 'sb_theme_save_post_hook', 10, 3 );

/*
 * Chạy hàm khi xóa bài viết
 */
function sb_theme_delete_post_hook($post_id) {
    do_action('sb_theme_delete_post', $post_id);
}
add_action( 'delete_post', 'sb_theme_delete_post_hook', 10 );

/*
 * Chạy hàm khi trạng thái bài viết được thay đổi
 */
function sb_theme_post_status_transitions_hook( $new_status, $old_status, $post ) {
    if( $new_status != $old_status ) {
        do_action('sb_theme_transition_post_status', $new_status, $old_status, $post);
        if('publish' == $new_status) {
            do_action('sb_theme_post_status_publish', $post);
        }
    }
}
add_action('transition_post_status', 'sb_theme_post_status_transitions_hook', 10, 3);

function sb_theme_prevent_publish_post($post) {
    if(SB_Membership::is_paid_membership_enabled() && 'post' == $post->post_type) {
        $current_role = SB_User::get_current_role();
        if(in_array($current_role, SB_Membership::get_paid_role_ids())) {
            $post_data = array(
                'ID' => $post->ID,
                'post_status' => 'pending'
            );
            wp_update_post($post_data);
        }
    }
}
add_action('sb_theme_post_status_publish', 'sb_theme_prevent_publish_post');

function sb_theme_subtract_published_post_coin($post_id, $post, $update) {
    $user = SB_User::get_by('id', $post->post_author);
    if(SB_Membership::is_paid_membership_enabled()) {
        if(SB_User::is($user) && in_array(SB_User::get_current_role($user), SB_Membership::get_paid_role_ids())) {
            $current_user = $user;
            if(!SB_User::is_admin($current_user->ID)) {
                $minimum_coin_can_post = SB_Membership::get_minimum_coin_can_post();
                $pass_free_post = (SB_User::count_all_post($current_user->ID) >= SB_Membership::get_free_post_number()) ? true : false;
                if($minimum_coin_can_post > 0 && $pass_free_post) {
                    $user_coin = SB_User::get_coin($current_user->ID);
                    if($user_coin < $minimum_coin_can_post) {
                        $transient_name = SB_Cache::build_user_transient_name($current_user->ID, '_minimum_coin_can_post');
                        set_transient($transient_name, 1, MINUTE_IN_SECONDS);
                        $edit_url = SB_Core::get_admin_edit_page_url();
                        wp_redirect($edit_url);
                        exit;
                    }
                }
                if($pass_free_post && !SB_Membership::can_user_write_paid_post($user->ID)) {
                    $transient_name = SB_Cache::build_user_transient_name($current_user->ID, '_limit_free_post');
                    set_transient($transient_name, 1, MINUTE_IN_SECONDS);
                    $edit_url = SB_Core::get_admin_edit_page_url();
                    wp_redirect($edit_url);
                    exit;
                }
                if('publish' == $post->post_status) {
                    $post_cost_coin = SB_Membership::get_post_coin_cost();
                    SB_User::minus_coin($user->ID, $post_cost_coin);
                }
            }
        }
    }
}
add_action('sb_theme_save_post', 'sb_theme_subtract_published_post_coin', 10, 3);

/*
 * Chạy hàm khi bài viết được đăng
 */
function sb_theme_on_post_publish_hook( $ID, $post ) {
    do_action('sb_theme_publish_post', $ID, $post);
}
add_action('publish_post', 'sb_theme_on_post_publish_hook', 10, 2);

/*
 * Kiểm tra trước khi xóa tài khoản
 */
function sb_theme_delete_user_hook($user_id) {
    $current_user = SB_User::get_current();
    if(SB_User::is($current_user) && !SB_Membership::is_super_admin($current_user->ID) && SB_Membership::is_super_admin($user_id)) {
        wp_die(__('You don\'t have permission to delete super administrator users!', 'sb-theme'));
        exit;
    }
    if(SB_User::is($current_user) && $current_user->ID == $user_id) {
        wp_die(__('You can\'t delete your own account!', 'sb-theme'));
        exit;
    }
    do_action('sb_theme_delete_user', $user_id);
}
add_action('delete_user', 'sb_theme_delete_user_hook');

/*
 * Cài đặt gửi mail thông qua SMTP
 */
function sb_theme_phpmailer_init_hook($phpmailer) {
    if(SB_Option::use_smtp_mail()) {
        $sb_smtp = SB_Option::get_option_by_key(array('smtp_email'));
        $host = isset($sb_smtp['smtp_host']) ? $sb_smtp['smtp_host'] : '';
        $port = isset($sb_smtp['smtp_port']) ? $sb_smtp['smtp_port'] : '';
        $username = isset($sb_smtp['username']) ? $sb_smtp['username'] : '';
        $password = isset($sb_smtp['password']) ? $sb_smtp['password'] : '';
        if(empty($host) || empty($port) || empty($username) || empty($password)) {
            return;
        }
        $sb_smtp = SB_Option::get_option_by_key(array('smtp_email'));
        $secure_type = '';
        if(isset($sb_smtp['encryption']) && 'none' != $sb_smtp['encryption']) {
            $secure_type = $sb_smtp['encryption'];
        }
        $phpmailer->isSMTP();
        $phpmailer->Host = $host;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = $username;
        $phpmailer->Password = $password;
        $phpmailer->SMTPSecure = $secure_type;
        $phpmailer->Port = $port;
        $phpmailer = apply_filters('sb_theme_phpmailer', $phpmailer);
    }
}
add_action('phpmailer_init', 'sb_theme_phpmailer_init_hook');

/*
 * Cảnh báo chưa cài đặt plugin Really Simple Captcha
 */
function sb_theme_admin_notice_hook() {
    SB_Message::warning_missing_really_simple_captcha_plugin();
}
add_action('sb_theme_admin_notices', 'sb_theme_admin_notice_hook');

function sb_theme_add_style_and_script_to_editor() {
    if(version_compare(SB_Option::get_current_wordpress_version(), '4.2', '>=')) {
        if(SB_Option::support_link_title()) {
            if(!SB_Core::is_restore_link_title_field_installed()) {
                SB_Lib::restore_link_title();
            }
        }
    }
}
add_action('wp_enqueue_editor', 'sb_theme_add_style_and_script_to_editor', 20);

/*
 * Chạy hàm khi trang cài đặt nâng cao cập nhật
 */
function sb_theme_sanitize_option_updated($input) {
    if(isset($input['sbt_advanced'])) {
        SB_Membership::update_limit_post_roles();
        do_action('sb_theme_advanced_setting_updated');
    }
}
add_action('sb_theme_sanitize_option_done', 'sb_theme_sanitize_option_updated');

/*
 * Chạy hàm sau khi cài đặt được cập nhật
 */
function sb_theme_sb_option_update_hook() {
    if(SB_Admin_Custom::is_sb_page()) {
        if(isset($_REQUEST['settings-updated']) && (bool)$_REQUEST['settings-updated']) {
            SB_Core::delete_transient('sb_theme_post');
            delete_transient(SB_Cache::build_admin_advanced_setting_tab_transient_name());
            do_action('sb_theme_update_sb_option');
        }
    }
}
if(isset($GLOBALS['pagenow']) && 'admin.php' == $GLOBALS['pagenow']) add_action('sb_theme_admin_init', 'sb_theme_sb_option_update_hook');

/*
 * Chạy hàm khi xem trang chỉnh sửa bài viết
 */
function sb_theme_edit_post_hook() {
    if(!empty($_GET['post'])) {
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if('edit' == $action) {
            $post = get_post($_GET['post']);
            if(!is_wp_error($post)) {
                do_action('sb_theme_edit_post', $post);
            }
        }
    }
}
if(isset($GLOBALS['pagenow']) && 'post.php' == $GLOBALS['pagenow']) add_action('load-post.php', 'sb_theme_edit_post_hook');

/*
 * Thay đổi chữ Submit for Review thành Update
 */
function sb_theme_on_edit_post($post) {
    $post_id = $post->ID;
    if('pending' == $post->post_status || 'draft' == $post->post_status) {
        add_filter( 'gettext', 'sb_theme_change_publish_post_button_text_filter', 10, 2 );
    }
}
add_action('sb_theme_edit_post', 'sb_theme_on_edit_post');

/* ================================================================================================================== */

// Thiết lập filter cho giao diện

function sb_theme_no_index_private_post_type($robotsstr) {
    $private_types = SB_Post::get_private_post_types();
    if(in_array(get_post_type(), $private_types)) {
        $robotsstr = 'noindex, nofollow';
    }
    return $robotsstr;
}
add_filter('wpseo_robots', 'sb_theme_no_index_private_post_type');

/*
 * Tạo filter lọc avatar cho người dùng, ngoại trừ những avatar trong trang cài đặt thảo luận
 */
function sb_theme_get_avatar_filter($avatar, $id_or_email, $size, $default, $alt) {
	if(!isset($GLOBALS['pagenow']) || (isset($GLOBALS['pagenow']) && 'options-discussion.php' != $GLOBALS['pagenow'])) {
		$avatar = apply_filters('sb_theme_get_avatar', $avatar, $id_or_email, $size, $default, $alt);
	}
	return $avatar;
}
add_filter('get_avatar', 'sb_theme_get_avatar_filter', 10, 5);

/*
 * Khai báo avatar mặc định cho người dùng, nếu người dùng chọn avatar mặc định được chọn cục bộ thì hiển thị
 * avatar này.
 */
function sb_theme_get_avatar_default($avatar, $id_or_email, $size, $default, $alt) {
	if(SB_PHP::is_string_contain($avatar, 'sb-default-avatar')) {
		$custom_avatar_url = SB_Comment::get_default_avatar_url();
		if(!empty($custom_avatar_url)) {
			$avatar_src = SB_PHP::get_image_source($avatar);
			if(!empty($avatar_src)) {
				$default = $custom_avatar_url;
				$is_localhost = SB_Core::is_localhost();
				if($is_localhost) {
					$default = 'mystery';
				}
				$avatar_src = add_query_arg(array('d' => $default), $avatar_src);
			}
			$avatar = SB_Core::build_avatar_image($size, $avatar_src);
		}
	}
	return $avatar;
}
add_filter('sb_theme_get_avatar', 'sb_theme_get_avatar_default', 10, 5);

/*
 * Thêm avatar mặc định
 */
function sb_theme_default_avatar_array($avatar_defaults) {
	$local_avatar = SB_CORE_URL . '/images/sb-default-avatar-32.png';
	$avatar_defaults[$local_avatar] = __('Avatar mặc định tùy chọn (Không hoạt động trên localhost)', 'sb-theme');
	$avatar_defaults = apply_filters('sb_theme_default_avatar_array', $avatar_defaults);
	return $avatar_defaults;
}
add_filter('avatar_defaults', 'sb_theme_default_avatar_array');

/*
 * Thêm avatar mặc định vào trang cài đặt bình luận,
 * hiển thị avatar mặc định mà người dụng cài đặt trong danh sách chọn avatar mặc định.
 */
function sb_theme_get_avatar_options_discussion($avatar, $id_or_email, $size, $default, $alt) {
	if(isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'options-discussion.php') {
		if(SB_PHP::is_string_contain($avatar, 'sb-default-avatar')) {
			$custom_avatar_url = SB_Comment::get_default_avatar_url();
			if(!empty($custom_avatar_url)) {
				$avatar_src = SB_PHP::get_image_source($avatar);
				$default = $custom_avatar_url;
				$is_localhost = SB_Core::is_localhost();
				if($is_localhost) {
					$default = 'mystery';
				}
				if(!empty($avatar_src)) {
					$avatar_src = add_query_arg(array('d' => $default), $avatar_src);
				}
				$avatar = SB_Core::build_avatar_image($size, $avatar_src);
				$avatar = str_replace('"', '\'', $avatar);
			}
		}
	}
	return $avatar;
}
add_filter('get_avatar', 'sb_theme_get_avatar_options_discussion', 10, 5);

/*
 * Lưu cache avatar
 */
function sb_theme_get_avatar_cache( $avatar, $id_or_email, $size, $default, $alt ) {
	$transient_name = SB_Cache::build_user_avatar_transient_name($id_or_email, '_' . $size);
	if(false === ($cached_avatar = get_transient($transient_name))) {
		$cached_avatar = $avatar;
		set_transient($transient_name, $cached_avatar, WEEK_IN_SECONDS);
	}
	$avatar = $cached_avatar;
	return $avatar;
}
add_filter( 'sb_theme_get_avatar' , 'sb_theme_get_avatar_cache' , 20 , 5 );

/*
 * Hiệu ứng rung khi phát hiện lỗi
 */
function sb_theme_login_shake_error($shake_error_codes) {
    $shake_error_codes[] = 'invalid_captcha_code';
    $shake_error_codes[] = 'authentication_failed';
    $shake_error_codes = apply_filters('sb_theme_login_shake_error_codes', $shake_error_codes);
    return $shake_error_codes;
}
add_filter( 'shake_error_codes', 'sb_theme_login_shake_error' );

/*
 * Lọc khi người dùng đăng nhập
 */
function sb_theme_authenticate_user($user, $username, $password) {
    if(!isset($_POST['wp-submit'])) {
        remove_filter('authenticate', 'sb_theme_authenticate_user', 10, 3);
        add_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
        return $user;
    }
    $checkemail = isset($_GET['checkemail']) ? $_GET['checkemail'] : '';
    $data_social = isset($_GET['data_social']) ? $_GET['data_social'] : '';
    if(!empty($checkemail) || !empty($data_social)) {
        add_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
        remove_filter('authenticate', 'sb_theme_authenticate_user', 10, 3);
        return $user;
    }
    if(SB_Captcha::check_login_captcha_post_empty()) {
        return SB_Message::empty_captcha_error();
    }
    if(!SB_User::is($user)) {
        $userdata = get_user_by('login', $username);
        if(!$userdata) {
            return new WP_Error('authentication_failed', __('Tài khoản hoặc mật khẩu không đúng.', 'sb-theme'));
        } else {
            $userdata = apply_filters('wp_authenticate_user', $userdata, $password);
            if(is_wp_error($userdata)) {
                return $userdata;
            }
            if(!wp_check_password($password, $userdata->user_pass, $userdata->ID)) {
                return new WP_Error('authentication_failed', __('Tài khoản hoặc mật khẩu không đúng.', 'sb-theme'));
            }
            if(!SB_Captcha::check_login_captcha_post_valid()) {
                return SB_Message::invalid_captcha_error();
            }
            $user =  new WP_User($userdata->ID);
        }
    }
    $user = apply_filters('sb_theme_authenticate', $user, $username, $password);
    return $user;
}
remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
add_filter('authenticate', 'sb_theme_authenticate_user', 10, 3);

/*
 * Lọc khi người dùng yêu cầu lấy lại mật khẩu
 */
function sb_theme_registration_errors_filter($errors, $sanitized_user_login, $user_email) {
    if(SB_Captcha::check_login_captcha_post_empty()) {
        $sb_error = SB_Message::empty_captcha_error();
        $error_code = $sb_error->get_error_code();
        $error_message = $sb_error->get_error_message($error_code);
        $errors->add($error_code, $error_message);
        return $errors;
    }
    if(!SB_Captcha::check_login_captcha_post_valid()) {
        $sb_error = SB_Message::invalid_captcha_error();
        $error_code = $sb_error->get_error_code();
        $error_message = $sb_error->get_error_message($error_code);
        $errors->add($error_code, $error_message);
        return $errors;
    }
    return $errors;
}
add_filter('registration_errors', 'sb_theme_registration_errors_filter', 10, 3);

/*
 * Lọc giao diện có sử dụng captcha
 */
function sb_theme_use_captcha_filter($result) {
    if(SB_Option::use_login_captcha()) {
        $result = true;
    }
    return $result;
}
add_filter('sb_theme_use_captcha', 'sb_theme_use_captcha_filter');

/*
 * Không cho người dùng chỉnh sửa quyền admin
 */
function sb_theme_remove_admin_editable_role($roles) {
    $current_user = SB_User::get_current();
    if((SB_Membership::has_super_admin() && !SB_Membership::is_super_admin($current_user->ID)) || !current_user_can('update_core')) {
        unset($roles['administrator']);
        unset($roles['super_administrator']);
    }
    $roles = apply_filters('sb_theme_editable_roles', $roles);
    $roles = apply_filters('sb_theme_select_roles', $roles);
    return $roles;
}
add_filter('editable_roles', 'sb_theme_remove_admin_editable_role');

/*
 * Lọc role hiển thị cho người dùng lựa chọn
 */
function sb_theme_filter_select_user_groups( $editable_roles ) {
    if(current_user_can('update_core')) {
        if(isset($GLOBALS['pagenow']) && ('user-new.php' == $GLOBALS['pagenow'] || 'user-edit.php' == $GLOBALS['pagenow'])) {
            if(SB_Membership::is_paid_membership_enabled()) {
                $paid_roles = SB_Membership::get_paid_membership_groups();
                foreach($paid_roles as $key => $data) {
                    $editable_roles[$key] = $data;
                }
            }
        }
    }
    return $editable_roles;
}
//add_filter('sb_theme_select_roles', 'sb_theme_filter_select_user_groups');

/*
 * Thay đổi tên người gửi mail
 */
function sb_theme_mail_from_name( $name ) {
	if ( 'wordpress' == strtolower( $name ) ) {
		$name = get_bloginfo( 'name' );
	}
    if(empty($name) && SB_Option::use_smtp_mail()) {
        $name = SB_Option::get_option_by_key(array('smtp_mail', 'from_name'));
    }
	$name = apply_filters('sb_theme_mail_from_name', $name);
	return $name;
}
add_filter( 'wp_mail_from_name', 'sb_theme_mail_from_name' );

/*
 * Lọc địa chỉ email người gửi mail
 */
function sb_theme_mail_from_filter($email) {
    if(empty($email) && SB_Option::use_smtp_mail()) {
        $email = SB_Option::get_option_by_key(array('smtp_mail', 'from_email'));
    }
    $email = apply_filters('sb_theme_mail_from', $email);
    return $email;
}
add_filter('wp_mail_from', 'sb_theme_mail_from_filter');

/*
 * Khai báo đường dẫn chuyển tiếp khi đăng xuất
 */
function sb_login_page_custom_logout_redirect($logout_url, $redirect) {
	$redirect = SB_User::get_logout_redirect();
	$redirect = apply_filters('sb_theme_logout_redirect', $redirect);
	$logout_url = add_query_arg(array('redirect_to' => $redirect), $logout_url);
    $logout_url = apply_filters('sb_theme_logout_url', $logout_url, $redirect);
	return $logout_url;
}
add_filter('logout_url', 'sb_login_page_custom_logout_redirect', 10, 2);

/*
 * Chỉ cho phép tác giả xem bài viết của chính mình
 */
function sb_theme_author_view_only_own_post( $wp_query ) {
    if(strpos($_SERVER['REQUEST_URI'], '/wp-admin/edit.php') !== false) {
        if(!current_user_can('update_core')) {
            global $current_user;
            $wp_query->set('author', $current_user->ID);
        }
    }
    apply_filters('sb_theme_parse_query', $wp_query);
}
add_filter('parse_query', 'sb_theme_author_view_only_own_post');

/*
 * Xóa tất cả đường link trong nội dung bài viết
 */
function sb_theme_remove_all_link_from_post_content($content) {
    $current_user = SB_User::get_current();
    if(!SB_User::is_admin($current_user->ID) && in_array(SB_User::get_current_role(), SB_Membership::get_paid_role_ids())) {
        $content = SB_PHP::remove_all_link($content);
    }
    return $content;
}
add_filter('sb_theme_pre_save_post_content', 'sb_theme_remove_all_link_from_post_content');

/*
 * Lọc nội dung bài viết trước khi lưu vào cơ sở dữ liệu
 */
function sb_theme_before_save_post_content_filter( $content ) {
    $content = apply_filters('sb_theme_pre_save_post_content', $content);
    return $content;
}
add_filter( 'content_save_pre', 'sb_theme_before_save_post_content_filter', 10, 1 );

/*
 * Khai báo đường dẫn chuyển tiếp khi đăng nhập
 */
function sb_login_page_custom_login_redirect($login_url, $redirect) {
	$redirect = SB_User::get_login_redirect();
	$redirect = apply_filters('sb_theme_login_redirect', $redirect);
	$login_url = add_query_arg(array('redirect_to' => $redirect), $login_url);
    $login_url = apply_filters('sb_theme_login_url', $login_url, $redirect);
	return $login_url;
}
add_filter('login_url', 'sb_login_page_custom_login_redirect', 10, 2);

/*
 * Thêm thông tin cho tài khoản người dùng
 */
function sb_theme_add_user_contact_fields($fields) {
	$fields['phone'] = __('Phone', 'sb-theme');
	$fields['address'] = __('Address', 'sb-theme');
	$fields['identity'] = __('Identity', 'sb-theme');
	$fields = apply_filters('sb_theme_user_contact_fields', $fields);
	return $fields;
}
add_filter('user_contactmethods', 'sb_theme_add_user_contact_fields');

/*
 * Thay đổi đường dẫn logo trang đăng nhập về trang chủ
 */
function sb_theme_login_logo_url() {
	$url = home_url('/');
	$url = apply_filters('sb_theme_login_logo_url', $url);
	return $url;
}
add_filter( 'login_headerurl', 'sb_theme_login_logo_url');

/*
 * Thay đổi mô tả cho logo trang đăng nhập
 */
function sb_theme_login_logo_description() {
	$desc = get_bloginfo('description');
	$desc = apply_filters('sb_theme_login_logo_description', $desc);
	return $desc;
}
add_filter('login_headertitle', 'sb_theme_login_logo_description');

/*
 * Thêm class trang đăng nhập vào thẻ body
 */
function sb_theme_login_body_class($classes) {
	if(is_page() && is_page_template()) {
		global $post;
		$login_page_id = sb_login_page_get_page_login_id();
		$account_page_id = sb_login_page_get_page_account_id();
		$lost_password_page_id = sb_login_page_get_page_lost_password_id();
		$register_page_id = sb_login_page_get_page_register_id();
		if($post->ID == $login_page_id) {
			$classes[] = 'sb-theme';
		} elseif($post->ID == $account_page_id) {
			$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
			if($action == 'verify') {
				$classes[] = 'sb-verify-account';
			}
			$classes[] = 'sb-account-page';
		} elseif($post->ID == $lost_password_page_id) {
			$classes[] = 'sb-lost-password-page';
		} elseif($post->ID == $register_page_id) {
			$classes[] = 'sb-register-page';
		}
		if(SB_User::is_logged_in()) {
			$classes[] = 'sb-user';
		} else {
			$classes[] = 'sb-guest';
		}
	}
	$classes = apply_filters('sb_theme_body_class', $classes);
	return $classes;
}
add_filter('body_class', 'sb_theme_login_body_class');

function sb_theme_login_body_class_filter($classes, $action) {
    $classes[] = 'sb-theme-login';
    if(!empty($action)) {
        $classes[] = 'sb-theme-action-' . $action;
    }
    $classes = apply_filters('sb_theme_login_body_class', $classes, $action);
    return $classes;
}
add_filter('login_body_class', 'sb_theme_login_body_class_filter', 10, 3);

function sb_theme_login_message_hook($message) {
    $message = apply_filters('sb_theme_login_message', $message);
    return $message;
}
add_filter('login_message', 'sb_theme_login_message_hook');

function sb_theme_login_message_vietnamese($message) {
    if(SB_Option::get_default_language() == 'vi') {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        switch($action) {
            case 'register':
                $message = '<p class="message register">' . __('Đăng ký làm thành viên.', 'sb-theme') . '</p>';
                break;
            case 'lostpassword':
                $message = '<p class="message">' . __('Hãy điền vào địa chỉ email của bạn để lấy lại mật khẩu.', 'sb-theme') . '</p>';
                break;
        }
    }
    return $message;
}
add_filter('sb_theme_login_message', 'sb_theme_login_message_vietnamese');

function sb_theme_custom_login_error($error) {
    if(!isset($_POST['wp-submit'])) {
        return $error;
    }
    if('vi' == SB_Option::get_default_language()) {
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'lostpassword') {
            $error = '<strong>' . __('Lỗi:', 'sb-theme') . '</strong> ' . __('Xin vui lòng nhập chính xác địa chỉ email.', 'sb-theme');
        } elseif(isset($_REQUEST['registration']) && $_REQUEST['registration'] == 'disabled') {
            $error = '<strong>' . __('Lỗi:', 'sb-theme') . '</strong> ' . __('Hệ thống không cho phép đăng ký tài khoản.', 'sb-theme');
        } else {
            $error = '<strong>' . __('Lỗi:', 'sb-theme') . '</strong> ' . __('Xin vui lòng kiểm tra lại.', 'sb-theme');
        }
    }
    return $error;
}
add_filter( 'login_errors', 'sb_theme_custom_login_error' );

function sb_theme_change_login_form_text( $translation, $text ) {
    if(SB_Core::is_login_page() && 'vi' == SB_Option::get_default_language()) {
        switch($text) {
            case 'Username':
                $translation = 'Tên tài khoản';
                break;
            case 'Password':
                $translation = 'Mật khẩu';
                break;
            case 'Remember Me':
                $translation = 'Nhớ đăng nhập';
                break;
            case 'Log In':
                $translation = 'Đăng nhập';
                break;
            case 'Log in':
                $translation = 'Đăng nhập';
                break;
            case 'Lost your password?':
                $translation = 'Quên mật khẩu?';
                break;
            case '&larr; Back to %s':
                $translation = 'Quay lại trang chủ';
                break;
            case 'Register':
                $translation = 'Đăng ký';
                break;
            case 'E-mail':
                $translation = 'Địa chỉ email';
                break;
            case 'A password will be e-mailed to you.':
                $translation = 'Mật khẩu sẽ được chuyển đến email của bạn.';
                break;
            case 'Username or E-mail:':
                $translation = 'Tên tài khoản hoặc địa chỉ email:';
                break;
            case 'Get New Password':
                $translation = 'Nhận mật khẩu mới';
                break;
            case 'You are now logged out.':
                $translation = 'Bạn đã đăng xuất khỏi hệ thống.';
                break;
        }
    }
    return $translation;
}
add_filter( 'gettext', 'sb_theme_change_login_form_text', 10, 2 );

function sb_theme_change_woocommerce_text($translation, $text) {
    if(SB_Theme::support('woocommerce') && (!is_admin() || (is_admin() && defined('DOING_AJAX')))) {
        switch($text) {
            case 'View Cart':
                $translation = SB_Text::get_view_cart();
                break;
            case 'Apply Coupon':
                $translation = 'Áp dụng mã giảm giá';
                break;
            case 'Coupon code':
                $translation = 'Mã giảm giá';
                break;
            case 'Product':
                $translation = 'Sản phẩm';
                break;
            case 'Price':
                $translation = 'Giá';
                break;
            case 'Quantity':
                $translation = 'Số lượng';
                break;
            case 'Total':
                $translation = 'Tổng cộng';
                break;
            case 'Remove this item':
                $translation = 'Xóa đối tượng này';
                break;
            case 'Update Cart':
                $translation = 'Cập nhật giỏ hàng';
                break;
            case 'Cart Totals':
                $translation = 'Tổng số trong giỏ';
                break;
            case 'Subtotal':
                $translation = 'Tạm tính';
                break;
            case 'Shipping':
                $translation = 'Vận chuyển';
                break;
            case 'Free Shipping':
                $translation = 'Miễn phí vận chuyển';
                break;
            case 'Calculate Shipping':
                $translation = 'Tính phí vận chuyển';
                break;
            case 'Proceed to Checkout':
                $translation = 'Tiến hành thanh toán';
                break;
            case 'Update Totals':
                $translation = 'Cập nhật tổng';
                break;
            case 'Postcode / Zip':
                $translation = 'Mã bưu điện';
                break;
            case 'Have a coupon?':
                $translation = 'Bạn có mã giảm giá?';
                break;
            case 'Click here to enter your code':
                $translation = 'Nhấn vào đây để nhập mã của bạn';
                break;
            case 'Billing Details':
                $translation = 'Thông tin thanh toán';
                break;
            case 'Ship to a different address?':
                $translation = 'Giao hàng tới địa chỉ khác?';
                break;
            case 'Country':
                $translation = 'Quốc gia';
                break;
            case 'First Name':
                $translation = 'Tên';
                break;
            case 'Last Name':
                $translation = 'Họ';
                break;
            case 'Company Name':
                $translation = 'Tên công ty / doanh nghiệp';
                break;
            case 'Address':
                $translation = 'Địa chỉ';
                break;
            case 'Order Notes':
                $translation = 'Ghi chú';
                break;
            case 'Notes about your order, e.g. special notes for delivery.':
                $translation = 'Ghi chú về đơn hàng của bạn, ví dụ: lưu ý khi giao hàng.';
                break;
            case 'Town / City':
                $translation = 'Quận huyện / Thành phố';
                break;
            case 'Email Address':
                $translation = 'Địa chỉ email';
                break;
            case 'Phone':
                $translation = 'Số điện thoại';
                break;
            case 'Your order':
                $translation = 'Đơn hàng của bạn';
                break;
            case 'Direct Bank Transfer':
                $translation = 'Chuyển khoản ngân hàng';
                break;
            case 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.':
                $translation = 'Cập nhật tổng';
                break;
            case 'Cheque Payment':
                $translation = 'Thanh toán bằng Séc';
                break;
            case 'Please send your cheque to Store Name, Store Street, Store Town, Store State / County, Store Postcode.':
                $translation = 'Xin vui lòng gửi thông tin thanh toán bằng Séc của bạn đến địa chỉ của cửa hàng.';
                break;
            case 'Place order':
                $translation = 'Đặt hàng';
                break;
            case 'Free':
                $translation = 'Miễn phí';
                break;
            case 'Order Received':
                $translation = 'Ghi nhận đơn hàng';
                break;
            case 'Thank you. Your order has been received.':
                $translation = 'Cảm ơn bạn đã đặt hàng, đơn hàng của bạn đã được lưu vào hệ thống.';
                break;
            case 'Order Number:':
                $translation = 'Mã đơn hàng:';
                break;
            case 'Date:':
                $translation = 'Ngày tháng:';
                break;
            case 'Total:':
                $translation = 'Tổng cộng:';
                break;
            case 'Payment Method:':
                $translation = 'Phương thức thanh toán:';
                break;
            case 'Our Bank Details':
                $translation = 'Thông tin ngân hàng của chúng tôi';
                break;
            case 'Order Details':
                $translation = 'Thông tin đơn hàng';
                break;
            case 'Subtotal:':
                $translation = 'Tạm tính:';
                break;
            case 'Shipping:':
                $translation = 'Vận chuyển:';
                break;
            case 'Payment Method:':
                $translation = 'Phương thức thanh toán:';
                break;
            case 'Total:':
                $translation = 'Tổng cộng:';
                break;
            case 'Customer details':
                $translation = 'Thông tin khách hàng';
                break;
            case 'Email:':
                $translation = 'Địa chỉ email:';
                break;
            case 'Telephone:':
                $translation = 'Số điện thoại:';
                break;
            case 'Billing Address':
                $translation = 'Địa chỉ thanh toán';
                break;
            case 'Shipping Address':
                $translation = 'Địa chỉ nhận hàng';
                break;
            case 'Cart updated.':
                $translation = 'Giỏ hàng đã được cập nhật thành công.';
                break;
            case '%s removed.':
                $translation = '%s đã được xóa.';
                break;
            case 'Undo?':
                $translation = 'Phục hồi?';
                break;
            case '%s removed. %sUndo?%s':
                $translation = '%s đã được xóa. %sPhục hồi?%s';
                break;
            case 'Your cart is currently empty.':
                $translation = 'Hiện không có sản phẩm nào được đặt vào giỏ hàng.';
                break;
            case 'Return To Shop':
                $translation = 'Quay trở lại cửa hàng';
                break;
            case 'Returning customer?':
                $translation = 'Bạn đã có tài khoản?';
                break;
            case 'Click here to login':
                $translation = 'Nhấn vào đây để đăng nhập';
                break;
            case 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing & Shipping section.':
                $translation = 'Nếu bạn đã từng mua hàng với chúng tôi trước đây, xin vui lòng nhập thông tin của bạn vào ô bên dưới. Nếu bạn là khách hàng mới, xin vui lòng nhập địa chỉ thanh toán và địa chỉ nhận hàng vào các ô bên dưới.';
                break;
            case 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.':
                $translation = 'Nếu bạn đã từng mua hàng với chúng tôi trước đây, xin vui lòng nhập thông tin của bạn vào ô bên dưới. Nếu bạn là khách hàng mới, xin vui lòng nhập địa chỉ thanh toán và địa chỉ nhận hàng vào các ô bên dưới.';
                break;
            case 'Username or email':
                $translation = 'Tài khoản hoặc email';
                break;
            case 'Password':
                $translation = 'Mật khẩu';
                break;
            case 'Remember me':
                $translation = 'Ghi nhớ đăng nhập';
                break;
            case 'Login':
                $translation = 'Đăng nhập';
                break;
            case 'Lost your password?':
                $translation = 'Bạn quên mật khẩu?';
                break;
            case 'Street address':
                $translation = 'Địa chỉ đường phố';
                break;
            case 'Create an account?':
                $translation = 'Tạo tài khoản mới?';
                break;
            case '%s is a required field.':
                $translation = '%s là mục bắt buộc nhập.';
                break;
            case 'is a required field.':
                $translation = 'là mục bắt buộc nhập.';
                break;
            case 'Please enter a coupon code.':
                $translation = 'Xin vui lòng nhập vào mã giảm giá.';
                break;
            case 'Coupon does not exist!':
                $translation = 'Mã giảm giá không tồn tại!';
                break;
            case 'Coupon code applied successfully.':
                $translation = 'Mã giảm giá đã được áp dụng thành công.';
                break;
            case 'Sorry, it seems the coupon "%s" is invalid - it has now been removed from your order.':
                $translation = 'Xin lỗi, mã giảm giá "%s" không đúng - hệ thống đã xóa mã giảm giá ra khỏi đơn hàng của bạn.';
                break;
            case 'Return To Cart':
                $translation = 'Quay trở lại giỏ hàng';
                break;
            case 'There are some issues with the items in your cart (shown above). Please go back to the cart page and resolve these issues before checking out.':
                $translation = 'Có một vài vấn đề xảy ra với giỏ hàng của bạn (xem thông báo bên trên). Xin vui lòng quay trở lại giỏ hàng và khắc phục các sự cố này trước khi thanh toán.';
                break;
            case 'This coupon has expired.':
                $translation = 'Mã giảm giá đã hết hạn sử dụng.';
                break;
            case 'Add to cart':
                $translation = SB_Text::get_add_to_cart();
                break;
            case 'Shipping costs updated.':
                $translation = 'Phí vận chuyển đã được cập nhật.';
                break;
            case 'Description':
                $translation = 'Mô tả';
                break;
            case '%s review for %s':
                $translation = '%s đánh giá cho %s';
                break;
            case '%s reviews for %s':
                $translation = '%s đánh giá cho %s';
                break;
            case 'Reviews (%d)':
                $translation = 'Đánh giá (%d)';
                break;
            case 'Additional Information':
                $translation = 'Thông tin thêm';
                break;
            case 'Add a review':
                $translation = 'Thêm nhận xét';
                break;
            case 'Your Rating':
                $translation = 'Đánh giá của bạn';
                break;
            case 'Your Review':
                $translation = 'Nhận xét của bạn';
                break;
            case '(%d customer review)':
                $translation = '(%d nhận xét)';
                break;
            case 'Submit':
                $translation = 'Gửi';
                break;
            case '%s customer reviews':
                $translation = '% nhận xét';
                break;
            case '%s customer review':
                $translation = '% nhận xét';
                break;
            case 'Be the first to review':
                $translation = 'Trở thành người đầu tiên đánh giá';
                break;
            case 'There are no reviews yet.':
                $translation = 'Hiện chưa có đánh giá nào.';
                break;
            case 'Please select a rating':
                $translation = 'Xin vui lòng chọn đánh giá của bạn!';
                break;
            case '&quot;%s&quot; was successfully added to your cart.':
                $translation = '&quot;%s&quot; đã được thêm vào giỏ hàng thành công.';
                break;
            case 'Name':
                $translation = 'Tên';
                break;
            case 'Your comment is awaiting approval':
                $translation = 'Bình luận của bạn đang được đợi để xét duyệt.';
                break;
        }
    }
    return $translation;
}
add_filter('gettext', 'sb_theme_change_woocommerce_text', 10, 2);

function sb_theme_change_woocommerce_text_with_context($translations, $text, $context, $domain = 'default') {
    if(SB_Theme::support('woocommerce') && (!is_admin() || (is_admin() && defined('DOING_AJAX')))) {
        switch($text) {
            case 'Notes about your order, e.g. special notes for delivery.':
                $translations = 'Ghi chú về đơn hàng của bạn, ví dụ: lưu ý khi giao hàng.';
                break;
            case 'Street address':
                $translations = 'Địa chỉ đường phố';
                break;
            case '%s review for %s':
                $translations = '%s nhận xét cho %s';
                break;
            case '%s reviews for %s':
                $translations = '%s nhận xét cho %s';
                break;
            case '%s customer reviews':
                $translations = '% nhận xét';
                break;
        }
    }
    return $translations;
}
add_filter('gettext_with_context', 'sb_theme_change_woocommerce_text_with_context', 10, 3, 2);

function sb_theme_change_woocommerce_template_path($template, $template_name, $template_path) {
    $woocommerce_template_path  = trailingslashit(SB_THEME_CONTENT_WOOCOMMERCE_PATH);
    $file_path = $woocommerce_template_path . $template_name;
    if(file_exists($file_path)) {
        $template = $file_path;
    }
    return $template;
}
add_filter('woocommerce_locate_template', 'sb_theme_change_woocommerce_template_path', 10, 3);

function sb_theme_woocommerce_template_part_filter($template, $slug, $name) {
    $woocommerce_template_path  = trailingslashit(SB_THEME_CONTENT_WOOCOMMERCE_PATH);
    if(!empty($name)) {
        $file_name = "{$slug}-{$name}.php";
    } else {
        $file_name = "{$slug}.php";
    }
    $file_path = $woocommerce_template_path . $file_name;
    if(file_exists($file_path)) {
        $template = $file_path;
    }
    return $template;
}
add_filter('wc_get_template_part', 'sb_theme_woocommerce_template_part_filter', 10, 3);

function sb_theme_cart_shipping_method_filter($label, $method) {
    if(is_object($method) && $method->id == 'free_shipping') {
        $label = 'Miễn phí vận chuyển';
    }
    return $label;
}
add_filter('woocommerce_cart_shipping_method_full_label', 'sb_theme_cart_shipping_method_filter', 10, 3);

/*
 * Thêm class của SB vào thẻ body
 */
function sb_theme_body_class($classes) {
    $classes[] = 'sb-theme-front-end';
	$browser_code = SB_Browser::get_name();
	if($browser_code != 'ie' && strlen($browser_code) > 2 && SB_PHP::is_string_contain($browser_code, 'ie')) {
		$browser_code .= ' browser-ie';
	}
	$name = 'browser-' . $browser_code;
	$name = trim($name, '-');
	$classes[] = $name;
	if(SB_Detect::is_mobile()) {
		$classes[] = 'mobile';
	} else {
		$classes[] = 'pc';
	}
	if(is_singular()) {
		$classes[] = 'sb-singular';
		if(!is_page()) {
			$classes[] = 'sb-singular-post';
		}
	}
	if(is_404()) {
		$classes[] = 'sb-not-found';
	}
	if(SB_Option::utility_enabled('jquery_snowfall')) {
		$classes[] = 'snowfall';
	}
	$classes[] = 'sb-theme sb-team';
	if(SB_User::is_logged_in()) {
		$classes[] = 'sb-user';
	} else {
		$classes[] = 'sb-guest';
	}
	return $classes;
}
add_filter('sb_theme_body_class', 'sb_theme_body_class');

/*
 * Thêm thông tin của SB vào trong class của bài viết
 */
function sb_theme_post_class($classes) {
	global $post;
	if(!SB_Core::is_error($post)) {
		if(is_sticky($post->ID)) {
			$classes[] = 'sb-post-sticky';
		}
	}
	$classes[] = 'sb-post';
	$classes = apply_filters('sb_theme_post_class', $classes);
	return $classes;
}
add_filter('post_class', 'sb_theme_post_class');

/*
 * Khai báo ký tự excerpt more
 */
function sb_theme_excerpt_more($more) {
	$more = '...';
	$more = apply_filters('sb_theme_excerpt_more', $more);
	return $more;
}
add_filter( 'excerpt_more', 'sb_theme_excerpt_more' );

/*
 * Thêm nút vào trình soạn thảo, thanh công cụ đầu tiên
 */
function sb_theme_more_mce_buttons_toolbar_1( $buttons ) {
	if(is_admin()) {
        $tmp = $buttons;
        unset($buttons);
        $buttons[] = 'fontselect';
        $buttons[] = 'fontsizeselect';
        $last = array_pop($tmp);
        $buttons = array_merge($buttons, $tmp);
        $buttons[] = 'styleselect';
        $buttons[] = $last;
    }
	return $buttons;
}
add_filter( 'mce_buttons', 'sb_theme_more_mce_buttons_toolbar_1' );

/*
 * Thêm nút vào trình soạn thảo, thanh công cụ thứ 2
 */
function sb_theme_more_mce_buttons_toolbar_2( $buttons ) {
	if(is_admin()) {
        $buttons[] = 'subscript';
        $buttons[] = 'superscript';
        $buttons[] = 'hr';
        $buttons[] = 'cut';
        $buttons[] = 'copy';
        $buttons[] = 'paste';
        $buttons[] = 'backcolor';
        $buttons[] = 'newdocument';
    }
	return $buttons;
}
add_filter( 'mce_buttons_2', 'sb_theme_more_mce_buttons_toolbar_2' );

/*
 * Kiểm tra và thay đổi tên tập tin
 */
function sb_theme_sanitize_media_file_name($filename) {
	$filename = SB_PHP::remove_vietnamese(SB_PHP::lowercase($filename));
	$filename = apply_filters('sb_theme_sanitize_file_name', $filename);
	return $filename;
}
add_filter('sanitize_file_name', 'sb_theme_sanitize_media_file_name', 10);

/*
 * Xóa các cột không cần thiết trong trang quản lý bài viết
 */
function sb_theme_remove_post_table_columns( $columns ) {
	unset( $columns['wpseo-title'] );
	unset( $columns['wpseo-metadesc'] );
	unset( $columns['wpseo-focuskw'] );
    $columns = apply_filters('sb_theme_edit_post_column', $columns);
	return $columns;
}
add_filter( 'manage_edit-post_columns', 'sb_theme_remove_post_table_columns' );

/*
 * Xóa các cột không cần thiết trong trang quản lý trang
 */
function sb_theme_remove_page_table_columns( $columns ) {
    $columns = apply_filters('sb_theme_edit_page_column', $columns);
	return $columns;
}
add_filter( 'manage_edit-page_columns', 'sb_theme_remove_page_table_columns' );

/*
 * Xóa các thứ không cần thiết trong excerpt
 */
function sb_theme_shortcode_from_excerpt($excerpt) {
	$excerpt = trim(preg_replace('|\[(.+?)\](.+?\[/\\1\])?|s', '', $excerpt));
	$excerpt = strip_shortcodes($excerpt);
	$excerpt = strip_tags($excerpt);
	$excerpt = trim(trim($excerpt, '&nbsp;'));
	$excerpt = wpautop($excerpt);
    $excerpt = apply_filters('sb_theme_the_excerpt', $excerpt);
	return trim($excerpt);
}
add_filter('the_excerpt', 'sb_theme_shortcode_from_excerpt');

/*
 * Kiểm tra thông tin trước khi upload tập tin
 */
function sb_theme_pre_upload_file( $file ){
	$file['name'] = SB_PHP::remove_vietnamese(SB_PHP::lowercase($file['name']));
	$file = apply_filters('sb_theme_pre_upload_file', $file);
    $file = apply_filters('sb_theme_before_upload_file', $file);
	return $file;
}
add_filter('wp_handle_upload_prefilter', 'sb_theme_pre_upload_file' );

function sb_theme_check_file_size_before_upload($file) {
    $type = $file['type'];
    $is_image = strpos($type, 'image');
    $size = $file['size'] / 1024;
    $limit_size = SB_Core::get_file_size_limit();
    $limit_count = SB_Core::get_file_count_limit();
    if($is_image !== false) {
        $limit_image_size = SB_Core::get_image_size_limit();
        $limit_image_count = SB_Core::get_image_count_limit();
        $allow_image_type = SB_Core::get_image_type_allow();
        $user = SB_User::get_current();
        $args = array(
            'orderby' => 'post_date',
            'order' => 'DESC',
            'numberposts' => -1,
            'post_type' => 'attachment',
            'author' => $user->ID,
        );
        $query = SB_Query::get($args);
        $attachmentsbyuser = $query->posts;

        if($limit_image_size > 0 && $size > $limit_image_size) {
            $file['error'] = sprintf(__('Hình ảnh không được vượt quá %s KB.', 'sb-theme'), $limit_image_size);
        } elseif(count($allow_image_type) > 0 && !in_array($type, $allow_image_type)) {
            $file['error'] = sprintf(__('Định dạng của hình ảnh phải là %s.', 'sb-theme'), implode(', ', $allow_image_type));
        }
    } else {
        if($limit_size > 0 && $size > $limit_size) {
            $file['error'] = sprintf(__('Tập tin không được vượt quá %s KB.', 'sb-theme'), $limit_size);
        }
    }
    return $file;

}
add_filter('sb_theme_before_upload_file', 'sb_theme_check_file_size_before_upload');

/*
 * Xóa các kích thước hình ảnh thumbnail mặc định
 */
function sb_theme_remove_default_image_sizes($sizes) {
	if(isset($sizes['thumbnail'])) {
		unset($sizes['thumbnail']);
	}
	if(isset($sizes['medium'])) {
		unset($sizes['medium']);
	}
	if(isset($sizes['large'])) {
		unset($sizes['large']);
	}
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'sb_theme_remove_default_image_sizes');

// Tắt auto paragraph trong shortcode
remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop' , 99);
add_filter('the_content', 'shortcode_unautop', 100);

/*
 * Kiểm tra dữ liệu bình luận trước khi thêm vào cơ sở dữ liệu
 */
function sb_theme_preprocess_comment($commentdata) {
	if(SB_Comment::enable_spam_check() && sb_comment_spam($commentdata)) {
		$commentdata['comment_content'] = '';
		SB_Comment::set_spam_session(1);
		return $commentdata;
	}
	$comment_author_url = isset($commentdata['comment_author_url']) ? $commentdata['comment_author_url'] : '';
	if(!empty($comment_author_url) && (!SB_PHP::is_url($comment_author_url) || SB_Comment::disable_website_url())) {
		unset( $commentdata['comment_author_url'] );
	} else {
		$commentdata['comment_author_url'] = SB_PHP::get_domain_name_with_http($comment_author_url);
	}
	if( $commentdata['comment_content'] == SB_PHP::strtoupper( $commentdata['comment_content'] )) {
		$commentdata['comment_content'] = SB_PHP::strtolower( $commentdata['comment_content'] );
	}
    $commentdata = apply_filters('sb_theme_pre_save_comment', $commentdata);
    $commentdata = apply_filters('sb_theme_preprocess_comment', $commentdata);
	return $commentdata;
}
add_filter('preprocess_comment', 'sb_theme_preprocess_comment', 1);

function sb_theme_filter_term_slug_before_insert($value) {
    if(isset($_POST['taxonomy'])) {
        $taxonomy = $_POST['taxonomy'];
        $slug = isset($_POST['slug']) ? $_POST['slug'] : '';
        if(empty($slug)) {
            $name = isset($_POST['tag-name']) ? $_POST['tag-name'] : '';
            if(!empty($name)) {
                $slug = sanitize_title($name);
                if(SB_Option::use_administrative_boundaries()) {
                    $district = isset($_POST['district']) ? absint($_POST['district']) : 0;
                    if($district > 0) {
                        $item = SB_Term::get_by('id', $district, 'district');
                        if(is_object($item) && !is_wp_error($item)) {
                            $value = $slug . '-' . $item->slug;
                        }
                    }
                }
                $value = apply_filters('sb_theme_pre_term_slug', $slug, $taxonomy);
            }
        }
    }

    return $value;
}
add_filter('pre_category_nicename', 'sb_theme_filter_term_slug_before_insert');

function sb_theme_filter_option_by_option($options) {
    $media_link_to = isset($options['sbt_advanced']['writing']['media_link_to']) ? $options['sbt_advanced']['writing']['media_link_to'] : '';
    if(!empty($media_link_to)) {
        update_option('image_default_link_type', $media_link_to);
    }
    return $options;
}
add_filter('sb_theme_sanitize_option', 'sb_theme_filter_option_by_option');

function sb_theme_add_to_cart_text_filter() {
    $woocommerce_version = SB_Core::get_woocommerce_version();
    $text = SB_Text::get_add_to_cart();
    if(version_compare($woocommerce_version, '2.1', '>=')) {

    }
    return $text;
}
add_filter('add_to_cart_text', 'sb_theme_add_to_cart_text_filter');
add_filter('woocommerce_product_add_to_cart_text', 'sb_theme_add_to_cart_text_filter', 10);

do_action('sb_theme_hook');

do_action('sb_theme_hook_after');