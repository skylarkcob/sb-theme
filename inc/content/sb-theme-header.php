<?php do_action('sb_theme_html_before'); ?>
<!DOCTYPE html>
<html lang="vi" dir="ltr" class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php if(SB_Core::compare_wp_version('4.1', '<')) : ?>
        <title><?php SB_Theme::the_title(); ?></title>
    <?php endif; ?>
	<meta content="width=device-width, initial-scale=1" name="viewport">
    <?php wp_head(); ?>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<script type="text/javascript">(function(){document.documentElement.className='js'})();</script>
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
        <?php do_action('sb_theme_site_container_before'); ?>