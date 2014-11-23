<?php
$args = SB_Theme::get_search_form_args();
$action = isset($args['action']) ? $args['action'] : home_url('/');
$action = esc_url($action);
$form_class = isset($args['form_class']) ? $args['form_class'] : 'search-form';
$form_class = SB_PHP::add_string_with_space_before($form_class, 'sb-search');
$method = isset($args['method']) ? $args['method'] : 'get';
$label = isset($args['label']) ? $args['label'] : __('Search for', 'sb-theme');
$submit_text = isset($args['submit_text']) ? $args['submit_text'] : __('Search', 'sb-theme');
$placeholder = isset($args['placeholder']) ? $args['placeholder'] : __('Search …', 'sb-theme');
$search_field_class = isset($args['search_field_class']) ? $args['search_field_class'] : 'search-field';
$search_field_class = SB_PHP::add_string_with_space_before($search_field_class, 'sb-search-input');
$search_submit_class = isset($args['search_submit_class']) ? $args['search_submit_class'] : 'search-submit';
$search_submit_class = SB_PHP::add_string_with_space_before($search_submit_class, 'sb-submit-button');
$font_awesome = isset($args['font_awesome']) ? $args['font_awesome'] : false;
if((bool)$font_awesome) {
    $search_submit_class = SB_PHP::add_string_with_space_before($search_submit_class, 'font-awesome');
}
?>
<form action="<?php echo $action; ?>" class="<?php echo $form_class; ?>" method="<?php echo $method; ?>" role="search">
    <label>
        <span class="screen-reader-text"><?php echo $label; ?></span>
        <input type="search" title="<?php echo $label; ?>" name="s" value="" placeholder="<?php echo $placeholder; ?>" class="<?php echo $search_field_class; ?>">
    </label>
    <input type="submit" value="<?php echo $submit_text; ?>" class="<?php echo $search_submit_class; ?>">
</form>