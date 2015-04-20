<div class="sbt-adv-setting sbt-row-setting">
    <?php
    global $sb_admin;
    $adv_tabs = $sb_admin->get_advanced_setting_tabs();
    $transient_name = SB_Cache::build_admin_advanced_setting_tab_transient_name();
    if(false === ($tabs_html = get_transient($transient_name))) {
        $count = 0;
        $tabs = new SB_HTML('h2');
        $tabs->set_attribute('class', 'nav-tab-wrapper sbt-adv-tabs');
        $tab_items = '';
        foreach($adv_tabs as $key => $tab) {
            $tab_item = new SB_HTML('a');
            $atts = array(
                'href' => 'javascript:;',
                'id' => 'sbt_advs_' . $key . '_tab',
                'text' => $tab['name'],
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
        $tabs->set_text($tab_items);
        $tabs_html = $tabs->build();
        set_transient($transient_name, $tabs_html, 4 * WEEK_IN_SECONDS);
    }
    echo $tabs_html;
    ?>
    <div class="sbt-adv-setting-content row-setting-content">
        <?php $count = 0; ?>
        <?php foreach($adv_tabs as $key => $tab) : ?>
            <?php
            $content_class = 'tab-content-item ' . $key;
            if(0 == $count) {
                $content_class .= ' active';
            }
            ?>
            <div class="<?php echo $content_class; ?>">
                <div class="tab-content-inner">
                    <?php do_action('sb_theme_advanced_setting_' . $key . '_field'); ?>
                </div>
            </div>
            <?php $count++; ?>
        <?php endforeach; ?>
    </div>
</div>