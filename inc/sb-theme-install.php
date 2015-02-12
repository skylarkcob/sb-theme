<?php
function sb_theme_check_core() {
    return defined('SB_CORE_VERSION');
}

function sb_theme_get_default_theme() {
    $themes = wp_get_themes();
    $wp_theme = '';
    foreach($themes as $theme) {
        $author_uri = $theme->get('AuthorURI');
        if(strrpos($author_uri, 'wordpress.org') !== false) {
            $wp_theme = $theme;
            break;
        }
    }
    if(empty($wp_theme)) {
        foreach($themes as $theme) {
            $text_domain = $theme->get('TextDomain');
            if(strrpos($text_domain, 'sb-theme') === false) {
                $wp_theme = $theme;
                break;
            }
        }
    }
    return $wp_theme;
}

function sb_theme_switch_to_default_theme() {
    $theme = sb_theme_get_default_theme();
    if(!empty($theme)) {
        switch_theme($theme->get('TextDomain'));
    }
}

function sb_theme_check_admin_notices() {
    if(!defined('SB_CORE_VERSION') && defined('SB_THEME_VERSION')) {
        unset($_GET['activated']);
        $my_theme = wp_get_theme();
        $theme_name = $my_theme->get('Name');
        printf('<div class="error"><p><strong>' . __('Error', 'sb-theme') . ':</strong> ' . __('The theme with name %1$s will be deactivated because of missing %2$s plugin', 'sb-theme') . '.</p></div>', '<strong>' . $theme_name . '</strong>', sprintf('<a target="_blank" href="%s" style="text-decoration: none">SB Core</a>', 'https://wordpress.org/plugins/sb-core/'));
        sb_theme_switch_to_default_theme();
    } elseif(!class_exists('SB_Theme') && defined('SB_THEME_VERSION')) {
        printf('<div class="error"><p><strong>' . __('Error', 'sb-theme') . ':</strong> ' . __('It looks like you\'re using incorrect %1$s pack! Click here to %2$s.', 'sb-theme') . '</p></div>', '<strong>SB Theme Core</strong>', sprintf('<a target="_blank" href="%1$s" style="text-decoration: none">%2$s</a>', 'https://github.com/skylarkcob/sb-theme/', __('re-download', 'sb-theme')));
    }
}
if(!empty($GLOBALS['pagenow']) && 'themes.php' === $GLOBALS['pagenow']) {
    add_action('admin_notices', 'sb_theme_check_admin_notices', 0);
}