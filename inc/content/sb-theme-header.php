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
<?php do_action('sb_theme_body_before'); ?>
<!-- // Theme is created by SB Team - laidinhcuongvn@gmail.com - ladiuvn@gmail.com - http://sauhi.com - http://hocwp.net -->
<div class="sb-blog sb-site">
    <div class="sb-blog-container sb-site-container">