<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <?php if(version_compare(get_bloginfo('version'), '4.1', '<')) : ?>
        <title><?php SB_Theme::the_title(); ?></title>
    <?php endif; ?>
    <meta name="viewport" content="width=device-width">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <script>(function(){document.documentElement.className='js'})();</script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
do_action('sb_theme_body_before');
$transform = apply_filters('sb_theme_transform', true);
$site_class = 'sb-blog sb-site';
if(!(bool)$transform) {
    $site_class = SB_PHP::add_string_with_space_before($site_class, 'transform-disabled');
} else {
    $site_class = SB_PHP::add_string_with_space_before($site_class, 'transform');
}
?>
<div id="sb-site" class="<?php echo $site_class; ?>">
    <div class="sb-blog-container sb-site-container">