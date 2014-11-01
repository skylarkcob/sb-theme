<?php
function sb_theme_footer_text_callback() {
    $options = SB_Option::get();
    $value = isset($options['theme']['footer_text']) ? $options['theme']['footer_text'] : '';
    $id = 'sb_theme_footer_text';
    $name = 'sb_options[theme][footer_text]';
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => __('The text shows on footer area, you can enter address, phone, email,...', 'sb-theme')
    );
    SB_Field::rich_editor_field($args);
}

function sb_theme_setting_field_footer_text() {
    sb_theme_add_setting_field('sb_theme_footer_text', __('Footer text', 'sb-theme'), 'sb_theme_footer_text_callback');
}

function sb_theme_front_page_widget_callback() {
    $options = SB_Option::get();
    $active_cats = isset($options['theme']['front_page_widget']) ? $options['theme']['front_page_widget'] : '';
    $args = array(
        'exclude' => $active_cats
    );
    $cats = SB_Term::get_categories($args);
    ?>
    <div class="sb-sortable-list">
        <div class="sb-sortable-list-container">
            <ul id="sortable-list-cat" class="connectedSortable">
                <?php foreach($cats as $cat) : ?>
                    <li data-category="<?php echo $cat->term_id; ?>" class="ui-state-default"><?php echo $cat->name; ?></li>
                <?php endforeach; ?>
            </ul>
            <ul id="sortable-list-cat-active" class="connectedSortable sb-front-page-widget">
                <?php $cats = $active_cats; $active_cats = explode(',', $active_cats); ?>
                <?php foreach($active_cats as $cat_id) : if($cat_id < 1) continue; $cat = get_category($cat_id); ?>
                    <li data-category="<?php echo $cat->term_id; ?>" class="ui-state-default"><?php echo $cat->name; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <input type="hidden" name="sb_options[theme][front_page_widget]" value="<?php echo $cats; ?>">
    </div>
    <p class="description" style="clear: both"><?php _e('Drag and drop the widget into right box to active it.', 'sb-theme'); ?></p>
    <?php
}

function sb_theme_setting_field_front_page_widget() {
    sb_theme_add_setting_field('sb_theme_front_page_widget', __('Front page widget', 'sb-theme'), 'sb_theme_front_page_widget_callback');
}

function sb_theme_rss_feed_callback() {
    $id = 'sb_theme_rss_feed';
    $name = 'sb_options[theme][rss_feed]';
    $description = __('You can remove or add new feed data.', 'sb-theme');
    $list_feeds = SB_Option::get_theme_rss_feed();
    $order = SB_Option::get_theme_option(array('keys' => array('rss_feed', 'order')));
    if(empty($order)) {
        foreach($list_feeds as $feed) {
            $order .= $feed['id'] . ',';
        }
        $order = trim($order, ',');
    }
    $args = array(
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'list_feeds' => $list_feeds,
        'order' => $order
    );
    SB_Field::rss_feed($args);
}

function sb_theme_setting_field_rss_feed() {
    sb_theme_add_setting_field('sb_theme_rss_feed', __('RSS Feed', 'sb-theme'), 'sb_theme_rss_feed_callback');
}

function sb_theme_scroll_top_callback() {
    $name = 'sb_options[theme][scroll_top]';
    $options = SB_Option::get();
    $value = isset($options['theme']['scroll_top']) ? $options['theme']['scroll_top'] : 1;
    $description = __('You can turn on or turn off the scroll to top button on your site.', 'sb-theme');
    $id = 'sb_theme_scroll_top';
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_theme_setting_field_scroll_top() {
    sb_theme_add_setting_field('sb_theme_scroll_to_top', __('Scroll to top', 'sb-theme'), 'sb_theme_scroll_top_callback');
}

function sb_theme_social_callback() {
    $options = SB_Option::get();
    $args = array();
    $field = array(
        'id' => 'sb_theme_social_facebook',
        'name' => 'sb_options[theme][social][facebook]',
        'value' => isset($options['theme']['social']['facebook']) ? $options['theme']['social']['facebook'] : '',
        'description' => __('The url to your Facebook page.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_twitter',
        'name' => 'sb_options[theme][social][twitter]',
        'value' => isset($options['theme']['social']['twitter']) ? $options['theme']['social']['twitter'] : '',
        'description' => __('The url to your Twitter account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_gplus',
        'name' => 'sb_options[theme][social][gplus]',
        'value' => isset($options['theme']['social']['gplus']) ? $options['theme']['social']['gplus'] : '',
        'description' => __('The url to your Google Plus account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_youtube',
        'name' => 'sb_options[theme][social][youtube]',
        'value' => isset($options['theme']['social']['youtube']) ? $options['theme']['social']['youtube'] : '',
        'description' => __('The url to your YouTube channel.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_linkedin',
        'name' => 'sb_options[theme][social][linkedin]',
        'value' => isset($options['theme']['social']['linkedin']) ? $options['theme']['social']['linkedin'] : '',
        'description' => __('The url to your LinkedIn account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_pinterest',
        'name' => 'sb_options[theme][social][pinterest]',
        'value' => isset($options['theme']['social']['pinterest']) ? $options['theme']['social']['pinterest'] : '',
        'description' => __('The url to your Pinterest account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_zingme',
        'name' => 'sb_options[theme][social][zingme]',
        'value' => isset($options['theme']['social']['zingme']) ? $options['theme']['social']['zingme'] : '',
        'description' => __('The url to your Zing Me account.', 'sb-theme')
    );
    array_push($args, $field);
    $field = array(
        'id' => 'sb_theme_social_rss',
        'name' => 'sb_options[theme][social][rss]',
        'value' => isset($options['theme']['social']['rss']) ? $options['theme']['social']['rss'] : '',
        'description' => __('The url for RSS on your site.', 'sb-theme')
    );
    array_push($args, $field);
    $args = apply_filters('sb_theme_social', $args);
    SB_Field::social_field($args);
}

function sb_theme_setting_field_social() {
    sb_theme_add_setting_field('sb_theme_social', __('Social settings', 'sb-theme'), 'sb_theme_social_callback');
}

function sb_theme_no_thumbnail_callback() {
    $options = SB_Option::get();
    $value = isset($options['theme']['thumbnail']) ? $options['theme']['thumbnail'] : '';
    $args = array(
        'id' => 'sb_theme_no_thumbnail',
        'name' => 'sb_options[theme][thumbnail]',
        'value' => $value,
        'description' => __('You can enter url or upload new thumbnail image file.', 'sb-theme')
    );
    SB_Field::media_image($args);
}

function sb_theme_setting_field_no_thumbnail() {
    sb_theme_add_setting_field('sb_theme_no_thumbnail', __('Thumbnail image', 'sb-theme'), 'sb_theme_no_thumbnail_callback');
}