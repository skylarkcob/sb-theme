<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Membership {
    public static function is_paid_membership_enabled() {
        $result = (bool)SB_Option::get_advanced_membership_setting('use_paid_membership');
        return apply_filters('sb_theme_paid_membership_enabled', $result);
    }

    public static function create_user_group($role, $display_name, $capabilities = array()) {
        $capabilities['read'] = true;
        add_role($role, $display_name, $capabilities);
    }

    public static function build_read_only_capabilities() {
        return self::get_capabilities_by_role('subscriber');
    }

    public static function use_sb_login_page() {
        $result = sb_login_page_use_sb_login();
        return $result;
    }

    public static function get_capabilities_by_role($role_name, $check_default = true) {
        $caps = array();
        if($check_default) {
            $options = SB_Option::get();
            $wp_defaults = isset($options['wp_default']) ? $options['wp_default'] : '';
            $roles = isset($wp_defaults['roles']) ? $wp_defaults['roles'] : '';
            if(is_array($roles)) {
                $role = isset($roles[$role_name]) ? $roles[$role_name] : '';
                $caps = isset($role['capabilities']) ? $role['capabilities'] : '';
            }
        }
        if(empty($caps)) {
            $role = self::get_role($role_name);
            if(is_object($role)) {
                $caps = $role->capabilities;
            }
        }
        return $caps;
    }

    public static function get_paid_membership_free_post_number() {
        $key = 'paid_free_post_number';
        $value = SB_Option::get_advanced_membership_setting($key);
        $value = absint($value);
        $value = apply_filters('sb_theme_paid_membership_free_post_number', $value);
        return $value;
    }

    public static function get_limit_publish_post_roles() {
        $key = 'limit_publish_post_role';
        $value = SB_Option::get_advanced_setting('writing', $key);
        $value = SB_PHP::string_to_array(',', $value);
        $value = apply_filters('sb_theme_limit_publish_post_roles', $value);
        return $value;
    }

    public static function get_limit_delete_post_roles() {
        $key = 'limit_delete_post_role';
        $value = SB_Option::get_advanced_setting('writing', $key);
        $value = SB_PHP::string_to_array(',', $value);
        $value = apply_filters('sb_theme_limit_delete_post_roles', $value);
        return $value;
    }

    public static function get_limit_delete_published_post_roles() {
        $key = 'limit_delete_published_post_role';
        $value = SB_Option::get_advanced_setting('writing', $key);
        $value = SB_PHP::string_to_array(',', $value);
        $value = apply_filters('sb_theme_limit_delete_published_post_roles', $value);
        return $value;
    }

    public static function get_limit_edit_published_post_roles() {
        $key = 'limit_edit_published_post_role';
        $value = SB_Option::get_advanced_setting('writing', $key);
        $value = SB_PHP::string_to_array(',', $value);
        $value = apply_filters('sb_theme_limit_edit_published_post_roles', $value);
        return $value;
    }
    
    public static function get_role($role_id) {
        global $wp_roles;
        if(!isset($wp_roles)) {
            $wp_roles = new WP_Roles();
        }
        return $wp_roles->get_role($role_id);
    }

    public static function get_role_label($role_name) {
        global $wp_roles;
        $role = $wp_roles->roles[$role_name]['name'];
        return $role;
    }

    public static function get_roles() {
        global $wp_roles, $sb_theme_roles;
        if(!is_array($sb_theme_roles)) {
            $sb_theme_roles = $wp_roles->get_names();
        }
        return $sb_theme_roles;
    }

    public static function remove_all_role() {
        remove_role('super_admin');
        remove_role('banned');
        remove_role('gold');
        $paid_groups = self::get_paid_membership_groups();
        foreach($paid_groups as $key => $data) {
            remove_role($key);
        }
    }

    public static function init_roles_and_capabilities() {
        self::regenerate_roles();
        self::update_all_role();
    }

    public static function regenerate_roles($force = false) {
        if($force || self::need_to_regenerate_role()) {
            self::remove_all_role();
            if(self::is_paid_membership_enabled()) {
                self::add_default_paid_member_group();
            }
            self::add_default_roles();
        }
    }

    public static function get_default_roles() {
        $options = SB_Option::get();
        if(isset($options['wp_default']['roles'])) {
            return $options['wp_default']['roles'];
        }
        return null;
    }

    /*
     * Cập nhật quyền hạn cho các nhóm thành viên
     */
    public static function update_limit_post_roles() {
        $role_ids = self::get_paid_role_ids();

        // delete_posts
        $prevent_roles = self::get_limit_delete_post_roles();
        if(is_array($prevent_roles) && count($prevent_roles) > 0) {
            self::prevent_delete_posts($prevent_roles);
            $dif_role_ids = $role_ids;
            $dif_role_ids[] = 'contributor';
            $dif_role_ids[] = 'author';
            $dif_role_ids[] = 'editor';
            $dif_role_ids = SB_PHP::subtract_array($dif_role_ids, $prevent_roles);
            self::allow_delete_posts($dif_role_ids);
        } else {
            $dif_role_ids = $role_ids;
            $dif_role_ids[] = 'contributor';
            $dif_role_ids[] = 'author';
            $dif_role_ids[] = 'editor';
            self::allow_delete_posts($dif_role_ids);
        }

        // delete_published_posts
        $prevent_roles = self::get_limit_delete_published_post_roles();
        if(is_array($prevent_roles) && count($prevent_roles) > 0) {
            self::prevent_delete_published_posts($prevent_roles);
            $dif_role_ids = $role_ids;
            $dif_role_ids[] = 'author';
            $dif_role_ids[] = 'editor';
            $dif_role_ids = SB_PHP::subtract_array($dif_role_ids, $prevent_roles);
            self::allow_delete_published_posts($dif_role_ids);
        } else {
            $dif_role_ids = $role_ids;
            $dif_role_ids[] = 'author';
            $dif_role_ids[] = 'editor';
            self::allow_delete_published_posts($dif_role_ids);
        }

        // edit_published_posts
        $prevent_roles = self::get_limit_edit_published_post_roles();
        if(is_array($prevent_roles) && count($prevent_roles) > 0) {
            self::prevent_edit_published_posts($prevent_roles);
            $dif_role_ids = $role_ids;
            $dif_role_ids[] = 'author';
            $dif_role_ids[] = 'editor';
            $dif_role_ids = SB_PHP::subtract_array($dif_role_ids, $prevent_roles);
            self::allow_edit_published_posts($dif_role_ids);
        } else {
            $dif_role_ids = $role_ids;
            $dif_role_ids[] = 'author';
            $dif_role_ids[] = 'editor';
            self::allow_edit_published_posts($dif_role_ids);
        }

        // publish_posts
        $prevent_roles = self::get_limit_publish_post_roles();
        if(is_array($prevent_roles) && count($prevent_roles) > 0) {
            self::prevent_publish_posts($prevent_roles);
            $dif_role_ids = $role_ids;
            $dif_role_ids[] = 'author';
            $dif_role_ids[] = 'editor';
            $dif_role_ids = SB_PHP::subtract_array($dif_role_ids, $prevent_roles);
            self::allow_publish_posts($dif_role_ids);
        } else {
            $dif_role_ids = $role_ids;
            $dif_role_ids[] = 'author';
            $dif_role_ids[] = 'editor';
            self::allow_publish_posts($dif_role_ids);
        }
    }

    public static function has_super_admin() {
        $admin_ids = self::get_super_admin_ids();
        if(count($admin_ids) > 0) {
            return true;
        }
        return false;
    }

    public static function is_super_admin($user_id) {
        $admin_ids = self::get_super_admin_ids();
        if(in_array($user_id, $admin_ids) && SB_User::is_admin($user_id)) {
            return true;
        }
        return false;
    }

    public static function need_to_regenerate_role() {
        return apply_filters('sb_theme_need_regenerate_role', false);
    }

    public static function get_super_admin_ids() {
        $result = array();
        $ids = SB_User::get_super_admin_define_ids_array();
        foreach($ids as $id) {
            $result[] = absint($id);
        }
        return $result;
    }

    public static function update_all_role() {

        $editor = get_role('editor');
        $caps = array(
            'moderate_comments',
            'manage_categories',
            'manage_links',
            'edit_others_posts',
            'edit_others_pages',
            'delete_posts',
            'read_private_posts'
        );
        foreach($caps as $cap) {
            $editor->remove_cap($cap);
        }

        $author = get_role('author');

        $super_admin = self::get_role('super_administrator');
        if($super_admin && is_object($super_admin)) {
            $super_admin->add_cap('delete_site');
        }

        if(self::is_paid_membership_enabled()) {
            $paid_groups = self::get_paid_membership_groups();
            $can_create_posts = self::can_create_post_roles();
            $author_caps = $author->capabilities;

            foreach($paid_groups as $key => $data) {
                if(in_array($key, $can_create_posts)) {
                    $paid_role = self::get_role($key);
                    if($paid_role && is_object($paid_role)) {
                        foreach($author_caps as $key => $cap) {
                            $paid_role->add_cap($key);
                        }
                    }
                }
            }
        }
        self::update_limit_post_roles();
    }

    public static function get_free_post_number() {
        return self::get_paid_membership_free_post_number();
    }

    public static function get_coin_rate() {
        $key = 'coin_rate';
        $value = SB_Option::get_advanced_membership_setting($key);
        $value = absint($value);
        if($value < 1) {
            $value = 1;
        }
        return $value;
    }

    public static function get_minimum_coin_add_fund($coin_rate = '') {
        $key = 'minimum_coin_add_fund';
        $value = SB_Option::get_advanced_membership_setting($key);
        $value = absint($value);
        if(!is_numeric($coin_rate) || $coin_rate < 1) {
            $coin_rate = self::get_coin_rate();
        }
        if($value < ($coin_rate * 2)) {
            $value = $coin_rate * 2;
        }
        return $value;
    }

    public static function get_coin_price_base($coin_rate = '') {
        $coin_base = apply_filters('sb_theme_coin_price_base', SB_THEME_COIN_PRICE_BASE);
        if(!is_numeric($coin_rate) || $coin_rate < 1) {
            $coin_rate = self::get_coin_rate();
        }
        return $coin_base / $coin_rate;
    }

    public static function get_coin_price($quantity, $coin_rate = '') {
        return $quantity * self::get_coin_price_base($coin_rate);
    }

    public static function get_minimum_coin_can_post() {
        $key = 'minimum_coin_can_post';
        $value = SB_Option::get_advanced_membership_setting($key);
        $value = absint($value);
        $post_coin_cost = self::get_post_coin_cost();
        if($value < $post_coin_cost) {
            $value = $post_coin_cost;
        }
        return apply_filters('sb_theme_paid_membership_minimum_coin_can_post', $value);
    }

    public static function can_user_write_paid_post($user_id) {
        $minimum_coin_can_post = self::get_minimum_coin_can_post();
        $pass_free_post = (SB_User::count_all_post($user_id) >= self::get_free_post_number()) ? true : false;
        $user_coin = SB_User::get_coin($user_id);
        $post_coin_cost = self::get_post_coin_cost();
        if($pass_free_post && $user_coin < $minimum_coin_can_post && $user_coin < $post_coin_cost) {
            return false;
        }
        return true;
    }

    public static function get_add_coin_url() {
        $key = 'add_coin_page';
        $value = SB_Option::get_advanced_membership_setting($key);
        $value = absint($value);
        $url = '';
        if($value > 0) {
            $page = get_post($value);
            if(!SB_Core::is_error($page)) {
                $url = get_permalink($page);
            }
        }
        $url = apply_filters('sb_theme_add_coin_url', $url);
        return $url;
    }

    public static function get_post_coin_cost() {
        $key = 'post_cost_coin';
        $value = SB_Option::get_advanced_membership_setting($key);
        $value = absint($value);
        $post_cost_coin = $value;
        if($post_cost_coin < 1) {
            $post_cost_coin = 1;
        }
        return $post_cost_coin;
    }

    public static function prevent_delete_published_posts($roles = array()) {
        self::prevent($roles, 'delete_published_posts');
    }

    public static function allow_delete_published_posts($roles = array()) {
        self::allow($roles, 'delete_published_posts');
    }

    public static function prevent_delete_posts($roles = array()) {
        self::prevent($roles, 'delete_posts');
    }

    public static function allow_delete_posts($roles = array()) {
        self::allow($roles, 'delete_posts');
    }

    public static function prevent_cap($role, $cap) {
        $role = self::get_role($role);
        if(is_object($role) && !is_wp_error($role)) {
            $role->remove_cap($cap);
        }
    }
    
    public static function allow_cap($role, $cap) {
        $role = self::get_role($role);
        if(is_object($role) && !is_wp_error($role)) {
            $role->add_cap($cap);
        }
    }

    public static function prevent($roles = array(), $cap) {
        foreach($roles as $role_id) {
            self::prevent_cap($role_id, $cap);
        }
    }

    public static function allow($roles = array(), $cap) {
        foreach($roles as $role_id) {
            self::allow_cap($role_id, $cap);
        }
    }
    
    public static function prevent_edit_published_posts($roles = array()) {
        self::prevent($roles, 'edit_published_posts');
    }

    public static function allow_edit_published_posts($roles = array()) {
        self::allow($roles, 'edit_published_posts');
    }
    
    public static function prevent_publish_posts($roles = array()) {
        self::prevent($roles, 'publish_posts');
    }

    public static function allow_publish_posts($roles = array()) {
        self::allow($roles, 'publish_posts');
    }

    public static function add_default_roles() {
        $admin_role = self::get_role('administrator');
        $caps = $admin_role->capabilities;
        add_role('super_administrator', __('Super Administrator', 'sb-theme'), $caps);
        add_role('banned', __('Banned', 'sb-theme'));
    }

    public static function get_paid_role_ids() {
        $roles = array(
            'standard',
            'golden',
            'emerald',
            'sapphire',
            'platinum',
            'diamond',
            'ultimate'
        );
        $roles = apply_filters('sb_theme_paid_role_ids', $roles);
        return $roles;
    }

    public static function can_create_post_roles() {
        $roles = self::get_paid_role_ids();
        $new_roles = apply_filters('sb_theme_can_create_post_roles', array());
        $roles = wp_parse_args($roles, $new_roles);
        return apply_filters('sb_theme_can_create_post_roles', $roles);
    }

    public static function get_paid_membership_groups() {
        $capabilities = self::get_capabilities_by_role('author');
        $default_groups = array(
            'standard' => array(
                'name' => __('Standard', 'sb-theme'),
                'capabilities' => $capabilities
            ),
            'golden' => array(
                'name' => __('Golden', 'sb-theme'),
                'capabilities' => $capabilities
            ),
            'emerald' => array(
                'name' => __('Emerald', 'sb-theme'),
                'capabilities' => $capabilities
            ),
            'sapphire' => array(
                'name' => __('Sapphire', 'sb-theme'),
                'capabilities' => $capabilities
            ),
            'platinum' => array(
                'name' => __('Platinum', 'sb-theme'),
                'capabilities' => $capabilities
            ),
            'diamond' => array(
                'name' => __('Diamond', 'sb-theme'),
                'capabilities' => $capabilities
            ),
            'ultimate' => array(
                'name' => __('Ultimate', 'sb-theme'),
                'capabilities' => $capabilities
            )
        );
        $new_groups = apply_filters('sb_theme_paid_member_groups', array());
        $member_groups = wp_parse_args($default_groups, $new_groups);
        return $member_groups;
    }

    public static function add_default_paid_member_group() {
        $member_groups = self::get_paid_membership_groups();
        foreach($member_groups as $role => $data) {
            self::create_user_group($role, $data['name'], $data['capabilities']);
        }
    }
}