<?php
defined('ABSPATH') or die('Please do not pip me!');

SB_Admin_Custom::setting_page_before();
?>
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
                <span><?php _e('Phiên bản:', 'sb-theme'); ?> <?php echo $version; ?></span>
            <?php endif; ?>
        </div>
        <div class="sbtheme-content">
            <div class="sidebar">
                <?php
                $current_tab = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
                $transient_name = SB_Cache::build_admin_sidebar_tab_transient_name();
                $sidebar_html = get_transient($transient_name);
                if(false === $sidebar_html || $current_tab != $sidebar_html['current_tab']) {
                    $sidebar_tabs = new SB_HTML('ul');
                    $sidebar_tabs->set_attribute('class', 'sbtheme-list-section sb-tabs');

                    $count = 0;
                    $tabs = apply_filters('sb_admin_tabs', array());

                    $tabs = apply_filters('sb_theme_admin_tabs', $tabs);
                    $key = 'sb_options';
                    $about_tab = isset($tabs[$key]) ? $tabs[$key] : '';

                    $tab_items = '';
                    if($about_tab) {
                        unset($tabs[$key]);
                        $class = 'tab-item section-item tab-' . $key;
                        $new_class_key = str_replace('_', '-', $key);
                        $class = SB_PHP::add_string_with_space_before($class, $new_class_key);
                        if($key == $current_tab) {
                            $class .= ' active';
                        }
                        $type = isset($value['type']) ? $value['type'] : 'normal';
                        $class .= ' ' . $type;
                        $value = $about_tab;
                        $tab_item = new SB_HTML('li');
                        $tab_item->set_attribute('class', $class);

                        $tab_link = new SB_HTML('a');
                        $tab_link->set_attribute('class', 'sbtheme-group-tab');
                        $tab_link->set_attribute('href', admin_url('admin.php?page=' . $key));
                        $tab_link->set_attribute('data-section', $value['section_id']);
                        $tab_link->set_text('<i class="tab-icon ' . $key . '"></i> <span class="group-title">' . $value['title'] . '</span>');
                        $tab_item->set_text($tab_link->build());
                        $tab_items .= $tab_item->build();
                    }
                    foreach($tabs as $key => $value) {
                        $class = 'tab-item section-item tab-' . $key;
                        if($key == $current_tab) {
                            $class .= ' active';
                        }
                        $type = isset($value['type']) ? $value['type'] : 'normal';
                        $class .= ' ' . $type;
                        $tab_item = new SB_HTML('li');
                        $tab_item->set_attribute('class', $class);

                        $tab_link = new SB_HTML('a');
                        $tab_link->set_attribute('class', 'sbtheme-group-tab');
                        $tab_link->set_attribute('href', admin_url('admin.php?page=' . $key));
                        $tab_link->set_attribute('data-section', $value['section_id']);
                        $tab_link->set_text('<i class="tab-icon ' . $key . '"></i> <span class="group-title">' . $value['title'] . '</span>');
                        $tab_item->set_text($tab_link->build());
                        $tab_items .= $tab_item->build();
                        $count++;
                    }
                    $sidebar_tabs->set_text($tab_items);
                    $sidebar_html = array(
                        'html' => $sidebar_tabs->build(),
                        'current_tab' => $current_tab
                    );
                    set_transient($transient_name, $sidebar_html, DAY_IN_SECONDS);
                }
                echo $sidebar_html['html'];
                $autocomplete = '';
                if('sbt_smtp_email' == $current_tab) {
                    $autocomplete = 'off';
                }
                ?>
            </div>
            <div class="main">
                <form id="sb-options-form" method="post" action="options.php" data-page="<?php echo $current_tab; ?>" autocomplete="<?php echo $autocomplete; ?>">
                    <?php
                    settings_fields( 'sb-setting' );
                    SB_Admin_Custom::table_setting_page( $current_tab );
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
<?php SB_Admin_Custom::setting_page_after(); ?>