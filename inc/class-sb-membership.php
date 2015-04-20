<?php
class SB_Membership {
    public static function is_paid_membership_enabled() {
        return apply_filters('sb_theme_paid_membership_enabled', false);
    }

    public static function create_user_group($role, $display_name, $capabilities = array()) {
        $capabilities['read'] = true;
        add_role($role, $display_name, $capabilities);
    }

    public static function build_read_only_capabilities() {
        $subscriber_role = self::get_role('subscriber');
        return $subscriber_role->capabilities;
    }

    public static function get_role($role_id) {
        global $wp_roles;
        if(!isset($wp_roles)) {
            $wp_roles = new WP_Roles();
        }
        return $wp_roles->get_role($role_id);
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
        if(defined('SB_THEME_SUPER_ADMIN')) {
            $ids = SB_THEME_SUPER_ADMIN;
            $ids = SB_PHP::string_to_array(',', $ids);
            foreach($ids as $id) {
                $result[] = absint($id);
            }
        }
        return $result;
    }

    public static function init_roles_and_capabilities() {
        if(self::need_to_regenerate_role()) {
            self::remove_all_role();
            if(self::is_paid_membership_enabled()) {
                self::add_default_paid_member_group();
            }
            self::add_default_roles();
        }
        self::update_all_role();
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
    }

    public static function get_free_post_number() {
        return apply_filters('sb_theme_free_post_number', 1);
    }

    public static function prevent_delete_published_posts($roles = array()) {
        self::prevent($roles, 'delete_published_posts');
    }

    public static function prevent_delete_posts($roles = array()) {
        self::prevent($roles, 'delete_posts');
    }

    public static function prevent_cap($role, $cap) {
        $role = self::get_role($role);
        $role->remove_cap($cap);
    }

    public static function prevent($roles = array(), $cap) {
        foreach($roles as $role_id) {
            self::prevent_cap($role_id, $cap);
        }
    }

    public static function prevent_edit_published_posts($roles = array()) {
        self::prevent($roles, 'edit_published_posts');
    }

    public static function prevent_publish_posts($roles = array()) {
        self::prevent($roles, 'publish_posts');
    }

    public static function add_default_roles() {
        $admin_role = self::get_role('administrator');
        $caps = $admin_role->capabilities;
        add_role('super_administrator', __('Super Administrator', 'sb-theme'), $caps);
        add_role('banned', __('Banned', 'sb-theme'));
    }

    public static function can_create_post_roles() {
        $roles = array(
            'golden',
            'emerald',
            'sapphire',
            'platinum',
            'diamond',
            'ultimate'
        );
        $new_roles = apply_filters('sb_theme_can_create_post_roles', array());
        $roles = wp_parse_args($roles, $new_roles);
        return apply_filters('sb_theme_can_create_post_roles', $roles);
    }

    public static function get_paid_membership_groups() {
        $capabilities = self::build_read_only_capabilities();
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