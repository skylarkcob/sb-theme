<?php
class SB_Custom_Menu {

    public function __construct() {
        add_filter('wp_setup_nav_menu_item', array($this, 'add_menu_custom_fields'));
        add_action('wp_update_nav_menu_item', array($this, 'save_menu_custom_fields'), 10, 3);
        add_filter('wp_edit_nav_menu_walker', array($this, 'edit_menu_walker'), 10, 2);
    }

    public function add_menu_custom_fields($menu_item) {
        $menu_item = apply_filters('sb_theme_add_menu_item_field', $menu_item);
        return $menu_item;
    }

    public function save_menu_custom_fields($menu_id, $menu_item_db_id, $args) {
        do_action('sb_theme_save_menu_item_field', $menu_id, $menu_item_db_id, $args);
    }

    function edit_menu_walker($walker, $menu_id) {
        $walker = apply_filters('sb_theme_menu_custom_walker', 'SB_Menu_Edit_Walker', $menu_id);
        return $walker;
    }

}