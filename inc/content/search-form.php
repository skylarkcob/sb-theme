<?php
defined('ABSPATH') or die('Please do not pip me!');

$args = SB_Theme::get_search_form_args();
$action = isset($args['action']) ? $args['action'] : home_url('/');
$action = esc_url($action);
$form_class = isset($args['form_class']) ? $args['form_class'] : 'search-form';
$form_class = SB_PHP::add_string_with_space_before($form_class, 'sb-search');
$method = isset($args['method']) ? $args['method'] : 'get';
$label = isset($args['label']) ? $args['label'] : ('vi' == SB_Core::get_language()) ? 'Tìm kiếm theo từ khóa' : __('Enter keyword for search', 'sb-theme');
$submit_text = isset($args['submit_text']) ? $args['submit_text'] : (('vi' == SB_Core::get_language()) ? 'Tìm kiếm' : __('Search', 'sb-theme'));
if(isset($args['submit_button_text']) && !empty($args['submit_button_text'])) {
    $submit_text = $args['submit_button_text'];
}
$placeholder = isset($args['placeholder']) ? $args['placeholder'] : ('vi' == SB_Core::get_language()) ? 'Nhập từ khóa...' : __('Search...', 'sb-theme');
$search_field_class = isset($args['search_field_class']) ? $args['search_field_class'] : 'search-field';
$search_field_class = SB_PHP::add_string_with_space_before($search_field_class, 'sb-search-input');
$search_submit_class = isset($args['search_submit_class']) ? $args['search_submit_class'] : '';
$search_submit_class = SB_PHP::add_string_with_space_before($search_submit_class, 'sb-submit-button search-submit');
$font_awesome = isset($args['font_awesome']) ? $args['font_awesome'] : false;
$search_icon = isset($args['search_icon']) ? $args['search_icon'] : false;
if($search_icon) {
    $font_awesome = true;
    $submit_text = '&#xf002;';
}
if((bool)$font_awesome) {
    $search_submit_class = SB_PHP::add_string_with_space_before($search_submit_class, 'font-awesome');
}
$fields = isset($args['fields']) ? $args['fields'] : array();
?>
<form action="<?php echo $action; ?>" class="<?php echo $form_class; ?>" method="<?php echo $method; ?>" role="search">
    <span class="screen-reader-text"><?php echo $label; ?></span>
    <input type="search" title="<?php echo esc_attr($label); ?>" name="s" value="" data-placeholder="<?php echo esc_attr($placeholder); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" class="<?php echo esc_attr($search_field_class); ?>">
    <?php foreach($fields as $field) : ?>
        <?php echo $field; ?>
    <?php endforeach; ?>
    <input type="submit" value="<?php echo esc_attr($submit_text); ?>" class="<?php echo esc_attr($search_submit_class); ?>">
</form>