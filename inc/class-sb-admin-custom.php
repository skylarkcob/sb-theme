<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Admin_Custom {
    public static function add_submenu_page( $title, $slug, $callback ) {
        if ( ! self::submenu_page_exists( $slug ) ) {
            add_submenu_page( 'sb_options', $title, $title, 'manage_options', $slug, $callback );
        }
    }

    public static function submenu_page_exists( $handle ) {
        return self::menu_page_exists( $handle, true );
    }

    public static function menu_page_exists( $handle, $sub = false ) {
        if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
            return false;
        }
        global $menu, $submenu;
        $check_menu = $sub ? $submenu : $menu;
        if ( empty( $check_menu ) ) {
            return false;
        }
        foreach ( $check_menu as $k => $item ) {
            if ( $sub ) {
                foreach ( $item as $sm ) {
                    if( $sm[2] == $handle ) {
                        return true;
                    }
                }
            } else {
                if( $item[2] == $handle ) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function get_current_page() {
        return isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
    }

    public static function add_recaptcha_setting_field_callback() {
        $options = SB_Option::get();
        $value = isset( $options['global']['recaptcha']['site_key'] ) ? $options['global']['recaptcha']['site_key'] : '';
        $args = array(
            'id' => 'sb_core_global_recaptcha_site_key',
            'name' => SB_Option::build_sb_option_name( array( 'global', 'recaptcha', 'site_key' ) ),
            'value' => $value,
            'description' => __( 'The Google reCaptcha Site key, you can use this in the HTML code your site serves to users.', 'sb-theme' ),
            'container_class' => 'margin-bottom'
        );
        SB_Field::text( $args );
        $value = isset( $options['global']['recaptcha']['secret_key'] ) ? $options['global']['recaptcha']['secret_key'] : '';
        $args = array(
            'id' => 'sb_core_global_recaptcha_secret_key',
            'name' => SB_Option::build_sb_option_name( array( 'global', 'recaptcha', 'secret_key' ) ),
            'value' => $value,
            'description' => __( 'The reCaptcha Secret key, you can use this for communication between your site and Google.', 'sb-theme' ),
        );
        SB_Field::text( $args );
    }

	public static function setting_field_category_sortable_connect() {
		sb_theme_setting_field_category_widget();
	}

    public static function add_recaptcha_setting_field( $section_id, $page_id ) {
        self::add_setting_field( 'sb_theme_global_recaptcha_key', 'reCAPTCHA', $section_id, array( 'SB_Admin_Custom', 'add_recaptcha_setting_field_callback' ), $page_id );
    }

    public static function add_media_upload_to_post_page() {
        $pagenow = SB_Admin_Custom::get_page_now();
        if ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) {
            wp_enqueue_media();
        }
    }

    public static function get_page_now() {
        global $pagenow;
        return $pagenow;
    }

    public static function get_pagenow() {
        return self::get_page_now();
    }

    public static function is_edit_term_page() {
        $page = self::get_page_now();
        if ( false !== strrpos( $page, 'edit-tags' ) ) {
            return true;
        }
        return false;
    }

    public static function row_setting_field($args = array()) {
        $title = isset($args['title']) ? $args['title'] : '';
        $decription = isset($args['description']) ? $args['description'] : '';
        $callback = isset($args['callback']) ? $args['callback'] : '';
        if(!empty($callback)) : ?>
            <div class="a-row">
                <strong class="row-title"><?php echo $title; ?></strong>
                <div class="row-desc"><p><?php echo $decription; ?></p></div>
                <div class="a-row-content">
                    <?php call_user_func($callback); ?>
                </div>
            </div>
        <?php endif;
    }

    public static function is_sb_page() {
        $page = self::get_current_page();
        if ( SB_PHP::is_string_contain( $page, 'sb' ) || SB_PHP::is_string_contain( $page, 'sbt' ) ) {
            return true;
        }
        return false;
    }

    public static function is_about_page() {
        $page = self::get_current_page();
        if ( 'sb_options' == $page ) {
            return true;
        }
        return false;
    }

    public static function get_default_rss_feed_number() {
        return SB_RSS_FEED_NUMBER;
    }

    public static function set_current_rss_feed_item( $args ) {
        global $sb_current_rss_feed;
        $sb_current_rss_feed = $args;
    }

    public static function get_current_rss_feed_item() {
        global $sb_current_rss_feed;
        if ( ! is_array( $sb_current_rss_feed ) ) {
            $sb_current_rss_feed = array();
        }
        return $sb_current_rss_feed;
    }

    public static function add_section( $section_id, $section_title, $page_slug ) {
        add_settings_section( $section_id, $section_title, array( 'SB_Admin_Custom', 'section_description_callback' ), $page_slug );
    }

    public static function section_description_callback($args) {
        if ( 'sb_options_section' == $args['id']) {
            _e( 'Mô tả ngắn gọn về mã nguồn SB.', 'sb-theme' );
        } else {
            _e( 'Cập nhật thông tin cài đặt của bạn ở bên dưới:', 'sb-theme' );
        }
    }

    public static function add_setting_field( $field_id, $field_title, $section_id, $callback, $page_slug ) {
        add_settings_field( $field_id, $field_title, $callback, $page_slug, $section_id );
    }

    public static function setting_page_callback() {
        sb_core_get_content( 'sb-admin-setting-page' );
    }

    public static function row_setting_page_callback() {
        sb_theme_get_content('sb-admin-row-setting-page');
    }

    public static function store_row_setting_page_callback() {
        SB_Theme::get_content('sb-admin-store-row-setting-page');
    }

    public static function checkout_setting_page_callback() {
        sb_theme_get_content('sb-admin-setting-checkout-page');
    }

	public static function vchat_setting_page_callback() {
		SB_Theme::get_content('sb-admin-setting-vchat-page');
	}

    public static function setting_page_before() {
        ?>
        <div class="wrap sb-theme sb-option sb-options" data-message-confirm="<?php echo SB_Message::get_confirm_text(); ?>" data-option-changed="0">
        <noscript><div class="no-js"><?php echo SB_Message::get_browser_not_support_javascript(); ?></div></noscript>
        <?php
    }

    public static function setting_page_after() {
        echo '</div>';
    }

    public static function table_setting_page( $page, $table = true ) {
        global $wp_settings_sections, $wp_settings_fields;
        if ( ! isset( $wp_settings_sections[$page] ) ) {
            return;
        }
        $count = 0;
        foreach ( (array) $wp_settings_sections[$page] as $section ) {
            $section_id = $section['id'];
            $class = 'sbtheme-option-section';
            $class = SB_PHP::add_string_with_space_before($class, str_replace('_', '-', $section_id));
            echo '<div id="' . $section_id . '" class="' . $class . '">';
            if ( $section['title'] ) {
                echo "<h3 class=\"setting-title\">{$section['title']}</h3>\n";
            }
            if ( $section['callback'] ) {
                call_user_func( $section['callback'], $section );
            }
            $has_field = true;
            if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section_id] ) ) {
                $has_field = false;
            }
            if($table) {
                echo '<table class="form-table">';
            }
            if($has_field && 'sb_options_section' != $section_id) {
                do_settings_fields( $page, $section_id );
            } elseif('sb_options_section' == $section_id) {
                sb_core_get_content('sb-admin-about');
            }
            if($table) {
                echo '</table>';
            }
            echo '</div>';
            $count++;
        }
    }

    public static function form_before($class = '') {
        $class = SB_PHP::add_string_with_space_before($class, 'sb-theme-setting-form');
        echo '<form class="' . $class . '" method="post" action="' . esc_url(admin_url('options.php')) . '">';
        settings_fields( 'sb-setting' );
    }

    public static function form_after() {
        submit_button(SB_Message::get_save_changes());
        echo '</form>';
    }

    public static function row_setting_page( $page ) {
        self::table_setting_page($page, false);
    }

    public static function build_row_setting_tab($base_id, $tabs, $container_class = '') {
        $result = '';
        $count = 0;
        $tabs_html = new SB_HTML('h2');
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'nav-tab-wrapper');
        $tabs_html->set_attribute('class', $container_class);
        $tab_items = '';
        foreach($tabs as $key => $tab) {
            $tab_label = isset($tab['name']) ? $tab['name'] : '';
            if(empty($tab_label)) {
                continue;
            }
            $tab_item = new SB_HTML('a');
            $atts = array(
                'href' => 'javascript:;',
                'id' => 'sbt_' . $base_id . '_' . $key . '_tab',
                'text' => $tab_label,
                'data-tab' => $key
            );
            $tab_item_class = 'nav-tab ' . $key;
            if($count == 0) {
                $tab_item_class = SB_PHP::add_string_with_space_before($tab_item_class, 'nav-tab-active');
            }
            $atts['class'] = $tab_item_class;
            $tab_item->set_attribute_array($atts);
            $tab_items .= $tab_item->build();
            $count++;
        }
        if(!empty($tab_items)) {
            $tabs_html->set_text($tab_items);
        }
        $result = $tabs_html->build();
        return $result;
    }

    public static function do_row_setting_content($base_name, $tabs) {
        $count = 0;
        foreach($tabs as $key => $tab) :
            $content_class = 'tab-content-item ' . $key;
            if(0 == $count) {
                $content_class .= ' active';
            }
            ?>
            <div class="<?php echo $content_class; ?>">
                <div class="tab-content-inner">
                    <?php do_action('sb_theme_' . $base_name . '_setting_' . $key . '_field'); ?>
                </div>
            </div>
            <?php $count++;
        endforeach;
    }

    public static function row_setting_page_content($args = array()) {
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sbt-row-setting');
        $tab_html = isset($args['tab_html']) ? $args['tab_html'] : '';
        $callback = isset($args['callback']) ? $args['callback'] : '';
        if(!SB_PHP::callback_exists($callback) || empty($tab_html)) {
            return;
        }
	    $title = isset($args['title']) ? $args['title'] : '';
	    if(!empty($title)) {
		    echo '<h2>' . $title . '</h2>';
	    }
        ?>
        <div class="<?php echo $container_class; ?>">
            <?php echo $tab_html; ?>
            <div class="row-setting-content">
                <?php call_user_func($callback); ?>
            </div>
        </div>
        <?php
    }
}