<?php
function sb_do_settings_sections( $page ) {
    global $wp_settings_sections, $wp_settings_fields;
    if ( ! isset( $wp_settings_sections[$page] ) ) {
        return;
    }
    $count = 0;
    foreach ( (array) $wp_settings_sections[$page] as $section ) {
        $section_id = $section['id'];
        $class = 'sbtheme-option-section';
        echo '<div id="'.$section_id.'" class="'.$class.'">';
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
        echo '<table class="form-table">';
        if($has_field && 'sb_options_section' != $section_id) {
            do_settings_fields( $page, $section_id );
        } elseif('sb_options_section' == $section_id) {
            sb_core_get_content('sb-admin-about');
        }
        echo '</table>';
        echo '</div>';
        $count++;
    }
}
?>
<div class="wrap sb-option sb-options" data-message-confirm="<?php echo SB_Message::get_confirm_text(); ?>">
    <noscript><div class="no-js"><?php echo SB_Message::get_browser_not_support_javascript(); ?></div></noscript>
    <h2 style="display: none">&nbsp;</h2>
    <?php if (isset($_REQUEST['submit']) || isset($_REQUEST['settings-updated'])) : ?>
        <div id="message" class="updated">
            <p><strong><?php echo SB_Message::get_changes_saved_successfully(); ?></strong></p>
        </div>
    <?php endif; ?>
    <div class="sbtheme-container">
        <div class="sbtheme-header">
            <?php
            $theme = wp_get_theme();
            $name = $theme->get('Name');
            if(empty($name)) {
                $name = SB_Message::get_theme_no_name();
            }
            ?>
            <h2><?php echo $name; ?></h2>
            <?php
            $version = $theme->get('Version');
            $version = str_replace('-wpcom', '', $version);
            if(!empty($version)) : ?>
                <span><?php _e('Version:', 'sb-theme'); ?> <?php echo $version; ?></span>
            <?php endif; ?>
        </div>
        <div class="sbtheme-content">
            <div class="sidebar">
                <ul class="sbtheme-list-section sb-tabs">
                    <?php
                    $count = 0;
                    $tabs = apply_filters('sb_admin_tabs', array());
                    $current_tab = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
                    $tabs = apply_filters('sb_theme_admin_tabs', $tabs);
                    $key = 'sb_options';
                    $about_tab = isset($tabs[$key]) ? $tabs[$key] : '';
                    if($about_tab) {
                        unset($tabs[$key]);
                        $class = 'tab-item section-item tab-'.$key; if($key == $current_tab) $class .= ' active';
                        $type = isset($value['type']) ? $value['type'] : 'normal';
                        $class .= ' ' . $type;
                        $value = $about_tab;
                        ?>
                        <li class="<?php echo $class; ?>">
                            <a class="sbtheme-group-tab" href="<?php echo admin_url('admin.php?page=' . $key); ?>" data-section="<?php echo $value['section_id']; ?>"><i class="tab-icon <?php echo $key; ?>"></i> <span class="group-title"><?php _e($value['title'], 'sb-theme'); ?></span></a>
                        </li>
                        <?php
                    }
                    foreach($tabs as $key => $value) :
	                    $class = 'tab-item section-item tab-' . $key;
	                    if($key == $current_tab) {
		                    $class .= ' active';
	                    }
                        $type = isset($value['type']) ? $value['type'] : 'normal';
                        $class .= ' ' . $type;
                        ?>
                        <li class="<?php echo $class; ?>">
                            <a class="sbtheme-group-tab" href="<?php echo admin_url('admin.php?page='.$key); ?>" data-section="<?php echo $value['section_id']; ?>"><i class="tab-icon <?php echo $key; ?>"></i> <span class="group-title"><?php _e($value['title'], 'sb-theme'); ?></span></a>
                        </li>
                        <?php
	                    $count++;
                    endforeach; ?>
                </ul>
            </div>
            <div class="main">
                <form id="sb-options-form" method="post" action="options.php" data-page="<?php echo $current_tab; ?>">
                    <?php
                    settings_fields( 'sb-setting' );
                    sb_do_settings_sections( $current_tab );
                    if(!SB_Admin_Custom::is_about_page()) :
	                    submit_button(SB_Message::get_save_changes()); ?>
	                    <div class="top-save-button"><?php submit_button(SB_Message::get_save_changes()); ?></div>
	                    <div class="sb-button reset-button">
		                    <span class="button"><?php echo SB_Message::get_reset(); ?></span>
	                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <div class="sbtheme-footer sbteam-footer">
            <div class="left">
	            <p><?php printf(SB_Message::get_created_by(), SB_THEME_AUTHOR); ?> <?php printf(SB_Message::get_ask_question_via_email(), ' <em>' . SB_THEME_EMAIL . '</em>'); ?></p>
            </div>
            <div class="right">
                <ul class="sb-social-list">
                    <li class="github">
	                    <a target="_blank" href="<?php echo SB_THEME_GITHUB; ?>"></a>
                    </li>
                    <li class="facebook">
	                    <a target="_blank" href="<?php echo SB_THEME_FACEBOOK_PAGE; ?>"></a>
                    </li>
                    <li class="twitter">
	                    <a target="_blank" href="<?php echo SB_THEME_TWITTER; ?>"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sbtheme-copyright sbteam-copyright">
        <p>&copy; 2008 - <?php echo date('Y'); ?> <a href="<?php echo SB_THEME_WEBSITE; ?>"><?php echo SB_THEME_AUTHOR; ?></a>. <?php echo SB_Message::get_all_rights_reserved(); ?>.</p>
    </div>
</div>