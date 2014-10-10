<?php
function sb_theme_footer_text_callback() {
    $options = SB_Option::get();
    $value = isset($options['theme']['footer_text']) ? $options['theme']['footer_text'] : '';
    $id = 'sb_theme_footer_text';
    $name = 'sb_options[theme][footer_text]';
    SB_Field::rich_editor_field($id, $name, $value, __('The text shows on footer area, you can enter address, phone, email,...', 'sb-theme'));
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
    <p class="description"><?php _e('Drag and drop the widget into right box to active it.', 'sb-theme'); ?></p>
    <?php
}